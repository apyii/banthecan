<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $ticket common\models\Ticket */
/* @var $showTags boolean switch for tags display*/

//Ticket Decoration Bar displays the Ticket decorations
if ($taglist = $ticket->tagNames) {

	echo Html::beginTag('div', ['class' => 'ticket-single-tags']);

	$tagArray = explode(',', $taglist);
	$tagCount = count($tagArray);
	$i = 1;
	foreach ($tagArray as $tag) {
		$carouselItems[] = [
            'content' => '',
            'caption' => "$tag&nbsp;&ndash;&nbsp;<small>($i/$tagCount)</small>",
		];
		$i++;
	}

	/*echo Carousel::widget([
        'items' => $carouselItems,
        'controls' => false,
        'showIndicators' => false,
	]);*/

	echo Html::endTag('div');

}

?>