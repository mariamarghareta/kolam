<?php
class Grading extends CI_Model
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
        $query = $this->db->select('g.id, g.tebar_id, g.asal_kolam_id, g.dt, g.total_biomass, g.total_populasi, g.sr, g.pertumbuhan_daging, g.fcr, g.adg, k.name as kolam_name, b.name as blok_name, k.id as kolam_id, b.id as blok_id, t.kode')
            ->from('grading g')
            ->join('kolam k', 'k.id = g.asal_kolam_id')
            ->join('blok b', 'b.id = k.blok_id')
            ->join('tebar t', 't.id = g.tebar_id')
            ->where('g.deleted', 0)
            ->group_start()
            ->like('t.kode ', $searchword)
            ->or_like('k.name ', $searchword)
            ->or_like('b.name ', $searchword)
            ->or_like('g.dt ', $searchword)
            ->or_like('g.total_biomass ', $searchword)
            ->or_like('g.total_populasi ', $searchword)
            ->or_like('g.sr ', $searchword)
            ->or_like('g.pertumbuhan_daging ', $searchword)
            ->or_like('g.fcr ', $searchword)
            ->or_like('g.adg ', $searchword)
            ->group_end()
            ->limit($data_count, ($offset-1) * $data_count)
            ->order_by('g.id desc')
            ->get();
        return $query->result_array();
    }


    public function show_all_data()
    {
        $query = $this->db->select('id, name')
            ->from('blok')
            ->where('deleted', 0)
            ->get();
        return $query->result_array();
    }


    public function get_count_all()
    {
        $this->db->like('deleted', 0);
        $this->db->from('grading');
        return $this->db->count_all_results();
    }


    public function cek_kembar($name, $id){
        if ($name == ""){
            return false;
        }
        $this->db->from('blok')
            ->where('name', strtoupper($name))
            ->where('deleted', 0)
            ->where('id !=', $id);
        $query = $this->db->count_all_results();
        if($query >= 1){
            return false;
        } else {
            return true;
        }
    }


    public function insert($tebar_id, $sampling_id,  $asal_kolam_id, $total_biomass, $total_populasi, $sr, $pertumbuhan_daging, $fcr, $adg, $create_uid){
        $data = array(
            'tebar_id' => $tebar_id,
            'sampling_id' => $sampling_id,
            'asal_kolam_id' => $asal_kolam_id,
            'dt' => $this->get_now(),
            'total_biomass' => $total_biomass,
            'total_populasi' => $total_populasi,
            'sr' => $sr,
            'pertumbuhan_daging' => $pertumbuhan_daging,
            'fcr' => $fcr,
            'adg' => $adg,
            'create_uid' => $create_uid,
            'create_time' => $this->get_now(),
            'write_uid' => $create_uid,
            'write_time' => $this->get_now()
        );
        $query = $this->db->insert('grading', $data);
        return $this->db->insert_id();
    }


    public function get($id){
        $query = $this->db->select('g.id, g.tebar_id, g.sampling_id, g.asal_kolam_id, g.dt, g.total_biomass, g.total_populasi, g.sr, g.pertumbuhan_daging, g.fcr, g.adg, t.kode, k.name as kolam_name, b.name as blok_name, b.id as blok_id')
            ->from('grading g')
            ->join('kolam k', 'k.id = g.asal_kolam_id', 'left')
            ->join('blok b', 'b.id = k.blok_id', 'left')
            ->join('tebar t', 't.id = k.tebar_id', 'left')
            ->where('g.id', $id)
            ->where('g.deleted',0)
            ->get();
        return $query->result();
    }


    public function get_without_check($id){
        $query = $this->db->select('g.id, g.tebar_id, g.sampling_id, g.asal_kolam_id, g.dt, g.total_biomass, g.total_populasi, g.sr, g.pertumbuhan_daging, g.fcr, g.adg, t.kode, k.name as kolam_name, b.name as blok_name, b.id as blok_id')
            ->from('grading g')
            ->join('kolam k', 'k.id = g.asal_kolam_id', 'left')
            ->join('blok b', 'b.id = k.blok_id', 'left')
            ->join('tebar t', 't.id = k.tebar_id', 'left')
            ->where('g.id', $id)
            ->get();
        return $query->result();
    }


    public function update($name, $id, $write_uid){
        $name = strtoupper($name);
        $data = array(
            'name' => $name,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('id', $id);
        return $this->db->update('blok', $data);
    }


    public function delete($id, $write_uid){
        $data = array(
            'deleted' => 1,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('id', $id);
        return $this->db->update('grading', $data);
    }
}