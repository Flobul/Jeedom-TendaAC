function addCmdToTable(_cmd) {
   if (!isset(_cmd)) {
        var _cmd = {configuration: {}};
    }
    if (!isset(_cmd.configuration)) {
        _cmd.configuration = {};
    }

    if (init(_cmd.type) == 'info') {
        var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '" >';
        if (init(_cmd.logicalId) == 'brut') {
            tr += '<input type="hiden" name="brutid" value="' + init(_cmd.id) + '">';
        }

        tr += '<td>';
        tr += '<span class="cmdAttr" data-l1key="id"></span>';
        tr += '</td>';
        tr += '<td>';
        tr += '<input class="cmdAttr form-control input-sm" data-l1key="name" style="width : 140px;" placeholder="{{Nom}}"></td>';
        tr += '<td class="expertModeVisible">';
        tr += '<input class="cmdAttr form-control type input-sm" data-l1key="type" value="action" disabled style="margin-bottom : 5px;" />';
        tr += '<span class="subType" subType="' + init(_cmd.subType) + '"></span>';
        tr += '</td>';

        tr += '<td>';
        tr += '<input class="cmdAttr" id="'+ _cmd.id +'value" style="width : 200px; font-style: italic;" readonly="true" value="">';
        $('#'+_cmd.id +'value').val("loading");
        jeedom.cmd.execute({
            id: _cmd.id,
            cache: 0,
            notify: false,
            success: function(result) {
                $('#'+_cmd.id +'value').val(result);
            }
          });
        tr += '</td>';

        tr += '<td>';
        if (init(_cmd.logicalId) == 'nbimpulsionminute') {
            tr += '<textarea class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="calcul" style="height : 33px;" placeholder="{{Calcul}}"></textarea> (utiliser #brut# dans la formule)';
        }

        tr += '</td>';
        tr += '<td>';
        tr += '<input type=hidden class="cmdAttr form-control input-sm" data-l1key="unite" value="">';
        tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="minValue" placeholder="{{Min}}" title="{{Min}}" style="width : 50%;display : none;"> ';
        tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="maxValue" placeholder="{{Max}}" title="{{Max}}" style="width : 50%;display : none;">';
        tr += '<span><input type="checkbox" class="cmdAttr" data-l1key="isHistorized"/> {{Historiser}}<br/></span>';
        tr += '<span><input type="checkbox" class="cmdAttr" data-l1key="isVisible" checked/> {{Afficher}}<br/></span>';
        tr += '</td>';
        tr += '<td>';
        if (is_numeric(_cmd.id)) {
            tr += '<a class="btn btn-default btn-xs cmdAction expertModeVisible" data-action="configure"><i class="fa fa-cogs"></i></a> ';
        }
        tr += '</td>';
        table_cmd = '#table_cmd';
        if ( $(table_cmd+'_'+_cmd.eqType ).length ) {
            table_cmd+= '_'+_cmd.eqType;
        }
        $(table_cmd+' tbody').append(tr);
        $(table_cmd+' tbody tr:last').setValues(_cmd, '.cmdAttr');
    }
    if (init(_cmd.type) == 'action') {
        var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
        tr += '<td>';
        tr += '<span class="cmdAttr" data-l1key="id"></span>';
        tr += '</td>';
        tr += '<td>';
        tr += '<input class="cmdAttr form-control input-sm" data-l1key="name" style="width : 140px;" placeholder="{{Nom}}">';
        tr += '</td>';
        tr += '<td>';
        tr += '<input class="cmdAttr form-control type input-sm" data-l1key="type" value="action" disabled style="margin-bottom : 5px;" />';
        tr += '<span class="subType" subType="' + init(_cmd.subType) + '"></span>';
        tr += '<input class="cmdAttr" data-l1key="configuration" data-l2key="virtualAction" value="1" style="display:none;" >';
        tr += '</td>';
        tr += '<td>';
        tr += '</td>';
        tr += '<td></td>';
        tr += '<td>';
        tr += '<span><input type="checkbox" class="cmdAttr" data-l1key="isVisible" checked/> {{Afficher}}<br/></span>';
        tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="minValue" placeholder="{{Min}}" title="{{Min}}" style="width : 50%;display : none;">';
        tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="maxValue" placeholder="{{Max}}" title="{{Max}}" style="width : 50%;display : none;">';
        tr += '</td>';
        tr += '<td>';
        if (is_numeric(_cmd.id)) {
            tr += '<a class="btn btn-default btn-xs cmdAction expertModeVisible" data-action="configure"><i class="fa fa-cogs"></i></a> ';
            tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i> {{Tester}}</a>';
        }
        tr += '</td>';
        tr += '</tr>';

        table_cmd = '#table_cmd';
        if ( $(table_cmd+'_'+_cmd.eqType ).length ) {
            table_cmd+= '_'+_cmd.eqType;
        }
        $(table_cmd+' tbody').append(tr);
        $(table_cmd+' tbody tr:last').setValues(_cmd, '.cmdAttr');
        var tr = $(table_cmd+' tbody tr:last');
        jeedom.eqLogic.builSelectCmd({
            id: $(".li_eqLogic.active").attr('data-eqLogic_id'),
            filter: {type: 'info'},
            error: function (error) {
                $('#div_alert').showAlert({message: error.message, level: 'danger'});
            },
            success: function (result) {
              tr.find('.cmdAttr[data-l1key=value]').append(result);
              tr.setValues(_cmd, '.cmdAttr');
            }
        });
    }
}

