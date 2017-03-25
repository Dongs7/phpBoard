<h3 class="page-title text_center">CREATE A NEW POST</h3>
<div id="write" class="container">
  <!-- <form class="" action="/board/newpost/<?php echo $this->uri->segment(3);?>" method="post"> -->
    <?php
    $attributes = array('class' => 'form-horizontal', 'id' => 'new_post');
    echo form_open('/board/newpost/'.$this->uri->segment(3), $attributes);
    ?>
    <div class="form-group">
      <label for="title">POST TITLE</label>
      <input type="text" class="form-control" id="title" name="title" placeholder="Title">
    </div>
    <hr>
    <div class="form-group">
      <textarea name="body" class="form-control" placeholder="your contents here"></textarea>
    </div>

    <div class="write_tools float_right">
      <input type="submit" name="" value="POST" class="btn btn-info">
      <button type="button" name="button" class="btn btn-danger" onclick="history.go(-1);">CANCEL</button>
    </div>
  <!-- </form> -->
</div>

<?php echo validation_errors();?>
