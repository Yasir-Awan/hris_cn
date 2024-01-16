<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
class GetRoles extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ApiModel/GetRolesModel');
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
                // $this->load->model('ApiModel/Eloquent/Role');
                // $this->load->model('ApiModel/Eloquent/Module');
                // $this->load->model('ApiModel/Eloquent/Employee');
                // $employee = new Employee;
                // $module = new Module;
                // $role = new Role;
                // $role = $role->find(2); 
                // $output = $role->with('employees','modules')->get();
                // $this->response($output, 200);

                $role = new GetRolesModel;
                    $role_info = $role->get_roles_list();
                    $resp = array('role_info' => $role_info);
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
