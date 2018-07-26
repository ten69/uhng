<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Plan */

$this->title = 'Thêm mới kế hoạch';
$this->params['breadcrumbs'][] = ['label' => 'Kế hoạch', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plan-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
