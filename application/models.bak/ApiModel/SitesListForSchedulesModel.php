<?php
defined('BASEPATH') or exit('No direct script access allowed');
class SitesListForSchedulesModel extends CI_Model
{
    public function get_sites_list()
    {
        $this->db->group_by('site');
        $query = $this->db->get('vw_schedule');
        return $query->result_array();
    }
}
