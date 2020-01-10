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
    /*     * *************************Attributs****************************** */
    /*     * ***********************Methode static*************************** */
    public static function pull() {
        log::add('tendaac','debug','cron auto start');
        foreach (self::byType('tendaac') as $eqLogic) {
            $eqLogic->scan();
        }
        log::add('tendaac','debug','cron auto stop');
    }
    public static function cron() {
  		foreach(eqLogic::byType('tendaac') as $eqTendaac){
  			if($eqTendaac->getIsEnable()){
				if ($eqTendaac->getConfiguration('RepeatCmd') == "cron") {
            		$eqTendaac->scan();
        			log::add('tendaac','debug','cron manuel');
            	}
         	}
  		}
  	}
    public static function cron5() {
  		foreach(eqLogic::byType('tendaac') as $eqTendaac){
  			if($eqTendaac->getIsEnable()){
              	if ($eqTendaac->getConfiguration('RepeatCmd') == "cron5") {
            		$eqTendaac->scan();
        			log::add('tendaac','debug','cron5 manuel');
            	}
         	}
  		}
  	}
    public static function cron10() {
  		foreach(eqLogic::byType('tendaac') as $eqTendaac){
  			if($eqTendaac->getIsEnable()){
              	if ($eqTendaac->getConfiguration('RepeatCmd') == "cron10") {
            		$eqTendaac->scan();
        			log::add('tendaac','debug','cron10 manuel');
            	}
         	}
  		}
  	}
    public static function cron15() {
  		foreach(eqLogic::byType('tendaac') as $eqTendaac){
  			if($eqTendaac->getIsEnable()){
              	if ($eqTendaac->getConfiguration('RepeatCmd') == "cron15") {
            		$eqTendaac->scan();
        			log::add('tendaac','debug','cron15 manuel');
            	}
         	}
  		}
  	}
    public static function cron30() {
  		foreach(eqLogic::byType('tendaac') as $eqTendaac){
  			if($eqTendaac->getIsEnable()){
              	if ($eqTendaac->getConfiguration('RepeatCmd') == "cron30") {
            		$eqTendaac->scan();
        			log::add('tendaac','debug','cron30 manuel ');
            	}
         	}
  		}
  	}
    public static function cronHourly() {
  		foreach(eqLogic::byType('tendaac') as $eqTendaac){
  			if($eqTendaac->getIsEnable()){
              	if ($eqTendaac->getConfiguration('RepeatCmd') == "cronHourly") {
            		$eqTendaac->scan();
        			log::add('tendaac','debug','cronHourly manuel');
            	}
         	}
  		}
  	}
    public function getUrl() {
        $url = 'http://';
        $url .= $this->getConfiguration('ip');
        return $url."/";
    }
    public function preUpdate() {
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
        if ( $this->getIsEnable() ) {
          $info = $this->cookieurl('goform/getStatus?random=0.46529553086082265&modules=internetStatus%2CdeviceStatistics%2CsystemInfo%2CwanAdvCfg%2CwifiRelay%2CwifiBasicCfg%2CsysTime');
          if (stripos($info, 'internetStatus') !== FALSE) {
            log::add('tendaac','debug','Routeur présent');
          }
          else {
            log::add('tendaac','debug','Routeur non présent');
          }
          if ( $info === false )
          throw new Exception(__('Le routeur Tenda ne repond pas ou le compte est incorrect.',__FILE__));
        }
    }
    public function cookieurl($parseurl) {
      $authurl = $this->getUrl(). 'login/Auth';
      $parseurl = $this->getUrl(). $parseurl;
      log::add('tendaac','debug','ParseURL = '.$parseurl);
      if ( $this->getConfiguration('password') == "" ) {
        $html = @file_get_contents($parseurl);
        log::add('tendaac','debug','Reponse du routeur OK');
      }
      else {
        $password = $this->getConfiguration('password');
  			$password = base64_encode($password);
  			$postinfo = "password=".$password;
  			$cookie_file_path = "cookie.txt";
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
				curl_setopt($ch, CURLOPT_URL, $parseurl);
				$html = curl_exec($ch);
				$formatdate = date("Ymd")."-".date("His");
              	file_put_contents('/var/www/html/plugins/tendaac/data/backup/RouterCfm-'.$formatdate.'.cfg', $html);

				if (file_exists('/var/www/html/plugins/tendaac/data/backup/RouterCfm-'.$formatdate.'.cfg')) {
				log::add('tendaac','debug','Fichier de config créé : RouterCfm-'.$formatdate.'.cfg');
				} else {
				log::add('tendaac','debug','Fichier non créé');
				}
            }
        	else {
			curl_setopt($ch, CURLOPT_URL, $parseurl);

  			$html = curl_exec($ch);
  			curl_close($ch);
              }
  			if (stripos($html, 'internetStatus') !== FALSE ) {
				log::add('tendaac','debug','Cookie OK');
			}
			else {
				log::add('tendaac','debug','Cookie NOK');
			}
        return $html;
      }
    }
    public function preInsert()
    {
        $this->setIsVisible(0);
    }
    public function postInsert()
    {
        $cmd = $this->getCmd(null, 'status');
        if ( ! is_object($cmd) ) {
            $cmd = new tendaacCmd();
            $cmd->setName('Etat');
            $cmd->setEqLogic_id($this->getId());
            $cmd->setType('info');
            $cmd->setSubType('binary');
            $cmd->setLogicalId('status');
            $cmd->setIsVisible(1);
            $cmd->setEventOnly(1);
            $cmd->save();
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
            $reboot->setIsVisible(0);
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
            $backup->setIsVisible(0);
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
    }
    public function event() {
        foreach (eqLogic::byType('tendaac') as $eqLogic) {
            if ( $eqLogic->getId() == init('id') ) {
                $eqLogic->scan();
            }
        }
    }
    public function scan() {
      if ( $this->getIsEnable() ) {
        $statuscmd = $this->getCmd(null, 'status');
        $url = $this->getUrl();
        $info = $this->cookieurl('goform/getStatus?random=0.46529553086082265&modules=internetStatus%2CdeviceStatistics%2CsystemInfo%2CwanAdvCfg%2CwifiRelay%2CwifiBasicCfg%2CsysTime');
        if ( $info === false ) {
          throw new Exception(__('Le routeur tenda ne repond pas.',__FILE__));
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
     // if ( $routername->execCmd() != $routername->formatValue($arr["deviceStastics"]["routerName"]) ) {
        $routername->setCollectDate('');
        $routername->event($arr["deviceStastics"]["routerName"]);
      //}

      $softversion = $this->getCmd(null, 'softversion');
      $softversion->setCollectDate('');
      $softversion->event($arr["systemInfo"]["softVersion"]);

      $wifien = $this->getCmd(null, 'wifien');
	  if ( $arr["wifiBasicCfg"]["wifiEn"] == "true" ) {
        $wifien->setCollectDate('');
        $wifien->event(1);
      } else {
      	$wifien->setCollectDate('');
        $wifien->event(0);
      }
      $wifien5g = $this->getCmd(null, 'wifien5g');
	  if ( $arr["wifiBasicCfg"]["wifiEn_5G"] == "true" ) {
        $wifien5g->setCollectDate('');
        $wifien5g->event(1);
      } else {
      	$wifien5g->setCollectDate('');
        $wifien5g->event(0);
      }
      $wifissid = $this->getCmd(null, 'wifissid');
      $wifissid->setCollectDate('');
      $wifissid->event($arr["wifiBasicCfg"]["wifiSSID"]);

      $wifissid5g = $this->getCmd(null, 'wifissid5g');
      $wifissid5g->setCollectDate('');
      $wifissid5g->event($arr["wifiBasicCfg"]["wifiSSID_5G"]);

      $wifistatus = $this->getCmd(null, 'wifistatus');
      if ( $wifistatus->execCmd() != $wifistatus->formatValue($regs[1]) ) {
        $wifistatus->setCollectDate('');
        $wifistatus->event($regs[1]);
      }
    }
  }
    /*     * **********************Getteur Setteur*************************** */
}
class tendaacCmd extends cmd
{
    /*     * *************************Attributs****************************** */
    /*     * ***********************Methode static*************************** */
    /*     * *********************Methode d'instance************************* */
    public function formatValue($_value, $_quote = false) {
        if ($this->getLogicalId() == 'wifistatus') {
            if ( $_value == O ) {
                return 0;
            } else {
                return 1;
            }
        }
        return $_value;
    }
    /*     * **********************Getteur Setteur*************************** */
    public function execute($_options = null) {
        $eqLogic = $this->getEqLogic();
        if (!is_object($eqLogic) || $eqLogic->getIsEnable() != 1) {
            throw new Exception(__('Equipement desactivé impossible d\éxecuter la commande : ' . $this->getHumanName(), __FILE__));
        }
        $url = $eqLogic->getUrl();
        if ( $this->getLogicalId() == 'backup' ) {
          $info = $eqLogic->cookieurl('cgi-bin/DownloadCfg/RouterCfm.cfg?random=0.46529553086082265');
			log::add('tendaac','debug','Backup config ');
		}
        else if ( $this->getLogicalId() == 'reboot' ) {
			$url .= "goform/sysReboot?module1=sysOperate&action=reboot";
			$result = @file_get_contents($url);
			log::add('tendaac','debug','get '.preg_replace("/:[^:]*@/", ":XXXX@", $url));
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
