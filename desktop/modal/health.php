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

if (!isConnect('admin')) {
	throw new Exception('401 Unauthorized');
}
$eqLogics = tendaac::byType('tendaac');
?>

<table class="table table-condensed tablesorter" id="table_healthtendaac">
	<thead>
		<tr>
			<th>{{Module}}</th>
			<th>{{ID}}</th>
			<th>{{IP}}</th>
			<th>{{Statut}}</th>
			<th>{{WiFi}}</th>
			<th><i class="fas fa-arrow-down"> {{Vitesse réception}}</th>
			<th><i class="fas fa-arrow-up"> {{Vitesse émission}}</th>
			<th>{{Temps de connexion}}</th>
			<th>{{Dernière communication}}</th>
			<th>{{Date de création}}</th>
		</tr>
	</thead>
	<tbody>
	 <?php
foreach ($eqLogics as $eqLogic) {
	echo '<tr><td><a href="' . $eqLogic->getLinkToConfiguration() . '" style="text-decoration: none;">' . $eqLogic->getHumanName(true) . '</a></td>';
	echo '<td><span class="label label-info" style="font-size : 1em; cursor : default;">' . $eqLogic->getId() . '</span></td>';
	if ($eqLogic->getConfiguration('type') == 'box') {
		echo '<td><span class="label label-info" style="font-size : 1em; cursor : default;">' . $eqLogic->getConfiguration('ip') . '</span></td>';
    }
	else {
		$ip = $eqLogic->getCmd('info', 'ip');
		if (isset($ip) && $ip != '') {
			echo '<td><span class="label label-info" style="font-size : 1em; cursor : default;">' . $ip->execCmd() . '</span></td>';
		}
	}
	if ($eqLogic->getConfiguration('type') == 'box') {
		$status = '<span class="label label-success" style="font-size : 1em; cursor : default;">{{OK}}</span>';
		if ($eqLogic->getStatus('state') == 'nok') {
			$status = '<span class="label label-danger" style="font-size : 1em; cursor : default;">{{NOK}}</span>';
		}
    } else {
		$present = $eqLogic->getCmd('info', 'present');
		if (isset($present) && $present != '' && $present->execCmd() == '1') {
			$status = '<span class="label label-success" style="font-size : 1em; cursor : default;">{{OK}}</span>';
		} else {
			$status = '<span class="label label-danger" style="font-size : 1em; cursor : default;">{{NOK}}</span>';          
		}
	}
	if ($eqLogic->getConfiguration('type') == 'box') {
		$wifistatus = $eqLogic->getCmd('info', 'wifistatus');
		if (is_object($wifistatus)) {
			$wifivalue = $wifistatus->execCmd();
		}
		if ($wifivalue == 1){
			$wifi = '<span class="label label-success" style="font-size : 1em;" title="{{Présent}}"><i class="fas fa-rss"></i></span>';
		} else {
			$wifi = '<span class="label label-danger" style="font-size : 1em;" title="{{Absent}}"><i class="fa fa-times"></i></span>';
		}
	} else {
		$wifi = $eqLogic->getCmd('info', 'access');
		if (isset($wifi) && $wifi != '' && $wifi->execCmd() == 'WiFi') {
			$wifi = '<span class="label label-success" style="font-size : 1em; cursor : default;"><i class="fas fa-rss"></i></span>';
        } elseif (isset($wifi) && $wifi != '' && $wifi->execCmd() == 'Ethernet') {
			$wifi = '<span class="label label-success" style="font-size : 1em; cursor : default;"><i class="icon techno-cable1"></i></span>';
		} else {
			$wifi = '<span class="label label-danger" style="font-size : 1em; cursor : default;">{{NOK}}</span>';          
		}
	}
	echo '<td>' . $status . '</td>';
	echo '<td>' . $wifi . '</td>';

	$downspeed = $eqLogic->getCmd('info', 'downspeed');
	if (is_object($downspeed)) {
		$downspeedvalue = $downspeed->execCmd();
	}
	$upspeed = $eqLogic->getCmd('info', 'upspeed');
	if (is_object($upspeed)) {
		$upspeedvalue = $upspeed->execCmd();
	}
	if ($eqLogic->getConfiguration('type') == 'box') {
		$wantime = $eqLogic->getCmd('info', 'wantime');
  		if (is_object($wantime)) {
			$wantimevalue = $wantime->execCmd();
		}
	} else {
		$wantime = $eqLogic->getCmd('info', 'lastlogin');
  		if (is_object($wantime)) {
			$wantimevalue = $wantime->execCmd();
		}		
    }
	echo '<td></i><span class="label label-info" style="font-size : 1em; cursor : default;">' . $downspeedvalue . ' MB/s</span></td>';
	echo '<td></i><span class="label label-info" style="font-size : 1em; cursor : default;">' . $upspeedvalue . ' MB/s</span></td>';
	echo '<td></i><span class="label label-info" style="font-size : 1em; cursor : default;">' . $wantimevalue . '</span></td>';

	echo '<td><span class="label label-info" style="font-size : 1em; cursor : default;">' . $eqLogic->getCache('lastupdate','0') . '</span></td>';
	echo '<td><span class="label label-info" style="font-size : 1em; cursor : default;">' . $eqLogic->getConfiguration('createtime') . '</span></td></tr>';
}
?>
	</tbody>
</table>