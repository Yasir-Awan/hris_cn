<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
class GetSites extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ApiModel/Forms/FilterLists/GetSitesModel');

    }
    public function index_get()
    {
        $headers = apache_request_headers();
        $head = explode(" ", $headers['Authorization']);

        $token = $head[1];

        try {
            $this->load->helper('verifyAuthToken');
            $verifiedToken = verifyToken($token);
            if($verifiedToken){
                $site = new GetSitesModel;
                    $site_info = $site->get_sites_list();
                    $resp = array('site_info' => $site_info);
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
