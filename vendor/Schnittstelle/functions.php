<?php
include_once 'moco/functions.php';
include_once 'trello/functions.php';


class MocoSyncTrello {

    function __construct($id = null, $firstname = null) {
        $this->id = $id;
        $this->firstname = $firstname;
    }

      /****************************/
     /* Main-Function, algorithm */
    /****************************/

    function algorithm($mocoData,$trelloData,$trelloArrayExistingLabels,$trelloArchivedData) {
        /* initialize variables for later use */
        $info = $mocoData['info'];
        $checkSpecial = $mocoData['custom_properties'];
        $identifier = $mocoData['identifier'];
        $name = $mocoData['name'].' -- '.$mocoData['identifier'];
        $moco_project_name = $mocoData['name'];
        $memberID = $this->getTrelloMemberId($mocoData['leader']);
        $leader = $mocoData['leader'];
        $moco_id = $mocoData['id'];
        $moco_active = $mocoData["active"];
        $customer_name = $mocoData['customer'];
        $customs = $mocoData['custom_properties'];
        $moco_info = $mocoData['info'];
        if ($identifier != '') {
            /* check for editing a projectcard or create a new projectcard */
			$exists = $this->checkTrelloProjectExists($mocoData, $trelloData);


			/* if exists */
			if ($exists == true) {

				/* checking the archiving status */
				$checkArchived = $this->checkIsArchived($identifier, $trelloArchivedData);
				if ($checkArchived == true) {
                    /* If the project card is archived in Trello but not in moco unarchive the projet card */
					$this->unarchiveTrelloCard($identifier);
				}

				/* find adequate trello-projectcard */
				$trelloProject = $this->findTrelloProject($mocoData, $trelloData);
				$cardID = $this->getTrelloCardId($mocoData, $trelloProject);
				$nameBefore = $trelloProject['name'];
				$listIDBefore = $trelloProject['idList'];
				$infoBefore = $trelloProject['desc'];
				$labelIDBefore = $trelloProject['idLabels'];
				$labelIDBefore = $labelIDBefore[0];

				/* check for changes */
				$name = $this->changeTrelloName($mocoData, $trelloProject);
				$listID = $this->checkStatusChange($mocoData, $trelloProject);
				$info = $this->checkDescriptionChange($mocoData, $trelloProject);
				$labelID = $this->checkCompanyChange($mocoData, $trelloProject);

                /* send updated card to Trello */
				if($name != $nameBefore || $listID != $listIDBefore || $info != $infoBefore || $labelID != $labelIDBefore) {
					$this->sendMocoToTrello($name, $labelID, $info, $listID, $cardID, $moco_project_name, $moco_info, $customs, $leader, $moco_id, $identifier, $moco_active, $customer_name);
				}
            }


				/* if new */
			else {
                /* get the label id for the new project card */
				$labelID = $this->setTrelloLabel($trelloArrayExistingLabels, $mocoData);

                /* get the list id for the new project card */
				$listID = $this->getTrelloListId($checkSpecial['Status']);

                /* send the new project from MOCO with all necessary data to Trello */
				$this->sendNewTrelloCard($name, $labelID, $memberID, $info, $listID, $moco_project_name, $customs , $leader, $moco_id, $identifier, $moco_active, $customer_name);
			}
		}
    }

      /*******************************/
     /* Main-Function for Archiving */
    /*******************************/

    function archive($mocoData,$trelloData) {
        /* check for archiving status */
			$trelloArchiveStatus = $this->getTrelloArchiveStatus($mocoData,$trelloData);
			/* if not archived -> archiv projectcard */
			if($trelloArchiveStatus == false) {
				$trelloArchiveID = $this->getTrelloCardIdArchive($mocoData, $trelloData);
				$this->archiveTrelloCards($trelloArchiveID, $mocoData['identifier']);
			}
    }

      /*****************************/
     /* to receive data from APIs */
    /*****************************/

	function getMocoAppData() {
		$moco = new mocoFunctions();
		return $moco->getMocoAppData();
	}

