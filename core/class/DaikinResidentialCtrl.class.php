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

/* * ***************************Includes********************************* */
require_once __DIR__  . '/../../../../core/php/core.inc.php';

class DaikinResidentialCtrl extends eqLogic {
    /*     * *************************Attributs****************************** */
    
  /*
   * Permet de définir les possibilités de personnalisation du widget (en cas d'utilisation de la fonction 'toHtml' par exemple)
   * Tableau multidimensionnel - exemple: array('custom' => true, 'custom::layout' => false)
	public static $_widgetPossibility = array();
   */
    
    /*     * ***********************Methode static*************************** */

    static public function getAllDevices()
    {
        log::add('DaikinResidentialCtrl', 'info','Getting all devices.');
        exec("/usr/bin/node ../class/functions/getDevices.js ".config::byKey('tokenSet', 'DaikinEquipmentID')." 2>1", $output, $result_code);
        log::add('DaikinResidentialCtrl', 'debug','Return code: '.$result_code);
        if ($result_code == 0) {
            $eqLogics = self::byType('DaikinResidentialCtrl', true);
            for ($i = 0; $i < count($output)-1; $i++) {
                $found = false;
                foreach ($eqLogics as $dev) {
                    if ($dev->getConfiguration('DaikinEquipmentID') == $output[$i]) {
                        $found = true;
                    }
                }

                if (! $found){
                    log::add('DaikinResidentialCtrl', 'info',"Creating new equipment for $output[$i].");
                    $newEq = new DaikinResidentialCtrl();
                    $newEq->setEqType_name('DaikinResidentialCtrl');
                    $newEq->setName($output[$i]);
                    $newEq->setConfiguration('DaikinEquipmentID', $output[$i]);
                    $newEq->setCategory('heating', 1);
                    $newEq->setIsEnable(1);
                    $newEq->save();
                }
            }

            log::add('DaikinResidentialCtrl', 'debug','Tokens: '.$output[count($output)-1]);
            if (json_decode($output[count($output)-1]) != null) {
                config::save('tokenSet', $output[count($output)-1], 'DaikinEquipmentID');
            }
        }
    }
    /*
     * Fonction exécutée automatiquement toutes les minutes par Jeedom
      public static function cron() {
      }
     */

    /*
     * Fonction exécutée automatiquement toutes les 5 minutes par Jeedom
      public static function cron5() {
      }
     */

    /*
     * Fonction exécutée automatiquement toutes les 10 minutes par Jeedom
      public static function cron10() {
      }
     */
    
    /*
     * Fonction exécutée automatiquement toutes les 15 minutes par Jeedom
      public static function cron15() {
      }
     */
    
    /*
     * Fonction exécutée automatiquement toutes les 30 minutes par Jeedom
      public static function cron30() {
      }
     */
    
    /*
     * Fonction exécutée automatiquement toutes les heures par Jeedom
      public static function cronHourly() {
      }
     */

    /*
     * Fonction exécutée automatiquement tous les jours par Jeedom
      public static function cronDaily() {
      }
     */



    /*     * *********************Méthodes d'instance************************* */
    
 // Fonction exécutée automatiquement avant la création de l'équipement 
    public function preInsert() {
        
    }

 // Fonction exécutée automatiquement après la création de l'équipement 
    public function postInsert() {
        
    }

 // Fonction exécutée automatiquement avant la mise à jour de l'équipement 
    public function preUpdate() {
        
    }

 // Fonction exécutée automatiquement après la mise à jour de l'équipement 
    public function postUpdate() {
        
    }

 // Fonction exécutée automatiquement avant la sauvegarde (création ou mise à jour) de l'équipement 
    public function preSave() {
        
    }

 // Fonction exécutée automatiquement après la sauvegarde (création ou mise à jour) de l'équipement 
    public function postSave() {
        $info = $this->getCmd(null, 'temperature');
        if (!is_object($info)) {
            $info = new DaikinResidentialCtrlCmd();
            $info->setName(__('Temperature', __FILE__));
        }
        $info->setLogicalId('temperature');
        $info->setEqLogic_id($this->getId());
        $info->setType('info');
        $info->setSubType('numeric');
        $info->setUnite('°C');
        $info->save();

        $refresh = $this->getCmd(null, 'refresh');
        if (!is_object($refresh)) {
            $refresh = new DaikinResidentialCtrlCmd();
            $refresh->setName(__('Refresh', __FILE__));
        }
        $refresh->setEqLogic_id($this->getId());
        $refresh->setLogicalId('refresh');
        $refresh->setType('action');
        $refresh->setSubType('other');
        $refresh->save();

        $switchOn = $this->getCmd(null, 'switchOn');
        if (!is_object($switchOn)) {
            $switchOn = new DaikinResidentialCtrlCmd();
            $switchOn->setName(__('Switch On', __FILE__));
        }
        $switchOn->setEqLogic_id($this->getId());
        $switchOn->setLogicalId('switchOn');
        $switchOn->setType('action');
        $switchOn->setSubType('other');
        $switchOn->save();

        $switchOff = $this->getCmd(null, 'switchOff');
        if (!is_object($switchOff)) {
            $switchOff = new DaikinResidentialCtrlCmd();
            $switchOff->setName(__('Switch Off', __FILE__));
        }
        $switchOff->setEqLogic_id($this->getId());
        $switchOff->setLogicalId('switchOff');
        $switchOff->setType('action');
        $switchOff->setSubType('other');
        $switchOff->save();
    }

