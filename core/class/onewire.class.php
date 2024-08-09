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

/*LEBANSAIS C 07/05/2020 
*		- modification de TypeGPIO_light pour afficher une erreur si le composant n'est pas sur le bus ou ne repond pas
*		- modification de execute pour afficher un message dans le centre de message en cas de valeur incorecte
*/

/* * *************sudo /etc/init.d/owserver restart**************Includes********************************* */
require_once dirname(__FILE__) . '/../../core/php/onewire.inc.php';


class onewire extends eqLogic
{



	public static function dependancy_info()
	{
		$return = array();
		$return['log'] = 'gpio_dependance';
		$return['progress_file'] = '/tmp/dependancy_gpio_in_progress';
		$return['state'] = (self::InstallationOk()) ? 'ok' : 'nok';
		return $return;
	}

	public static function dependancy_install()
	{
		if (file_exists('/tmp/dependancy_gpio_in_progress')) {
			return;
		}

		log::remove(__CLASS__ . '_dependance');
		return array('script' => dirname(__FILE__) . '/../../ressources/installv4.sh ' . '/tmp/installation_gpio_in_progress', 'log' => log::getPathToLog(__CLASS__ . '_dependance'));
	}

	public static function InstallationOk()
	{
		if ((int) shell_exec("gpio -v | grep 'gpio version' | cut -c15-") == 0) {
			return false;
		}
		return true;
	}



	public static function dongle_install()
	{
		if (file_exists('/tmp/dependances_onewire_en_cours')) return;	// Install déja en cours
		log::remove('onewire_update');
		exec('sudo /bin/bash ' . dirname(__FILE__) . '/../../ressources/onewire_install.sh >> ' . log::getPathToLog('onewire_update') . ' 2>&1 &');
	}


	public function event()
	{
		foreach (eqLogic::byType('onewire') as $eqLogic) {
			if ($eqLogic->getId() == init('id')) {
				$eqLogic->scan();
			}
		}
	}


	public static function cron()
	{
		foreach (eqLogic::byType('onewire') as $eqLogic) {
			$autorefresh = $eqLogic->getConfiguration('autorefresh');
			$autorefresh = ($autorefresh == "" ? '* * * * * *' : $autorefresh);
			if ($eqLogic->getIsEnable() == 1 && $autorefresh != '') {
				try {
					$c = new Cron\CronExpression($autorefresh, new Cron\FieldFactory);
					if ($c->isDue()) {
						try {
							foreach ($eqLogic->getCmd('info') as $cmd) {

								log::add('monhistory', 'info', 'Cron lancé');
								$value = $cmd->formatValue($cmd->execute());

								if ($cmd->execCmd(null, 2) != $value) {

									$cmd->event($value);
								}
							}
						} catch (Exception $exc) {
							log::add('script', 'error', __('Erreur pour ', __FILE__) . $eqLogic->getHumanName() . ' : ' . $exc->getMessage());
						}
					}
				} catch (Exception $exc) {
					log::add('script', 'error', __('Expression cron non valide pour ', __FILE__) . $eqLogic->getHumanName() . ' : ' . $autorefresh);
				}
			}
		}
	}

	public static function test_connexion($mode = false, $connexion = false, $host = false, $port = false, $login = false, $pass = false, $rsftp = false)
	{
		if ($connexion == 'distant') {
			/*DISTANT GPIO*/
			if ($mode == 'gpio') {
				if (!$connexion = ssh2_connect($host, $port)) {
					$error = 'Erreur de connexion GPIO verifier le host (' . $host . ') ainsi que votre port (' . $port . ')';
					log::add('onewire', 'error', $error);
					if ($rsftp)
						return array('code' => false, 'result' => $error);
					else
						return $error;
				} else {
					if (!@ssh2_auth_password($connexion, $login, $pass)) {
						$error = 'Erreur d authentification GPIO verifier le login (' . $login . ') ainsi que votre mdp ';
						log::add('onewire', 'error', $error);
						if ($rsftp)
							return array('code' => false, 'result' => $error);
						else
							return $error;
					}
					if (!$sftp = ssh2_sftp($connexion)) {
						$error = 'Connection SFTP impossible au serveur distant verifier les droits';
						log::add('onewire', 'error', $error);
						if ($rsftp)
							return array('code' => false, 'result' => $error);
						else
							return $error;
					} else {
						if ($rsftp)
							return array('code' => true, 'result' => $sftp);
					}
				}
				/* DISTANT OWFS */
			} else {
				if (!$ow = new OWNet("tcp://" . $host . ":" . $port)) {
					$error = 'Erreur de connexion OWFS  verifier le host (' . $host . ') ainsi que votre port (' . $port . ')';
					if ($rsftp)
						return array('code' => false, 'result' => $error);
					else
						return $error;
				} else {
					if (!$ow->isconnecte('tcp')) {
						$error = 'Erreur de connexion OWFS  verifier le host (' . $host . ') ainsi que votre port (' . $port . ')';
						if ($rsftp)
							return array('code' => false, 'result' => $error);
						else
							return $error;
					}
				}
			}
		}
		if ($rsftp)
			return array('code' => true, 'result' => '');
		else
			return true;
	}


