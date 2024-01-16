<?php
defined('BASEPATH') or exit('No direct script access allowed');
class GetShiftsModel extends CI_Model
{
    public function get_shifts_list()
    {
        $query = $this->db->get('vw_shifts');
        return $query->result_array();
    }
}
