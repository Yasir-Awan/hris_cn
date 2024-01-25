<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
class GetEmployees extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ApiModel/Forms/FilterLists/GetEmployeesModel');

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
                $employee = new GetEmployeesModel;
                    $employee_info = $employee->get_employees_list();
                    $resp = array('employee_info' => $employee_info);
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
