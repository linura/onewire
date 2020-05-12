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
    throw new Exception('{{401 - Accès non autorisé}}');
}
if (init('id') == '') {
    throw new Exception('{{EqLogic ID ne peut etre vide}}');
}
$eqLogic = eqLogic::byId(init('id'));
if (!is_object($eqLogic)) {
    throw new Exception('{{EqLogic non trouvé}}');
}
$eqLogic = onewire::byId(init('id'));
$cmd = $eqLogic->getCmd(); 
if (count($cmd) > 0)
    $cmd = $cmd[0];

// $class = onewireCmd::getclass($cmd->getConfiguration('composantName'), $cmd->getConfiguration('composantGroup', false), false);
$class = onewireCmd::getclass($cmd->getConfiguration('composantName'), $cmd->getConfiguration('composantGroup',false), false);
/*TODO*/
/*$lebtemp = $cmd->getConfiguration('composantName');
echo '<script type = "text/javascript"> alert("composant name '. $lebtemp .'");</script>';
$lebtemp = $cmd->getConfiguration('composantGroup');
echo '<script type = "text/javascript"> alert("composant groupe '. $lebtemp .'");</script>';*/
$select = '<option>choisir</option>';
foreach ($class as $c => $cl) {
    foreach($cl as $val){
        $select .= '<option value="' . $val . '">' . $val . '</option>';
    }
    //$select .= '<option value="' . $cl[0] . '">' . $cl[0] . '</option>';
/*TODO*/   
 /*   echo '<script type = "text/javascript"> alert("in class C '. $c .'");</script>';
    echo '<script type = "text/javascript"> alert("in class tab 0 '. $cl[0] .'");</script>';
    echo '<script type = "text/javascript"> alert("in class tab 1 '. $cl[1] .'");</script>';
    echo '<script type = "text/javascript"> alert("in class tab 2 '. $cl[2] .'");</script>';*/
}

sendVarToJS('configureDeviceId', init('id'));

$sameDevices = array();
?>
<div id='div_configureDeviceAlert' style="display: none;"></div>
<ul class="nav nav-tabs" role="tablist">
    <li class="active"><a href="#tab_general" role="tab" data-toggle="tab">{{Générale}}</a></li>
    <li><a href="#tab_history" role="tab" data-toggle="tab">{{Historique}}</a></li>

