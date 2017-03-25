<h3 class='lead text_center'>Member Profile</h3>

<div id="profile" class="container">
  <div class="profile_control">
    <div class="profile_image">
      <img src="/public/profile/<?php echo $this->session->userdata('profile');?>" alt="" class="img-circle">
    </div>

    <br>
    <br>
    <br>
    <div class="profile_content text_center lead">
      <small>Click the button below to change picture</small>
      <?php echo form_open_multipart('/auth/upload/'.$this->session->userdata('user_id'));?>

        <label for="file-upload" class="custom-upload">
          <input id="file-upload" type="file" name="userfile" />
          <i class="fa fa-camera"></i> <span contenteditable='false' id="selectedFile"></span>
        </label>
        <br>
        <input type="submit" name="upload" value="submit" class="btn btn-danger">
      </form>
      <br>
      <label for="">User ID: </label>
      <p><?php echo $this->session->userdata('user_id');?></p>
      <label for="">User Email: </label>
      <p><?php echo $this->session->userdata('user_email');?></p>
      <label for="">User Level: </label>
      <p><?php echo $this->session->userdata('level');?></p>
    </div>
  </div>
  <div class="tools text_center">
    <a href="/board/lists/ci_board/page/1"><button class="btn btn-info" type="button" name="button"><i class="fa fa-arrow-left"></i> Back to list</button></a>
  </div>
</div>
