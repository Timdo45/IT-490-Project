<?php
$curl = curl_init("http://google.com");

curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
$page = curl_exec($curl);

if(curl_errno($curl)) // check for execution errors
{
	echo 'Scraper error: ' . curl_error($curl);
	exit;
}
curl_close($curl);
?>

