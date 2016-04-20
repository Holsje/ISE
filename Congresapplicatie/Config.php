<?php
	function topLayout($pageName,$css,$javaScript) {
?>
		<head>
			<link href="css/bootstrap/css/bootstrap.min.css" rel="stylesheet">
			<link href="css/header.css" rel="stylesheet">
			<?php echo $css; ?>
			<br/>
			<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
			<script src="css/bootstrap/js/bootstrap.min.js"></script>
			<script src="js/headerfunctions.js"></script>
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
	
	
	function bottomLayout() {
		echo '</html>';
	}
?>
