<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
class GetModules extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ApiModel/Forms/FilterLists/GetModulesModel');

    }
    public function index_get()
    {
        $headers = apache_request_headers();
        $head = explode(" ", $headers['Authorization']);

        $token = $head[1];

        try {
            $this->load->helper('verifyAuthToken');
            $verifiedToken = verifyToken($token);
            if($verifiedToken){
                $module = new GetModulesModel;
                    $module_info = $module->get_modules_list();
                    $resp = array('module_info' => $module_info);
                    $this->response($resp, 200);
            }
        }
        catch(Exception $e){
            $error = array("status"=>401,
            "message"=>"Invalid Token Provided",
            "success"=>"false"
        );
            $this->response($error);
        }
    }
}
