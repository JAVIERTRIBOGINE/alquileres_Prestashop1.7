<?php

if (!defined('_PS_VERSION_'))
exit;

/**
 * ARCHIVO DE INSTALACIÓN Y CONFIGURACIÓN DEL MÓDULO
 */

class Alquileres extends Module {

	public function __construct() {
	$this->name = 'alquileres';
	$this->tab = 'front_office_features';
	$this->version = '1.0.0';
	$this->author = 'xavi';
	$this->need_instance = 0;
	$this->ps_versions_compliancy = array('min'=>'1.6','max'=>_PS_VERSION_);
	$this->bootstrap = true;
	parent::__construct();
	$this->displayName = $this->l('Alquiler Espacios');
	$this->description = $this->l('Modulo para seleccionar horario para alquiler espacios');
	$this->confirmUninstall = $this->l('¿Desea desinstalar?');
	}


	public function install() {
		if (!parent::install()  || !$this->installModuleTab('AdminAlquileres', array(1=>'Alquiler Espacios'), 2)|| !$this->registerHook('ActionAdminControllerSetMedia'))
			return false;
		return true;
	}


	public function uninstall() {
		if (!parent::uninstall()  || !$this->uninstallModuleTab('AdminAlquileres') || !$this->unregisterHook('ActionAdminControllerSetMedia'))
			return false;
		return true;
	}

	/* se escribe funcion para linkar con el controlador*/

	public function installModuleTab($tabClass, $tabName, $idTabParent) {
        $tab = new Tab();
        $tab->name=$tabName;
        $tab->class_name = $tabClass;
        $tab->module = $this->name;
        $tab->id_parent = $idTabParent;
        $tab->position=5;
        if(!$tab->save())
        	return false;
        return true;
	}

	//metodo para agrupar el js con el hook
	public function hookActionAdminControllerSetMedia()
    {

    	$this->context->controller->addJs($this->_path.'views/js/'.$this->name.'.js');

    }

}
?>
