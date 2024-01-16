<?php
defined('BASEPATH') or exit('No direct script access allowed');
class DeleteEmployeeModel extends CI_Model
{
    function DeleteEmployee($emp_id,$role_id)
    {
        $this->db->where('emp_id', $emp_id);
        $this->db->where('role_id', $role_id);
        $this->db->delete('employees_roles');
    }

}
