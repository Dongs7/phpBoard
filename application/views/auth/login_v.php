
<div id="login" class="container">
  <h3 class="page-header lead text_center">LOG IN</p>

      <?php
      $attributes = array('class' => 'form-horizontal', 'id' => 'auth_login');
      echo form_open('/auth/login', $attributes);
      ?>
      <div class="control-group">
        <div class="controls">
          <input id="userid" type="text" class="input-xlarge" name="user_id" placeholder="User ID">
        </div>

      </div>
      <p class="help-block"></p>

      <div class="control-group">
        <!-- <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span> -->
        <div class="controls">
          <input id="password" type="password" class="input-xlarge" name="password" placeholder="Password">
        </div>

      </div>
      <p class="help-block"></p>
      <div class="help-block">
        <p class="help-block"><?php echo validation_errors();?></p>
      </div>
      <div class="form-actions">
        <input type="submit" class="text_center btn btn-info" value="LOGIN">
      </div>
    </form>
</div>
<div class="text_center">
  <h4 class="text_center">Dont have account?</h4>
  <a href="/auth/create" class="text_center"><button type="button" name="button" class="btn btn-danger">Create account</button></a>
</div>
