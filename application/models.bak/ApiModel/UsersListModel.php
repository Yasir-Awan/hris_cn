<?php
defined('BASEPATH') or exit('No direct script access allowed');
class UsersListModel extends CI_Model
{
    public function get_users_list()
    {
        $this->db->order_by('fname', 'asc');
        $query = $this->db->get('vw_userlist');
        return $query->result_array();
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

    function count_filtered_rows($search, $col, $table,$designation,$emp_id)
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

    function get_filtered_rows($limit, $start, $search, $col, $operator, $table,$designation,$emp_id)
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
