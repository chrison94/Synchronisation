<?php
    class mocoFunctions {

        function __construct() {
            $addon = rex_addon::get('MocoTrello');
            $this->mocoKey = $addon->getConfig('moco_key');
            $this->sitename = $addon->getConfig('moco_workspace');
        }
      /*****************************/
     /* to receive data from APIs */
    /*****************************/

    	function getMocoAppData() {
    		$page = 1;
    		$pages = 'notChecked';
    		$mocoAppData = $this->getMocoAppPageData($page);
    		$count = count($mocoAppData);
    		while($pages != 'check') {
    			if($count == 100) {
    				$page = $page+1;
    				$mocoAppDataNext = $this->getMocoAppPageData($page);
    				$count = count($mocoAppDataNext);
    				$mocoAppData = array_merge($mocoAppData,$mocoAppDataNext);
    			}
    			else {
    				$pages = 'check';
    			}
    		}
    		return $mocoAppData;
    	}

    	function getCustomer() {
    		$urlgM =  'https://'.$this->sitename.'.mocoapp.com/api/v1/customers';
    		$chgM = curl_init();
    		curl_setopt($chgM, CURLOPT_URL, $urlgM);
    		curl_setopt($chgM, CURLOPT_HTTPGET, TRUE);
    		curl_setopt($chgM, CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
    		curl_setopt($chgM, CURLOPT_HTTPHEADER,array('Authorization: Token token='.$this->mocoKey));
    		curl_setopt($chgM, CURLOPT_RETURNTRANSFER, true);
    		$VARIABLEgM = curl_exec($chgM);
    		curl_close($chgM);
    		$jsongM = json_decode($VARIABLEgM, true);
    		return $jsongM;
    	}

      /**********************************/
     /* to send data to APIs with POST */
    /**********************************/

        function sendNewMocoComment($newComment,$changeMocoDataID) {
    		$send = 'commentable_id='.$changeMocoDataID.'&commentable_type=Project&text='.rawurlencode($newComment).'';
    		$urlpM =  'https://'.$this->sitename.'.mocoapp.com/api/v1/comments';
    		$chpM = curl_init();
    		curl_setopt($chpM, CURLOPT_URL, $urlpM);
    		curl_setopt($chpM, CURLOPT_POST, true);
    		curl_setopt($chpM, CURLOPT_HTTPHEADER,array('Content-Type: application/x-www-form-urlencoded'));
    		curl_setopt($chpM, CURLOPT_HTTPHEADER,array('Authorization: Token token='.$this->mocoKey));
    		curl_setopt($chpM, CURLOPT_POSTFIELDS,$send);
    		curl_exec($chpM);
    		curl_close($chpM);
    	}

      /*********************************/
     /* to send data to APIs with PUT */
    /*********************************/

    	function updateCustomPropertiesInMoco($changeMocoDataID,$list) {
    		$urlpM =  'https://'.$this->sitename.'.mocoapp.com/api/v1/projects/'.$changeMocoDataID;
    		$send = 'custom_properties[Synchronisation]=0&custom_properties[Status]='.$list;
    		$chpM = curl_init();
    		curl_setopt($chpM, CURLOPT_URL, $urlpM);
    		curl_setopt($chpM, CURLOPT_CUSTOMREQUEST, "PUT");
    		curl_setopt($chpM, CURLOPT_HTTPHEADER,array('Content-Type: application/x-www-form-urlencoded')); 
    		curl_setopt($chpM, CURLOPT_HTTPHEADER,array('Authorization: Token token='.$this->mocoKey)); 
    		curl_setopt($chpM, CURLOPT_POSTFIELDS,$send);
    		curl_exec($chpM);
    		curl_close($chpM);
    	}

    	function updateStatusInMoco($changeMocoDataID,$listAfter,$mocoData) {
            $intern = rawurlencode($mocoData['Intern']);
    		$send ='custom_properties[Status]='.$listAfter.'&custom_properties[Intern]='.$intern;
            file_put_contents("Test.txt", print_r('JAAAJAAAA',true));
    		$urlpM =  'https://'.$this->sitename.'.mocoapp.com/api/v1/projects/'.$changeMocoDataID;
            $sql = rex_sql::factory();
            $sql->setQuery('UPDATE rex_synchronisation SET Status = "'.$listAfter.'" WHERE  moco_id = "'.$changeMocoDataID.'"');
    		$chpM = curl_init();
    		curl_setopt($chpM, CURLOPT_URL, $urlpM);
    		curl_setopt($chpM, CURLOPT_CUSTOMREQUEST, "PUT");
    		curl_setopt($chpM, CURLOPT_HTTPHEADER,array('Content-Type: application/x-www-form-urlencoded'));
    		curl_setopt($chpM, CURLOPT_HTTPHEADER,array('Authorization: Token token='.$this->mocoKey));
    		curl_setopt($chpM, CURLOPT_POSTFIELDS,$send);
    		curl_exec($chpM);
    		curl_close($chpM);
    	}

    	function updateInfoInMoco($changeMocoDataID,$infoAfter) {
    		$send = 'info='.rawurlencode($infoAfter);
    		$urlpM =  'https://'.$this->sitename.'.mocoapp.com/api/v1/projects/'.$changeMocoDataID;
            $sql = rex_sql::factory();
            $sql->setQuery('UPDATE rex_synchronisation SET info = "'.$infoAfter.'" WHERE  moco_id = "'.$changeMocoDataID.'"');            
    		$chpM = curl_init();
    		curl_setopt($chpM, CURLOPT_URL, $urlpM);
    		curl_setopt($chpM, CURLOPT_CUSTOMREQUEST, "PUT");
    		curl_setopt($chpM, CURLOPT_HTTPHEADER,array('Content-Type: application/x-www-form-urlencoded'));
    		curl_setopt($chpM, CURLOPT_HTTPHEADER,array('Authorization: Token token='.$this->mocoKey));
    		curl_setopt($chpM, CURLOPT_POSTFIELDS,$send);
    		curl_exec($chpM);
    		curl_close($chpM);
    	}

      /*********************************/
     /* Functions for evaluating data */
    /*********************************/

    	function checkProjectExists($mocoData,$name) {
    		if($mocoData['name'].' -- '.$mocoData['identifier'] == $name) {
    			return true;
    		}
    		return false;
    	}

        function checkProjectsFunction($mocoData,$name) {
            foreach($mocoData as $moco) {
                if($moco['name'].' -- '.$moco['identifier'] == $name) {
                    return true;
                }
            }
        }

    	function getMocoAppIdFromTrello($mocoData,$cardName) {
    		if($mocoData['name'] == $cardName) {
    			return $mocoData['id'];
    		}
    	}

    	function getMocoAppIdFromTrelloDB($mocoData,$cardName) {
    		if($mocoData['name'] == $cardName) {
    			return $mocoData['moco_id'];
    		}
    	}        


      /***************************/
     /* internal MOCO-Functions */
    /***************************/

        function getMocoAppPageData($page) {
            $urlgM =  'https://'.$this->sitename.'.mocoapp.com/api/v1/projects?page='.$page.'&include_archived=true';
            $chgM = curl_init();
            curl_setopt($chgM, CURLOPT_URL, $urlgM);
            curl_setopt($chgM, CURLOPT_HTTPGET, TRUE);
            curl_setopt($chgM, CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
            curl_setopt($chgM, CURLOPT_HTTPHEADER,array('Authorization: Token token='.$this->mocoKey));
            curl_setopt($chgM, CURLOPT_RETURNTRANSFER, true);
            $VARIABLEgM = curl_exec($chgM);
            curl_close($chgM);
            $jsongM = json_decode($VARIABLEgM, true);
            return $jsongM;
        }
    }
?>