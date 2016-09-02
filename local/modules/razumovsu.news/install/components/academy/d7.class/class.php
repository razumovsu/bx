<?php
/**
 * Created by PhpStorm
 * User: Sergey Pokoev
 * www.pokoev.ru
 * @ Академия 1С-Битрикс - 2015
 * @ academy.1c-bitrix.ru
 */
use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;

class D7Class extends CBitrixComponent
{
    var $test;

    protected function checkModules()
    {
        if (!Loader::includeModule('academy.d7'))
        {
            ShowError(Loc::getMessage('ACADEMY_D7_MODULE_NOT_INSTALLED'));
            return false;
        }

        return true;
    }

    public function executeComponent()
    {
        $this -> includeComponentLang('class.php');

        if($this -> checkModules())
        {
            /*Ваш код*/

            $this->includeComponentTemplate();
        }
    }
};