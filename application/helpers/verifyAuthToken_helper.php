<?php
function verifyToken($token){
    $jwt = new JWT();
    $JwtSecretKey = "Mysecretwordshere";
    $verification = $jwt->decode($token, $JwtSecretKey, 'HS256');
    return $verification;
}
?>