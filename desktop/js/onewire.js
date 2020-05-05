
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

jQuery(document).ready(function () {
    console.debug($('.eqLogicAttr[data-l2key=onewire_mode]').val());
    view_mode($('.eqLogicAttr[data-l2key=onewire_mode]').val());
    view_connexion($('.eqLogicAttr[data-l2key=onewire_connexion]').val());
});

$('.eqLogicAction[data-action=bt_docSpecific]').on('click', function () {
    window.open('https://bebel27a.github.io/jeedom-mymobdus.github.io/fr_FR/');
});

function view_mode(mode) {
    if (mode == 'owfs') {
        $('#libellessh').hide();
        $('#onewire_user').hide();
        $('#onewire_password').hide();
    }
    else {
        $('#libellessh').show();
        $('#onewire_user').show();
        $('#onewire_password').show();
    }
}
function view_connexion(con) {
    if (con == 'local') {
        $('#connect').hide();
    }
    else {
        $('#connect').show();
    }
}

$('body').delegate('.eqLogicAttr[data-l2key=onewire_mode]', 'change', function () {
    view_mode($(this).val());
});

$('body').delegate('.eqLogicAttr[data-l2key=onewire_connexion]', 'change', function () {
    view_connexion($(this).val());
});


$('body').delegate('.eqLogicAction[data-action=bt_testConnexion]', 'click', function () {
    console.info('test de la connexion');
    var onewire_mode = $('.eqLogicAttr[data-l2key=onewire_mode]').val();
    var onewire_connexion = $('.eqLogicAttr[data-l2key=onewire_connexion]').val();
    var onewire_addressip = $('.eqLogicAttr[data-l2key=onewire_addressip]').val();
    var onewire_portssh = $('.eqLogicAttr[data-l2key=onewire_portssh]').val();
    var onewire_user = $('.eqLogicAttr[data-l2key=onewire_user]').val();
    var onewire_password = $('.eqLogicAttr[data-l2key=onewire_password]').val();
    test_connexion(onewire_mode, onewire_connexion, onewire_addressip, onewire_portssh, onewire_user, onewire_password);
});


function test_connexion(mode, connexion, addressip, port, user, password) {
    $.ajax({// fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "plugins/onewire/core/ajax/onewire.ajax.php", // url du fichier php
        data: {
            action: "test_connexion",
            mode: mode,
            connexion: connexion,
            host: addressip,
            port: port,
            login: user,
            pass: password,
        },
        dataType: 'json',
        error: function (request, status, error) {
            handleAjaxError(request, status, error);
        },
        success: function (data) { // si l'appel a bien fonctionné
            if (data.state != 'ok') {
                $('#div_alert').showAlert({ message: data.result, level: 'danger' });
                return;
            }
            $('#div_alert').showAlert({ message: 'Connection reussi sur le serveur : ' + addressip, level: 'success' });
        }
    });
}



$("#table_cmd").sortable({ axis: "y", cursor: "move", items: ".cmd", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: true });



$('#bt_chooseIcon').on('click', function () {
    chooseIcon(function (_icon) {
        $('.eqLogicAttr[data-l1key=display][data-l2key=icon]').empty().append(_icon);
    });
});




$('#bt_owfsTable').on('click', function () {
    $('#md_modal').dialog({ title: "{{Serveur OWFS}}" });
    $('#md_modal').load('index.php?v=d&plugin=onewire&modal=show.owfs&id=' + $('.eqLogicAttr[data-l1key=id]').value()).dialog('open');
});
$('#bt_reload_owfs').on('click', function () {
    reloadowserverDemon();
});
$('#bt_stop_owfs').on('click', function () {
    stopowserverDemon();
});
$('#bt_sql').on('click', function () {
    updateSql();
});






$('#bt_configureDevice').on('click', function () {
    $('#md_modal').dialog({ title: "{{Configuration du périphérique}}" });
    $('#md_modal').load('index.php?v=d&plugin=onewire&modal=configure.device&id=' + $('.eqLogicAttr[data-l1key=id]').value()).dialog('open');
});


