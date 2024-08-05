<?php
	// ini_set('display_errors', 1);
	// ini_set('display_startup_errors', 1);
	// error_reporting(E_ALL);
	include "../server_data.php";
	$dbh = NULL;
	$dbh = @mysqli_connect($host,$username,$passwd,$dbname);
	if ( mysqli_connect_errno() ) {
		$dbh = NULL;
	}

	function if_hash_user_exist($dbh, string $hash_user) {
		$request = "SELECT `identifier` FROM `user`;";
		$ret = mysqli_query($dbh, $request);
		if ( mysqli_errno($dbh) ) {
			return mysqli_errno($dbh);
		}
		while( $line = mysqli_fetch_assoc($ret) ) { // on processe toutes les lignes résultats
			if (password_verify($line['identifier'], $hash_user)) {
				return $line['identifier'];
			}
		}
		return false;
	}
	$user = "";
?>