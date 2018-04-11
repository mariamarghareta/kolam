<?php
class Role extends CI_Model
{

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
        $this->load->database();
        $this->load->helper('date');
    }

    public function get_now(){
        $dt = new DateTime();
        $dt->setTimezone(new DateTimeZone('GMT+7'));
        return $dt->format("Y-m-d H:i:s");
    }


    public function show_all()
    {
        $query = $this->db->select('id, role')
            ->from('role')
            ->get();
        return $query->result_array();
    }
}