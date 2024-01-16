<?php
defined('BASEPATH') or exit('No direct script access allowed');
class SiteEmployeesModel extends CI_Model
{
    public function get_employees_list($site)
    {

            $this->db->where('site', $site);
            $query = $this->db->get('vw_userlist');
            return $query->result_array();
        
    }
}
