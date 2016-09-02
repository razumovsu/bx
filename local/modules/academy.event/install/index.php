<?
/**
 * Created by PhpStorm
 * User: Sergey Pokoev
 * www.pokoev.ru
 * @ Академия 1С-Битрикс - 2015
 * @ academy.1c-bitrix.ru
 */

use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

Class academy_event extends CModule
{
	function __construct()
	{
		$arModuleVersion = array();
		include(__DIR__."/version.php");

        $this->MODULE_ID = 'academy.event';
		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		$this->MODULE_NAME = Loc::getMessage("ACADEMY_EVENT_MODULE_NAME");
		$this->MODULE_DESCRIPTION = Loc::getMessage("ACADEMY_EVENT_MODULE_DESC");

		$this->PARTNER_NAME = Loc::getMessage("ACADEMY_EVENT_PARTNER_NAME");
		$this->PARTNER_URI = Loc::getMessage("ACADEMY_EVENT_PARTNER_URI");
	}

    //Определяем место размещения модуля
    public function GetPath($notDocumentRoot=false)
    {
        if($notDocumentRoot)
            return str_ireplace($_SERVER["DOCUMENT_ROOT"],'',dirname(__DIR__));
        else
            return dirname(__DIR__);
    }

    //Проверяем что система поддерживает D7
    public function isVersionD7()
    {
        return CheckVersion(\Bitrix\Main\ModuleManager::getVersion('main'), '14.00.00');
    }

    function InstallDB()
    {
        return true;
    }

    function UnInstallDB()
    {
        return true;
    }

	function InstallEvents()
	{
        \Bitrix\Main\EventManager::getInstance()->registerEventHandler('academy.d7', '\Academy\D7\Book::OnBeforeAdd', $this->MODULE_ID, '\Academy\Event\Event', 'eventHandler');
	}

	function UnInstallEvents()
	{
        \Bitrix\Main\EventManager::getInstance()->unRegisterEventHandler('academy.d7', '\Academy\D7\Book::OnBeforeAdd', $this->MODULE_ID, '\Academy\Event\Event', 'eventHandler');
	}

	function InstallFiles()
	{
        return true;
	}

	function UnInstallFiles()
	{
		return true;
	}

	function DoInstall()
	{
		global $APPLICATION;
        if($this->isVersionD7())
        {
            $this->InstallDB();
            $this->InstallEvents();
            $this->InstallFiles();

            \Bitrix\Main\ModuleManager::registerModule($this->MODULE_ID);
        }
        else
        {
            $APPLICATION->ThrowException(Loc::getMessage("ACADEMY_EVENT_INSTALL_ERROR_VERSION"));
        }
	}

	function DoUninstall()
	{
        \Bitrix\Main\ModuleManager::unRegisterModule($this->MODULE_ID);

        $this->UnInstallEvents();
        $this->UnInstallFiles();
        $this->UnInstallDB();
	}
}
?>