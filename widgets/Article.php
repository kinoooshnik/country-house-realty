<?php

namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Url;

class Article extends Widget
{
    public $image;
    public $title;

    public function init()
    {
        ?>
        </div>
        <div class="article">
        <div class="py-5 bg-image-full"
             style="background-image: url('<?= isset($this->image) ? Url::to($this->image) : '' ?>');"></div>
        <div class="d-block mx-auto container on-blur-bg">
            <div style="text-align: center;">
                <div class="title display-4 m-1"><?= isset($this->title) ? $this->title : '' ?></div>
            </div>
        </div>

        <div class="bg-image-footer"></div>
        <div class="container" style="padding-top: 30px">
        <?php
        parent::init();
    }

    public function run()
    {
        echo '</div></div>';
    }
}