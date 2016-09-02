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
use \Academy\D7\Book2Table;

class d7OrmMN extends CBitrixComponent
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
        $result = Book2Table::getList(array(
            'select'  => array(
                'NAME',
                //'AUTHOR_NAME' => '\Academy\D7\BookAuthorsUsTable:BOOK.AUTHOR.NAME',
                //'AUTHOR_LAST_NAME' => '\Academy\D7\BookAuthorsUsTable:BOOK.AUTHOR.LAST_NAME',
                '\Academy\D7\BookAuthorsUsTable:BOOK.AUTHOR.NAME',
                '\Academy\D7\BookAuthorsUsTable:BOOK.AUTHOR.LAST_NAME',
            ),
            "data_doubling"=>false
        ));

        return $result->fetchAll();
    }


    public function executeComponent()
    {
        $this -> includeComponentLang('class.php');

        $this -> checkModules();

        $this -> arResult = $this->var1();

        $this->includeComponentTemplate();
    }
};