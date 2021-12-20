<?php

$curl = curl_init();

curl_setopt_array($curl, [
	CURLOPT_URL => "https://twitch-game-popularity.p.rapidapi.com/game?name=League%20of%20Legends&year=2021&month=08",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	CURLOPT_HTTPHEADER => [
		"x-rapidapi-host: twitch-game-popularity.p.rapidapi.com",
		"x-rapidapi-key: 2f7ad4e9ebmsh8bdead9090d5e1ep196ec0jsn83ae003f08fa"
	],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
	echo "cURL Error #:" . $err;
} else {
	echo $response;
}
