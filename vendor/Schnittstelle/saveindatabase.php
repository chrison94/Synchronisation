<?php
	/* VARIABLES and debugs */
	//file_put_contents("Test.txt", print_r($mocoAppData,true),FILE_APPEND);
$board = $moco = $esperantoBoard = $trelloData = $trelloEsperantoData = $mocoAppData = $trelloExistingLabels = $trelloArchivedData = $trelloArchivedEsperantoData = $trelloExistingEsperantoLabels = $trelloArrayExistingLabels = '';
$addon = rex_addon::get('MocoTrello');

/*include_once 'functions.php';

/* init MOCO-object 
$moco = new MocoSyncTrello();

/* step 1 get moco data 
$mocoAppData = $moco->getMocoAppData();
/*foreach ($mocoAppData as $mocoData) {
	$checkSync = $mocoData['custom_properties'];
	if ($checkSync['Synchronisation'] !== '1') {
		unset($mocoAppData[key($mocoAppData)]);
	}
}
$updateMoco = $mocoAppData;
/* erhalte alle Daten aus der Datenbank 
$sql = rex_sql::factory();
$sql_data = $sql->setQuery('SELECT * from rex_synchronisation');
$sql_data = $sql_data->getArray();
$usql_data = $sql_data;
$mo["active"] = '';
$mo["info"] = '';
/* überprüfe ob neu? 
foreach($mocoAppData as $mo) {
    foreach($sql_data as $sql_dat) {
        if($mo['identifier'] == $sql_dat['identifier']) {
		    unset($mocoAppData[key($mocoAppData)]);
        }
    }
 } 
/* überprüfe ob updaten 
foreach($updateMoco as $umo) {
    foreach($usql_data as $usql_data) {
        if($umo['identifier'] != $usql_data['identifier']) {
		    unset($updateMoco[key($mocoAppData)]);
        }
    }
}

/* Einfügen 
foreach($mocoAppData as $mo) {
    $customs = $mo['custom_properties'];
    $customer = $mo['customer'];
    $leader = $mo['leader'];
    $sql->setQuery('INSERT INTO rex_synchronisation (`identifier`,`name`,`info`,`active`,`Status`,`customer`,`user_id`,`moco_id`) VALUES("'.$mo["identifier"].'","'.$mo["name"].'","'.$mo["info"].'","'.$mo["active"].'","'.$customs["Status"].'","'.$customer["name"].'","'.$leader["id"].'","'.$mo['id'].'")');
}

/* updaten 
foreach($updateMoco as $updateMo) {
    foreach($sql_data as $sql_dat) {
        $customs = $updateMo['custom_properties'];
        $customer = $updateMo['customer'];
        $leader = $updateMo['leader'];
        if($updateMo['identifier'] == $sql_dat['identifier']) {
            $sql->setQuery('UPDATE rex_synchronisation SET name = "'.$updateMo["name"].'", info = "'.$updateMo["info"].'", active = "'.$updateMo["active"].'", Status = "'.$customs["Status"].'", customer = "'.$customer["name"].'", user_id = "'.$leader["id"].'", moco_id = "'.$updateMo['id'].'" WHERE  identifier = "'.$updateMo["identifier"].'"');            
        }       
    }
}
    ?> */