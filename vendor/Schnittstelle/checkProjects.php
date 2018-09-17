<?php
include_once 'functions.php';
$addon = rex_addon::get('MocoTrello');

/* File to check if Trello cards are on a board that are not part of a MOCO project */

        /* init variable to count the project cards that do not belong to a project in MOCO and init output variable */
        $i = 0;
        $output = '';

        /* The file in which the projects are located is emptied each time you call it up */
        file_put_contents("Projekte.txt", print_r("Projekte die nicht in beiden Anwendungen Vorkommen: ",true));

        /* init boards with ID and init MOCO-object */
        $moco = new MocoSyncTrello();
        $board = new MocoSyncTrello($addon->getConfig('regelBoard'));
        $esperantoBoard = new MocoSyncTrello($addon->getConfig('esperantoBoard'));

        /* Get the full scope of project data from MOCO and Trello */
        $sql = rex_sql::factory();
        $mocoAppData = $sql->setQuery('SELECT * from rex_synchronisation');
        $mocoAppData = $mocoAppData->getArray();        $trelloData = $board->getTrelloArchivedData();
        $trelloEsperantoData = $esperantoBoard->getTrelloArchivedData();


    /* Loop through the Trello Esperanto board data */
    foreach($trelloEsperantoData as $trello) {

         /* Check if the project card belongs to a project in MOCO */
        $checkExistence = $moco->checkProjectsFunction($mocoAppData,$trello['name']);

        /* If yes, save to file and to an output variable */
        if($checkExistence != true) {
            $i = $i + 1;
            $output .=  " *|| ". $i .". ".$trello['name'];
            file_put_contents("Projekte.txt", print_r(" *|| ". $i .". ".$trello['name'],true),FILE_APPEND);
        }
    }


    /* Loop through the Trello normal customer board data */
    foreach($trelloData as $trello) {

        /* Check if the project card belongs to a project in MOCO */
        $checkExistence = $moco->checkProjectsFunction($mocoAppData,$trello['name']);

        /* If yes, save to file and to an output variable */
        if($checkExistence != true) {
            $i = $i + 1;
            $output .=  " || ". $i .". ".$trello['name'];
            file_put_contents("Projekte.txt", print_r(" || ". $i .". ".$trello['name'],true),FILE_APPEND);
        }
    }


    /* print the output variable to also get feedback on the synchronization website */
    print_r($output);
?>
