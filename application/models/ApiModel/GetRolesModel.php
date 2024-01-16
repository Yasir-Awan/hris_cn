<?php
defined('BASEPATH') or exit('No direct script access allowed');


class GetRolesModel extends CI_Model
{
    public function associatedModules($role_id){
        $this->db->where('id', $role_id);
        $query = $this->db->get('vw_modules_roles');
        return $query->result_array();
    }
    public function associatedEmployees($role_id){
        $this->db->where('id', $role_id);
        $this->db->order_by('employee_name', 'asc');
        $query = $this->db->get('vw_employees_roles');
        return $query->result_array();
    }
    public function associatedSites($role_id){
        $this->db->where('id', $role_id);
        $this->db->order_by('site_name', 'asc');
        $query = $this->db->get('vw_roles_sites');
        return $query->result_array();
    }
    public function get_roles_list()
    {
        $result = array();
        $query = $this->db->get('tbl_roles');
        $roles = $query->result_array();
        foreach($roles as $key=>$role){
            $result[$key] = $role;
            $result[$key]['modules'] = $this->associatedModules($role['id']);
            $result[$key]['employees'] = $this->associatedEmployees($role['id']);
            $result[$key]['sites'] = $this->associatedSites($role['id']);
        }
        return $result;
    }
}
