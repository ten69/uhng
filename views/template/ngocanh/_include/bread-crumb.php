<?php 
use aabc\widgets\Breadcrumbs;


?>        
<div class="container">
<?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
</div>