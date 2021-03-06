<!DOCTYPE html>

<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="shortcut icon" href="assets/ico/favicon.png">

	<title>DUMBOBAY - shopping area</title>

	<!-- Bootstrap core CSS -->
	<link href=<?php echo base_url("/assets/css/bootstrap.css");?> rel="stylesheet">
	<link href=<?php echo base_url("assets/css/dumbobaycss.css");?> rel="stylesheet">
	<link href=<?php echo base_url("assets/css/font-awesome.min.css");?> rel="stylesheet">

	<!-- Custom styles for this template -->
	<link href=<?php echo base_url("assets/css/main.css");?> rel="stylesheet">


	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
  <![endif]-->
</head>

<body>

	<!-- Fixed navbar -->
	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#" style="padding:2px 15px 2px 15px"><?php echo img("assets/img/dumbobay-logo-sm.png"); //<img src="assets/img/dumbobay-logo-sm.png">?></a>
			</div>
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav navbar-right">
					<li class="active"><a href="home">HOME</a></li>
					<!--<li><a data-toggle="modal" data-target="#myModal" href="#myModal" class="signin">SIGN IN</a></li>-->
					<li><a href="home/login">SIGN IN</a></li>
					<!--<li><a href="signin.html">SIGN IN</a></li>-->
					<li><a href="home/register">SIGN UP</a></li>
				</ul>
			</div><!--/.nav-collapse -->
		</div>
	</div>
	<!-- =======================================================================-->
	<div id="headerwrap">
		<div class="container">
			<div class="row centered">
				<div class="col-lg-8 col-lg-offset-2">
					<h1>Just let me <b>Shop</b> <br>and no one gets hurt.</h1>
					<h2>- DumboBay -</h2>
					<h2><a href="product.html" class="link-product"><b>> Products <</b></a></h2>
				</div>
			</div><!-- row -->
		</div><!-- container -->
	</div><!-- headerwrap -->
	<!-- ======================================================================= -->
<!-- 	<div class="container w">
		<div class="row centered">
			<img src="assets/img/DumboBay-Logo-b.png">
		</div>row 
	</div>container -->

	<!-- FOOTER -->
	<div id="f">
		<div class="container">
			<div class="row">
			<div class="col-md-6 col-md-push-2">
				<?php echo img("assets/img/DumboBay-Logo-b.png");//<img src="assets/img/DumboBay-Logo-b.png">?>
			</div>
			<div class="col-md-6 col-md-push-2">
				<?php echo img("assets/img/Frappe-Logo-bg-sm.png");//<img src="assets/img/Frappe-Logo-bg-sm.png">?>
			</div>
			</div><!-- row -->
		</div><!-- container -->
	</div><!-- Footer -->


	<!-- MODAL FOR CONTACT 

	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">DumboBay</h4>
				</div>
		
				<form class="form-horizontal">
					<div class="modal-body">
						<div class="row centered">
							<fieldset>
								<legend>SIGN IN</legend>
								<div align="center">
									<div class="form-group">
										<label style="left:20%" for="inputEmail" class="col-lg-2 control-label">Username</label>
										<div class="col-lg-10">
											<input style="width:50%" type="text" class="form-control" size="20" id="username" name="username" placeholder="Put your username in here." >
										</div>
									</div>
									<div class="form-group">
										<label style="left:20%" for="inputPassword" class="col-lg-2 control-label">Password</label>
										<div class="col-lg-10">
											<input style="width:50%" type="password" class="form-control" size="20" id="password" name="password" placeholder="********">
										</div>
									</div>
								</div>
							</fieldset>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
						<a href="#"><button type="submit" value="login" class="btn btn-danger">Sign In</button></a>
					</div>
				</form>
				
			</div>
		</div>
	</div> -->


<!-- Bootstrap core JavaScript
	================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
	<script src=<?echo base_url("?>assets/js/bootstrap.min.js");?>></script>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>

</body>
</html>
