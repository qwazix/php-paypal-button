<?php

/*
 * This checks if the file should be downloaded and 
 * presents it to the user for download.
 * 
 *
 */

require_once './Order.php';
require_once './PayPal.php';

$tx = $_GET['tx'];

$order = new Order($tx);

if (!$order->verify_download())
    echo "Maximum download limit reached";
