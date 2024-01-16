<?php
defined('BASEPATH') or exit('No direct script access allowed');
class RolesListModel extends CI_Model
{
    public function get_roles_list()
    {
        $this->db->order_by('role_name', 'asc');
        $query = $this->db->get('tbl_employee_roles');
        return $query->result_array();
    }
}
