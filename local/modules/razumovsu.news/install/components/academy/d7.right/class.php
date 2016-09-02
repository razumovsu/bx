<?php
/**
 * Created by PhpStorm
 * User: Sergey Pokoev
 * www.pokoev.ru
 * @ Академия 1С-Битрикс - 2015
 * @ academy.1c-bitrix.ru
 */
use \Bitrix\Main;
use \Bitrix\Main\Localization\Loc;

class d7Right extends CBitrixComponent
{

    /**
     * проверяет подключение необходиимых модулей
     * @throws LoaderException
     */
    protected function checkModules()
    {
        if (!Main\Loader::includeModule('academy.d7'))
            throw new Main\LoaderException(Loc::getMessage('ACADEMY_D7_MODULE_NOT_INSTALLED'));
    }

    function var1()
    {
        $arResult='У вас есть доступ к компоненту и здесь может быть ваш исполняемый код';

        return $arResult;
    }

    public function executeComponent()
    {
        global $APPLICATION;

        $this -> includeComponentLang('class.php');

        $this -> checkModules();

        if($APPLICATION->GetGroupRight("academy.d7")<"K")
        {
            ShowError(Loc::getMessage("ACCESS_DENIED"));
        }
        else
        {
            $this->arResult = $this->var1();

            $this->includeComponentTemplate();
        }
    }
};