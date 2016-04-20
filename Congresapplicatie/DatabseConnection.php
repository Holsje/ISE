<?PHP
	require('query_congres.php');
	require('connectDatabase.php');

	$congress = new Congres($server,
						   $database,
						   $uid,
						   $password);
?>