<?php
/**
 * Info.php
 *
 * Copyright (c) 2008 PayFast (Pty) Ltd
 * You (being anyone who is not PayFast (Pty) Ltd) may download and use this plugin / code in your own website in conjunction with a registered and active PayFast account. If your PayFast account is terminated for any reason, you may not use this plugin / code or part thereof.
 * Except as expressly indicated in this licence, you may not use, copy, modify or distribute this plugin / code or part thereof in any way.
 * 
 * @author     Jonathan Smit
 * @link       http://www.payfast.co.za/help/cube_cart
 * @category   PayFast
 * @package    PayFast_PayFast
 */

/**
 * PayFast_PayFast_Model_Info
 */
class PayFast_PayFast_Model_Info
{
    /**
     * Cross-models public exchange keys
     *
     * @var string
     */
    const PAYMENT_STATUS = 'payment_status';
    const M_PAYMENT_ID   = 'm_payment_id';
    const PF_PAYMENT_ID  = 'pf_payment_id';
    const EMAIL_ADDRESS  = 'email_address';
    
    /**
     * All payment information map
     *
     * @var array
     */
    protected $_paymentMap = array(
        self::PAYMENT_STATUS => 'payment_status',
        self::M_PAYMENT_ID   => 'm_payment_id',
        self::PF_PAYMENT_ID  => 'pf_payment_id',
        self::EMAIL_ADDRESS  => 'email_address',
        );

    /**
     * Map of payment information available to customer
     *
     * @var array
     */
	protected $_paymentPublicMap = array(
        'email_address'
        );

    /**
     * Rendered payment map cache
     *
     * @var array
     */
    protected $_paymentMapFull = array();


    // {{{ getPaymentInfo()
    /**
     * getPaymentInfo
     */	
	public function getPaymentInfo( Mage_Payment_Model_Info $payment, $labelValuesOnly = false )
    {
        // Collect PayFast-specific info
        $result = $this->_getFullInfo( array_values( $this->_paymentMap ), $payment, $labelValuesOnly );

        return( $result );
    }
    // }}}
	// {{{ getPublicPaymentInfo
    /**
     * getPublicPaymentInfo
     */
	public function getPublicPaymentInfo( Mage_Payment_Model_Info $payment, $labelValuesOnly = false )
    {
        return $this->_getFullInfo( $this->_paymentPublicMap, $payment, $labelValuesOnly );
    }
    // }}}
    // {{{
    /**
     * Grab data from source and map it into payment
     *
     * @param array|Varien_Object|callback $from
     * @param Mage_Payment_Model_Info $payment
     */
    public function importToPayment( $from, Mage_Payment_Model_Info $payment )
    {
        Varien_Object_Mapper::accumulateByMap( $from, array($payment, 'setAdditionalInformation'), $this->_paymentMap );
    }
    // }}}
    // {{{ exportFromPayment()
    /**
     * exportFromPayment
     * 
     * Grab data from payment and map it into target
     *
     * @param Mage_Payment_Model_Info $payment
     * @param array|Varien_Object|callback $to
     * @param array $map
     * @return array|Varien_Object
     */
    public function &exportFromPayment( Mage_Payment_Model_Info $payment, $to, array $map = null )
    {
        Varien_Object_Mapper::accumulateByMap( array( $payment, 'getAdditionalInformation' ),
            $to, $map ? $map : array_flip( $this->_paymentMap ) );
        
        return( $to );
    }
    // }}}
	// {{{ _getFullInfo()
    /**
     * _getFullInfo
     * 
     * Render info item
     *
     * @param array $keys
     * @param Mage_Payment_Model_Info $payment
     * @param bool $labelValuesOnly
     */
	protected function _getFullInfo( array $keys, Mage_Payment_Model_Info $payment, $labelValuesOnly )
    {
        $result = array();
        
        foreach( $keys as $key )
        {
            if( !isset( $this->_paymentMapFull[$key] ) )
                $this->_paymentMapFull[$key] = array();
            
            if( !isset( $this->_paymentMapFull[$key]['label'] ) )
            {
                if( !$payment->hasAdditionalInformation( $key ) )
                {
                    $this->_paymentMapFull[$key]['label'] = false;
                    $this->_paymentMapFull[$key]['value'] = false;
                }
                else
                {
                    $value = $payment->getAdditionalInformation( $key );
                    $this->_paymentMapFull[$key]['label'] = $this->_getLabel( $key );
                    $this->_paymentMapFull[$key]['value'] = $this->_getValue( $value, $key );
                }
            }
            
            if( !empty( $this->_paymentMapFull[$key]['value'] ) )
            {
                if( $labelValuesOnly )
                    $result[$this->_paymentMapFull[$key]['label']] = $this->_paymentMapFull[$key]['value'];
                else
                    $result[$key] = $this->_paymentMapFull[$key];
            }
        }
        
        return( $result );
    }
    // }}}
	// {{{ _getLabel()
    /**
     * _getLabel
     * 
     * Get the label for the given key.
     * 
     * @param $key String Key to return the label for
     * @return String Label for the given key
     */
	protected function _getLabel( $key )
    {
        // Variable initialization
        $label = '';
        
        switch( $key )
        {
            case 'payment_status':
                $label = Mage::helper( 'payfast' )->__( 'Payment Status' ); break;
            case 'm_payment_id':
                $label = Mage::helper( 'payfast' )->__( 'Payment ID' ); break;
            case 'pf_payment_id':
                $label = Mage::helper( 'payfast' )->__( 'PayFast Payment ID' ); break;
            case 'email_address':
                $label = Mage::helper( 'payfast' )->__( 'Email Address' ); break;
            default:
                $label = ''; break;
        }

        return( $label );
    }
    // }}}
    // {{{ _getValue()
    /**
     * _getValue
     * 
     * Get the value for the given key.
     * 
     * @param $value String Key to return the label for
     * @param $key String Key to return the label for
     * @return String Label for the given key
     */
    protected function _getValue( $value, $key )
    {
        return( $value );
    }
    // }}}
}