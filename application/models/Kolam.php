<?php
class Kolam extends CI_Model
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
        $query = $this->db->select('kolam.id, kolam.name, blok.name as blok_name')
            ->from('kolam')
            ->join('blok', 'blok.id = kolam.blok_id')
            ->where('kolam.deleted', 0)
            ->group_start()
            ->like('kolam.name ', $searchword)
            ->or_like('blok.name ', $searchword)
            ->group_end()
            ->limit($data_count, ($offset-1) * $data_count)
            ->get();
        return $query->result_array();
    }


    public function get_count_all()
    {
        $this->db->like('deleted', 0);
        $this->db->from('kolam');
        return $this->db->count_all_results();
    }


    public function cek_kembar($name, $blok_id, $id){
        if ($name == ""){
            return false;
        }
        $this->db->from('kolam')
            ->where('name', strtoupper($name))
            ->where('blok_id', $blok_id)
            ->where('deleted', 0)
            ->where('id !=', $id);
        $query = $this->db->count_all_results();
        if($query >= 1){
            return false;
        } else {
            return true;
        }
    }


    public function insert($nama, $blok_id, $create_uid){
        $nama = strtoupper($nama);
        $data = array(
            'name' => $nama,
            'blok_id' => $blok_id,
            'create_uid' => $create_uid,
            'create_time' => $this->get_now(),
            'write_uid' => $create_uid,
            'write_time' => $this->get_now()
        );
        $query = $this->db->insert('kolam', $data);
        return $query;
    }


    public function get($id){
        $query = $this->db->select('kolam.id, kolam.name, blok.id as blok_id, blok.name as blok_name, kolam.tebar_id, kolam.pemberian_pakan_id, pakan.total_ikan, pakan.biomass, pakan.size, tebar.tgl_tebar, pakan.sampling_id, tebar.kode, tebar.tgl_tebar, kar.name as create_user, karw.name as write_user, kolam.create_time, kolam.write_time')
            ->from('kolam')
            ->join('blok', 'blok.id = kolam.blok_id')
            ->join('pemberian_pakan pakan', 'pakan.id = kolam.pemberian_pakan_id', 'left')
            ->join('tebar', 'tebar.id = kolam.tebar_id', 'left')
            ->join('karyawan kar', 'kar.id = kolam.create_uid', 'left')
            ->join('karyawan karw', 'karw.id = kolam.write_uid', 'left')
            ->where('kolam.id', $id)
            ->where('kolam.deleted',0)
            ->get();
        return $query->result();
    }


    public function update($name, $blok_id, $id, $write_uid){
        $name = strtoupper($name);
        $data = array(
            'name' => $name,
            'blok_id' => $blok_id,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('id', $id);
        return $this->db->update('kolam', $data);
    }


    public function update_tebar_id($kolam_lama_id, $kolam_baru_id, $pakan_id, $tebar_id, $write_uid){
        if ($kolam_baru_id != $kolam_lama_id){
            $flag = True;
            #ubah tebar id kolam lama
            $data = array(
                'tebar_id' => 0,
                'write_uid' => $write_uid,
                'write_time' => $this->get_now()
            );
            $this->db->where('id', $kolam_lama_id);
            if(!$this->db->update('kolam', $data)){$flag=False;}

            #ubah tebar id kolam lama
            $data = array(
                'tebar_id' => $tebar_id,
                'write_uid' => $write_uid,
                'write_time' => $this->get_now()
            );
            $this->db->where('id', $kolam_baru_id);
            if(!$this->db->update('kolam', $data)){$flag=False;}

            #update pakan id dari kolam lama
            $kolam_lama = $this->get($kolam_lama_id);
            if ($kolam_lama[0]->pemberian_pakan_id == $pakan_id){
                $data = array(
                    'pemberian_pakan_id' => 0,
                    'write_uid' => $write_uid,
                    'write_time' => $this->get_now()
                );
                $this->db->where('id', $kolam_lama_id);
                if(!$this->db->update('kolam', $data)){$flag=False;}
            }

            #update pemberian pakan id kolam baru
            $kolam_baru = $this->get($kolam_baru_id);
            if ($kolam_baru[0]->pemberian_pakan_id == 0){
                $data = array(
                    'pemberian_pakan_id' => $pakan_id,
                    'write_uid' => $write_uid,
                    'write_time' => $this->get_now()
                );
                $this->db->where('id', $kolam_baru_id);
                if(!$this->db->update('kolam', $data)){$flag=False;}
            }
            return $flag;
        } else {
            return True;
        }
    }


    public function delete($id, $write_uid){
        $data = array(
            'deleted' => 1,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('id', $id);
        return $this->db->update('kolam', $data);
    }


    public function get_kolam_by_blok($blok_id){
        $query = $this->db->select('kolam.id, kolam.name, blok.name as blok_name, kolam.tebar_id')
            ->from('kolam')
            ->join('blok', 'blok.id = kolam.blok_id')
            ->where('kolam.deleted', 0)
            ->where('blok.id', $blok_id)
            ->where('kolam.tebar_id', 0)
            ->get();
        return $query->result_array();
    }

    public function get_all_kolam_by_blok($blok_id){
        $query = $this->db->select('kolam.id, kolam.name, blok.name as blok_name, kolam.tebar_id')
            ->from('kolam')
            ->join('blok', 'blok.id = kolam.blok_id')
            ->where('kolam.deleted', 0)
            ->where('blok.id', $blok_id)
            ->get();
        return $query->result_array();
    }

    public function get_occupied_kolam($blok_id){
        $query = $this->db->select('kolam.id, kolam.name, blok.name as blok_name, kolam.tebar_id')
            ->from('kolam')
            ->join('blok', 'blok.id = kolam.blok_id')
            ->where('kolam.deleted', 0)
            ->where('blok.id', $blok_id)
            ->where('kolam.tebar_id !=', 0)
            ->get();
        return $query->result_array();
    }


    public function get_kolam_for_tebar($blok_id, $tebar_id){
        $query = $this->db->select('kolam.id, kolam.name, blok.name as blok_name')
            ->from('kolam')
            ->join('blok', 'blok.id = kolam.blok_id')
            ->where('kolam.deleted', 0)
            ->where('blok.id', $blok_id)
            ->group_start()
            ->where('kolam.tebar_id', 0)
            ->or_where('kolam.tebar_id',$tebar_id)
            ->group_end()
            ->get();
        return $query->result_array();
    }


    public function update_pemberian_pakan($pemberian_pakan_id, $tebar_id, $id, $write_uid){
        $data = array(
            'pemberian_pakan_id' => $pemberian_pakan_id,
            'tebar_id' => $tebar_id,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('id', $id);
        return $this->db->update('kolam', $data);
    }

    public function set_tebar_id($tebar_id, $id, $write_uid){
        $data = array(
            'tebar_id' => $tebar_id,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('id', $id);
        return $this->db->update('kolam', $data);
    }


    public function get_last_pakan($id, $tebar_id, $asal, $tujuan, $write_uid){
        if($tujuan == 1){
            $query = $this->db->select('his.sampling_id, his.grading_id')
                ->from('tebar_history his')
                ->where('his.deleted', 0)
                ->where('his.tebar_id', $tebar_id)
                ->where('his.tujuan_kolam_id', $id)
                ->order_by('sequence desc')
                ->limit(1)
                ->get();
        } else {
            $query = $this->db->select('his.sampling_id, his.grading_id')
                ->from('tebar_history his')
                ->where('his.deleted', 0)
                ->where('his.tebar_id', $tebar_id)
                ->where('his.asal_kolam_id', $id)
                ->order_by('sequence desc')
                ->limit(1)
                ->get();
        }
        $temp = $query->result()[0];
        if($temp->sampling_id != 0){
            $query = $this->db->select('id')
                ->from('pemberian_pakan')
                ->where('sampling_id', $temp->sampling_id)
                ->where('deleted', 0)
                ->get();
            $pakan_id = $query->result()[0]->id;
        } else {
            $query = $this->db->select('id')
                ->from('pemberian_pakan')
                ->where('grading_id', $temp->grading_id)
                ->where('deleted', 0)
                ->get();
            $pakan_id = $query->result()[0]->id;
        }
        $data = array(
            'pemberian_pakan_id' => $pakan_id,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('id', $id);
        return $this->db->update('kolam', $data);
    }


    public function update_pemberian_pakan_sampling($kolam_lama, $pemberian_pakan_id, $tebar_id, $id, $write_uid){
        $this->get_last_pakan($kolam_lama, $tebar_id, 0, 1, $write_uid);
        $data = array(
            'pemberian_pakan_id' => $pemberian_pakan_id,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('id', $id);
        return $this->db->update('kolam', $data);
    }

    public function get_total_pakan_for_grading($kolam_id){
        $query = $this->db->select('sum(mpakan.jumlah_pakan) as total_pakan')
            ->from('monitoring_pakan mpakan')
            ->join('kolam', 'kolam.id = mpakan.kolam_id and kolam.pemberian_pakan_id = mpakan.pemberian_pakan_id')
            ->where('mpakan.deleted', 0)
            ->where('kolam.id', $kolam_id)
            ->get();
        $result =  $query->result();
        if (sizeof($result)>0){
            return $result[0]->total_pakan;
        } else {
            return 0;
        }
    }


    public function get_kolam_for_grading($blok_id, $grading_id){
        $query = $this->db->select('kolam.id, kolam.name, blok.name as blok_name')
            ->from('kolam')
            ->join('blok', 'blok.id = kolam.blok_id')
            ->join('tebar_history th', 'th.tujuan_kolam_id = kolam.id', 'left')
            ->where('kolam.deleted', 0)
            ->where('blok.id', $blok_id)
            ->group_start()
            ->or_where('th.id !=', null)
            ->where('th.grading_id',$grading_id)
            ->group_end()
            ->get();
        return $query->result_array();
    }


    public function is_available_gradingid($kolam_id, $grading_id){
        $query = $this->db->select('k.tebar_id, p.grading_id')
            ->from('kolam k')
            ->join('pemberian_pakan p', 'p.id = k.pemberian_pakan_id')
            ->where('k.id',$kolam_id)
            ->get();
        $res = $query->result_array();
        if(sizeof($res)>0){
            if($res[0]["tebar_id"] == 0 or $res[0]["grading_id"] == $grading_id){
                return True;
            } else {
                return False;
            }
        } else {
            return False;
        }
    }


    public function update_kolam_by_delete_tebar($id, $tebar_id, $write_uid){
        $data = array(
            'pemberian_pakan_id' => 0,
            'tebar_id' => 0,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('id', $id);
        $this->db->where('tebar_id', $tebar_id);
        return $this->db->update('kolam', $data);
    }

}