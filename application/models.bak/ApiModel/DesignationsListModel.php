<?php
defined('BASEPATH') or exit('No direct script access allowed');
class DesignationsListModel extends CI_Model
{
    public function get_designations_list()
    {
        $this->db->order_by('designation_name', 'asc');
        $query = $this->db->get('tbl_employee_designations');
        return $query->result_array();
    }
}
