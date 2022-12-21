<?php
define('SECRET_KEY','Super-Secret-Key');  // secret key can be a random string and keep in secret from anyone
define('ALGORITHM','HS256');   // Algorithm used to sign the token
$iat = time() + (1 * 24 * 60 * 60); 	   // time of token issued at + (1 day converted into seconds)
$nbf = $iat + 100; //not before in seconds
$tokenExp = $iat + 60 * 60; // expire time of token in seconds (1 min * 60) 10 anos 87600
$token = array(
   "iss" => "https://wolfx.com.br/",
   "aud" => "https://wolfx.com.br/",
   "exp" => $tokenExp,
   "data" => array() // add any thing you want to add to token //php array
);
?>
