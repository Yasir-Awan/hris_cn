<?php
defined('BASEPATH') or exit('No direct script access allowed');
class SitesListModel extends CI_Model
{
    public function get_sites_list($sites)
    {
        $this->db->order_by('name', 'asc');
        $this->db->where_in('id', $sites);
        $query = $this->db->get('tbl_sites');
        return $query->result_array();
    }
}
