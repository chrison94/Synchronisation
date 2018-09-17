<?php
$addonsPath = rex_path::src('addons');

/* file to configure a webhook for a given board ['modelID']
   all fields of the configuration block must be filled in */
if(empty($_POST['modelID']) ||  empty($_POST['trelloKey']) || empty($_POST['trelloToken'])) {
    $errors .= "Fehler: Alle Felder müssen ausgefüllt sein";
}
$addon = rex_addon::get('MocoTrello');

$trelloToken = htmlspecialchars($addon->getConfig('trello_token'));
$trelloKey = htmlspecialchars($addon->getConfig('trello_key'));
$modelID = htmlspecialchars($addon->getConfig('modelID'));
$adresse = htmlspecialchars($addon->getConfig('webhookLink'));
/* the POSTFIELD variables must be set as the entered values */
$urlcW =  'https://api.trello.com/1/tokens/'.$trelloToken.'/webhooks/?key='.$trelloKey;
$cW  = curl_init();
curl_setopt($cW, CURLOPT_URL, $urlcW);
curl_setopt($cW, CURLOPT_POST, TRUE);
curl_setopt($cW, CURLOPT_HTTPHEADER,array('Content-Type: application/json')); 
curl_setopt($cW, CURLOPT_HTTPHEADER,array('authorization: Token token=')); 
curl_setopt($cW, CURLOPT_POSTFIELDS, array('callbackURL' => $adresse,'idModel' => $modelID,'description' => 'Neuer Webhook'));
curl_exec($cW);
curl_close($cW);