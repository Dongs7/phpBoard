<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_m extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	/**
	 * Function to check if the user info exists in the database
	 * @param  [array] $auth [array containing user info]
	 * @return [array]       [array containg returned user info if exists, return false otherwise.]
	 */
  public function login_check($auth){
    // $userData = array(
    //   'user_id' => $arrays['user_id'],
    //   'password' => $arrays['password'],
    // );
		$prev = '';
    $sql = "SELECT user_id, user_email, level, profile, points, point_checker, password FROM users WHERE user_id='".$auth['user_id']."'";
    $query = $this->db->query($sql);


			//Execute if there is a returned data
			if($query->num_rows() > 0){

				//Store data into the array
				$result = $query->row();

				//Verify if the password matches
				if(password_verify($auth['password'], $result->password)){

					//Calculate a point ratio based on the current point
					//(user can level up every 50 points (create post = 10pts, comment = 2pts))
					$point_checker = FLOOR( $result->points / 50);

					//Caculate user level up system
					//if $point_checker is not equal to the value in the point_checker column in the database,
					//Update level and store new point_checker value into the database
					if($point_checker != $result->point_checker ){
						$this->db->set('level', ($result->level) - 1);
						$this->db->set('point_checker', $point_checker);
						$this->db->where('user_id',$result->user_id);
						$this->db->update('users');
					}
					return $result;
    		}else{
      		return FALSE; // If password does not match, return false
    		}
			}else{
				return false; //If no matching data found in the database, return false
			}
  	}

  public function profile_change($arrays, $id){

		//If users change their profile picture,
		//get filename and store it in the database
    $file_name = array(
      'profile' => $arrays['upload_data']['file_name']
    );
    $this->db->where('user_id', $id);
    $result = $this->db->update('users',$file_name);
    return $result;
  }

	/**
	 * Function to create a new user
	 * @param  [array] $arrays [array containg a new user data]
	 * @return [boolean]         [True if added, false otherwise]
	 */
  function create_user($arrays){
    $insert_user = array(
      'user_id' => $arrays['user_id'],
      'user_email' => $arrays['user_email'],
      'password' => password_hash($arrays['password'], PASSWORD_BCRYPT)//Hash the password
    );

    $result = $this->db->insert('users', $insert_user);

    return $result;
  }

	/**
	 * Function to get total number of users in the database
	 * @param  string $type
	 * @return [number]       [total number of returned rows]
	 */
	function get_alluser($type=''){
		$sql = "SELECT * FROM users ORDER BY level";
		$query = $this->db->query($sql);

		if($type == 'count'){
			$result = $query->num_rows();
		}else{
			$result = $query->result();
		}
		return $result;
	}

	/**
	 * Function to delete the selected user from the database
	 * @param  [number] $id [ID number of the selected user]
	 * @return [boolean]     [True if deleted]
	 */
	function delete_user($id){
		$where = $this->db->where('id', $id);
		$result = $this->db->delete('users');

		return $result;
	}











}
