<?php
defined('BASEPATH') or exit('No direct script access allowed');
class AttendanceListModel extends CI_Model
{
    public function get_attendance_list()
    {
        $query = $this->db->get('vw_finalized_attendance');
        return $query->result_array();
    }

    function count_assets($table,$designation,$emp_id)
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
  function asset_allposts($limit, $start, $table,$designation,$emp_id)
  {
      if($designation == 3){
          $this->db->limit($limit, $start);
          $this->db->order_by('checkout', 'desc');
          $query =  $this->db->get($table);
          if ($query->num_rows() > 0) {
            return $query->result();
          } else {
            return null;
          }
      }else{
          $this->db->where('bio_id', $emp_id);
          $this->db->limit($limit, $start);
          $this->db->order_by('checkout', 'desc');
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
      $this->db->like($col, $search);
      $query = $this->db->get($table);
      return $query->num_rows();
  }

  function mui_filter_rows_search($limit, $start, $search, $col, $operator, $table,$designation,$emp_id)
  {
    if($designation == 3){
        $this->db->like($col, $search);
        $this->db->limit($limit, $start);
        $this->db->order_by('checkout', 'desc');
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
      $this->db->order_by('checkout', 'desc');
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
      if(isset($customFilters['filterType']) && $customFilters['filterType'] == 1){
        $this->db->where('attendance_date>=', $customFilters['dateRange']['startDate']);
        $this->db->where('attendance_date<=', $customFilters['dateRange']['endDate']);
        $query = $this->db->get($table);
        return $query->num_rows();
      }
      if(isset($customFilters['filterType']) && $customFilters['filterType'] == 2){
        $this->db->where('attendance_date', $customFilters['day']);
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

  function custom_filter_rows_search($limit, $start, $table,$designation,$emp_id,$customFilters)
  {
    if($designation == 3){
      if(isset($customFilters['filterType']) && $customFilters['filterType'] == 1){
        $this->db->where('attendance_date>=', $customFilters['dateRange']['startDate']);
        $this->db->where('attendance_date<=', $customFilters['dateRange']['endDate']);
        $this->db->limit($limit, $start);
        $this->db->order_by('checkout', 'desc');
        $query =   $this->db->get($table);
        if ($query->num_rows() > 0) {
          return $query->result();
        } else {
          return null;
        }
      }
      if(isset($customFilters['filterType']) && $customFilters['filterType'] == 2){
        $this->db->where('attendance_date', $customFilters['day']);
        $this->db->limit($limit, $start);
        $this->db->order_by('checkout', 'desc');
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
        $this->db->order_by('checkout', 'desc');
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
        $this->db->order_by('checkout', 'desc');
        $query =   $this->db->get($table);
        if ($query->num_rows() > 0) {
          return $query->result();
        } else {
          return null;
        }
      }
    }else{
      if(isset($customFilters['filterType']) && $customFilters['filterType'] == 1){
        $this->db->where('bio_id', $emp_id);
        $this->db->where('attendance_date>=', $customFilters['dateRange']['startDate']);
        $this->db->where('attendance_date<=', $customFilters['dateRange']['endDate']);
        $this->db->limit($limit, $start);
        $this->db->order_by('checkout', 'desc');
        $query =   $this->db->get($table);
        if ($query->num_rows() > 0) {
          return $query->result();
        } else {
          return null;
        }
      }
      if(isset($customFilters['filterType']) && $customFilters['filterType'] == 2){
        $this->db->where('bio_id', $emp_id);
        $this->db->where('attendance_date', $customFilters['day']);
        $this->db->limit($limit, $start);
        $this->db->order_by('checkout', 'desc');
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
      if (isset($customFilters['filterType']) && $customFilters['filterType'] == 1){
        $this->db->like($col , $search);
        $this->db->where('attendance_date>=', $customFilters['dateRange']['startDate']);
        $this->db->where('attendance_date<=', $customFilters['dateRange']['endDate']);
        $query = $this->db->get($table);
        return $query->num_rows();
      }
      if (isset($customFilters['filterType']) && $customFilters['filterType'] == 2){
        $this->db->like($col , $search);
        $this->db->where('attendance_date', $customFilters['day']);
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

  function dual_filter_rows_search($limit, $start, $search, $col, $operator, $table,$designation,$emp_id,$customFilters)
  {
    if($designation == 3){
      if(isset($customFilters['filterType']) && $customFilters['filterType'] == 1){
        $this->db->where('attendance_date>=', $customFilters['dateRange']['startDate']);
        $this->db->where('attendance_date<=', $customFilters['dateRange']['endDate']);
        $this->db->like($col, $search);
        $this->db->limit($limit, $start);
        $this->db->order_by('checkout', 'desc');
        $query =   $this->db->get($table);
        if ($query->num_rows() > 0) {
          return $query->result();
        } else {
          return null;
        }
      }
      if(isset($customFilters['filterType']) && $customFilters['filterType'] == 2){
        $this->db->where('attendance_date', $customFilters['day']);
        $this->db->like($col, $search);
        $this->db->limit($limit, $start);
        $this->db->order_by('checkout', 'desc');
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
        $this->db->order_by('checkout', 'desc');
        $query =   $this->db->get($table);
        if ($query->num_rows() > 0) {
          return $query->result();
        } else {
          return null;
        }
      }
      if(isset($customFilters['filterType']) && $customFilters['filterType'] == 5){
        $this->db->where('designation', $customFilters['designation']);
        $this->db->like($col, $search);
        $this->db->limit($limit, $start);
        $this->db->order_by('checkout', 'desc');
        $query =   $this->db->get($table);
        if ($query->num_rows() > 0) {
          return $query->result();
        } else {
          return null;
        }
      }
    }else{
      if(isset($customFilters['filterType']) && $customFilters['filterType'] == 1){
        $this->db->where('bio_id', $emp_id);
        $this->db->where('attendance_date>=', $customFilters['dateRange']['startDate']);
        $this->db->where('attendance_date<=', $customFilters['dateRange']['endDate']);
        $this->db->like($col, $search);
        $this->db->limit($limit, $start);
        $this->db->order_by('checkout', 'desc');
        $query =   $this->db->get($table);
        if ($query->num_rows() > 0) {
          return $query->result();
        } else {
          return null;
        }
      }
      if(isset($customFilters['filterType']) && $customFilters['filterType'] == 2){
        $this->db->where('bio_id', $emp_id);
        $this->db->where('attendance_date', $customFilters['day']);
        $this->db->like($col, $search);
        $this->db->limit($limit, $start);
        $this->db->order_by('checkout', 'desc');
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
