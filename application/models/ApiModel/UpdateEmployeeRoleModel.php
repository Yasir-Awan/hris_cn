<?php
defined('BASEPATH') or exit('No direct script access allowed');
class UpdateEmployeeRoleModel extends CI_Model
{
    function UpdateRole($bioId,$hrRole)
    {
        $data = array(
            'emp_id'=>$bioId,
            'hr_role'=>$hrRole,
        );
        // echo "<pre>"; print_r($data); exit;
        $this->db->where('emp_id', $bioId);
        $this->db->update('nha.users', $data);
        // echo $this->db->last_query();

        // if ($query->num_rows() > 0) {
        // return $query->result_array();
        // } else {
        // return null;
        // }
    }

}
