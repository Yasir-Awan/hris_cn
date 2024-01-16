<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
class LeavesList extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ApiModel/LeavesListModel');
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
        $filters = $this->post('filters');
        $role = $this->post('role');
        if($role == 4)
        $emp_id = $this->post('emp_id');
        else
        $emp_id = $this->post('employees');
        $json = json_encode($filters);

        //Assuming the response is stored in a variable called $response
        $data = json_decode($json);
        $items = $data->items;
        // $this->response($filters, 200);

        $table = 'vw_leaves';
		$columns = array(
			0 => 'id',
			1 => 'bio_id',
			2 => 'full_name',
			3 => 'leave_type',
            4 => 'leave_type_readable',
            5 => 'leave_status_readable',
            6 => 'start_date',
            7 => 'readable_start_date',
            8 => 'end_date',
            9 => 'readable_end_date',
            10 => 'add_date',
            11 => 'readable_add_date',
            12 => 'reason',
            13 => 'status',
            14 => 'disapprove_reason',
            15 => 'saturdays',
            16 => 'sundays',
            17 => 'weekend_count'
		);
        $headers = apache_request_headers();
        $head = explode(" ", $headers['Authorization']);

        $token = $head[1];

        try {
            $this->load->helper('verifyAuthToken');
            $verifiedToken = verifyToken($token);
            if($verifiedToken){
                $leave = new LeavesListModel;
                    $leave_info = $leave->get_leaves_list($role,$emp_id);
                    $resp = array('leave_info' => $leave_info);
                    $this->response($resp, 200);
                    if(empty($items)){
                        $leave = new LeavesListModel;
                        $total_rows = $leave->count_rows($table,$role,$emp_id);
                        $leave_rows = $leave->get_rows($limit, $start, $table,$role,$emp_id);
                        $resp = array('pagesize'=>$pageSize,'page'=>$page,'leave_rows'=>$leave_rows,'total_rows'=>$total_rows,'filters'=>$items,'inif'=>'in if');
                        $this->response($resp, 200);
                    }
                    elseif(empty($items[0]->value)){
                        $leave = new LeavesListModel;
                        $total_rows = $leave->count_rows($table,$role,$emp_id);
                        $leave_rows = $leave->get_rows($limit, $start, $table,$role,$emp_id);
                        $resp = array('pagesize'=>$pageSize,'page'=>$page,'leave_rows'=>$leave_rows,'total_rows'=>$total_rows,'filters'=>$items,'in elseif'=>'in else if');
                        $this->response($resp, 200);
                    }
                    else{
                        $col = $items[0]->columnField;
                        $operator = $items[0]->operatorValue;
                        $search = $items[0]->value;
                        $leave = new LeavesListModel;
                        $total_rows = $leave->count_filtered_rows($search, $col, $table, $operator,$role,$emp_id);
                        $leave_rows = $leave->get_filtered_rows($limit, $start, $search, $col, $operator, $table,$role,$emp_id);
                        $resp = array('pagesize'=>$pageSize,'page'=>$page,'leave_rows'=>$leave_rows,'total_rows'=>$total_rows,'filters'=>$items,'inelse'=>'in else');
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
