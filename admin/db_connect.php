<?php 

$conn= new mysqli('127.0.0.1','root','','ADHA_db',3306) or die("Could not connect to mysql: " . mysqli_connect_error());

$archived_col = $conn->query("SHOW COLUMNS FROM orders LIKE 'archived'");
if($archived_col && $archived_col->num_rows === 0){
	$conn->query("ALTER TABLE orders ADD COLUMN archived tinyint(1) NOT NULL DEFAULT 0 AFTER status");
}