/*A la selection d un composant*/
function SelectComposantName(cmd_id) {

    var my_cmd = '';
    if (cmd_id != '')
        var my_cmd = '#cmd_' + cmd_id;

    if ($(my_cmd + " .SelectComposantName option:selected").val() != $(my_cmd + ' .selected_composantName').attr('composantname')) {
        if ($(my_cmd + " .SelectComposantName option:selected").val()) {
            $(my_cmd + ' .selected_composantGroup').attr('composantgroup', '');
            $(my_cmd + ' .selected_composantClass').attr('composantclass', '');
            $(my_cmd + ' .selected_composantClass2').attr('composantclass2', '');
            $(my_cmd + ' .adresse').val('');
        }
    }

    //$('.selected_composantName').attr('composantname',val);
    if ($(my_cmd + " .SelectComposantName option:selected").val() != '') {
        $(my_cmd + ' .selected_composantName').attr('composantname', $(my_cmd + " .SelectComposantName option:selected").val());
    }

    if ($(my_cmd + " .SelectComposantName option:selected").val()) {
        getClassOrGroup($(my_cmd + " .SelectComposantName option:selected").val(), my_cmd);
    }
    $(my_cmd + ' .SelectComposantGroup option').remove();
    $(my_cmd + ' .SelectComposantClass option').remove();
    $(my_cmd + ' .SelectComposantClass2 option').remove();
    $(my_cmd + ' .adresse').val('');
};


/*A la selection d une class*/
function SelectComposantClass(cmd_id) {
    var my_cmd = '';
    if (cmd_id != '')
        var my_cmd = '#cmd_' + cmd_id;

    if ($(my_cmd + " .SelectComposantClass option:selected").val() != '') {
        $(my_cmd + ' .selected_composantClass').attr('composantclass', $(my_cmd + " .SelectComposantClass option:selected").val());
    }
};

/*A la selection d une class*/
function SelectComposantClass2(cmd_id) {
    var my_cmd = '';
    if (cmd_id != '')
        var my_cmd = '#cmd_' + cmd_id;

    if ($(my_cmd + " .SelectComposantClass2 option:selected").val() != '') {
        $(my_cmd + ' .selected_composantClass2').attr('composantclass2', $(my_cmd + " .SelectComposantClass2 option:selected").val());
    }
};

/*A la selection d un group*/
function SelectComposantGroup(cmd_id) {

    var my_cmd = '';
    if (cmd_id != '')
        var my_cmd = '#cmd_' + cmd_id;

    $(my_cmd + ' .SelectComposantClass option').remove();
    $(my_cmd + ' .SelectComposantClass2 option').remove();

    if ($(my_cmd + " .SelectComposantGroup option:selected").val() != '') {
        $(my_cmd + ' .selected_composantGroup').attr('composantgroup', $(".SelectComposantGroup option:selected").val());
    }

    if ($(my_cmd + " .SelectComposantGroup option:selected").val()) {
        $(my_cmd + ' .selected_composantClass').attr('composantclass', '');
        $(my_cmd + ' .SelectComposantClass').removeAttr('disabled');
        getClass($(my_cmd + " .SelectComposantName option:selected").val(), $(my_cmd + " .SelectComposantGroup option:selected").val(), my_cmd, '');
    }

};

function reloadowserverDemon() {
    $.ajax({// fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "plugins/onewire/core/ajax/onewire.ajax.php", // url du fichier php
        data: {
            action: "reloadowserver",
        },
        dataType: 'json',
        error: function (request, status, error) {
            handleAjaxError(request, status, error);
        },
        success: function (data) { // si l'appel a bien fonctionné
            if (data.state != 'ok') {
                $('#div_alert').showAlert({ message: data.result, level: 'danger' });
                return;
            }
            $('#div_alert').showAlert({ message: 'Le serveur a été correctement relancé', level: 'success' });
        }
    });
}

