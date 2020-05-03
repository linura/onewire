<?php

//ini_set('display_errors', 1);
//error_reporting(E_ALL);
if (!isConnect('admin')) {
    throw new Exception('401 Unauthorized');
}

$tab_sondes = array();
$tab_conposant = array();
$tab_host = array();

$id_equipement = init('eqLogic',false);

$class2 = init('class2',false);
$instanceId = init('instanceId',false);

$eqLogics = ($id_equipement!==false  ? array(eqLogic::byId($id_equipement)): eqLogic::byType('onewire')) ;
/*Recuperation des composants deja en base*/

foreach ($eqLogics as $eqLogic){
	$connexion = ($eqLogic->getConfiguration('onewire_connexion') != '' ? $eqLogic->getConfiguration('onewire_connexion')  : 'local');
	$ip = ($eqLogic->getConfiguration('onewire_addressip') != '' && $connexion != 'local' ? $eqLogic->getConfiguration('onewire_addressip')  : 'localhost');
		foreach ($eqLogic->getCmd('info') as $cmd) {
			$instance_id = 	$cmd->getConfiguration('instanceId');
			$tab_conposant[$instance_id]['id'] = $cmd->getConfiguration('instanceId');//		print $cmd->getConfiguration('instanceId'). ' existe';
			$tab_conposant[$instance_id]['cmdvisible'] = $eqLogic->getIsVisible();
			$tab_conposant[$instance_id]['eqvisible'] = $cmd->getIsVisible();
			$tab_conposant[$instance_id]['actif'] = $eqLogic->getIsEnable();
			$tab_conposant[$instance_id]['eqnom'] = $eqLogic->getName();
			$tab_conposant[$instance_id]['cmdnom'] = $cmd->getName();
		}
		$sondes[] = $tab_conposant;
}
$i=0;
foreach (eqLogic::byType('onewire') as $eqLogic){

	$ht = $eqLogic->getConfiguration('onewire_addressip');
	if(!in_array($ht,$tab_host)){

	$mode = ($eqLogic->getConfiguration('onewire_mode') != '' ? $eqLogic->getConfiguration('onewire_mode')  : 'gpio');
	$user = ($eqLogic->getConfiguration('onewire_user') != '' ? $eqLogic->getConfiguration('onewire_user')  : '');
	$pass =($eqLogic->getConfiguration('onewire_password') != '' ? $eqLogic->getConfiguration('onewire_password')  : '');
	$sftp = ($eqLogic->getConfiguration('onewire_mode') != 'owfs' ? true  : false);
	$connexion = ($eqLogic->getConfiguration('onewire_connexion') != '' ? $eqLogic->getConfiguration('onewire_connexion')  : 'local');
	$port = ($eqLogic->getConfiguration('onewire_portssh') != '' && $connexion != 'local'  ? $eqLogic->getConfiguration('onewire_portssh')  : ($mode=='gpio' ? '22' : '4304'));
	$ip = ($eqLogic->getConfiguration('onewire_addressip') != '' && $connexion != 'local' ? $eqLogic->getConfiguration('onewire_addressip')  : 'localhost');
	$tab_host[] = $ht;

	$connect = onewire::test_connexion($mode,$connexion,$ip,$port,$user,$pass,true);

	//$connect['code'] = false;
	if($connect['code']!==true){
	        echo '<div class="ui-dialog-title">'.$eqLogic->getHumanName().'</div>';
			echo '<div style="clear:both">Erreur de connection au '.$connexion.' <br>';
			echo 'Erreur : '.$connect['result'].' <br>';
			echo 'Mode :  '.$mode.'<br>';
			echo 'Connexion : '.$connexion.'<br>';
			echo 'Host : '.$ip.'<br>';
			echo 'Port : '.$port.'<br>';
			echo 'Identifiant : '.$user.'<br></div><hr>';
	}else{
        echo '<div class="ui-dialog-title">'.$eqLogic->getHumanName().'</div>';
		$folder = ($mode =='gpio' && $connexion =='distant' ? 'ssh2.sftp://'.$connect['result'].'/sys/bus/w1/devices' : '/sys/bus/w1/devices');
	}
	/*Affichage composant bus class2*/
	if($instanceId)
		$folder .= '/'.$instanceId.'/'.$class2;

	if($mode =='gpio'){
		$dossier = opendir($folder);
		$composant = array();
		while (false !== ($file = readdir($dossier))) {
			if ($file != '.' && $file != '..') {
				$tab_sondes[] = $file;
			}
		}
	}else{

			/*LISTE DES COMPOSANTS OWSERVER */
			include_file('3rdparty', 'ownet/ownet.class', 'php','onewire');
			log::add('onewire', 'info', 'Connexion tcp://'.$ip.':'.$port);
			$ow = new OWNet("tcp://".$ip.":".$port);

			/*Affichage composant bus class2*/
			if($instanceId)
				$folder = '/'.$instanceId.'/'.$class2.'/';
			else
				$folder = '/';

			$ow_dir = $ow->dir($folder);

			if(count($ow_dir)>0){
			    foreach($ow_dir as $sondes => $sonde){
			        if($sondes == 'data'){
			            if($sonde!=''){
							$tab_sondes = explode(',',str_replace("/","",$sonde));
			            }
			        }
			    }
			}
	}

		/********************************************************************/
		if(count($tab_sondes)>0){

		    $txt = '
		                <span id="ui-id-3" class="ui-dialog-title">'.$eqLogic->getHumanName().'</span>
						<table id="table_owfs" class="table table-bordered table-condensed">
		                <thead><tr>

		                ';

           $txt .= ' <th  style="text-align:center">{{Host}} : '.$ip.'</th>
				            <th style="text-align:center">{{port}} : '.$port.'</th>
				            <th style="text-align:center">{{Mode}} : '.$mode.'</th>
				            <th  style="text-align:center">{{Connexion}} : '.$connexion.'</th>
				            <th  style="text-align:center">{{Identifiant}} : '.$user.'</th>
				            <th  style="text-align:center"></th>
				              <th  style="text-align:center"></th>
				             </tr><tr>



		                <tr>
		                    <th>{{Liste des fichiers OWFS}}</th>';
		                 if(count($tab_sondes)>0){
		                   $txt .= ' <th  style="text-align:center">{{Existe}}</th>
		                                <th style="text-align:center">{{Equipement}}</th>
		                                <th style="text-align:center">{{Commande}}</th>
		                                <th  style="text-align:center">{{Actif}}</th>
		                                <th  style="text-align:center">{{Eq Visible}}</th>
		                                <th  style="text-align:center">{{Cmd Visible}}</th>';
		                  }
		               $txt .= ' </tr>
		                </thead>
		                <tbody>';
		                foreach($tab_sondes as $ss => $s){
		                    $txt .= '<tr><td>'.$s.' </td>';
		                           if(count($tab_sondes)>0){
										if(array_key_exists($s,$tab_conposant)){
											$txt .='<td style="text-align:center"><span class="label label-success">Oui</span></td>';
											$txt .='<td style="text-align:center">'.$tab_conposant[$s]['eqnom'].'</td>';
											$txt .='<td style="text-align:center">'.$tab_conposant[$s]['cmdnom'].'</td>';
											$txt .='<td style="text-align:center">'.((int)$tab_conposant[$s]['actif']>0 ? '<span class="label label-success">Oui</span>' : '<span class="label label-danger">Non</span>').'</td>';
											$txt .='<td style="text-align:center" >'.((int)$tab_conposant[$s]['eqvisible']>0 ? '<span class="label label-success">Oui</span>' : '<span class="label label-danger">Non</span>').'</td>';
											$txt .='<td style="text-align:center" >'.((int)$tab_conposant[$s]['cmdvisible']>0 ? '<span class="label label-success">Oui</span>' : '<span class="label label-danger">Non</span>').'</td>';
											}
										else{
											$txt .= '<td style="text-align:center"><span class="label label-danger">Non</span></td>';
											$txt .= '<td style="text-align:center"><span class="label label-danger">Aucun</span></td>';
											$txt .= '<td style="text-align:center"><span class="label label-danger">Aucune</span></td>';
											$txt .= '<td style="text-align:center"><span class="label label-danger">Non</span></td>';
											$txt .= '<td style="text-align:center"><span class="label label-danger">Non</span></td>';
											$txt .= '<td style="text-align:center"><span class="label label-danger">Non</span></td>';
										}
		                           }

		                        $txt .= '</tr>';
		                }
		                $txt .='
		                </tbody>
		            </table>';
		    echo $txt;

		}else{
		    echo '<br><br><br><br>Aucune sonde trouvée sur votre bus : '.$folder.'<br>';
		    echo 'Verifier votre host : '.$ip.'<br>';
		    echo 'Verifier votre port : '.$port.'<br>';

		    echo 'il y a '.count($ow_dir).' élément';
		    echo '<hr><br><br>';
		}
	}
}







//print_r($ow->read("/10.E8C1C9000800/temperature"));
//print_r($ow->presence("/10.E8C1C9000800"));
//print_r($ow->set("/10.E8C1C9000800/temphigh",35)); // any value will be converted to string by fwrite function or socket_write




