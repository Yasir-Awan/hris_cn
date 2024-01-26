<?php
defined('BASEPATH') or exit('No direct script access allowed');
class AddRoleModel extends CI_Model
{
    function InsertRole($role_name,$employees,$modules,$sites)
    {
        $data = array(
            'name'=>$role_name,
        );
        $this->db->insert('tbl_roles',$data);
        // Get the last inserted ID
        $role_id = $this->db->insert_id();

        if(!empty($employees)){
            // Prepare data for batch insert
                $employee_data = array();
                foreach ($employees as $employee_id) {
                    $employee_data[] = array(
                        'role_id' => $role_id,
                        'emp_id' => $employee_id,
                        // Add other columns as needed
                    );
                }

                // Batch insert into 'employees_roles' table
                if (!empty($employee_data)) {
                    $this->db->insert_batch('employees_roles', $employee_data);
                }
        }

        if(!empty($modules)){
            // Prepare data for batch insert
                $module_data = array();
                foreach ($modules as $module_id) {
                    $module_data[] = array(
                        'role_id' => $role_id,
                        'module_id' => $module_id,
                        // Add other columns as needed
                    );
                }

                // Batch insert into 'employees_roles' table
                if (!empty($module_data)) {
                    $this->db->insert_batch('modules_roles', $module_data);
                }
        }

        if(!empty($sites)){
            // Prepare data for batch insert
                $site_data = array();
                foreach ($sites as $site_id) {
                    $site_data[] = array(
                        'role_id' => $role_id,
                        'site_id' => $site_id,
                        // Add other columns as needed
                    );
                }

                // Batch insert into 'employees_roles' table
                if (!empty($site_data)) {
                    $this->db->insert_batch('roles_sites', $site_data);
                }
        }
    }

}
