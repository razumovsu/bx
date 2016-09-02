<?php
/**
 * Created by PhpStorm.
 * User: razumovsu
 * Date: 15.08.16
 * Time: 15:29
 */
return array(
        "parent_menu" => "global_menu_content", // поместим в раздел "Сервис"
        "section" => "razumovsu_news_section",
        "sort"        => 1,                    // сортировка пункта меню
        "url"         => "имя страницы.php?lang=".LANG,  // ссылка на пункте меню
        "text"        => 'Модуль новости',       // текст пункта меню
        "title"       => 'Новости', // текст всплывающей подсказки
        "icon"        => "form_menu_icon", // малая иконка
        "page_icon"   => "form_page_icon", // большая иконка
        "items_id"    => "menu_razumovsu_news",  // идентификатор ветви
        "items" => array(
            array(
                'sort' => 100,
                'url' => "razumovsu_news_list.php?lang=".LANG,
                'text' => 'Список новостей',
                'title' => 'Таблица со списком новостей',
                'icon'=> 'dd_blank_module_icon',
                'page_icon'=> 'dd_blank_module_icon',
                'module_id' => 'razumovsu_news',
                'items_id'=> 'menu_razumovsu_news',
                'skip_chain'  => false,
                'more_url' => array(),
            ),
        ),
    );