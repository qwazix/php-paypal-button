<?php
/**
 * A class that includes configuration values
 *
 * @author qwazix
 */

class Settings {
    static $receipts_directory = 'receipts';
    static $allow_download_for_minutes = 300;
    static $max_downloads = 2;
    static $pp_hostname = "www.sandbox.paypal.com"; // Change to www.sandbox.paypal.com to test against sandbox
    static $auth_token = "xxxxxxxxxxxxxxxxxxxxxxxx"; //your PDT identity token (My Profile > Website payment preferences)
}
