<!DOCTYPE html>
<html>
  <head>
    <title>Welcome to CoWiFra | Install</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo base_url(); ?>assets/css/bootstrap_new.css" rel="stylesheet" media="screen">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="<?php echo base_url(); ?>assets/js/html5shiv.js"></script>
      <script src="<?php echo base_url(); ?>assets/js/respond.min.js"></script>
    <![endif]-->
  </head>
  
  <body>
    <script src="//code.jquery.com/jquery.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
	
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<hr>
				<div class="row">
					<div class="col-md-12 well">
						<h2>CoWiFra | Install Tool</h2>
						<hr>
						<div class="row">
							<div class="col-md-4">
								<ul class="nav nav-pills nav-stacked">
									<li class="active"><a href="">Welcome</a></li>
									<li><a href="">Requirements</a></li>
									<li><a href="">Environment</a></li>
									<li><a href="">Database</a></li>
									<li><a href="">Update</a></li>
									<li><a href="">First user</a></li>
									<li><a href="">Conclusion</a></li>
								</ul>
							</div>
							<div class="col-md-8">
								<p>This tool will guide you through the installation process of CoWiFra. Please make sure to have all the authentification information for your server together and follow all the instructions given during the install process. </p><br />
								<p>Please make sure that your server configuration has mod_rewrite activated and working properly. Should you wish to disable the mod_rewrite functionality for CoWiFra please check the manual for the instructions on how to enable the old URL format.</p><br />
								<p>If you are ready to start click on "Next"</p>
							</div>
						</div>
						<hr>
						<p class="text-right">
							<a class="btn btn-primary" href="#" disabled="disabled">Prev</a>
							<a class="btn btn-primary" href="#">Next</a>
						</p>
						<hr>
						<div class="progress">
							<div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 10%;">
								<span class="sr-only">60% Complete</span>
							</div>
						</div>
					</div>
					<hr>
					<p><small>CoWiFra | Install tool v.0.1</small></p>
				</div>
			</div>
		</div>
	</div>
	
  </body>
</html>