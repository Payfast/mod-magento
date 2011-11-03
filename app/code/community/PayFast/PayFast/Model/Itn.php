<?php
/**
 * Itn.php
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
 * Mage_Paypal_Model_Itn
 */
class Mage_Paypal_Model_Itn
{
    // {{{ getWriteLog()
    /**
     * getWriteLog
     */
	public function getWriteLog( $data )
    {
		$text = "\n";
		$text .= "RESPONSE: From PayFast[". date("Y-m-d H:i:s") ."]"."\n";
		
        foreach( $_REQUEST as $key => $val )
			$text .= $key."=>".$val."\n";

		$file = dirname( dirname( __FILE__ ) ) ."/Logs/notify.txt";
		
		$handle = fopen( $file, 'a' );
		fwrite( $handle, $text );
		fclose( $handle );
	}
    // }}}
}