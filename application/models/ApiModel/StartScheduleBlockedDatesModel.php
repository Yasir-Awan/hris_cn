<?php
defined('BASEPATH') or exit('No direct script access allowed');
class StartScheduleBlockedDatesModel extends CI_Model
{
    public function get_blocked_dates($user_id)
    {
        // echo $user_id; exit;
        // $this->db->where('bio_id', $user_id);
        // $this->db->where('status', 1);
        // $this->db->order_by('id', 'DESC');
        // $this->db->limit(3);
        // $leave_dates = $this->db->get('vw_leaves')->result_array(); 
        // $leave_dates_count = count($leave_dates);

        $this->db->where('bio_id', $user_id);
        $this->db->order_by('id', 'DESC');
        $this->db->limit(4);
        $schedule_dates = $this->db->get('tbl_schedules')->result_array();
        $schedule_dates_count = count($schedule_dates);
        $data = array('scheduleCount'=>$schedule_dates_count,'scheduleDates'=>$schedule_dates,);
        // $query = $this->db->get('shifts');
        return $data;
    }
}
