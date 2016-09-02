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

class d7OrmGetlist extends CBitrixComponent
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
            'select'  => array('ID','NAME_BOOK' =>'NAME','AGE_YEAR','WRITE_COUNT'), // имена полей, которые необходимо получить в результате
            'filter'  => array('WRITE_COUNT' => 0), // описание фильтра для WHERE и HAVING
            //'group'   => array(), // явное указание полей, по которым нужно группировать результат
            'order'   => array('ID'=>'DESC'), // параметры сортировки
            'limit'   => 3, // количество записей
            'offset'  => 2, // смещение для limit
        ));

        return $result;
    }


    public function executeComponent()
    {
        $this -> includeComponentLang('class.php');

        $this -> checkModules();

        $result = $this->var1();

        //Вариант 1 получения данных
        /*while ($row = $result->fetch())
        {
            $this -> arResult[] = $row;
        }*/

        //Вариант 2 получения данных
        $this -> arResult = $result->fetchAll();

        $this->includeComponentTemplate();
    }
};