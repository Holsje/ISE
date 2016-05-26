<?php
	//require_once('query_congres.php');
    require_once('connectDatabase.php');
	
	//$CongressApplicationDB = new Congres($server,
	//				   $database,
	//				   $uid,
	//				   $password);

	function topLayout($pageName,$css,$javaScript) {
?>

    <head>
        <title>
            <?php echo $pageName; ?>
        </title>
        <meta name="viewport" content="width=device-width,initial-skill=1.0">
        <link href="css/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="css/header.css" rel="stylesheet">
        <link href="css/public.css" rel="stylesheet">
        <link href=<?php echo $css; ?> rel="stylesheet">
            <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
            <script src="css/bootstrap/js/bootstrap.min.js"></script>
            <script src="js/public.js"></script>
            <script src="js/functions.js"></script>
            <script src="js/headerfunctions.js"></script>
            <script src="js/regex.js"></script>
            <script src=<?php echo $javaScript; ?>></script>
    </head>

    <body>
        <header>
            <div class="header1 col-xs-12 col-sm-12 col-md-12">
                <img class="img-responsive logo col-xs-12 col-sm-12 col-md-12" src="img/logo template.png" alt="logo">
            </div>
            <?php include 'menu.php'; ?>
        </header>
        <?php
            include 'Login.php';
	}
	
	
	function topLayoutManagement($pageName,$css,$javaScript) {
		?>

            <head>
                <title>
                    <?php echo $pageName; ?>
                </title>
                <meta name="viewport" content="width=device-width,initial-skill=1.0">
                <link rel="stylesheet" href="../css/bootstrap/css/bootstrap.min.css">
                <link rel="stylesheet" href="../css/headerManagement.css">
                <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
                <link rel="stylesheet" href="https://cdn.datatables.net/t/dt/dt-1.10.11/datatables.min.css">
                <link rel="stylesheet" href="../css/dataTables/css/dataTables.bootstrap.min.css">
                <link rel="stylesheet" href="../css/management.css">
                <?php echo $css; ?>
                    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
                    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
                    <script src="../css/bootstrap/js/bootstrap.min.js"></script>
                    <script src="../js/headerfunctions.js"></script>
                    <script src="../js/regex.js"></script>
                    <script src="../js/management.js"></script>
                    <script src="https://cdn.datatables.net/t/dt/dt-1.10.11/datatables.min.js"></script>
                    <script src="../js/dataTables/js/jquery.dataTables.min.js"></script>
                    <script src="../js/dataTables/js/dataTables.bootstrap.min.js"></script>


                    <?php echo $javaScript; ?>
            </head>

            <body>
                <div class="row">
                    <div class="header1 col-xs-12 col-sm-12 col-md-12">
                        <img class="img-responsive logo col-xs-12 col-sm-12 col-md-12" src="../img/logo template.png" alt="logo">
                    </div>
                    <?php include 'admin/menu.php'; ?>
                </div>


                <?php
	}

	function bottomLayout() {

		echo '</body></html>';
	}
?>
