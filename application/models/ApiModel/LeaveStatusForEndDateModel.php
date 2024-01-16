<?php
defined('BASEPATH') or exit('No direct script access allowed');
class LeaveStatusForEndDateModel extends CI_Model
{
  function get_endDate_leaveStatus($toDate, $userId)
  {
      $this->db->select('*');
      $this->db->from('vw_leaves');
      $this->db->where('bio_id', $userId);
      $this->db->order_by('id', 'desc');
      $this->db->limit(1);
      $query = $this->db->get();



      if ($query->num_rows() > 0) {
      return $query->result_array();
      } else {
      return null;
      }
  }
}