 // Fonction exécutée automatiquement avant la suppression de l'équipement 
    public function preRemove() {
        
    }

 // Fonction exécutée automatiquement après la suppression de l'équipement 
    public function postRemove() {
        
    }

    /*
     * Non obligatoire : permet de modifier l'affichage du widget (également utilisable par les commandes)
      public function toHtml($_version = 'dashboard') {

      }
     */

    /*
     * Non obligatoire : permet de déclencher une action après modification de variable de configuration
    public static function postConfig_<Variable>() {
    }
     */

    /*
     * Non obligatoire : permet de déclencher une action avant modification de variable de configuration
    public static function preConfig_<Variable>() {
    }
     */

    /*     * **********************Getteur Setteur*************************** */

    public function getTemperature() {
        log::add('DaikinResidentialCtrl', 'debug','getting temperature of equipment '.$this->getConfiguration('DaikinEquipmentID').
            ' with tokens '.config::byKey('tokenSet', 'DaikinEquipmentID'));
        exec("/usr/bin/node ../../plugins/DaikinResidentialCtrl/core/class/functions/getData.js ".
            $this->getConfiguration('DaikinEquipmentID').
            " temperatureControl /operationModes/auto/setpoints/roomTemperature".
            " \"".str_replace("\"", "\\\"", config::byKey('tokenSet', 'DaikinEquipmentID'))."\" 2>1", $output, $result_code);
        log::add('DaikinResidentialCtrl', 'debug','Return code: '.$result_code);
        if ($result_code == 0) {
            log::add('DaikinResidentialCtrl', 'info',"Temperature of equipment {$this->getConfiguration('DaikinEquipmentID')}: {$output[count($output)-2]}");
            log::add('DaikinResidentialCtrl', 'debug','Tokens: '.$output[count($output)-1]);
            if (json_decode($output[count($output)-1]) != null) {
                config::save('tokenSet', $output[count($output)-1], 'DaikinEquipmentID');
            }
            return (int) $output[count($output)-2];
        } else {
            log::add('DaikinResidentialCtrl', 'error', implode('\n', $output));
        }
    }

    public function setOnOffMode($mode) {
        log::add('DaikinResidentialCtrl', 'debug',"Switching $mode equipment ".$this->getConfiguration('DaikinEquipmentID').
            ' with tokens '.config::byKey('tokenSet', 'DaikinEquipmentID'));
        exec("/usr/bin/node ../../plugins/DaikinResidentialCtrl/core/class/functions/setData.js ".
            $this->getConfiguration('DaikinEquipmentID').
            " onOffMode $mode".
            " \"".str_replace("\"", "\\\"", config::byKey('tokenSet', 'DaikinEquipmentID'))."\" 2>1", $output, $result_code);
        log::add('DaikinResidentialCtrl', 'debug','Return code: '.$result_code);
        if ($result_code == 0) {
            log::add('DaikinResidentialCtrl', 'debug','return: '.implode('\n', $output));
            if (json_decode($output[count($output)-1]) != null) {
                config::save('tokenSet', $output[count($output)-1], 'DaikinEquipmentID');
            }
        } else {
            log::add('DaikinResidentialCtrl', 'error', implode('\n', $output));
        }
    }
}

class DaikinResidentialCtrlCmd extends cmd {
    /*     * *************************Attributs****************************** */
    
    /*
      public static $_widgetPossibility = array();
    */
    
    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */

    /*
     * Non obligatoire permet de demander de ne pas supprimer les commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
      public function dontRemoveCmd() {
      return true;
      }
     */

  // Exécution d'une commande  
    public function execute($_options = array()) {
        $eqlogic = $this->getEqLogic(); // Récupération de l’eqlogic
        switch ($this->getLogicalId()) {
            case 'refresh': // LogicalId de la commande rafraîchir que l’on a créé dans la méthode Postsave de la classe .
                $info = $eqlogic->getTemperature() ; //Lance la fonction et stocke le résultat dans la variable $info
                $eqlogic->checkAndUpdateCmd('temperature', $info);
                //log::add('DaikinResidentialCtrl', 'debug', print_r($eqlogic));
                break;
            case 'switchOn':
                $info = $eqlogic->setOnOffMode('on');
                break;
            case 'switchOff':
                $info = $eqlogic->setOnOffMode('off');
                break;
        }
    }

    /*     * **********************Getteur Setteur*************************** */
}


