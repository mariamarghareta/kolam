<?php
class Tabelpakan extends CI_Model
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
        $query = $this->db->select('id, age, round(weight,2) as weight, fr, sr')
            ->from('tabel_pakan')
            ->where('deleted', 0)
            ->group_start()
            ->like('age ', $searchword)
            ->or_like('weight ', $searchword)
            ->or_like('fr ', $searchword)
            ->or_like('sr ', $searchword)
            ->group_end()
            ->limit($data_count, ($offset-1) * $data_count)
            ->get();
        return $query->result_array();
    }


    public function get_count_all()
    {
        $this->db->like('deleted', 0);
        $this->db->from('tabel_pakan');
        return $this->db->count_all_results();
    }


    public function cek_kembar($weight, $fr, $sr, $id){
        $this->db->from('tabel_pakan')
            ->where('weight', $weight)
            ->where('fr', $fr)
            ->where('sr', $sr)
            ->where('deleted', 0)
            ->where('id !=', $id);
        $query = $this->db->count_all_results();
        if($query >= 1){
            return false;
        } else {
            return true;
        }
    }


    public function insert($age, $weight, $fr, $sr, $create_uid){
        $data = array(
            'age' => $age,
            'weight' => $weight,
            'fr' => $fr,
            'sr' => $sr,
            'create_uid' => $create_uid,
            'create_time' => $this->get_now(),
            'write_uid' => $create_uid,
            'write_time' => $this->get_now()
        );
        $query = $this->db->insert('tabel_pakan', $data);
        return $query;
    }


    public function get($id){
        $query = $this->db->select('tb.id, tb.age, round(tb.weight,2) as weight, round(tb.fr,2) as fr, round(tb.sr,2) as sr, kar.name as create_user, karw.name as write_user, tb.create_time, tb.write_time')
            ->from('tabel_pakan tb')
            ->join('karyawan kar', 'kar.id = tb.create_uid', 'left')
            ->join('karyawan karw', 'karw.id = tb.write_uid', 'left')
            ->where('tb.id', $id)
            ->where('tb.deleted',0)
            ->get();
        return $query->result();
    }


    public function update($age, $weight, $fr, $sr, $id, $write_uid){
        $data = array(
            'age' => $age,
            'weight' => $weight,
            'fr' => $fr,
            'sr' => $sr,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('id', $id);
        return $this->db->update('tabel_pakan', $data);
    }


    public function delete($id, $write_uid){
        $data = array(
            'deleted' => 1,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('id', $id);
        return $this->db->update('tabel_pakan', $data);
    }


    public function get_pakan($size){
        if($size == 0){
            $weight= 0;
        } else {
            $weight = 1000/$size;
        }
        $query = $this->db->select('age, weight, fr, sr')
            ->from('tabel_pakan')
            ->where('deleted', 0)
            ->group_start()
            ->where('weight <=', $weight)
            ->or_where('id in (select id from tabel_pakan where deleted = 0 and weight = (select min(weight) from tabel_pakan where deleted = 0))')
            ->group_end()
            ->order_by('weight desc')
            ->limit(1)
            ->get();
        return $query->result_array();
    }
}