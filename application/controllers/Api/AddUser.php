<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
class AddUser extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ApiModel/AddUserModel');
    }
    public function index_post()
    {
        $data = json_decode(file_get_contents("php://input"),true);
        // echo "<pre>";
        // print_r($data);
        // exit;
        // echo "</pre>";
        // echo "<pre>"; print_r($_POST);/
        // $user_data = $_POST;
        // echo "<pre>"; print_r($user_data); exit;
        // $jwt = new JWT();
        // $JwtSecretKey = "Mysecretwordshere";
        // $userName = $this->post('username');
        // $password = sha1($this->post('password'));
        $user = new AddUserModel;
        $user->add_user($data);

        // if (!empty($user_info)) {
        //     $data = array(
        //         'user_id' => $user_info[0]['id'],
        //         'site' => $user_info[0]['site'],
        //         'full_name' => $user_info[0]['fname'] . ' ' . $user_info[0]['lname'],
        //         'username' => $user_info[0]['username'],
        //         'contact' => $user_info[0]['contact'],
        //         'role' => $user_info[0]['employee_role'],
        //         'status' => $user_info[0]['status'],
        //     );

        //     $token = $jwt->encode($data, $JwtSecretKey, 'HS256');
        //     $site = $this->db->select('*')->where('id', $user_info[0]['site'])->get('sites')->result_array();

            $resp = array("New User Added");

        //     $this->session->set_userdata('user_id', $user_info[0]['id']);
        //     $this->session->set_userdata('fname', $user_info[0]['fname']);
        //     $this->session->set_userdata('lname', $user_info[0]['lname']);
        //     $this->session->set_userdata('full_name', $user_info[0]['fname'] . ' ' . $user_info[0]['lname']);
        //     $this->session->set_userdata('role', $user_info[0]['employee_role']);
        //     $this->session->set_userdata('site', $user_info[0]['site']);
        //     $this->session->set_userdata('access_token', $token);

            $this->response($resp, 200);

        // } else {
        //     $this->response(['status' => FALSE, 'message' => 'No User Found.'], REST_Controller::HTTP_NOT_FOUND);
        // }
    }
}