function stopowserverDemon() {
    $.ajax({// fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "plugins/onewire/core/ajax/onewire.ajax.php", // url du fichier php
        data: {
            action: "stopowserver",
        },
        dataType: 'json',
        error: function (request, status, error) {
            handleAjaxError(request, status, error);
        },
        success: function (data) { // si l'appel a bien fonctionné
            if (data.state != 'ok') {
                $('#div_alert').showAlert({ message: data.result, level: 'danger' });
                return;
            }
            $('#div_alert').showAlert({ message: 'Le serveur a été correctement arrété', level: 'success' });
        }
    });
}




function updateSql() {
    $.ajax({// fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "plugins/onewire/core/ajax/onewire.ajax.php", // url du fichier php
        data: {
            action: "updateSql",
        },
        dataType: 'json',
        error: function (request, status, error) {
            handleAjaxError(request, status, error);
        },
        success: function (data) { // si l'appel a bien fonctionné
            if (data.state != 'ok') {
                $('#div_alert').showAlert({ message: data.result, level: 'danger' });
                return;
            }
            $('#div_alert').showAlert({ message: 'La tables des composants est a jour', level: 'success' });
        }
    });
}





function getGroup(ComposantName, my_cmd) {
    $.ajax({// fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "plugins/onewire/core/ajax/onewire.ajax.php", // url du fichier php

        data: {
            action: "getgroup",
            name: ComposantName,
        },
        dataType: 'json',
        error: function (request, status, error) {
            handleAjaxError(request, status, error);
        },
        success: function (data) { // si l'appel a bien fonctionné
            selType = '<option value="">Choisir</option>';
            $.each(data, function (key, val) {
                var selected = '';
                if ($(my_cmd + ' .selected_composantGroup').attr('composantgroup') == val)
                    selected = 'selected="selected"';

                selType += '<option ' + selected + ' value="' + val + '">' + val + '</option>';
            });
            $(my_cmd + ' .SelectComposantGroup').append(selType);
        }
    });

}


function getAdresse(class2, instanceId, composantName) {

    if ($('.li_eqLogic.active').attr('data-eqLogic_id') != undefined) {
        /*recuperation des informations pour se connecter au bus */
        $('#md_modal').dialog({ title: "{{BUS " + composantName + " }}" });
        $('#md_modal').load('index.php?v=d&plugin=onewire&modal=show.owfs&instanceId=' + instanceId + '&class2=' + class2 + '&eqLogic=' + $('.li_eqLogic.active').attr('data-eqLogic_id')).dialog('open');
    } else {
        console.info('Impossible de trouver l\'équipement associé');
    }

}


function getClass(ComposantName, group, my_cmd, typeclass) {


    $.ajax({// fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "plugins/onewire/core/ajax/onewire.ajax.php?action=getclass", // url du fichier php
        data: {
            action: "getclass",
            name: ComposantName,
            group: group
        },
        dataType: 'json',
        error: function (request, status, error) {
            handleAjaxError(request, status, error);
        },
        success: function (data) { // si l'appel a bien fonctionné

            //on masque tout
            $(my_cmd + ' .adresse').hide();
            $(my_cmd + ' .cmdAction[data-action=configurebus]').hide();

            $(my_cmd + ' .SelectComposantClass2').hide();
            $(my_cmd + ' .SelectComposantClass2 option').remove();/*Vide la class2*/
            $(my_cmd + ' .SelectComposantClass2').attr('disabled', 'disabled');/*desactive les class*/

            $(my_cmd + ' .SelectComposantClass').hide();
            $(my_cmd + ' .SelectComposantClass option').remove();/*Vide la class*/
            $(my_cmd + ' .SelectComposantClass').attr('disabled', 'disabled');/*desactive les class*/

            /*CLASS 2 UNIQUEMENT RETOUR LE SELECT*/
            if (data.class2.length > 1) {
                var composantClass2 = $(my_cmd + ' .selected_composantClass2').attr('composantclass2');
                //  var selType = '<select id="SelectComposantName" style="width : 120px; margin-bottom : 3px;"  class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="composnantName">';
                var selType2 = '<option value="">Choisir</option>';
                $.each(data.class2, function (key2, val2) {
                    var selected2 = '';
                    if (composantClass2 == val2)
                        selected2 = 'selected="selected"';

                    selType2 += '<option ' + selected2 + ' value="' + val2 + '">' + val2 + '</option>';
                });

                $(my_cmd + ' .SelectComposantClass2').removeAttr('disabled');/*reactive la liste des class*/
                $(my_cmd + ' .SelectComposantClass2').show();
                $(my_cmd + ' .SelectComposantClass2').append(selType2);
                $(my_cmd + ' .cmdAction[data-action=configurebus]').show();
                $(my_cmd + ' .adresse').show();
            }

            /*CLASS*/
            var composantClass = $(my_cmd + ' .selected_composantClass').attr('composantclass');
            //  var selType = '<select id="SelectComposantName" style="width : 120px; margin-bottom : 3px;"  class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="composnantName">';
            var selType = '<option value="">Choisir</option>';

            $.each(data.class, function (key2, val2) {
                var selected = '';
                if (composantClass == val2)
                    selected = 'selected="selected"';
                selType += '<option ' + selected + ' value="' + val2 + '">' + val2 + '</option>';
            });
            $(my_cmd + ' .SelectComposantClass').removeAttr('disabled');/*reactive la liste des class*/
            $(my_cmd + ' .SelectComposantClass').show();
            $(my_cmd + ' .SelectComposantClass').append(selType);

        }
    });

}


