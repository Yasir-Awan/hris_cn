<?php
defined('BASEPATH') or exit('No direct script access allowed');
class AttendanceDetailsModel extends CI_Model
{
    public function get_detail($userName, $password)
    {
        $db2 = $this->load->database('database2', TRUE);
        $query = $db2->get_where('users', array('username' => $userName, 'password' => $password));

        if($query->num_rows()>0){
            return $query->result_array();
        }else{
            return "User Not Found!";
        }
        // return $query->result_array();
    }

    public function get_attendance_detail($emp_id,$date)
    {
        $query = $this->db->get_where('vw_finalized_attendance', array('bio_id' => $emp_id, 'attendance_date' => $date));
        if($query->num_rows()>0){
            return $query->result_array();
        }
    }

    public function get_associated_modules($role_id)
    {
        $this->db->select('vw_modules_roles.module_id, vw_modules_roles.module_name');
        $this->db->where('id', $role_id);
        $query = $this->db->get('vw_modules_roles');

        if($query->num_rows()>0){
            $lines = $query->result_array();
            $moduleMapping = [];
            foreach ($lines as $row) {
                    $moduleMapping[$row['module_id']] = $row['module_name'];
            }
            // Now, $moduleMapping contains the mapping of module_id to module_name
            // Convert to the desired formats
            $tabNameToIndex = $moduleMapping; // {"1": "schedules", "2": "leaves", ...}
            $indexToTabName = array_flip($moduleMapping);
            return array('tabNameToIndex'=>$tabNameToIndex,'indexToTabName'=>$indexToTabName);
        }else{ return 'no records found'; }
    }

    public function get_associated_employees($role_id,$emp_id)
    {
        $this->db->select('emp_id');
        $this->db->where('role_id', $role_id);
        $query = $this->db->get('employees_roles');

        if($query->num_rows()>0){
            $employees = $query->result_array();
            $empIds = array_column($employees, 'emp_id');
            array_push($empIds, $emp_id);
            return $empIds;
        }else{
            $empIds = array();
            array_push($empIds, $emp_id);
            return $empIds;
        }
    }

    public function get_associated_sites($role_id,$site_id)
    {
        $this->db->select('site_id');
        $this->db->where('id', $role_id);
        $query = $this->db->get('vw_roles_sites');

        if($query->num_rows()>0){
            $sites = $query->result_array();
            $siteIds = array_column($sites, 'site_id');
            array_push($siteIds, $site_id);
            return $siteIds;
        }else{
            $siteIds = array();
            array_push($siteIds, $site_id);
            return $siteIds;
        }
    }

    public function get_associated_permissions($role_id)
    {
        $this->db->select('*');
        $this->db->where('id', $role_id);
        $query = $this->db->get('tbl_roles');

        if($query->num_rows()>0){
            $roles = $query->result_array();
            return $roles;
        }
    }
}
