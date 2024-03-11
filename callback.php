<?php
require_once 'public/common.php'; 

if (isset($_COOKIE['user'])) {
    header("Location: index.php");
    exit();
}

require __DIR__ . '/vendor/autoload.php';
$client = new Google_Client();
$fh = fopen('D:\SecureFiles/aboutMeSecret.txt','r');
$secret = '';
while ($line = fgets($fh)) {
$secret.= $line;
  }
$client->setClientId('733461572156-qdmuq5en3kbagv89gej4dp8153k79cs9.apps.googleusercontent.com');
$client->setClientSecret($secret);

$client->setRedirectUri('http://localhost:3000/about/callback.php');
$client->addScope('email');

$authUrl = $client->createAuthUrl();

if (isset($_GET['code'])) {
    $accessToken = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (isset($accessToken['access_token'])) {
        
        $oauth2 = new Google_Service_Oauth2($client);
        $user = $oauth2->userinfo->get();
        
        $email = explode('@', $user->email)[0];
        $profilePicture = $user->picture;
        $names = explode(' ', $user->name);
        if (!checkIfUserExists($email)) {
            $usersData = loadData();
            $usersData->$email = [
                'Login' => $email,
                'FirstName' => $names[0],
                'LastName' => $names[1] == null ? "" : $names[1],
                'Password' => uniqid(),
                'ProfilePicture' => $profilePicture,
                'Age' => "",
                'Description' => "",
                'Skills' => []
            ];
    
            file_put_contents('users.json', json_encode($usersData, JSON_PRETTY_PRINT));
        }
        setcookie('login', json_encode($email), time() + 7200, '/');
        header("Location: index.php");
        exit();
    } else {
        header("Location: login.php?error=access_token_error");
        exit();
    }
} else {
    header("Location: login.php?error=missing_code");
    exit();
}
?>