<?php
defined('BASEPATH') or exit('No direct script access allowed');
class SiteRoleEmployeesModel extends CI_Model
{
    public function get_employees_list($site,$role)
    {
            $this->db->where('site', $site);
            $this->db->where('role', $role);
            $query = $this->db->get('vw_userlist');
            return $query->result_array();
        
    }
}
