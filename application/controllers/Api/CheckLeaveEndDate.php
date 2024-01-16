<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
class CheckLeaveEndDate extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ApiModel/CheckLeaveEndDateModel');
    }
    public function index_post()
    {
        $toDate = $this->post('to_date');
        $clientDate = explode(" ",$toDate);
        $checkDate = $clientDate[0];
        $userId = $this->post('user_id');
        $headers = apache_request_headers();
        $head = explode(" ", $headers['Authorization']);
        $token = $head[1];

        try {
            $this->load->helper('verifyAuthToken');
            $verifiedToken = verifyToken($token);
            if($verifiedToken){
                $leaveStatus = new CheckLeaveEndDateModel;
                    $startDate_leaveInfo = $leaveStatus->get_endDate_leaveStatus($toDate,$userId);
                    if($startDate_leaveInfo===null){
                        $resp = array('msg'=>'no leave found','status'=>'201');
                        $this->response($resp, 200);
                    }else{
                        foreach($startDate_leaveInfo as $row){
                            $leaveSt = $row['start_date'];
                            $leaveEn = $row['end_date'];

                            $leaveStart = explode(" ",$leaveSt);
                            $leaveEnd = explode(" ",$leaveEn);

                            $leaveStartDate = new DateTime($leaveStart[0]);
                            $leaveEndDate = new DateTime($leaveEnd[0]);
                            $check_date = new DateTime($checkDate);

                            if (($check_date >= $leaveStartDate) && ($check_date <= $leaveEndDate)) {
                                $resp = array('startDate_leaveInfo' => $startDate_leaveInfo,
                                'rdAbleStart'=>$leaveStartDate,'rdAbleEnd'=>$leaveEndDate,
                                'msg'=>"The check date is between the start and end dates.",
                                'status'=>'200'
                            );
                                $this->response($resp, 200);
                            } 
                            // else {
                                // $resp = array('msg'=>"The check date is not between the start and end dates.",'status'=>'201');
                            //     $resp = array('startDate_leaveInfo' => $startDate_leaveInfo,
                            //     'rdAbleStart'=>$leaveStartDate,'rdAbleEnd'=>$leaveEndDate,
                            //     'msg'=>"The check date is not between the start and end dates.",
                            //     'status'=>'201'
                            // );
                            //     $this->response($resp, 200);
                            // }
                        }
                    }
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
