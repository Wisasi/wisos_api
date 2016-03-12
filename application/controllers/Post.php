<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'/libraries/REST_Controller.php';
class Post extends REST_Controller {
	public function __construct()
    {
        parent::__construct();
        $this->load->model('post_model');
    }

    function create_post()
	{
		$this->benchmark->mark('code_start');
		$validation = 'ok';

		$id_user 	= filter($this->post('id_user'));
		$id_foto 	= filter(trim($this->post('id_foto')));
		$id_video 	= filter(trim($this->post('id_video')));
		$tag 		= filter(trim($this->post('tag')));
		$tgl_add 	= filter(trim($this->post('tgl_add')));
		$description= filter($this->post('description'));
		
		$data = array();
		if ($id_user == '')
		{
			$data['id_user'] = 'Required';
			$validation = 'error';
			$code = 400;
		}
		if ($description == '')
		{
			$data['description'] = 'Required';
			$validation = 'error';
			$code = 400;
		}
		if ($validation == 'ok')
		{
			$param = array();
			$param['id_post'] 	= '';
			$param['id_user'] 	= $id_user;
			$param['id_foto'] 	= $id_foto;
			$param['id_video'] 	= $id_video;
			$param['tag'] 		= $tag;
			$param['tgl_add'] 	= date('Y-m-d H:i:s');
			$param['tgl_update']= date('Y-m-d H:i:s');
			$param['description'] 		= $description;
			$query = $this->post_model->create($param);
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

		$default_order = array('tgl_add','id_post','description');
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

		$query = $this->post_model->lists($param);
		$total = $this->post_model->lists_count($param2);

		$data = array();
		if ($query)
		{
			foreach ($query as $i => $u)
			{
				$data[$i] = array(
					'id_post' 	=> $u->id_post,
					'id_user' 	=> $u->id_user,
					'description' 		=> $u->description,
					'id_foto' 	=> $u->id_foto,
					'id_video' 	=> $u->id_video,
					'tag' 		=> $u->tag,
					'tgl_add' 	=> $u->tgl_add,
					'tgl_update'=> $u->tgl_update
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
		
		$id_post 	= filter($this->post('id_post'));
		$description 		= filter($this->post('description'));
		$id_foto 	= filter($this->post('id_foto'));
		$id_video 	= filter($this->post('id_video'));
		$tag 		= filter($this->post('tag'));
		
		$data = array();
		if ($id_post == '')
		{
			$data['id_post'] = 'Required';
			$validation = 'error';
			$code = 400;
		}
		
		if ($validation == 'ok')
		{
			$query = $this->post_model->info(array('id_post' => $id_post));
			
			if ($query->num_rows() > 0)
			{
				$param = array();
				if ($description != '')
				{
					$param['description'] = $description;
				}
				
				if ($id_foto != '')
				{
					$param['id_foto'] = $id_foto;
				}
				
				if ($id_video != '')
				{
					$param['id_video'] = $id_video;
				}

				if ($tag != '')
				{
					$param['tag'] = $tag;
				}
				
				if (count($param) > 0)
				{
					$param['tgl_update'] = date('Y-m-d H:i:s');
					$update = $this->post_model->update($id_post, $param);
					
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
		
        $id_post = filter($this->post('id_post'));
        
		$data = array();
        if ($id_post == '')
		{
			$data['id_post'] = 'Required';
			$validation = "error";
			$code = 400;
		}
        
        if ($validation == "ok")
		{
            $query = $this->post_model->info(array('id_post' => $id_post));
			
			if ($query->num_rows() > 0)
			{
                $delete = $this->post_model->delete($id_post);
				
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
				$data['delete'] = 'ID Not Found';
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
}

/* End of file Post.php */
/* Location: ./application/controllers/Post.php */