<h3 class="page-title text-center">MODIFY POST</h3>
<div id="edit" class="container">
  <form class="" action="/board/edit/<?php echo $this->uri->segment(3);?>/board_id/<?php echo $this->uri->segment(5);?>" method="post">
    <div class="form-group">
      <label for="title">POST TITLE</label>
      <input type="text" class="form-control" id="title" name="title" value="<?php echo $views->title?>">
    </div>
    <hr>
    <div class="form-group">
      <textarea name="body" class="form-control"><?php echo $views->body;?></textarea>
    </div>

    <div class="edit_tools float_right">
      <input type="submit" name="" value="SUBMIT" class="btn btn-info">
      <button type="button" name="button" class="btn btn-danger" onclick="history.go(-1);">CANCEL</button>
    </div>
  </form>
</div>
