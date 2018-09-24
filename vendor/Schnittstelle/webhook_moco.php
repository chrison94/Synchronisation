<?php
/* DEBUGS
file_put_contents("Test.txt", print_r($mocoData,true));
*/


$mocoData = $moco = $esperantoBoard = $trelloData = $trelloEsperantoData = $mocoAppData = $trelloExistingLabels = $trelloArchivedData = $trelloArchivedEsperantoData = $trelloExistingEsperantoLabels = $trelloArrayExistingLabels = '';
$addon = rex_addon::get('MocoTrello');
include_once 'functions.php';
$mocoData = json_decode(file_get_contents("php://input"),TRUE);

$firstname = $mocoData['leader'];
$firstname = $firstname['firstname'];

/* init boards with ID and init MOCO-object */
$moco = new MocoSyncTrello();
$board = new MocoSyncTrello($addon->getConfig('regelBoard'),$firstname);
$esperantoBoard = new MocoSyncTrello($addon->getConfig('esperantoBoard'),$firstname);
/* step 1 get moco data */


/* step 2 get trello data */
$trelloData = $board->getTrelloData();
$trelloArchivedData = $board->getTrelloArchivedData();
$trelloEsperantoData = $esperantoBoard->getTrelloData();
$trelloArchivedEsperantoData = $esperantoBoard->getTrelloArchivedData();

/* step 3 merging active cards with archived cards */
$trelloData = array_merge($trelloData, $trelloArchivedData);
$trelloEsperantoData = array_merge($trelloEsperantoData, $trelloArchivedEsperantoData);

/* step 4 get all labels and merge into one array */
$trelloExistingLabels = $board->getTrelloLabels();
$trelloExistingEsperantoLabels = $esperantoBoard->getTrelloLabels();
$trelloArrayExistingLabels = array_merge($trelloExistingLabels, $trelloExistingEsperantoLabels);

/* step 5 algorithm, for each data in $mocoData do -> algorithm */
	$mocoActive = $mocoData['active'];

	$mocoCustomerData = $mocoData['customer'];
	$mocoCustomer = $mocoCustomerData['name'];
	/* checking if project in moco is active or archived -- Project is active */
	if ($mocoActive == 'true') {
		/* Choosing the board.. if esperanto-board -> do algorithm with Esperanto-Data*/
		if ($mocoCustomer == 'Esperanto' || $mocoCustomer == 'SiebenWelten' || $mocoCustomer == 'BäderParkHotel' || $mocoCustomer == 'Q-Alm') {
            $esperantoBoard->algorithm($mocoData,$trelloEsperantoData,$trelloArrayExistingLabels,$trelloArchivedEsperantoData);

		/* Choosing the board.. if normal-customer-board -> do algorithm with normal-customer-Data*/
		} else {

            $board->algorithm($mocoData,$trelloData,$trelloArrayExistingLabels,$trelloArchivedData);

        }
	}

	/* checking if project in moco is active or archived -- Project is archived */
     else {
		/* Choosing the board */
		if ($mocoCustomer == 'Esperanto' || $mocoCustomer == 'SiebenWelten' || $mocoCustomer == 'BäderParkHotel' || $mocoCustomer == 'Q-Alm') 
        {
            $esperantoBoard->archive($mocoData,$trelloEsperantoData);
			/* Choosing the board */
		} else {
            $board->archive($mocoData,$trelloData);

		}
	}