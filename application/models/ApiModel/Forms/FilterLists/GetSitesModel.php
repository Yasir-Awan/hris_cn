<?php
defined('BASEPATH') or exit('No direct script access allowed');
class GetSitesModel extends CI_Model
{
    public function get_sites_list()
    {
        $query = $this->db->order_by('name', 'asc')->get('tbl_sites');
        return $query->result_array();
    }
}
