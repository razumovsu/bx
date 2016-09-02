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

class d7OrmUpdate extends CBitrixComponent
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
        //Обновление записи. Обновляется только название. (нужно указать верный id)
        $result = BookTable::update(1, array(
            'NAME' => 'Книга для теста измененная',
        ));

        return $result;
    }


    public function executeComponent()
    {
        $this -> includeComponentLang('class.php');

        $this -> checkModules();

        $result = $this->var1();

        if ($result->isSuccess())
        {
            $id = $result->getId();
            $this->arResult='Запись изменена с id: '.$id;
        }
        else
        {
            $error=$result->getErrorMessages();
            $this->arResult='Произошла ошибка при изменении: <pre>'.var_export($error,true).'</pre>';
        }

        $this->includeComponentTemplate();
    }
};