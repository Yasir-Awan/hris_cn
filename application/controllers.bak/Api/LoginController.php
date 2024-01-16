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

        $password = sha1($this->post('password'));
        $loginModel = new LoginModel;
        $user_info = $loginModel->get_detail($userName, $password);

        try {
            if($user_info!= "User Not Found!"){
                $data = array(
                    'user_id' => $user_info[0]['hr_bio_ref_id'],
                    'site' => $user_info[0]['hr_site'],
                    'full_name' => $user_info[0]['fname'] . ' ' . $user_info[0]['lname'],
                    'email' => $user_info[0]['username'],
                    'contact' => $user_info[0]['contact'],
                    'designation' => $user_info[0]['hr_employee_designation'],
                    'status' => $user_info[0]['status'],
                );

                $token = $jwt->encode($data, $JwtSecretKey, 'HS256');
                $site = $loginModel->get_site_detail($user_info[0]['hr_site']);

                $resp = array(
                    'token' => $token,
                    'user_id' => $user_info[0]['hr_bio_ref_id'],
                    'designation' => $user_info[0]['hr_employee_designation'],
                    'site_id' => $site[0]['id'],
                    'site_name' => $site[0]['name'],
                );

                $user_data = array(
                    'user_id' => $user_info[0]['hr_bio_ref_id'],
                    'fname' => $user_info[0]['fname'],
                    'lname' => $user_info[0]['lname'],
                    'full_name' => $user_info[0]['fname'] . ' ' . $user_info[0]['lname'],
                    'designation' => $user_info[0]['hr_employee_designation'],
                    'site' => $user_info[0]['hr_site'],
                    'access_token' => $token
                );
                // Set CORS headers

                $this->response($user_data, 200);
            }
        }
        catch(Exception $e){
            $error = array("status"=>401,
            "message"=>"Invalid Credentials",
            "success"=>"false"
        );
            $this->response($error);
        }
    }
}