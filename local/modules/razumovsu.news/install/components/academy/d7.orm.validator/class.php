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

class d7OrmValidator extends CBitrixComponent
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
        $result = BookTable::add(array(
            'NAME' => 'Книга для теста',
            'RELEASED' => '2002',
            'ISBN' => '00000000001',
            'AUTHOR' => 'Сергей Покоев',
            'TIME_ARRIVAL' => new Type\DateTime('04.09.2015 00:00:00'),
            'DESCRIPTION' => 'тестовый текст
            вторая строчка'
        ));

        return $result;
    }

    function var2()
    {
        $result = BookTable::add(array(
            'NAME' => 'Книга для теста',
            'RELEASED' => '2002',
            'ISBN' => '0000-000D 002',
            'AUTHOR' => 'Сергей Покоев',
            'TIME_ARRIVAL' => new Type\DateTime('04.09.2015 00:00:00'),
            'DESCRIPTION' => 'тестовый текст
            вторая строчка'
        ));

        return $result;
    }

    public function executeComponent()
    {
        $this -> includeComponentLang('class.php');

        $this -> checkModules();

        //Первая часть.
        //$result = $this->var1();

        //Вторая часть
        $result = $this->var2();

        if ($result->isSuccess())
        {
            $id = $result->getId();
            $this->arResult='Запись добавлена с id: '.$id;
        }
        else
        {
            $error=$result->getErrorMessages();
            $this->arResult='Произошла ошибка при добавлении: <pre>'.var_export($error,true).'</pre>';
        }

        $this->includeComponentTemplate();
    }
};