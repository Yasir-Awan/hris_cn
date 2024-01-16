<?php
defined('BASEPATH') or exit('No direct script access allowed');
class RolesListDropdownModel extends CI_Model
{
    public function get_roles_list()
    {
            $query = $this->db->get('tbl_roles');
            return $query->result_array();
    }
}
