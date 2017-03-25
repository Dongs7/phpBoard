<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	function __construct(){
		parent::__construct();
    $this->load->database();
    $this->load->model('Auth_m');
	}

  public function index(){
    $this->login();
  }
	/**
	 * Function to add header and footer in the page automatically
	 */
  public function _remap($method){
		$this->load->view('layout/header_v');

		if(method_exists($this, $method)){
			$this->{"{$method}"}();
		}

		$this->load->view('layout/footer_v');
	}


  public function login(){

    //Set rules for the fields
    $this->form_validation->set_rules('user_id','USER ID','required');
    $this->form_validation->set_rules('password','PASSWORD','required');

    //If validation is true,
    if($this->form_validation->run() == TRUE){

			//Set second parameter to True for preventing XSS attack
      $auth_data = array(
        'user_id'=>$this->input->post('user_id', TRUE),
        'password'=>$this->input->post('password', TRUE),
      );

      //Send $authData array to Auth model and get the result
      $result = $this->Auth_m->login_check($auth_data);


			//If result has a data for the user,
      if($result){

        $newData = array(
          'user_id' => $result->user_id,
          'user_email' => $result->user_email,
          'level'=> $result->level,
          'profile' => $result->profile,
					'points' => $result->points,
          'logged_in' => TRUE
        );

				//Set session user data using the data received
        $this->session->set_userdata($newData);

        alert('logged in', '/board/lists/ci_board/page/1');
        exit;
      }else{
        alert('Login failed. Please try again', '/board/lists/ci_board/page/1');
        exit;
      }
    }else{
      $this->load->view('auth/login_v');
    }
  }

  /**
   * LOGOUT
   * Destroy current user session
   */
  function logout(){
    $result = $this->session->sess_destroy();
    alert('logged out', '/board/lists/ci_board/page/1');
    exit;
  }

  /**
   * Profile page if user logged in
   * Get Current session user info
   */
  function profile(){
    if($this->session->userdata('logged_in') == TRUE){
      $this->load->view('auth/profile_v', array('error'=>''));
    }
  }


  /**
   * Change User profile picture function
   *
   */
  function upload(){
    $config['upload_path'] = './public/profile/';
    $config['allowed_types'] = 'jpg|gif|png';
    $config['max_size'] = 100;

    $this->load->library('upload', $config);

    if(! $this->upload->do_upload('userfile')){
      $error = array('error'=>$this->upload->display_errors());
      $this->load->view('auth/profile_v', array('error'=>''));
    }else{
      $data = array('upload_data' => $this->upload->data());
      $user_id = $this->uri->segment(3);

      // var_dump($user_id);
      // var_dump($data['file_name']);
      $result = $this->Auth_m->profile_change($data, $user_id);
      if($result){
        $this->session->unset_userdata('profile');
        $this->session->set_userdata('profile',$data['upload_data']['file_name']);
        alert('Changed', '/auth/profile');
        exit;
      }
    }
  }

	/**
	 * Function to create new users
	 *
	 */
  function create(){
    $this->form_validation->set_rules('user_id','USER ID','required|is_unique[users.user_id]');
    $this->form_validation->set_rules('password','PASSWORD','required');
    $this->form_validation->set_rules('passwordcf','PASSWORD Confirm','required|matches[password]');
    $this->form_validation->set_rules('user_email','Email','required|valid_email|is_unique[users.user_email]');

    if($this->form_validation->run() == TRUE){


      $newUser = array(
        'user_id' => $this->input->post('user_id', TRUE),
        'password' => $this->input->post('password', TRUE),
        'user_email' => $this->input->post('user_email', TRUE),
      );

      $result = $this->Auth_m->create_user($newUser);

      if($result){
        alert('Successfully created. Please login again.', '/auth/login');
        exit;
      }else{
        alert('Fail to create user. Please try again' , '/board/lists/ci_board/page/1');
        exit;
      }

    }else{
      $this->load->view('auth/create_v');
    }
  }

	function admin(){
		$this->load->model('Board_m');

		$data['tPost'] = $this->Board_m->get_list('ci_board', 'count', '','','','');
		$data['tUser'] = $this->Auth_m->get_alluser('count');
		$this->load->view('auth/admin_v', $data);
	}
}
