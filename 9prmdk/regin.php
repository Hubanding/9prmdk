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
			
			if($user_role == 1){
				header("Location: admin.php");
			} else if($user_role == 0){
				header("Location: user.php");
			}
		} else {
			unset($_SESSION['token']);
		}
	}
?>
<html>
	<head> 
		<meta charset="utf-8">
		<title> Регистрация </title>
		
		<script src="https://code.jquery.com/jquery-1.8.3.js"></script>
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
				<div class = "login">
					<div class="name">Регистрация</div>
				
					<div class = "sub-name">Логин:</div>
					<input name="_login" type="text" placeholder="" onkeypress="return PressToEnter(event)"/>
					<div class = "sub-name">Пароль:</div>
					<input name="_password" type="password" placeholder="" onkeypress="return PressToEnter(event)"/>
					<div class = "sub-name">Повторите пароль:</div>
					<input name="_passwordCopy" type="password" placeholder="" onkeypress="return PressToEnter(event)"/>
					
					<a href="login.php">Вернуться</a>
					<input type="button" class="button" value="Зайти" onclick="RegIn()" style="margin-top: 0px;"/>
					<img src = "img/loading.gif" class="loading" style="margin-top: 0px;"/>
				</div>
				
				<div class="footer">
					© КГАПОУ "Авиатехникум", 2020
					<a href=#>Конфиденциальность</a>
					<a href=#>Условия</a>
				</div>
			</div>
		</div>
		
		<script>
			var loading = document.getElementsByClassName("loading")[0];
			var button = document.getElementsByClassName("button")[0];
			
			function RegIn() {
				var _login = document.getElementsByName("_login")[0].value;
				var _password = document.getElementsByName("_password")[0].value;
				var _passwordCopy = document.getElementsByName("_passwordCopy")[0].value;
				
				if(_login != "") {
					if(_password != "") {
						if(_password == _passwordCopy) {
							loading.style.display = "block";
							button.className = "button_diactive";
							
							var data = new FormData();
							data.append("login", _login);
							data.append("password", _password);
							
							// AJAX запрос
							$.ajax({
								url         : 'ajax/regin_user.php',
								type        : 'POST', // важно!
								data        : data,
								cache       : false,
								dataType    : 'json',
								processData : false,
								contentType : false,
								success: function (_data) {
									if(_data.token) {
										localStorage.setItem("token", _data.token);
										console.log("JWT Token:", _data.token); // Вывод в консоль
                                		alert("JWT Token: " + _data.token);
										location.reload();
										loading.style.display = "none";
										button.className = "button";
									} else {
										alert(_data.error);
										loading.style.display = "none";
										button.className = "button";
									}
								},
								// функция ошибки
								error: function( ){
									console.log('Системная ошибка!');
									loading.style.display = "none";
									button.className = "button";
								}
							});
						} else alert("Пароли не совпадают.");
					} else alert("Введите пароль.");
				} else alert("Введите логин.");
			}
			
			function PressToEnter(e) {
				if (e.keyCode == 13) {
					var _login = document.getElementsByName("_login")[0].value;
					var _password = document.getElementsByName("_password")[0].value;
					var _passwordCopy = document.getElementsByName("_passwordCopy")[0].value;
					
					if(_password != "") {
						if(_login != "") {
							if(_passwordCopy != "") {
								RegIn();
							}
						}
					}
				}
			}
			
		</script>
	</body>
</html>