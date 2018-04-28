<?php
class Pembelian extends CI_Model
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
        $query = $this->db->select('id, tipe, dt, ref_id, name, jumlah_item, harga_per_item, total_harga, isi, total_isi, keterangan, deleted')
            ->from('pembelian')
            ->where('deleted', 0)
            ->group_start()
            ->like('dt ', $searchword)
            ->or_like('jumlah_item ', $searchword)
            ->or_like('harga_per_item ', $searchword)
            ->or_like('total_harga ', $searchword)
            ->or_like('isi ', $searchword)
            ->or_like('total_isi ', $searchword)
            ->or_like('keterangan ', $searchword)
            ->group_end()
            ->order_by('dt desc')
            ->limit($data_count, ($offset-1) * $data_count)
            ->get();
        return $query->result_array();
    }


    public function show_all_data()
    {
        $query = $this->db->select('id, name')
            ->from('pembelian')
            ->where('deleted', 0)
            ->get();
        return $query->result_array();
    }


    public function get_count_all()
    {
        $this->db->like('deleted', 0);
        $this->db->from('pembelian');
        return $this->db->count_all_results();
    }


    public function insert_obat($obat_id, $jumlah_item, $harga_per_item, $total_harga, $isi, $total_isi, $keterangan, $create_uid){
        $data = array(
            'dt' => $this->get_now(),
            'obat_id' => $obat_id,
            'jumlah_item' => $jumlah_item,
            'harga_per_item' => $harga_per_item,
            'total_harga' => $total_harga,
            'isi' => $isi,
            'total_isi' => $total_isi,
            'keterangan' => $keterangan,
            'create_uid' => $create_uid,
            'create_time' => $this->get_now(),
            'write_uid' => $create_uid,
            'write_time' => $this->get_now(),
        );
        $query = $this->db->insert('beli_obat', $data);
        return $query;
    }


    public function insert_pakan($pakan_id, $jumlah_item, $harga_per_item, $total_harga, $isi, $total_isi, $keterangan, $create_uid){
        $data = array(
            'dt' => $this->get_now(),
            'pakan_id' => $pakan_id,
            'jumlah_item' => $jumlah_item,
            'harga_per_item' => $harga_per_item,
            'total_harga' => $total_harga,
            'isi' => $isi,
            'total_isi' => $total_isi,
            'keterangan' => $keterangan,
            'create_uid' => $create_uid,
            'create_time' => $this->get_now(),
            'write_uid' => $create_uid,
            'write_time' => $this->get_now()
        );
        $query = $this->db->insert('beli_pakan', $data);
        return $query;
    }


    public function insert_lain($name, $jumlah_item, $harga_per_item, $total_harga, $isi, $total_isi, $keterangan, $create_uid){
        $data = array(
            'dt' => $this->get_now(),
            'name' => $name,
            'jumlah_item' => $jumlah_item,
            'harga_per_item' => $harga_per_item,
            'total_harga' => $total_harga,
            'isi' => $isi,
            'total_isi' => $total_isi,
            'keterangan' => $keterangan,
            'create_uid' => $create_uid,
            'create_time' => $this->get_now(),
            'write_uid' => $create_uid,
            'write_time' => $this->get_now()
        );
        $query = $this->db->insert('beli_lain', $data);
        return $query;
    }


    public function get($id, $tipe_pembelian){
        if($tipe_pembelian == 'p'){
            $query = $this->db->select('id, dt, pakan_id, jumlah_item, harga_per_item, total_harga, isi, total_isi, keterangan')
                ->from('beli_pakan')
                ->where('id', $id)
                ->where('deleted',0)
                ->get();
            return $query->result();
        }else if($tipe_pembelian == 'o'){
            $query = $this->db->select('id, dt, obat_id, jumlah_item, harga_per_item, total_harga, isi, total_isi, keterangan')
                ->from('beli_obat')
                ->where('id', $id)
                ->where('deleted',0)
                ->get();
            return $query->result();
        }else if($tipe_pembelian == 'l'){
            $query = $this->db->select('id, dt, name, jumlah_item, harga_per_item, total_harga, isi, total_isi, keterangan')
                ->from('beli_lain')
                ->where('id', $id)
                ->where('deleted',0)
                ->get();
            return $query->result();
        }
    }


    public function update_lain($name, $jumlah_item, $harga_per_item, $total_harga, $isi, $total_isi, $keterangan, $id, $write_uid){
        $data = array(
            'name' => $name,
            'jumlah_item' => $jumlah_item,
            'harga_per_item' => $harga_per_item,
            'total_harga' => $total_harga,
            'isi' => $isi,
            'total_isi' => $total_isi,
            'keterangan' => $keterangan,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('id', $id);
        return $this->db->update('beli_lain', $data);
    }


    public function delete($id, $tipe, $write_uid){
        $data = array(
            'deleted' => 1,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );
//      WARNING INI HARUS UPDATE STOK
        $this->db->where('id', $id);
        if($tipe == 'o'){
            return $this->db->update('beli_obat', $data);
        } else if($tipe == 'p'){
            return $this->db->update('beli_pakan', $data);
        } else if($tipe == 'l'){
            return $this->db->update('beli_lain', $data);
        }

    }
}