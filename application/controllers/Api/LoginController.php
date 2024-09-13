<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
class LoginController extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ApiModel/LoginModel');
    }
    public function index_post()
    {
        $jwt = new JWT();
        $JwtSecretKey = "Mysecretwordshere";
        $userName = $this->post('username');

        // $this->response($userName, 200);

        // $password = sha1($this->post('password'));
        $loginModel = new LoginModel;
        $user_info = $loginModel->get_detail($userName);

        try {
            if($user_info!= "User Not Found!"){
                $data = array(
                    'user_id' => $user_info[0]['emp_id'],
                    'site' => $user_info[0]['hr_site'],
                    'full_name' => $user_info[0]['fname'] . ' ' . $user_info[0]['lname'],
                    'email' => $user_info[0]['username'],
                    'contact' => $user_info[0]['contact'],
                    'designation' => $user_info[0]['hr_employee_designation'],
                    'status' => $user_info[0]['status'],
                );

                $token = $jwt->encode($data, $JwtSecretKey, 'HS256');
                $site = $loginModel->get_site_detail($user_info[0]['hr_site']);
                $modules = $loginModel->get_associated_modules($user_info[0]['hr_role']);
                $employees = $loginModel->get_associated_employees($user_info[0]['hr_role'],$user_info[0]['emp_id']);
                $sites = $loginModel->get_associated_sites($user_info[0]['hr_role'],$user_info[0]['hr_site']);
                $permissions = $loginModel->get_associated_permissions($user_info[0]['hr_role']);

                // $resp = array(
                //     'token' => $token,
                //     'user_id' => $user_info[0]['emp_id'],
                //     'designation' => $user_info[0]['hr_employee_designation'],
                //     'site_id' => $site[0]['id'],
                //     'site_name' => $site[0]['name'],
                // );

                $user_data = array(
                    'user_id' => $user_info[0]['emp_id'],
                    'fname' => $user_info[0]['fname'],
                    'lname' => $user_info[0]['lname'],
                    'full_name' => $user_info[0]['fname'] . ' ' . $user_info[0]['lname'],
                    'designation' => $user_info[0]['hr_employee_designation'],
                    'site' => $user_info[0]['hr_site'],
                    'role' => $user_info[0]['hr_role'],
                    'tabNameToIndex' =>$modules['tabNameToIndex'],
                    'indexToTabName' =>$modules['indexToTabName'],
                    'employees' => $employees,
                    'sites'=> $sites,
                    'read_permission'=>$permissions[0]['read_permission'],
                    'write_permission'=>$permissions[0]['write_permission'],
                    'edit_permission'=>$permissions[0]['edit_permission'],
                    'approval_permission'=>$permissions[0]['approval_permission'],
                    'delete_permission'=>$permissions[0]['delete_permission'],
                    'access_token' => $token,
                    "success"=>"true"
                );
                // Set CORS headers

                $this->response($user_data, 200);
            }
        }
        catch(Exception $e){
            $error = array("status"=>'gulam jazz',
            "message"=>$e,
            "success"=>"false"
        );
            $this->response($error);
        }
    }
}