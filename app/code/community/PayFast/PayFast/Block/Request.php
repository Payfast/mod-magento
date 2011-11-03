<?php
/**
 * Request.php
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