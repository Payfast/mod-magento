<?php
/**
 * Server.php
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
 * PayFast_PayFast_Model_Source_Server
 */
class PayFast_PayFast_Model_Source_Server
{
    // {{{ toOptionArray()
    /**
     * toOptionArray
     */ 
    public function toOptionArray()
    {
        return array(
            array( 'value' => 'test', 'label' => Mage::helper( 'payfast' )->__( 'Test' ) ),
            array( 'value' => 'live', 'label' => Mage::helper( 'payfast' )->__( 'Live' ) ),
        );
    }
    // }}}
}