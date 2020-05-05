<?php
if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}

$plugin = plugin::byId('onewire');

$plugin_email = ((int) config::byKey('active', 'mail') > 0 ? true : false);
$listemail = array();

if ($plugin_email) {
    foreach (eqLogic::byType('mail') as $eqLogic) {
        $listemail[$eqLogic->getId()] = $eqLogic->getHumanName();
    }
}

$eqLogics = eqLogic::byType($plugin->getId());

sendVarToJS('ListEmail', $listemail);
sendVarToJS('pluginEmail', $plugin_email);
sendVarToJS('eqType', $plugin->getId());

$deamonRunning = onewire::deamon_info();
if ($deamonRunning['state'] != 'ok') {
    echo '<div class="alert alert-danger">ATTENTION LE DEMON OWFS NE TOURNE PAS !!! </div>';
}

?>
<!-- script pour les icons perso -->
<script src="https://kit.fontawesome.com/4f90f8faf4.js" crossorigin="anonymous"></script>

<div class="row row-overflow">

    <!-- Menu de gauche -->
<!-- TODO laisser pour le moment voir avec utilisateur si besoin -->
 <!--   <div class="col-lg-2 col-md-3 col-sm-4">
        <div class="bs-sidebar">
            <ul id="ul_eqLogic" class="nav nav-list bs-sidenav">
                <a class="btn btn-default eqLogicAction" style="width : 100%;margin-top : 5px;margin-bottom: 5px;" data-action="add"><i class="fa fa-plus-circle"></i> {{Ajouter}}</a>
                <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%" /></li>
                <?php
                //foreach ($eqLogics as $eqLogic) {
                //    echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $eqLogic->getId() . '"><a>' . $eqLogic->getHumanName(true) . '</a></li>';
                //}
                ?>
            </ul>
        </div>
    </div> -->

    <!-- Menu gestion -->

    <div class="col-lg-10 col-md-9 col-sm-8 eqLogicThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">
        <legend><i class="fas fa-cog"></i>{{Gestion}}</legend>
        <div class="eqLogicThumbnailContainer">

            <div class="cursor" id="bt_owfsTable" style="background-color : #ffffff; height : 130px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 200px;margin-left : 10px;">
                <center>
                    <i class="fas fa-project-diagram" style="font-size : 5em;color:indigo;"></i>
                </center>
                <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676">
                    <center>{{Afficher les composants du bus}}</center>
                </span>
            </div>


            <div class="cursor" id="bt_reload_owfs" style="background-color : #ffffff; height : 130px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;">
                <center>
                    <i class="fa fa-refresh fa-spin" style="font-size : 5em;color:#767676;"></i>
                </center>
                <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676">
                    <center>{{Relancer OWFS}}</center>
                </span>
            </div>

            <div class="cursor" id="bt_stop_owfs" style="background-color : #ffffff; height : 130px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;">
                <center>
                    <i class="fas fa-stop-circle" style="font-size : 5em;color:red;"></i>
                </center>
                <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676">
                    <center>{{Arreter OWFS}}</center>
                </span>
            </div>

            <div class="cursor" id="bt_docSpecific" style="background-color : #ffffff; height : 130px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;">
                <center>
                    <i class="fas fa-book" style="font-size : 5em;color:#767676;"></i>
                </center>
                <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676">
                    <center>{{Documentation}}</center>
                </span>
            </div>
        </div>

        <!-- Menu mes equipements -->

        <legend><br><br><br><i class="fas fa-table"></i>{{Mes équipements}}</legend>
        <div class="eqLogicThumbnailContainer">
            <div class="cursor eqLogicAction" data-action="add" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;">
                <center>
                    <i class="fas fa-plus-circle" style="font-size : 7em;color:green;"></i>
                </center>
                <!-- TODO -->
                <!-- <span style="font-size : 1.1em;position:relative; top : 23px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#BE0104"> -->
                <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#BE0104">
                    <center>Ajouter</center>
                </span>
            </div>
            <?php
            foreach ($eqLogics as $eqLogic) {
                $opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
                echo '<div class="eqLogicDisplayCard cursor ' . $opacity . '" data-eqLogic_id="' . $eqLogic->getId() . '" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
                echo "<center>";
                $mode = $eqLogic->getConfiguration("onewire_mode");
                if ($mode == "gpio") {
                    echo '<img src="plugins/onewire/plugin_info/images/gpio.png" height="110" width="110" />';
                } else if ($mode == "owfs") {
                    echo '<img src="plugins/onewire/plugin_info/images/1-wire.png" height="110" width="110" />';
                } else {
                    echo '<img src="plugins/onewire/plugin_info/images/onewire.png" height="110" width="110" />';
                }
                echo "</center>";
                echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $eqLogic->getHumanName(true, true) . '</center></span>';
                echo '</div>';
            }
            ?>
        </div>
    </div>

    <!-- configuration des équipement -->
    <!-- Liste des onglets -->
    <div class="col-lg-10 eqLogic" style="border-left: solid 1px #EEE; padding-left: 25px;display: none;">
    <!-- Bouton sauvegarder -->
    <a class="btn btn-success eqLogicAction pull-right" data-action="save"><i class="fa fa-check-circle"></i>
        {{Sauvegarder}}</a>
      <!-- Bouton Supprimer -->
      <a class="btn btn-danger eqLogicAction pull-right" data-action="remove"><i class="fa fa-minus-circle"></i>
        {{Supprimer}}</a>
      <!-- Bouton configuration avancée -->
      <a class="btn btn-default eqLogicAction pull-right" data-action="configure"><i class="fa fa-cogs"></i>
        {{Configuration avancée}}</a>
        
        <ul class="nav nav-tabs" role="tablist">
            <!-- Bouton de retour -->
            <li role="presentation"><a class="eqLogicAction cursor" aria-controls="home" role="tab" data-action="returnToThumbnailDisplay"><i class="fa fa-arrow-circle-left"></i></a>
            </li>
            <!-- Onglet "Equipement" -->
            <li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-tachometer"></i> {{Equipement}}</a></li>
            <!-- Onglet "Commandes" -->
            <li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i> {{Commandes}}</a></li>
        </ul>

        <div role="tabpanel" class="tab-pane active" id="eqlogictab">
            <div class="row">
                <div class="tab-content" style="height:calc(100%);overflow:auto;overflow-x: hidden;">
                    <div role="tabpanel" class="tab-pane active" id="eqlogictab">
                        <br />
                        <form class="form-horizontal">
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-lg-3 control-label">{{Nom de l équipement}}</label>
                                    <div class="col-lg-4">
                                        <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
                                        <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement}}" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-3 control-label">{{Objet parent}}</label>
                                    <div class="col-lg-4">
                                        <select class="eqLogicAttr form-control" data-l1key="object_id">
                                            <option value="">{{Aucun}}</option>
                                            <?php
                                            foreach (jeeObject::all() as $object) {
                                                echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-3 control-label">{{Catégorie}}</label>
                                    <div class="col-lg-3">
                                        <?php
                                        $i = 0;
                                        foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
                                            echo '{{' . $value['name'] . '}}: <input type="checkbox" class="eqLogicAttr" data-label-text="{{' . $value['name'] . '}}"  data-l1key="category" data-l2key="' . $key . '" /><br>';
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-3 control-label"></label>
                                    <div class="col-lg-5">
                                        Activer: <input type="checkbox" class=" eqLogicAttr" data-label-text="{{Activer}}" data-l1key="isEnable" checked />
                                        Visible: <input type="checkbox" class=" eqLogicAttr" data-label-text="{{visible}}" data-l1key="isVisible" checked />
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label class="col-lg-3 control-label">{{Auto-actualisation (cron)}}</label>
                                    <div class="col-lg-4">
                                        <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="autorefresh" placeholder="{{Auto-actualisation (cron)}}" />
                                    </div>
                                    <div class="col-lg-1">
                                        <i class="fa fa-question-circle cursor bt_pageHelp floatright" data-name="cronSyntaxe"></i>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label class="col-lg-3 control-label">{{Node ID}}</label>
                                    <div class="col-lg-4">
                                        <input type="text" class="eqLogicAttr form-control" data-l1key="logicalId" />
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    <div role="tabpanel" class="tab-pane active" id="eqlogictab">
                        <div class="col-sm-6">
                            <form class="form-horizontal">
                                <fieldset>
                                    <legend>{{Paramatrage}} </legend>
                                    <div class="form-group">
                                        <label class="col-lg-2 control-label">{{Mode}}</label>
                                        <div class="col-lg-4">
                                            <select id="onewire_mode" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="onewire_mode">
                                                <option value="gpio">{{GPIO}}</option>
                                                <option value="owfs">{{OWFS}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-2 control-label">{{Equipement}}</label>
                                        <div class="col-lg-4">
                                            <select id="onewire_equipement" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="onewire_equipement">
                                                <option value="mini">{{Mini}}</option>
                                                <option value="diy">{{DIY}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-2 control-label">{{Connexion}}</label>
                                        <div class="col-lg-4">
                                            <select id="onewire_equipement" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="onewire_connexion">
                                                <option value="local">{{Local}}</option>
                                                <option value="distant">{{Distant}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="connect">
                                        <div id="onewire_addressip" class="form-group">
                                            <label class="col-lg-2 control-label">{{Host}}</label>
                                            <div class="col-lg-4">
                                                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="onewire_addressip" placeholder="{{Nom de l équipement}}" />
                                            </div>
                                        </div>
                                        <div id="onewire_portssh" class="form-group">
                                            <label class="col-lg-2 control-label">{{Port}} <span id="libellessh">{{SSH}}</span></label>
                                            <div class="col-lg-4">
                                                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="onewire_portssh" placeholder="{{Port}}" />
                                            </div>
                                        </div>
                                        <div id="onewire_user" class="form-group">
                                            <label class="col-lg-2 control-label">{{Identifiant SSH}}</label>
                                            <div class="col-lg-4">
                                                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="onewire_user" placeholder="{{Login SSH}}" />
                                            </div>
                                        </div>
                                        <div id="onewire_password" class="form-group">
                                            <label class="col-lg-2 control-label">{{MDP SSH}}</label>
                                            <div class="col-lg-4">
                                                <input type="password" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="onewire_password" placeholder="{{Mot de passe SSH}}" />
                                            </div>

                                        </div>
                                        <div class="col-lg-6">
                                            <label class="col-lg-4 control-label"></label>
                                            <a class="btn btn-success eqLogicAction" style="margin-left:10px;margin-bottom: 17px;" data-action="bt_testConnexion"><i class="fa fa-plus-circle"></i> {{Tester}}</a>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="commandtab">
                        <a class="btn btn-success btn-sm cmdAction " data-action="add"><i class="fa fa-plus-circle"></i> {{Commandes}}</a>
                        <a class="btn btn-default" id="bt_configureDevice" title="Configurer"><i class="fa fa-wrench"></i></a><br /><br />
                        <table id="table_cmd" class="table table-bordered  table-condensed">
                            <thead>
                                <tr>
                                    <th style="width: 200px;">{{Nom}}</th>
                                    <th style="width: 100px;" class="">{{Type}}</th>
                                    <th style="width: 200px;" class="">{{ID 1Wire}}</th>
                                    <th style="width: 200px;" class="">{{Class}}</th>
                                    <th style="width: 80px;" class="">{{Etalonner}}</th>
                                    <th>{{Paramètres}}</th>
                                    <th style="width: 100px;">{{Options}}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
<!-- TODO -->
 <!--                   <form class="form-horizontal">
                        <fieldset>
                            <div class="form-actions">
                                <a class="btn btn-danger eqLogicAction" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
                                <a class="btn btn-success eqLogicAction" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
                            </div>
                        </fieldset>
                    </form> -->

                </div>
            </div>
        </div>
    </div>
</div>
<!-- TODO a supprimer en master -->
<!-- <SCRIPT LANGUAGE="Javascript"> window.alert(Object.entries(contextmenuitems).length.tostring()); </script> -->

<?php include_file('desktop', 'onewire', 'js', 'onewire'); ?>
<?php include_file('core', 'plugin.template', 'js'); ?>
<!-- <?php include_file('desktop', 'plugin.template.dev', 'js', 'onewire'); ?> -->

<!-- TODO : asupprimer en master -->
<!-- Ajout de deux div en ligne 306-307 pour fermer div class row et liste de onglet -->