	public static function reloadowserver()
	{

		if ((int) jeedom::version() > 2)
			$result = exec("sh /var/www/html/plugins/onewire/ressources/reloadowserver.sh");
		else
			$result = exec("sh /usr/share/nginx/www/jeedom/plugins/onewire/ressources/reloadowserver.sh");

		log::add('onewire', 'debug', 'Relance du serveur owserver');
		if ($result == 0) {
			return false;
		}
		return true;
	}
	public static function stopowserver()
	{
		if ((int) jeedom::version() > 2)
			$result = exec("sh /var/www/html/plugins/onewire/ressources/stopowserver.sh");
		else
			$result = exec("sh /usr/share/nginx/www/jeedom/plugins/onewire/ressources/stopowserver.sh");
		log::add('onewire', 'debug', 'Arret du serveur owserver');
		if ($result == 0) {
			return false;
		}
		return true;
	}


	public static function deamon_start()
	{
		self::reloadowserver();
	}
	public static function deamon_stop()
	{
		self::stopowserver();
	}

	public static function deamon_info()
	{
		$return = array();
		$return['log'] = 'onewire';
		$type = "autre";
		$return['state'] = 'nok';
		$modeesclave = false;
		$return['launchable'] = 'nok';
		$return['launchable_message'] = __('Mode OWFS uniquement : Merci de configurer au moins 1 équipement', __FILE__);

		$eqLogics = eqLogic::byType('onewire');
		foreach ($eqLogics as $eqLogic) {
			if ($eqLogic->getConfiguration('onewire_mode') == 'owfs') {
				$return['launchable'] = 'ok';
				$type = "owfs";
				if ($eqLogic->getConfiguration('onewire_connexion') == 'distant')
					$modeesclave = true;
			}
		}


		if ($return['launchable'] == 'ok') {
			if ($modeesclave) {
				$return['launchable'] = 'nok';
				$return['launchable_message'] = __('Merci de verifier sur le maitre', __FILE__);
			} else {
				$pid_file = '/var/run/owfs/owserver.pid';
				if (file_exists($pid_file)) {
					$pid = trim(file_get_contents($pid_file));
					if (is_numeric($pid) && posix_getsid($pid)) {
						$return['state'] = 'ok';
					} else {
						shell_exec('sudo rm -rf ' . $pid_file . ' 2>&1 > /dev/null;rm -rf ' . $pid_file . ' 2>&1 > /dev/null;');
					}
				}
			}
		} else {
			if ($return['launchable'] = 'nok' && $type != 'owfs')
				$return['state'] = 'ok';
		}


		return $return;
	}


	public static function updateSql()
	{
		$sql = file_get_contents(dirname(__FILE__) . '/../../plugin_info/install_composants.sql');
		log::add('onewire', 'debug', 'Mise a jour de la table des composants');
		if (!DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW)) {
			return false;
		}
		return true;
	}
}


class onewireCmd extends cmd
{


	public function AddSendHistory($sensor_id, $sensor_class, $sensor_value, $history_type)
	{
		$sql = 'INSERT INTO
					onewire_send_history SET
						sensor_id = "' . $sensor_id . '",
						sensor_class = "' . $sensor_class . '",
						sensor_value = "' . $sensor_value . '",
						history_type = "' . $history_type . '",
						date_add = "' . date('Y-m-d H:i:m') . '"';
		$res = DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
	}


