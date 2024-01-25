<?php
defined('BASEPATH') or exit('No direct script access allowed');
class GetModulesModel extends CI_Model
{
    public function get_modules_list()
    {
        $query = $this->db->order_by('name', 'asc')->get('tbl_modules');
        return $query->result_array();
    }
}
