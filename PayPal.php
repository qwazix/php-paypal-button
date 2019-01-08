<?php

/**
 * PayPal API implementation
 *
 * @author qwazix
 */

class PayPal {

    
    
    var $ch;

    function __construct($tx_token) {
        $req = 'cmd=_notify-synch';

        $req .= "&tx=$tx_token&at=".Settings::$auth_token;

        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, "https://".Settings::$pp_hostname."/cgi-bin/webscr");
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 1);
        //set cacert.pem verisign certificate path in curl using 'CURLOPT_CAINFO' field here,
        //if your server does not bundled with default verisign certificates.
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, array("Host: ".Settings::$pp_hostname));
        
    }
    
    function ask(){
        $this->res = curl_exec($this->ch);
        curl_close($this->ch);
        return $this->res;
    }
    
    function tx_info(){
        return $this->res;
    }

}
