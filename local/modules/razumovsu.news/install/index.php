<?

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Config as Conf;
use \Bitrix\Main\Config\Option;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Entity\Base;
use \Bitrix\Main\Application;

Loc::loadMessages(__FILE__);
Class razumovsu_news extends CModule
{
    var $exclusionAdminFiles;

	function __construct()
	{
		$arModuleVersion = array();
		include(__DIR__."/version.php");

        $this->exclusionAdminFiles=array(
            '..',
            '.',
            'menu.php',
            'operation_description.php',
            'task_description.php'
        );

        $this->MODULE_ID = 'razumovsu.news';
		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		$this->MODULE_NAME = Loc::getMessage("ACADEMY_D7_MODULE_NAME");
		$this->MODULE_DESCRIPTION = Loc::getMessage("ACADEMY_D7_MODULE_DESC");

		$this->PARTNER_NAME = Loc::getMessage("ACADEMY_D7_PARTNER_NAME");
		$this->PARTNER_URI = Loc::getMessage("ACADEMY_D7_PARTNER_URI");

        $this->MODULE_SORT = 1;
        $this->SHOW_SUPER_ADMIN_GROUP_RIGHTS='Y';
        $this->MODULE_GROUP_RIGHTS = "Y";
	}

    //Определяем место размещения модуля
    public function GetPath($notDocumentRoot=false)
    {
        if($notDocumentRoot)
            return str_ireplace(Application::getDocumentRoot(),'',dirname(__DIR__));
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
        $xx = Loader::includeModule($this->MODULE_ID);

        if(!Application::getConnection(\Razumovsu\News\NewTable::getConnectionName())->isTableExists(
            Base::getInstance('\Razumovsu\News\NewTable')->getDBTableName()
            )
        )
        {
            Base::getInstance('\Razumovsu\News\NewTable')->createDbTable();
        }

    }

    function UnInstallDB()
    {
        $xx = Loader::includeModule($this->MODULE_ID);

//        Application::getConnection(\Razumovsu\News\NewTable::getConnectionName())->
//            queryExecute('drop table if exists '.Base::getInstance('\Razumovsu\News\NewTable')->getDBTableName());

    }

	function InstallEvents()
	{
	    return true;
	}

	function UnInstallEvents()
	{
        return true;
	}

	function InstallFiles($arParams = array())
	{
        $path=$this->GetPath()."/install/components";

        if(\Bitrix\Main\IO\Directory::isDirectoryExists($path))
            CopyDirFiles($path, $_SERVER["DOCUMENT_ROOT"]."/bitrix/components", true, true);
        else
            throw new \Bitrix\Main\IO\InvalidPathException($path);

        if (\Bitrix\Main\IO\Directory::isDirectoryExists($path = $this->GetPath() . '/admin'))
        {
            CopyDirFiles($this->GetPath() . "/install/admin/", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin"); //если есть файлы для копирования
            if ($dir = opendir($path))
            {
                while (false !== $item = readdir($dir))
                {
                    if (in_array($item,$this->exclusionAdminFiles))
                        continue;
                    file_put_contents($_SERVER['DOCUMENT_ROOT'].'/bitrix/admin/'.$this->MODULE_ID.'_'.$item,
                        '<'.'? require($_SERVER["DOCUMENT_ROOT"]."'.$this->GetPath(true).'/admin/'.$item.'");?'.'>');
                }
                closedir($dir);
            }
        }

        return true;
	}

	function UnInstallFiles()
	{
        \Bitrix\Main\IO\Directory::deleteDirectory($_SERVER["DOCUMENT_ROOT"] . '/bitrix/components/academy/');

        if (\Bitrix\Main\IO\Directory::isDirectoryExists($path = $this->GetPath() . '/admin')) {
            DeleteDirFiles($_SERVER["DOCUMENT_ROOT"] . $this->GetPath() . '/install/admin/', $_SERVER["DOCUMENT_ROOT"] . '/bitrix/admin');
            if ($dir = opendir($path)) {
                while (false !== $item = readdir($dir)) {
                    if (in_array($item, $this->exclusionAdminFiles))
                        continue;
                    \Bitrix\Main\IO\File::deleteFile($_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin/' . $this->MODULE_ID . '_' . $item);
                }
                closedir($dir);
            }
        }
		return true;
	}

	function DoInstall()
	{
		global $APPLICATION;
        if($this->isVersionD7())
        {
           $x = \Bitrix\Main\ModuleManager::registerModule($this->MODULE_ID);

            $this->InstallDB();
            $this->InstallEvents();
            $this->InstallFiles();

        }
        else
        {
            $APPLICATION->ThrowException(Loc::getMessage("ACADEMY_D7_INSTALL_ERROR_VERSION"));
        }

        $APPLICATION->IncludeAdminFile(Loc::getMessage("ACADEMY_D7_INSTALL_TITLE"), $this->GetPath()."/install/step.php");
	}

    function DoUninstall()
    {
        global $APPLICATION;

        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();
        $this->UnInstallFiles();
        $this->UnInstallEvents();

//        if ($request["savedata"] != "Y") {
            $this->UnInstallDB();
//        }

        \Bitrix\Main\ModuleManager::unRegisterModule($this->MODULE_ID);

        $APPLICATION->IncludeAdminFile(Loc::getMessage("ACADEMY_D7_UNINSTALL_TITLE"),
            $this->GetPath() . "/install/unstep.php");

    }

    function GetModuleRightList()
    {
        return array(
            "reference_id" => array("D","K","S","W"),
            "reference" => array(
                "[D] ".Loc::getMessage("ACADEMY_D7_DENIED"),
                "[K] ".Loc::getMessage("ACADEMY_D7_READ_COMPONENT"),
                "[S] ".Loc::getMessage("ACADEMY_D7_WRITE_SETTINGS"),
                "[W] ".Loc::getMessage("ACADEMY_D7_FULL"))
        );
    }
}
?>