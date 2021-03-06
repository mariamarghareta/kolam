<?php
class Laporan_keuangan extends CI_Model
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
        $query = $this->db->select('DATE_FORMAT(dt,"%d-%m-%Y") as dt, keterangan, jumlah, harga, total, jenis')
            ->from('v_lap_keuangan lap')
            ->where('lap.dt >=', $from)
            ->where('lap.dt <=', $to)
            ->get();
        return $query->result_array();
    }
}