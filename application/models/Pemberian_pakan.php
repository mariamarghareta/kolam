<?php
class Pemberian_pakan extends CI_Model
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


    public function show_all($data_count, $offset, $searchword)
    {

    }


    public function get_count_all()
    {

    }


    public function insert($ukuran, $fr, $sr, $dosis_pakan, $total_pakan, $pagi, $sore, $malam, $tebar_id, $kolam_id, $sampling_id, $grading_id, $sampling, $size, $biomass, $total_ikan, $angka, $satuan, $create_uid){
        $data = array(
            'is_active' => 1,
            'ukuran' => $ukuran,
            'fr' => $fr,
            'sr' => $sr,
            'dosis_pakan' => $dosis_pakan,
            'total_pakan' => $total_pakan,
            'pagi' => $pagi,
            'sore' => $sore,
            'malam' => $malam,
            'tebar_id' => $tebar_id,
            'kolam_id' => $kolam_id,
            'sampling_id' => $sampling_id,
            'grading_id' => $grading_id,
            'sampling' => $sampling,
            'size' => $size,
            'biomass' => $biomass,
            'total_ikan' => $total_ikan,
            'create_uid' => $create_uid,
            'create_time' => $this->get_now(),
            'dt' => $this->get_now(),
            'angka' => $angka,
            'satuan' => $satuan,
            'write_uid' => $create_uid,
            'write_time' => $this->get_now()

        );
        $query = $this->db->insert('pemberian_pakan', $data);
        return $this->db->insert_id();
    }


    public function get_by_sampling($id){
        $query = $this->db->select('id, sampling, angka, satuan, size, biomass, total_ikan, ukuran, fr, sr, dosis_pakan, total_pakan, pagi, sore, malam, tebar_id, kolam_id, sampling_id, grading_id')
            ->from('pemberian_pakan')
            ->where('sampling_id', $id)
            ->where('deleted',0)
            ->get();
        return $query->result();
    }


    public function get_by_grading($id){
        $query = $this->db->select('p.id, p.sampling, p.angka, p.satuan, p.size, p.biomass, p.total_ikan, p.ukuran, p.fr, p.sr, p.dosis_pakan, p.total_pakan, p.pagi, p.sore, p.malam, p.tebar_id, p.kolam_id, p.sampling_id, p.grading_id, b.id as blok_id, k.name as kolam_name, b.name as blok_name')
            ->from('pemberian_pakan p')
            ->join('kolam k', 'p.id = k.pemberian_pakan_id', 'left')
            ->join('blok b', 'b.id = k.blok_id', 'left')
            ->where('p.grading_id', $id)
            ->where('p.deleted',0)
            ->get();
        return $query->result();
    }


    public function update_from_tebar($fr, $sr, $dosis_pakan, $total_pakan, $pagi, $sore, $malam, $tebar_id, $kolam_id, $sampling, $size, $biomass, $total_ikan, $angka, $satuan, $id, $write_uid){
        $data = array(
            'fr' => $fr,
            'sr' => $sr,
            'dosis_pakan' => $dosis_pakan,
            'total_pakan' => $total_pakan,
            'pagi' => $pagi,
            'sore' => $sore,
            'malam' => $malam,
            'tebar_id' => $tebar_id,
            'kolam_id' => $kolam_id,
            'sampling' => $sampling,
            'size' => $size,
            'biomass' => $biomass,
            'total_ikan' => $total_ikan,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now(),
            'angka' => $angka,
            'satuan' => $satuan,
        );

        $this->db->where('id', $id);
        return $this->db->update('pemberian_pakan', $data);
    }


    public function delete($id, $write_uid){
        $data = array(
            'deleted' => 1,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('id', $id);
        return $this->db->update('pemberian_pakan', $data);
    }


    public function delete_by_grading($id, $write_uid){
        $data = array(
            'deleted' => 1,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('grading_id', $id);
        return $this->db->update('pemberian_pakan', $data);
    }
}