<?php
if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin = plugin::byId('tendaac');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());
?>

<div class="row row-overflow">
    <div class="col-lg-9 col-md-9 col-sm-8 eqLogicThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">
        <legend><i class="fa fa-cog"></i>  {{Gestion}}</legend>
        <div class="eqLogicThumbnailContainer">
            <div class="cursor eqLogicAction" data-action="add" style="background-color : #ffffff; height : 120px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
                <center>
                    <i class="fa fa-plus-circle" style="font-size : 6em;color:#94ca02;"></i>
                </center>
                <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#94ca02"><center>Ajouter</center></span>
            </div>
            <div class="cursor eqLogicAction" data-action="gotoPluginConf" style="background-color : #ffffff; height : 120px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;">
                <center>
                    <i class="fa fa-wrench" style="font-size : 6em;color:#767676;"></i>
                </center>
                <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676"><center>{{Configuration}}</center></span>
            </div>
            <div class="cursor" id="bt_healthtendaac" style="background-color : #ffffff; height : 120px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
              <center>
                <i class="fas fa-medkit" style="font-size : 6em;color:#767676;"></i>
              </center>
              <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676"><center>{{Santé}}</center></span>
            </div>
        </div>
        <legend>{{Mes routeurs Tenda}}
        </legend>
        <div class="eqLogicThumbnailContainer">
            <?php
            if (count($eqLogics) == 0) {
                echo "<br/><br/><br/><center><span style='color:#767676;font-size:1.2em;font-weight: bold;'>{{Vous n\\'avez pas encore de routeur Tenda, cliquez sur Ajouter un équipement pour commencer}}</span></center>";
            } else {
                foreach ($eqLogics as $eqLogic) {
                    echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
                    echo "<center>";
                    echo '<img src="plugins/tendaac/docs/images/tendaac_icon.png" height="105" width="95" />';
                    echo "</center>";
                    echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $eqLogic->getHumanName(true, true) . '</center></span>';
                    echo '</div>';
                }
            }
            ?>
        </div>
    </div>

	<div class="col-xs-12 eqLogic" style="display: none;">
		<div class="input-group pull-right" style="display:inline-flex">
			<span class="input-group-btn">
				<a class="btn btn-default btn-sm eqLogicAction roundedLeft" data-action="configure"><i class="fa fa-cogs"></i> {{Configuration avancée}}</a>
				<a class="btn btn-default btn-sm eqLogicAction" data-action="copy"><i class="fas fa-copy"></i> {{Dupliquer}}</a>
				<a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a>
				<a class="btn btn-danger btn-sm eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
			</span>
		</div>
		<ul class="nav nav-tabs" role="tablist">
        <li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fa fa-arrow-circle-left"></i></a></li>
        <li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-tachometer"></i> {{Equipement}}</a></li>
        <li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i> {{Commandes}}</a></li>
      </ul>
      <div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
        <div role="tabpanel" class="tab-pane active" id="eqlogictab">
          <div class="col-sm-6">
          <form class="form-horizontal">
            <fieldset>
              <legend>
                <i class="fa fa-arrow-circle-left eqLogicAction cursor" data-action="returnToThumbnailDisplay"></i> {{Général}}
                <i class='fa fa-cogs eqLogicAction pull-right cursor expertModeVisible' data-action='configure'></i>
              </legend>

              <div class="form-group">
                <label class="col-sm-3 control-label">{{Nom du routeur Tenda}}</label>
                <div class="col-sm-7">
                  <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
                  <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom du routeur Tenda}}"/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label" >{{Objet parent}}</label>
                <div class="col-sm-7">
                  <select class="form-control eqLogicAttr" data-l1key="object_id">
                    <option value="">{{Aucun}}</option>
                    <?php
                    foreach (jeeObject::all() as $object) {
                      echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>'."\n";
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">{{Catégorie}}</label>
                <div class="col-sm-7">
                  <?php
                  foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
                    echo '<label class="checkbox-inline">'."\n";
                    echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
                    echo '</label>'."\n";
                  }
                  ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label" ></label>
                <div class="col-sm-5">
                  <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-label-text="{{Activer}}" data-l1key="isEnable" checked/>Activer</label>
                  <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-label-text="{{Visible}}" data-l1key="isVisible" checked/>Visible</label>
                </div>
              </div>

              <div class="form-group">
                <legend>{{Configuration}}
                </legend>
                <a class="btn btn-default" id="bt_goWebpage" title='{{Accéder à la page web}}'><i class="fa fa-cogs"></i></a>
                <label class="col-sm-4 control-label">{{IP du routeur Tenda}}</label>
                <div class="col-sm-7">
                  <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="ip"/>
                </div>
              </div>


              <div class="form-group">
                <label class="col-sm-4 control-label">{{Mot de passe du routeur Tenda}}
                  <sup>
                    <i class="fa fa-question-circle tooltips" title="Saisissez le mot de passe d'accès au routeur.