	public function getHistoryCall($sensor_id)
	{

		$sql = 'select * from 	onewire_send_history where sensor_id = "' . $sensor_id . '" ORDER BY date_add DESC ';
		$result =  DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL);

		return $result;
	}




	public function getclass($name, $group = false, $ajax = true)
	{
		$sql = 'SELECT class, class2 from onewire where name like( "' . $name . '" )' . ($group ? ' AND groupe like ("' . $group . '")  ' : '');
		$result =  DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL);
		if (!$result)
			echo 'Erreur de REquete MYSQL :' . $sql;
		else {

			$tabclass = array();
			foreach ($result as $res => &$val) {
				$string = substr($val['class'], 0, -1);
				$string = substr($string, 1);
				$tabclass = explode('|', $string);
			}

			$tabclass2 = array();
			foreach ($result as $res2 => &$val2) {
				$string2 = substr($val2['class2'], 0, -1);
				$string2 = substr($string2, 1);
				$tabclass2 = explode('|', $string2);
			}

			if ($ajax)
				echo json_encode(array('class' => $tabclass, 'class2' => $tabclass2));
			else
				return array('class' => $tabclass, 'class2' => $tabclass2);
		}
	}

	public function getgroup($name)
	{
		$sql = 'SELECT  groupe from  onewire where name like ("' . $name . '")';
		$result =  DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL);
		$tabgroup = array();
		foreach ($result as $res => &$val) {
			$tabgroup[] = $val['groupe'];
		}
		echo json_encode($tabgroup);
	}

	public function getIsgroup($name)
	{
		$sql = 'SELECT count(*) as nb  from onewire where name like ("' . $name . '") and groupe != "" and groupe is not null';
		$result =  DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
		if ($result['nb'] > 0)
			echo json_encode('ok');
		else
			echo json_encode('ko');
	}

	public function AjaxsendParameter($param, $sensor_id, $valeur, $eq)
	{

		$equipement = eqLogic::byId($eq);
		$port = ($equipement->getConfiguration('onewire_portssh') != '' ? $equipement->getConfiguration('onewire_portssh')  : '4304');
		$host = ($equipement->getConfiguration('onewire_addressip') != '' ? $equipement->getConfiguration('onewire_addressip')  : '127.0.0.1');

		include_file('3rdparty', 'ownet/ownet.class', 'php', 'onewire');
		$ow = new OWNet('tcp://' . $host . ':' . $port);
		if ($ow->presence("/" . $sensor_id)) {
			$ow->set("/" . $sensor_id . "/" . $param, $valeur); // any value will be converted to string by fwrite function or socket_write
			log::add('onewire', 'debug', 'parametre envoyé : /' . $sensor_id . '/' . $param . '--->' . $valeur);
			onewireCmd::AddSendHistory($sensor_id, $param, $valeur, 'send');
		}
	}

	public function getComposants()
	{
		$sql = 'SELECT id,name  from onewire group by name';
		$result = DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL);
		echo  json_encode($result);
	}

	public function mailAlert($obj, $value)
	{
		$plugin_email = ((int) config::byKey('active', 'mail') > 0 ? true : false);

		if ($plugin_email && (int) $this->getConfiguration('mail_error') > 0) {
			onewireCmd::OneWireSendMail($obj, $value);
		} else {
			if ((int) $this->getConfiguration('mail_error') > 0)
				log::add('onewireMail', 'info', 'Il faut installer le plugin mail  pour utiliser les mails ');
		}
	}



	function DateDiff($timestamp)
	{
		$difference = time() - $timestamp;
		$jour = floor($difference / 86400);
		// le % signifie modulo qui permet de récuperer le reste d'une division
		$reste = ($difference % 86400);
		$heure = floor($reste / 3600);
		$reste = ($difference % 3600);
		$minute = floor($reste / 60);
		return $minute;
	}


	function OneWireSendMail($obj, $value)
	{

		$equipement = $obj->getEqLogic();
		$date = new DateTime();
		$minute  = self::DateDiff($obj->getConfiguration('mail_timestamp'));
		log::add('onewireMail', 'info', 'nb minute :' . $value . ' > ' . $obj->getConfiguration('value_mail') . '  && ((int) ' . $obj->getConfiguration('mail_send') . '==0 || ( (int) ' . $obj->getConfiguration('mail_send') . '==1 && (' . $minute . ' >  ' . $obj->getConfiguration('time_mail') . ' && ' . $obj->getConfiguration('time_mail') . ' !=0 )))');
		switch ($obj->getConfiguration('AlertType')) {
			case "supp":

				if ($value > $obj->getConfiguration('value_mail')  && ((int) $obj->getConfiguration('mail_send') == 0 || ((int) $obj->getConfiguration('mail_send') == 1 && ($minute >  $obj->getConfiguration('time_mail') && $obj->getConfiguration('time_mail') != 0)))) {
					log::add('onewireMail', 'info', 'Mail configuré ');
					/*envoyer mail */
					$eqLogic_m = eqLogic::byId($obj->getConfiguration('MailTemplate'));
					foreach ($eqLogic_m->getCmd() as $cmd_email) {
						log::add('onewireMailSend', 'info', 'Mail envoyé. Composant ' . $obj->getConfiguration('instanceId') . ' ' . $equipement->getName() . ' ' . $obj->getName() . ' ' . $obj->getConfiguration('composantClass') . ' Valeur : ' . $value . ' Message :' . $eqLogic_m->getConfiguration('fromName') . '---' . $cmd_email->getName());
						$info = array('title' => $eqLogic_m->getConfiguration('fromName'), 'message' => $cmd_email->getName());
						$cmd_email->execCmd($info);
					}

					/*topé mail envoyé*/
					$this->setConfiguration('mail_send', 1);
					$this->setConfiguration('mail_timestamp', $date->getTimestamp());
					$this->save();
				} else {
					log::add('onewireMail', 'info', 'mail non envoyé :  ' . $value . ' > ' . $obj->getConfiguration('value_mail') . '  && ' . $obj->getConfiguration('mail_send'));
				}
				break;
			case "inf":
				if ($value < $obj->getConfiguration('value_mail')  && ((int) $obj->getConfiguration('mail_send') == 0 || ((int) $obj->getConfiguration('mail_send') == 1  && ($minute >  $obj->getConfiguration('time_mail') && $obj->getConfiguration('time_mail') != 0)))) {
					/*envoyer mail */
					$eqLogic_m = eqLogic::byId($obj->getConfiguration('MailTemplate'));
					foreach ($eqLogic_m->getCmd() as $cmd_email) {
						log::add('onewireMailSend', 'info', 'Mail envoyé. Composant ' . $obj->getConfiguration('instanceId') . ' ' . $equipement->getName() . ' ' . $obj->getName() . ' ' . $obj->getConfiguration('composantClass') . ' Valeur : ' . $value . ' Message :' . $eqLogic_m->getConfiguration('fromName') . '---' . $cmd_email->getName());
						$info = array('title' => $eqLogic_m->getConfiguration('fromName'), 'message' => $cmd_email->getName());
						$cmd_email->execCmd($info);
					}

					/*topé mail envoyé*/
					$obj->setConfiguration('mail_send', 1);
					$obj->setConfiguration('mail_timestamp', $date->getTimestamp());
					$obj->save();
				}
				break;
			case "egal":
				if ($value == $obj->getConfiguration('value_mail')  && ((int) $obj->getConfiguration('mail_send') == 0 || ((int) $obj->getConfiguration('mail_send') == 1  && ($minute >  $obj->getConfiguration('time_mail') && $obj->getConfiguration('time_mail') != 0)))) {
					/*envoyer mail */
					$eqLogic_m = eqLogic::byId($obj->getConfiguration('MailTemplate'));
					foreach ($eqLogic_m->getCmd() as $cmd_email) {
						log::add('onewireMailSend', 'info', 'Mail envoyé. Composant ' . $obj->getConfiguration('instanceId') . ' ' . $equipement->getName() . ' ' . $obj->getName() . ' ' . $obj->getConfiguration('composantClass') . ' Valeur : ' . $value . ' Message :' . $eqLogic_m->getConfiguration('fromName') . '---' . $cmd_email->getName());
						$info = array('title' => $eqLogic_m->getConfiguration('fromName'), 'message' => $cmd_email->getName());
						$cmd_email->execCmd($info);
					}

					/*topé mail envoyé*/
					$obj->setConfiguration('mail_send', 1);
					$obj->setConfiguration('mail_timestamp', $date->getTimestamp());
					$obj->save();
				}
				break;
		}
	}
	public function TypeGPIO_light_esclave($ajax = false)
	{

		log::add('onewire', 'debug', 'Distant->composant   trouvée : ' . $this->getConfiguration('instanceId'));

		$equipement = eqLogic::byId($this->getEqLogic_id(), 'onewire');
		$ip = ($equipement->getConfiguration('onewire_addressip') != '' ? $equipement->getConfiguration('onewire_addressip')  : 'localhost');
		$port = ($equipement->getConfiguration('onewire_portssh') != '' ? $equipement->getConfiguration('onewire_portssh')  : '22');
		$user = ($equipement->getConfiguration('onewire_user') != '' ? $equipement->getConfiguration('onewire_user')  : '');
		$pass = ($equipement->getConfiguration('onewire_password') != '' ? $equipement->getConfiguration('onewire_password')  : '');

		if (!$connection = ssh2_connect($ip, $port)) {
			log::add('onewire', 'error', 'connexion SSH KO pour ' . $this->getConfiguration('instanceId'));
			$cnx_ssh = 'KO';
		} else {
			if (!ssh2_auth_password($connection, $user, $pass)) {
				log::add('onewire', 'error', 'Authentification SSH KO pour ' . $this->getConfiguration('instanceId'));
				$cnx_ssh = 'KO';
			} else {
				$cnx_ssh = 'OK';
				log::add('onewire', 'debug', 'Authentification SSH OK pour ' . $this->getConfiguration('instanceId'));
				$sonde = "find /sys/bus/w1/devices/ -name  " . trim(str_replace(".", "-", $this->getConfiguration('instanceId'))) . "  -exec cat {}/w1_slave \\; | grep \"t=\" | awk -F \"t=\" '{print $2/1000}'";
				$output = ssh2_exec($connection, $sonde);
				stream_set_blocking($output, true);
				$temp = stream_get_contents($output);
/* TODO */		
				if ($temp === NULL || !$temp)
					message::add('onewire', 'Une sonde est en erreur. Merci de verifier le bus ou la sonde');
				if (!$temp || $temp === NULL)
					$temp = trim(exec($sonde));
				log::add('onewire', 'debug', 'TypeGPIO_light_esclave->Valeur  trouvée : ' . $temp);

				if ($ajax) {
					onewireCmd::AddSendHistory($this->getConfiguration('instanceId'), $this->getConfiguration('composantClass'), $temp, 'receive');
					echo json_encode($temp);
				} else {
					return $temp;
				}
			}
		}
	}
	public function TypeGPIO_light($ajax = false)
	{
		log::add('onewire', 'debug', 'TypeGPIO_light()-> Traitement du composant : ' . $this->getConfiguration('instanceId'));
		$sonde = "find /sys/bus/w1/devices/ -name  " . trim(str_replace(".", "-", $this->getConfiguration('instanceId'))) . "  -exec cat {}/w1_slave \\; | grep \"t=\" | awk -F \"t=\" '{print $2/1000}'";
		$temp = trim(exec($sonde));
		if (!$temp || $temp === NULL)
			$temp = trim(exec($sonde));
		log::add('onewire', 'debug', 'TypeGPIO_light->Valeur  trouvée : ' . $temp);

		if ($ajax) {
			onewireCmd::AddSendHistory($this->getConfiguration('instanceId'), $this->getConfiguration('composantClass'), $temp, 'receive');
			echo json_encode($temp);
		} else {
			if (!$temp || $temp === NULL)
/*TODO*/			
				message::add('onewire', 'Une sonde est en erreur. Merci de verifier le bus ou la sonde');
			return $temp;
		}
	}

	public function TypeOwserver($ajax = false)
	{
		$equipement = eqLogic::byId($this->getEqLogic_id(), 'onewire');
		$port = ($equipement->getConfiguration('onewire_portssh') != '' ? $equipement->getConfiguration('onewire_portssh')  : '4304');
		$host = ($equipement->getConfiguration('onewire_addressip') != '' ? $equipement->getConfiguration('onewire_addressip')  : '127.0.0.1');


		if ($this->getType() == 'info') {
			$ow = new OWNet("tcp://" . $host . ":" . $port);
			log::add('onewire', 'debug', "=================Traitement : " . $equipement->getName() . '--' . $this->getName() . "=======================");
			log::add('onewire', 'debug', "Connection :  tcp://" . OWNET_DEFAULT_HOST . ":" . OWNET_DEFAULT_PORT);
			log::add('onewire', 'debug', 'TypeOwserver()-> Traitement du composant : ' . $this->getConfiguration('instanceId'));

			$ow_presence = "/" . trim($this->getConfiguration('instanceId'));
			$ow_cmd =  "/" . trim($this->getConfiguration('instanceId')) . "/" . trim($this->getConfiguration('composantClass'));
			/*Lecture de cla class 2*/
			if ($this->getConfiguration('composantClass2', 'false') != 'false') {
				$ow_class2 = "/" . trim($this->getConfiguration('adresse')) . '/' . trim($this->getConfiguration('composantClass2'));
				log::add('onewire', 'debug', '---ow_class2 : ' . $ow_class2);
				$ow_cmd .= $ow_class2;
			}
			log::add('onewire', 'debug', "Commande envoyée : " . $ow_cmd);

			if ($ow->presence($ow_presence)) {
				log::add('onewire', 'debug', 'Le composant ' . $this->getConfiguration('instanceId') . ' existe.');

				log::add('onewire', 'debug', "Commande : " . $ow_cmd);
				$temp = trim($ow->read($ow_cmd));
				log::add('onewire', 'debug', "Valeur owread : " . $temp);

				if (!$temp || $temp === NULL) {
					$temp = trim(exec("owread " . $ow_cmd));
					log::add('onewire', 'debug', 'Valeur non trouvé test en local :  owread ' . $ow_cmd);
				}
				$i = 0;
				while (($temp === null or ($temp == 0 && $this->getSubType() != 'binary')) && $i < 10) { //or $temp==0
					usleep(1000000); // wait for 1 seconds
					$temp = trim($ow->read($ow_cmd));
					log::add('onewire', 'debug', "Toujours pas trouvé on relance Commande : owread " . $ow_cmd);

					if (!$temp || $temp === NULL or ($temp == 0 && $this->getSubType() != 'binary')) {
						log::add('onewire', 'debug', "Toujours pas trouvé essai ultime  '.$i.' Commande : owread " . $ow_cmd);
						$temp = trim(exec("owread " . $ow_cmd));
						if (!$temp || $temp === NULL or ($temp == 0 && $this->getSubType() != 'binary'))/*pour le test distant*/
							$temp = trim($ow->read($ow_cmd));
					}
					$i++;
				}
				log::add('onewire', 'debug', 'Valeur  trouvée : ' . $temp);
			} else {
				log::add('onewire', 'debug', 'Impossible de trouver : ' . $ow_presence);
				if ($ajax)
					print 'Le composant ' . $this->getConfiguration('instanceId') . ' n existe pas !!!!';
				return false;
			}

			if ($ajax) {
				onewireCmd::AddSendHistory($this->getConfiguration('instanceId'), $this->getConfiguration('composantClass'), $temp, 'receive');
				echo json_encode($temp);
			} else {
				return $temp;
			}
		} else {
		}
	}




	public function getValue($ajax = false)
	{


		$equipement = eqLogic::byId($this->getEqLogic_id(), 'onewire');
		if (!$this->getConfiguration('instanceId'))
			return;

		if ($equipement->getConfiguration('onewire_mode') != 'owfs') {
			if ($equipement->getConfiguration('onewire_connexion') == 'distant') {
				log::add('onewire', 'debug', 'MODE GPIO DISTANT (' . $equipement->getConfiguration('onewire_mode') . ')');
				return $this->TypeGPIO_light_esclave($ajax);
			} else {
				log::add('onewire', 'debug', 'MODE GPIO LOCAL (' . $equipement->getConfiguration('onewire_mode') . ')');
				return	$this->TypeGPIO_light($ajax);
			}
		} else {
			log::add('onewire', 'debug', 'MODE OWFS (' . $equipement->getConfiguration('onewire_mode') . ')');
			return onewireCmd::TypeOwserver($ajax);
		}
	}



	public function sendValue($instanceId, $class, $value)
	{

		$equipement = eqLogic::byId($this->getEqLogic_id(), 'onewire');
		$port = ($equipement->getConfiguration('onewire_portssh') != '' ? $equipement->getConfiguration('onewire_portssh')  : '4304');
		$host = ($equipement->getConfiguration('onewire_addressip') != '' ? $equipement->getConfiguration('onewire_addressip')  : '127.0.0.1');


		log::add('onewire', 'debug',   'Envoi de la valeur : ' . $this->getConfiguration('value') . ' sur l ID : ' . $this->getConfiguration('instanceId'));
		$ow = new OWNet("tcp://" . $host . ":" . $port);
		$ow->set("/" . trim($this->getConfiguration('instanceId')) . "/" . trim($this->getConfiguration('composantClass')), $this->getConfiguration('value'));
		log::add('onewire', 'debug', "/" . trim($this->getConfiguration('instanceId')) . "/" . trim($this->getConfiguration('composantClass')) . "," . $this->getConfiguration('value'));
		$temp = trim($ow->read("uncached/" . trim($this->getConfiguration('instanceId')) . "/" . trim($this->getConfiguration('composantClass'))));
		return $temp;
	}

	public  function execute($_options = array())
	{
		$equipement = eqLogic::byId($this->getEqLogic_id(), 'onewire');
		log::add('onewire', 'debug', 'Execute()-> Lecture du composant : ' . $this->getConfiguration('instanceId') . ' avec la class ' . $this->getConfiguration('composantClass'));

		if ($this->getType() != 'info') {
			log::add('onewire', 'debug', 'Type action');
			if ($equipement->getConfiguration('onewire_mode') == 'owfs') {/*Si action et mode OWFS*/
				log::add('onewire', 'debug', 'Type action mode OWFS');
				$temp =  $this->sendValue();
			} else {
				/*Si action et mode GPOI pas de commande possible*/
				log::add('onewire', 'debug', 'Type action mode GPIO');
				log::add('onewire', 'debug', 'Pas d action possible en GPIO seulement avec le dongle.');
				$temp =  $this->sendValue();
			}
		} else {
			if ($this->getEventOnly() == 0) {
				$this->setEventOnly(1);
				$this->save();
			}
			$temp = $this->getValue(false);

			if ((int) $temp == 85) {
				log::add('onewire', 'debug', 'La sonde est en erreur on ne fait rien. Merci de verifier le composant ou le cablage');
/*TODO*/		message::add('onewire', 'La sonde ' . $equipement->getName() . ' est en erreur. Merci de verifier le composant ou le cablage. Valeur lue: ' . $temp);
				return false;
			}
		}


		/*Gestion des alertes*/
		onewireCmd::MailAlert($this, $temp);
		if ($temp !== null) { //$temp!=0

			//DS2438 voir la doc mais il faut appliquer une formule */
			//Humidité réelle = (humidité relevée)/(1.0546 –0.00216*température relevée)
			if ($this->getConfiguration('composantGroup') == "Humidity sensor" && (int) substr($this->getConfiguration('instanceId'), 0, 2) == 26) {
				$temp =  $temp / (1.0546 - (0.00216 * $temp));
				log::add('onewire', 'debug', 'composnant Humidity Sensor detecter la formule est donc : Humidité réelle = (humidité relevée)/(1.0546 –0.00216*température relevée, voir la doc )');
			}


			$temp = $temp + $this->getConfiguration('calibrer', 0);
			$replace['#state#'] = $temp;
			log::add('onewire', 'debug', 'Le composnant  ' . $this->getConfiguration('instanceId') . ' a une valeur de ' . $temp . ' (#state# = ' . number_format($replace['#state#'], 2) . ')');
			$decimal = $this->getConfiguration('decimal', 0);
			if ((int) $decimal > 0) {
				$this->event(number_format($replace['#state#'], (int) $decimal));
				return  number_format($replace['#state#'], (int) $decimal);
			} else {
				$this->event($value);
				return $replace['#state#'];
			}
		} else {
			log::add('onewire', 'debug', 'Erreur du composant ' . $this->getConfiguration('instanceId') . ' la valeur est a 0 !!! ');
			return false;
		}
	}
}
