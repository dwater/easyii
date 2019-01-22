<?php
namespace yii\easyii\modules\gallery\api;

use Yii;
use yii\easyii\components\API;
use yii\helpers\Html;
use yii\helpers\Url;

class PhotoObject extends \yii\easyii\components\ApiObject
{
    public $image;
    public $description;
    public $rel;

    //Lance,20180829,option added.
    public function box($width, $height, $option=[]){
        $img = Html::img($this->thumb($width, $height));
        //$a = Html::a($img, $this->image, [
        $a = Html::a($img, Yii::getAlias('@uploadsUrl').$this->image, array_merge($option, [
            'class' => 'easyii-box',
            'rel' => 'album-' . ($this->rel ? $this->rel : $this->model->item_id),
            'title' => $this->description
        ]));
        return LIVE_EDIT ? API::liveEdit($a, $this->editLink) : $a;
    }

    public function getEditLink(){
        return Url::to(['/admin/gallery/a/photos', 'id' => $this->model->item_id]).'#photo-'.$this->id;
    }
    

    //Lance,20170315
    public function fullBox(){
        $img = Html::img(Yii::getAlias('@uploadsUrl').$this->image, ['class' => 'img-responsive', 'alt' => $this->description ]);
        return $img;
    }
}