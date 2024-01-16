<?php
defined('BASEPATH') or exit('No direct script access allowed');
class AddLeaveModel extends CI_Model
{
    function InsertLeave($leaveStart,$leaveEnd,$bio_id,$leave_type,$leave_reason,$addDateTime,$saturdays,$sundays,$totalLeaveDays,$shortHrs)
    {
        $data = array(
            'bio_id'=>$bio_id,
            'start_date'=>$leaveStart,
            'end_date'=>$leaveEnd,
            'add_date'=>$addDateTime,
            'leave_type'=>$leave_type,
            'reason'=>$leave_reason,
            'saturdays'=>$saturdays,
            'sundays'=>$sundays,
            'total_leave_days'=>$totalLeaveDays,
            'short_hrs'=>$shortHrs
        );
        // echo "<pre>"; print_r($data); exit;
        $this->db->insert('tbl_leaves',$data);

        // if ($query->num_rows() > 0) {
        // return $query->result_array();
        // } else {
        // return null;
        // }
    }

}
