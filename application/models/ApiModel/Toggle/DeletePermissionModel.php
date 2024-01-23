<?php
defined('BASEPATH') or exit('No direct script access allowed');
class DeletePermissionModel extends CI_Model
{
    function ToggleRole($roleId)
    {
        $this->db->select('*'); // Select all columns, you can specify specific columns if needed
        $this->db->from('tbl_roles'); // Replace 'your_table' with the actual table name
        $this->db->where('id', $roleId); // Replace with your actual conditions

        $result = $this->db->get()->result_array();

        if($result[0]['delete_permission']=='0'){
            $data = array(
                'delete_permission'=>1,
            );
            $this->db->where('id', $roleId);
            $this->db->update('tbl_roles', $data);
        }
        if($result[0]['delete_permission']=='1'){
            $data = array(
                'delete_permission'=>0,
            );
            $this->db->where('id', $roleId);
            $this->db->update('tbl_roles', $data);
        }
    }

}
