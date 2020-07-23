<?php
if (!isConnect('admin')) {
        throw new Exception('{{401 - Accès non autorisé}}');
}
?>
				<div class="form-group">
                <legend>{{Configuration}}
                </legend>
                <a class="btn btn-default" id="bt_goWebpage" title="{{Accéder à la page web}}"><i class="fa fa-cogs"></i></a>
                <label class="col-sm-4 control-label">{{IP du routeur Tenda}}</label>
                <div class="col-sm-7">
                  <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="ip"/>
                </div>
              </div>


              <div class="form-group">
                <label class="col-sm-4 control-label">{{Mot de passe du routeur Tenda}}
                  <sup>
                    <i class="fa fa-question-circle tooltips" title="Saisissez le mot de passe d\'accès au routeur.
Laisse le champ vide si vous n\'avez pas de mot de passe." style="font-size : 1em;color:grey;"></i>
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


		<div class="col-lg-8">
			<form class="form-horizontal">
				<legend>{{Sauvegardes}}</legend>
					<fieldset>
            <div class="form-group">
              <label class="col-xs-12"><i class="fas fa-tape"></i> {{Sauvegardes disponibles}}</label>
              <div class="col-xs-12">
                <select class="form-control" id="sel_restoreBackupTenda">
                </select>
              </div>
            </div>

            <div class="form-group">
              <div class="col-sm-6 col-xs-12">
                <a class="btn btn-danger" id="bt_removeBackupTenda" style="width:100%;"><i class="far fa-trash-alt"></i> {{Supprimer la sauvegarde}}</a>
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
<?php
include_file('desktop', 'tendaac', 'js', 'tendaac');