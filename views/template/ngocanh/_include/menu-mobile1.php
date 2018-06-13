<?php 
    use backend\models\Cauhinh;
    $cache = Aabc::$app->dulieu; 
    $data = $cache->get('menu');

    $menu = $cache->get('cauhinh'.Cauhinh::menu);
    $menu = json_decode($menu,true);
?>




<div id="menu-mobile">
    <div class="clearfix">
        <div class="account_mobile" style="">                    
            <ul class="account_text text-center">
                <li>
                    <a class="" href="/" title="">Ngọc Anh PC</a>
                </li>                        
            </ul>
        </div>
        <ul class="menu-mobile">
            <?php foreach ($menu as $k => $v) { ?>
                <li class="">
                    <a class="submenu-level1-children-a" href="<?= $v['l']?>" title="<?= $v['k']?>">
                        <?= $v['k']?>
                    </a>                
                </li>
            <?php } ?>
            
            
        </ul>
    </div>
</div>