<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Board extends CI_Controller {


	function __construct(){
		parent::__construct();
		date_default_timezone_set('America/Vancouver');
		$this->load->database();
		$this->load->model('board_m');
	}
	public function index()
	{
		$this->lists();
	}


	public function _remap($method){
		$this->load->view('layout/header_v');

		if(method_exists($this, $method)){
			$this->{"{$method}"}();
		}

		$this->load->view('layout/footer_v');
	}

	 /**
	 * Main Page - Show full list of forum
	 *
	 */
	public function lists(){


		$table = $this->uri->segment(3);

		$q = $soption = $sterm ='';
		$uri_segment = 5;

		$uri_array = $this->segment_explode($this->uri->uri_string());

		if(in_array('q', $uri_array)){
			$soption = urldecode($this->url_exploded($uri_array, 'q'));
			$sterm = urldecode($this->url_exploded($uri_array, $soption));

			$q = '/q/'.$soption.'/'.$sterm;
			$uri_segment = 8;
		}


		//Pagination
		$this->load->library('pagination');

		$config['base_url'] = '/board/lists/ci_board'.$q.'/page/';
		$config['total_rows'] = $this->board_m->get_list($table, 'count','','',$soption, $sterm);
		$config['per_page'] = 5;
		$config['uri_segment'] = $uri_segment;

		//Apply Twitter bootstrap style to pagination
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['first_link'] = false;
		$config['last_link'] = false;
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['prev_link'] = '&laquo';
		$config['prev_tag_open'] = '<li class="prev">';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = '&raquo';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';

		//Dont forget to initialize pagination with $config value!
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();


		//Set $pages variable to 1 if 5th segment does not have any value.
		$pages = $this->uri->segment($uri_segment,1);

		//Set start and limit for pagination
		if($pages > 1){
			$start = ($pages/$config['per_page']) * $config['per_page'];
		}else{
			$start = ($pages - 1) * $config['per_page'];
		}

		$limit = $config['per_page'];

		//Need to fix here array not working, need to make function instead.
		$data['search_result'] = array(
			'soption' => $soption,
			'sterm' => $sterm
		);
		// $data['comments'] = $this->board_m->get_list($table,'comment',$start,$limit , $soption, $sterm);
		$data['lists'] = $this->board_m->get_list($table,'',$start,$limit, $soption, $sterm);
		// echo '<pre>';
		// 	var_dump($data);
		// echo '</pre>';
		$this->load->view('board/list_v', $data);
	}


	/**
	 * View selected Post
	 * @return [type] [description]
	 */
	public function view(){
		$table = $this->uri->segment(3);
		$id = $this->uri->segment(5);

		$data['views'] = $this->board_m->get_view($table, $id);
		$data['comment_list'] = $this->board_m->get_comment($table, $id);
		$this->load->view('board/view_v', $data);
	}


	public function newpost(){
		$this->form_validation->set_rules('title', 'TITLE', 'required');
		$this->form_validation->set_rules('body', 'CONTENTS', 'required');

		if($this->session->userdata('logged_in') == TRUE){

				if($_POST){
					if($this->form_validation->run() == TRUE){
						$title = $this->input->post('title', TRUE);
						$body = $this->input->post('body', TRUE);

						$newdata = array(
							'table' => $this->uri->segment(3),
							'title' => $title,
							'body' => $body,
							'author' => $this->session->userdata('user_id')
						);

						$result = $this->board_m->create_post($newdata);

						if($result){
							alert('Post Created', '/board/lists/ci_board/page/1');
							exit;
						}else{
							alert('Post failed, Please try again', '/board/lists/ci_board/page/1');
							exit;
						}
					}else{
						$this->load->view('board/write_v');
					}
				}else{
					$this->load->view('board/write_v');
				}

		}else{
			alert('Please log in first', '/board/lists/ci_board/page/1');
			exit;
		}
	}

	public function edit(){
		$table = $this->uri->segment(3);
		$id = $this->uri->segment(5);

		if($this->session->userdata('logged_in') == TRUE){
			if($_POST){
				$edit_title = $this->input->post('title', TRUE);
				$edit_body = $this->input->post('body', TRUE);

				$editData = array(
					'table' => $this->uri->segment(3),
					'board_id' => $this->uri->segment(5),
					'title' => $edit_title,
					'body' => $edit_body,
				);

				$result = $this->board_m->modify_post($editData);

				if($result){
					alert('Post Edited', '/board/lists/ci_board/page/1');
				}else{
					alert('Post edit failed, Please try again', '/board/lists/ci_board/page/1');
				}
			}else{
				$data['views'] = $this->board_m->get_view($table, $id);
				$this->load->view('board/edit_v', $data);
			}
		}else{
			alert('login first', '/board/lists/ci_board/page/1');
			exit;
		}
	}

	public function delete(){
		if($this->session->userdata('logged_in') == TRUE){
			$table = $this->uri->segment(3);
			$id = $this->uri->segment(5);

			$return = $this->board_m->delete_post($table, $id);

			if($return){
				alert('Post deleted', '/board/lists/ci_board/page/1');
			}
		}else{
			alert('login first', '/board/lists/ci_board/page/1');
			exit;
		}
	}

	function segment_explode($uri){
		//Get length of current uri
		$len = strlen($uri);

		//If there is a '/' at the first index,
		//Set new $uri starting from index 1.
		if(substr($uri, 0, 1) == '/'){
			$uri = $substr($uri, 1, $len);
		}

		//If there is a '/' at the end,
		//Set new $uri from index 0 to length -1.
		if(substr($uri, -1) == '/'){
			$uri = $substr($uri, 0, $len-1);
		}

		return explode('/', $uri);
	}

	function url_exploded($arrays, $key){
		$cnt = count($arrays);
		for($i=0; $i < $cnt; $i++){
			if($arrays[$i] == $key){
				$target = $arrays[$i+1];
				return $target;
			}
		}

	}

}