</ul>
<div class="tab-content">
    <div class="tab-pane active" id="tab_general"><br />

        <legend>{{Informations}} </legend>
        <div id='div_configureDeviceAlert' style="display: none;"></div>
        <form class="form-horizontal">
            <fieldset>
                <input type="hidden" class="onewireParameters form-control" data-l2key="sensor_id" value="<?php echo $cmd->getConfiguration('instanceId') ?>" />
                <div id="div_configureDeviceParameters">
                    <div class="form-group alert alert-warning">
                        <label class="col-lg-2 control-label tooltips">{{Ecrire paramètre sur }}<?php echo $cmd->getConfiguration('composantName') ?></label>
                        <div class="col-lg-2">
                            <select id="in_parametersId" class="onewireParameters form-control" data-l2key="in_parametersId">
                                <?php
                                echo $select;
                                ?>
                            </select>
                        </div>
                        <label class="col-lg-1 control-label tooltips">{{Valeur}}</label>
                        <div class="col-lg-1">
                            <input class="onewireParameters form-control" data-l2key="value" />

                        </div>
                        <div class="col-lg-3">
                            <a class="btn btn-success pull-right" style="color : white;" id="bt_configureDeviceSendGeneric"><i class="fa fa-check"></i> {{Appliquer}}</a>
                        </div>
                    </div>
                    <div class="form-group alert alert-success">
                        <label class="col-lg-2 control-label tooltips">{{Lire paramètre sur }} <?php echo $cmd->getConfiguration('composantName') ?></label>

                        <div class="col-lg-2">
                            <select id="in_parametersReadId" class="onewireParameters form-control" data-l2key="in_parametersReadId">
                                <?php
                                echo $select;
                                ?>
                            </select>

                        </div>

                        <label class="col-lg-1 control-label tooltips">{{Valeur}}</label>
                            <div class="col-lg-1  control-label " id="view_readparameter">
                        </div>
                        <div class="col-lg-3">
                            <a class="btn btn-warning pull-right bt_configureReadParameter" style="color : white;" data-force="1"><i class="fa fa-refresh"></i> {{Demander}}</a>
                        </div>
                    </div>
                </div>
            </fieldset>
        </form>

    </div>
    <div class="tab-pane " id="tab_history"><br />
        <legend>{{Historique des valeurs demandées ou envoyés}} </legend>
        <div id='div_configureDeviceAlerthistory' style="display: none;"></div>

        <div class="col-lg-10 eqLogic" style="border-left: solid 1px #EEE; padding-left: 25px;">
            <table class="table table-condensed table-bordered" id="table_send_<?php echo $cmd->getConfiguration('instanceId') ?>">
                <thead>
                    <tr>
                        <th>{{Type}}</th>
                        <th>{{ID 1Wire}}</th>
                        <th>{{Class}}</th>
                        <th>{{Valeur}}</th>
                        <th>{{Date}}</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    $result = onewirecmd::getHistoryCall($cmd->getConfiguration('instanceId'));
                    if ($result) {
                        foreach ($result as $key => $val) {
                            $txt = '  <tr><td>' . ($val['history_type'] == 'send' ? ' <i class="fa fa-arrow-right"></i>' : ' <i class="fa fa-arrow-left"></i>') . '</td>';
                            $txt .= '<td>' . $val['sensor_id'] . '</td>';
                            $txt .= '<td>' . $val['sensor_class'] . '</td>';
                            $txt .= '<td>' . $val['sensor_value'] . '</td>';
                            $txt .= '<td>' . $val['date_add'] . '</td></tr>';
                            echo $txt;
                        }
                    }
                    ?>

                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    initTooltips();
    $('#bt_configureDeviceSendGeneric').on('click', function() {
        var sensor_id = $('.onewireParameters[data-l2key=sensor_id]').val();
        var param_id = $('.onewireParameters[data-l2key=in_parametersId]').val();
        var param_value = $('.onewireParameters[data-l2key=value]').val();
        var eq_id = $('.li_eqLogic.active').attr('data-eqLogic_id');


        if (param_id != '' && param_value != '' && sensor_id != '' && eq_id != undefined)
            sendParameter(param_id, param_value, sensor_id, eq_id)
        else
            $('#div_configureDeviceAlert').showAlert({
                message: '{{Erreur les parametres ne sont pas envoyés, merci de remplir tous les champs ou de selectionner un equipement .}}',
                level: 'danger'
            });

    });

    $('.bt_configureReadParameter').on('click', function() {
        var sensor_id = $('.onewireParameters[data-l2key=sensor_id]').val();
        var sensor_class = $('.onewireParameters[data-l2key=in_parametersReadId]').val();
        /*TODO*/
      /*  window.alert("sensor id " + sensor_id);
        window.alert("sensor class " + sensor_class);*/
        if (sensor_class != '' && sensor_id != '')
            configureDeviceLoad(sensor_id, sensor_class);
        else
            $('#div_configureDeviceAlert').showAlert({
                message: '{{Erreur les parametres ne sont pas envoyés, merci de remplir tous les champs .}}',
                level: 'danger'
            });
    });

    function configureDeviceLoad(sensor_id, sensor_class) {
        $.ajax({ // fonction permettant de faire de l'ajax
            type: "POST", // methode de transmission des données au fichier php
            url: "plugins/onewire/core/ajax/onewire.ajax.php", // url du fichier php
            data: {
                action: "getValue",
                sensor_id: sensor_id,
                sensor_class: sensor_class
            },
            dataType: 'json',
            error: function(request, status, error) {
                handleAjaxError(request, status, error, $('#div_configureDeviceAlert'));
            },
            success: function(data) { // si l'appel a bien fonctionné
                $('#view_readparameter').html(data.valeur);
                //window.alert(data);
            }
        });
    }

    function save_history(sensor_id, sensor_class, sensor_value, history_type) {
        $.ajax({ // fonction permettant de faire de l'ajax
            type: "POST", // methode de transmission des données au fichier php
            url: "plugins/onewire/core/ajax/onewire.ajax.php", // url du fichier php
            data: {
                action: "AddSendHistory",
                sensor_class: sensor_class,
                sensor_id: sensor_id,
                history_type: history_type,
                sensor_value: sensor_value
            },
            dataType: 'json',
            error: function(request, status, error) {
                handleAjaxError(request, status, error, $('#div_configureDeviceAlert'));
            },
            success: function(data) { // si l'appel a bien fonctionné
            }
        });

    }

    function sendParameter(param, valeur, sensor_id, equ_id) {
        $.ajax({ // fonction permettant de faire de l'ajax
            type: "POST", // methode de transmission des données au fichier php
            url: "plugins/onewire/core/ajax/onewire.ajax.php", // url du fichier php
            data: {
                action: "sendparameter",
                param: param,
                sensor_id: sensor_id,
                valeur: valeur,
                eq_id: equ_id
            },
            dataType: 'json',
            error: function(request, status, error) {
                handleAjaxError(request, status, error, $('#div_configureDeviceAlert'));
            },
            success: function(data) { // si l'appel a bien fonctionné
                if (data.state != 'ok') {
                    $('#div_alert').showAlert({
                        message: data.result,
                        level: 'danger'
                    });
                    return;
                }
                $('#div_configureDeviceAlert').showAlert({
                    message: 'Parametre envoyé',
                    level: 'success'
                });
            }
        });

    }
</script>


<?php if (is_array($device) && count($device) != 0 && $eqLogic->getConfiguration('device') != '') { ?>
    <script>
        configureDeviceLoad();
    </script>
<?php } ?>
