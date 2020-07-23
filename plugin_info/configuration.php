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

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
include_file('core', 'authentification', 'php');
if (!isConnect()) {
	include_file('desktop', '404', 'php');
	die();
}
?>

<form class="form-horizontal">
	<fieldset>
		<legend>
			<i class="fa fa-list-alt"></i> {{Paramètres}}
		</legend>
		<div class="form-group">
			<label class="col-sm-4 control-label">{{Créer un objet pour chaque nouveau client}}</label>
			<div class="col-sm-2">
				<input type="checkbox" class="configKey tooltips" data-l1key="createClients">
			</div>
		</div>
		<div class="form-group">
		  <label class="col-lg-4 control-label" >{{Pièce par défaut pour les nouveaux clients}}</label>
		  <div class="col-lg-3">
			<select id="sel_object" class="configKey form-control" data-l1key="defaultParentObject">
			  <option value="">{{Aucune}}</option>
			  <?php
				foreach (jeeObject::all() as $object) {
				  echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
				}
			  ?>
			</select>
		  </div>
		</div>
<?php
	$ignoredClients=config::byKey('ignoredClients','tendaac',[],true);
	if(count($ignoredClients)) :
?>
		<div class="form-group">
			<label class="col-lg-4 control-label">{{Nombre de clients ignorés}}</label>
			<div class="col-lg-3">
				<?php echo count($ignoredClients); ?>
			</div>
		</div>
  		<div class="form-group">
			<label class="col-lg-4 control-label">{{Réinitialiser}}</label>
			<div class="col-lg-3">
				<a class="btn btn-default" id="bt_noMoreIgnore"><i class='fa fa-trash'></i> {{Ne plus ignorer les clients supprimés}}</a>
			</div>
		</div>
<?php
	endif;
?>
	</fieldset>
</form>
<style>
	div.tblfavorites {
		overflow-y:scroll;
		border:#000000 1px solid;
		min-height:15px;
		max-height:180px;
		width: 50%;
	}
</style>
<script>
$('#bt_noMoreIgnore').on('click', function () {
	$.ajax({// fonction permettant de faire de l'ajax
		type: "POST", // methode de transmission des données au fichier php
		url: "plugins/tendaac/core/ajax/tendaac.ajax.php", // url du fichier php
		data: {
			action: "noMoreIgnore",
			what: "clients"
		},
		dataType: 'json',
		error: function (request, status, error) {
			handleAjaxError(request, status, error);
		},
		success: function (data) { // si l'appel a bien fonctionné
		if (data.state != 'ok') {
			$('#div_alert').showAlert({message: data.result, level: 'danger'});
			return;
		}
		$('#div_alert').showAlert({message: '{{Action réussie}}', level: 'success'});
	  }
	});
});

</script>