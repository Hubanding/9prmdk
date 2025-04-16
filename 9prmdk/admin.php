<?php
	session_start();
	include("./settings/connect_datebase.php");
	if (isset($_SESSION['token'])) {
		$SECRET_KEY = 'cAtwa1kkEy';
		$token = $_SESSION['token'];

		$header_base64 = explode('.', $token)[0];
		$payload = explode('.', $token)[1];
		$signatureJWT = explode('.', $token)[2];

		$alg = json_decode(base64_decode($header_base64))->alg;
	
		$unsignedToken = $header_base64 . '.' . $payload;
		$signature = base64_encode(hash_hmac($alg, $unsignedToken, $SECRET_KEY, true));
	
		if ($signatureJWT === $signature) {
			$payload_data = json_decode(base64_decode($payload), true);
			$user_id = $payload_data['userId'];
			$user_role = $payload_data['userRole'];
			
			if($user_role == 0){
				header("Location: user.php");
			}
		} else {
			unset($_SESSION['token']);
		}
	}
	else{
		header("Location: login.php");
	}
?>
<!DOCTYPE HTML>
<html>
	<head> 
		<script src="https://code.jquery.com/jquery-1.8.3.js"></script>
		<meta charset="utf-8">
		<title> Admin панель </title>
		
		<link rel="stylesheet" href="style.css">
	</head>
	<body>
		<div class="top-menu">

			<a href=#><img src = "img/logo1.png"/></a>
			<div class="name">
				<a href="index.php">
					<div class="subname">БЗОПАСНОСТЬ  ВЕБ-ПРИЛОЖЕНИЙ</div>
					Пермский авиационный техникум им. А. Д. Швецова
				</a>
			</div>
		</div>
		<div class="space"> </div>
		<div class="main">
			<div class="content">
				<input type="button" class="button" value="Выйти" onclick="logout()"/>
				
				<div class="name">Административная панель</div>
			
				Административная панель служит для создания, редактирования и удаления записей на сайте.
			
				<div class="footer">
					© КГАПОУ "Авиатехникум", 2020
					<a href=#>Конфиденциальность</a>
					<a href=#>Условия</a>
				</div>
			</div>
		</div>
		
		<script>
			function logout() {
				$.ajax({
					url         : 'ajax/logout.php',
					type        : 'POST', // важно!
					data        : null,
					cache       : false,
					dataType    : 'html',
					processData : false,
					contentType : false, 
					success: function (_data) {
						location.reload();
					},
					error: function( ){
						console.log('Системная ошибка!');
					}
				});
			}
		</script>
	</body>
</html>