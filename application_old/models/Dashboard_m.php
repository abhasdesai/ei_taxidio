<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard_m extends CI_Model
{
    function getTotalUsers()
    {
       $Q=$this->db->query('select id from tbl_front_users where isactive=1');
       return $Q->num_rows();
    }

    function getTotalCountries()
    {
       $Q=$this->db->query('select id from tbl_country_master');
       return $Q->num_rows();
    }

    function getTotalCities()
    {
       $Q=$this->db->query('select id from tbl_city_master');
       return $Q->num_rows();
    }

    function getTotalSubscribers()
    {
       $Q=$this->db->query('select id from tbl_subscribers');
       return $Q->num_rows();
    }
}
