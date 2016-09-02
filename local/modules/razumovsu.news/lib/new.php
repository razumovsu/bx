<?php
namespace Razumovsu\News;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;

class NewTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'new_news';
    }

    public static function getUfId()
    {
        return 'NEW_NEWS';
    }

    public static function getConnectionName()
    {
        return 'default';
    }


    public static function getMap()
    {
        return array(
            new Entity\IntegerField('ID', array(
                'primary' => true,
                'autocomplete' => true
            )),
            new Entity\StringField('TITLE', array(
                'required' => true,
            )),
            new Entity\TextField('TEXT', array(
                'required' => true,
            )),
            new Entity\EnumField('IS_ACTIVE', array(
                'values' => array('Y', 'N')
            )),
            new Entity\DatetimeField('CREATED_AT', array(
                'required' => true,
                'default_value' => new Type\DateTime
            )),
            new Entity\DatetimeField('UPDATED_AT', array(
                'required' => true,
            )),
            new Entity\IntegerField('USER_ID'),
        );
    }

}