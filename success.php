<?php

/* 
 * Insert your success template here
 * 
 */

require_once './PayPal.php';
require_once './Order.php';
require_once './Inventory.php';

$tx = $_GET['tx'];

$order = new Order($tx);

if ($order->verify()){
    echo "<br> Thanks for purchasing! <br>";
    echo "<a href=download.php?tx=$tx >Download</a><br>";
} else {
    echo "This order has expired.";
}
?>
<br><br> 