<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'/libraries/REST_Controller.php';
class User extends REST_Controller {
	public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
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

		$default_order = array('tgl_add','id_user','username');
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

		$query = $this->user_model->lists($param);
		$total = $this->user_model->lists_count($param2);

		$data = array();
		if ($query)
		{
			foreach ($query as $i => $u)
			{
				$data[$i] = array(
					'id_user' 		=> $u->id_user,
					'username' 		=> $u->username,
					'password' 		=> $u->password,
					'nama_depan' 	=> $u->nama_depan,
					'nama_tengah' 	=> $u->nama_tengah,
					'nama_belakang' => $u->nama_belakang,
					'email' 		=> $u->email,
					'no_hp' 		=> $u->no_hp,
					'barcode' 		=> $u->barcode,
					'tgl_lahir' 	=> $u->tgl_lahir,
					'alamat' 		=> $u->alamat,
					'id_negara' 	=> $u->id_negara,
					'id_prov' 		=> $u->id_prov,
					'id_kota' 		=> $u->id_kota,
					'id_kec' 		=> $u->id_kec,
					'id_desa' 		=> $u->id_desa,
					'id_foto' 		=> $u->id_foto,
					'id_banner' 	=> $u->id_banner,
					'status' 		=> $u->status,
					'tgl_add' 		=> $u->tgl_add
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

	public function info_get()
	{
		$this->benchmark->mark('code_start');

		$id_user = filter($this->get('id_user'));
		$data = array();

		if($id_user == '')
		{
			$data['id_user'] = 'Required';
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
			$query = $this->user_model->infos($id_user);
			$total = $this->user_model->infos_count($id_user);

			if($total == "1")
			{
				if ($query)
				{
					foreach ($query as $i => $u)
					{
						$info_negara 	= $this->user_model->get_data_negara($u->id_negara);
						$info_provinsi 	= $this->user_model->get_data_provinsi($u->id_prov);
						$info_kota 		= $this->user_model->get_data_kota($u->id_kota);
						$info_kecamatan = $this->user_model->get_data_kecamatan($u->id_kec);
						$info_desa 		= $this->user_model->get_data_desa($u->id_desa);

						if ($info_negara)
						{
							foreach ($info_negara as $n => $ne)
							{
								$negara = array(
									'id_negara' 	=> $ne->id_negara,
									'name' 			=> $ne->name
								);
							}
						}
						if ($info_provinsi)
						{
							foreach ($info_provinsi as $p => $pr)
							{
								$provinsi = array(
									'id_provinsi' 	=> $pr->id_provinsi,
									'name' 			=> $pr->name
								);
							}
						}
						if ($info_kota)
						{
							foreach ($info_kota as $k => $ko)
							{
								$kota = array(
									'id_kota' 		=> $ko->id_kota,
									'name' 			=> $ko->name
								);
							}
						}
						if ($info_kecamatan)
						{
							foreach ($info_kecamatan as $ke => $kec)
							{
								$kecamatan = array(
									'id_kecamatan' 	=> $kec->id_kecamatan,
									'name' 			=> $kec->name
								);
							}
						}
						if ($info_desa)
						{
							foreach ($info_desa as $d => $de)
							{
								$desa = array(
									'id_desa' 		=> $de->id_desa,
									'name' 			=> $de->name
								);
							}
						}

						$data = array(
							'id_user' 		=> $u->id_user,
							'username' 		=> $u->username,
							'password' 		=> $u->password,
							'nama_depan' 	=> $u->nama_depan,
							'nama_tengah' 	=> $u->nama_tengah,
							'nama_belakang' => $u->nama_belakang,
							'email' 		=> $u->email,
							'no_hp' 		=> $u->no_hp,
							'barcode' 		=> $u->barcode,
							'tgl_lahir' 	=> $u->tgl_lahir,
							'alamat' 		=> $u->alamat,
							'negara' 		=> $negara,
							'provinsi' 		=> $provinsi,
							'kota' 			=> $kota,
							'kecamatan' 	=> $kecamatan,
							'desa' 			=> $desa,
							'id_foto' 		=> $u->id_foto,
							'id_banner' 	=> $u->id_banner,
							'status' 		=> $u->status,
							'tgl_add' 		=> $u->tgl_add
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
				$data['id_user'] = 'ID User not found';
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