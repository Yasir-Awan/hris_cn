<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
class ReportList extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ApiModel/Reporting/ReportListModel');
    }
    public function index_post()
    {
        $page = $this->post('page');
        $customFilters = $this->post('customFilter');
        // echo "<pre>"; print_r($customFilters);
        // $this->response($customFilters->employees, 200);
        // $filters = $this->post('filters');

        // Access the 'employees' array within the 'customFilter' object
        $employees = isset($customFilters['employees']) ? $customFilters['employees'] : [];
        $role = $this->post('role');
        if($role == 4)
        $emp_id = $this->post('emp_id');
        else
        $emp_id = $this->post('employees');
        // $json = json_encode($filters);

        // $data = json_decode($json);
        // $items = $data->items;

        $pageSize = $this->post('pageSize');
        if($page==0){
            $start = $page;
        }else{
            $start = ($pageSize * $page)-$pageSize;
        }
        $limit = $pageSize;
        $table = 'vw_finalized_attendance';

        $headers = apache_request_headers();
        $head = explode(" ", $headers['Authorization']);
        $token = $head[1];

        try {
            $this->load->helper('verifyAuthToken');
            $verifiedToken = verifyToken($token);
            if($verifiedToken){
                if(empty($items) && empty($customFilters['filterType'])){
                    $attendance = new ReportListModel;
                    $total_rows = $attendance->count_assets($table,$role,$emp_id);
                    $attendance_rows = $attendance->asset_allposts($limit, $start, $table,$role,$emp_id);
                    $resp = array('pagesize'=>$pageSize,'page'=>$page,'attendance_rows'=>$attendance_rows,'total_rows'=>$total_rows,'filters'=>$items,'inif'=>'in if');
                    $this->response($resp, 200);
                }
                elseif(empty($items[0]->value) && empty($customFilters['filterType'])){
                    $attendance = new AttendanceListModel;
                    $total_rows = $attendance->count_assets($table,$role,$emp_id);
                    $attendance_rows = $attendance->asset_allposts($limit, $start, $table,$role,$emp_id);
                    $resp = array('custom_filters'=>$customFilters['filterType'],'pagesize'=>$pageSize,'page'=>$page,'attendance_rows'=>$attendance_rows,'total_rows'=>$total_rows,'filters'=>$items,'in elseif'=>'in else if sab se pehli elseif');
                    $this->response($resp, 200);
                }
                elseif(!empty($customFilters['filterType']) ){
                    $attendance = new ReportListModel;
                    $total_rows = $attendance->custom_filter_rows_count($table,$role,$emp_id,$employees,$customFilters);
                    $attendance_rows = $attendance->custom_filter_rows_search($limit, $start, $table,$role,$emp_id,$employees,$customFilters);
                    $resp = array('custom_filters'=>$customFilters['dateRange']['startDate'],'employees'=>$employees,'pagesize'=>$pageSize,'page'=>$page,'attendance_rows'=>$attendance_rows,'total_rows'=>$total_rows,'in elseif'=>'in else if second wali else if');
                    $this->response($resp, 200);
                }
                elseif(!empty($items[0]->value) && empty($customFilters['filterType'])){
                    $col = $items[0]->columnField;
                    $operator = $items[0]->operatorValue;
                    $search = $items[0]->value;
                    $attendance = new AttendanceListModel;
                    $total_rows = $attendance->mui_filter_rows_count($search, $col, $table, $operator,$role,$emp_id);
                    $attendance_rows = $attendance->mui_filter_rows_search($limit, $start, $search, $col, $operator, $table,$role,$emp_id);
                    $resp = array('pagesize'=>$pageSize,'page'=>$page,'attendance_rows'=>$attendance_rows,'total_rows'=>$total_rows,'filters'=>$items,'in elseif'=>'in else if third wali else if');
                    $this->response($resp, 200);
                }
                else{
                    $col = $items[0]->columnField;
                    $operator = $items[0]->operatorValue;
                    $search = $items[0]->value;
                    $attendance = new AttendanceListModel;
                    $total_rows = $attendance->dual_filter_rows_count($search, $col, $table, $operator,$role,$emp_id,$customFilters);
                    $attendance_rows = $attendance->dual_filter_rows_search($limit, $start, $search, $col, $operator, $table,$role,$emp_id,$customFilters);
                    $resp = array('custom_filters'=>$customFilters['dateRange']['startDate'],'pagesize'=>$pageSize,'page'=>$page,'attendance_rows'=>$attendance_rows,'total_rows'=>$total_rows,'filters'=>$items,'inelse'=>'in else');
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
