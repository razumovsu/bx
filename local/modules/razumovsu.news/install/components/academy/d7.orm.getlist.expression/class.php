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
use \Bitrix\Main\Type;
use \Academy\D7\BookTable;

class d7OrmGetlistExpression extends CBitrixComponent
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
        $result = BookTable::getList(array(
            'select' => array('CNT'),
            'runtime' => array(
                new Main\Entity\ExpressionField('CNT', 'COUNT(*)')
            ),
        ));

        return $result->fetch();
    }

    function var2()
    {
        $result = BookTable::getList(array(
            'select' => array(
                new Main\Entity\ExpressionField('CNT', 'COUNT(*)')
            ),
        ));

        return $result->fetch();
    }

    function var3()
    {
        $result = BookTable::getList(array(
            'select' => array(
                'ID','NAME', 'ACTIVITY'
            ),
            'filter'  => array('ACTIVITY' => 1),
            'runtime' => array(
                new Main\Entity\IntegerField('ACTIVITY'),
            )
        ));

        return $result->fetchAll();
    }

    public function executeComponent()
    {
        $this -> includeComponentLang('class.php');

        $this -> checkModules();

        //$this -> arResult = $this->var1();

        //$this -> arResult = $this->var2();

        $this -> arResult = $this->var3();

        $this->includeComponentTemplate();
    }
};