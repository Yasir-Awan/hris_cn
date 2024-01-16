<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
class AddSchedule extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ApiModel/AddScheduleModel');
    }
    public function index_post()
    {
        $fromDate = $this->post('from_date');
        $sDate = explode(",",$fromDate);
        $sDatePieces = explode("/",$sDate[0]);
        $sFormattedDate = [$sDatePieces[2],$sDatePieces[0],$sDatePieces[1]];
        $startDate = implode("-", $sFormattedDate);

        $toDate = $this->post('to_date');
        $eDate = explode(",",$toDate);
        $eDatePieces = explode("/",$eDate[0]);
        $eFormattedDate = [$eDatePieces[2],$eDatePieces[0],$eDatePieces[1]];
        $endDate = implode("-", $eFormattedDate);

        // Create DateTime objects for the start and end dates
        date_default_timezone_set('Asia/Karachi');

        $start_date = new DateTime($startDate);
        $end_date = new DateTime($endDate);

        $total_days = 0;
        $saturdays = 0;
        $sundays = 0;
        $loop_start_date = clone $start_date;
        // Loop through each day and count the days and weekends
        while ($loop_start_date <= $end_date) {
            // Increment the total days counter
            $total_days++;
            // Check if the current day is a Saturday (6) or Sunday (0)
            $day_of_week = (int)$loop_start_date->format('w');
            if ($day_of_week === 6) {
                $saturdays++;
            } elseif ($day_of_week === 0) {
                $sundays++;
            }
            // Move to the next day
            $loop_start_date->modify('+1 day');
        }

        $userId = $this->post('user_id');
        $selectedUsers = $this->post('selected_users');
        if(count($selectedUsers)===0){
            $selectedUsers[0] = $this->post('user_id');
        }
        else{
            $selectedUsers = $this->post('selected_users');
        }
        $p_h_c = $this->post('p_h_c');
        $p_h_r = $this->post('p_h_r');

        $weekends = $saturdays + $sundays;
        $s_days_only_sundays_included = $total_days - $saturdays;
        $s_days_only_saturdays_included = $total_days - $sundays;
        $s_days_excluded_only_public_holidays = $total_days - $p_h_c;
        $s_days_excluded_only_weekends = $total_days - $weekends;

        $resp = array();
        $leave_status = 0;
        $total_leave_days = null;
        $leave_saturdays = null;
        $leave_sundays = null;
        $short_leave_hrs = null;
        $leave_days_only_saturdays_included = null;
        $leave_days_only_sundays_included = null;
        $leave_days_weekends_included = null;
        $leave_days_weekends_excluded = null;

        $shift_id = $this->post('shift_id');
        $headers = apache_request_headers();
        $head = explode(" ", $headers['Authorization']);

        $token = $head[1];

        try {
            $this->load->helper('verifyAuthToken');
            $verifiedToken = verifyToken($token);
            if($verifiedToken){
                        foreach($selectedUsers as $userId){
                        $NewSchedule = new AddScheduleModel;
                        $employeeLeaves = $NewSchedule->FetchLeaves($userId,$startDate,$endDate);
                        
                        if(!empty($employeeLeaves)){
                                    foreach($employeeLeaves as $row){
                                        $leave_status = $row['status'];
                                        $leaveSDate = explode(" ",$row['start_date']);
                                        $leaveEDate = explode(" ",$row['end_date']);

                                        // Create DateTime objects for the start and end dates
                                        $leave_start_date = new DateTime($leaveSDate[0]);
                                        $leave_end_date = new DateTime($leaveEDate[0]);

                                        if($startDate < $leaveSDate[0] && $leaveEDate[0] < $endDate){
                                            if($row['leave_type']==1){
                                                if($short_leave_hrs == null) $time1 = '00:00:00';
                                                else $time1 = $short_leave_hrs;
                                                $time2 = $row['short_hrs'];
                                                
                                                // Split the times into hours, minutes, and seconds
                                                list($h1, $m1, $s1) = explode(':', $time1);
                                                list($h2, $m2, $s2) = explode(':', $time2);
                                                
                                                // Calculate the total seconds
                                                $total_seconds = ($h1 * 3600 + $m1 * 60 + $s1) + ($h2 * 3600 + $m2 * 60 + $s2);
                                                
                                                // Calculate the new hours, minutes, and seconds
                                                $new_hours = floor($total_seconds / 3600);
                                                $total_seconds %= 3600;
                                                $new_minutes = floor($total_seconds / 60);
                                                $new_seconds = $total_seconds % 60;
                                                // Format the result
                                                $short_leave_hrs = sprintf('%02d:%02d:%02d', $new_hours, $new_minutes, $new_seconds);
                                            }
                                            else{
                                                // Loop through each day and count the days and weekends
                                                while ($leave_start_date <= $leave_end_date) {
                                                    // Increment the total days counter
                                                    $total_leave_days++;
                                                    // Check if the current day is a Saturday (6) or Sunday (0)
                                                    $day_of_week = (int)$leave_start_date->format('w');
                                                    if ($day_of_week === 6) {
                                                        $leave_saturdays++;
                                                    } elseif ($day_of_week === 0) {
                                                        $leave_sundays++;
                                                    }
                                                    // Move to the next day
                                                    $leave_start_date->modify('+1 day');
                                                }
                                            }
                                        }

                                        if($startDate == $leaveSDate[0] && $leaveEDate[0] == $endDate){
                                            
                                            if($row['leave_type']==1){
                                                if($short_leave_hrs == null) $time1 = '00:00:00';
                                                else $time1 = $short_leave_hrs;
                                                $time2 = $row['short_hrs'];
                                                
                                                // Split the times into hours, minutes, and seconds
                                                list($h1, $m1, $s1) = explode(':', $time1);
                                                list($h2, $m2, $s2) = explode(':', $time2);
                                                
                                                // Calculate the total seconds
                                                $total_seconds = ($h1 * 3600 + $m1 * 60 + $s1) + ($h2 * 3600 + $m2 * 60 + $s2);
                                                
                                                // Calculate the new hours, minutes, and seconds
                                                $new_hours = floor($total_seconds / 3600);
                                                $total_seconds %= 3600;
                                                $new_minutes = floor($total_seconds / 60);
                                                $new_seconds = $total_seconds % 60;
                                                
                                                // Format the result
                                                $short_leave_hrs = sprintf('%02d:%02d:%02d', $new_hours, $new_minutes, $new_seconds);
                                                // $short_leave_hrs = $row['short_leave_hrs'];
                                            }
                                            else{
                                                // Loop through each day and count the days and weekends
                                                while ($leave_start_date <= $leave_end_date) {
                                                    // Increment the total days counter
                                                    $total_leave_days++;
                                                    // Check if the current day is a Saturday (6) or Sunday (0)
                                                    $day_of_week = (int)$leave_start_date->format('w');
                                                    if ($day_of_week === 6) {
                                                        $leave_saturdays++;
                                                    } elseif ($day_of_week === 0) {
                                                        $leave_sundays++;
                                                    }
                                                    // Move to the next day
                                                    $leave_start_date->modify('+1 day');
                                                }
                                            }
                                        }

                                        if($startDate > $leaveSDate[0] && $endDate < $leaveEDate[0]){
                                                    $total_leave_days = $total_days;
                                                    $leave_saturdays = $saturdays;
                                                    $leave_sundays = $sundays;
                                        }

                                        if($startDate > $leaveSDate[0] && $endDate > $leaveEDate[0]){
                                            $loop_start_date = clone $start_date;

                                            // Loop through each day and count the days and weekends
                                            while ($loop_start_date <= $leave_end_date) {
                                                // Increment the total days counter
                                                $total_leave_days++;
                                                // Check if the current day is a Saturday (6) or Sunday (0)
                                                $day_of_week = (int)$loop_start_date->format('w');
                                                if ($day_of_week === 6) {
                                                    $leave_saturdays++;
                                                } elseif ($day_of_week === 0) {
                                                    $leave_sundays++;
                                                }
                                                // Move to the next day
                                                $loop_start_date->modify('+1 day');
                                            }
                                        }
                                        if($startDate < $leaveSDate[0] && $endDate < $leaveEDate[0]){
                                            $loop_start_date = clone $leave_start_date;
                                            $loop_end_date = clone $end_date;

                                            // Loop through each day and count the days and weekends
                                            while ($loop_start_date <= $loop_end_date) {
                                                // Increment the total days counter
                                                $total_leave_days++;
                                                // Check if the current day is a Saturday (6) or Sunday (0)
                                                $day_of_week = (int)$loop_start_date->format('w');
                                                if ($day_of_week === 6) {
                                                    $leave_saturdays++;
                                                } elseif ($day_of_week === 0) {
                                                    $leave_sundays++;
                                                }
                                                // Move to the next day
                                                $loop_start_date->modify('+1 day');
                                            }
                                    }
                                    }
                                    $leave_days_only_saturdays_included = $total_leave_days-$leave_sundays;
                                    $leave_days_only_sundays_included = $total_leave_days-$leave_saturdays;
                                    $leave_days_weekends_included = $total_leave_days;
                                    $leave_days_weekends_excluded = $total_leave_days-($leave_saturdays+$leave_sundays);
                                    $inserting_data = [
                                        'bio_id' => $userId,
                                        'schedule_start' => $startDate,
                                        'schedule_end' => $endDate,
                                        'total_days'=>$total_days,
                                        'public_holiday_count' => $p_h_c,
                                        'public_holiday_reason' =>$p_h_r,
                                        'shift_id'=>$shift_id,
                                        'saturdays'=>$saturdays,
                                        'sundays'=>$sundays,
                                        'weekends'=>$weekends,
                                        's_days_only_saturdays_included'=>$s_days_only_saturdays_included,
                                        's_days_only_sundays_included'=>$s_days_only_sundays_included,
                                        's_days_excluded_only_public_holidays'=>$s_days_excluded_only_public_holidays,
                                        's_days_excluded_only_weekends'=>$s_days_excluded_only_weekends,
                                        's_days_excluded_ph_weekends'=>$total_days-($weekends+$p_h_c),
                                        'leave_status'=>1,
                                        'total_leave_days'=>$total_leave_days,
                                        'leave_days_only_saturdays_included'=>$leave_days_only_saturdays_included,
                                        'leave_days_only_sundays_included'=>$leave_days_only_sundays_included,
                                        'leave_days_weekends_included'=>$leave_days_weekends_included,
                                        'leave_days_weekends_excluded'=>$leave_days_weekends_excluded,
                                        'short_leave_hrs'=>$short_leave_hrs
                                    ];
            
                                    $startDate_leaveInfo = $NewSchedule->InsertSchedule($inserting_data);
                                    // $resp = array('msg'=>'schedule inserted','status'=>'200');
                                    // $this->response($resp, 200);
                                    $employeeLeaves = array();
                                    $leave_status = 0;
                                    $total_leave_days = null;
                                    $leave_days_only_saturdays_included = null;
                                    $leave_days_only_sundays_included = null;
                                    $leave_days_weekends_included = null;
                                    $leave_days_weekends_excluded = null;
                                    $short_leave_hrs = null;
                        }
                        else{
                                    $inserting_data = [
                                        'bio_id' => $userId,
                                        'schedule_start' => $startDate,
                                        'schedule_end' => $endDate,
                                        'total_days'=>$total_days,
                                        'public_holiday_count' => $p_h_c,
                                        'public_holiday_reason' =>$p_h_r,
                                        'shift_id'=>$shift_id,
                                        'saturdays'=>$saturdays,
                                        'sundays'=>$sundays,
                                        'weekends'=>$weekends,
                                        's_days_only_saturdays_included'=>$s_days_only_saturdays_included,
                                        's_days_only_sundays_included'=>$s_days_only_sundays_included,
                                        's_days_excluded_only_public_holidays'=>$s_days_excluded_only_public_holidays,
                                        's_days_excluded_only_weekends'=>$s_days_excluded_only_weekends,
                                        's_days_excluded_ph_weekends'=>$total_days-($weekends+$p_h_c),
                                        'leave_status'=>$leave_status,
                                        'total_leave_days'=>$total_leave_days,
                                        'leave_days_only_saturdays_included'=>$leave_days_only_saturdays_included,
                                        'leave_days_only_sundays_included'=>$leave_days_only_sundays_included,
                                        'leave_days_weekends_included'=>$leave_days_weekends_included,
                                        'leave_days_weekends_excluded'=>$leave_days_weekends_excluded,
                                        'short_leave_hrs'=>$short_leave_hrs
                                    ];
                                    $total_leave_days = null;
                                    $leave_days_only_saturdays_included = null;
                                    $leave_days_only_sundays_included = null;
                                    $leave_days_weekends_included = null;
                                    $leave_days_weekends_excluded = null;
                                    $short_leave_hrs = null;
                                    $startDate_leaveInfo = $NewSchedule->InsertSchedule($inserting_data);
                        }
                    }
            }
            $resp = array('msg'=>'schedule inserted','status'=>'200');
                                    $this->response($resp, 200);
        }
        catch(Exception $e){
            $this->response($e);
        }
    }
}
