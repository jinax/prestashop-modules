<?php

/**
 * 2007-2019 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2019 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0  Academic Free License (AFL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;


if (!defined('_PS_VERSION_')) {
    exit;
}

class MyModule extends Module
{
    public function __construct()
    {
        $this->name = 'mymodule';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'jinax';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.6',
            'max' => _PS_VERSION_
        ];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('My module');
        $this->description = $this->l('Product slider');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        if (!Configuration::get('MYMODULE_NAME')) {
            $this->warning = $this->l('No name provided');
        }
    }

    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        return parent::install() &&
            $this->registerHook('leftColumn') &&
            $this->registerHook('header') &&
            Configuration::updateValue('MYMODULE_NAME', 'my friend');
    }

    public function hookDisplayLeftColumn($params)
    {
        $this->context->smarty->assign([
            'my_module_name' => Configuration::get('MYMODULE_NAME'),
            'my_module_link' => $this->context->link->getModuleLink('mymodule', 'display'),
            'array_prod' => Product::getProducts(Configuration::get('PS_LANG_DEFAULT'), 0, 10, 'id_product', 'ASC'),
            'link' => new Link()
        ]);

        return $this->display(__FILE__, 'mymodule.tpl');
    }

    public function hookDisplayRightColumn($params)
    {
        return $this->hookDisplayLeftColumn($params);
    }

    public function hookDisplayHeader()
    {       
        $this->context->controller->addCSS($this->_path . 'views/css/mymodule.css', 'all');
        $this->context->controller->addCSS($this->_path . 'views/css/swiper.min.css', 'all');
        $this->context->controller->addCSS($this->_path . 'views/css/mycss.css', 'all');
        $this->context->controller->addJS(($this->_path). 'views/js/swiper.min.js');
        $this->context->controller->addJS(($this->_path). 'views/js/myscript.js');    
    }

    public function uninstall()
    {
        if (
            !parent::uninstall() ||
            !Configuration::deleteByName('MYMODULE_NAME')
        ) {
            return false;
        }

        return true;
    }
}
