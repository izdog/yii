<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.99.0/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
</head>
<body>
<?php $this->beginBody() ?>
<header>
    <nav>
        <div class="nav-wrapper">
            <ul>
                <li><a href="<?= Url::to(['/users'])?>">User</a></li>
                <li><a href="<?=Url::to(['/tasks']) ?>">Tasks</a></li>
            </ul>
        </div>
    </nav>
</header>


        <?= $content ?>


<?php $this->endBody() ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.99.0/js/materialize.min.js"></script>
<script src="/js/app.js"></script>
</body>
</html>
<?php $this->endPage() ?>
