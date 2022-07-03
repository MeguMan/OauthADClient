<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("diplom");

if (!empty($_GET['access_token']) and empty($_COOKIE['access_token'])) {
    setcookie("access_token", $_GET['access_token']);
    header("Refresh:0");
}

//запрос для проверки валидности токена
$ch = curl_init('http://localhost:8080/api/v1/testAuth');
curl_setopt_array($ch, array(
    CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer '.$_COOKIE['access_token'],
    ),
));

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

//если токен валиден
if ($httpcode == 200) {
    echo "<form method=\"POST\">";
    echo "\n"."Вы успешно авторизовались, вам был выдан access token";
    echo "<p>ЕУЗ для поиска: <input type=\"text\" name=\"username\" /></p>";
    echo "<input type=\"submit\" value=\"Отправить\">";
    echo "</form>";
    if (isset($_POST["username"])){
        $username = $_POST["username"];
        
		$ch = curl_init('http://localhost:8080/api/v1/ad/'.$username);
        curl_setopt_array($ch, array(
        CURLOPT_POST => TRUE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'Authorization: Bearer '.$_COOKIE['access_token'],
        ),
        CURLOPT_POSTFIELDS => json_encode($postData)
        ));
        
    
        $response = curl_exec($ch);

        curl_close($ch);
        echo $response;
    }
} else {
    // если токен не валиден то надо авторизоваться
	//echo $response;
	echo "<p><a href=\"http://localhost:8080/yandex/auth?redirect_uri=http://localhost/crm/diplom.php\">Аутентификация через Yandex</a></p>";
    echo "<p><a href=\"http://localhost:8080/vk/auth?redirect_uri=http://localhost/crm/diplom.php\">Аутентификация через Вконтакте</a></p>";
    echo "<p><a href=\"http://localhost:8080/google/auth?redirect_uri=http://localhost/crm/diplom.php\">Аутентификация через Google</a></p>";
    echo "<p><a href=\"http://localhost:8080/github/auth?redirect_uri=http://localhost/crm/diplom.php\">Аутентификация через Github</a></p>";
    echo "<p><a href=\"http://localhost:8080/mail/auth?redirect_uri=http://localhost/crm/diplom.php\">Аутентификация через Mailru</a></p>";
    echo "<p><a href=\"http://localhost:8080/discord/auth?redirect_uri=http://localhost/crm/diplom.php\">Аутентификация через Discord</a></p>";
}
?>