<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class User_model extends CI_Model
{
	var $table = 'user';
	var $id = 'id_user';

	public function __construct()
    {
        parent::__construct();
    }

    function create($param)
    {
        $query = $this->db->insert($this->table, $param);
        return $query;
    }

	function lists($param)
	{
		$where = array();
        $or_where = array();
        if (isset($param['q'])) {
            $where += array('username LIKE ' => '%' . $param['q'] . '%');
            $or_where += array('email LIKE ' => '%' . $param['q'] . '%');
        }

        $this->db->select('id_user,username,password,nama_depan,nama_tengah,nama_belakang,email,no_hp,
            barcode,tgl_lahir,alamat,id_negara,id_prov,id_kota,id_kec,id_desa,id_foto,id_banner,status,tgl_add');
        $this->db->from($this->table);
        $this->db->where($where);
        $this->db->or_where($or_where);
        $this->db->order_by($param['order'], $param['sort']);
        $this->db->limit($param['limit'], $param['offset']);
        $query = $this->db->get()->result();
        return $query;
	}

	function lists_count($param)
    {
        $where = array();
        $or_where = array();
        if (isset($param['q']))
        {
            $where += array('username LIKE ' => '%'.$param['q'].'%');
            $or_where += array('email LIKE ' => '%'.$param['q'].'%');
        }
        
        $this->db->select('id_user');
        $this->db->from($this->table);
        $this->db->where($where);
        $this->db->or_where($or_where);
        $query = $this->db->count_all_results();
        return $query;
    }

	function update($id, $param)
    {
        $this->db->where($this->id, $id);
        $query = $this->db->update($this->table, $param);
        return $query;
    }

	function delete($id)
    {
        $this->db->where($this->id, $id);
        $query = $this->db->delete($this->table);
        return $query;
    }
    
    function info($param)
    {
        $where = array();
        if (isset($param['id_user']))
        {
            $where += array('id_user' => $param['id_user']);
        }

        $this->db->select('id_user,username,password,nama_depan,nama_tengah,nama_belakang,email,no_hp,
            barcode,tgl_lahir,alamat,id_negara,id_prov,id_kota,id_kec,id_desa,id_foto,id_banner,status,tgl_add');
        $this->db->from($this->table);
        $this->db->where($where);
        $query = $this->db->get();
        return $query;
    }

    function infos($id)
    {
        $where = array();
        if (isset($id))
        {
            $where += array('id_user' => $id);
        }

        $this->db->select('id_user,username,password,nama_depan,nama_tengah,nama_belakang,email,no_hp,
            barcode,tgl_lahir,alamat,id_negara,id_prov,id_kota,id_kec,id_desa,id_foto,id_banner,status,tgl_add');
        $this->db->from($this->table);
        $this->db->where($where);
        $query = $this->db->get()->result();
        return $query;
    }

    function infos_count($id)
    {
        $where = array();
        if (isset($id))
        {
            $where += array('id_user' => $id);
        }
        
        $this->db->select('id_user');
        $this->db->from($this->table);
        $this->db->where($where);
        $query = $this->db->count_all_results();
        return $query;
    }

    function get_data_negara($id)
    {
        $where = array();
        if (isset($id))
        {
            $where += array('id_negara' => $id);
        }

        $this->db->select('id_negara,name');
        $this->db->from('negara');
        $this->db->where($where);
        $query = $this->db->get()->result();
        return $query;
    }

    function get_data_provinsi($id)
    {
        $where = array();
        if (isset($id))
        {
            $where += array('id_provinsi' => $id);
        }

        $this->db->select('id_provinsi,name');
        $this->db->from('provinsi');
        $this->db->where($where);
        $query = $this->db->get()->result();
        return $query;
    }

    function get_data_kota($id)
    {
        $where = array();
        if (isset($id))
        {
            $where += array('id_kota' => $id);
        }

        $this->db->select('id_kota,name');
        $this->db->from('kota');
        $this->db->where($where);
        $query = $this->db->get()->result();
        return $query;
    }

    function get_data_kecamatan($id)
    {
        $where = array();
        if (isset($id))
        {
            $where += array('id_kecamatan' => $id);
        }

        $this->db->select('id_kecamatan,name');
        $this->db->from('kecamatan');
        $this->db->where($where);
        $query = $this->db->get()->result();
        return $query;
    }

    function get_data_desa($id)
    {
        $where = array();
        if (isset($id))
        {
            $where += array('id_desa' => $id);
        }

        $this->db->select('id_desa,name');
        $this->db->from('desa');
        $this->db->where($where);
        $query = $this->db->get()->result();
        return $query;
    }
}

/* End of file User_model.php */
/* Location: ./application/models/User_model.php */