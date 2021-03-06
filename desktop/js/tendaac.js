function printEqLogic(_eqLogic) {
    $('.eqLogicAttr[data-l1key=configuration][data-l2key=type]').off();
    if (isset(_eqLogic.configuration) && isset(_eqLogic.configuration.type) && _eqLogic.configuration.type != '') {
        $('.item-conf').load('index.php?v=d&plugin=tendaac&modal=' + _eqLogic.configuration.type + '.configuration', function () {
            $('body').setValues(_eqLogic, '.eqLogicAttr');
            initCheckBox();
            modifyWithoutSave = false;
        });
    } else {
        $('.item-conf').empty();
        $('.eqLogicAttr[data-l1key=configuration][data-l2key=type]').on('change', function () {
            $('.item-conf').load('index.php?v=d&plugin=tendaac&modal=' + $(this).val() + '.configuration', function() {
                initCheckBox();
            });
        });
    }	
	
}

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
        tr += '<input class="cmdAttr form-control input-sm" data-l1key="name" style="width : 200px;" placeholder="{{Nom}}"></td>';
        tr += '<td>';
        tr += '<input class="cmdAttr form-control type input-sm" data-l1key="type" value="info" disabled style="margin-bottom : 5px;" />';
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
        tr += '</td><td>';
        tr += '</td><td>';
        tr += '<span><input type="checkbox" class="cmdAttr" data-l1key="isHistorized"/> {{Historiser}}<br/></span>';
        tr += '<span><input type="checkbox" class="cmdAttr" data-l1key="isVisible" checked/> {{Afficher}}<br/></span>';
        tr += '</td>';
        tr += '<td>';
        if (is_numeric(_cmd.id)) {
            tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fa fa-cogs"></i></a> ';
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
        tr += '</td>';
        tr += '<td></td>';
        tr += '<td></td>';
        tr += '<td>';
        tr += '<span><input type="checkbox" class="cmdAttr" data-l1key="isVisible" checked/> {{Afficher}}<br/></span>';
        tr += '</td>';
        tr += '<td>';
        if (is_numeric(_cmd.id)) {
            tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fa fa-cogs"></i></a> ';
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

$('.eqLogicAction[data-action=discover]').on('click', function (e) {
	var what=e.currentTarget.dataset.action2 || null;
	$.ajax({// fonction permettant de faire de l'ajax
		type: "POST", // methode de transmission des données au fichier php
		url: "plugins/tendaac/core/ajax/tendaac.ajax.php", // url du fichier php
		data: {
			action: "syncTendaac",
			what: what
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
			$('#div_alert').showAlert({message: '{{Synchronisation réussie}} : '+what, level: 'success'});
			location.reload();
	  }
	});
});

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
$('#bt_createBackupTenda').off().on('click', function () {
     bootbox.confirm('{{Êtes-vous sûr de vouloir créer un backup ? Une fois lancée cette opération ne peut être annulée.}}',
         function (result) {
             if (result) {
               $.ajax({
             		type: "POST",
             		url: "plugins/tendaac/core/ajax/tendaac.ajax.php",
             		data: {
             			action: "createBackup",
             			id: $('.eqLogicAttr[data-l1key=id]').value(),
             		},
             		dataType: 'json',
             		global: false,
             		error: function (request, status, error) {
             			handleAjaxError(request, status, error);
             		},
             		success: function (data) {
             			$('#div_alert').showAlert({message: 'Backup effectué avec succès !', level: 'success'});
                         updateListBackup();
             		}
             	});
             }
         });
 });

$('.eqLogicAction[data-action=delete]').on('click', function (e) {
	var what=e.currentTarget.dataset.action2;
	if (what == 'clients') var text='{{Cette action supprimera les '+what+' désactivés (grisés).<br/>Ceux-ci seront ignorés lors des prochains scans.<br/>Pour réinitialiser les ignorés, allez dans la configuration du plugin.}}';
	else if (what == 'all') var text='{{Cette action supprimera tous les clients.}}';
		bootbox.confirm(text, function(result) {		if (result) {
			$.ajax({// fonction permettant de faire de l'ajax
				type: "POST", // methode de transmission des données au fichier php
				url: "plugins/tendaac/core/ajax/tendaac.ajax.php", // url du fichier php
				data: {
					action: "deleteDisabledEQ",
					what: what
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
				$('#div_alert').showAlert({message: '{{Suppression réussie}} : '+what, level: 'success'});
				location.reload();
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
          	updateListBackup();
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

 function updateListBackup() {
	$.ajax({
		type: "POST",
		url: "plugins/tendaac/core/ajax/tendaac.ajax.php",
		data: {
			action: "listBackup",
		},
		dataType: 'json',
		global: false,
		error: function (request, status, error) {
			handleAjaxError(request, status, error);
		},
		success: function (backups) {
			var options = '';
			for (i in backups) {
				var toto = backups[i].split(",");
				options = '<option value="">{{Aucune}}</option>';
				for (j in toto) {
				options += '<option value="/var/www/html/plugins/tendaac/data/backup/' + toto[j] + '">' + toto[j] + '</option>';
				}
			}
			$('#sel_restoreBackupTenda').html(options);
		}
	});
}

updateListBackup();