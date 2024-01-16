<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
class ScheduleList extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ApiModel/ScheduleListModel');
    }
    public function index_post()
    {
        $page = $this->post('page');
        $filters = $this->post('filters');
        $designation = $this->post('designation');
        $emp_id = $this->post('emp_id');
        $json = json_encode($filters);

        // Assuming the response is stored in a variable called $response
        $data = json_decode($json);
        $items = $data->items;

        $pageSize = $this->post('pageSize');
        if($page==0){
            $start = $page;
        }else{
            $start = ($pageSize * $page)-$pageSize;
        }
        $limit = $pageSize;
        $table = 'vw_schedule';
		$columns = array(
			0 => 'bio_id',
			1 => 'user_name',
			2 => 'from_date',
			3 => 'to_date',
            4 => 'shift_name',
		);

        $headers = apache_request_headers();
        $head = explode(" ", $headers['Authorization']);

        $token = $head[1];

        try {
            $this->load->helper('verifyAuthToken');
            $verifiedToken = verifyToken($token);
            if($verifiedToken){
                if(empty($items)){
                    $schedule = new ScheduleListModel;
                    $total_rows = $schedule->count_records($table,$designation,$emp_id);
                    $schedule_rows = $schedule->schedule_records($limit, $start, $table,$designation,$emp_id);
                    $resp = array('pagesize'=>$pageSize,'page'=>$page,'schedule_rows'=>$schedule_rows,'total_rows'=>$total_rows,'inif'=>'in if');
                    $this->response($resp, 200);
                }
                elseif(empty($items[0]->value)){
                    $schedule = new ScheduleListModel;
                    $total_rows = $schedule->count_records($table,$designation,$emp_id);
                    $schedule_rows = $schedule->schedule_records($limit, $start, $table,$designation,$emp_id);
                    $resp = array('pagesize'=>$pageSize,'page'=>$page,'schedule_rows'=>$schedule_rows,'total_rows'=>$total_rows,'in elseif'=>'in else if');
                    $this->response($resp, 200);
                }
                else{
                    $col = $items[0]->columnField;
                    $operator = $items[0]->operatorValue;
                    $search = $items[0]->value;
                    $schedule = new ScheduleListModel;
                    $total_rows = $schedule->filter_rows_count($search, $col, $table, $operator,$designation,$emp_id);
                    $schedule_rows = $schedule->filter_rows_search($limit, $start, $search, $col, $operator, $table,$designation,$emp_id);
                    $resp = array('pagesize'=>$pageSize,'page'=>$page,'schedule_rows'=>$schedule_rows,'total_rows'=>$total_rows,'inelse'=>'in else');
                    $this->response($resp, 200);
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
