<?php

//use yii;
use yii\bootstrap\Html;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use common\models\Board;

/* @var $this \yii\web\View */
/* @var $content string */

    AppAsset::register($this);
    if ($boardObject = Board::getCurrentActiveBoard()) {
        $this->title = $boardObject->title;
    } else {
        $this->title = '';
    }

    $searchPanelOpen = false;
    if (isset($_COOKIE['search-block'])) {
        $searchPanelOpen = $_COOKIE['search-block'] == '1';
    }
?>

<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title>Ban the Can(<?= (YII_ENV_DEMO ? 'DEMO' : '') ?>): <?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
<?php echo $this->renderFile('@frontend/views/layouts/partials/_modalContainer.php'); ?>

<div class="wrap">
    <?php echo $this->renderFile('@frontend/views/layouts/partials/_navigation.php'); ?>
    <div class="container-fluid">
        <?php echo Alert::widget(); ?>

        <div class="row">

            <div id="left-sidebar" class="collapse width <?php echo $searchPanelOpen ? 'in' : ''?>">
                <div class="col-xs-6 col-sm-4 col-lg-2">
                    <?php echo $this->renderFile('@frontend/views/layouts/partials/_left-sidebar.php'); ?>
                </div>
            </div>

            <div id="left-layout-main" class="<?php echo $searchPanelOpen ? 'col-xs-6 col-sm-8 col-lg-10' : 'col-xs-12'; ?>">
                <?php echo $content ?>
            </div>

        </div>
    </div>
</div>

<?php echo $this->renderFile('@frontend/views/layouts/partials/_footer.php'); ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
