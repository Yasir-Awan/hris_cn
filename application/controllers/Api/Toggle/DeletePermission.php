<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
class DeletePermission extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ApiModel/Toggle/DeletePermissionModel');
    }
    public function index_post()
    {
        $RoleId = $this->post('role_id');
        $headers = apache_request_headers();
        $head = explode(" ", $headers['Authorization']);
        $token = $head[1];

        try {
            $this->load->helper('verifyAuthToken');
            $verifiedToken = verifyToken($token);
            if($verifiedToken){
                $shortHrs = null;
                $DeletePermission = new DeletePermissionModel;
                $role = $DeletePermission->ToggleRole($RoleId);

                    $resp = array('msg'=>'delete permission updated','status'=>'200');
                    $this->response($resp, 200);

        }}
        catch(Exception $e){
            $error = array("status"=>401,"message"=>"Invalid Token Provided","success"=>"false");
            $this->response($error);
        }
    }
}
