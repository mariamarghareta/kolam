<?php
class Sampling extends CI_Model
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
        $query = $this->db->select('sampling.id, tebar.kode, blok.name as blok_name, kolam.name as kolam_name, round(sampling.kenaikan_daging,2) as kenaikan_daging, round(sampling.fcr,2) as fcr, round(sampling.adg,2) as adg, sampling.dt, kar.name as create_user, karw.name as write_user, sampling.create_time, sampling.write_time, gab.sampling_id as sampling_gabungan')
            ->from('sampling')
            ->join('tebar','tebar.id = sampling.tebar_id', 'left')
            ->join('pemberian_pakan pakan','pakan.sampling_id = sampling.id', 'left')
            ->join('kolam','kolam.id = sampling.kolam_id', 'left')
            ->join('blok','blok.id = kolam.blok_id', 'left')
            ->join('tebar_history his','his.sampling_id = sampling.id', 'left')
            ->join('karyawan kar', 'kar.id = sampling.create_uid', 'left')
            ->join('karyawan karw', 'karw.id = sampling.write_uid', 'left')
            ->join('v_sampling_combine gab', 'gab.sampling_id = sampling.id', 'left')
            ->where('sampling.deleted', 0)
            ->where('his.sequence !=', 1)
            ->where('tebar.deleted', 0)
            ->group_start()
            ->like('tebar.kode', $searchword)
            ->or_like('blok.name', $searchword)
            ->or_like('kolam.name', $searchword)
            ->or_like('sampling.kenaikan_daging', $searchword)
            ->or_like('sampling.fcr', $searchword)
            ->or_like('sampling.adg', $searchword)
            ->group_end()
            ->limit($data_count, ($offset-1) * $data_count)
            ->order_by('sampling.id desc')
            ->get();
        return $query->result_array();
    }


    public function get_count_all()
    {
        $this->db->from('sampling')
            ->join('tebar_history his','his.sampling_id = sampling.id', 'left')
            ->where('his.sequence !=', 1)
            ->like('sampling.deleted', 0);
        return $this->db->count_all_results();
    }


    public function insert($tebar_id, $kolam_id, $kenaikan_daging, $fcr, $adg, $create_uid){
        $data = array(
            'tebar_id' => $tebar_id,
            'kolam_id' => $kolam_id,
            'kenaikan_daging' => $kenaikan_daging,
            'fcr' => $fcr,
            'adg' => $adg,
            'create_uid' => $create_uid,
            'create_time' => $this->get_now(),
            'dt' => $this->get_now(),
            'write_uid' => $create_uid,
            'write_time' => $this->get_now()
        );
        $query = $this->db->insert('sampling', $data);
        return $this->db->insert_id();
    }


    public function get($id){
        $query = $this->db->select('s.id, s.tebar_id, round(s.kenaikan_daging,2) as kenaikan_daging, round(s.fcr,2) as fcr, round(s.adg,2) as adg, s.kolam_id, k.blok_id, kar.name as create_user, karw.name as write_user, s.create_time, s.write_time, s.dt')
            ->from('sampling s')
            ->join('kolam k', 'k.id = s.kolam_id')
            ->join('blok b', 'b.id = k.blok_id')
            ->join('karyawan kar', 'kar.id = s.create_uid', 'left')
            ->join('karyawan karw', 'karw.id = s.write_uid', 'left')
            ->where('s.id', $id)
            ->where('s.deleted',0)
            ->get();
        return $query->result();
    }


    public function get_without_check($id){
        $query = $this->db->select('s.id, s.tebar_id, round(s.kenaikan_daging,2) as kenaikan_daging, round(s.fcr,2) as fcr, round(s.adg,2) as adg, s.kolam_id, k.blok_id, kar.name as create_user, karw.name as write_user, s.create_time, s.write_time, s.dt')
            ->from('sampling s')
            ->join('kolam k', 'k.id = s.kolam_id', 'left')
            ->join('blok b', 'b.id = k.blok_id', 'left')
            ->join('karyawan kar', 'kar.id = s.create_uid', 'left')
            ->join('karyawan karw', 'karw.id = s.write_uid', 'left')
            ->where('s.id', $id)
            ->get();
        return $query->result();
    }


    public function update($tebar_id, $kolam_id, $kenaikan_daging, $fcr, $adg, $id, $write_uid){
        $data = array(
            'tebar_id' => $tebar_id,
            'kolam_id' => $kolam_id,
            'kenaikan_daging' => $kenaikan_daging,
            'fcr' => $fcr,
            'adg' => $adg,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now(),
        );

        $this->db->where('id', $id);
        return $this->db->update('sampling', $data);
    }


    public function delete($id, $write_uid){
        $data = array(
            'deleted' => 1,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('id', $id);
        return $this->db->update('sampling', $data);
    }
}