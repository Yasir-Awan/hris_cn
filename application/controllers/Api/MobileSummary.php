<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
class MobileSummary extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ApiModel/MobileSummaryModel');
    }
    public function index_post()
    {
        $page = $this->post('page');
        $pageSize = $this->post('pageSize');
        $limit = $pageSize;
        if($page==0){
            $start = $page;
        }else{
            $start = ($pageSize * $page)-$pageSize;
        }
        // $customFilters = $this->post('customFilter');
        // $filters = $this->post('filters');
        $role = $this->post('role');
        if($role == 4)
        $emp_id = $this->post('emp_id');
        else
        $emp_id = $this->post('employees');
        // $json = json_encode($filters);

        //Assuming the response is stored in a variable called $response
        // $data = json_decode($json);
        // $items = $data->items;
        // $this->response($filters, 200);

        $table = 'vw_attendance_summary';
		$columns = array(
			0 => 'id',
			1 => 'fullname',
			2 => 'bio_id',
			3 => 'schedule_start_date',
            4 => 'schedule_end_date',
            5 => 'shift_type',
            6 => 'total_hrs',
            7 => 'hq_hrs',
            8 => 'site_hrs',
            9 => 'total_time',
            10 => 'total_acceptable_time',
		);
        $headers = apache_request_headers();
        $head = explode(" ", $headers['Authorization']);
        $token = $head[1];

        try {
            $this->load->helper('verifyAuthToken');
            $verifiedToken = verifyToken($token);
            if($verifiedToken){
                if(empty($items) && empty($customFilters['filterType'])){
                    $summary = new MobileSummaryModel;
                    $total_rows = $summary->count_rows($table,$role,$emp_id);
                    $attendance_rows = $summary->get_rows($limit, $start, $table,$role,$emp_id);
                    $resp = array('pagesize'=>$pageSize,'page'=>$page,'summary_rows'=>$attendance_rows,'total_rows'=>$total_rows,'inif'=>'in if');
                    $this->response($resp, 200);
                }
                // elseif(empty($items[0]->value) && empty($customFilters['filterType'])){
                //     $summary = new MonthlySummaryModel;
                //     $total_rows = $summary->count_rows($table,$role,$emp_id);
                //     $summary_rows = $summary->get_rows($limit, $start, $table,$role,$emp_id);
                //     $resp = array('custom_filters'=>$customFilters['filterType'],'pagesize'=>$pageSize,'page'=>$page,'summary_rows'=>$summary_rows,'total_rows'=>$total_rows,'filters'=>$items,'in elseif'=>'in else if sab se pehli elseif');
                //     $this->response($resp, 200);
                // }
                // elseif(!empty($customFilters['filterType']) && empty($items[0]->value)){
                //     $summary = new MonthlySummaryModel;
                //     $total_rows = $summary->custom_filter_rows_count($table,$role,$emp_id,$customFilters);
                //     $summary_rows = $summary->custom_filter_rows($limit, $start, $table,$role,$emp_id,$customFilters);

                //     $resp = array('pagesize'=>$pageSize,'page'=>$page,'summary_rows'=>$summary_rows,'total_rows'=>$total_rows,'filters'=>$items,'in elseif'=>'in else if second wali else if');
                //     $this->response($resp, 200);
                // }
                // elseif(!empty($items[0]->value) && empty($customFilters['filterType'])){
                //     $col = $items[0]->columnField;
                //     $operator = $items[0]->operatorValue;
                //     $search = $items[0]->value;
                //     $summary = new MonthlySummaryModel;
                //     $total_rows = $summary->mui_filter_rows_count($search, $col, $table, $operator,$role,$emp_id);
                //     $summary_rows = $summary->mui_filter_rows($limit, $start, $search, $col, $operator, $table,$role,$emp_id);
                //     $resp = array('pagesize'=>$pageSize,'page'=>$page,'summary_rows'=>$summary_rows,'total_rows'=>$total_rows,'filters'=>$items,'in elseif'=>'in else if third wali else if');
                //     $this->response($resp, 200);
                // }
                // else{
                //     $col = $items[0]->columnField;
                //     $operator = $items[0]->operatorValue;
                //     $search = $items[0]->value;
                //     $summary = new MonthlySummaryModel;
                //     $total_rows = $summary->dual_filter_rows_count($search, $col, $table, $operator,$role,$emp_id,$customFilters);
                //     $summary_rows = $summary->dual_filter_rows($limit, $start, $search, $col, $operator, $table,$role,$emp_id,$customFilters);
                //     $resp = array('pagesize'=>$pageSize,'page'=>$page,'summary_rows'=>$summary_rows,'total_rows'=>$total_rows,'filters'=>$items,'inelse'=>'in else');
                //     $this->response($resp, 200);
                // }
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