function getClassOrGroup(Composantname, my_cmd) {

    if (Composantname == '' || Composantname === null)
        return;

    $.ajax({// fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "plugins/onewire/core/ajax/onewire.ajax.php", // url du fichier php

        data: {
            action: "isgroup",
            name: Composantname,
        },
        dataType: 'json',
        error: function (request, status, error) {
            handleAjaxError(request, status, error);
        },
        success: function (data) { // si l'appel a bien fonctionné

            if (data == 'ok') {/*Si  il existe  des groupes */
                var group = getGroup(Composantname, my_cmd);
                $(my_cmd + ' .SelectComposantGroup').removeAttr('disabled');/*reactive la liste des groupe*/
                $(my_cmd + ' .SelectComposantClass option').remove();/*vide les class*/
                $(my_cmd + ' .SelectComposantClass').attr('disabled', 'disabled');/*desactive les class*/

            } else {/*Si il n y pas de groupe*/
                $(my_cmd + ' .SelectComposantGroup option').remove();/*Vide les groupes*/
                $(my_cmd + ' .SelectComposantGroup').attr('disabled', 'disabled'); /*readesactive les group*/
                $(my_cmd + ' .SelectComposantClass').removeAttr('disabled');/*reactive la liste des class*/
                getClass(Composantname, null, my_cmd, '');
            }
        }
    });
}

jeedom.cmd.availableMailTemplate = function (cmd_id, mail_is_install/*,virtuel_is_install*/) {

    /*Test si le plugin mail est bien installer */
    if (mail_is_install != 1/* || virtuel_is_install!=1*/) return;

    var selTypeAlert = '<option  value="">Choisir</option>';
    if (ListEmail == "none") return;

    var my_cmd = '';
    if (cmd_id != '')
        var my_cmd = '#select_mail_alert_' + cmd_id;
    var MailTemplate = $(my_cmd + ' .selected_MailTemplate').attr('MailTemplate');

    $.each(ListEmail, function (key, val) {
        var selected = '';
        if (MailTemplate == key)
            selected = 'selected="selected"';

        selTypeAlert += '<option ' + selected + ' value="' + key + '">' + val + '</option>';
    });
    $(my_cmd + ' .SelectMailTemplate').append(selTypeAlert);
}

