<?php
defined('BASEPATH') or exit('No direct script access allowed');
class UpdateLeaveModel extends CI_Model
{
    function UpdateLeave($leaveStart,$leaveEnd,$bio_id,$leave_id,$leave_type,$leave_reason,$addDateTime,$saturdays,$sundays,$totalLeaveDays,$shortHrs)
    {
        $data = array(
            'id'=>$leave_id,
            'bio_id'=>$bio_id,
            'start_date'=>$leaveStart,
            'end_date'=>$leaveEnd,
            'add_date'=>$addDateTime,
            'leave_type'=>$leave_type,
            'status'=>2,
            'reason'=>$leave_reason,
            'saturdays'=>$saturdays,
            'sundays'=>$sundays,
            'total_leave_days'=>$totalLeaveDays,
            'short_hrs'=>$shortHrs
        );
        // echo "<pre>"; print_r($data); exit;
        $this->db->where('id', $leave_id);
        $this->db->update('tbl_leaves', $data);
        // echo $this->db->last_query();

        // if ($query->num_rows() > 0) {
        // return $query->result_array();
        // } else {
        // return null;
        // }
    }

}
