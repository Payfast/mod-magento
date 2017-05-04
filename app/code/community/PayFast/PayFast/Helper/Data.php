<?php
/**
 * Data.php
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
 * PayFast_PayFast_Helper_Data
 */
class PayFast_PayFast_Helper_Data extends Mage_Payment_Helper_Data
{
    // {{{ getPendingPaymentStatus()
    /**
     * getPendingPaymentStatus
     */
    public function getPendingPaymentStatus()
    {
        if( version_compare( Mage::getVersion(), '1.4.0', '<' ) )
            return( Mage_Sales_Model_Order::STATE_HOLDED );
        else
            return( Mage_Sales_Model_Order::STATE_PENDING_PAYMENT );
    }
    // }}}
}
