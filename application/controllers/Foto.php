<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'/libraries/REST_Controller.php';
class Foto extends REST_Controller {
	public function __construct()
    {
        parent::__construct();
        $this->load->model('foto_model');
    }

    function create_post()
	{
		$this->benchmark->mark('code_start');
		$validation = 'ok';

		$id_album 		= filter($this->post('id_album'));
		$name 			= filter($this->post('name'));
		$description 	= filter($this->post('description'));
		$link 			= filter($this->post('link'));
		$posisi 		= filter($this->post('posisi'));
		$tgl_add 		= filter($this->post('tgl_add'));
		$tgl_update 	= filter($this->post('tgl_update'));
		
		$data = array();
		if ($id_album == '')
		{
			$data['id_album'] = 'Required';
			$validation = 'error';
			$code = 400;
		}
		if ($name == '')
		{
			$data['name'] = 'Required';
			$validation = 'error';
			$code = 400;
		}
		if ($validation == 'ok')
		{
			$param = array();
			$param['id_foto'] 		= '';
			$param['id_album'] 		= $id_album;
			$param['name'] 			= $name;
			$param['description'] 	= $description;
			$param['link'] 			= $link;
			$param['tgl_add'] 		= date('Y-m-d H:i:s');
			$param['tgl_update'] 	= date('Y-m-d H:i:s');

			$query = $this->foto_model->create($param);
			if ($query > 0)
			{
				$data['create'] = 'Success';
				$validation = 'ok';
				$code = 200;
			}
			else
			{
				$data['create'] = 'Failed';
				$validation = 'error';
				$code = 400;
			}
		}
		
		$output = array();
		$output['message'] = $validation;
		$output['code'] = $code;
		$output['result'] = $data;
		$this->benchmark->mark('code_end');
		$output['load'] = $this->benchmark->elapsed_time('code_start', 'code_end') . ' seconds';
		$this->response($output, $code);
	}

	public function lists_get()
	{
		$this->benchmark->mark('code_start');
		$offset = filter($this->get('offset'));
		$limit = filter($this->get('limit'));
		$order = filter($this->get('order'));
		$sort = filter($this->get('sort'));
		$q = filter($this->get('q'));

		$default_order = array('tgl_add','id_foto','id_album','name');
		$default_sort = array('desc','asc');

		if ($limit != '' && $limit < 20)
		{
			$limit = $limit;
		}
		else
		{
			$limit = 20;
		}
		
		if ($offset != '')
		{
			$offset = $offset;
		}
		else
		{
			$offset = 0;
		}
		
		if (is_array(in_array($order, $default_order) && ($order != '')))
		{
			$order = $order;
		}
		else
		{
			$order = 'tgl_add';
		}
		
		if (is_array(in_array($sort, $default_sort) && ($sort != '')))
		{
			$sort = $sort;
		}
		else
		{
			$sort = 'desc';
		}
		
		$param = array();
		$param2 = array();
		$param['limit'] = $limit;
		$param['offset'] = $offset;
		$param['order'] = $order;
		$param['sort'] = $sort;
		$param['q'] = $q;
		$param2['q'] = $q;

		$query = $this->foto_model->lists($param);
		


		$data = array();
		if ($query)
		{
			foreach ($query as $i => $u)
			{
				$data[$i] = array(
					'id_foto' 		=> $u->id_foto,
					'id_album' 		=> $u->id_album,
					'name' 			=> $u->name,
					'description' 	=> $u->description,
					'link' 			=> $u->link,
					'tgl_add' 		=> $u->tgl_add,
					'tgl_update'	=> $u->tgl_update
				);
			}
		}

		$output = array();
		$output['message'] = 'ok';
		$output['code'] = 200;
		$output['limit'] = intval($limit);
		$output['offset'] = intval($offset);
		$output['total'] = intval($total);
		$output['result'] = $data;
		$this->benchmark->mark('code_end');
		$output['load'] = $this->benchmark->elapsed_time('code_start', 'code_end') . ' seconds';
		$this->response($output, 200);
	}

