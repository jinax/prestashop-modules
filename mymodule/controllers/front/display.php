<?php

class mymoduledisplayModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();
        $this->setTemplate('module:mymodule/views/templates/front/display.tpl');
    }
}
