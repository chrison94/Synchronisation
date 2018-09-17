<?php
	class trelloFunctions {

        function __construct($id = null) {
            $this->boardID = $id;
            $addon = rex_addon::get('MocoTrello');
            $this->trelloKey = $addon->getConfig('trello_key');
            $this->trelloToken = $addon->getConfig('trello_token');
        }

      /*****************************/
     /* to receive data from APIs */
    /*****************************/

        function getTrelloData() {
            $urlgT = 'https://api.trello.com/1/boards/'.$this->boardID.'/cards?key='.$this->trelloKey.'&token='.$this->trelloToken.'&fields=name,url,idMembers,idList,closed,idBoard,desc,idLabels';
			$chgT = curl_init();
			curl_setopt($chgT, CURLOPT_URL, $urlgT);
			curl_setopt($chgT, CURLOPT_HTTPGET, TRUE);
			curl_setopt($chgT, CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
			curl_setopt($chgT, CURLOPT_RETURNTRANSFER, true);
			$VARIABLEgT = curl_exec($chgT);
			curl_close($chgT);
			$jsongT = json_decode($VARIABLEgT, true);
			return $jsongT;
		}

        function getTrelloLabels() {
			$urlgT = 'https://api.trello.com/1/boards/'.$this->boardID.'/labels?key='.$this->trelloKey.'&token='.$this->trelloToken;
			$chgT = curl_init();
			curl_setopt($chgT, CURLOPT_URL, $urlgT);
			curl_setopt($chgT, CURLOPT_HTTPGET, TRUE);
			curl_setopt($chgT, CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
			curl_setopt($chgT, CURLOPT_RETURNTRANSFER, true);
			$VARIABLEgT = curl_exec($chgT);
			curl_close($chgT);
			$jsongT = json_decode($VARIABLEgT, true);
			return $jsongT;
		}

        function getTrelloListData() {
			$urlgT = 'https://api.trello.com/1/boards/'.$this->boardID.'/lists?key='.$this->trelloKey.'&token='.$this->trelloToken;
			$chgT = curl_init();
			curl_setopt($chgT, CURLOPT_URL, $urlgT);
			curl_setopt($chgT, CURLOPT_HTTPGET, TRUE);
			curl_setopt($chgT, CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
			curl_setopt($chgT, CURLOPT_RETURNTRANSFER, true);
			$VARIABLEgT = curl_exec($chgT);
			curl_close($chgT);
			$jsongT = json_decode($VARIABLEgT, true);
			return $jsongT;
		}

        function getTrelloArchivedData() {
			$urlgT = 'https://api.trello.com/1/boards/'.$this->boardID.'/cards/all?key='.$this->trelloKey.'&token='.$this->trelloToken;
			$chgT = curl_init();
			curl_setopt($chgT, CURLOPT_URL, $urlgT);
			curl_setopt($chgT, CURLOPT_HTTPGET, TRUE);
			curl_setopt($chgT, CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
			curl_setopt($chgT, CURLOPT_RETURNTRANSFER, true);
			$VARIABLEgT = curl_exec($chgT);
			curl_close($chgT);
			$jsongT = json_decode($VARIABLEgT, true);
			return $jsongT;
		}

      /**********************************/
     /* to send data to APIs with POST */
    /**********************************/

        function createNewTrelloLabels($newCustomerData) {
            foreach($newCustomerData as $data) {
                if($data != '') {
                    $name = rawurlencode($data);
					$urlT = 'https://api.trello.com/1/labels?name='.$name.'&color=black&idBoard='.$this->boardID.'&key='.$this->trelloKey.'&token='.$this->trelloToken;                    
					$chT = curl_init();
					curl_setopt($chT, CURLOPT_URL, $urlT);
					curl_setopt($chT, CURLOPT_POST, TRUE);
					curl_setopt($chT, CURLOPT_RETURNTRANSFER, true);
					curl_exec($chT);
					curl_close($chT);
                }
            }
		}

        function sendNewTrelloCard($name, $labelID, $memberID, $info, $listID, $moco_project_name, $customs , $leader, $moco_id, $identifier, $moco_active, $customer_name) {
            $sql = rex_sql::factory();
            $sql->setQuery('INSERT INTO rex_synchronisation (`identifier`,`name`,`info`,`active`,`Status`,`customer`,`user_id`,`moco_id`,`Intern`) VALUES("'.$identifier.'","'.$moco_project_name.'","'.$info.'","'.$moco_active.'","'.$customs["Status"].'","'.$customer_name["name"].'","'.$leader["id"].'","'.$moco_id.'","'.$customs["Intern"].'")');
			$name = rawurlencode($name);
			$info = rawurlencode($info);
			$labelID = rawurlencode($labelID);
			$listID = rawurlencode($listID);
			$urlT = 'https://api.trello.com/1/cards?name='.$name.'&idLabels='.$labelID.'&idMembers='.$memberID.'&desc='.$info.'&idList='.$listID.'&keepFromSource=all&key='.$this->trelloKey.'&token='.$this->trelloToken;
			$chT = curl_init();
			curl_setopt($chT, CURLOPT_URL, $urlT);
			curl_setopt($chT, CURLOPT_POST, TRUE);
			curl_setopt($chT, CURLOPT_RETURNTRANSFER, true);
			curl_exec($chT);
			curl_close($chT);
		}

      /*********************************/
     /* to send data to APIs with PUT */
    /*********************************/

        function archiveTrelloCards($id, $identifier) {
            $sql = rex_sql::factory();
            $sql->setQuery('UPDATE rex_synchronisation SET active = "" WHERE identifier = "'.$identifier.'"');

            $urlT = 'https://api.trello.com/1/cards/'.$id.'?closed=true&key='.$this->trelloKey.'&token='.$this->trelloToken;
			$chT = curl_init();
			curl_setopt($chT, CURLOPT_URL, $urlT);
			curl_setopt($chT, CURLOPT_CUSTOMREQUEST, "PUT");
			curl_setopt($chT, CURLOPT_HEADER, false);
			curl_setopt($chT, CURLOPT_RETURNTRANSFER, true);
			curl_exec($chT);
			curl_close($chT);
		}

        function unarchiveTrelloCard($identifier) {
			$trelloData = $this->getTrelloArchivedData();
			foreach($trelloData as $trello) {
				$trelloIdentifier = stristr($trello['name'],$identifier);                
				if($identifier == $trelloIdentifier) {
					$urlT = 'https://api.trello.com/1/cards/'.$trello['id'].'?closed=false&key='.$this->trelloKey.'&token='.$this->trelloToken;
					$chT = curl_init();
					curl_setopt($chT, CURLOPT_URL, $urlT);
					curl_setopt($chT, CURLOPT_CUSTOMREQUEST, "PUT");
					curl_setopt($chT, CURLOPT_HEADER, false);
					curl_setopt($chT, CURLOPT_RETURNTRANSFER, true);
					curl_exec($chT);
					curl_close($chT);
				}
			}
		}

        function reviseTrelloProjectName($trelloID,$oldName) {
			$oldName = rawurlencode($oldName);
			$urlT = 'https://api.trello.com/1/cards/'.$trelloID.'?name='.$oldName.'&key='.$this->trelloKey.'&token='.$this->trelloToken;
			$chT = curl_init();
			curl_setopt($chT, CURLOPT_URL, $urlT);
			curl_setopt($chT, CURLOPT_CUSTOMREQUEST, "PUT");
			curl_setopt($chT, CURLOPT_HEADER, false);
			curl_setopt($chT, CURLOPT_RETURNTRANSFER, true);
			curl_exec($chT);
			curl_close($chT);
		}

		function sendMocoToTrello($name, $labelID, $info, $listID, $cardID, $moco_project_name, $moco_info, $customs , $leader, $moco_id, $identifier, $moco_active, $customer_name) {
            $sql = rex_sql::factory();
            $sql_data = $sql->setQuery('SELECT * from rex_synchronisation');
            $sql_data = $sql_data->getArray();
            foreach($sql_data as $sql_dat) {
              if($identifier == $sql_dat['identifier']) {
                  $sql->setQuery('UPDATE rex_synchronisation SET name = "'.$moco_project_name.'", info = "'.$moco_info.'", active = "'.$moco_active.'", Status = "'.$customs["Status"].'", customer = "'.$customer_name["name"].'", user_id = "'.$leader["id"].'", moco_id = "'.$moco_id.'", Intern = "'.$customs['Intern'].'" WHERE  identifier = "'.$identifier.'"');
              }
            }
            $name = rawurlencode($name);
			$info = rawurlencode($info);
			$labelID = rawurlencode($labelID);
			$listID = rawurlencode($listID);

			$urlT = 'https://api.trello.com/1/cards/'.$cardID.'?name='.$name.'&desc='.$info.'&idList='.$listID.'&idLabels='.$labelID.'&key='.$this->trelloKey.'&token='.$this->trelloToken;
			$chT = curl_init();
			curl_setopt($chT, CURLOPT_URL, $urlT);
			curl_setopt($chT, CURLOPT_CUSTOMREQUEST, "PUT");
			curl_setopt($chT, CURLOPT_HEADER, false);
			curl_setopt($chT, CURLOPT_RETURNTRANSFER, true);
			curl_exec($chT);
			curl_close($chT);
		}

		function reviseArchiveTrelloCards($cardId) {
			$urlT = 'https://api.trello.com/1/cards/'.$cardId.'?closed=false&key='.$this->trelloKey.'&token='.$this->trelloToken;
			$chT = curl_init();
			curl_setopt($chT, CURLOPT_URL, $urlT);
			curl_setopt($chT, CURLOPT_CUSTOMREQUEST, "PUT");
			curl_setopt($chT, CURLOPT_HEADER, false);
			curl_exec($chT);
			curl_close($chT);
		}

      /*********************************/
     /* Functions for evaluating data */
    /*********************************/

        function getTrelloArchiveStatus($mocoData,$trelloData) {
			foreach($trelloData as $trello) {
				$trelloID = stristr($trello['name'],$mocoData['identifier']);
				if($trelloID == $mocoData['identifier']) {
					return $trello['closed'];
				}
			}
		}

		function checkTrelloProjectExists($mocoData,$trelloData) {
			foreach($trelloData as $data) {
				$trelloIdentifier = stristr($data['name'],$mocoData['identifier']);
				if($mocoData['identifier'] == $trelloIdentifier) {
					return true;
				}
			}
		}

		function setTrelloLabel($trelloArrayExistingLabels,$mocoData) {
			$mocoCustomerData = $mocoData['customer'];
			$mocoCustomer = $mocoCustomerData['name'];
			foreach($trelloArrayExistingLabels as $customerLabel) {
				if($customerLabel['name'] == $mocoCustomer) {
					return $customerLabel['id'];
				}
			}
		}

		function changeTrelloName($mocoData,$trelloData) {
			$name = $mocoData['name'].' -- '.$mocoData['identifier'];
			return $name;
		}

		function findTrelloProject($mocoData, $trelloData) {
			foreach($trelloData as $data) {
				$identifier = stristr($data['name'],$mocoData['identifier']);
				if($identifier == $mocoData['identifier']) {
					return $data;
				}
			}
		}

		function checkDescriptionChange($mocoData,$trelloData) {
			if($trelloData['desc'] != $mocoData['info']) {
				$info = $mocoData['info'];
				return $info;
			}
			else {
				$infoBefore = $trelloData['desc'];
				return $infoBefore;
			}
		}

		function checkCompanyChange($mocoData,$trelloData) {
			$mocoCustomer = $mocoData['customer'];
			$mocoLabelId = $this->getTrelloLabelId($mocoCustomer['name']);
			if($trelloData['idLabels'] != $mocoLabelId) {
				$idLabel = $mocoLabelId;
				return $idLabel;
			}
			else {
				$idLabelBefore = $trelloData['idLabels'];
				return $idLabelBefore;
			}
		}

		function checkStatusChange($mocoData,$trelloData) {
			$customProperties = $mocoData['custom_properties'];
			$listIDMoco = $this->getTrelloListId($customProperties['Status']);
			if($listIDMoco != $trelloData['idList']) {
				$idList = $listIDMoco;
				return $idList;
			}
			else {
				$idListBefore = $trelloData['idList'];
				return $idListBefore;
			}
		}

        function getTrelloCardId($mocoData,$trelloData) {
            $trelloID = stristr($trelloData['name'],$mocoData['identifier']);
            if($trelloID == $mocoData['identifier']) {
                return $trelloData['id'];
            }
		}

		function getTrelloCardIdArchive($mocoData,$trelloData) {
            foreach($trelloData as $data) {
                $trelloID = stristr($data['name'],$mocoData['identifier']);
                if($trelloID == $mocoData['identifier']) {
                    return $data['id'];
                }
            }
		}

		function getTrelloListId($status) {
			$trelloData = $this->getTrelloListData();
			foreach($trelloData as $trello) {
				if($status == $trello['name']) {
					return $trello['id'];
				}
			}
		}

		function checkIsArchived($identifier,$trelloData) {
			foreach($trelloData as $data) {
				$trelloIdentifier = stristr($data['name'],$identifier);
				if($identifier == $trelloIdentifier) {
					if($data['closed'] == true) {
						return true;
					}
					else { return false;}
				}
			}
		}

      /*****************************/
     /* internal Trello-Functions */
    /*****************************/

		function getTrelloLabelId($mocoCustomerName) {
			$trelloLabels = $this->getTrelloLabels();
			foreach($trelloLabels as $trelloLabel) {
				if($trelloLabel['name'] == $mocoCustomerName) {
					return $trelloLabel['id'];
				}
			}
		}
    }

?>