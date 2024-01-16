<?php
defined('BASEPATH') or exit('No direct script access allowed');
class SiteRolesModel extends CI_Model
{
    public function get_roles_list($site)
    {
            // $this->db->group_by(array('site', 'role'));
            $this->db->select('role, role_name');
            $this->db->where('site', $site);
            $this->db->from('vw_userlist');
            $this->db->group_by(array('site', 'role'));
            $query = $this->db->get();
            return $query->result_array();

    }
}
