<?php
/**
 * RedirectController.php
 * 
 * Copyright (c) 2010-2011 PayFast (Pty) Ltd
 * 
 * LICENSE:
 * 
 * This payment module is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published
 * by the Free Software Foundation; either version 3 of the License, or (at
 * your option) any later version.
 * 
 * This payment module is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser General Public
 * License for more details.
 * 
 * @author     Jonathan Smit
 * @copyright  2010-2011 PayFast (Pty) Ltd
 * @license    http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link       http://www.payfast.co.za/help/cube_cart
 * @category   PayFast
 * @package    PayFast_PayFast
 */

// Include the PayFast common file
define( 'PF_DEBUG', ( Mage::getStoreConfig( 'payment/payfast/debugging' ) ? true : false ) );
include_once( dirname( __FILE__ ) .'/../payfast_common.inc' );
 
/**
 * PayFast_PayFast_RedirectController
 */
class PayFast_PayFast_RedirectController extends Mage_Core_Controller_Front_Action
{
    protected $_order;
	protected $_WHAT_STATUS = false;

    // {{{ getOrder()
    /**
     * getOrder
     */
    public function getOrder()
    {
        return( $this->_order );
    }
    // }}}
    // {{{ _expireAjax()
    /**
     * _expireAjax
     */
    protected function _expireAjax()
    {
        if( !Mage::getSingleton( 'checkout/session' )->getQuote()->hasItems() )
        {
            $this->getResponse()->setHeader( 'HTTP/1.1', '403 Session Expired' );
            exit;
        }
    }
    // }}}
    // {{{ _getCheckout()
    /**
     * _getCheckout
     * 
     * Get singleton of Checkout Session Model
     *
     * @return Mage_Checkout_Model_Session
     */
    protected function _getCheckout()
    {
        return Mage::getSingleton( 'checkout/session' );
    }
    // }}}
    // {{{ getQuote()
    /**
     * getQuote
     */
	public function getQuote()
    {
        return $this->getCheckout()->getQuote();
    }
    // }}}
    // {{{ getStandard()
    /**
     * getStandard()
     */
    public function getStandard()
    {
        return Mage::getSingleton( 'payfast/standard' );
    }
    // }}}
    // {{{ getConfig()
    /**
     * getConfig
     */
	public function getConfig()
    {
        return $this->getStandard()->getConfig();
    }
    // }}}
    // {{{ _getPendingPaymentStatus()
    /**
     * _getPendingPaymentStatus
     */
    protected function _getPendingPaymentStatus()
    {
        return Mage::helper( 'payfast' )->getPendingPaymentStatus();
    }
    // }}}
    // {{{ redirectAction()
    /**
     * redirectAction
     */
    public function redirectAction()
    {
        pflog( 'Redirecting to PayFast' );
        
		try
        {
            $session = Mage::getSingleton( 'checkout/session' );

            $order = Mage::getModel( 'sales/order' );
            $order->loadByIncrementId( $session->getLastRealOrderId() );
        
            if( !$order->getId() )
                Mage::throwException( 'No order for processing found' );
        
            if( $order->getState() != Mage_Sales_Model_Order::STATE_PENDING_PAYMENT )
            {
                $order->setState(
                    Mage_Sales_Model_Order::STATE_PENDING_PAYMENT,
                    $this->_getPendingPaymentStatus(),
                    Mage::helper( 'payfast' )->__( 'Customer was redirected to PayFast.' )
                )->save();
            }

            if( $session->getQuoteId() && $session->getLastSuccessQuoteId() )
            {
                $session->setPayfastQuoteId( $session->getQuoteId() );
                $session->setPayfastSuccessQuoteId( $session->getLastSuccessQuoteId() );
                $session->setPayfastRealOrderId( $session->getLastRealOrderId() );
                $session->getQuote()->setIsActive( false )->save();
                $session->clear();
            }
			
			$this->getResponse()->setBody( $this->getLayout()->createBlock( 'payfast/request' )->toHtml() );
	        $session->unsQuoteId();
            
            return;
        }
        catch( Mage_Core_Exception $e )
        {
            $this->_getCheckout()->addError( $e->getMessage() );
        }
        catch( Exception $e )
        {
            Mage::logException($e);
        }
        
        $this->_redirect( 'checkout/cart' );
    }
    // }}}
    // {{{ cancelAction()
    /**
     * cancelAction
     * 
     * Action for when a user cancel's a payment on PayFast.
     */
    public function cancelAction()
    {
		// Get the user session
        $session = Mage::getSingleton( 'checkout/session' );
        $session->setQuoteId( $session->getPayfastQuoteId( true ) );
		$session = $this->_getCheckout();
        
        if( $quoteId = $session->getPayfastQuoteId() )
        {
            $quote = Mage::getModel( 'sales/quote' )->load( $quoteId );
            
            if( $quote->getId() )
            {
                $quote->setIsActive( true )->save();
                $session->setQuoteId( $quoteId );
            }
        }
		
        // Cancel order
		$order = Mage::getModel( 'sales/order' )->loadByIncrementId( $session->getLastRealOrderId() );
		if( $order->getId() )
            $order->cancel()->save();

        $this->_redirect('checkout/cart');
    }
    // }}}
    // {{{ successAction()
    /**
     * successAction
     */
    public function successAction()
    {
		try
        {
			$session = Mage::getSingleton( 'checkout/session' );;
			$session->unsPayfastRealOrderId();
			$session->setQuoteId( $session->getPayfastQuoteId( true ) );
			$session->setLastSuccessQuoteId( $session->getPayfastSuccessQuoteId( true ) );
			$this->_redirect( 'checkout/onepage/success', array( '_secure' => true ) );
			
            return;
		}
        catch( Mage_Core_Exception $e )
        {
			$this->_getCheckout()->addError( $e->getMessage() );
		}
        catch( Exception $e )
        {
			Mage::logException( $e );
		}
		
        $this->_redirect( 'checkout/cart' );
    }
    // }}}
}