	function getCustomer() {
		$moco = new mocoFunctions();
		return $moco->getCustomer();
	}

	function getTrelloData() {
		$trello = new trelloFunctions($this->id, $this->firstname);
		return $trello->getTrelloData();
	}

    function getTrelloLabels() {
		$trello = new trelloFunctions($this->id, $this->firstname);
		return $trello->getTrelloLabels();
	}

	function getTrelloListData() {
		$trello = new trelloFunctions($this->id, $this->firstname);
		return $trello->getTrelloListData();
	}

    function getTrelloArchivedData() {
		$trello = new trelloFunctions($this->id, $this->firstname);
		return $trello->getTrelloArchivedData();
	}

      /**********************************/
     /* to send data to APIs with POST */
    /**********************************/

    function sendNewMocoComment($newComment,$changeMocoDataID) {
		$moco = new mocoFunctions();
		return $moco->sendNewMocoComment($newComment,$changeMocoDataID);
	}

	function createNewTrelloLabels($newCustomerData) {
		$trello = new trelloFunctions($this->id, $this->firstname);
		return $trello->createNewTrelloLabels($newCustomerData);
	}

	function sendNewTrelloCard($name, $labelID, $memberID, $info, $listID, $moco_project_name, $customs , $leader, $moco_id, $identifier, $moco_active, $customer_name) {
		$trello = new trelloFunctions($this->id, $this->firstname);
		return $trello->sendNewTrelloCard($name, $labelID, $memberID, $info, $listID, $moco_project_name, $customs , $leader, $moco_id, $identifier, $moco_active, $customer_name);
	}


      /*********************************/
     /* to send data to APIs with PUT */
    /*********************************/

    function updateCustomPropertiesInMoco($changeMocoDataID,$list) {
		$moco = new mocoFunctions();
		return $moco->updateCustomPropertiesInMoco($changeMocoDataID,$list);
	}

    function updateStatusInMoco($changeMocoDataID,$listAfter,$mocoData) {
		$moco = new mocoFunctions();
		return $moco->updateStatusInMoco($changeMocoDataID,$listAfter,$mocoData);
	}

    function updateInfoInMoco($changeMocoDataID,$infoAfter) {
		$moco = new mocoFunctions();
		return $moco->updateInfoInMoco($changeMocoDataID,$infoAfter);
	}

	function archiveTrelloCards($id, $identifier) {
		$trello = new trelloFunctions($this->id, $this->firstname);
		return $trello->archiveTrelloCards($id, $identifier);
	}

	function unarchiveTrelloCard($identifier) {
		$trello = new trelloFunctions($this->id, $this->firstname);
		return $trello->unarchiveTrelloCard($identifier);
	}

	function reviseTrelloProjectName($changeMocoDataID,$oldName) {
		$trello = new trelloFunctions($this->id, $this->firstname);
		return $trello->reviseTrelloProjectName($changeMocoDataID,$oldName);
	}

    function sendMocoToTrello($name, $labelID, $info, $listID, $cardID, $moco_project_name, $moco_info, $customs , $leader, $moco_id, $identifier, $moco_active, $customer_name) {
		$trello = new trelloFunctions($this->id, $this->firstname);
		return 	$trello->sendMocoToTrello($name, $labelID, $info, $listID, $cardID, $moco_project_name, $moco_info, $customs , $leader, $moco_id, $identifier, $moco_active, $customer_name);
	}

	function reviseArchiveTrelloCards($cardId) {
		$trello = new trelloFunctions($this->id, $this->firstname);
		return $trello->reviseArchiveTrelloCards($cardId);
	}

      /************************************/
     /* to send data to APIs with DELETE */
    /************************************/

    function deleteTrelloCard($id) {
        $trello = new trelloFunctions($this->id, $this->firstname);
        return $trello->deleteTrelloCard($id);
    }

      /*********************************/
     /* Functions for evaluating data */
    /*********************************/

