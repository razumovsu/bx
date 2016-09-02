<?php
/**
 * Created by PhpStorm.
 * User: razumovsu
 * Date: 29.08.16
 * Time: 11:14
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentDescription = array(
    "NAME" => GetMessage('RAZUMOVSU_CACHE_NAME'),
    "DESCRIPTION" => GetMessage('RAZUMOVSU_CACHE_DESCRIPTION'),
    "SORT" => 10,
    "CACHE_PATH" => "Y",
    "PATH" => array(
        "ID" => "Look",
    ),
    "COMPLEX" => "N",
);
