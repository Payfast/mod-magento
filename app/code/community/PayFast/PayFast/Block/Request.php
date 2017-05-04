<?php
/**
 * Request.php
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
 * PayFast_Block_Request 
 */
class PayFast_PayFast_Block_Request extends Mage_Core_Block_Abstract
{
    // {{{ _toHtml()
    /**
     * _toHtml 
     */
    protected function _toHtml()
    {
        $standard = Mage::getModel( 'payfast/standard' );
        $form = new Varien_Data_Form();
        $form->setAction( $standard->getPayFastUrl() )
            ->setId( 'payfast_checkout' )
            ->setName( 'payfast_checkout' )
            ->setMethod( 'POST' )
            ->setUseContainer( true );
        
        foreach( $standard->getStandardCheckoutFormFields() as $field=>$value )
            $form->addField( $field, 'hidden', array( 'name' => $field, 'value' => $value, 'size' => 200 ) );
        
        $html = '<html><body>';
        $html.= $this->__( 'You will be redirected to PayFast in a few seconds.' );
        $html.= $form->toHtml();
		#echo $html;exit;
        $html.= '<script type="text/javascript">document.getElementById( "payfast_checkout" ).submit();</script>';
        $html.= '</body></html>';
        return $html;
    }
    // }}}
}