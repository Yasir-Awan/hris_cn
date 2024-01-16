<?php
defined('BASEPATH') or exit('No direct script access allowed');
class DeleteSiteModel extends CI_Model
{
    function DeleteSite($site_id,$role_id)
    {
        $this->db->where('site_id', $site_id);
        $this->db->where('role_id', $role_id);
        $this->db->delete('roles_sites');
    }
}
