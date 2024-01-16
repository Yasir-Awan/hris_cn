<?php
defined('BASEPATH') or exit('No direct script access allowed');
class AddScheduleModel extends CI_Model
{
    function InsertSchedule($inserting_data)
    {
        // $data = array(
        //     'user_bio_id'=>$userId,
        //     'from_date'=>$startDate,
        //     'to_date'=>$endDate,
        //     'shift_id'=>$shiftId
        // );
        $this->db->insert('tbl_schedules',$inserting_data);

        // if ($query->num_rows() > 0) {
        // return $query->result_array();
        // } else {
        // return null;
        // }
    }

    function FetchLeaves($empId,$startDate,$endDate)
    {
        $this->db->select('*');
        $this->db->from('tbl_leaves');
        $this->db->where('bio_id', $empId);
        $this->db->group_start();
        $this->db->where('start_date BETWEEN ' . $this->db->escape($startDate) . ' AND ' . $this->db->escape($endDate), null, false);
        $this->db->or_where('end_date BETWEEN ' . $this->db->escape($startDate) . ' AND ' . $this->db->escape($endDate), null, false);
        $this->db->group_end();
        $leaves = $this->db->get()->result_array();
        // $leaves = $this->db->$query->result_array();
        // echo "<pre>"; print_r($leaves); exit;
        return $leaves;
    }

    function getShift($shiftId)
    {
        $this->db->where('id', $shiftId);
        $shift = $this->db->get('vw_shifts')->result_array();
        return $shift;
    }
}
