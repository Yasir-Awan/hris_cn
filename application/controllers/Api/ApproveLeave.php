<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
class ApproveLeave extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ApiModel/ApproveLeaveModel');
    }
    public function index_post()
    {
        $leaveId = $this->post('leave_id');
        $headers = apache_request_headers();
        $head = explode(" ", $headers['Authorization']);
        $token = $head[1];

        try {
            $this->load->helper('verifyAuthToken');
            $verifiedToken = verifyToken($token);
            if($verifiedToken){
                $shortHrs = null;
                $ApproveLeave = new ApproveLeaveModel;
                $leave = $ApproveLeave->FetchLeave($leaveId);
                date_default_timezone_set('Asia/Karachi');
                if(empty($leave)){                   
                    $resp = array('msg'=>'leave approved','status'=>'201');
                    $this->response($resp, 201);}
                if(!empty($leave)){
                    $startDate = $leave[0]['start_date'];
                    $lvStartTimeStamp = strtotime($startDate); // Convert the string to a timestamp
                    $leaveStart = date('Y-m-d H:i:s', $lvStartTimeStamp);
            
                    $endDate = $leave[0]['end_date'];
                    $lvEndTimeStamp = strtotime($endDate); // Convert the string to a timestamp
                    $leaveEnd = date('Y-m-d H:i:s', $lvEndTimeStamp); 
            
                    $leave_type = $leave[0]['leave_type'];
                    $bio_id = $leave[0]['bio_id'];

                    if($leave_type == 1){
                        // Format as 'HH:MM:SS'
                        $shortHrs = $leave[0]['short_hrs'];
                    }
                    // if($leave_type != 1){
                    //     $totalLeaveDays = $leave[0]['total_leave_days'];
                    //     $saturdays = $leave[0]['saturdays'];
                    //     $sundays = $leave[0]['sundays'];
                    // }
                    $employee_schedules_info = $ApproveLeave->get_user_schedules($bio_id,$leaveStart,$leaveEnd);

                    // echo "<pre>"; print_r($employee_schedules_info[0]); exit;

                    if(empty($employee_schedules_info)){
                    //    echo $this->db->last_query();
                    //     echo "yasir "; exit;
                    }
                    else{
                        // echo $this->db->last_query();
                        // echo "<pre>"; print_r($employee_schedules_info); exit;
                        // foreach($employee_schedules_info as $row){
                            $scheduleStart = $employee_schedules_info[0]['schedule_start'].' 00:00:00';
                            $scheduleEnd = $employee_schedules_info[0]['schedule_end'].' 00:00:00';
                            $schedule_start = new DateTime($scheduleStart);
                            $schedule_end = new DateTime($scheduleEnd);
                            $leave_start = new DateTime($leaveStart);
                            $leave_end = new DateTime($leaveEnd);
                            $total_leave_days_in_schedule = 0;
                            $leave_saturdays_in_schedule = 0;
                            $leave_sundays_in_schedule = 0;
                            if($employee_schedules_info[0]['schedule_start'] < $leaveStart && $leaveEnd < $employee_schedules_info[0]['schedule_end']){
                                if($leave_type==1){
                                    // echo 'short leave hrs'; exit;
                                    $time1 = $shortHrs;
                                    $time2 = $employee_schedules_info[0]['short_leave_hrs'];
                                    
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
                                    $updatedShortHrs = sprintf('%02d:%02d:%02d', $new_hours, $new_minutes, $new_seconds);
                                    if($employee_schedules_info[0]['leave_status']==0){
                                        $updating_data = [
                                            'short_leave_hrs' => $updatedShortHrs,
                                            'leave_status' => 1
                                        ];
                                    }else{
                                        $updating_data = [
                                            'short_leave_hrs' => $updatedShortHrs,
                                        ];
                                    }
                                    $ApproveLeave->update_schedule($employee_schedules_info[0]['id'],$updating_data);
                                    // break; 
                                }
                                else{
                                    // Loop through each day and count the days and weekends
                                    while ($leave_start <= $leave_end) {
                                        // echo 'leave start and leave end within schedule'; exit;
                                        // Increment the total days counter
                                        $total_leave_days_in_schedule++;
                                        // Check if the current day is a Saturday (6) or Sunday (0)
                                        $day_of_week = (int)$leave_start->format('w');
                                        if ($day_of_week === 6) {
                                            $leave_saturdays_in_schedule++;
                                        } elseif ($day_of_week === 0) {
                                            $leave_sundays_in_schedule++;
                                        }
                                        // Move to the next day
                                        $leave_start->modify('+1 day');
                                    }
                                    $current_weekends_of_leave_in_schedule = $leave_saturdays_in_schedule + $leave_sundays_in_schedule;
                                    $leave_days_only_saturdays_included = $total_leave_days_in_schedule - $leave_sundays_in_schedule;
                                    $leave_days_only_sundays_included = $total_leave_days_in_schedule - $leave_saturdays_in_schedule;
                                    $leave_days_weekends_excluded = $total_leave_days_in_schedule - $current_weekends_of_leave_in_schedule;

                                    $updated_total_leave_days_in_schedule = $employee_schedules_info[0]['total_leave_days'] + $total_leave_days_in_schedule;
                                    $updated_leave_days_only_saturdays_included = $employee_schedules_info[0]['leave_days_only_saturdays_included'] + $leave_days_only_saturdays_included;
                                    $updated_leave_days_only_sunday_included = $employee_schedules_info[0]['leave_days_only_sundays_included'] + $leave_days_only_sundays_included;
                                    $updated_leave_days_weekends_excluded = $employee_schedules_info[0]['leave_days_weekends_excluded'] + $leave_days_weekends_excluded;
                                    
                                    if($employee_schedules_info[0]['leave_status']==0){
                                        $updating_data = [
                                            'total_leave_days' => $updated_total_leave_days_in_schedule,
                                            'leave_days_only_saturdays_included' => $updated_leave_days_only_saturdays_included,
                                            'leave_days_only_sundays_included' => $updated_leave_days_only_sunday_included,
                                            'leave_days_weekends_excluded'=>$updated_leave_days_weekends_excluded,
                                            'leave_status'=>1
                                        ];
                                    }else{
                                        $updating_data = [
                                            'total_leave_days' => $updated_total_leave_days_in_schedule,
                                            'leave_days_only_saturdays_included' => $updated_leave_days_only_saturdays_included,
                                            'leave_days_only_sundays_included' => $updated_leave_days_only_sunday_included,
                                            'leave_days_weekends_excluded'=>$updated_leave_days_weekends_excluded
                                        ];
                                    }
                                    // echo "<pre>"; print_r($updating_data); exit;
                                    $ApproveLeave->update_schedule($employee_schedules_info[0]['id'],$updating_data);
                                    // break;
                                }
                            }
                            if($leaveStart<$employee_schedules_info[0]['schedule_start'] && $employee_schedules_info[0]['schedule_end']<$leaveEnd){
                                // Loop through each day and count the days and weekends
                                // echo "schedule start and schedule end within the leave"; exit;
                                while ($schedule_start <= $schedule_end) {
                                    // Increment the total days counter
                                    $total_leave_days_in_schedule++;
                                    // Check if the current day is a Saturday (6) or Sunday (0)
                                    $day_of_week = (int)$schedule_start->format('w');
                                    if ($day_of_week === 6) {
                                        $leave_saturdays_in_schedule++;
                                    } elseif ($day_of_week === 0) {
                                        $leave_sundays_in_schedule++;
                                    }
                                    // Move to the next day
                                    $schedule_start->modify('+1 day');
                                }
                                $current_weekends_of_leave_in_schedule = $leave_saturdays_in_schedule + $leave_sundays_in_schedule;
                                $leave_days_only_saturdays_included = $total_leave_days_in_schedule - $leave_sundays_in_schedule;
                                $leave_days_only_sundays_included = $total_leave_days_in_schedule - $leave_saturdays_in_schedule;
                                $leave_days_weekends_excluded = $total_leave_days_in_schedule - $current_weekends_of_leave_in_schedule;

                                $updated_total_leave_days_in_schedule = $employee_schedules_info[0]['total_leave_days'] + $total_leave_days_in_schedule;
                                $updated_leave_days_only_saturdays_included = $employee_schedules_info[0]['leave_days_only_saturdays_included'] + $leave_days_only_saturdays_included;
                                $updated_leave_days_only_sunday_included = $employee_schedules_info[0]['leave_days_only_sundays_included'] + $leave_days_only_sundays_included;
                                $updated_leave_days_weekends_excluded = $employee_schedules_info[0]['leave_days_weekends_excluded'] + $leave_days_weekends_excluded;
                                
                                if($employee_schedules_info[0]['leave_status']==0){
                                    $updating_data = [
                                        'total_leave_days' => $updated_total_leave_days_in_schedule,
                                        'leave_days_only_saturdays_included' => $updated_leave_days_only_saturdays_included,
                                        'leave_days_only_sundays_included' => $updated_leave_days_only_sunday_included,
                                        'leave_days_weekends_excluded'=>$updated_leave_days_weekends_excluded,
                                        'leave_status'=>1
                                    ];
                                }else{
                                    $updating_data = [
                                        'total_leave_days' => $updated_total_leave_days_in_schedule,
                                        'leave_days_only_saturdays_included' => $updated_leave_days_only_saturdays_included,
                                        'leave_days_only_sundays_included' => $updated_leave_days_only_sunday_included,
                                        'leave_days_weekends_excluded'=>$updated_leave_days_weekends_excluded
                                    ];
                                }
                                $ApproveLeave->update_schedule($employee_schedules_info[0]['id'],$updating_data);
                                // break;
                            }
                            if($leaveStart<$employee_schedules_info[0]['schedule_start'] && $leaveEnd<$employee_schedules_info[0]['schedule_end']){
                                // echo "<pre>"; print_r($schedule_start);
                                // echo "leave end is between schedule start and schedule end"; exit;
                                // Loop through each day and count the days and weekends
                                while ($schedule_start <= $leave_end) {
                                    // Increment the total days counter
                                    $total_leave_days_in_schedule++;
                                    // Check if the current day is a Saturday (6) or Sunday (0)
                                    $day_of_week = (int)$schedule_start->format('w');
                                    if ($day_of_week === 6) {
                                        $leave_saturdays_in_schedule++;
                                    } elseif ($day_of_week === 0) {
                                        $leave_sundays_in_schedule++;
                                    }
                                    // Move to the next day
                                    $schedule_start->modify('+1 day');
                                }

                                $current_weekends_of_leave_in_schedule = $leave_saturdays_in_schedule + $leave_sundays_in_schedule;
                                $leave_days_only_saturdays_included = $total_leave_days_in_schedule - $leave_sundays_in_schedule;
                                $leave_days_only_sundays_included = $total_leave_days_in_schedule - $leave_saturdays_in_schedule;
                                $leave_days_weekends_excluded = $total_leave_days_in_schedule - $current_weekends_of_leave_in_schedule;

                                $updated_total_leave_days_in_schedule = $employee_schedules_info[0]['total_leave_days'] + $total_leave_days_in_schedule;
                                $updated_leave_days_only_saturdays_included = $employee_schedules_info[0]['leave_days_only_saturdays_included'] + $leave_days_only_saturdays_included;
                                $updated_leave_days_only_sunday_included = $employee_schedules_info[0]['leave_days_only_sundays_included'] + $leave_days_only_sundays_included;
                                $updated_leave_days_weekends_excluded = $employee_schedules_info[0]['leave_days_weekends_excluded'] + $leave_days_weekends_excluded;
                                
                                if($employee_schedules_info[0]['leave_status']==0){
                                    $updating_data = [
                                        'total_leave_days' => $updated_total_leave_days_in_schedule,
                                        'leave_days_only_saturdays_included' => $updated_leave_days_only_saturdays_included,
                                        'leave_days_only_sundays_included' => $updated_leave_days_only_sunday_included,
                                        'leave_days_weekends_excluded'=>$updated_leave_days_weekends_excluded,
                                        'leave_status'=>1
                                    ];
                                }else{
                                    $updating_data = [
                                        'total_leave_days' => $updated_total_leave_days_in_schedule,
                                        'leave_days_only_saturdays_included' => $updated_leave_days_only_saturdays_included,
                                        'leave_days_only_sundays_included' => $updated_leave_days_only_sunday_included,
                                        'leave_days_weekends_excluded'=>$updated_leave_days_weekends_excluded
                                    ];
                                }

                                $ApproveLeave->update_schedule($employee_schedules_info[0]['id'],$updating_data);
                                // break;
                            }
                            if($employee_schedules_info[0]['schedule_start']<$leaveStart && $employee_schedules_info[0]['schedule_end']<$leaveEnd){
                                // echo "leave start is between schedule start and schedule end"; 
                                // Loop through each day and count the days and weekends
                                while ($leave_start <= $schedule_end) {
                                    // Increment the total days counter
                                    $total_leave_days_in_schedule++;
                                    // Check if the current day is a Saturday (6) or Sunday (0)
                                    $day_of_week = (int)$leave_start->format('w');
                                    if ($day_of_week === 6) {
                                        $leave_saturdays_in_schedule++;
                                    } elseif ($day_of_week === 0) {
                                        $leave_sundays_in_schedule++;
                                    }
                                    // Move to the next day
                                    $leave_start->modify('+1 day');
                                }

                                $current_weekends_of_leave_in_schedule = $leave_saturdays_in_schedule + $leave_sundays_in_schedule;
                                $leave_days_only_saturdays_included = $total_leave_days_in_schedule - $leave_sundays_in_schedule;
                                $leave_days_only_sundays_included = $total_leave_days_in_schedule - $leave_saturdays_in_schedule;
                                $leave_days_weekends_excluded = $total_leave_days_in_schedule - $current_weekends_of_leave_in_schedule;

                                $updated_total_leave_days_in_schedule = $employee_schedules_info[0]['total_leave_days'] + $total_leave_days_in_schedule;
                                $updated_leave_days_only_saturdays_included = $employee_schedules_info[0]['leave_days_only_saturdays_included'] + $leave_days_only_saturdays_included;
                                $updated_leave_days_only_sunday_included = $employee_schedules_info[0]['leave_days_only_sundays_included'] + $leave_days_only_sundays_included;
                                $updated_leave_days_weekends_excluded = $employee_schedules_info[0]['leave_days_weekends_excluded'] + $leave_days_weekends_excluded;
                                
                                if($employee_schedules_info[0]['leave_status']==0){
                                    $updating_data = [
                                        'total_leave_days' => $updated_total_leave_days_in_schedule,
                                        'leave_days_only_saturdays_included' => $updated_leave_days_only_saturdays_included,
                                        'leave_days_only_sundays_included' => $updated_leave_days_only_sunday_included,
                                        'leave_days_weekends_excluded'=>$updated_leave_days_weekends_excluded,
                                        'leave_status'=>1
                                    ];
                                }else{
                                    $updating_data = [
                                        'total_leave_days' => $updated_total_leave_days_in_schedule,
                                        'leave_days_only_saturdays_included' => $updated_leave_days_only_saturdays_included,
                                        'leave_days_only_sundays_included' => $updated_leave_days_only_sunday_included,
                                        'leave_days_weekends_excluded'=>$updated_leave_days_weekends_excluded
                                    ];
                                }
                                // echo "<pre>"; print_r($updating_data); exit;
                                $ApproveLeave->update_schedule($employee_schedules_info[0]['id'],$updating_data);
                                // break;
                            }
                        // }
                    }
                }
                    $leaveInfo = $ApproveLeave->ApproveLeave($leaveId);
                        $resp = array('msg'=>'leave approved','status'=>'200');
                        $this->response($resp, 200);
            }
        }
        catch(Exception $e){
            $error = array("status"=>401,"message"=>"Invalid Token Provided","success"=>"false");
            $this->response($error);
        }
    }
}
