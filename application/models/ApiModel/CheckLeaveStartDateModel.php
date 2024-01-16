<?php
defined('BASEPATH') or exit('No direct script access allowed');
class CheckLeaveStartDateModel extends CI_Model
{
    function get_startDate_leaveStatus($fromDate, $userId)
    {
        $this->db->select('*');
        $this->db->from('tbl_leaves');
        $this->db->where('bio_id', $userId);
        $this->db->order_by('id', 'desc');
        $this->db->limit(3);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return null;
        }
    }
}
