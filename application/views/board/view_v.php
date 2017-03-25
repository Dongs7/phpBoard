<script type="text/javascript">
  $(function(){
    $('#add_comment').on('click',function(){
      console.log('clicked');
      $.ajax({
        url:'/ajax_board/add_comment',
        type:'POST',
        data:{
          'comment_contents':encodeURIComponent($('#input01').val()),
          'csrf_test_name': getCookie('csrf_cookie_name'),
          'table': '<?php echo $this->uri->segment(3);?>',
          'board_id': '<?php echo $this->uri->segment(5);?>',
        },
        // content:'application/json',
        dataType:'html',
        complete: function(xhr, textStatus){

          // console.log("hihi" + xhr.responseText);
          if(textStatus == 'success'){
            if(xhr.responseText == 500){
              alert('yes');
            }

            else if(xhr.responseText == 1000){
              alert('Content is empty. Please write something');
            }else if(xhr.responseText == 2000){
              alert('Please enter it again');
            }else if(xhr.responseText == 9000){
              alert('Pleaes log in first.');
            }else{
              $('.comment_show').html(xhr.responseText);
              $('#input01').val('');
            }
          }
        }
        });
      });
$(function(){
  //Comment_delete function will not work right after the new
  //comment is added. So we need event propagation in order to make
  //this function work without refreshing the page.
      $('.comment_show').on('click','.comment_delete',function(e){
        e.preventDefault();
        var target = $(this).attr('vals');
        console.log('clickedddd');
        $.ajax({
          url:'/ajax_board/delete_comment',
          type:'post',
          data:{
            'target_id':$(this).attr('vals'),
            'csrf_test_name': getCookie('csrf_cookie_name'),
            'table': '<?php echo $this->uri->segment(3);?>'
          },
          dataType:'text',
          complete:function(xhr, textStatus){
            if(textStatus == 'success'){
              // console.log(xhr.responseText);
              $('#row_num_'+xhr.responseText).remove();
              alert('Comment deleted');
            }
          }
        })
      });
    });
});


</script>



<div id="view" class="container">
  <div class="panel panel-info">
    <div class="panel-heading">
      <h2><?php echo $views->title;?></h2>
      <p><i>Author: <?php echo $views->author;?></i></p>
      <p><i>Date: <?php echo date("M j, Y, G:i ", strtotime($views->createdAt));?></i></p>
    </div>
    <div class="panel-body body_content" style="min-height:300px; height:100%;">
    <?php echo $views->body?>
    </div>

    <div class="panel-footer clearfix">
      <div class="view_tools">
        <a href="/board/lists/ci_board/page/1"><button type="button" class="btn btn-info">BACK</button></a>
        <?php if($this->session->userdata('user_id') == $views->author OR $this->session->userdata('level') == 1){ ;?>
          <a href="/board/delete/<?php echo $this->uri->segment(3);?>/id/<?php echo $this->uri->segment(5);?>"><button type="button" class="tool_right btn btn-danger float_right">DELETE</button></a>
          <a href="/board/edit/<?php echo $this->uri->segment(3);?>/id/<?php echo $this->uri->segment(5);?>"><button type="button" class="tool_right btn btn-primary float_right">EDIT</button></a>
        <?php };?>
      </div>
    </div>
  </div>

<!-- Comment Input Area -->
  <!-- <form class="form-horizontal" method="post" action="" name="com_add"> -->
    <?php
    $attributes = array('class' => 'form-horizontal', 'id' => 'comment_add');
    echo form_open('', $attributes);
    ?>
    <div id="comment-section" class="comment">
      <div class="control-group">
        <label for="input01">COMMENT</label>
        <!-- <div class="control">
          <textarea id="input01" name="comment_content" style="min-height:100px; !important"></textarea>
          <input type="button" id="add_comment" class="btn btn-danger" name="" value="ADD">
        </div> -->

        <div class="input-group input-group-lg">
          <?php if($this->session->userdata('logged_in')==TRUE){?>
            <span class="input-group-addon"><?php echo $this->session->userdata('user_id');?></span>
            <input class="form-control" id="input01" type="text" name="comment_content" value="" placeholder="Comment" onkeypress="return event.keyCode != 13;">
            <div class="input-group-btn">
            <!-- <input type="button" id="add_comment" class="btn btn-danger" name="" value="ADD"> -->
              <button type="button" id="add_comment" class="btn btn-danger" name="">ADD</button>
              <?php }else{ ?>
              <span class="input-group-addon"><i class="glyphicon glyphicon-exclamation-sign" style="color:red;"></i></span>
              <input class="form-control" id="input01" type="text" name="comment_content" placeholder="Please Log in first" disabled>
                <div class="input-group-btn">
                <!-- <input type="button" id="add_comment" class="btn btn-danger" name="" value="ADD"> -->
                  <button type="button" id="add_comment" class="btn btn-danger" disabled>ADD</button>
                  <?php } ?>
                </div>
            </div>

      </div>
    </div>
  </form>
  <br>
<!--  Comment Show Area-->
  <div class="comment_show">
    <table cellspacing="0" cellpadding="0" class="table table-striped" id="comment_table">
<?php
foreach ($comment_list as $lt)
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
  </div>
