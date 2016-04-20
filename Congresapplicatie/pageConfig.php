<?php
	require_once('query_congres.php');
    require_once('connectDatabase.php');
	
	$CongressApplicationDB = new Congres($server,
					   $database,
					   $uid,
					   $password);

	function topLayout($pageName,$css,$javaScript) {
?>
		<head>
			<title><?php echo $pageName; ?></title>
			<link href="css/bootstrap/css/bootstrap.min.css" rel="stylesheet">
			<link href="css/header.css" rel="stylesheet">
			<?php echo $css; ?>
			<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
			<script src="css/bootstrap/js/bootstrap.min.js"></script>
			<script src="js/headerfunctions.js"></script>
			<script src="js/regex.js"></script>
			<?php echo $javaScript; ?>
		</head>
		<body>
			<header>
				<div class="header1 col-xs-12 col-sm-12 col-md-12">
					<img class="img-responsive logo col-xs-12 col-sm-12 col-md-12" 
					src="img/logo template.png" alt="logo">
				</div>
					<?php include 'menu.php'; ?>
			</header>
		<?php
	}
	
	
	function topLayoutMannagement($pageName,$css,$javaScript) {
		?>
		<head>
			<title><?php echo $pageName; ?></title>
			<link href="css/bootstrap/css/bootstrap.min.css" rel="stylesheet">
			<link href="css/header.css" rel="stylesheet">
			<?php echo $css; ?>
			<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
			<script src="css/bootstrap/js/bootstrap.min.js"></script>
			<script src="js/headerfunctions.js"></script>
			<script src="js/regex.js"></script>
			<?php echo $javaScript; ?>
		</head>
		<body>
			<div class="header1 col-xs-12 col-sm-12 col-md-12">
				<img class="img-responsive logo col-xs-12 col-sm-12 col-md-12" 
				src="img/logo template.png" alt="logo">
			</div>
		
		
		<?php
	}
	
	function bottomLayout() {
		echo '</body></html>';
	}
?>
