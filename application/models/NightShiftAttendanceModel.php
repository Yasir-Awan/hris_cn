<?php
defined('BASEPATH') or exit('No direct script access allowed');
class NightShiftAttendanceModel extends CI_Model
{
    public function get_night_shift_schedule()
    {
        $query = $this->db->where('shift_type',2)->get('vw_schedule')->result_array();
        return $query;
    }
    public function check_employee_attendance_records($userId)
    {
        // $this->db->select('*');
        // $this->db->from('your_table_name');
        // $this->db->order_by("CONCAT(date_column, ' ', time_column)", "DESC");

        // $result = $this->db->get();
        // $query = $this->db->where('bio_id',$userId)->order_by("CONCAT(attendance_date, ' ', check_time)", "DESC")->limit(1)->get('tbl_night_attendance')->result_array();
        // return $query;
        $query = $this->db
            ->where('bio_id', $userId)
            ->order_by("CONCAT(attendance_date, ' ', check_time) DESC")
            ->limit(1)
            ->get('tbl_night_attendance')
            ->result_array();
            return $query; 

    }
    public function get_all_employee_records_from_checkinout($userId,$from_date,$to_date)
    {
        // $night_shift_schedules = $this->db->where('shift_type',2)->get('vw_schedule')->result_array();
        // echo "<pre>"; print_r($night_shift_schedules); exit;
        // echo $ref_id ;
        // if($ref_id){
            // $nextDay = date('Y-m-d', strtotime($night_shift_schedules[0]['schedule_end'] . ' +2 day'));
            // $startingDay = date('Y-m-d', strtotime($night_shift_schedules[0]['schedule_start'] . ' -1 day'));
            // echo $startingDay;
            // echo $nextDay;
            //     $query = $this->db->where('ref_id >',$ref_id)->order_by('ref_id','asc')->get('tbl_checkinouts')->result_array();
        //     return $query;
        // }if(empty($ref_id)){
            $nextDay = date('Y-m-d', strtotime($to_date . ' +2 day'));
            $startingDay = date('Y-m-d', strtotime($from_date . ' -1 day'));
            // $query = array();
            // $query = $this->db->where('userid',$userId)->where('CheckDate >',$startingDay)->where('CheckDate <',$nextDay)->order_by("CONCAT(CheckDate, ' ', CheckTime)", "ASC")->get('tbl_checkinouts')->result_array();
            $query = $this->db
                        ->where('userid', $userId)
                        ->where('CheckDate >', $startingDay)
                        ->where('CheckDate <', $nextDay)
                        ->order_by("CONCAT(CheckDate, ' ', CheckTime) ASC")
                        ->get('tbl_checkinouts')
                        ->result_array();
            // echo "<pre>"; print_r($checkinout_rows); exit;
            return $query;
        // }
    }
    public function get_inlimit_employee_records_from_checkinout($user_id,$attendance_date,$check_time,$schedule_start,$schedule_end)
    {
        $nextDay = date('Y-m-d', strtotime($schedule_end . ' +2 day'));
        $lastTime = $attendance_date.' '.$check_time;
        $startingDay = date('Y-m-d', strtotime($schedule_start . ' -1 day'));

        // Convert dates to timestamps using strtotime
        // $timestamp1 = strtotime($attendance_date);
        // $timestamp2 = strtotime($schedule_start);
        // if ($timestamp1 > $timestamp2){
        //     $query = $this->db
        //             ->where('userid', $user_id)
        //             ->where("CONCAT(CheckDate, ' ', CheckTime) > '$lastTime'", null, false)
        //             ->where("CheckDate < '$nextDay'", null, false)
        //             ->order_by("CONCAT(CheckDate, ' ', CheckTime) ASC")
        //             ->get('tbl_checkinouts')
        //             ->result_array();
        //         return $query;
        // }
        // if ($timestamp2 > $timestamp1){
            $query = $this->db
                    ->where('userid', $user_id)
                    ->where('CheckDate >', $startingDay)
                    ->where('CheckDate <', $nextDay)
                    // ->where("CheckDate >'$startingDay'", null, false)
                    // ->where("CheckDate < '$nextDay'", null, false)
                    ->order_by("CONCAT(CheckDate, ' ', CheckTime) ASC")
                    ->get('tbl_checkinouts')
                    ->result_array();
                return $query;
        // }
        // $query = $this->db->where('userid ',$user_id)->where("CONCAT(CheckDate, ' ', CheckTime) > $lastTime AND CheckDate < $nextDay")->order_by("CONCAT(CheckDate, ' ', CheckTime)", "ASC")->get('tbl_checkinouts')->result_array();
    }
    public function get_checkout_data($emp_id,$attendance_date)
    {
        $query = $this->db->where('userid =',$emp_id)->where('CheckDate',$attendance_date)->where('CheckTime <','12:00:00')->order_by('ref_id','desc')->limit(1)->get('tbl_checkinouts')->result_array();
        return $query;
    }
    public function insert_night_attendance($insertion_data)
    {
        $this->db->insert_batch('tbl_night_attendance',$insertion_data);
        // $query = $this->db->where('userid =',$emp_id)->where('CheckDate',$attendance_date)->where('CheckTime <','12:00:00')->order_by('ref_id','desc')->limit(1)->get('checkinout')->result_array();
        // return $query;
    }
    public function check_ref_id($ref_id)
    {
        $query = $this->db->where('ref_id',$ref_id)->get('tbl_night_attendance')->result_array();
        return $query;
    }
}
