<?php
/**
 * Created by PhpStorm
 * User: Sergey Pokoev
 * www.pokoev.ru
 * @ Академия 1С-Битрикс - 2015
 * @ academy.1c-bitrix.ru
 */
class d7SQL extends CBitrixComponent
{
    var $connection;
    var $sqlHelper;
    var $sql;

    function __construct($component = null)
    {
        parent::__construct($component);
        $this->connection = \Bitrix\Main\Application::getConnection();
        $this->sqlHelper = $this->connection->getSqlHelper();

        //Строка запроса. Выбираем все логины, активных пользователей
        $this->sql = 'SELECT LOGIN FROM b_user WHERE ACTIVE = \''.$this->sqlHelper->forSql('Y', 1).'\' ';
    }

    /*
     * Возвращаем все значения
     */
    function var1()
    {
        $recordset = $this->connection->query($this->sql);
        while ($record = $recordset->fetch())
        {
            $arResult[]=$record;
        }

        return $arResult;
    }

    /*
     * Возвращаем первые два значения
     */
    function var2()
    {
        $recordset = $this->connection->query($this->sql,2);
        while ($record = $recordset->fetch())
        {
            $arResult[]=$record;
        }

        return $arResult;
    }

    /*
     * Возвращаем два значения, отступая два элемента от начала
     */
    function var3()
    {
        $recordset = $this->connection->query($this->sql,2,2);
        while ($record = $recordset->fetch())
        {
            $arResult[]=$record;
        }

        return $arResult;
    }

    /*
     * Возвращаем сразу первый элемент из запроса
     */
    function var4()
    {
        $arResult = $this->connection->queryScalar($this->sql);

        return $arResult;
    }

    /*
     * Выполняем запрос, не возвращая результат, т. е. INSERT, UPDATE, DELETE
     */
    function var5()
    {
        $this->connection->queryExecute('UPDATE b_user SET ACTIVE = \'N\' WHERE LOGIN=\'test\' ');//Заменить на UPDATE
    }

    /*
     * Модифицируем результат
     */
    function var6()
    {
        $recordset = $this->connection->query($this->sql);
        $recordset->addFetchDataModifier(
            function ($data)
            {
                $data["LOGIN"] .= ": Логин пользователя";
                return $data;
            }
        );
        while ($record = $recordset->fetch())
        {
            $arResult[]=$record;
        }

        return $arResult;
    }

    public function executeComponent()
    {
        //$this->arResult = $this->var1();

        //$this->arResult = $this->var2();

        //$this->arResult = $this->var3();

        //$this->arResult = $this->var4();

        //$this->var5();

        $this->arResult = $this->var6();

        $this->includeComponentTemplate();
    }
};