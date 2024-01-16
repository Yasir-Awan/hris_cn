<?php
defined('BASEPATH') or exit('No direct script access allowed');
class AddUserModel extends CI_Model
{
    public function add_user($user)
    {
        // echo "<pre>"; print_r($user); exit;
        $password = sha1($user['password']);
        $userData = array(
            'fname' => $user['fname'],
            'lname' => $user['lname'],
            'email' => $user['email'],
            'address'=>$user['address'],
            'consultant_name' => $user['consultant'],
            'contact' => $user['contact'],
            'employee_field' => $user['empField'],
            'employee_role' => $user['empRole'],
            'employee_section' => $user['empSec'],
            'employee_type' => $user['empType'],
            'password' => $password,
            'site'=> $user['site'],

        );
        $query = $this->db->insert('user_detail',$userData);
        // return $query->result_array();
    }
}
