<?php
/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */
/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
class tendaac extends eqLogic {
		public static function pull() {
			foreach (eqLogic::byType('tendaac') as $eqTendaac) {
				if($eqTendaac->getIsEnable()){
					if ($eqTendaac->getConfiguration('type') == "box") {
						$eqTendaac->scan();
						log::add('tendaac','debug','Lancement scan CRON auto');
						$status = $eqTendaac->getCmd(null, 'status');
					} else $status = $eqTendaac->getCmd(null, 'present');
					if ($status->execCmd() == '1') {
						$eqTendaac->setCache('lastupdate',date("d-m-Y H:i:s"));
					}
					log::add('tendaac','debug','DEBUGGG' . $eqTendaac->getCache('lastupdate','0'));
				}
			}
		}
		public static function cron() {
			foreach(eqLogic::byType('tendaac') as $eqTendaac){
				if($eqTendaac->getIsEnable()){
					if ($eqTendaac->getConfiguration('type') == "box") {
						if ($eqTendaac->getConfiguration('RepeatCmd') == "cron") {
							$eqTendaac->scan();
							log::add('tendaac','debug','Lancement scan CRON');
						}
					}
				}
			}
		}
		public static function cron5() {
			foreach(eqLogic::byType('tendaac') as $eqTendaac){
				if($eqTendaac->getIsEnable()){
					if ($eqTendaac->getConfiguration('type') == "box") {
						if ($eqTendaac->getConfiguration('RepeatCmd') == "cron5") {
							$eqTendaac->scan();
							log::add('tendaac','debug','Lancement scan CRON5');
						}
					}
				}
			}
		}
		public static function cron10() {
			foreach(eqLogic::byType('tendaac') as $eqTendaac){
				if($eqTendaac->getIsEnable()){
					if ($eqTendaac->getConfiguration('type') == "box") {
						if ($eqTendaac->getConfiguration('RepeatCmd') == "cron10") {
							$eqTendaac->scan();
							log::add('tendaac','debug','Lancement scan CRON10');
						}
					}
				}
			}
		}
		public static function cron15() {
			foreach(eqLogic::byType('tendaac') as $eqTendaac){
				if($eqTendaac->getIsEnable()){
					if ($eqTendaac->getConfiguration('type') == "box") {
						if ($eqTendaac->getConfiguration('RepeatCmd') == "cron15") {
							$eqTendaac->scan();
							log::add('tendaac','debug','Lancement scan CRON15');
						}
					}
				}
			}
		}
		public static function cron30() {
			foreach(eqLogic::byType('tendaac') as $eqTendaac){
				if($eqTendaac->getIsEnable()){
					if ($eqTendaac->getConfiguration('type') == "box") {
						if ($eqTendaac->getConfiguration('RepeatCmd') == "cron30") {
							$eqTendaac->scan();
							log::add('tendaac','debug','Lancement scan CRON30');
						}
					}
				}
			}
		}
		public static function cronHourly() {
			foreach(eqLogic::byType('tendaac') as $eqTendaac){
				if($eqTendaac->getIsEnable()){
					if ($eqTendaac->getConfiguration('type') == "box") {
						if ($eqTendaac->getConfiguration('RepeatCmd') == "cronHourly") {
							$eqTendaac->scan();
							log::add('tendaac','debug','Lancement scan CRONHourly');
						}
					}
				}
			}
		}

	public static function deleteDisabledEQ($what = 'clients') {
		log::add('tendaac', 'info', "deleteDisabledEQ");
		$ignoredNew=[];
		if($what == 'all' || $what == 'clients') {
			$eqLogics = eqLogic::byType('tendaac');
			foreach ($eqLogics as $eqLogic) {
				if($eqLogic->getConfiguration('type','') != 'cli') continue;
					if($what == 'clients' ) {
						if ($eqLogic->getIsEnable() != 1) {
							$ignoredNew[]=$eqLogic->getLogicalId();
							$eqLogic->remove();
						}
					} elseif ($what == 'all' ) {
						$ignoredNew[]=$eqLogic->getLogicalId();
						$eqLogic->remove();
					}
			}
			if(count($ignoredNew)) {
				log::add('tendaac', 'debug', "ignoredNew :".json_encode($ignoredNew));
				$ignoredBefore=config::byKey('ignoredClients','tendaac',[],true);
				if($ignoredBefore==null) $ignoredBefore=[];
				log::add('tendaac', 'debug', "ignoredBefore :".json_encode($ignoredBefore));
				$ignoredClients = array_unique(array_merge($ignoredBefore,$ignoredNew),SORT_REGULAR);
				log::add('tendaac', 'debug', "ignoredClients :".json_encode($ignoredClients));
				config::save('ignoredClients',$ignoredClients,'tendaac');
			}
		}
	}

