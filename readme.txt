PayFast Magento v1.4-1.7 Module v1.1 for Magento v1.4-1.9
-----------------------------------------------------------------------------
Copyright (c) 2008 PayFast (Pty) Ltd
You (being anyone who is not PayFast (Pty) Ltd) may download and use this plugin / code in your own website in conjunction with a registered and active PayFast account. If your PayFast account is terminated for any reason, you may not use this plugin / code or part thereof.
Except as expressly indicated in this licence, you may not use, copy, modify or distribute this plugin / code or part thereof in any way.

INTEGRATION:
1. Unzip the module to a temporary location on your computer
2. Copy the “app” folder from the archive to your base “magento” folder (using FTPprogram or similar)
- This should NOT overwrite any existing files or folders and merely supplement them with the PayFast files
- This is however, dependent on the FTPprogram you use
3. Login to the Magento Administrator console
4. Using the main menu, navigate to System ? Cache Management
5. Click “Select All” at the top left of the “Cache Storage Management” table
6. Select “Refresh” from the “Actions” drop-down list at the top right of the table and click “Submit”
7. Using the main menu, navigate to System ? Configuration
8. Using the left menu, navigate to Sales ? Payment Methods
9. Enter the following details under the “PayFast” heading:
10. Enabled = Yes
11. Merchant ID = Integration page>
12. Merchant Key = Integration page>
13. Server = Test
14. Debugging = No
15. Click “Save Config”
16. The module is now and ready to be tested with the Sandbox. To test with the sandbox, use the following login credentials when redirected to the PayFast site:
- Username: sbtu01@payfast.co.za
- Password: clientpass

I”m ready to go live! What do I do?
In order to make the module “LIVE”, follow the instructions below:

1. Login to the Magento Administrator console
2. Using the main menu, navigate to System ? Configuration
3. Using the left menu, navigate to Sales ? Payment Methods
4. Under the “PayFast” heading, change the “Server” setting to “Live”
5. Click “Save Config”

******************************************************************************
*                                                                            *
*    Please see the URL below for all information concerning this module:    *
*                                                                            *
*                   https://www.payfast.co.za/shopping-carts/magento/        *
*                                                                            *
******************************************************************************
