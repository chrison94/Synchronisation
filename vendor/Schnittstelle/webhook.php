<?php
/* DEBUGS
file_put_contents("Test.txt", print_r($output,true),FILE_APPEND);
*/
include_once 'functions.php';
$api = new MocoSyncTrello();
$body = json_decode(file_get_contents("php://input"),FALSE);
$output = $body->action;
file_put_contents("Test.txt", print_r($body,true),FILE_APPEND);
/* informations for sync */
$cardId = $body->action->data->card->id;
$functionType = $body->action->type;
$name = $body->action->display->entities->card->text;
$cardName = trim(substr($name, 0, strpos($name, '--')));

/* list of projectcard */
$checkList = $body->action->display->entities->listAfter;
$boolCheckList = array_key_exists('text',$checkList);
$listAfter = $body->action->display->entities->listAfter->text;

/* description of projectcard */
$checkInfo = $body->action->display->entities->card;
$boolCheckInfo = array_key_exists('desc',$checkInfo);
$infoAfter = $body->action->display->entities->card->desc;

/* new comment of projectcard */
$checkComment = $body->action->display->entities->comment;
$boolCheckComment = array_key_exists('text',$checkComment);
$commentAfter = $body->action->display->entities->comment->text;

/* revise name */
$checkOldName = $body->action->data->old;
$boolCheckOldName = array_key_exists('name',$checkOldName);
$oldName = $body->action->data->old->name;
$newName = $body->action->data->card->name;

/* archivestatus of projectcards */
$archivedIdentifier = $body->action->data->old->closed;
$closed = $body->action->data->card->closed;

/* if functionType is updateCard/commentCard */
if($functionType == 'updateCard' || $functionType == 'commentCard') {
    /* get all existing projects from moco */
    $sql = rex_sql::factory();
    $mocoAppData = $sql->setQuery('SELECT * from rex_synchronisation');
    $mocoAppData = $mocoAppData->getArray();
    /* go through the projects */
	foreach($mocoAppData as $mocoData) {
		$mocoArchiveStatus = $mocoData['active'];
		$identifier = stristr($name,$mocoData['identifier']);

        /* if changed Trello-Project exists in MOCO */
		if($identifier == $mocoData['identifier']) {

            /* Check if name has been changed */
            if($boolCheckOldName == TRUE) {
                /* True -> Check if new name exists in MOCO */
                $checkExistence = $api->checkProjectExists($mocoData,$newName);
                /* if it not exists -> change to old name */
                if($checkExistence == false) {
                    $api->reviseTrelloProjectName($cardId,$oldName);
                }
		    }

            /* Determining the ID of the Moco project that was changed in Trello */
			$changeMocoDataID = $api->getMocoAppIdFromTrelloDB($mocoData,$cardName);
			if($changeMocoDataID != NULL) {

                /* Check if list has been changed   */
				if($boolCheckList == TRUE && $listAfter != $mocoData['Status']) {
					$api->updateStatusInMoco($changeMocoDataID,$listAfter,$mocoData);
				}

                /* Check if info has been changed */
				if($boolCheckInfo == TRUE && $infoAfter != $mocoData['info']) {
					$api->updateInfoInMoco($changeMocoDataID,$infoAfter);
				}

                /* Check if archive status has been changed */
				if($closed == '1' || $archivedIdentifier == '1') {
					if($closed == '1' && $mocoArchiveStatus == '1') {
						$api->reviseArchiveTrelloCards($cardId);
					}
					if($closed == '' && $mocoArchiveStatus == '0') {
						$api->archiveTrelloCards($cardId,$mocoData['identifier']);
					}
				}

                /* Check if a Trello card has been commented */
				if($boolCheckComment == TRUE) {
						$api->sendNewMocoComment($commentAfter,$changeMocoDataID);
                }
			}
		}
	}
}

?>
