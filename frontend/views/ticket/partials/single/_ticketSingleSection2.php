<?php

use common\models\ticketDecoration\TicketDecorationLink;

//Ticket Decoration Bar displays the Ticket decorations
/* @var $this yii\web\View */
/* @var $model common\models\Ticket */
/* @var $isKanBan boolean */
/* @var $fixedHeightTicketView boolean */

    $section = 2;
    $sectionOccupied = false;

    // Test if any decorations should be placed in this section
    foreach ($model->getDecorations() as $ticketDecoration) {
        if ($ticketDecoration->displaySection == $section) {
            $sectionOccupied = true;
            break;
        }
    }

    if ($isKanBan && $sectionOccupied) {
        echo $this->render('@frontend/views/ticket/partials/single/_ticketSingleDecorations', [
            'model' => $model,
            'section' => $section,
            'fixedHeightTicketView' => $fixedHeightTicketView,
            ]
        );
    } else {
        echo $this->render('@frontend/views/ticket/partials/single/_ticketSingleTags', [
            'model' => $model
            ]
        );
    }

?>