<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Plan */

$this->title = 'Cập nhật kế hoạch: ' . $model->code;
$this->params['breadcrumbs'][] = ['label' => 'Kế hoạch', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->code];
$this->params['breadcrumbs'][] = 'Cập nhật';

if(empty($ttsp)) $ttsp = '';
?>
<div class="plan-update">


    <?= $this->render('_form', [
        'model' => $model,
        'ttsp' => $ttsp,
        'Congdoans' => $Congdoans,
        'Nhanviens' => $Nhanviens,
    ]) ?>

</div>
