<?php
defined('BASEPATH') or exit('No direct script access allowed');
class DeleteModuleModel extends CI_Model
{
    function DeleteModule($module_id,$role_id)
    {
        $this->db->where('module_id', $module_id);
        $this->db->where('role_id', $role_id);
        $this->db->delete('modules_roles');
    }
}
