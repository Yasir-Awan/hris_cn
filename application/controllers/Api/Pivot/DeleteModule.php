<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
class DeleteModule extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ApiModel/Pivot/DeleteModuleModel');
    }
    public function index_post()
    {
        $module_id = $this->post('module_id');
        $role_id = $this->post('role_id');
        $headers = apache_request_headers();
        $head = explode(" ", $headers['Authorization']);
        $token = $head[1];

        try {
            $this->load->helper('verifyAuthToken');
            $verifiedToken = verifyToken($token);
            if($verifiedToken){
                    $DeleteModule = new DeleteModuleModel;
                    $moduleInfo = $DeleteModule->DeleteModule($module_id,$role_id);
                        $resp = array('msg'=>'module deleted','status'=>'200');
                        $this->response($resp, 200);
            }
        }
        catch(Exception $e){
            $error = array("status"=>401,"message"=>"Invalid Token Provided","success"=>"false");
            $this->response($error);
        }
    }
}
