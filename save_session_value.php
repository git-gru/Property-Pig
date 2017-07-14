<?php
$curlSession = curl_init();
curl_setopt($curlSession, CURLOPT_URL, 'https://api.getaddress.io/v2/uk/sw1a2aa?api-key=yy3j3jzEcEOyBNilloGJ0A8272&postcode='.$_REQUEST['postcode'].'&house='.$_REQUEST['house'].'');
curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);

$jsonData = json_decode(curl_exec($curlSession));
echo "<pre>";print_r($jsonData);
curl_close($curlSession);
?>