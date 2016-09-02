<?php
use Bitrix\Main\Application;
use Bitrix\Main\EventManager;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);

Class retor_utypeproplocation extends CModule
{

    var $exclusionAdminFiles;

    function __construct()
    {

        $arModuleVersion = array();
        $this->exclusionAdminFiles = array(
            '..',
            '.',
            'menu.php',
            'operation_description.php',
            'task_description.php'
        );
        $path = str_replace("\\", "/", __FILE__);
        $path = substr($path, 0, strlen($path) - strlen("/index.php"));
        include($path . "/version.php");

        $this->MODULE_ID = "retor.utypeproplocation";
        $this->MODULE_GROUP_RIGHTS = "Y";

        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

        $this->MODULE_NAME = GetMessage('RETOR_UTYPEPROP_LOCATION_MODULE_NAME');
        $this->MODULE_DESCRIPTION = GetMessage('RETOR_UTYPEPROP_LOCATION_MODULE_DESCRIPTION');

        $this->PARTNER_NAME = "Интернет агентство 'ReTOR'";
        $this->PARTNER_URI = "http://retor.ru";
    }

    //Определяем место размещения модуля
    public function GetPath($notDocumentRoot = false)
    {
        if ($notDocumentRoot) {
            return str_ireplace(Application::getDocumentRoot(), '', dirname(__DIR__));
        } else {
            return dirname(__DIR__);
        }
    }

    function InstallFiles($arParams = array())
    {
        $path = $this->GetPath() . "/install/components";

        if (\Bitrix\Main\IO\Directory::isDirectoryExists($path)) {
            CopyDirFiles($path, $_SERVER["DOCUMENT_ROOT"] . "/bitrix/components", true, true);
        } else {
            throw new \Bitrix\Main\IO\InvalidPathException($path);
        }

        if (\Bitrix\Main\IO\Directory::isDirectoryExists($path = $this->GetPath() . '/admin')) {
            CopyDirFiles($this->GetPath() . "/install/admin/",
                $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin"); //если есть файлы для копирования
            if ($dir = opendir($path)) {
                while (false !== $item = readdir($dir)) {
                    if (in_array($item, $this->exclusionAdminFiles)) {
                        continue;
                    }
                    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin/' . $this->MODULE_ID . '_' . $item,
                        '<' . '? require($_SERVER["DOCUMENT_ROOT"]."' . $this->GetPath(true) . '/admin/' . $item . '");?' . '>');
                }
                closedir($dir);
            }
        }
    }

    function UnInstallFiles()
    {
        \Bitrix\Main\IO\Directory::deleteDirectory($_SERVER["DOCUMENT_ROOT"] . '/bitrix/components/retor/');

        if (\Bitrix\Main\IO\Directory::isDirectoryExists($path = $this->GetPath() . '/admin')) {
            DeleteDirFiles($_SERVER["DOCUMENT_ROOT"] . $this->GetPath() . '/install/admin/',
                $_SERVER["DOCUMENT_ROOT"] . '/bitrix/admin');
            if ($dir = opendir($path)) {
                while (false !== $item = readdir($dir)) {
                    if (in_array($item, $this->exclusionAdminFiles)) {
                        continue;
                    }
                    \Bitrix\Main\IO\File::deleteFile($_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin/' . $this->MODULE_ID . '_' . $item);
                }
                closedir($dir);
            }
        }
        return true;
    }

    function InstallEvents()
    {
        EventManager::getInstance()->registerEventHandler('main', 'OnUserTypeBuildList', $this->MODULE_ID,
            '\Retor\Utypeproplocation\RetorUtypePropLocation', 'GetUserTypeDescription');
    }

    function UnInstallEvents()
    {
        EventManager::getInstance()->unRegisterEventHandler('main', 'OnUserTypeBuildList', $this->MODULE_ID,
            '\Retor\Utypeproplocation\RetorUtypePropLocation', 'GetUserTypeDescription');
    }

    function InstallDB($install_wizard = true)
    {
        ModuleManager::registerModule($this->MODULE_ID);
        return true;
    }

    function UnInstallDB($arParams = Array())
    {
        ModuleManager::unRegisterModule($this->MODULE_ID);
        return true;
    }

    function DoInstall()
    {

        $this->InstallEvents();
        $this->InstallDB(false);
        $this->InstallFiles();
    }

    function DoUninstall()
    {

        $this->UnInstallEvents();
        $this->UnInstallDB();
        $this->UnInstallFiles();
    }


}

