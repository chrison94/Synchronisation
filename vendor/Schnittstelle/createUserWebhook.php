<?php
/* init variables */
$exists = true; $mocoCustomers = $newEsperantoBoardLabels = $newRegelBoardLabels = $trelloExistingLabels = $trelloExistingEsperantoLabels = $trelloArrayExistingLabels = $trelloLabelNames = $trelloArrayLabelNames = $mocoDataCustomerName = $mocoDataArrayCustomerName = $newCustomerData = '';
$addon = rex_addon::get('MocoTrello');
include_once 'functions.php';

/* init boards with ID and init MOCO-object */
$moco = new MocoSyncTrello();
$board = new MocoSyncTrello($addon->getConfig('regelBoard'));
$esperantoBoard = new MocoSyncTrello($addon->getConfig('esperantoBoard'));

/* get the customer-data from MOCO */
$mocoCustomers = json_decode(file_get_contents("php://input"),TRUE);
$mocoCustomers = $mocoCustomers['name'];

/* Get existing Trello labels and check with MOCO labels */
$trelloExistingLabels = $board->getTrelloLabels();
$trelloExistingEsperantoLabels = $esperantoBoard->getTrelloLabels();
$trelloArrayExistingLabels = array_merge($trelloExistingLabels, $trelloExistingEsperantoLabels);

foreach ($trelloArrayExistingLabels as $existingLabel) {
    if($existingLabel['name'] == $mocoCustomers) {
        $exists = true;
        break;
    }
    else {
        $exists = false;
    }
}

if($exists == false) {
    if($mocoCustomers == 'Esperanto' || $mocoCustomers == 'SiebenWelten' || $mocoCustomers == 'BäderParkHotel' || $mocoCustomers == 'Q-Alm') {
        $esperantoBoard->createNewTrelloLabels($mocoCustomers);
    }
    else {
        file_put_contents("Test.txt", print_r("hä",true));
        $board->createNewTrelloLabels($mocoCustomers);
    }
}

?>