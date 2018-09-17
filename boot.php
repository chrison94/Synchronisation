<?php
$addonsPath = rex_path::src('addons');
if(isset($_POST['kunden'])) {
    include_once $addonsPath.'/MocoTrello/vendor/Schnittstelle/createUser.php';
}
if(isset($_POST['projects'])) {
    include_once $addonsPath.'/MocoTrello/vendor/Schnittstelle/synchronisation.php';
}
if(isset($_POST['check'])) {
    include_once $addonsPath.'/MocoTrello/vendor/Schnittstelle/checkProjects.php';
}
if(isset($_POST['webhook'])) {
    include_once $addonsPath.'/MocoTrello/vendor/Schnittstelle/createWebhook.php';
}

$this->setProperty('author', 'Christian Gehrke');

if (rex::isBackend() && is_object(rex::getUser())) {

}
if (rex::isBackend() && rex::getUser()) {

}
