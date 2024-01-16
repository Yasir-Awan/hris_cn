<?php
defined('BASEPATH') or exit('No direct script access allowed');
class DeleteLeaveModel extends CI_Model
{
    function DeleteLeave($leave_id)
    {
        $this->db->where('id', $leave_id);
        $this->db->delete('tbl_leaves');
    }

}
