<?php
namespace yii\easyii\modules\file;

class FileModule extends \yii\easyii\components\Module
{
    public static $installConfig = [
        'title' => [
            'en' => 'Files',
            'ru' => 'Файлы',
        ],
        'icon' => 'file',
        'order_num' => 30,
    ];
}