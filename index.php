<?php
/*
 * List Of words
 */
require_once 'brain.php';
/*
 *   Require A Config File
 */
require_once 'Config.php';
/*
 * Check Conection 
 */
$hub_verify_token = null;
if(isset($_REQUEST['hub_challenge'])) {
    $challenge = $_REQUEST['hub_challenge'];
    $hub_verify_token = $_REQUEST['hub_verify_token'];
}
if ($hub_verify_token === $verify_token) {
    echo $challenge;
}
/**
 * [$input ] : Decode Json Data
 * [$sender] : the Id Of Sender
 * [$message] : the text Message by the Sender
 */
$input = json_decode(file_get_contents('php://input'), true);
$sender = $input['entry'][0]['messaging'][0]['sender']['id'];
$message = $input['entry'][0]['messaging'][0]['message']['text'];
$message_to_reply = '';
/**
 * Check If the Message Exist in List Words
 */
if(array_search($message, $a)) {
    // Exist Echo the Reply
    $result = array_search($message, $a);
    if($result != '') {
            $message_to_reply = $result;
    }
}else {
    // Word doesn't Exist in list
    $message_to_reply = 'Huh! what do you mean?';
}
//API Url
$url = 'https://graph.facebook.com/v2.6/me/messages?access_token='.$access_token;
//Initiate cURL.
$ch = curl_init($url);
//The JSON data.
$jsonData = '{
    "recipient":{
        "id":"'.$sender.'"
    },
    "message":{
        "text":"'.$message_to_reply.'"
    }
}';
//Encode the array into JSON.
$jsonDataEncoded = $jsonData;
//Tell cURL that we want to send a POST request.
curl_setopt($ch, CURLOPT_POST, 1);
//Attach our encoded JSON string to the POST fields.
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
//Set the content type to application/json
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
//Execute the request
if(!empty($input['entry'][0]['messaging'][0]['message'])){
    $result = curl_exec($ch);
}