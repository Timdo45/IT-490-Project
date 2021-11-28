<?php
$output = shell_exec('telnet serverb.example.com 5672');
if($output){
	echo "$output";
}
else
	echo "thing";
?>
