<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
class EmployeesListForFilters extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ApiModel/EmployeesListForFiltersModel');
    }
    public function index_post()
    {
        $users = $this->post('employees');

        $headers = apache_request_headers();
        $head = explode(" ", $headers['Authorization']);
        $token = $head[1];

        try {
            $this->load->helper('verifyAuthToken');
            $verifiedToken = verifyToken($token);
            if($verifiedToken){
                $employees = new EmployeesListForFiltersModel;
                    $user_info = $employees->get_users_list($users);
                    $resp = array('user_info' => $user_info);
                    $this->response($resp, 200);
                    // if(empty($items)){
                    //     $user = new UsersListModel;
                    //     $total_rows = $user->count_rows($table,$role,$emp_id);
                    //     $employees_rows = $user->get_rows($limit, $start, $table,$role,$emp_id);
                    //     $resp = array('pagesize'=>$pageSize,'page'=>$page,'employees_rows'=>$employees_rows,'total_rows'=>$total_rows,'filters'=>$items,'inif'=>'in if');
                    //     $this->response($resp, 200);
                    // }
                    // elseif(empty($items[0]->value)){
                    //     $user = new UsersListModel;
                    //     $total_rows = $user->count_rows($table,$role,$emp_id);
                    //     $employees_rows = $user->get_rows($limit, $start, $table,$role,$emp_id);
                    //     $resp = array('pagesize'=>$pageSize,'page'=>$page,'employees_rows'=>$employees_rows,'total_rows'=>$total_rows,'filters'=>$items,'in elseif'=>'in else if');
                    //     $this->response($resp, 200);
                    // }
                    // else{
                    //     $col = $items[0]->columnField;
                    //     $operator = $items[0]->operatorValue;
                    //     $search = $items[0]->value;
                    //     $user = new UsersListModel;
                    //     $total_rows = $user->count_filtered_rows($search, $col, $table, $operator,$role,$emp_id);
                    //     $employees_rows = $user->get_filtered_rows($limit, $start, $search, $col, $operator, $table,$role,$emp_id);
                    //     $resp = array('pagesize'=>$pageSize,'page'=>$page,'employees_rows'=>$employees_rows,'total_rows'=>$total_rows,'filters'=>$items,'inelse'=>'in else');
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
