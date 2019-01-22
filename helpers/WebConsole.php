<?php
namespace yii\easyii\helpers;

use Yii;
use yii\helpers\FileHelper;

class WebConsole
{
    private static $_console;
    public static $logFile;
    public static $logFileHandler;

    public static function console()
    {
        if(!self::$_console)
        {
            $logsPath = Yii::getAlias('@runtime/logs');
            if(!FileHelper::createDirectory($logsPath, 0777)){
                throw new \yii\web\ServerErrorHttpException('Cannot create `'.$logsPath.'`. Please check write permissions.');
            }

            self::$logFile = $logsPath . DIRECTORY_SEPARATOR . 'console.log';
            self::$logFileHandler = fopen(self::$logFile, 'w+');

            defined('STDIN') or define( 'STDIN',  self::$logFileHandler);
            defined('STDOUT') or define( 'STDOUT',  self::$logFileHandler);

            $oldApp = Yii::$app;

            //Lance,@app->@console
            $consoleConfigFile = Yii::getAlias('@console/config/main.php');

            if(!file_exists($consoleConfigFile) || !is_array(($consoleConfig = require($consoleConfigFile)))){
                throw new \yii\web\ServerErrorHttpException('Cannot find `'.Yii::getAlias('@console/config/main.php').'`. Please create and configure console config.');
            }

            self::$_console = new \yii\console\Application($consoleConfig);

            Yii::$app = $oldApp;
        } else {
            ftruncate(self::$logFileHandler, 0);
        }

        return self::$_console;
    }

    public static function migrate()
    {
        ob_start();
       
        self::console()->runAction('migrate', ['migrationPath' => '@easyii/migrations/', 'interactive' => false]);

        //dektrium/yii2-user
        self::console()->runAction('migrate/up', ['migrationPath' => '@dektrium/user/migrations/', 'interactive' => false]);
        
        //yii2mod/yii2-comments
        self::console()->runAction('migrate', ['migrationPath' => '@yii2mod/comments/migrations/', 'interactive' => false]);
        
        //@console/migrations
        self::console()->runAction('migrate', ['migrationPath' => '@console/migrations/', 'interactive' => false]);        
        
        $result = file_get_contents(self::$logFile) . "\n" . ob_get_clean();

        Yii::$app->cache->flush();

        return $result;
    }
}