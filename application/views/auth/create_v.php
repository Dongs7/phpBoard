<h3 class="lead text_center">Create a New Account</h3>
<div id="create" class="container">
  <?php
  $attributes = array('class' => 'form-horizontal', 'id' => 'user_create');
  echo form_open('/auth/create', $attributes);
  ?>
  <div class="input-group input-group-lg">
      <span class=" input-group-addon glyphicon glyphicon-user"></span>
      <input type="text" class="form-control" name="user_id" value="" placeholder="User ID">
      <!-- <p class="help-block"></p> -->
  </div>
  <br>
  <div class="input-group input-group-lg">
      <span class=" input-group-addon glyphicon glyphicon-envelope"></span>
      <input type="text" class="form-control" name="user_email" value="" placeholder="Email">
      <p class="help-block"></p>
  </div>
  <br>
  <div class="input-group input-group-lg">
      <span class=" input-group-addon glyphicon glyphicon-lock"></span>
      <input type="text" class="form-control" name="password" value="" placeholder="Password">
      <p class="help-block"></p>
  </div>
  <br>
  <div class="input-group input-group-lg">
      <span class=" input-group-addon glyphicon glyphicon-alert"></span>
      <input type="text" class="form-control" name="passwordcf" value="" placeholder="Confirm Password">
      <p class="help-block"></p>
  </div>
  <br>
  <div class="text_center">
      <!-- <span class=" input-group-addon glyphicon glyphicon-alert"></span> -->
      <input type="submit" class="form-control btn btn-danger" name="submit" value="CREATE">
      <!-- <p class="help-block"></p> -->
  </div>
</div>

<?php echo validation_errors();?>
