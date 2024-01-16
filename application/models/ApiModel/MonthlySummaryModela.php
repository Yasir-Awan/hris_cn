<?php
defined('BASEPATH') or exit('No direct script access allowed');
class MonthlySummaryModel extends CI_Model
{
    public function get_monthly_summary($designation,$emp_id)
    {
        if($designation == 3){
            $this->db->order_by('id', 'desc');
            $query = $this->db->get('vw_attendance_summary');
            return $query->result_array();
        }else{
            $this->db->where('bio_id', $emp_id);
            $this->db->order_by('id', 'desc');
            $query = $this->db->get('vw_attendance_summary');
            return $query->result_array();
        }
    }

    function count_rows($table,$designation,$emp_id)
    {
        if($designation == 3){
        $query = $this->db->get($table);
        return $query->num_rows();
        }else{
        $this->db->where('bio_id', $emp_id);
        $query = $this->db->get($table);
        return $query->num_rows();
        }
    }

    function get_rows($limit, $start, $table,$designation,$emp_id)
    {
        if($designation == 3){
            $this->db->limit($limit, $start);
            $this->db->order_by('id', 'desc');
            $query =  $this->db->get($table);
            if ($query->num_rows() > 0) {
                return $query->result();
            } else {
                return null;
            }
        }else{
            $this->db->where('bio_id', $emp_id);
            $this->db->limit($limit, $start);
            $this->db->order_by('id', 'desc');
            $query =  $this->db->get($table);
            if ($query->num_rows() > 0) {
                return $query->result();
            } else {
                return null;
            }
        }
    }

    function mui_filter_rows_count($search, $col, $table,$designation,$emp_id)
    {
        // if($designation == 3){
        $this->db->like($col, $search);
        $query = $this->db->get($table);
        return $query->num_rows();
        // }
        // else{
        //   $this->db->where('bio_id', $emp_id);
        //   $this->db->like($col, $search);
        //   $query = $this->db->get($table);
        //   return $query->num_rows();
        // }
    }

    function mui_filter_rows($limit, $start, $search, $col, $operator, $table,$designation,$emp_id)
    {
        if($designation == 3){
        $this->db->like($col, $search);
        $this->db->limit($limit, $start);
        $this->db->order_by('id', 'desc');
        $query =   $this->db->get($table);
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
        }else{
        $this->db->where('bio_id', $emp_id);
        $this->db->like($col, $search);
        $this->db->limit($limit, $start);
        $this->db->order_by('id', 'desc');
        $query =   $this->db->get($table);
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
        }
    }

    function custom_filter_rows_count($table,$designation,$emp_id,$customFilters)
    {
        if(isset($customFilters['filterType']) && $customFilters['filterType'] == 3){
            $month = $customFilters['month'];
            $this->db->where("(DATE_FORMAT(schedule_start_date, '%Y-%m') = '$month' OR DATE_FORMAT(schedule_end_date, '%Y-%m') = '$month')");
            $query = $this->db->get($table);
            return $query->num_rows();
        }
        if(isset($customFilters['filterType']) && $customFilters['filterType'] == 4){
            $this->db->where('site', $customFilters['site']);
            $query = $this->db->get($table);
            return $query->num_rows();
        }
        if(isset($customFilters['filterType']) && $customFilters['filterType'] == 5){
            $this->db->where('designation', $customFilters['designation']);
            $query = $this->db->get($table);
            return $query->num_rows();
        }
    }

