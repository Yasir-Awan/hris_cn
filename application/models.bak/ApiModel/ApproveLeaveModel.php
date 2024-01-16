<?php
defined('BASEPATH') or exit('No direct script access allowed');
class ApproveLeaveModel extends CI_Model
{
    function ApproveLeave($leaveId)
    {
        $data = array(
            'status'=>1,
        );
        // echo "<pre>"; print_r($data); exit;
        $this->db->where('id', $leaveId);
        $this->db->group_start(); // Start a group for OR conditions
        $this->db->or_where('status', 2);
        $this->db->or_where('status', 0);
        $this->db->group_end(); // End the group for OR conditions
        $this->db->update('tbl_leaves', $data); 
        // $this->db->update('leave_table',$data)->where('id',$leaveId);

        // if ($query->num_rows() > 0) {
        // return $query->result_array();
        // } else {
        // return null;
        // }
    }

    function update_schedule($schedule_id,$updating_data)
    {       
        // echo "<pre>"; print_r($data); exit;
        $this->db->where('id', $schedule_id);
        $this->db->update('tbl_schedules',$updating_data);

        // return $schedules;
    }

    function FetchLeave($leaveId)
    {
        $this->db->where('id', $leaveId);
        $this->db->group_start(); // Start a group for OR conditions
        $this->db->or_where('status', 2);
        $this->db->or_where('status', 0);
        $this->db->group_end(); // End the group for OR conditions
        $leave = $this->db->get('tbl_leaves')->result_array();
        $rows = count($leave);
        // echo "<pre>"; print_r($rows); exit;
        // $schedule_dates_count = count($schedule_dates);
        // $data = array('leaveCount'=>$leave_dates_count,'leaveDates'=>$leave_dates,'scheduleCount'=>$schedule_dates_count,'scheduleDates'=>$schedule_dates,);
        // $query = $this->db->get('shifts');
        return $leave;
    }

    function get_user_schedules($empId,$leaveStart,$leaveEnd)
    {
        $schedules = $this->db->query("SELECT * FROM `tbl_schedules` WHERE `bio_id` = '$empId' AND ( ( '$leaveStart' >= schedule_start AND '$leaveStart' <= schedule_end) OR ('$leaveEnd' <= schedule_end AND '$leaveEnd' >= schedule_start) );")->result_array();
        return $schedules;
    }
}
