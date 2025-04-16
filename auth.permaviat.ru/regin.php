<?php
    include("settings/connect_db.php");
  
    if (!isset($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_USER'])) { header('HTTP/1.0 403 Forbidden'); exit; }
    if (!isset($_SERVER['PHP_AUTH_PW']) || empty($_SERVER['PHP_AUTH_PW'])) { header('HTTP/1.0 403 Forbidden'); exit; }

    $login = $_SERVER['PHP_AUTH_USER'];
    $password = $_SERVER['PHP_AUTH_PW'];

    $query_user = $mysqli->query("SELECT * FROM `users` WHERE `login` = '$login'");
    if ($read_user = $query_user->fetch_assoc()) {
        http_response_code(401); 
    } else {
		$mysqli->query("INSERT INTO `users`(`login`, `password`, `roll`) VALUES ('".$login."', '".$password."', 0)");
        $query_user = $mysqli->query("SELECT * FROM `users` WHERE `login`='".$login."' AND `password`= '".$password."';");
		$user_new = $query_user->fetch_row();
		$id = $user_new[0];
		$role = $user_new[3];
        $header = ["typ" => "JWT", "alg" => "sha256"];
        $payload = [
            "userId" => $id, 
            "userRole" => $role  
        ];

        $SECRET_KEY = 'cAtwa1kkEy'; 

        $base64Header = base64_encode(json_encode($header)); 
        $base64Payload = base64_encode(json_encode($payload)); 
        
        $unsignedToken = $base64Header . "." .$base64Payload; 
        $signature = hash_hmac($header['alg'], $unsignedToken, $SECRET_KEY, true);

        $base64Signature = base64_encode($signature); 
       
        $token =  $base64Header . '.'. $base64Payload . '.' . $base64Signature;

        http_response_code(200); 
        echo json_encode(['token' => $token]);
    }
?>