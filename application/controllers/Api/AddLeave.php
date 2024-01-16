<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
class AddLeave extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ApiModel/AddLeaveModel');
    }
    public function index_post()
    {
        date_default_timezone_set('Asia/Karachi');
        $startDate = $this->post('start_date');
        $lvStartTimeStamp = strtotime($startDate); // Convert the string to a timestamp
        $leaveStart = date('Y-m-d H:i:s', $lvStartTimeStamp);

        $endDate = $this->post('end_date');
        $lvEndTimeStamp = strtotime($endDate); // Convert the string to a timestamp
        $leaveEnd = date('Y-m-d H:i:s', $lvEndTimeStamp); 

        $leave_type = $this->post('leave_type');
        $shortHrs = null;
        $totalLeaveDays = null;
        $saturdays = 0;
        $sundays = 0;

        $leave_reason = $this->post('leave_reason');
        $bio_id = $this->post('user_bio_id');

        $timestamp = time(); // Get the current timestamp
        $addDateTime = date('Y-m-d H:i:s', $timestamp); 
        $headers = apache_request_headers();
        $head = explode(" ", $headers['Authorization']);
        $token = $head[1];

        try {
            $this->load->helper('verifyAuthToken');
            $verifiedToken = verifyToken($token);
            if($verifiedToken){
                    if($leave_type == 1){
                        $timestamp1 = $leaveStart; // Replace this with your first timestamp
                        $timestamp2 = $leaveEnd; // Replace this with your second timestamp
                        // Convert timestamps to DateTime objects
                        $dateTime1 = new DateTime($timestamp1);
                        $dateTime2 = new DateTime($timestamp2);
                        // Calculate the difference in seconds
                        $shortTime = $dateTime2->getTimestamp() - $dateTime1->getTimestamp();
                        // Calculate hours, minutes, and seconds
                        $hours = floor($shortTime / 3600);
                        $minutes = floor(($shortTime % 3600) / 60);
                        $seconds = $shortTime % 60;
                        // Format as 'HH:MM:SS'
                        $shortHrs = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
                    }
                    if($leave_type != 1){
                        $dateTime1 = new DateTime($leaveStart);
                        $dateTime2 = new DateTime($leaveEnd);
                        $interval = new DateInterval('P1D'); // 1 day interval
                        $dateRange = new DatePeriod($dateTime1, $interval, $dateTime2);

                        foreach ($dateRange as $date) {
                            $dayOfWeek = $date->format('N'); // 1 (Monday) to 7 (Sunday)
                            if ($dayOfWeek == 6) { // Saturday
                                $saturdays++;
                            } elseif ($dayOfWeek == 7) { // Sunday
                                $sundays++;
                            }
                        }
                        $interval = $dateTime2->diff($dateTime1);
                        $totalLeaveDays = $interval->days+1;
                    }
                    $NewLeave = new AddLeaveModel;
                    $leaveInfo = $NewLeave->InsertLeave($leaveStart,$leaveEnd,$bio_id,$leave_type,$leave_reason,$addDateTime,$saturdays,$sundays,$totalLeaveDays,$shortHrs);
                        $resp = array('msg'=>'leave inserted','status'=>'200');
                        $this->response($resp, 200);
            }
        }
        catch(Exception $e){
            $error = array("status"=>401,"message"=>"Invalid Token Provided","success"=>"false");
            $this->response($error);
        }
    }
}
