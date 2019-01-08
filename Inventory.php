<?php

/**
 * Inventory contains a list of all items on sale.
 * 
 * We assume that everything is a digital downloads
 * so there is no quantity or availability check
 * 
 * This just returns item locations by id
 *
 * @author qwazix
 */
class Inventory {
    
    var $items = array();
    var $tags = array();
    var $directory = "items";
    
    function __construct(){
        $this->items["testitem"] = "testitem.html";
        
        $this->after["testitem"] = function($tx){
            echo "<!--$tx-->";
        };
    }
    
    function getItem($id){
        return $this->directory."/".$this->items[$id];
    }
    
    function after($id){
        return $this->after[$id];
    }
}
