<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Guide extends CI_Controller {

	var $table	 = 'tb_guide';
	var $section = 'Guide';

	function __construct(){
		parent::__construct();
		if($this->session->userdata('masuk')!=TRUE && $this->session->userdata('access')!='admin'){$url=base_url('login');redirect($url);};

		$this->load->model(['model','m_validation']);
		$this->load->library(['form_validation','encryption','cloudinarylib']);
	}

	public function index()
	{
		$data = [
					'content' 	=> $this->section.('/view'),
					'section' 	=> $this->section,
					'show'		=> $this->model->get_all($this->table)->result(),
				];
		$this->load->view('template', $data);
	}

	public function post()
	{

		$data = [
					'content' 	=> $this->section.('/post'),
					'section' 	=> $this->section,
				];
		$this->load->view('template', $data);
	}

	public function save(){

		var_dump($_FILES);die;

		$post 		= $this->input->post();

		$validasi 	= $this->form_validation->set_rules($this->m_validation->val_guide());
		if($validasi->run()==false){
			$data = [
					'content' 	=> $this->section.('/post'),
					'section' 	=> $this->section,
				];
			$this->load->view('template', $data);
		}else{
			$data = [
						'id_guide'			=> null,
						'NIK'				=> $post['nik'],
						'email'				=> $post['email'],
						'guide_name'		=> $post['name'],
						'no_phone'			=> $post['hp'],
						'age'				=> $post['age'],
						'gender'			=> $post['gender'],
						'image'				=> $this->upload(),
						'id_destination'	=> 1,
						'status'			=> 1,
						'password'			=> "$2b$07$DQsrkCHHxNQIKX04PhQsCOZjIdRemoahpmaTLl61z81CJEDQMm8we"
					];

					if ($data['image'] != ''){

						$this->model->save($this->table, $data);

						$this->session->set_flashdata('flash', '<div class="alert alert-success alert-dismissible fade show " role="alert">Your data has been saved successfully.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
						redirect('guide/post');
					}else{

						$this->session->set_flashdata('flash', '<div class="alert alert-danger alert-dismissible fade show" role="alert">The image failed to upload !<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
						
						$data = [
								'content' 	=> $this->section.('/post'),
								'section' 	=> $this->section,
							];
						$this->load->view('template', $data);
					}

		}
		
	}


	public function edit($id=null)
	{
		if(!isset($id)) show_404();
		$id = str_replace(['-','_','~'],['=','+','/'],$id);
		$id = $this->encryption->decrypt($id);

		$data = [
					'content' 	=> $this->section.('/edit'),
					'section' 	=> $this->section,
					'show'		=> $this->model->get_by($this->table, 'id_guide', $id)->result(),
					'id'		=> str_replace(['=','+','/'], ['-','_','~'], $this->encryption->encrypt($id))

				];
		$this->load->view('template', $data);
	}


	public function update($id=null)
	{
		if(!isset($id)) show_404();
		$post 		= $this->input->post();
		$id 		= $this->encryption->decrypt(str_replace(['-','_','~'],['=','+','/'],$id));
		$validasi 	= $this->form_validation->set_rules($this->m_validation->val_editGuide());
		if($validasi->run()==false){
			$data = [
					'content' 	=> $this->section.('/edit'),
					'section' 	=> $this->section,
					'show'		=> $this->model->get_by($this->table, 'id_guide', $id)->result(),
					'id'		=> str_replace(['=','+','/'], ['-','_','~'], $this->encryption->encrypt($id))

				];
			$this->load->view('template', $data);
		}else{
			$data = [
					'guide_name'		=> $post['name'],
					'no_phone'			=> $post['hp'],
					'age'				=> $post['age'],
					'gender'			=> $post['gender'],
					'id_destination'	=> 1,
					'status'			=> 1,
					];
			$this->model->update($this->table, 'id_guide', $id, $data);
			$this->session->set_flashdata('flash', '<div class="alert alert-success alert-dismissible fade show mb-3" role="alert">Data has been updated successfully.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
			redirect('guide');
		}
	}



	public function delete($id=null)
	{
		if(!isset($id)) show_404();
		$id = str_replace(['-','_','~'],['=','+','/'],$id);
		$id = $this->encryption->decrypt($id);
		$this->model->delete($this->table, 'id_guide' , $id);
		$this->session->set_flashdata('flash', '<div class="alert alert-danger alert-dismissible fade show " role="alert">Data has been delete successfully.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
		redirect('guide');
	}


	private function upload()
	{
		$name = $_FILES['foto']['name'];

		$config['upload_path'] 		= './assets/img/guide';
		$config['allowed_types'] 	= 'jpeg|jpg|png';
		$config['file_name']		= $name;
		$config['max_size']  		= '2048';
		
		$this->load->library('upload', $config);
		if ( $this->upload->do_upload('foto')){
			// $upload = \Cloudinary\Uploader::upload("https://kojiflowers.com/wp-content/uploads/2017/01/vide-1050x478.png");
			return $this->upload->data('file_name');
		}else{
			return "";
		}
	}

}

/* End of file guide.php */
/* Location: ./application/controllers/guide.php */