<?php

chdir(dirname(__FILE__));

error_reporting(E_ALL);

require "./VantagePro.php";

$v=new VantagePro("192.168.0.5", 22222);

if (!$v->FetchData(2)) {
	print $v->GetLastErrorMessage()."\n\n";
	exit;
	}

$D=$v->GetData();

print_r($D);
?>