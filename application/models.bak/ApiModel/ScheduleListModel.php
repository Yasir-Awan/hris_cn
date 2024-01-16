<?php
defined('BASEPATH') or exit('No direct script access allowed');
class ScheduleListModel extends CI_Model
{
    public function get_attendance_list()
    {
      $query = $this->db->select('*')->order_by('id', 'desc')->get('vw_schedule');
        return $query->result_array();
    }

    function count_records($table,$designation,$emp_id)
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
  function schedule_records($limit, $start, $table,$designation,$emp_id)
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

  function filter_rows_count($search, $col, $table,$designation,$emp_id)
  {
    if($designation == 3){
      $this->db->like($col, $search);
      $query = $this->db->get($table);
      return $query->num_rows();
    }else{
      $this->db->where('bio_id', $emp_id);
      $this->db->like($col, $search);
      $query = $this->db->get($table);
      return $query->num_rows();
    }
  }

  function filter_rows_search($limit, $start, $search, $col, $operator, $table,$designation,$emp_id)
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
}