    function checkProjectExists($mocoData,$oldName) {
		$moco = new mocoFunctions();
		return $moco->checkProjectExists($mocoData,$oldName);
	}

    function getMocoAppIdFromTrello($mocoData,$cardName) {
		$moco = new mocoFunctions();
		return $moco->getMocoAppIdFromTrello($mocoData,$cardName);
	}

    function getMocoAppIdFromTrelloDB($mocoData,$cardName) {
		$moco = new mocoFunctions();
		return $moco->getMocoAppIdFromTrelloDB($mocoData,$cardName);
	}

    function checkProjectsFunction($mocoData,$name) {
        $moco = new mocoFunctions();
        return $moco->checkProjectsFunction($mocoData,$name);
    }

    function getTrelloArchiveStatus($mocoData,$trelloData) {
		$trello = new trelloFunctions($this->id, $this->firstname);
		return $trello->getTrelloArchiveStatus($mocoData,$trelloData);
	}

    function checkTrelloProjectExists($mocoData,$trelloData) {
		$trello = new trelloFunctions($this->id, $this->firstname);
		return $trello->checkTrelloProjectExists($mocoData,$trelloData);
	}

    function setTrelloLabel($trelloArrayExistingLabels,$mocoData) {
		$trello = new trelloFunctions($this->id, $this->firstname);
		return $trello->setTrelloLabel($trelloArrayExistingLabels,$mocoData);
	}

	function changeTrelloName($mocoData,$trelloData) {
		$trello = new trelloFunctions($this->id, $this->firstname);
		return $trello->changeTrelloName($mocoData,$trelloData);
	}

    function findTrelloProject($mocoData, $trelloData) {
		$trello = new trelloFunctions($this->id, $this->firstname);
		return $trello->findTrelloProject($mocoData, $trelloData);
	}

    function checkDescriptionChange($mocoData,$trelloData) {
		$trello = new trelloFunctions($this->id, $this->firstname);
		return $trello->checkDescriptionChange($mocoData,$trelloData);
	}

	function checkCompanyChange($mocoData,$trelloData) {
		$trello = new trelloFunctions($this->id, $this->firstname);
		return $trello->checkCompanyChange($mocoData,$trelloData);
	}

	function checkStatusChange($mocoData,$trelloData) {
		$trello = new trelloFunctions($this->id, $this->firstname);
		return $trello->checkStatusChange($mocoData,$trelloData);
	}

	function getTrelloCardId($mocoData,$trelloData) {
		$trello = new trelloFunctions($this->id, $this->firstname);
		return $trello->getTrelloCardId($mocoData,$trelloData);
	}

  	function getTrelloCardIdArchive($mocoData,$trelloData) {
		$trello = new trelloFunctions($this->id, $this->firstname);
		return $trello->getTrelloCardIdArchive($mocoData,$trelloData);
	}

	function getTrelloListId($status) {
		$trello = new trelloFunctions($this->id, $this->firstname);
		return $trello->getTrelloListId($status);
	}

	function checkIsArchived($identifier,$trelloData) {
		$trello = new trelloFunctions($this->id, $this->firstname);
		return $trello->checkIsArchived($identifier,$trelloData);
	}

      /**************************************************/
     /* hardcoded-Function to set the member of a card */
    /**************************************************/

	function getTrelloMemberId(array $mocoMembers) {
        $addon = rex_addon::get('MocoTrello');
        $this->mocoWorkerName = $addon->getConfig('moco_worker_moco_name');
        $this->workerTrelloID= $addon->getConfig('moco_worker_moco_id'); 
        $this->mocoCoWorkerName = $addon->getConfig('moco_co_worker_moco_name');
        $this->coWorkerTrelloID= $addon->getConfig('moco_co_worker_moco_id');
		if($mocoMembers['firstname'] == $this->mocoWorkerName) {
			return $this->workerTrelloID;
		}
        if($mocoMembers['firstname'] == $this->mocoCoWorkerName) {
			return $this->coWorkerTrelloID;
		}
	}
}

?>
