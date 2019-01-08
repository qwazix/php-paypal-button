<?php

/**
 * Class order represents one purchase.
 * 
 * It stores receipts, verifies payment status and
 * points the user to the downloadable file
 *
 * @author qwazix
 */
require_once './Inventory.php';
require_once './Settings.php';


class Order {
    var $inventory;
    
    function __construct($txn_id) {
        $this->inventory = new Inventory();
        if (!file_exists(Settings::$receipts_directory)) mkdir(Settings::$receipts_directory);
        if (isset($txn_id) && $txn_id != "") {
            $this->txn_id = $txn_id;
            $this->filename = Settings::$receipts_directory.'/'.$this->txn_id;
        }
        if (!$this->load()){
            $pp = new PayPal($txn_id);
            $string = $pp->ask();
            
            $lines = explode("\n", trim($string));
            $keyarray = array();
            if (strcmp($lines[0], "SUCCESS") == 0) {
                for ($i = 1; $i < count($lines); $i++) {
                    $temp = explode("=", $lines[$i], 2);
                    $keyarray[urldecode($temp[0])] = urldecode($temp[1]);
                }
                $this->status = "SUCCESS";
                $this->downloads = 0;
                foreach ($keyarray as $key => $value) {
                    $key = utf8_encode($key);
                    $value = utf8_encode($value);
                    $this->{$key} = $value;
                }
                $this->payment_date_text = $this->payment_date;
                $this->payment_date = strtotime($keyarray['payment_date']);
            } else {
                $this->status = "FAIL";
            }
        } 
    }
    
    function load() {
        if (isset($this->filename) && file_exists($this->filename)){
            $keyarray = json_decode(file_get_contents($this->filename));
            foreach ($keyarray as $key => $value) {
                $this->{$key} = $value;
            }
            return true;
        } else {
            return false;
        } 
    }
    
    function save() {
        if ($this->status == "SUCCESS"){
            file_put_contents(Settings::$receipts_directory.'/'.$this->txn_id, json_encode($this,JSON_PRETTY_PRINT));
        }
    }
    
    function download_filename() {
        if (!is_a($this->inventory,"Inventory")) $this->inventory = new Inventory();
        return $this->inventory->getItem($this->item_number);
    }
    
    function download(){
        $content_type = mime_content_type($this->download_filename());
        header("Content-type: ".$content_type);
        header('Content-Disposition: attachment; filename="'.basename($this->download_filename()).'"');
        $this->downloads++;
        readfile($this->download_filename());
        $after = $this->inventory->after($this->item_number);
        $after($this->txn_id);
        $this->save();
    }
    
    function verify(){
       $minutes_since_payment = (time()-$this->payment_date)/60;
       return $this->status == "SUCCESS" && ($minutes_since_payment < Settings::$allow_download_for_minutes || $this->downloads == 0) && $this->downloads < Settings::$max_downloads;
    }
    
    function verify_download(){  
        if ($this->verify()){
            $this->download();
        }
    }

}
