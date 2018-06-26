<?php
class Laporan_mon_sayur extends CI_Model
{

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
        $this->load->database();
        $this->load->helper('date');
    }


    public function show_all($from, $to)
    {
        $query = $this->db->select('*')
            ->from('v_lap_mon_sayur lap')
            ->where('lap.write_time >=', $from)
            ->where('lap.write_time <=', $to)
            ->get();
        return $query->result_array();
    }

}