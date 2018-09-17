<?php
/* init variables */
$mocoCustomers = $newEsperantoBoardLabels = $newRegelBoardLabels = $trelloExistingLabels = $trelloExistingEsperantoLabels = $trelloArrayExistingLabels = $trelloLabelNames = $trelloArrayLabelNames = $mocoDataCustomerName = $mocoDataArrayCustomerName = $newCustomerData = '';
$addon = rex_addon::get('MocoTrello');

include_once 'functions.php';
/* init boards with ID and init MOCO-object */
$moco = new MocoSyncTrello();
$board = new MocoSyncTrello($addon->getConfig('regelBoard'));
$esperantoBoard = new MocoSyncTrello($addon->getConfig('esperantoBoard'));

//$board = new MocoSyncTrello('5b3f9b2154ecf3090650f0f3');
//$esperantoBoard = new MocoSyncTrello('5b3f9b941ce2e383b0bd41db');
/* get the customer-data from MOCO */
$mocoCustomers = json_decode(file_get_contents("php://input"),TRUE);
file_put_contents("Test.txt", print_r($mocoCustomers,true));


/* Get existing Trello labels and check with MOCO labels */
$trelloExistingLabels = $board->getTrelloLabels();
$trelloExistingEsperantoLabels = $esperantoBoard->getTrelloLabels();
$trelloArrayExistingLabels = array_merge($trelloExistingLabels, $trelloExistingEsperantoLabels);
foreach ($trelloArrayExistingLabels as $existingLabel) {
    $trelloLabelNames .= $existingLabel['name'].',';
}
$trelloArrayLabelNames = explode(',', $trelloLabelNames);

foreach ($mocoCustomers as $mocoCustomer) {
    $mocoDataCustomerName .= $mocoCustomer['name'].',';
}
$mocoDataArrayCustomerName = explode(',', $mocoDataCustomerName);

/* return new customers in MOCO */
$newCustomerData = array_diff($mocoDataArrayCustomerName, $trelloArrayLabelNames);

/* go through the new customer data and save in one variable each board */
foreach($newCustomerData as $data) {
    if($data == 'Esperanto' || $data == 'SiebenWelten' || $data == 'BäderParkHotel' || $data == 'Q-Alm') {
        $newEsperantoBoardLabels .= $data.',';
    }
    else {
        $newRegelBoardLabels .= $data.',';
    }
}
$newEsperantoBoardLabels = explode(',', $newEsperantoBoardLabels);
$newRegelBoardLabels = explode(',', $newRegelBoardLabels);

/* send new MOCO customer data to Trello as labels */
$esperantoBoard->createNewTrelloLabels($newEsperantoBoardLabels);
$board->createNewTrelloLabels($newRegelBoardLabels);
?>