    function custom_filter_rows($limit, $start, $table,$designation,$emp_id,$customFilters)
    {
        if($designation == 3){
        if(isset($customFilters['filterType']) && $customFilters['filterType'] == 3){
            $month = $customFilters['month'];
            $this->db->where("(DATE_FORMAT(schedule_start_date, '%Y-%m') = '$month' OR DATE_FORMAT(schedule_end_date, '%Y-%m') = '$month')");
            $this->db->limit($limit, $start);
            $this->db->order_by('id', 'desc');
            $query =   $this->db->get($table);
            if ($query->num_rows() > 0) {
            return $query->result();
            } else {
            return null;
            }
        }
        if(isset($customFilters['filterType']) && $customFilters['filterType'] == 4){
            $this->db->where('site', $customFilters['site']);
            $this->db->limit($limit, $start);
            $this->db->order_by('id', 'desc');
            $query =   $this->db->get($table);
            if ($query->num_rows() > 0) {
            return $query->result();
            } else {
            return null;
            }
        }
        if(isset($customFilters['filterType']) && $customFilters['filterType'] == 5){
            $this->db->where('designation', $customFilters['designation']);
            $this->db->limit($limit, $start);
            $this->db->order_by('id', 'desc');
            $query =   $this->db->get($table);
            if ($query->num_rows() > 0) {
            return $query->result();
            } else {
            return null;
            }
        }
        }else{
        if(isset($customFilters['filterType']) && $customFilters['filterType'] == 3){
            $month = $customFilters['month'];
            $this->db->where('bio_id', $emp_id);
            $this->db->where("(DATE_FORMAT(schedule_start_date, '%Y-%m') = '$month' OR DATE_FORMAT(schedule_end_date, '%Y-%m') = '$month')");
            $this->db->limit($limit, $start);
            $this->db->order_by('id', 'desc');
            $query =   $this->db->get($table);
            if ($query->num_rows() > 0) {
            return $query->result();
            } else {
            return null;
            }
        }
        }
    }

    function dual_filter_rows_count($search, $col, $table,$operator,$designation,$emp_id,$customFilters)
    {
        if (isset($customFilters['filterType']) && $customFilters['filterType'] == 3){
            $month = $customFilters['month'];
            $this->db->like($col , $search);
            $this->db->where("(DATE_FORMAT(schedule_start_date, '%Y-%m') = '$month' OR DATE_FORMAT(schedule_end_date, '%Y-%m') = '$month')");
            $query = $this->db->get($table);
            return $query->num_rows();
        }
        if (isset($customFilters['filterType']) && $customFilters['filterType'] == 4){
            $this->db->like($col , $search);
            $this->db->where('site', $customFilters['site']);
            $query = $this->db->get($table);
            return $query->num_rows();
        }
        if (isset($customFilters['filterType']) && $customFilters['filterType'] == 5){
            $this->db->like($col , $search);
            $this->db->where('designation', $customFilters['designation']);
            $query = $this->db->get($table);
            return $query->num_rows();
        }
    }

    function dual_filter_rows($limit, $start, $search, $col, $operator, $table,$designation,$emp_id,$customFilters)
    {
        if($designation == 3){
        if(isset($customFilters['filterType']) && $customFilters['filterType'] == 3){
            $month = $customFilters['month'];
            $this->db->like($col , $search);
            $this->db->where("(DATE_FORMAT(schedule_start_date, '%Y-%m') = '$month' OR DATE_FORMAT(schedule_end_date, '%Y-%m') = '$month')");
            $this->db->limit($limit, $start);
            $this->db->order_by('id', 'desc');
            $query =   $this->db->get($table);
            if ($query->num_rows() > 0) {
            return $query->result();
            } else {
            return null;
            }
        }
        if(isset($customFilters['filterType']) && $customFilters['filterType'] == 4){
            $this->db->where('site', $customFilters['site']);
            $this->db->like($col, $search);
            $this->db->limit($limit, $start);
            $this->db->order_by('id', 'desc');
            $query =   $this->db->get($table);
            if ($query->num_rows() > 0) {
            return $query->result();
            } else {
            return null;
            }
        }
        if(isset($customFilters['filterType']) && $customFilters['filterType'] == 5){
            $this->db->where('designation', $customFilters['designationD']);
            $this->db->like($col, $search);
            $this->db->limit($limit, $start);
            $this->db->order_by('id', 'desc');
            $query =   $this->db->get($table);
            if ($query->num_rows() > 0) {
            return $query->result();
            } else {
            return null;
            }
        }
        }else{
        if(isset($customFilters['filterType']) && $customFilters['filterType'] == 3){
            $month = $customFilters['month'];
            $this->db->where('bio_id', $emp_id);
            $this->db->where("(DATE_FORMAT(schedule_start_date, '%Y-%m') = '$month' OR DATE_FORMAT(schedule_end_date, '%Y-%m') = '$month')");
            $this->db->like($col, $search);
            $this->db->limit($limit, $start);
            $this->db->order_by('id', 'desc');
            $query =   $this->db->get($table);
            if ($query->num_rows() > 0) {
            return $query->result();
            } else {
            return null;
            }
        }
        }
    }
}