	public static function syncTendaac($what='all') {
		log::add('tendaac', 'info', "syncTendaac");
			function transforme($time) {
				if ($time>=86400) {
					$jour = floor($time/86400);
					$reste = $time%86400;
					$heure = floor($reste/3600);
					$reste = $reste%3600;
					$minute = floor($reste/60);
					$seconde = $reste%60;
					$result = $jour.'j '.$heure.'h '.$minute.'min '.$seconde.'s';
				}
				elseif ($time < 86400 AND $time>=3600) {
					$heure = floor($time/3600);
					$reste = $time%3600;
					$minute = floor($reste/60);
					$seconde = $reste%60;
					$result = $heure.'h '.$minute.'min '.$seconde.' s';
				}
				elseif ($time<3600 AND $time>=60) {
					$minute = floor($time/60);
					$seconde = $time%60;
					$result = $minute.'min '.$seconde.'s';
				}
				elseif ($time < 60) {
					$result = $time.'s';
				}
				return $result;
			}
		if($what == 'all' || $what == 'clients') {
			$eqLogics = eqLogic::byType('tendaac');
			foreach ($eqLogics as $eqLogic) {
				if($eqLogic->getConfiguration('type','') != 'box' || $eqLogic->getIsEnable() != 1) {
					continue;
				}
				$connected = $eqLogic->cookieurl('goform/getQos?random=0.46529553086082265&modules=onlineList');
				if (isset($connected)) {
              		$content = json_decode($connected, true);
					if (isset($content["onlineList"])) {
						for($i = 0;$i < count($content["onlineList"]); $i++){
							$Hostname[$i] = $content["onlineList"][$i]["qosListHostname"]; //Unknown
							$Remark[$i] = $content["onlineList"][$i]["qosListRemark"]; //ESP Ballon
							if ($Hostname[$i] == 'Unknown' && (!empty($Remark[$i])))	{
								$Hostname[$i] = $Remark[$i];
							}
							$IPAddress[$i] = $content["onlineList"][$i]["qosListIP"];  //192.168.0.31
							$ConnectType[$i] = $content["onlineList"][$i]["qosListConnectType"]; //wifi ou ou wires
							if (!isset($content["onlineList"][$i]["qosListAccessType"])) {
								$AccessType[$i] = ' ';
							} else {
								$AccessType[$i] = $content["onlineList"][$i]["qosListAccessType"]; //wifi_2G ou wifi_5G
							}
							if ($ConnectType[$i] == 'wifi') {
								$ConnectType[$i] = 'WiFi';
								$AccessType[$i] = $content["onlineList"][$i]["qosListAccessType"]; //wifi_2G ou wifi_5G
								if ($AccessType[$i] == 'wifi_2G') {
									$AccessType[$i] = '2.4 GHz';
								} else if ($AccessType[$i] == 'wifi_5G') {
									$AccessType[$i] = '5 GHz';
								} else {
									$AccessType[$i] = '1';
								}
							} else if ($ConnectType[$i] == 'wires') {
									$ConnectType[$i] = 'Ethernet';
							} else {
								$ConnectType[$i] = '';
							}
							$MACAddress[$i] = $content["onlineList"][$i]["qosListMac"]; //c8:d8:54:6f:aa:ef
							$DownSpeed[$i] = $content["onlineList"][$i]["qosListDownSpeed"]; //1540.00
							$Present[$i] = $content["onlineList"][$i]["qosListAccess"];
							$Type[$i] = $content["onlineList"][$i]["qosListManufacturer"];
							$SimpleDownSpeed[$i] = round($DownSpeed[$i]/1024,2);
							if ($DownSpeed[$i] > 1024) {
								$DownSpeed[$i] = round($DownSpeed[$i]/1024,2).' MB/s';
							} else {
								$DownSpeed[$i] = $DownSpeed[$i].' KB/s';
							}
							$UpSpeed[$i] = $content["onlineList"][$i]["qosListUpSpeed"]; //351.00
							$SimpleUpSpeed[$i] = round($UpSpeed[$i]/1024,2);
							if ($UpSpeed[$i] > 1024) {
								$UpSpeed[$i] = round($UpSpeed[$i]/1024,2).' MB/s';
							} else {
								$UpSpeed[$i] = $UpSpeed[$i].' KB/s';
							}
							$Timest[$i] = $content["onlineList"][$i]["qoslistConnetTime"]; //8085
							$Timest[$i] = transforme($Timest[$i]);

							$client['Name'] = $Hostname[$i];
							$client['Key'] = $MACAddress[$i];
							$client['IPAddress'] = $IPAddress[$i];
							$client['AccessType'] = $ConnectType[$i];
							$client['lastlogin'] = $Timest[$i];
							$client['DownSpeed'] = $SimpleDownSpeed[$i];
							$client['UpSpeed'] = $SimpleUpSpeed[$i];
							$client['deviceType'] = $Type[$i];
							$activeclients[$MACAddress[$i]] = $Present[$i];
							$ignoredClients=config::byKey('ignoredClients','tendaac',[],true);

							if(!in_array($MACAddress[$i],$ignoredClients)) {

								$lbcli = tendaac::byLogicalId($MACAddress[$i], 'tendaac');
								if (!is_object($lbcli)) {
									tendaac::createClient($client, $eqLogic->getId());
									event::add('jeedom::alert', array(
										'level' => 'warning',
										'page' => 'tendaac',
										'message' => __('Client inclus avec succès : ' .$MACAddress[$i], __FILE__),
									));
								}
							}
						}
					}
				}
			}
		}
	}

