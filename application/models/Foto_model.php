<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Foto_model extends CI_Model
{
	var $table = 'foto';
	var $id = 'id_foto';

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
            $where += array('name LIKE ' => '%' . $param['q'] . '%');
            $or_where += array('description LIKE ' => '%' . $param['q'] . '%');
        }

        $this->db->select('id_foto, id_album, name, description, link, posisi, tgl_add, tgl_update');
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
            $where += array('description LIKE ' => '%'.$param['q'].'%');
            $or_where += array('name LIKE ' => '%'.$param['q'].'%');
        }
        
        $this->db->select('id_foto');
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
        if (isset($param['id_foto']))
        {
            $where += array('id_foto' => $param['id_foto']);
        }
        if (isset($param['id_album']))
        {
            $where += array('id_album' => $param['id_album']);
        }
        if (isset($param['name']))
        {
            $where += array('name' => $param['name']);
        }
        if (isset($param['description']))
        {
            $where += array('description' => $param['description']);
        }
        
        $this->db->select('id_foto, id_album, name, description, link, tgl_add, tgl_update');
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
            $where += array('id_foto' => $id);
        }

        $this->db->select('id_foto, id_album, name, description, link, posisi, tgl_add, tgl_update');
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
            $where += array('id_foto' => $id);
        }
        
        $this->db->select('id_foto');
        $this->db->from($this->table);
        $this->db->where($where);
        $query = $this->db->count_all_results();
        return $query;
    }
}

/* End of file Foto_model.php */
/* Location: ./application/models/Foto_model.php */