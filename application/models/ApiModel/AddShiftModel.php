<?php
defined('BASEPATH') or exit('No direct script access allowed');
class AddShiftModel extends CI_Model
{
    function InsertShift($shiftName,$shiftType,$startTime,$endTime)
    {
        $data = array(
            'shift_name'=>$shiftName,
            'shift_type'=>$shiftType,
            'start'=>$startTime,
            'end'=>$endTime
        );
        $this->db->insert('tbl_shifts',$data);

        // if ($query->num_rows() > 0) {
        // return $query->result_array();
        // } else {
        // return null;
        // }
    }
}
