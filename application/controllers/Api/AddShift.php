<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
class AddShift extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ApiModel/AddShiftModel');
    }
    public function index_post()
    {
        $shiftName = $this->post('shift_name');
        $shiftType = $this->post('shift_type');
        $startTime = $this->post('start');
        $endTime = $this->post('end');

        $headers = apache_request_headers();
        $head = explode(" ", $headers['Authorization']);

        $token = $head[1];

        try {
            $this->load->helper('verifyAuthToken');
            $verifiedToken = verifyToken($token);
            if($verifiedToken){
                $NewShift = new AddShiftModel;
                    $startDate_leaveInfo = $NewShift->InsertShift($shiftName,$shiftType,$startTime,$endTime);                
                        $resp = array('msg'=>'Shift inserted','status'=>'200');
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
