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

try {
	require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
	include_file('core', 'authentification', 'php');

	if (!isConnect('admin')) {
		throw new Exception('401 Unauthorized');
	}
	ajax::init();
  
	if (init('action') == 'deleteDisabledEQ') {
		tendaac::deleteDisabledEQ(init('what'));
		ajax::success();
    }
	if (init('action') == 'syncTendaac') {
		if(init('what'))
			$param=init('what');
		else
			$param=null;
		tendaac::syncTendaac($param);
		ajax::success();
    }
  	if (init('action') == 'noMoreIgnore') {
		tendaac::noMoreIgnore(init('what'));
		ajax::success();
	}
	if (init('action') == 'checkRemoveFile') {
		$url = init('url');
		$arr = ajax::success(tendaac::checkRemoveFile(init('url')));
		$return['cmd'] = array();
		foreach ($arr as $cmd) {
			log::add('tendaac', 'debug', "Erreur checkRemoveFile : ".$cmd);
			$return['cmd'][] = $cmd;
		}
		ajax::success($return);
	}

	if (init('action') == 'createBackup') {
		$eqLogics = eqLogic::byType('tendaac');
		foreach ($eqLogics as $eqLogic) {
			if ($eqLogic->getId() == init('id')) {
			$backup = $eqLogic->getCmd(null, 'backup');
			$backup->execCmd();
			log::add('tendaac','debug','Lancement backup par page accueil');
			}
		}
		ajax::success($return);
	}
	if (init('action') == 'listBackup') {
		$options = '';
		$path = '/var/www/html/';
		$directory = 'plugins/tendaac/data/backup/';
		$scanned_directory = preg_grep('~\.(cfg)$~',(scandir($path.$directory)));
		foreach ($scanned_directory as $key => $info) {
          $options .= $info.',';
		}
		$options = substr($options,0,strlen($options)-1);
      	ajax::success($options);
    }

  throw new Exception('Aucune methode correspondante');
	/*     * *********Catch exeption*************** */
} catch (Exception $e) {
	ajax::error(displayExeption($e), $e->getCode());
}
?>