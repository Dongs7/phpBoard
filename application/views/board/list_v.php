<?php
if($search_result['soption'] != '' AND $search_result['sterm'] != ''){
?>
<div class="search_result lead text_center bg bg-danger">
  <p>Search Result</p>
  <p>Category : <?php echo $search_result['soption'];?></p>
  <p>Keyword : <?php echo $search_result['sterm'];?></p>
</div>
<?php } ?>

<div id="main" class="container clearfix">
  <a href="/board/newpost/<?php echo $this->uri->segment(3);?>"><button type="button" class=" btn btn-success float_right">NEW POST</button></a>
  <table class="table table-striped table-hover">
    <thead>
      <tr>
        <th>Title</th>
        <th>Author</th>
        <th>Views</th>
        <th>Date</th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach(array_merge($lists) as $lt){
      ?>

      <tr>

        <th>
          <a href="/board/view/<?php echo $this->uri->segment(3);?>/id/<?php echo $lt->board_id;?>"><?php echo $lt-> title;?></a>
        </th>
        <th><?php echo $lt-> author;?></th>
        <th><?php echo $lt-> hits;?></th>
        <th><?php echo date("M j, Y ", strtotime($lt->createdAt));?></th>
      </tr>
      <?php
      }
      ?>
    </tbody>
    <tfoot>
      <tr>
        <th colspan='5' class="text_center"><?php echo $pagination;?></th>
      </tr>
    </tfoot>
  </table>

  <div id="search_post" class="float_right">
    <form id="searching" method="post" action="">
      <?php
      $attributes = array('class' => 'form-horizontal', 'id' => 'searching');
      echo form_open('/auth/login', $attributes);
      ?>
      <!-- <select class="test" name="search_option">
        <option value="Author">Author</option>
        <option value="Title">Title</option>
        <option value="Both">Both</option>
      </select> -->

      <div class="input-group">
        <div class="input-group-btn">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span id="selected">Author</span> <span class="caret"></span></button>
        <ul class="dropdown-menu">
          <li class="">Author</li>
          <li class="">Title</li>
          <li class="">Both</li>
        </ul>
      </div><!-- /btn-group -->
        <input type="text" name="search" class="form-control" placeholder="Enter search word" id="q" onkeypress="search_enter(document.q);">
        <!-- <input type="submit" id="search_btn" value="SEARCH"> -->
        <div class="input-group-btn">
          <button type="button" id="search_btn" name="button" class="btn btn-default"><i class="glyphicon glyphicon-search"></i></button>
        </div>
      </div>


    </form>
  </div>

</div>
