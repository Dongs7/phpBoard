<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Board_m extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	/**
	 * Function to get posts informtion
	 * @param  [string] $table   [table name in the datbase]
	 * @param  [string] $type    [send total number of rows if the value is count]
	 * @param  [number] $offset  [Get starting index for the pagination]
	 * @param  [number] $limit   [Total number of posts will be shown in one page]
	 * @param  [string] $soption [search option (authot, title or both)]
	 * @param  [string] $sterm   [search word]
	 * @return [array]          [array containing returned data]
	 */
	function get_list($table, $type, $offset ,$limit, $soption, $sterm){
		$limit_query = '';
		$search_query =' WHERE';

		//If offset and limit parameters are not empty
		if($limit != '' OR $offset != ''){
			$limit_query = " LIMIT ".$offset.','.$limit;
		}

		//Search query based on $soption (author, contents or both)
		if($soption != '' AND $sterm != ''){
			if($soption == 'Author'){
				$search_query .= " author like '%".$sterm."%' and";
			}else if($soption == 'Title'){
				$search_query .= " title like '%".$sterm."%' and";
			}else{
				$search_query .= " title like '%".$sterm."%' OR author like '%".$sterm."'and ";
			}
		}

		$sql = "SELECT * FROM ".$table.$search_query." board_pid= '0' ORDER BY board_id DESC".$limit_query;
		$query = $this->db->query($sql);

		if($type == 'count'){
			$result = $query->num_rows();
		}
		else{
			$result = $query->result();
		}
		return $result;
	}


	function get_view($table, $id){

		//Increment the number of views when users click the post.
		$this->db->set('hits','hits+1', FALSE);
		$this->db->where('board_id', $id);
		$this->db->update($table);

		$sql = "SELECT * FROM ".$table." WHERE board_id =".$id;
		$query = $this->db->query($sql);
		$result = $query->row();

		return $result;
	}

	function create_post($arrays){
		$table = $arrays['table'];

		$insert_data = array(
			'author' => $arrays['author'],
			'title' => $arrays['title'],
			'body' => $arrays['body'],
			'createdAt' => date('Y-m-d H:i:s'),
			'hits' => '0'
		);

		$result = $this->db->insert($arrays['table'], $insert_data);

		//Add 10 points to current session user if the user creates a new post
		//when $result == true
		if($result){
			$author = $this->session->userdata('user_id');
			$this->db->set('points', 'points+10', false);
			$this->db->where('user_id', $author);
			$this->db->update('users');
		}else{
			return false;
		}
		return $result;
	}

	function modify_post($arrays){

		$id = array(
			'board_id' => $arrays['board_id']
		);

		$edit_data = array(
			'title' =>$arrays['title'],
			'body' =>$arrays['body'],
		);

		$result = $this->db->update($arrays['table'], $edit_data, $id);

		return $result;
	}

	function delete_post($table, $id){
		// var_dump($table);
		$where = array(
			'board_id' => $id
		);
		$result = $this->db->delete($table, $where);

		return $result;
	}

	function insert_comment($arrays){
		$insert_data = array(
			'board_pid' => $arrays['board_id'],
			'body' => $arrays['body'],
			'title'=>'',
			'author' => $arrays['user_id']
		);


		// var_dump($arrays['table']);
		$this->db->insert($arrays['table'], $insert_data);
		$board_id = $this->db->insert_id();
		return $board_id;
	}

	function get_comment($table, $id){
		$sql = "SELECT * FROM ".$table." WHERE board_pid = '".$id."' ORDER BY board_id DESC";
		$query = $this->db->query($sql);
		return $query->result();
	}
}
