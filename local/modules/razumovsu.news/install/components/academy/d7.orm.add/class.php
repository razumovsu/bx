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

class d7OrmAdd extends CBitrixComponent
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

    //Корректное добавление записи
    function var1()
    {
        $result = BookTable::add(array(
            'NAME' => 'Книга для теста',
            'RELEASED' => '2002',
            'ISBN' => '978-0321127426',
            'AUTHOR' => 'Сергей Покоев',
            'TIME_ARRIVAL' => new Type\DateTime('04.09.2015 00:00:00'),
            'DESCRIPTION' => 'тестовый текст
            вторая строчка'
        ));

        return $result;
    }

    //Добавление записи без обязательного поля "Название".
    function var2()
    {
        $result = BookTable::add(array(
            'RELEASED' => '2002',
            'ISBN' => '978-0321127426',
            'AUTHOR' => 'Сергей Покоев',
            'TIME_ARRIVAL' => new Type\DateTime('04.09.2015 00:00:00'),
            'DESCRIPTION' => 'тестовый текст
            вторая строчка'
        ));

        return $result;
    }

    //Добавление записи без указания поля, для которого установлено значение по умолчанию
    function var3()
    {
        $result = BookTable::add(array(
            'NAME' => 'Книга для теста',
            'RELEASED' => '2002',
            'ISBN' => '978-0321127426',
            'AUTHOR' => 'Сергей Покоев',
            'DESCRIPTION' => 'тестовый текст
            вторая строчка'
        ));

        return $result;
    }

    public function executeComponent()
    {
        $this -> includeComponentLang('class.php');

        $this -> checkModules();

        //все верно
        //$result = $this->var1();

        //Не указал обязательное поле: название
        //$result = $this->var2();

        //Добавление используя поле по умолчанию.
        $result = $this->var3();

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