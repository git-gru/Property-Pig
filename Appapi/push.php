<?php
//include_once('class_functions.php');

function to_sendpushnotification($message, $deviceToken)
{
   $url = 'https://android.googleapis.com/gcm/send';
    $serverApiKey = "AIzaSyBcqi7QBrz2UYmfLvdN8KF-adcjmQaAEhM";

    $headers = array(
        'Content-Type:application/json',
        'Authorization:key=' . $serverApiKey
    );
    $data = array(
        'registration_ids' => array($deviceToken),
        'data' => array(
            'title' => 'Property Pig',
            'message' => $message,
            'sound' => 'default'
        )
    );
    
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);

    if ($headers)
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($ch, CURLOPT_POST, true);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
   // echo "<pre>";print_r($response);
    curl_close($ch);
}

function push_ios($message, $deviceToken)
{

    // Put your private key's passphrase here:
    $passphrase = 'sics';

    $ctx = stream_context_create();
    stream_context_set_option($ctx, 'ssl', 'local_cert', 'pushcert.pem');
    stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

    // Open a connection to the APNS server
    $fp = stream_socket_client(
        'ssl://gateway.sandbox.push.apple.com:2195', $err,
        $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);

    if (!$fp)
        exit("Failed to connect: $err $errstr" . PHP_EOL);

    //echo 'Connected to APNS' . PHP_EOL;

    // Create the payload body
    $body['aps'] = array(
        'title' => 'Property Pig',
        'alert' => $message,
        // 'msgcnt' => $count,
        'sound' => 'default'

    );
    //print_r($body);

    // Encode the payload as JSON
    $payload = json_encode($body);

    // Build the binary notification
    $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

    // Send it to the server
    $result = fwrite($fp, $msg, strlen($msg));
    //print_r($result);
    /*if (!$result)
        echo 'Message not delivered' . PHP_EOL;
    else
        echo 'Message successfully delivered' . PHP_EOL;*/

    // Close the connection to the server
    fclose($fp);

}

?>