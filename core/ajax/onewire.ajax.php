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

try {
    require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
    include_file('core', 'authentification', 'php');

    if (!isConnect('admin')) {
        throw new Exception('401 Unauthorized');
    }

    if (init('action') == 'reloadowserver') {
        onewire::reloadowserver();
        ajax::success();
    }

    if (init('action') == 'installDongle') {
        exec('sudo /bin/bash ' . dirname(__FILE__) . '/../../ressources/dongle/usb_install.sh >> ' . log::getPathToLog('onewire_dependancy') . ' 2>&1 &');
        ajax::success();
    }

    if (init('action') == 'installGpio') {
        exec('sudo /bin/bash ' . dirname(__FILE__) . '/../../ressources/gpio/gpio_install.sh >> ' . log::getPathToLog('onewire_dependancy') . ' 2>&1 &');
        ajax::success();
    }

    if (init('action') == 'installDI2c') {
        exec('sudo /bin/bash ' . dirname(__FILE__) . '/../../ressources/i2c/i2c_install.sh >> ' . log::getPathToLog('onewire_dependancy') . ' 2>&1 &');
        ajax::success();
    }

    if (init('action') == 'test_connexion') {

        $mode = init('mode');
        $connexion = init('connexion');
        $host = init('host');
        $port = init('port');
        $login = init('login');
        $pass = init('pass');
        $test = onewire::test_connexion($mode, $connexion, $host, $port, $login, $pass);
        if ($test === true) {
            ajax::success();
        } else {
            ajax::error($test);
        }
    }


    if (init('action') == 'stopowserver') {
        onewire::stopowserver();
        ajax::success();
    }


    if (init('action') == 'updateSql') {
        onewire::updateSql();
        ajax::success();
    }

    if (init('action') == 'sendparameter') {
        $param = init('param');
        $sensor_id =  init('sensor_id');
        $valeur = init('valeur');
        $eq = cmd::byId(init('eq_id'));
        onewireCmd::AjaxsendParameter($param, $sensor_id, $valeur, $eq);
        ajax::success();
    }

    if (init('action') == 'getValue') {
        $sensor_class =  init('sensor_class');
        $cmd = cmd::byId(init('sensor_id'));
        //return $cmd->getValue(true);
        return cmd::getValue($cmd);
    }
    if (init('action') == 'AddSendHistory') {
        $sensor_class =  init('sensor_class');
        $sensor_id =  init('sensor_id');
        $history_type =  init('history_type');
        $sensor_value =  init('sensor_value');
        return onewireCmd::AddSendHistory($sensor_id, $sensor_class, $sensor_value, $history_type);
    }



    if (init('action') == 'getComposants') {

        return onewireCmd::getComposants();
    }


    if (init('action') == 'isgroup') {
        $name =  init('name');
        return onewireCmd::getIsgroup($name);
    }

    if (init('action') == 'getclass') {
        $name =  init('name', false);
        $group =  init('group');
        return onewireCmd::getclass($name, $group);
    }

    if (init('action') == 'getgroup') {
        $name =  init('name');
        return onewireCmd::getgroup($name);
    }

    throw new Exception('Aucune methode correspondante');
    /*     * *********Catch exeption*************** */
} catch (Exception $e) {
    ajax::error(displayExeption($e), $e->getCode());
}
