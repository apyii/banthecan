<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $activity array */
/* @var $news ActiveRecord */
/* @var $board ActiveRecord */
/* @var $newTickets ActiveRecord */
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>
            <?php echo $board ? Html::encode($board->title) : ''; ?>
        </h1>
        <em class="lead">
            <?php echo $board ? Html::encode($board->description) : ''; ?>
        </em>
    </div>

    <div class="body-content">

        <div class="row">

            <div class="col-lg-6">
                <h2><?php echo \Yii::t('app', 'Recent Activity'); ?></h2>

                <h3><?php echo \Yii::t('app', 'Tickets'); ?></h3>
                <table class="table table-condensed table-striped">
                    <tbody>
                    <?php
                    foreach ($newTickets as $k => $v) {
                        echo '<tr><td>'
                            . $v->title
                            . '</td><td>'
                            . $v->getUpdateUser()->username
                            . '</div></td></tr>';
                    }
                    ?>
                    </tbody>
                </table>

                <h3><?php echo \Yii::t('app', 'Boards'); ?></h3>
                <table class="table table-condensed table-striped">
                    <thead>
                    <tbody>
                    <?php
                    foreach ($activity as $k => $v) {
                        echo '<tr><td>' . \Yii::t('app', $k) . '</td><td>' . $v . ' ' . \Yii::t('app', 'Updates') . '</td></tr>';
                    }
                    ?>
                    </tbody>
                </table>

            </div>

            <div class="col-lg-6">
                <h2><?php echo \Yii::t('app', 'Site News'); ?></h2>
                <table class="table table-condensed table-striped">
                    <thead>
                    <tr>
                        <th><?php echo \Yii::t('app', 'Date'); ?></th>
                        <th><?php echo \Yii::t('app', 'Event'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($news as $k => $v) {
                        echo '<tr><td>'
                            . Yii::$app->formatter->asDate($v->updated_at, 'long')
                            . '</td><td><div title="' . $v->description . '">'
                            . $v->title
                            . '</div></td></tr>';
                    }
                    ?>
                    </tbody>
                </table>
            </div>

        </div>

    </div>
</div>
