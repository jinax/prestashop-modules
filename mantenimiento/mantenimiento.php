<?php

/**
 * 2007-2019 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    PrestaShop SA <contact@prestashop.com>
 *  @copyright 2007-2019 PrestaShop SA
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class Mantenimiento extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'mantenimiento';
        $this->tab = 'others';
        $this->version = '1.0.0';
        $this->author = 'jinax';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Mantenimiento');
        $this->description = $this->l('Módulo de personalización de la página de mantenimiento');

        $this->confirmUninstall = $this->l('');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        $arenombrar = _PS_THEME_DIR_ . "templates/errors/maintenance_original.tpl";
        $original = _PS_THEME_DIR_ . "templates/errors/maintenance.tpl";
        $delmodulo = _PS_MODULE_DIR_ . "mantenimiento/maintenance.tpl";

        if(!rename( $original, $arenombrar))
        { 
            echo "Error $original to $arenombrar" ;
        }

        if (!copy($delmodulo, $original)) { 
            echo "File cannot be copied! \n"; 
        } 

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('displayMaintenance');
    }

    public function uninstall()
    {
        $nombredef = _PS_THEME_DIR_ . "templates/errors/maintenance.tpl";
        $original = _PS_THEME_DIR_ . "templates/errors/maintenance_original.tpl";
        
        // $nombredef = _THEME_DIR_ . "templates/errors/maintenance.tpl";
        // $original = _THEME_DIR_ . "templates/errors/maintenance_original.tpl";


        if (!unlink($nombredef)) { 
            echo ("$nombredef no pudo ser borrado"); 
        } 
      
        if(!rename( $original, $nombredef))
        { 
            echo "Error $original to $nombredef" ;
        }
        
        Configuration::deleteByName('MANTENIMIENTO_IMG');
        Configuration::deleteByName('MANTENIMIENTO_DESC');
        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool) Tools::isSubmit('submitMantenimientoModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configure.tpl');

        return $output . $this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitMantenimientoModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );
        
        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Configuración'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'file',
                        'label' => $this->l('Imagen'),
                        'name' => 'MANTENIMIENTO_IMG',
                        'desc' => $this->l('Suba una imagen a la página de mantenimiento.'),
                        'display_image' => true
                    ),
                    array(
                        'type' => 'textarea',
                        'lang' => false,
                        'label' => $this->l('contenido'),
                        'name' => 'MANTENIMIENTO_DESC',
                        'desc' => $this->l('Inserte contenido para la página de mantenimiento.'),
                        'cols' => 40,
                        'rows' => 10,
                        'class' => 'rte',
                        'autoload_rte' => true
                    )
                ),
                'submit' => array(
                    'title' => $this->l('Guardar')
                )
            )
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {      
        return array(
            'MANTENIMIENTO_IMG' => Tools::getValue('MANTENIMIENTO_IMG'),
            'MANTENIMIENTO_DESC' => Tools::getValue('MANTENIMIENTO_DESC')      
        );
    }


    /**
     * Save form data.
     */
    protected function postProcess()
    { 

        $form_values = $this->getConfigFormValues();

        Configuration::updateValue('MANTENIMIENTO_IMG', $form_values['MANTENIMIENTO_IMG']);
        Configuration::updateValue('MANTENIMIENTO_DESC', $form_values['MANTENIMIENTO_DESC']);

        if (isset($_FILES['MANTENIMIENTO_IMG']) && $_FILES['MANTENIMIENTO_IMG']['error'] === UPLOAD_ERR_OK) {

            $fileTmpPath = $_FILES['MANTENIMIENTO_IMG']['tmp_name'];
            $fileName = $_FILES['MANTENIMIENTO_IMG']['name'];
            $fileType = $_FILES['MANTENIMIENTO_IMG']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            // $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

            $allowedfileExtensions = array('jpg', 'gif', 'png');
            if (in_array($fileExtension, $allowedfileExtensions)) {
                $uploadFileDir = _PS_MODULE_DIR_. '/mantenimiento/img/';
                // $dest_path = $uploadFileDir . $newFileName;
                $dest_path = $uploadFileDir . $fileName;
                if(move_uploaded_file($fileTmpPath, $dest_path))
                {
                    $message ='File is successfully uploaded.';
                    $this->displayConfirmation($this->l($message));
                }
                else
                {
                    $message = 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
                    $this->displayError($this->l($message));
                }
            }

            return $this->displayConfirmation($this->l('The settings have been updated.'));
        }
       
        return '';
    }
  

    /**
     * Add the CSS & JavaScript files you want to be loaded in the BO.
     */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path . 'views/js/back.js');
            $this->context->controller->addCSS($this->_path . 'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path . '/views/js/front.js');
        $this->context->controller->addCSS($this->_path . '/views/css/front.css');
        $this->context->controller->addCSS($this->_path . '/views/css/style.css');
    }


    public function processForm()
    {
        if (Tools::isSubmit('mantenimiento_form')) {
            $Name = Tools::getValue('Name');
            $Email = Tools::getValue('Email');
            $Message = Tools::getValue('Message');
            $NameTo = "nametowhom";
            $EmailTo = "mail@example.com";
            $Subject = "formulario de la página de mantenimiento";

            $var_list = [];
            $var_list['{email}'] = $Email;
            $var_list['{message}'] = $Message;

            $success = Mail::Send(
                (int) (Configuration::get('PS_LANG_DEFAULT')),
                'contact',
                $Subject,
                $var_list,
                $EmailTo,
                $NameTo,
                $Email,
                $Name
            );

            if($success){
                $this->context->smarty->assign('confirmation', 'ok');
            }
        }
    }

    public function hookDisplayMaintenance()
    {
        $imgname = Configuration::get('MANTENIMIENTO_IMG');
        $path_url = _PS_MODULE_DIR_. 'mantenimiento/img/'. $imgname;

        
        if ($imgname && file_exists($path_url)) {
            $path_templ = '/modules/mantenimiento/img/'.$imgname;
            $this->smarty->assign('mantenimiento_img', $path_templ);
        }

        $desc = Configuration::get('MANTENIMIENTO_DESC');
        $this->context->smarty->assign('mantenimiento_desc', $desc);

        $this->context->smarty->assign('module_dir', $this->_path);

        $this->processForm();

        return $this->display(__FILE__, 'mantenimiento.tpl');
    }
}
