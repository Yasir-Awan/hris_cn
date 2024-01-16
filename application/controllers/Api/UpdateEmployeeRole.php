<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
class UpdateEmployeeRole extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ApiModel/UpdateEmployeeRoleModel');
    }
    public function index_post()
    {
        date_default_timezone_set('Asia/Karachi');
        $bioId = $this->post('bio_id');

        $hrRole = $this->post('hr_role');

        $headers = apache_request_headers();
        $head = explode(" ", $headers['Authorization']);
        $token = $head[1];

        try {
            $this->load->helper('verifyAuthToken');
            $verifiedToken = verifyToken($token);
            if($verifiedToken){
                    $UpdateRole = new UpdateEmployeeRoleModel;
                    $empInfo = $UpdateRole->UpdateRole($bioId,$hrRole);
                    // End portion to update the tbl_leaves
                        $resp = array('msg'=>'role updated','status'=>'200');
                        $this->response($resp, 200);
            }
        }
        catch(Exception $e){
            $error = array("status"=>401,"message"=>"Invalid Token Provided","success"=>"false");
            $this->response($error);
        }
    }
}
