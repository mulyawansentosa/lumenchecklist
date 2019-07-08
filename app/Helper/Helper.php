<?php

function isJson(){
    return is_string($string) && is_array(json_decode($string, true)) ? true : false;
}    
