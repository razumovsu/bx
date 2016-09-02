<?php
namespace Retor\Utypeproplocation;

use Bitrix\Main\Localization\Loc;
use CModule;

Loc::loadMessages(__FILE__);

class RetorUtypePropLocation
{

    function GetUserTypeDescription()
    {
        $x = 5;
        return array(
            "USER_TYPE_ID" => "location20",
            "CLASS_NAME" => "RetorUtypePropLocation",
            "DESCRIPTION" => GetMessage('RETOR_UTYPEPROP_LOCATION_PROP_NAME'),
            "BASE_TYPE" => "int",
        );
    }

    function GetEditFormHTML($arUserField, $arHtmlControl)
    {

        if (!CModule::IncludeModule('sale')) {
            return false;
        }

        global $APPLICATION;

        ob_start();

        $APPLICATION->IncludeComponent(
            "retor:sale.location.selector.search",
            "search-in-admin",
            array(
                "COMPONENT_TEMPLATE" => "search",
                "ID" => htmlspecialcharsbx($arHtmlControl["VALUE"]),
                "CODE" => "",
                "INPUT_NAME" => htmlspecialcharsbx($arHtmlControl['NAME']),
                "PROVIDE_LINK_BY" => "id",
                "JSCONTROL_GLOBAL_ID" => "",
                "JS_CALLBACK" => "",
                "SEARCH_BY_PRIMARY" => "Y",
                "EXCLUDE_SUBTREE" => "",
                "FILTER_BY_SITE" => "Y",
                "SHOW_DEFAULT_LOCATIONS" => "Y",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "36000000"
            ),
            false
        );

        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }

    function GetDBColumnType($arUserField)
    {
        global $DB;
        switch (strtolower($DB->type)) {
            case 'mysql':
                return 'int(18)';
                break;
            case 'oracle':
                return 'number(18)';
                break;
            case 'mssql':
                return "int";
                break;
        }
    }

} // class exists