function getComposants(cmd_id) {

    var my_cmd = '';
    if (cmd_id != '')
        var my_cmd = '#cmd_' + cmd_id;


    $.ajax({// fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "plugins/onewire/core/ajax/onewire.ajax.php", // url du fichier php

        data: {
            action: "getComposants",
        },
        dataType: 'json',
        error: function (request, status, error) {
            handleAjaxError(request, status, error);
        },
        success: function (data) { // si l'appel a bien fonctionné
            $(my_cmd + ' .SelectComposantGroup option').remove();
            var composantName = $(my_cmd + ' .selected_composantName').attr('composantname');
            var composantGroupe = $(my_cmd + ' .selected_composantGroup').attr('composantgroup');
            var composantClass = $(my_cmd + ' .selected_composantClass').attr('composantclass');
            var composantClass2 = $(my_cmd + ' .selected_composantClass2').attr('composantclass2');

            selType = '<option value="">Choisir</option>';
            $.each(data, function (key, val) {
                var selected = '';
                if (composantName == data[key].name)
                    selected = 'selected="selected"';
                selType += '<option ' + selected + ' value="' + data[key].name + '">' + data[key].name + '</option>';
            });
            if (composantName != '') {
                if (composantGroupe != '') {
                    getGroup(composantName, my_cmd);
                } else {
                    $(my_cmd + ' .SelectComposantGroup').attr('disabled', 'disabled'); /*readesactive les group*/
                }
                if (composantClass != '') {
                    getClass(composantName, composantGroupe, my_cmd, '');
                }
                if (composantClass2 != '') {
                    getClass(composantName, composantGroupe, my_cmd, '2');
                }
            }
            $(my_cmd + ' .SelectComposantName').append(selType);
        }
    });
}
jeedom.cmd.availableAlertType = function () {

    selAlertType = '<select  style="width:60px" class="SelectAlertType cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="AlertType">';
    selAlertType += '<option value=""></option>';
    selAlertType += '<option value="supp">></option>';
    selAlertType += '<option value="inf"><</option>';
    selAlertType += '<option value="egal">=</option>';
    selAlertType += '</select>';
    return selAlertType;
};

$('body').delegate('.cmd .cmdAction[data-action=configurebus]', 'click', function () {

    var id_cmd = $(this).parent().parent().data('cmd_id');
    var num_cmd = $(this).parent().attr('id');
    var class2 = $(this).parent().find('.SelectComposantClass').val();
    var instanceId = $(this).parent().parent().find('.cmdAttr[data-l1key=configuration][data-l2key=instanceId]').val();
    var name = $(this).parent().parent().find('.cmdAttr[data-l1key=name]').val();

    getAdresse(class2, instanceId, name);

});

$('body').delegate('.cmd .cmdAttr[data-l2key=mail_error]', 'change', function () {


    if ($(this).value() == 1 && pluginEmail == 1) {
        $('#div_' + $(this).attr('name')).show();
        $('#div_' + $(this).attr('name')).removeClass('hide');
    } else {
        $('#div_' + $(this).attr('name')).hide();
        $('#div_' + $(this).attr('name')).addClass('hide');

    }
});

jeedom.cmd.availablecomposants = function (nb_cmd) {
    getComposants(nb_cmd);
}

function getMarket(id) {
    $('#md_modal2').dialog({ title: "{{Market Jeedom}}" });
    $('#md_modal2').load('index.php?v=d&modal=market.display&type=plugin&id=' + id).dialog('open');

}

