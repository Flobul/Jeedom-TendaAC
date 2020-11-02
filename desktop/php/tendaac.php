<?php
if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin = plugin::byId('tendaac');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());

$has = ["box"=>false,"cli"=>false];
foreach ($eqLogics as $eqLogic) {
    if ($eqLogic->getConfiguration('type') == '') {
        $eqLogic->setConfiguration('type', 'box');
        $eqLogic->save();
    }
    $type=$eqLogic->getConfiguration('type','');
    if($type) {
        $has[$type]=true;
    }
}
?>

<div class="row row-overflow">
    <div class="col-lg-9 col-md-9 col-sm-8 eqLogicThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">
        <legend><i class="fa fa-cog"></i>  {{Gestion}}</legend>
        <div class="eqLogicThumbnailContainer">
            <div class="cursor eqLogicAction logoPrimary" data-action="add"  >
                <i class="fas fa-plus-circle"></i>
                <br>
                <span >{{Ajouter}}</span>
            </div>
            <div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf" >
                <i class="fas fa-wrench"></i>
                <br>
                <span>{{Configuration}}</span>
			</div>
            <div class="cursor logoSecondary" id="bt_healthtendaac">
                <i class="fas fa-medkit"></i>
                <br />
                <span>{{Santé}}</span>
            </div>
   	 </div>
        <legend><i class="fas fa-table"></i>{{Mes routeurs Tenda}}
        </legend>
        <div class="panel">
            <div class="panel-body">
<div class="eqLogicThumbnailContainer ">
            <?php
                    if($has['box']) {
                        foreach ($eqLogics as $eqLogic) {
                            if($eqLogic->getConfiguration('type','') != 'box') {
                                continue;
                            }
                            $opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
                            echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogic->getId() . '">';
                            echo '<img src="' . $eqLogic->getImage() . '"/>';
                            echo '<br>';
                            echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
                            echo '</div>';
                        }
            } else {
                echo "<br/><br/><br/><center><span style='color:#767676;font-size:1.2em;font-weight: bold;'>{{Vous n\\'avez pas encore de routeur Tenda, cliquez sur Ajouter un équipement pour commencer}}</span></center>";
            }
            ?>
        </div>
    	</div>
        </div>
		<legend>
			<i class="fas fa-table"></i> {{Mes Clients}} 
				<span class="cursor eqLogicAction" style="color:#0091ff" data-action="discover" data-action2="clients" title="{{Scanner les clients}}">
			<i class="fas fa-bullseye"></i></span>&nbsp;
				<span class="cursor eqLogicAction" style="color:#0091ff" data-action="delete" data-action2="clients" title="{{Supprimer Clients non-actifs (et ignorer lors des prochaines sync)}}">
			<i class="fas fa-trash"></i></span>
				<span class="cursor eqLogicAction" style="position: absolute; right: 100px;color:#0091ff" data-action="delete" data-action2="all" title="{{Supprimer tous les clients}}">
			<i class="fas fa-trash"></i></span>
		</legend>
        <div class="input-group" style="margin-bottom:5px;">
            <input class="form-control roundedLeft" placeholder="{{Rechercher}}" id="in_searchEqlogic2" />
            <div class="input-group-btn">
                <a id="bt_resetEqlogicSearch2" class="btn roundedRight" style="width:30px"><i class="fas fa-times"></i></a>
            </div>
        </div>
        <div class="panel">
            <div class="panel-body">
                <div class="eqLogicThumbnailContainer  second">
                    <?php
                    if($has['cli']) {
						foreach ($eqLogics as $eqLogic) {
                            if($eqLogic->getConfiguration('type','') != 'cli') {
                                continue;
                            }
                            $opacity = '';
                            if ($eqLogic->getIsEnable() != 1) {
                                $opacity = ' disableCard';
                            }

                            echo '<div class="eqLogicDisplayCard cursor  second '.$opacity.'" data-eqLogic_id="' . $eqLogic->getId() . '">';
                            echo '<img src="' . $eqLogic->getImage() . '"/>';
                            echo '<br>';
                            echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
                    echo '</div>';
                }
                    } else {
                        echo "<br/><br/><br/><center><span style='color:#767676;font-size:1.2em;font-weight: bold;'>{{Scannez les clients pour les créer}}</span></center>";
            }
            ?>
                </div>
            </div>
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
        <li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Equipement}}</a></li>
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
			</fieldset>
			</form>
		</div>
		<div class="col-sm-6">
			<br />
			<form class="form-horizontal">
				<fieldset>                
					<div class="item-conf"></div>
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
                <th style="width: 150px;">{{Sous-Type}}</th>
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
