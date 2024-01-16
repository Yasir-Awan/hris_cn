<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CorsMiddleware {

    protected $CI;

    public function __construct() {
        $this->CI =& get_instance();
    }

    public function setHeaders() {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
    }
}
