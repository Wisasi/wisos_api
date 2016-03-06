<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Post_model extends CI_Model
{
	var $table = 'post';
	var $id = 'id_post';

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
            $where += array('post LIKE ' => '%' . $param['q'] . '%');
            $or_where += array('tag LIKE ' => '%' . $param['q'] . '%');
        }

        $this->db->select('id_post, id_user, post, id_foto, id_video, tag, tgl_add, tgl_update');
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
            $where += array('post LIKE ' => '%'.$param['q'].'%');
            $or_where += array('tag LIKE ' => '%'.$param['q'].'%');
        }
        
        $this->db->select('id_post');
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
        if (isset($param['id_post']))
        {
            $where += array('id_post' => $param['id_post']);
        }
        if (isset($param['post']))
        {
            $where += array('post' => $param['post']);
        }
        if (isset($param['tag']))
        {
            $where += array('tag' => $param['tag']);
        }
        
        $this->db->select('id_post, id_user, post, id_foto, id_video, tag, tgl_add, tgl_update');
        $this->db->from($this->table);
        $this->db->where($where);
        $query = $this->db->get();
        return $query;
    }
}
?>