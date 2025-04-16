<?php
	session_start();
	include("../settings/connect_datebase.php");
	
	$login = $_POST['login'];
	$password = $_POST['password'];
	
	$url = 'http://localhost/9prmdk/auth.permaviat.ru/regin.php';
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
	curl_setopt($ch, CURLOPT_HEADER, false); 

	$response = curl_exec($ch);
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);

	if ($http_code == 200) {
		$response_data = json_decode($response, true);
		
		if (isset($response_data['token'])) {
			$token = $response_data['token'];
			$_SESSION['token'] = $token;
			echo json_encode(['token' => $token]);
		} else {
			echo json_encode(['error' => 'Токен не получен']);
		}
	} else {
		echo json_encode(['error' => 'Пользователь с таким логином уже существует']);
	}
?>