<?php
defined('BASEPATH') or exit('No direct script access allowed');
class GetEmployeesModel extends CI_Model
{
    public function get_employees_list()
    {
        $query = $this->db->order_by('fullname', 'asc')->get('vw_userlist');
        return $query->result_array();
    }
}
