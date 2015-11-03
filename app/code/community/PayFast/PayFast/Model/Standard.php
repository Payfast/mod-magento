<?php
/**
 * Standard.php
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

/**
 * PayFast_PayFast_Model_Standard
 */
class PayFast_PayFast_Model_Standard extends Mage_Payment_Model_Method_Abstract
{
	protected $_code = 'payfast';
	protected $_formBlockType = 'payfast/form';
	protected $_infoBlockType = 'payfast/payment_info';
	protected $_order;
	
	protected $_isGateway              = true;
	protected $_canAuthorize           = true;
	protected $_canCapture             = true;
	protected $_canCapturePartial      = false;
	protected $_canRefund              = false;
	protected $_canVoid                = true;
	protected $_canUseInternal         = true;
	protected $_canUseCheckout         = true;
	protected $_canUseForMultishipping = true;
	protected $_canSaveCc			   = false;

    // {{{ getCheckout()
    /**
     * getCheckout
     */
	public function getCheckout()
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
    // {{{ getQuote()
    /**
     * getQuote
     */
	public function getConfig()
    {
        return Mage::getSingleton( 'payfast/config' );
    }
    // }}}
    // {{{ getOrderPlaceRedirectUrl()
    /**
     * getOrderPlaceRedirectUrl
     */
	public function getOrderPlaceRedirectUrl()
	{
		return Mage::getUrl( 'payfast/redirect/redirect', array( '_secure' => true ) );
	}
    // }}}
    // {{{ getPaidSuccessUrl()
    /**
     * getPaidSuccessUrl
     */
	public function getPaidSuccessUrl()
	{
		return Mage::getUrl( 'payfast/redirect/success', array( '_secure' => true ) );
	}
    // }}}
    // {{{ getPaidCancelUrl()
    /**
     * getPaidCancelUrl
     */
	public function getPaidCancelUrl()
	{
		return Mage::getUrl( 'payfast/redirect/cancel', array( '_secure' => true ) );
	}
    // }}}
    // {{{ getPaidNotifyUrl()
    /**
     * getPaidNotifyUrl
     */
	public function getPaidNotifyUrl()
	{
		return Mage::getUrl( 'payfast/notify', array( '_secure' => true ) );
	}
    // }}}
    // {{{ getRealOrderId()
    /**
     * getRealOrderId
     */
	public function getRealOrderId()
    {
        return Mage::getSingleton( 'checkout/session' )->getLastRealOrderId();
    }
    // }}}
    // {{{ getNumberFormat($number)
    /**
     * getNumberFormat
     */
	public function getNumberFormat( $number )
    {
        return number_format( $number, 2, '.', '' );
    }
    // }}}
    // {{{ getTotalAmount()
    /**
     * getTotalAmount
     */
	public function getTotalAmount( $order )
    {
		if( $this->getConfigData( 'use_store_currency' ) )
            $price = $this->getNumberFormat( $order->getGrandTotal() );
    	else
        	$price = $this->getNumberFormat( $order->getBaseGrandTotal() );

		return $price;
	}
    // }}}
    // {{{ getStoreName()
    /**
     * getStoreName
     */
	public function getStoreName()
    {
		$store_info = Mage::app()->getStore();
		return $store_info->getName();
	}
    // }}}
    // {{{ getStandardCheckoutFormFields()
    /**
     * getStandardCheckoutFormFields
     */
	public function getStandardCheckoutFormFields()
	{
		// Variable initialization
        $orderIncrementId = $this->getCheckout()->getLastRealOrderId();
        $order = Mage::getModel( 'sales/order' )->loadByIncrementId( $orderIncrementId );
		$description = '';
		
        // If NOT test mode, use normal credentials
        if( $this->getConfigData( 'server' ) == 'live' )
        {
            $merchantId = $this->getConfigData( 'merchant_id' );
            $merchantKey = $this->getConfigData( 'merchant_key' );
        }
        // If test mode, use generic sandbox credentials
        else
        {
            $merchantId = '10000100';
            $merchantKey = '46f0cd694581a';
        }
        
        // Create description
        foreach( $order->getAllItems() as $items )
        {
			$totalPrice = $this->getNumberFormat( $items->getQtyOrdered() * $items->getPrice() );
			$description .=
                $this->getNumberFormat( $items->getQtyOrdered() ) .
                ' x '. $items->getName() .'; ';
		}

        $pfDescription = substr( $description, 0, 254 );
		
        // Construct data for the form
        $data = array(
            // Merchant details
            'merchant_id' => $merchantId,
            'merchant_key' => $merchantKey,
            'return_url' => $this->getPaidSuccessUrl(),
            'cancel_url' => $this->getPaidCancelUrl(),
            'notify_url' => $this->getPaidNotifyUrl(),
            
            // Buyer details
            'name_first' => $order->getData( 'customer_firstname' ),
            'name_last' => $order->getData( 'customer_lastname' ),
            'email_address' => $order->getData( 'customer_email' ),

            // Item details
            'm_payment_id' => $this->getRealOrderId(),
            'amount' => $this->getTotalAmount( $order ),
            'item_name' => $this->getStoreName().', Order #'.$this->getRealOrderId(),
            'item_description' => $pfDescription,
        );

        $pfOutput = '';
        // Create output string
        foreach( $data as $key => $val )
            $pfOutput .= $key .'='. urlencode( trim( $val ) ) .'&';
    
        $passPhrase = $this->getConfigData( 'passphrase');

        if( empty( $passPhrase ) || $this->getConfigData( 'server' ) != 'live' )
        {
            $pfOutput = substr( $pfOutput, 0, -1 );
        }
        else
        {
            $pfOutput = $pfOutput."passphrase=".urlencode( $passPhrase );
        }

        $pfSignature = md5( $pfOutput );
        $data['signature'] = $pfSignature;
        $data['user_agent'] = 'Magento 1.9';
        
		return( $data );
	}
    // }}}
    // {{{ initialize()
    /**
     * initialize
     */
    public function initialize( $paymentAction, $stateObject )
    {
        $state = Mage_Sales_Model_Order::STATE_PENDING_PAYMENT;
        $stateObject->setState( $state );
        $stateObject->setStatus( 'pending_payment' );
        $stateObject->setIsNotified( false );
    }
    // }}}
    // {{{ getPayFastUrl()
    /**
     * getPayFastUrl
     * 
     * Get URL for form submission to PayFast.
     */
	public function getPayFastUrl()
    {
		switch( $this->getConfigData( 'server' ) )
        {
			case 'test':
				$url = 'https://sandbox.payfast.co.za/eng/process';
                break;
			case 'live':
			default :
				$url = 'https://www.payfast.co.za/eng/process';
                break;
		}
        
		return( $url );
    }
    // }}}
}