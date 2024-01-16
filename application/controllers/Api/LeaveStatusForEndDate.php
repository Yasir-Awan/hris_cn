<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
class LeaveStatusForEndDate extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ApiModel/LeaveStatusForEndDateModel');
    }
    public function index_post()
    {
        $fromDate = $this->post('from_date');
        $toDate = $this->post('to_date');
        $clientDate = explode(",",$toDate);
        $DatePieces = explode("/",$clientDate[0]);
        $formattedDate = [$DatePieces[2],$DatePieces[0],$DatePieces[1]];

        $checkDate = implode("-", $formattedDate);
        $userId = $this->post('user_id');
        // $resp = array('from' => $fromDate,'to'=>$toDate,'user'=>$userId);
        // $this->response($resp, 200);
        $headers = apache_request_headers();
        $head = explode(" ", $headers['Authorization']);

        $token = $head[1];

        try {
            $this->load->helper('verifyAuthToken');
            $verifiedToken = verifyToken($token);
            if($verifiedToken){
                // $leaveStatus = new LeaveStatusForEndDateModel;
                // $endDate_leaveInfo = $leaveStatus->get_endDate_leaveStatus($fromDate,$toDate,$userId);
                $leaveStatus = new LeaveStatusForEndDateModel;
                $endDate_leaveInfo = $leaveStatus->get_endDate_leaveStatus($toDate,$userId);
                    
                if($endDate_leaveInfo===null){
                    $resp = array('msg'=>'no leave found','status'=>'201');
                    $this->response($resp, 201);
                }else{
                    $leaveSt = $endDate_leaveInfo[0]['start_date'];
                    $leaveEn = $endDate_leaveInfo[0]['end_date'];
                    $leaveStart = explode(" ",$leaveSt);
                    $leaveEnd = explode(" ",$leaveEn);
                    $leaveStartDate = new DateTime($leaveStart[0]);
                    $leaveEndDate = new DateTime($leaveEnd[0]);
                    $check_date = new DateTime($checkDate);

                    if (($check_date >= $leaveStartDate) && ($check_date <= $leaveEndDate)) {
                        $resp = array('endDate_leaveInfo' => $endDate_leaveInfo,
                        'rdAbleStart'=>$leaveStartDate,'rdAbleEnd'=>$leaveEndDate,
                        'msg'=>"The check date is between the start and end dates.",
                        'status'=>'200'
                    );
                        $this->response($resp, 200);
                    } else {
                        $resp = array('msg'=>"The check date is not between the start and end dates.",'status'=>'201');
                        $this->response($resp, 201);
                    }
                }
                // $resp = array('endDate_leaveInfo' => $endDate_leaveInfo);
                //     $this->response($resp, 200);
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
