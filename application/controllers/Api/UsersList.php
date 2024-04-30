<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
class UsersList extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ApiModel/UsersListModel');
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

        $table = 'vw_userlist';
		$columns = array(
			0 => 'id',
			1 => 'fullname',
			2 => 'email',
            3 => 'bio_ref_id',
            4 => 'site',
            5 => 'site_name',
            6 => 'contact',
            7 => 'address',
            8 => 'employee_type',
            9 => 'type_of_employee',
            10 => 'consultant',
            11 => 'section',
            12 => 'section_name',
            13 => 'field',
            14 => 'field_name',
            15 => 'role',
            16 => 'role_name',
            17 => 'employee_team',
            18 => 'status'
		);
        $headers = apache_request_headers();
        $head = explode(" ", $headers['Authorization']);
        $token = $head[1];

        try {
            $this->load->helper('verifyAuthToken');
            $verifiedToken = verifyToken($token);
            if($verifiedToken){
                $user = new UsersListModel;
                    if(empty($items)){
                        $user = new UsersListModel;
                        $total_rows = $user->count_rows($table,$role,$emp_id);
                        $employees_rows = $user->get_rows($limit, $start, $table,$role,$emp_id);
                        $resp = array('pagesize'=>$pageSize,'page'=>$page,'employees_rows'=>$employees_rows,'total_rows'=>$total_rows,'filters'=>$items,'inif'=>'in if');
                        $this->response($resp, 200);
                    }
                    elseif(empty($items[0]->value)){
                        $user = new UsersListModel;
                        $total_rows = $user->count_rows($table,$role,$emp_id);
                        $employees_rows = $user->get_rows($limit, $start, $table,$role,$emp_id);
                        $resp = array('pagesize'=>$pageSize,'page'=>$page,'employees_rows'=>$employees_rows,'total_rows'=>$total_rows,'filters'=>$items,'in elseif'=>'in else if');
                        $this->response($resp, 200);
                    }
                    else{
                        $col = $items[0]->columnField;
                        $operator = $items[0]->operatorValue;
                        $search = $items[0]->value;
                        $user = new UsersListModel;
                        $total_rows = $user->count_filtered_rows($search, $col, $table, $operator,$role,$emp_id);
                        $employees_rows = $user->get_filtered_rows($limit, $start, $search, $col, $operator, $table,$role,$emp_id);
                        $resp = array('pagesize'=>$pageSize,'page'=>$page,'employees_rows'=>$employees_rows,'total_rows'=>$total_rows,'filters'=>$items,'inelse'=>'in else');
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
