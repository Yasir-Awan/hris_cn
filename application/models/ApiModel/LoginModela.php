<?php
defined('BASEPATH') or exit('No direct script access allowed');
class LoginModel extends CI_Model
{
    public function get_detail($userName, $password)
    {
        $db2 = $this->load->database('database2', TRUE);
        $query = $db2->get_where('users', array('username' => $userName, 'password' => $password));

        if($query->num_rows()>0){
            return $query->result_array();
        }else{
            return "User Not Found!";
        }
        // return $query->result_array();
    }

    public function get_site_detail($site_id)
    {
        $query = $this->db->get_where('tbl_sites', array('id' => $site_id));

        if($query->num_rows()>0){
            return $query->result_array();
        }
        // return $query->result_array();
    }
}
