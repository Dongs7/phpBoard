<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" href="/public/css/master.css">

	<script
  src="https://code.jquery.com/jquery-3.2.0.js"
  integrity="sha256-wPFJNIFlVY49B+CuAIrDr932XSb6Jk3J1M22M3E2ylQ="
  crossorigin="anonymous"></script>
	<script src="https://use.fontawesome.com/a932d2807f.js"></script>
	<script type="text/javascript" src="/public/js/master.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<title>royBoard</title>
</head>
<body>
	<header>
		<nav class="nav navbar navbar-default clearfix">
			<div class="container">
				<a href="/board/lists/ci_board" class="navbar-brand">roysBoard</a>
				<?php if($this->session->userdata('logged_in')== TRUE){?>
					 <a href="/auth/logout" class='float_right login_btn'><button type="button" name="button" class="btn btn-primary">logout</button></a>
					 <a href="/auth/profile/<?php echo $this->session->userdata('user_id');?>" class="float_right login_btn"><button type="button" name="button" class="btn btn-danger">Hello!! <?php echo $this->session->userdata('user_id');?> level: <?php echo $this->session->userdata('level')?></button></a>
					 <?php if($this->session->userdata('level') == 1){?>
						<a href="/auth/admin" class="float_right login_btn"><button type="button" name="button" class="btn btn-info">ADMIN</button></a> 
					 <?php }?>
				<?php }else{?>
					<a href="/auth/login" class='float_right login_btn'><button type="button" name="button" class="btn btn-primary">Login</button></a>
				<?php }?>
			</div>
		</nav>
	</header>

	<h3 class="text_center">roysBoard in PHP, MySQL, Bootstrap and CodeIgniter 3</h3>
	<hr>
