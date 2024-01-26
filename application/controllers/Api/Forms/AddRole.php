<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
class AddRole extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ApiModel/Forms/AddRoleModel');
    }
    public function index_post()
    {

        $role_name = $this->post('role_name');
        $employees = $this->post('employees');
        $modules = $this->post('modules');
        $sites = $this->post('sites');

        $headers = apache_request_headers();
        $head = explode(" ", $headers['Authorization']);
        $token = $head[1];

        try {
            $this->load->helper('verifyAuthToken');
            $verifiedToken = verifyToken($token);
            if($verifiedToken){

                    $NewRole = new AddRoleModel;
                    $roleInfo = $NewRole->InsertRole($role_name,$employees,$modules,$sites);
                        $resp = array('msg'=>'role inserted','status'=>'200');
                        $this->response($resp, 200);
            }
        }
        catch(Exception $e){
            $error = array("status"=>401,"message"=>"Invalid Token Provided","success"=>"false");
            $this->response($error);
        }
    }
}