Laisse le champ vide si vous n'avez pas de mot de passe." style="font-size : 1em;color:grey;"></i>
                  </sup>
                </label>
                <div class="col-sm-7">
                  <input type="password" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="password"/>
                </div>
                </div>


			<div class="form-group">
                <label class="col-sm-4 control-label">{{Rafraîchissement des informations}}
                	<sup>
                    <i class="fa fa-question-circle tooltips" title="Réception des informations à intervalle selectionné.
  La commande est envoyée toutes les minutes, 5 minutes, 10 minutes, 15 minutes, 30 minutes..." style="font-size : 1em;color:grey;"></i>
                	</sup>
               	</label>
               	<div class="col-sm-7">
					<select class="eqLogicAttr form-control input-sm" data-l1key="configuration" data-l2key="RepeatCmd">
						<option value="">{{Non}}</option>
						<option value="cron">{{Toutes les minutes}}</option>
						<option value="cron5">{{Toutes les 5 minutes}}</option>
						<option value="cron10">{{Toutes les 10 minutes}}</option>
						<option value="cron15">{{Toutes les 15 minutes}}</option>
						<option value="cron30">{{Toutes les 30 minutes}}</option>
						<option value="cronHourly">{{Toutes les heures}}</option>
					</select>
        </div>
      </div>
			</fieldset>
			</form>
		</div>

		<div class="col-lg-6">
			<form class="form-horizontal">
				<legend>{{Sauvegardes}}</legend>
					<fieldset>
            <div class="form-group">
              <label class="col-xs-12"><i class="fas fa-tape"></i> {{Sauvegardes disponibles}}</label>
              <div class="col-xs-12">
                <select class="form-control" id="sel_restoreBackupTenda">
                  <option value="">{{Aucune}}</option>
                  <?php
                  $path = '/var/www/html/';
                  $directory = 'plugins/tendaac/data/backup/';
                  $scanned_directory = preg_grep('~\.(cfg)$~',(scandir($directory)));
                  foreach ($scanned_directory as $key => $info) {
                    echo '<option value="' . $path . $directory . $info . '">' . $info . '</option>';
                  }
                  ?>
                </select>
              </div>
            </div>

            <div class="form-group">
              <div class="col-sm-6 col-xs-12">
                <a class="btn btn-danger" id="bt_removeBackupTenda" style="width:100%;"><i class="far fa-trash-alt"></i> {{Supprimer la sauvegarde}}</a>
              </div>
              <div class="col-sm-6 col-xs-12">
                <a class="btn btn-warning" id="bt_restoreBackupTenda" style="width:100%;"><i class="fas fa-sync fa-spin" style="display:none;"></i> <i class="far fa-file"></i> {{Restaurer la sauvegarde}}</a>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-6 col-xs-12">
                <a class="btn btn-success" id="bt_downloadBackupTenda" style="width:100%;"><i class="fas fa-cloud-download-alt"></i> {{Télécharger la sauvegarde}}</a>
              </div>
              <div class="col-sm-6 col-xs-12">
                <a class="btn btn-default" id="bt_createBackupTenda" style="width:100%;"><i class="fas fa-cloud-upload-alt"></i> {{Lancer la sauvegarde}}</a>
              </div>
            </div>
          </fieldset>
        </form>
      </div>
    </div>

        <div role="tabpanel" class="tab-pane" id="commandtab">
          <table id="table_cmd" class="table table-bordered table-condensed">
            <thead>
              <tr>
                <th style="width: 50px;">#</th>
                <th style="width: 230px;">{{Nom}}</th>
                <th style="width: 110px;">{{Sous-Type}}</th>
                <th>{{Valeur}}</th>
                <th style="width: 50px;">{{Unité}}</th>
                <th style="width: 100px;">{{Paramètres}}</th>
                <th style="width: 150px;"></th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
</div>
<?php
include_file('desktop', 'tendaac', 'js', 'tendaac');
include_file('core', 'plugin.template', 'js');
