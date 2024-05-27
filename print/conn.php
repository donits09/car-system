<?php
	$conn = new mysqli('localhost', 'root', '', 'db_print');
	
	if(!$conn){
		die("Error: Can't connect to database");
	}
?>