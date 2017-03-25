<script type="text/javascript">
  $(function(){
    $('#admin ul li').on('click',function(){
      var selected = $(this).attr('id');
      // var selected = $('li.active').attr('id');
      // alert(selected);
      $.ajax({
        url:'/ajax_board/'+selected,
        method:'GET',
        dataType:'html',
        complete: function(xhr, textStatus){
          if(textStatus == 'success'){
            console.log('success');
            $('.info_data').html(xhr.responseText);
          }
        }
      });
    });
  });

</script>



<div id="admin" class="container">
  <h3 class="lead text_center">LV.1 user can modify any user info here.</h3>

  <ul class="nav nav-tabs nav-justified">
    <li class="" id="members">
      <a href="#">
        <h4 class="lead text_center"> TOTAL USERS</h4>
				<p class="text_center"><?php echo $tUser?></p>
      </a>
    </li>

    <li class="" id="posts">
      <a href="#" >
        <h4 class="lead text_center"> TOTAL POSTS</h4>
				<p class="text_center"><?php echo $tPost?></p>
      </a>
    </li>
  </ul>

  <div class="info_data">

  </div>

</div>

<div class="modal fade" tabindex="-1" role="dialog" id="myModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">DELETE USER</h4>
      </div>
      <div class="modal-body">
        <p>Do you really want to delete this user?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" data-dismiss="modal" class="btn btn-primary delete-confirm" >YES</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