	public static function noMoreIgnore($what = 'clients') {
		config::remove('ignoredClients','tendaac');
	}
		public function getUrl() {
			$url = 'http://';
			$url .= $this->getConfiguration('ip');
			return $url."/";
		}
		public function preUpdate() {
			if ( $this->getIsEnable() && $this->getConfiguration('type','') == 'box') {
				$reboot = $this->getCmd(null, 'reboot');
				if ( ! is_object($reboot) ) {
					$reboot = new tendaacCmd();
					$reboot->setName('Reboot');
					$reboot->setEqLogic_id($this->getId());
					$reboot->setType('action');
					$reboot->setSubType('other');
					$reboot->setLogicalId('reboot');
					$reboot->setEventOnly(1);
					$reboot->setIsVisible(0);
					$reboot->setDisplay('generic_type','GENERIC_ACTION');
					$reboot->save();
				}
				else {
					if ( $reboot->getDisplay('generic_type') == "" ) {
						$reboot->setDisplay('generic_type','GENERIC_ACTION');
						$reboot->save();
					}
				}
				$backup = $this->getCmd(null, 'backup');
				 if ( ! is_object($backup) ) {
						$backup = new tendaacCmd();
						$backup->setName('Backup');
						$backup->setEqLogic_id($this->getId());
						$backup->setType('action');
						$backup->setSubType('other');
						$backup->setLogicalId('backup');
						$backup->setEventOnly(1);
						$backup->setIsVisible(0);
						$backup->setDisplay('generic_type','GENERIC_ACTION');
						$backup->save();
				}
				else {
					if ( $backup->getDisplay('generic_type') == "" ) {
						$backup->setDisplay('generic_type','GENERIC_ACTION');
						$backup->save();
					}
				}
				$cmd = $this->getCmd(null, 'status');
				if ( is_object($cmd) ) {
					if ( $cmd->getDisplay('generic_type') == "" ) {
						$cmd->setDisplay('generic_type','GENERIC_INFO');
						$cmd->save();
					}
				}
				$cmd = $this->getCmd(null, 'wifistatus');
				if ( is_object($cmd) ) {
					if ( $cmd->getDisplay('generic_type') == "" ) {
						$cmd->setDisplay('generic_type','GENERIC_INFO');
						$cmd->save();
					}
				}
				$cmd = $this->getCmd(null, 'routername');
				if ( is_object($cmd) ) {
					if ( $cmd->getDisplay('generic_type') == "" ) {
						$cmd->setDisplay('generic_type','GENERIC_INFO');
						$cmd->save();
					}
				}
				$cmd = $this->getCmd(null, 'softversion');
				if ( is_object($cmd) ) {
					if ( $cmd->getDisplay('generic_type') == "" ) {
						$cmd->setDisplay('generic_type','GENERIC_INFO');
						$cmd->save();
					}
				}
				$cmd = $this->getCmd(null, 'wifien');
				if ( is_object($cmd) ) {
					if ( $cmd->getDisplay('generic_type') == "" ) {
						$cmd->setDisplay('generic_type','GENERIC_INFO');
						$cmd->save();
					}
				}
				$cmd = $this->getCmd(null, 'wifien5g');
				if ( is_object($cmd) ) {
					if ( $cmd->getDisplay('generic_type') == "" ) {
						$cmd->setDisplay('generic_type','GENERIC_INFO');
						$cmd->save();
					}
				}
				$cmd = $this->getCmd(null, 'wifissid');
				if ( is_object($cmd) ) {
					if ( $cmd->getDisplay('generic_type') == "" ) {
						$cmd->setDisplay('generic_type','GENERIC_INFO');
						$cmd->save();
					}
				}
				$cmd = $this->getCmd(null, 'wifissid5g');
				if ( is_object($cmd) ) {
					if ( $cmd->getDisplay('generic_type') == "" ) {
						$cmd->setDisplay('generic_type','GENERIC_INFO');
						$cmd->save();
					}
				}
				$cmd = $this->getCmd(null, 'upspeed');
				if ( is_object($cmd) ) {
					if ( $cmd->getDisplay('generic_type') == "" ) {
						$cmd->setDisplay('generic_type','GENERIC_INFO');
						$cmd->save();
					}
				}
				$cmd = $this->getCmd(null, 'downspeed');
				if ( is_object($cmd) ) {
					if ( $cmd->getDisplay('generic_type') == "" ) {
						$cmd->setDisplay('generic_type','GENERIC_INFO');
						$cmd->save();
					}
				}
				$cmd = $this->getCmd(null, 'wantime');
				if ( is_object($cmd) ) {
					if ( $cmd->getDisplay('generic_type') == "" ) {
						$cmd->setDisplay('generic_type','GENERIC_INFO');
						$cmd->save();
					}
				}
				if ( $this->getIsEnable() ) {
					$info = $this->cookieurl('goform/getStatus?random=0.46529553086082265&modules=internetStatus%2CdeviceStatistics%2CsystemInfo%2CwanAdvCfg%2CwifiRelay%2CwifiBasicCfg%2CsysTime');
					if (stripos($info, 'internetStatus') !== FALSE) {
						log::add('tendaac','debug','Routeur présent');
					}
					else {
						log::add('tendaac','debug','/!\ Routeur non présent');
					}
					if ( $info === false )
						throw new Exception(__('Le routeur Tenda ne repond pas ou le compte est incorrect.',__FILE__));
				}
			}
		}

		public function cookieurl($parseurl) {
			$authurl = $this->getUrl(). 'login/Auth';
			$parseurl = $this->getUrl(). $parseurl;
			if ( $this->getConfiguration('password') == "" ) {
				log::add('tendaac','debug','Pas de mot de passe => parse html');
				$html = @file_get_contents($parseurl);
			}
			else {
				log::add('tendaac','debug','Mot de passe entré => parse curl');
				$password = $this->getConfiguration('password');
				$password = base64_encode($password);
				$postinfo = "password=".$password;
				$temp_dir = jeedom::getTmpFolder('tendaac');
				$cookie_file_path = $temp_dir.'/'."cookie.txt";
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_NOBODY, false);
				curl_setopt($ch, CURLOPT_URL, $authurl);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postinfo);
				curl_exec($ch);
			if (stripos($parseurl, 'DownloadCfg/RouterCfm.cfg') !== FALSE ) {
				log::add('tendaac','debug','CURL fichier de config');
				curl_setopt($ch, CURLOPT_URL, $parseurl);
				$html = curl_exec($ch);
				$formatdate = date("Ymd")."-".date("His");
				file_put_contents(dirname(__FILE__) . '/../../data/backup/RouterCfm-'.$formatdate.'.cfg', $html);
				if (file_exists(dirname(__FILE__) . '/../../data/backup/RouterCfm-'.$formatdate.'.cfg')) {
					log::add('tendaac','debug','Fichier de config créé : RouterCfm-'.$formatdate.'.cfg');
				} else {
					log::add('tendaac','debug','/!\ Fichier non créé');
				}
			} else if (stripos($parseurl, 'goform/getStatus') !== FALSE) {
				log::add('tendaac','debug','CURL getStatus');
				curl_setopt($ch, CURLOPT_URL, $parseurl);
				$html = curl_exec($ch);
				sleep(3); //valeurs nulles sans tempo
              	$html = curl_exec($ch);
				curl_close($ch);
			} else if (stripos($parseurl, 'goform/getQos') !== FALSE) {
				log::add('tendaac','debug','CURL getQos');
				curl_setopt($ch, CURLOPT_URL, $parseurl);
				$html = curl_exec($ch);
              	log::add('tendaac','debug','TEST onlineList CURL = '. strlen($html));
				if (strlen($html) < 20) {

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_NOBODY, false);
				curl_setopt($ch, CURLOPT_URL, $authurl);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postinfo);
				curl_exec($ch);