	function update_post()
	{
		$this->benchmark->mark('code_start');
		$validation = 'ok';
		
		$id_foto 		= filter($this->post('id_foto'));
		$id_album 		= filter($this->post('id_album'));
		$name 			= filter($this->post('name'));
		$description 	= filter($this->post('description'));
		
		$data = array();
		if ($id_foto == '')
		{
			$data['id_foto'] = 'Required';
			$validation = 'error';
			$code = 400;
		}
		
		if ($validation == 'ok')
		{
			$query = $this->foto_model->info(array('id_foto' => $id_foto));
			
			if ($query->num_rows() > 0)
			{
				$param = array();
				if ($name != '')
				{
					$param['name'] = $name;
				}
				
				if ($id_album != '')
				{
					$param['id_album'] = $id_album;
				}
				
				if ($description != '')
				{
					$param['description'] = $description;
				}
				
				if (count($param) > 0)
				{
					$param['tgl_update'] = date('Y-m-d H:i:s');
					$update = $this->foto_model->update($id_foto, $param);
					
					if ($update > 0)
					{
						$data['update'] = 'Success';
						$validation = 'ok';
						$code = 200;
					}
					else
                    {
                        $data['update'] = 'Failed';
                        $validation = 'error';
                        $code = 400;
                    }
				}
				else
				{
					$data['update'] = 'Nothing to update';
					$validation = 'ok';
					$code = 200;
				}
			}
			else
			{
				$data['update'] = 'ID Not Found';
				$validation = 'error';
				$code = 400;
			}
		}
		
		$output = array();
		$output['message'] = $validation;
		$output['code'] = $code;
		$output['result'] = $data;
		$this->benchmark->mark('code_end');
		$output['load'] = $this->benchmark->elapsed_time('code_start', 'code_end') . ' seconds';
		$this->response($output, $code);
	}

	function delete_post()
	{
		$this->benchmark->mark('code_start');
		$validation = 'ok';
		
        $id_foto = filter($this->post('id_foto'));
        
		$data = array();
        if ($id_foto == '')
		{
			$data['id_foto'] = 'Required';
			$validation = "error";
			$code = 400;
		}
        
        if ($validation == "ok")
		{
            $query = $this->foto_model->info(array('id_foto' => $id_foto));
			
			if ($query->num_rows() > 0)
			{
                $delete = $this->foto_model->delete($id_foto);
				
				if ($delete > 0)
				{
					$data['delete'] = 'Success';
					$validation = "ok";
					$code = 200;
				}
				else
				{
					$data['delete'] = 'Failed';
					$validation = "error";
					$code = 400;
				}
			}
			else
			{
				$data['delete'] = 'ID Foto Not Found';
				$validation = "error";
				$code = 400;
			}
		}
		
		$rv = array();
		$rv['message'] = $validation;
		$rv['code'] = $code;
		$rv['result'] = $data;
		$this->benchmark->mark('code_end');
		$rv['load'] = $this->benchmark->elapsed_time('code_start', 'code_end') . ' seconds';
		$this->response($rv, $code);
	}

	public function info_get()
	{
		$this->benchmark->mark('code_start');

		$id_foto = filter($this->get('id_foto'));
		$data = array();

		if($id_foto == '')
		{
			$data['id_foto'] = 'Required';
			$validation = "error";
			$code = 400;

			$rv = array();
			$rv['message'] = $validation;
			$rv['code'] = $code;
			$rv['result'] = $data;
			$this->benchmark->mark('code_end');
			$rv['load'] = $this->benchmark->elapsed_time('code_start', 'code_end') . ' seconds';
			$this->response($rv, $code);
		}
		else
		{
			$query = $this->foto_model->infos($id_foto);
			$total = $this->foto_model->infos_count($id_foto);

			if($total == "1")
			{
				if ($query)
				{
					foreach ($query as $i => $u)
					{
						$data[$i] = array(
							'id_foto' 		=> $u->id_foto,
							'id_album' 		=> $u->id_album,
							'name' 			=> $u->name,
							'description' 	=> $u->description,
							'link' 			=> $u->link,
							'tgl_add' 		=> $u->tgl_add,
							'tgl_update'	=> $u->tgl_update
						);
					}
				}

				$output = array();
				$output['message'] = 'ok';
				$output['code'] = 200;
				$output['result'] = $data;
				$this->benchmark->mark('code_end');
				$output['load'] = $this->benchmark->elapsed_time('code_start', 'code_end') . ' seconds';
				$this->response($output, 200);
			}
			else
			{
				$output = array();
				$data['id_foto'] = 'ID photos not found';
				$output['message'] = 'error';
				$output['code'] = 400;
				$output['result'] = $data;
				$this->benchmark->mark('code_end');
				$output['load'] = $this->benchmark->elapsed_time('code_start', 'code_end') . ' seconds';
				$this->response($output, 400);
			}
		}
	}
}

/* End of file Post.php */
/* Location: ./application/controllers/Post.php */