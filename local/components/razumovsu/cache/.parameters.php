<?php
/**
 * Created by PhpStorm.
 * User: razumovsu
 * Date: 02.08.16
 * Time: 11:14
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
#1026:Personal Area. Here you can prepare $arParams
$arComponentParameters = array(
    "GROUPS" => array(
    ),
    "PARAMETERS" => array(
        "CACHE_TIME"  =>  Array("DEFAULT"=>36000000),
        "CACHE_FILTER" => array(
            "PARENT" => "CACHE_SETTINGS",
            "NAME" => GetMessage("BN_P_CACHE_FILTER"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "N",
        ),
        "CACHE_GROUPS" => array(
            "PARENT" => "CACHE_SETTINGS",
            "NAME" => GetMessage("CP_BN_CACHE_GROUPS"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y",
        ),
    ),
);
?>