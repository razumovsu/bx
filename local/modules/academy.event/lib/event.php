<?php
/**
 * Created by PhpStorm
 * User: Sergey Pokoev
 * www.pokoev.ru
 * @ Академия 1С-Битрикс - 2015
 * @ academy.1c-bitrix.ru
 *
 * файл event.php
 */

namespace Academy\Event;

class event
{
    static public function eventHandler(\Bitrix\Main\Entity\Event $event)
    {
        $fields = $event->getParameter("fields");

        echo'<pre>';
        echo'Обработчик события из модуля academy.event
        ';
        var_dump($fields);
        echo'</pre>';
    }
}