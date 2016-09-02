<?php
/**
 * Created by PhpStorm.
 * User: razumovsu
 * Date: 29.08.16
 * Time: 11:14
 */
use \Bitrix\Main;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use Bitrix\Main\Context;
use Bitrix\Main\Application;
use Bitrix\Main\Data;

class CRazumovsuCache extends CBitrixComponent
{
    protected $errorsFatal = array();
    protected $errorsNonFatal = array();
    protected $requestData = array();


    /**
     * Подключаем модули
     *
     * @throws Main\LoaderException
     */
    protected function checkRequiredModules()
    {
        if(!Loader::includeModule('main'))
        {
            throw new Main\LoaderException(Loc::getMessage('RAZUMOVSU_CACHE_MAIN_MODULE_NOT_INSTALLED'));
        }
    }

    /**
     * Собираем ошибки
     *
     */
    protected function formatResultErrors()
    {
        $errors = array();
        if (!empty($this->errorsFatal))
            $errors['FATAL'] = $this->errorsFatal;
        if (!empty($this->errorsNonFatal))
            $errors['NONFATAL'] = $this->errorsNonFatal;


        if (!empty($errors['FATAL']))
            $this->arResult['FATAL_ERROR'] = $errors['FATAL'];
        if (!empty($errors['NONFATAL']))
            $this->arResult['NONFATAL'] = $errors['NONFATAL'];

        // backward compatiblity
        $error = each($this->errorsFatal);
        if (!empty($error['value']))
            $this->arResult['ERROR_MESSAGE'] = $error['value'];
    }

    /**
     * Получаем экземпляр запроса
     *
     */
    protected function processRequest()
    {
        $this->requestData = Context::getCurrent()->getRequest();
    }

    /**
     * Собираем $this->arResult
     *
     */
    protected function formatResult()
    {

    }

    /**
     * Функция-обертка над всей логикой компонента, заключена в try-catch
     * @return void
     */
    private function performActions()
    {
        try
        {
            $this->performActionList();
        }
        catch (Exception $e)
        {
            $this->errorsNonFatal[htmlspecialcharsEx($e->getCode())] = htmlspecialcharsEx($e->getMessage());
        }
    }


    /**
     * Функция выполняет основную логику компонента, основываясь на $this->arParams - параметрах компонента,
     * формируем $this->arResult
     * @return void
     */
    protected function performActionList()
    {

//        $arSelect = Array(); // для большей выразительности стянем все поля!!!
//        $arFilter = Array("IBLOCK_ID"=>IntVal(2), "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
//        $arFields = array();
//        for($i = 0; $i < 500; $i++)
//        {
//            $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
//            while($ob = $res->GetNextElement())
//            {
//                $arFields[] = $ob->GetFields();
//            }
//        }
//        $this->arResult['DEMO_VAR'] = $arFields[99];




        $obCache = new CPHPCache();
        $life_time = 30 * 60;
        $cache_id = 'unique_string';

        if($obCache->InitCache($life_time, $cache_id, '/'))
        {
            $vars = $obCache->GetVars();
            $demo_var = $vars['DEMO_VAR'];
            $this->arResult['DEMO_VAR'] = $demo_var;
           // $obCache->Output();
        }
        else
        {
            $demo_var = 'demo_value';
            $this->arResult['DEMO_VAR'] = $demo_var;
            // пример тяжелых запросов к базе данных
            // поиздеваемся))) над бдэхой и сделаем getlist в цикле 1000 раз!!!
            // какой я садист(
            $arSelect = Array(); // для большей выразительности стянем все поля!!!
            $arFilter = Array("IBLOCK_ID"=>IntVal(2), "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
            $arFields = array();
            for($i = 0; $i < 500; $i++)
            {
                $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
                while($ob = $res->GetNextElement())
                {
                    $arFields[] = $ob->GetFields();
                }
            }

        }

        if($obCache->StartDataCache())
        {
            echo 'Буферизируем вывод';
            //echo json_encode($arFields[0]);
        }

        $obCache->EndDataCache(array(
            'DEMO_VAR' => $arFields,
        ));

    }

    /**
     * Точка входа в логику компонента
     *
     */
    public function executeComponent()
    {

        global $APPLICATION;
        try{
            $this->checkRequiredModules();
            $this->processRequest();
            $this->formatResult();
            $this->performActions();
        }
        catch (Exception $e){
            $this->errorsFatal[htmlspecialcharsEx($e->getCode())] = htmlspecialcharsEx($e->getMessage());
        }

        $this->formatResultErrors();
        $this->includeComponentTemplate();
    }
}