                  curl_setopt($ch, CURLOPT_URL, $parseurl);
				$html = curl_exec($ch);
              	log::add('tendaac','debug','TEST onlineList CURL cookie = '. strlen($html));
                }
				curl_close($ch);
			} else {
				log::add('tendaac','debug','CURL autre');
				curl_setopt($ch, CURLOPT_URL, $parseurl);
				$html = curl_exec($ch);
				curl_close($ch);
			}
			log::add('tendaac','debug','RESULTAT CURL = '. $html);
			return $html;
			}
		}
		public function preSave()
		{
			//$this->setIsVisible(0);
		}
		public function postSave()
		{
			if ( $this->getIsEnable() && $this->getConfiguration('type','') == 'box') {
				$status = $this->getCmd(null, 'status');
				if ( ! is_object($status) ) {
						$status = new tendaacCmd();
						$status->setName('Etat');
						$status->setEqLogic_id($this->getId());
						$status->setType('info');
						$status->setSubType('binary');
						$status->setLogicalId('status');
						$status->setIsVisible(1);
						$status->setEventOnly(1);
						$status->save();
				}
				$reboot = $this->getCmd(null, 'reboot');
				 if ( ! is_object($reboot) ) {
						$reboot = new tendaacCmd();
						$reboot->setName('Reboot');
						$reboot->setEqLogic_id($this->getId());
						$reboot->setType('action');
						$reboot->setSubType('other');
						$reboot->setLogicalId('reboot');
						$reboot->setEventOnly(1);
						$reboot->setIsVisible(1);
						$reboot->setOrder(1);
						$reboot->setDisplay('generic_type','GENERIC_ACTION');
						$reboot->save();
				}
				$backup = $this->getCmd(null, 'backup');
				 if ( ! is_object($backup) ) {
						$backup = new tendaacCmd();
						$backup->setName('Backup');
						$backup->setEqLogic_id($this->getId());
						$backup->setType('action');
						$backup->setSubType('other');
						$backup->setLogicalId('backup');
						$backup->setEventOnly(1);
						$backup->setIsVisible(1);
						$backup->setOrder(2);
						$backup->setDisplay('generic_type','GENERIC_ACTION');
						$backup->save();
				}
				$wifistatus = $this->getCmd(null, 'wifistatus');
				if ( ! is_object($wifistatus)) {
						$wifistatus = new tendaacCmd();
						$wifistatus->setName('Etat Wifi');
						$wifistatus->setEqLogic_id($this->getId());
						$wifistatus->setLogicalId('wifistatus');
						$wifistatus->setUnite('');
						$wifistatus->setType('info');
						$wifistatus->setSubType('binary');
						$wifistatus->setIsHistorized(0);
						$wifistatus->setEventOnly(1);
						$wifistatus->setDisplay('generic_type','GENERIC_INFO');
						$wifistatus->save();
				}
				$routername = $this->getCmd(null, 'routername');
				if ( ! is_object($routername)) {
						$routername = new tendaacCmd();
						$routername->setName('Nom du routeur');
						$routername->setEqLogic_id($this->getId());
						$routername->setLogicalId('routername');
						$routername->setUnite('');
						$routername->setType('info');
						$routername->setSubType('string');
						$routername->setIsHistorized(0);
						$routername->setEventOnly(1);
						$routername->setDisplay('generic_type','GENERIC_INFO');
						$routername->save();
				}
				$softversion = $this->getCmd(null, 'softversion');
				if ( ! is_object($softversion)) {
						$softversion = new tendaacCmd();
						$softversion->setName('Version de firmware');
						$softversion->setEqLogic_id($this->getId());
						$softversion->setLogicalId('softversion');
						$softversion->setUnite('');
						$softversion->setType('info');
						$softversion->setSubType('string');
						$softversion->setIsHistorized(0);
						$softversion->setEventOnly(1);
						$softversion->setDisplay('generic_type','GENERIC_INFO');
						$softversion->save();
				}
				$wifien = $this->getCmd(null, 'wifien');
				if ( ! is_object($wifien)) {
						$wifien = new tendaacCmd();
						$wifien->setName('WiFi 2.4G');
						$wifien->setEqLogic_id($this->getId());
						$wifien->setLogicalId('wifien');
						$wifien->setUnite('');
						$wifien->setType('info');
						$wifien->setSubType('binary');
						$wifien->setIsHistorized(0);
						$wifien->setEventOnly(1);
						$wifien->setDisplay('generic_type','GENERIC_INFO');
						$wifien->save();
				}
				$wifien5g = $this->getCmd(null, 'wifien5g');
				if ( ! is_object($wifien5g)) {
						$wifien5g = new tendaacCmd();
						$wifien5g->setName('WiFi 5G');
						$wifien5g->setEqLogic_id($this->getId());
						$wifien5g->setLogicalId('wifien5g');
						$wifien5g->setUnite('');
						$wifien5g->setType('info');
						$wifien5g->setSubType('binary');
						$wifien5g->setIsHistorized(0);
						$wifien5g->setEventOnly(1);
						$wifien5g->setDisplay('generic_type','GENERIC_INFO');
						$wifien5g->save();
				}
				$wifissid = $this->getCmd(null, 'wifissid');
				if ( ! is_object($wifissid)) {
						$wifissid = new tendaacCmd();
						$wifissid->setName('SSID WiFi 2.4G');
						$wifissid->setEqLogic_id($this->getId());
						$wifissid->setLogicalId('wifissid');
						$wifissid->setUnite('');
						$wifissid->setType('info');
						$wifissid->setSubType('string');
						$wifissid->setIsHistorized(0);
						$wifissid->setEventOnly(1);
						$wifissid->setDisplay('generic_type','GENERIC_INFO');
						$wifissid->save();
				}
				$wifissid5g = $this->getCmd(null, 'wifissid5g');
				if ( ! is_object($wifissid5g)) {
						$wifissid5g = new tendaacCmd();
						$wifissid5g->setName('SSID WiFi 5G');
						$wifissid5g->setEqLogic_id($this->getId());
						$wifissid5g->setLogicalId('wifissid5g');
						$wifissid5g->setUnite('');
						$wifissid5g->setType('info');
						$wifissid5g->setSubType('string');
						$wifissid5g->setIsHistorized(0);
						$wifissid5g->setEventOnly(1);
						$wifissid5g->setDisplay('generic_type','GENERIC_INFO');
						$wifissid5g->save();
				}
				$connectedlist = $this->getCmd(null, 'connectedlist');
				if ( ! is_object($connectedlist)) {
						$connectedlist = new tendaacCmd();
						$connectedlist->setName('Liste des hôtes connectés');
						$connectedlist->setEqLogic_id($this->getId());
						$connectedlist->setLogicalId('connectedlist');
						$connectedlist->setUnite('');
						$connectedlist->setType('info');
						$connectedlist->setSubType('string');
						$connectedlist->setDisplay('generic_type','GENERIC_INFO');
						$connectedlist->setIsHistorized(0);
						$connectedlist->save();
				}
				$upspeed = $this->getCmd(null, 'upspeed');
				if ( ! is_object($upspeed)) {
						$upspeed = new tendaacCmd();
						$upspeed->setName('Vitesse d\'envoi');
						$upspeed->setEqLogic_id($this->getId());
						$upspeed->setLogicalId('upspeed');
						$upspeed->setUnite('MB/s');
						$upspeed->setType('info');
						$upspeed->setSubType('numeric');
						$upspeed->setDisplay('generic_type','GENERIC_INFO');
						$upspeed->setIsHistorized(0);
						$upspeed->save();
				}
				$downspeed = $this->getCmd(null, 'downspeed');
				if ( ! is_object($downspeed)) {
						$downspeed = new tendaacCmd();
						$downspeed->setName('Vitesse de réception');
						$downspeed->setEqLogic_id($this->getId());
						$downspeed->setLogicalId('downspeed');
						$downspeed->setUnite('MB/s');
						$downspeed->setType('info');
						$downspeed->setSubType('numeric');
						$downspeed->setDisplay('generic_type','GENERIC_INFO');
						$downspeed->setIsHistorized(0);
						$downspeed->save();
				}
				$wantime = $this->getCmd(null, 'wantime');
				if ( ! is_object($wantime)) {
						$wantime = new tendaacCmd();
						$wantime->setName('Temps de connexion WAN');
						$wantime->setEqLogic_id($this->getId());
						$wantime->setLogicalId('wantime');
						$wantime->setUnite('');
						$wantime->setType('info');
						$wantime->setSubType('string');
						$wantime->setDisplay('generic_type','GENERIC_INFO');
						$wantime->setIsHistorized(0);
						$wantime->save();
				}
			} else if ($this->getConfiguration('type','') == 'cli') {
				$cmd = $this->getCmd(null, 'lastlogin');
				if ( ! is_object($cmd)) {
					$cmd = new tendaacCmd();
					$cmd->setName(__('Durée de connexion', __FILE__));
					$cmd->setEqLogic_id($this->getId());
					$cmd->setLogicalId('lastlogin');
					$cmd->setType('info');
					$cmd->setSubType('string');
					$cmd->setGeneric_type( 'GENERIC_INFO');
					$cmd->setIsVisible(0);
					$cmd->setIsHistorized(0);
					$cmd->save();
				}
				$cmd = $this->getCmd(null, 'present');
				if ( ! is_object($cmd)) {
					$cmd = new tendaacCmd();
					$cmd->setName(__('Présent', __FILE__));
					$cmd->setEqLogic_id($this->getId());
					$cmd->setLogicalId('present');
					$cmd->setType('info');
					$cmd->setGeneric_type( 'GENERIC_INFO');
					$cmd->setSubType('binary');
					$cmd->setIsVisible(1);
					$cmd->setIsHistorized(1);
					$cmd->save();
				}
				$cmd = $this->getCmd(null, 'ip');
				if ( ! is_object($cmd)) {
					$cmd = new tendaacCmd();
					$cmd->setName(__('Adresse IP', __FILE__));
					$cmd->setEqLogic_id($this->getId());
					$cmd->setLogicalId('ip');
					$cmd->setType('info');
					$cmd->setSubType('string');
					$cmd->setGeneric_type( 'GENERIC_INFO');
					$cmd->setIsVisible(1);
					$cmd->setIsHistorized(0);
					$cmd->save();
				}
				$cmd = $this->getCmd(null, 'macaddress');
				if ( ! is_object($cmd)) {
					$cmd = new tendaacCmd();
					$cmd->setName(__('Adresse Mac', __FILE__));
					$cmd->setEqLogic_id($this->getId());
					$cmd->setLogicalId('macaddress');
					$cmd->setType('info');
					$cmd->setSubType('string');
					$cmd->setGeneric_type( 'GENERIC_INFO');
					$cmd->setIsVisible(1);
					$cmd->setIsHistorized(0);
					$cmd->save();
				}
				$cmd = $this->getCmd(null, 'access');
				if ( ! is_object($cmd)) {
					$cmd = new tendaacCmd();
					$cmd->setName(__('Accès Internet', __FILE__));
					$cmd->setEqLogic_id($this->getId());
					$cmd->setLogicalId('access');
					$cmd->setType('info');
					$cmd->setSubType('string');
					$cmd->setGeneric_type( 'SWITCH_STATE');
					$cmd->setIsVisible(1);
					$cmd->setIsHistorized(1);
					$cmd->save();
				}
				$cmd = $this->getCmd(null, 'downspeed');
				if ( ! is_object($cmd)) {
					$cmd = new tendaacCmd();
					$cmd->setName(__('Vitesse de réception', __FILE__));
					$cmd->setEqLogic_id($this->getId());
					$cmd->setLogicalId('downspeed');
					$cmd->setType('info');
					$cmd->setSubType('numeric');
					$cmd->setUnite('MB/s');
					$cmd->setGeneric_type( 'SWITCH_STATE');
					$cmd->setIsVisible(1);
					$cmd->setIsHistorized(1);
					$cmd->save();
				}
				$cmd = $this->getCmd(null, 'upspeed');
				if ( ! is_object($cmd)) {
					$cmd = new tendaacCmd();
					$cmd->setName(__('Vitesse d\'envoi', __FILE__));
					$cmd->setEqLogic_id($this->getId());
					$cmd->setLogicalId('upspeed');
					$cmd->setType('info');
					$cmd->setSubType('numeric');
					$cmd->setUnite('MB/s');
					$cmd->setGeneric_type( 'SWITCH_STATE');
					$cmd->setIsVisible(1);
					$cmd->setIsHistorized(1);
					$cmd->save();
				}
				$cmd = $this->getCmd(null, 'manufacturer');
				if ( ! is_object($cmd)) {
					$cmd = new tendaacCmd();
					$cmd->setName(__('Marque', __FILE__));
					$cmd->setEqLogic_id($this->getId());
					$cmd->setLogicalId('manufacturer');
					$cmd->setType('info');
					$cmd->setSubType('string');
					$cmd->setGeneric_type( 'SWITCH_STATE');
					$cmd->setIsVisible(1);
					$cmd->setIsHistorized(1);
					$cmd->save();
				}
            }
		}
  
  	public function preRemove() {
		if ($this->getConfiguration('type') == "box") { // Si c'est un type box il faut supprimer ses clients
			$eqLogics = eqLogic::byType('tendaac');
			foreach ($eqLogics as $eqLogic) {
				if($eqLogic->getConfiguration('type') == 'cli' && $eqLogic->getConfiguration('boxId') == $this->getId()){
					$eqLogic->remove();
				}
			}
		}
	}

	public function getImage() {
		if($this->getConfiguration('type') == 'cli'){
			$filename = 'plugins/tendaac/core/config/' . $this->getConfiguration('deviceType') .'.png';
			if(file_exists(__DIR__.'/../../../../'.$filename)){
				return $filename;
			}
		}
		return 'plugins/tendaac/plugin_info/tendaac_icon.png';
	}

  	public static function nameExists($name) {
			$allTenda=eqLogic::byType('tendaac');
			foreach($allTenda as $u) {
				if($name == $u->getName()) return true;
			}
			return false;
	}
  	public static function createClient($client, $boxId) {
		$eqLogicClient = new tendaac();
		$mac = $client['Key'];
		$defaultRoom = intval(config::byKey('defaultParentObject','tendaac','',true));
		$name= (isset($client["Name"]) && $client["Name"]) ? $client["Name"] : $mac;
		if(self::nameExists($name)) {
			log::add('tendaac', 'debug', "Nom en double ".$name." renommé en ".$name.'_'.$mac);
			$name = $name.'_'.$mac;
		}
		log::add('tendaac', 'info', "Trouvé Client ".$name."(".$mac."):".json_encode($client));

		$eqLogicClient->setName($name);
		$eqLogicClient->setIsEnable(1);
		$eqLogicClient->setIsVisible(0);
		$eqLogicClient->setLogicalId($mac);
		$eqLogicClient->setEqType_name('tendaac');
		if($defaultRoom) $eqLogicClient->setObject_id($defaultRoom);
		$eqLogicClient->setConfiguration('type', 'cli');
		$eqLogicClient->setConfiguration('boxId', $boxId);
		$eqLogicClient->setConfiguration('macAddress',$mac);
		$eqLogicClient->setConfiguration('deviceType',$client['deviceType']);
		$eqLogicClient->setConfiguration('image',$eqLogicClient->getImage());
		$eqLogicClient->save();
	}
  
		public function checkRemoveFile($url) {
			if (file_exists(dirname(__FILE__) . '/../../data/backup/'.$url)) {
				unlink( dirname(__FILE__) . '/../../data/backup/'.$url );
				log::add('tendaac','debug','Fichier de config créé : '.$url);
				return 1;
			} else {
				log::add('tendaac','debug','/!\ Fichier de config inexistant : '.$url);
				return;
			}
		}
  
	function refreshClientInfo($client, $lbcli) {
		$clicmd = $lbcli->getCmd(null, 'lastlogin');
		if (is_object($clicmd) && isset($client["lastlogin"]) && $client["lastlogin"] !== '') {
			$lbcli->checkAndUpdateCmd('lastlogin', $client['lastlogin']);
		}
		$clicmd = $lbcli->getCmd(null, 'downspeed');
		if (is_object($clicmd) && isset($client['DownSpeed']) && $client['DownSpeed'] !== '') {
			$lbcli->checkAndUpdateCmd('downspeed', $client['DownSpeed']);
		}
		$clicmd = $lbcli->getCmd(null, 'upspeed');
		if (is_object($clicmd) && isset($client["UpSpeed"]) && $client["UpSpeed"] !== '') {
			$lbcli->checkAndUpdateCmd('upspeed', $client['UpSpeed']);
		}
		$clicmd = $lbcli->getCmd(null, 'ip');
		if (is_object($clicmd) && isset($client["IPAddress"])) {
			$lbcli->checkAndUpdateCmd('ip', $client['IPAddress']);
		}
		$clicmd = $lbcli->getCmd(null, 'macaddress');
		if (is_object($clicmd) && isset($client['Key'])) {
			$lbcli->checkAndUpdateCmd('macaddress', $client['Key']);
		}
		$clicmd = $lbcli->getCmd(null, 'access');
		if (is_object($clicmd) && isset($client['AccessType'])) {
			$lbcli->checkAndUpdateCmd('access', $client['AccessType']);
		}
		$clicmd = $lbcli->getCmd(null, 'manufacturer');
		if (is_object($clicmd) && isset($client['deviceType'])) {
			$lbcli->checkAndUpdateCmd('manufacturer', $client['deviceType']);
		}
	}
		public function event() {
			foreach (eqLogic::byType('tendaac') as $eqLogic) {
				if ( $eqLogic->getId() == init('id') ) {
					$eqLogic->scan();
					log::add('tendaac','debug','Scan lancé');
				}
			}
		}
		public function scan() {
			if ( $this->getIsEnable() ) {
				$statuscmd = $this->getCmd(null, 'status');
				$url = $this->getUrl();
				$info = $this->cookieurl('goform/getStatus?random=0.46529553086082265&modules=internetStatus%2CdeviceStatistics%2CsystemInfo%2CwanAdvCfg%2CwifiRelay%2CwifiBasicCfg%2CsysTime');
				$connected = $this->cookieurl('goform/getQos?random=0.46529553086082265&modules=onlineList');
				$wifi = $this->cookieurl('goform/getWifi?random=0.46529553086082265&modules=wifiTime%2CwifiPower');

				$tableau = json_decode($test, true);

				if ( $info === false ) {
					throw new Exception(__('Le routeur Tenda ne repond pas.',__FILE__));
					if ($statuscmd->execCmd() != 0) {
						$statuscmd->setCollectDate('');
						$statuscmd->event(0);
					}
				}
				if ($statuscmd->execCmd() != 1) {
					$statuscmd->setCollectDate('');
					$statuscmd->event(1);
				}
				$arr = json_decode($info, true);

				$routername = $this->getCmd(null, 'routername');
				$routername->setCollectDate('');
				$routername->event($arr["deviceStastics"]["routerName"]);
				log::add('tendaac','debug','Valeur extraite de $routername : '. $arr["deviceStastics"]["routerName"]);

				$softversion = $this->getCmd(null, 'softversion');
				$softversion->setCollectDate('');
				$softversion->event($arr["systemInfo"]["softVersion"]);
				log::add('tendaac','debug','Valeur extraite de $softversion : '. $arr["systemInfo"]["softVersion"]);

				$wifien = $this->getCmd(null, 'wifien');
				if ( $arr["wifiBasicCfg"]["wifiEn"] == "true" ) {
					$wifien->setCollectDate('');
					$wifien->event(1);
				} else {
					$wifien->setCollectDate('');
					$wifien->event(0);
				}
				log::add('tendaac','debug','Valeur extraite de $wifien : '. $arr["wifiBasicCfg"]["wifiEn"]);

				$wifien5g = $this->getCmd(null, 'wifien5g');
				if ( $arr["wifiBasicCfg"]["wifiEn_5G"] == "true" ) {
					$wifien5g->setCollectDate('');
					$wifien5g->event(1);
				} else {
					$wifien5g->setCollectDate('');
					$wifien5g->event(0);
				}
				log::add('tendaac','debug','Valeur extraite de $wifien5g : '. $arr["wifiBasicCfg"]["wifiEn_5G"]);

				$wifissid = $this->getCmd(null, 'wifissid');
				$wifissid->setCollectDate('');
				$wifissid->event($arr["wifiBasicCfg"]["wifiSSID"]);
				log::add('tendaac','debug','Valeur extraite de $wifissid : '. $arr["wifiBasicCfg"]["wifiSSID"]);

				$wifissid5g = $this->getCmd(null, 'wifissid5g');
				$wifissid5g->setCollectDate('');
				$wifissid5g->event($arr["wifiBasicCfg"]["wifiSSID_5G"]);
				log::add('tendaac','debug','Valeur extraite de $wifissid5g : '. $arr["wifiBasicCfg"]["wifiSSID_5G"]);

				$wifistatus = $this->getCmd(null, 'wifistatus');
				if ($arr["wifiBasicCfg"]["wifiEn"] == 'true' || $arr["wifiBasicCfg"]["wifiEn_5G"] == 'true') {
					$wifistatus->setCollectDate('');
					$wifistatus->event(1);
                } else {
					$wifistatus->setCollectDate('');
					$wifistatus->event(0);
				}

				$downspeed = $this->getCmd(null, 'downspeed');
				$arr["deviceStastics"]["statusDownSpeed"] = round($arr["deviceStastics"]["statusDownSpeed"]/1024,2);
				$downspeed->setCollectDate('');
				$downspeed->event($arr["deviceStastics"]["statusDownSpeed"]);
				log::add('tendaac','debug','Valeur extraite de $downspeed : '. $arr["deviceStastics"]["statusDownSpeed"]);

				$upspeed = $this->getCmd(null, 'upspeed');
				$arr["deviceStastics"]["statusUpSpeed"] = round($arr["deviceStastics"]["statusUpSpeed"]/1024,2);
				$upspeed->setCollectDate('');
				$upspeed->event($arr["deviceStastics"]["statusUpSpeed"]);
				log::add('tendaac','debug','Valeur extraite de $upspeed : '. $arr["deviceStastics"]["statusUpSpeed"]);

			function transforme($time) {
				if ($time>=86400) {
					$jour = floor($time/86400);
					$reste = $time%86400;
					$heure = floor($reste/3600);
					$reste = $reste%3600;
					$minute = floor($reste/60);
					$seconde = $reste%60;
					$result = $jour.'j '.$heure.'h '.$minute.'min '.$seconde.'s';
				}
				elseif ($time < 86400 AND $time>=3600) {
					$heure = floor($time/3600);
					$reste = $time%3600;
					$minute = floor($reste/60);
					$seconde = $reste%60;
					$result = $heure.'h '.$minute.'min '.$seconde.' s';
				}
				elseif ($time<3600 AND $time>=60) {
					$minute = floor($time/60);
					$seconde = $time%60;
					$result = $minute.'min '.$seconde.'s';
				}
				elseif ($time < 60) {
					$result = $time.'s';
				}
				return $result;
			}

				$wantime = $this->getCmd(null, 'wantime');
				if ($arr["systemInfo"]["wanConnectTime"] != '') {
					$formatwantime = transforme($arr["systemInfo"]["wanConnectTime"]);
					$wantime->setCollectDate('');
					$wantime->event($formatwantime);
                }
				log::add('tendaac','debug','Valeur extraite de $wantime : '. $arr["systemInfo"]["wanConnectTime"]);

              	$arr = json_decode($connected, true);
				$tabstyle = "<style> th, td { padding : 2px !important; color: #C7C6C6; } </style><style> th { text-align:center; } </style><style> td { text-align:left; } </style>";
				$ConnectedListTable =	 "$tabstyle<table border=1>";
				$ConnectedListTable .=  "<tr><th>Nom d'hôte</th><th>@IP</th><th>Connectivité</th><th>Download</th><th>Upload</th><th>Durée</th></tr>";

				$Hostname = array();
				$activeclients = array();
				for($i = 0;$i < count($arr["onlineList"]); $i++){
					$Hostname[$i] = $arr["onlineList"][$i]["qosListHostname"]; //Unknown
					$Remark[$i] = $arr["onlineList"][$i]["qosListRemark"]; //ESP Ballon
					if ($Hostname[$i] == 'Unknown' && (!empty($Remark[$i])))	{
						$Hostname[$i] = $Remark[$i];
					}
					$IPAddress[$i] = $arr["onlineList"][$i]["qosListIP"];  //192.168.0.31
					$ConnectType[$i] = $arr["onlineList"][$i]["qosListConnectType"]; //wifi ou ou wires
					if (!isset($arr["onlineList"][$i]["qosListAccessType"])) {
						$AccessType[$i] = ' ';
					} else {
						$AccessType[$i] = $arr["onlineList"][$i]["qosListAccessType"]; //wifi_2G ou wifi_5G
					}
					if ($ConnectType[$i] == 'wifi') {
						$ConnectType[$i] = 'WiFi';
						$AccessType[$i] = $arr["onlineList"][$i]["qosListAccessType"]; //wifi_2G ou wifi_5G
						if ($AccessType[$i] == 'wifi_2G') {
							$AccessType[$i] = '2.4 GHz';
						} else if ($AccessType[$i] == 'wifi_5G') {
							$AccessType[$i] = '5 GHz';
						} else {
							$AccessType[$i] = '1';
						}
					} else if ($ConnectType[$i] == 'wires') {
							$ConnectType[$i] = 'Ethernet';
					} else {
						$ConnectType[$i] = '';
					}
					$MACAddress[$i] = $arr["onlineList"][$i]["qosListMac"]; //c8:d8:54:6f:aa:ef
					$DownSpeed[$i] = $arr["onlineList"][$i]["qosListDownSpeed"]; //1540.00
					$Present[$i] = $arr["onlineList"][$i]["qosListAccess"];
					$Type[$i] = $arr["onlineList"][$i]["qosListManufacturer"];
					$SimpleDownSpeed[$i] = round($DownSpeed[$i]/1024,2);
					if ($DownSpeed[$i] > 1024) {
						$DownSpeed[$i] = round($DownSpeed[$i]/1024,2).' MB/s';
					} else {
						$DownSpeed[$i] = $DownSpeed[$i].' KB/s';
					}
					$UpSpeed[$i] = $arr["onlineList"][$i]["qosListUpSpeed"]; //351.00
					$SimpleUpSpeed[$i] = round($UpSpeed[$i]/1024,2);
					if ($UpSpeed[$i] > 1024) {
						$UpSpeed[$i] = round($UpSpeed[$i]/1024,2).' MB/s';
					} else {
						$UpSpeed[$i] = $UpSpeed[$i].' KB/s';
					}
					$Timest[$i] = $arr["onlineList"][$i]["qoslistConnetTime"]; //8085
					$Timest[$i] = transforme($Timest[$i]);

					$ConnectedListTable .=  "<tr><td>".$Hostname[$i]."</td><td>".$IPAddress[$i]."</td><td>".$ConnectType[$i]." ".$AccessType[$i]."</td><td>".$DownSpeed[$i]."</td><td>".$UpSpeed[$i]."</td><td>".$Timest[$i]."</td></tr>";

					$client['Name'] = $Hostname[$i];
					$client['Key'] = $MACAddress[$i];
					$client['IPAddress'] = $IPAddress[$i];
					$client['AccessType'] = $ConnectType[$i];
					$client['lastlogin'] = $Timest[$i];
					$client['DownSpeed'] = $SimpleDownSpeed[$i];
					$client['UpSpeed'] = $SimpleUpSpeed[$i];
					$client['deviceType'] = $Type[$i];
					$activeclients[$MACAddress[$i]] = $Present[$i];

					$lbcli = tendaac::byLogicalId($MACAddress[$i], 'tendaac');
					if (!is_object($lbcli) && config::byKey('createClients','tendaac',0)) {
						tendaac::createClient($client, $lbcli);
						$lbcli = tendaac::byLogicalId($MACAddress[$i], 'tendaac');
					}
					if (is_object($lbcli) && $lbcli->getConfiguration('type','') == 'cli') {
						if ($lbcli->getIsEnable()){
							 $this->refreshClientInfo($client, $lbcli);
						 }
					}
				}

				foreach (self::byType('tendaac') as $eqLogicClient) {
					if ($eqLogicClient->getConfiguration('type')=='cli') {
						$clicmd = $eqLogicClient->getCmd(null, 'present');
						if (is_object($clicmd)) {
							if (isset($activeclients[$eqLogicClient->getLogicalId()]) && $activeclients[$eqLogicClient->getLogicalId()] == true) {
								log::add('tendaac','debug','Le client '.$eqLogicClient->getHumanName() . ' est actif');
								$eqLogicClient->checkAndUpdateCmd('present', true);
							} else {
								log::add('tendaac','debug','Le client '.$eqLogicClient->getHumanName() . ' est inactif');
								$eqLogicClient->checkAndUpdateCmd('present', false);
							}
						}
					}
				}

				$ConnectedListTable .=  "</table>";
				$this->checkAndUpdateCmd('connectedlist', $ConnectedListTable);
			}
		}
}
class tendaacCmd extends cmd
{
		public function execute($_options = null) {
			$eqLogic = $this->getEqLogic();
			if (!is_object($eqLogic) || $eqLogic->getIsEnable() != 1) {
				throw new Exception(__('Equipement desactivé impossible d\'éxecuter la commande : ' . $this->getHumanName(), __FILE__));
			}
			$url = $eqLogic->getUrl();
			if ( $this->getLogicalId() == 'backup' ) {
				$info = $eqLogic->cookieurl('cgi-bin/DownloadCfg/RouterCfm.cfg?random=0.46529553086082265');
				log::add('tendaac','debug','Backup config');
			}
			else if ( $this->getLogicalId() == 'reboot' ) {
				$url .= "goform/sysReboot?module1=sysOperate&action=reboot";
				$result = @file_get_contents($url);
				log::add('tendaac','debug','Reboot routeur lancé');
			}
			else
			return false;

			if ( $result === false ) {
				return false;
			}
			$eqLogic->scan();
			return false;
		}
}
