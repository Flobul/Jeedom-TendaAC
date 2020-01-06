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
        log::add('tendaac','debug','cron start');
        foreach (self::byType('tendaac') as $eqLogic) {
            $eqLogic->scan();
        }
        log::add('tendaac','debug','cron stop');
    }

    public function getUrl() {
        $url = 'http://';
        if ( $this->getConfiguration('username') != '' )
        {
            $url .= $this->getConfiguration('username').':'.$this->getConfiguration('password').'@';
        }
        $url .= $this->getConfiguration('ip');
        return $url."/";
    }

    public function preUpdate()
    {
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
        else
        {
            if ( $reboot->getDisplay('generic_type') == "" )
            {
                $reboot->setDisplay('generic_type','GENERIC_ACTION');
                $reboot->save();
            }
        }
        $cmd = $this->getCmd(null, 'status');
        if ( is_object($cmd) ) {
            if ( $cmd->getDisplay('generic_type') == "" )
            {
                $cmd->setDisplay('generic_type','GENERIC_INFO');
                $cmd->save();
            }
        }
        $cmd = $this->getCmd(null, 'wifistatus');
        if ( is_object($cmd) ) {
            if ( $cmd->getDisplay('generic_type') == "" )
            {
                $cmd->setDisplay('generic_type','GENERIC_INFO');
                $cmd->save();
            }
        }
        $cmd = $this->getCmd(null, 'routername');
        if ( is_object($cmd) ) {
            if ( $cmd->getDisplay('generic_type') == "" )
            {
                $cmd->setDisplay('generic_type','GENERIC_INFO');
                $cmd->save();
            }
        }
        $cmd = $this->getCmd(null, 'softversion');
        if ( is_object($cmd) ) {
            if ( $cmd->getDisplay('generic_type') == "" )
            {
                $cmd->setDisplay('generic_type','GENERIC_INFO');
                $cmd->save();
            }
        }
        $cmd = $this->getCmd(null, 'wifien');
        if ( is_object($cmd) ) {
            if ( $cmd->getDisplay('generic_type') == "" )
            {
                $cmd->setDisplay('generic_type','GENERIC_INFO');
                $cmd->save();
            }
        }
        $cmd = $this->getCmd(null, 'wifien5g');
        if ( is_object($cmd) ) {
            if ( $cmd->getDisplay('generic_type') == "" )
            {
                $cmd->setDisplay('generic_type','GENERIC_INFO');
                $cmd->save();
            }
        }
        $cmd = $this->getCmd(null, 'wifissid');
        if ( is_object($cmd) ) {
            if ( $cmd->getDisplay('generic_type') == "" )
            {
                $cmd->setDisplay('generic_type','GENERIC_INFO');
                $cmd->save();
            }
        }
        $cmd = $this->getCmd(null, 'wifissid5g');
        if ( is_object($cmd) ) {
            if ( $cmd->getDisplay('generic_type') == "" )
            {
                $cmd->setDisplay('generic_type','GENERIC_INFO');
                $cmd->save();
            }
        }
        $cmd = $this->getCmd(null, 'wifi_off');
        if ( is_object($cmd) ) {
            if ( $cmd->getDisplay('generic_type') == "" )
            {
                $cmd->setDisplay('generic_type','GENERIC_ACTION');
                $cmd->save();
            }
        }
        $cmd = $this->getCmd(null, 'wifi_on');
        if ( is_object($cmd) ) {
            if ( $cmd->getDisplay('generic_type') == "" )
            {
                $cmd->setDisplay('generic_type','GENERIC_ACTION');
                $cmd->save();
            }
        }

        if ( $this->getIsEnable() )
        {
            log::add('tendaac','debug','get '.preg_replace("/:[^:]*@/", ":XXXX@", $this->getUrl()). 'goform/getStatus?random=0.46529553086082265&modules=internetStatus%2CdeviceStatistics%2CsystemInfo%2CwanAdvCfg%2CwifiRelay%2CwifiBasicCfg%2CsysTime');
            $info = @file_get_contents($this->getUrl(). 'goform/getStatus?random=0.46529553086082265&modules=internetStatus%2CdeviceStatistics%2CsystemInfo%2CwanAdvCfg%2CwifiRelay%2CwifiBasicCfg%2CsysTime');
            if ( $info === false )
                throw new Exception(__('Le routeur tenda ne repond pas ou le compte est incorrecte.',__FILE__));
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
        $wifi_2_4g_off = $this->getCmd(null, 'wifi_2_4g_off');
        if ( ! is_object($wifi_2_4g_off) ) {
            $wifi_2_4g_off = new tendaacCmd();
            $wifi_2_4g_off->setName('Wifi 2.4G Off');
            $wifi_2_4g_off->setEqLogic_id($this->getId());
            $wifi_2_4g_off->setType('action');
            $wifi_2_4g_off->setSubType('other');
            $wifi_2_4g_off->setLogicalId('wifi_2_4g_off');
            $wifi_2_4g_off->setEventOnly(1);
            $wifi_2_4g_off->setDisplay('generic_type','GENERIC_ACTION');
            $wifi_2_4g_off->save();
        }
        $wifi_5g_off = $this->getCmd(null, 'wifi_5g_off');
        if ( ! is_object($wifi_5g_off) ) {
            $wifi_5g_off = new tendaacCmd();
            $wifi_5g_off->setName('WiFi 5G off');
            $wifi_5g_off->setEqLogic_id($this->getId());
            $wifi_5g_off->setType('action');
            $wifi_5g_off->setSubType('other');
            $wifi_5g_off->setLogicalId('wifi_5g_off');
            $wifi_5g_off->setEventOnly(1);
            $wifi_5g_off->setDisplay('generic_type','GENERIC_ACTION');
            $wifi_5g_off->save();
        }
        $wifi_2_4g_on = $this->getCmd(null, 'wifi_2_4g_on');
        if ( ! is_object($wifi_2_4g_on) ) {
            $wifi_2_4g_on = new tendaacCmd();
            $wifi_2_4g_on->setName('WiFi 2.4G on');
            $wifi_2_4g_on->setEqLogic_id($this->getId());
            $wifi_2_4g_on->setType('action');
            $wifi_2_4g_on->setSubType('other');
            $wifi_2_4g_on->setLogicalId('wifi_2_4g_on');
            $wifi_2_4g_on->setEventOnly(1);
            $wifi_2_4g_on->setDisplay('generic_type','GENERIC_ACTION');
            $wifi_2_4g_on->save();
        }
        $wifi_5g_on = $this->getCmd(null, 'wifi_5g_on');
        if ( ! is_object($wifi_5g_on) ) {
            $wifi_5g_on = new tendaacCmd();
            $wifi_5g_on->setName('Wifi 5G on');
            $wifi_5g_on->setEqLogic_id($this->getId());
            $wifi_5g_on->setType('action');
            $wifi_5g_on->setSubType('other');
            $wifi_5g_on->setLogicalId('wifi_5g_on');
            $wifi_5g_on->setEventOnly(1);
            $wifi_5g_on->setDisplay('generic_type','GENERIC_ACTION');
            $wifi_5g_on->save();
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
            $reboot->setDisplay('generic_type','GENERIC_ACTION');
            $reboot->save();
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
            log::add('tendaac','debug','scan '.$this->getName());
            $statuscmd = $this->getCmd(null, 'status');
            $url = $this->getUrl();
            log::add('tendaac','debug','get '.preg_replace("/:[^:]*@/", ":XXXX@", $url).'goform/getStatus?random=0.46529553086082265&modules=internetStatus%2CdeviceStatistics%2CsystemInfo%2CwanAdvCfg%2CwifiRelay%2CwifiBasicCfg%2CsysTime');
            $info = @file_get_contents($this->getUrl(). 'goform/getStatus?random=0.46529553086082265&modules=internetStatus%2CdeviceStatistics%2CsystemInfo%2CwanAdvCfg%2CwifiRelay%2CwifiBasicCfg%2CsysTime');
            
            if ( $info === false ) {
                throw new Exception(__('Le routeur tenda ne repond pas.',__FILE__));
                if ($statuscmd->execCmd() != 0) {
                    $statuscmd->setCollectDate('');
                    $statuscmd->event(0);
                }
            }
            $arr = json_decode($info, true);

            $routername = $this->getCmd(null, 'routername');
            if ( $routername->execCmd() != $routername->formatValue($arr["deviceStastics"]["routerName"]) ) {
                log::add('tendaac','debug','routername change : '.$arr["deviceStastics"]["routerName"]);
                $routername->setCollectDate('');
                $routername->event($arr["deviceStastics"]["routerName"]);
            }

            $softversion = $this->getCmd(null, 'softversion');
            $softversion->setCollectDate('');
            $softversion->event($arr["systemInfo"]["softVersion"]);

            $wifien = $this->getCmd(null, 'wifien');
            log::add('tendaac','debug','wifien change : '.$wifien->execCmd()." != ".$wifien->formatValue($regs[1]));
            if ( $wifien->execCmd() != $wifien->formatValue($regs[1]) ) {
                log::add('tendaac','debug','wifien change : '.$regs[1]);
                $wifien->setCollectDate('');
                $wifien->event($arr["wifiBasicCfg"]["wifiEn"]);
            }

            $wifien5g = $this->getCmd(null, 'wifien5g');
            $wifien5g->setCollectDate('');
            $wifien5g->event($arr["wifiBasicCfg"]["wifiEn_5G"]);

            $wifissid = $this->getCmd(null, 'wifissid');
            $wifissid->setCollectDate('');
            $wifissid->event($arr["wifiBasicCfg"]["wifiSSID"]);

            $wifissid5g = $this->getCmd(null, 'wifissid5g');
            $wifissid5g->setCollectDate('');
            $wifissid5g->event($arr["wifiBasicCfg"]["wifiSSID_5G"]);     
                   
            $wifistatus = $this->getCmd(null, 'wifistatus');
            log::add('tendaac','debug','wifistatus change : '.$wifistatus->execCmd()." != ".$wifistatus->formatValue($regs[1]));
            if ( $wifistatus->execCmd() != $wifistatus->formatValue($regs[1]) ) {
                log::add('tendaac','debug','wifistatus change : '.$regs[1]);
                $wifistatus->setCollectDate('');
                $wifistatus->event($regs[1]);
            }
            
            if ($statuscmd->execCmd() != 1) {
                $statuscmd->setCollectDate('');
                $statuscmd->event(1);
            }
            log::add('tendaac','debug','scan end '.$this->getName());
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
            if ( $_value == 1 ) {
                return 0;
            } else {
                return 1;
            }
        }
        elseif ($this->getLogicalId() == 'wifien' || $this->getLogicalId() == 'wifien5g') {
            if ( $_value == true ) {
                return 1;
            } else {
                return 0;
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

        if ( $this->getLogicalId() == 'wifi_5g_off' )
        {
            $url .= 'goform/wirelessBasic?radiohiddenButton=1';
        }
        else if ( $this->getLogicalId() == 'wifi_5g_on' )
        {
            $url .= 'goform/wirelessBasic?radiohiddenButton=0';
        }
        else if ( $this->getLogicalId() == 'wifi_2_4g_off' )
        {
            $url .= 'goform/wirelessBasic?radiohiddenButton=0';
        }
        else if ( $this->getLogicalId() == 'wifi_2_4g_on' )
        {
            $url .= 'goform/wirelessBasic?radiohiddenButton=0';
        }
        else if ( $this->getLogicalId() == 'reboot' )
        {
            $url .= "goform/SysToolReboot";
        }
        else
            return false;
        log::add('tendaac','debug','get '.preg_replace("/:[^:]*@/", ":XXXX@", $url));
        $result = @file_get_contents($url);
        if ( $result === false )
        {
            return false;
        }
        $eqLogic->scan();
        return false;
    }
}