$('#bt_healthtendaac').on('click', function () {
  $('#md_modal').dialog({title: "{{Santé Tenda AC}}"});
  $('#md_modal').load('index.php?v=d&plugin=tendaac&modal=health').dialog('open');
});

$('#bt_goWebpage').on('click', function() {
    $('#md_modal').dialog({title: "{{Accéder à l'interface du routeur}}"});
    window.open('http://'+$('.eqLogicAttr[data-l2key=ip]').value()+'/');
});

$('#bt_downloadBackupTenda').on('click', function() {
    $('#md_modal').dialog({title: "{{Télécharger la sauvegarde}}"});
      if($('#sel_restoreBackupTenda').value() != ''){
        window.open('core/php/downloadFile.php?pathfile='+$('#sel_restoreBackupTenda').value());
      }
});
$('#bt_createBackupTenda').off().on('click', function (event) {
     bootbox.confirm('{{Êtes-vous sûr de vouloir créer un backup ? Une fois lancée cette opération ne peut être annulée.}}',
         function (result) {
             if (result) {
               $.ajax({
             		type: "POST",
             		url: "plugins/tendaac/core/ajax/tendaac.ajax.php",
             		data: {
             			action: "createBackup",
             		},
             		dataType: 'json',
             		global: false,
             		error: function (request, status, error) {
             			handleAjaxError(request, status, error);
             		},
             		success: function (data) {
             			$('#div_alert').showAlert({message: 'Backup effectué avec succès !', level: 'success'});
                       // fonction pour mettre à jour la liste
             		}
             	});
             }
         });
 });

function checkRemoveFile(url) {
	$('#div_alert').showAlert({message: '{{Suppression en cours}}', level: 'warning'});
	$.ajax({
		type: "POST",
		url: "plugins/tendaac/core/ajax/tendaac.ajax.php",
		data: {
			action: "checkRemoveFile",
			url: url,
		},
		dataType: 'json',
		global: false,
		error: function (request, status, error) {
			handleAjaxError(request, status, error);
		},
		success: function (data) {
			$('#div_alert').showAlert({message: 'Fichier de configuration supprimé avec succès !', level: 'success'});
          // fonction pour mettre à jour la liste
		}
	});
}

$('#bt_removeBackupTenda').on('click', function() {
  var url = $('#sel_restoreBackupTenda option:selected').text();
  $('#md_modal').dialog({title: "{{Supprimer la sauvegarde}}"});
  if($('#sel_restoreBackupTenda').value() != ''){
    bootbox.confirm('{{Êtes-vous sûr de vouloir supprimer la sauvegarde suivante :}} <b>' + $('#sel_restoreBackupTenda option:selected').text() + '</b> ?<br/>{{Une fois lancée, cette opération ne peut être annulée.}}',
    function (result) {
      if (result) {
        $('#div_alert').showAlert({message: url, level: 'danger'});
        checkRemoveFile(url);
      }
    });
  }
});