function addCmdToTable(_cmd) {
    var newcmd = false;
    if (!isset(_cmd)) {
        var _cmd = { configuration: {} };
        newcmd = true;
    }

    var nb_cmd = $('.cmd').length + 1;

    var tr = '<tr id="tr_cmd_' + nb_cmd + '" class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
    tr += '<td>';

    tr += '<div class="row">';
    tr += '<div class="col-lg-6">';
    tr += '<a class="cmdAction btn btn-default btn-sm" data-l1key="chooseIcon"><i class="fa fa-flag"></i> Icone</a>';
    tr += '<span class="cmdAttr" data-l1key="display" data-l2key="icon" style="margin-left : 10px;"></span>';
    tr += '</div>';
    tr += '<div class="col-lg-6">';
    tr += '<input class="cmdAttr form-control input-sm" data-l1key="name">';
    tr += '</div>';
    tr += '</div>';

    tr += '<td >';
    tr += '<span class="type" type="' + init(_cmd.type) + '">' + jeedom.cmd.availableType() + '</span>';
    tr += '<span class="subType" subType="' + init(_cmd.subType) + '"></span>';
    tr += '<input class="cmdAttr form-control input-sm" data-l1key="id" style="display : none;">';
    tr += '</td>';


    tr += '<select class="cmdAttr form-control tooltips input-sm" data-l1key="value" style="display : none;margin-top : 5px;" title="{{La valeur de la commande vaut par defaut la commande}}">';
    tr += '<option value="">Aucune</option>';
    tr += '</select>';
    tr += '</td>';
    tr += '<td ><input class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="instanceId" value="0">';
    tr += 'Valeur si action :<input class="cmdAttr form-control input-sm" data-l1key="configuration" placeholder="valeur" data-l2key="value" >';

    tr += '</td>';
    tr += '<td  id="cmd_' + nb_cmd + '">';
    tr += '<span class="class selected_composantName" composantName="' + init(_cmd.configuration.composantName) + '">';
    tr += '<select  onchange="SelectComposantName(' + nb_cmd + ')" style="float:left;width : 200px; margin-bottom : 3px;"  class="SelectComposantName cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="composantName">';
    tr += jeedom.cmd.availablecomposants(nb_cmd);
    tr += '</select></span>';
    tr += '<span class="class selected_composantGroup" composantGroup="' + init(_cmd.configuration.composantGroup) + '"><select onchange="SelectComposantGroup(' + nb_cmd + ')" style="float:left;width : 200px; margin-bottom : 3px;"  class="SelectComposantGroup cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="composantGroup">';
    tr += '</select></span>';
    tr += '<span class="class selected_composantClass"  composantclass="' + init(_cmd.configuration.composantClass) + '"><select onchange="SelectComposantClass(' + nb_cmd + ')" style="float:left;width : 200px; margin-bottom : 3px;"  class="SelectComposantClass cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="composantClass">';
    tr += '</select>';
    tr += '</span>';
    tr += '<input style="margin-bottom:3px;width: 158px; float: left;" class="adresse cmdAttr form-control input-sm" data-l1key="configuration" placeholder="code composant" data-l2key="adresse" >' +
        '<a style="float: right;width: 37px;height: 31px;line-height: 27px;" class="btn btn-default btn-xs cmdAction " data-action="configurebus"><i class="fa fa-cogs"></i></a>';
    tr += '<span class="class selected_composantClass2"  composantclass2="' + init(_cmd.configuration.composantClass2) + '">' +
        '<select onchange="SelectComposantClass2(' + nb_cmd + ')" style="float:left;width : 200px; margin-bottom : 3px;"  class="SelectComposantClass2 cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="composantClass2">';
    tr += '</select>';
    tr += '</span>';

    tr += '</td>';
    tr += '<td >';

    tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="calibrer" title="{{Ajouter ou soustraire à la valeur}}" >';
    tr += '</td>';
    tr += '<td>';
    tr += '<span>Historiser: <input type="checkbox" class=" cmdAttr" data-label-text="{{Historiser}}" data-l1key="isHistorized" /> <br/></span>';
    tr += '<span>Afficher: <input type="checkbox" class=" cmdAttr" data-label-text="{{Afficher}}" data-l1key="isVisible" checked/> <br/></span>';
    tr += '<span >Inverser: <input type="checkbox" class=" cmdAttr" data-label-text="{{Inverser}}" data-l1key="display" data-l2key="invertBinary" /> <br/></span>';


    tr += '<span>Mail: <input type="checkbox" name="mail_alert_' + nb_cmd + '" class=" cmdAttr"   data-label-text="{{Mail}}" data-l1key="configuration" data-l2key="mail_error"/><br/></span>';
    tr += '<div class="installmail">Merci d\'installer le plugin <a class="link_mail" href="javascript:getMarket(22)">E-mail</a></div>';
    tr += '<div class="param_mail" id="div_mail_alert_' + nb_cmd + '">Lorsque la valeur est :  <br>';
    tr += '<span style="float:left;" class="AlertType" AlertType="' + init(_cmd.configuration.AlertType) + '">' + jeedom.cmd.availableAlertType() + '</span>';
    tr += '<span style="float:left;margin : 5px 5px 0px 5px">à</span><span style="float:left;"> <input  class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="value_mail"   style="width:60px;"></span>';

    tr += '<div class="view_mail" id="select_mail_alert_' + nb_cmd + '">';
    tr += ' <div style="clear:both;padding-top: 4px;">Mail : <br>';
    tr += '<span class="class selected_MailTemplate" MailTemplate="' + init(_cmd.configuration.MailTemplate) + '">';
    tr += '<select   style="margin-top: 3px;" class="SelectMailTemplate cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="MailTemplate">';
    tr += '</select></span>';
    tr += '</div>';
    tr += ' <div style="clear:both;float:left;padding-top: 4px;line-height: 27px;">Alerter toute les : ';
    tr += '<span style="float:right;"> <input  class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="time_mail"   style="float:left;width:60px;">min</span>';
    tr += '</div>';


    tr += '</td>';
    tr += '<td>';

    tr += '<input class="cmdAttr form-control tooltips input-sm" data-l1key="unite"  style="width : 100px;" placeholder="Unité" title="{{Unité}}">';
    tr += '<input class="tooltips cmdAttr form-control input-sm " data-l1key="configuration" data-l2key="minValue" placeholder="{{Min}}" title="{{Min}}" style="margin-top : 5px;margin-top: 5px;width: 40px;float: left;margin-right: 5px;margin-bottom: 5px;"> ';
    tr += '<input class="tooltips cmdAttr form-control input-sm " data-l1key="configuration" data-l2key="maxValue" placeholder="{{Max}}" title="{{Max}}" style="margin-top : 5px;width: 40px;float: left;">';
    tr += '<input  class="tooltips cmdAttr form-control input-sm " data-l1key="configuration" data-l2key="decimal" placeholder="{{Nb décimal}}" title="{{Nb décimal}}" style="margin-top : 5px;">';
    tr += '</td>';
    tr += '<td>';
    if (is_numeric(_cmd.id)) {
        tr += '<a class="btn btn-default btn-xs cmdAction " data-action="configure"><i class="fa fa-cogs"></i></a> ';
        tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i> {{Tester}}</a>';
    }
    tr += '<i class="fa fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i></td>';
    tr += '</tr>';
    $('#table_cmd tbody').append(tr);
    var tr = $('#table_cmd tbody tr:last');



    jeedom.eqLogic.builSelectCmd({
        id: $(".li_eqLogic.active").attr('data-eqLogic_id'),
        filter: { type: 'info' },
        error: function (error) {
            $('#div_alert').showAlert({ message: error.message, level: 'danger' });
        },
        success: function (result) {
            tr.find('.cmdAttr[data-l1key=value]').append(result);
            tr.setValues(_cmd, '.cmdAttr');
            jeedom.cmd.changeType(tr, init(_cmd.subType));

            if (pluginEmail == 1) {
                $('.installmail').hide();
            } else {
                $('.installmail').hide();
                if (!pluginEmail)
                    $('.installmail').show();

                $('.view_mail').hide();
                $('.param_mail').hide();
            }
            if (newcmd) {
                $('#div_mail_alert_' + nb_cmd).hide();
                $('#div_mail_alert_' + nb_cmd).addClass('hide');
            } else {
                if (typeof _cmd.configuration.mail_error == "undefined") {
                    $('#div_mail_alert_' + nb_cmd).hide();
                    $('#div_mail_alert_' + nb_cmd).addClass('hide');
                } else if (_cmd.configuration.mail_error == 0) {
                    $('#div_mail_alert_' + nb_cmd).hide();
                    $('#div_mail_alert_' + nb_cmd).addClass('hide');
                }
            }

            jeedom.cmd.availableMailTemplate(nb_cmd, pluginEmail);
        }
    });
}