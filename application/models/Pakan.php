<?php
class Pakan extends CI_Model
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
        $query = $this->db->select('id, name, stok, min, case when stok <= min then -1 else (case when stok > min and stok <= (min * 1.2) then 0 else 1 end) end as status, satuan')
            ->from('pakan')
            ->where('deleted', 0)
            ->like('name ', $searchword)
            ->limit($data_count, ($offset-1) * $data_count)
            ->order_by('status asc')
            ->get();
        return $query->result_array();
    }


    public function get_count_all()
    {
        $this->db->like('deleted', 0);
        $this->db->from('pakan');
        return $this->db->count_all_results();
    }


    public function get_all_instock()
    {
        $query = $this->db->select('id, name, stok, min, satuan')
            ->from('pakan')
            ->where('deleted', 0)
            ->where('stok >', 0)
            ->get();
        return $query->result_array();
    }


    public function get_all()
    {
        $query = $this->db->select('id, name, stok, min, satuan, case when stok <= min then -1 else (case when stok > min and stok <= (min * 1.2) then 0 else 1 end) end as status')
            ->from('pakan')
            ->where('deleted', 0)
            ->order_by('status asc')
            ->get();
        return $query->result_array();
    }


    public function cek_kembar($name, $id){
        if ($name == ""){
            return false;
        }
        $this->db->from('pakan')
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


    public function insert($nama, $min, $satuan, $create_uid){
        $nama = strtoupper($nama);
        $data = array(
            'name' => $nama,
            'min' => $min,
            'satuan' => $satuan,
            'create_uid' => $create_uid,
            'create_time' => $this->get_now(),
            'write_uid' => $create_uid,
            'write_time' => $this->get_now()
        );
        $query = $this->db->insert('pakan', $data);
        return $query;
    }


    public function get($id){
        $query = $this->db->select('id, name, min, satuan, stok')
            ->from('pakan')
            ->where('id', $id)
            ->where('deleted',0)
            ->get();
        return $query->result();
    }


    public function update($name, $min, $satuan, $id, $write_uid){
        $name = strtoupper($name);
        $data = array(
            'name' => $name,
            'min' => $min,
            'satuan' => $satuan,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('id', $id);
        return $this->db->update('pakan', $data);
    }


    public function delete($id, $write_uid){
        $data = array(
            'deleted' => 1,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('id', $id);
        return $this->db->update('pakan', $data);
    }


    public function kurangi_stok($id, $jumlah, $write_uid){
        $data_sekarang = $this->get($id);
        $lama = $data_sekarang[0]->stok;
        $sekarang = $lama - $jumlah;
        if($sekarang < 0){
            $sekarang = 0;
        }
        $data = array(
            'stok' => $sekarang,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('id', $id);
        return $this->db->update('pakan', $data);

    }


    public function tambah_stok($id, $jumlah, $write_uid){
        $data_sekarang = $this->get($id);
        $lama = $data_sekarang[0]->stok;
        $sekarang = $lama + $jumlah;
        $data = array(
            'stok' => $sekarang,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('id', $id);
        return $this->db->update('pakan', $data);
        
    }


    public function update_stok($id, $stok, $write_uid){
        $data = array(
            'stok' => $stok,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('id', $id);
        return $this->db->update('pakan', $data);
    }


    public function update_live_stok($id, $write_uid){
        $query = $this->db->select('pakan_id, final_stok')
            ->from('v_pakan_real_stok')
            ->where('pakan_id', $id)
            ->get();
        $real_stok = $query->result()[0]->final_stok;
        return $this->update_stok($id, $real_stok, $write_uid);
    }
}