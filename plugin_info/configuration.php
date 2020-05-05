<?php
require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
include_file('core', 'authentification', 'php');
if (!isConnect()) {
    include_file('desktop', '404', 'php');
    die();
}
?>
<!--Selectionner dans la liste deroulante pour afficher le srcipt d installation :-->
<div class="form-group col-md-12">
    <form class="form-horizontal">
        <fieldset>
            <div class=" alert alert-warning">
                <i class="fa fa-arrow-right"></i> {{Installer la dependance correspondant à votre installation.}} </div>
            <fieldset>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr class="info">
                            <th>{{Dépendances spécifiques}}</th>
                            <th>{{Commande SSH}}</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <label style="line-height: 89px;" class="col-lg-5 control-label">{{Dongle USB}}</label>
                                    <div class="col-lg-5">
                                        <img src="plugins/onewire/plugin_info/images/1-wire.png" title="Dongle Usb 1-Wire">
                                    </div>
                                </div>
                            </td>
                            <td style="line-height: 89px;">
                                <div class="col-lg-5">
                                    <a class="btn btn-info bt_installUSB"><i class="fa fa-play"></i> {{wget -q -O - http://localhost/plugins/onewire/ressources/dongle/usb_install.sh | sudo bash}}</a>
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <label style="line-height: 89px;" class="col-lg-5 control-label">{{Gpio}}</label>
                                    <div class="col-lg-5">
                                        <img src="plugins/onewire/plugin_info/images/gpio.png" title="Gpio">
                                    </div>
                                </div>
                            </td>
                            <td style="line-height: 89px;">
                                <div class="col-lg-5">
                                    <a class="btn btn-info bt_installGpio"><i class="fa fa-play"></i> {{wget -q -O - http://localhost/plugins/onewire/ressources/gpio/gpio_install.sh | sudo bash}}</a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <label style="line-height: 89px;" class="col-lg-5 control-label">{{I2c}}</label>
                                    <div class="col-lg-5">
                                        <img src="plugins/onewire/plugin_info/images/i2c.png" title="i2c">
                                    </div>
                                </div>
                            </td>
                            <td style="line-height: 89px;">
                                <div class="col-lg-5">
                                    <a class="btn btn-info bt_installI2c"><i class="fa fa-play"></i> {{wget -q -O - http://localhost/plugins/onewire/ressources/i2c/i2c_install.sh | sudo bash}}</a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </fieldset>
    </form>
    <div>
        <script>
            $('.bt_installUSB').on('click', function() {
                $.ajax({ // fonction permettant de faire de l'ajax
                    type: "POST", // methode de transmission des données au fichier php
                    url: "plugins/onewire/core/ajax/onewire.ajax.php", // url du fichier php
                    data: {
                        action: "installDongle",
                    },
                    dataType: 'json',
                    error: function(request, status, error) {
                        handleAjaxError(request, status, error);
                    },
                    success: function(data) { // si l'appel a bien fonctionné
                        if (data.state != 'ok') {
                            $('#div_alert').showAlert({
                                message: data.result,
                                level: 'danger'
                            });
                            return;
                        }
                        $('#ul_plugin .li_plugin[data-plugin_id=onewire]').click(); // recharge la page config du plugin
                        $('#div_alert').showAlert({
                            message: '{{Le Script du Dongle USB est en cours d\'installation. Voir le log onewire_dependancy }}',
                            level: 'success'
                        });
                    }
                });
            });
            $('.bt_installUSB').on('click', function() {
                $.ajax({ // fonction permettant de faire de l'ajax
                    type: "POST", // methode de transmission des données au fichier php
                    url: "plugins/onewire/core/ajax/onewire.ajax.php", // url du fichier php
                    data: {
                        action: "installGpio",
                    },
                    dataType: 'json',
                    error: function(request, status, error) {
                        handleAjaxError(request, status, error);
                    },
                    success: function(data) { // si l'appel a bien fonctionné
                        if (data.state != 'ok') {
                            $('#div_alert').showAlert({
                                message: data.result,
                                level: 'danger'
                            });
                            return;
                        }
                        $('#ul_plugin .li_plugin[data-plugin_id=onewire]').click(); // recharge la page config du plugin
                        $('#div_alert').showAlert({
                            message: '{{Le Script du Gpio est en cours d\'installation. Voir le log onewire_dependancy }}',
                            level: 'success'
                        });
                    }
                });
            });
            $('.bt_installUSB').on('click', function() {
                $.ajax({ // fonction permettant de faire de l'ajax
                    type: "POST", // methode de transmission des données au fichier php
                    url: "plugins/onewire/core/ajax/onewire.ajax.php", // url du fichier php
                    data: {
                        action: "installI2c",
                    },
                    dataType: 'json',
                    error: function(request, status, error) {
                        handleAjaxError(request, status, error);
                    },
                    success: function(data) { // si l'appel a bien fonctionné
                        if (data.state != 'ok') {
                            $('#div_alert').showAlert({
                                message: data.result,
                                level: 'danger'
                            });
                            return;
                        }
                        $('#ul_plugin .li_plugin[data-plugin_id=onewire]').click(); // recharge la page config du plugin
                        $('#div_alert').showAlert({
                            message: '{{Le Script du I2c est en cours d\'installation. Voir le log onewire_dependancy }}',
                            level: 'success'
                        });
                    }
                });
            });
        </script>
        <?php include_file('desktop', 'config', 'js', 'onewire'); ?>