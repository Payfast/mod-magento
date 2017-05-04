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
 * PayFast_PayFast_Block_Payment_Info 
 */
class PayFast_PayFast_Block_Payment_Info extends Mage_Payment_Block_Info
{
    // {{{ _prepareSpecificInformation()
    /**
     * _prepareSpecificInformation 
     */
    protected function _prepareSpecificInformation( $transport = null )
    {
        $transport = parent::_prepareSpecificInformation( $transport );
        $payment = $this->getInfo();
        $pfInfo = Mage::getModel( 'payfast/info' );
        
        if( !$this->getIsSecureMode() )
            $info = $pfInfo->getPaymentInfo( $payment, true );
        else
            $info = $pfInfo->getPublicPaymentInfo( $payment, true );

        return( $transport->addData( $info ) );
    }
    // }}}
}