<?
    session_start();
	include("../settings/connect_datebase.php");

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
		} else {
			unset($_SESSION['token']);
		}
	}

    $IdUser = $user_id;
    $Message = $_POST["Message"];
    $IdPost = $_POST["IdPost"];

    $mysqli->query("INSERT INTO `comments`(`IdUser`, `IdPost`, `Messages`) VALUES ({$IdUser}, {$IdPost}, '{$Message}');");
?>