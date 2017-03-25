<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax_board extends CI_Controller {

	function __construct(){
		parent::__construct();
		date_default_timezone_set('America/Vancouver');
	}

	/**
	 * Function to add comment from the ajax request
	 */
  function add_comment(){
    if($this->session->userdata('logged_in') == TRUE){
			$this->load->model('Board_m');
			$table = $this->input->post('table', TRUE);
			$board_id = $this->input->post('board_id', TRUE);
			$body = $this->input->post('comment_contents',TRUE);

			if($body != ''){
				$write_comment = array(
					'table' => $table,
					'board_id' => $board_id,
					'body' => $body,
					'user_id' => $this->session->userdata('user_id')
				);

				$result = $this->Board_m->insert_comment($write_comment);

				//Below line will be the ajax response
				//which will be sent in the html form
				if($result){
					$sql = "SELECT * FROM ".$table." WHERE board_pid = '".$board_id."' ORDER BY board_id DESC";
        	$query = $this->db->query($sql);
        ?>

        	<table cellspacing="0" cellpadding="0" class="table table-striped" id="comment_table">
        <?php
        foreach ($query->result() as $lt)
        {
        ?>
        			<tr id="row_num_<?php echo $lt->board_id;?>">
        				<th scope="row">
        					<?php echo $lt->author;?>
        				</th>
        				<td><?php echo $lt->body;?></a></td>
        				<td><?php echo date("M j, Y, G:i ", strtotime($lt->createdAt));?></td>
								<?php if($this->session->userdata('logged_in')==TRUE){
              		if($this->session->userdata('user_id') == $lt->author){
      					?>
        				<td><button class="btn btn-link comment_delete" vals="<?php echo $lt->board_id;?>"><i class="fa fa-trash"></i></button></td>
								<?php }else{ ?>
									<td colspan='2'></td>
								<?php }}?>
        			</tr>
        <?php
        }
        ?>

        	</table>
        <?php
				}
			}else{ // If input field is empty, send 1000
				echo 1000;
			}
		}else{ // If user is not logged in, send 9000
			echo 9000;
		}
  }

	/**
	 * Function to delete comment from the ajax request
	 * @return [number] [ID number for the selected comment]
	 */
	function delete_comment(){
		if($this->session->userdata('logged_in') == TRUE){
			$this->load->model('Board_m');
			$id = $this->input->post('target_id', TRUE);
			$table = $this->input->post('table', TRUE);

			$result = $this->Board_m->delete_post($table, $id);

			if($result){
				echo $id;
			}
		}else{ // if the user is not logged in
			alert('log in first');
		}
	}

	/**
	 * Function to delete user
	 * @return [boolean] [TRUE if deleted]
	 */
	function delete(){
		$id = $this->input->post('id', TRUE);
		$this->load->model('Auth_m');

		//Send $id to the 'delete_user' function in the auth_m model
		$result  = $this->Auth_m->delete_user($id);

		if($result){
			alert('user deleted','/auth/admin');
		}
	}

	/**
	 * Function to get total number of users and posts in the database
	 * @return [ajax response in html] [The response will be sent in the html form]
	 */
	function overall(){
		$this->load->model('Auth_m');
		$this->load->model('Board_m');

		$total_post = $this->Board_m->get_list('ci_board', 'count', '','','','');
		$total_user = $this->Auth_m->get_alluser('count');

		?>
		<div class="row">
			<div class="col-md-6">
				<h4 class="lead text_center"> TOTAL USERS</h4>
				<p class="text_center"><?php echo $total_user?></p>
			</div>

			<div class="col-md-6">
				<h4 class="lead text_center"> TOTAL POSTS</h4>
				<p class="text_center"><?php echo $total_post?></p>
			</div>
		</div>
		<?php
	}

	/**
	 * Function to get detail member info
	 * @return [ajax response] [The response will be sent in the html form]
	 */
	function members(){
		$this->load->model('Auth_m');
		$result = $this->Auth_m->get_alluser('');
		// $this->load->view('auth/admin_v');
		if($result){
			?>

			<table cellspacing='0' cellpadding='0' class="table table-striped table-hover">
				<thead>
					<tr>
						<th>USER ID</th>
						<th>USER EMAIL</th>
						<th>USER LEVEL</th>
						<th>MEMBER SINCE</th>
						<th>DELETE USER</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach($result as $user){
					?>
					<tr>
						<td><?php echo $user->user_id ;?></td>
						<td><?php echo $user->user_email ;?></td>
						<td><?php echo $user->level ;?></td>
						<td><?php echo $user->createdAt ;?></td>
						<td> <button vars="<?php echo $user->id;?>"  type="button" name="button" class="delete btn btn-link"><span class="glyphicon glyphicon-trash"></span></button></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<script type="text/javascript">
				//When the admin clicks the delete button,
				//Bootstrap modal pops up and ask confirmation.
				//When 'yes' clicks, selected user will be removed from the database.
				$('.delete').on('click', function(){
					var selected = $(this).attr('vars');
					$('#myModal').modal({
						keyboard:true,
						backdrop: false
					})
					.one('click', '.delete-confirm', function(e){
						$.ajax({
							url:'/ajax_board/delete',
							type:'post',
							data:{
								'id':selected,
								'csrf_test_name': getCookie('csrf_cookie_name'),
							},
							complete:function(xhr,response){
								setTimeout(function(){
									alert('User deleted');
									location.reload('/auth/admin');
								},500);
							}
						});
					});
				});
			</script>
			<?php
			}
		}
}
