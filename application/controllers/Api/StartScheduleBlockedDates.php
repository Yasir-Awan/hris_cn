<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
class StartScheduleBlockedDates extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ApiModel/StartScheduleBlockedDatesModel');
    }

    public function index_post()
    {
        $userId = $this->post('user_id');

        $headers = apache_request_headers();
        $head = explode(" ", $headers['Authorization']);

        $token = $head[1];

        try {
            $this->load->helper('verifyAuthToken');
            $verifiedToken = verifyToken($token);
            if($verifiedToken){
                $BlockedDates = new StartScheduleBlockedDatesModel;
                    $blocked_dates_info = $BlockedDates->get_blocked_dates($userId);
                    // echo "<pre>"; print_r($blocked_dates_info); exit;
                    // if($blocked_dates_info['leaveCount']==0 || $blocked_dates_info['scheduleCount']==0){
                    //     $resp = array('blocked_info' => $blocked_dates_info,'status'=>401);
                    // }
                    // else{
                        $resp = array('blocked_info' => $blocked_dates_info,'status'=>200);
                    // }
                    $this->response($resp, 200);
            }
        }
        catch(Exception $e){
            $error = array("status"=>401,
            "message"=>"Invalid Token Provided",
            "success"=>"false"
        );
            $this->response($error);
        }
    }
}
