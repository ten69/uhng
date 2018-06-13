<?php 
    use backend\models\Cauhinh;

    
    $cache = Aabc::$app->dulieu; 
    $data = $cache->get('menu');

    $menu = $cache->get('cauhinh'.Cauhinh::menu);
    $menu = json_decode($menu,true);
?>




<div id="menu-mobile">
    <div class="clearfix">
        <!-- <div class="account_mobile" style="">                    
            <ul class="account_text text-center">
                <li>
                    <a class="" href="/" title="">Trang chủ</a>
                </li>                        
            </ul>
        </div> -->
        <ul class="menu-mobile">
            
             <?php if(is_array($menu)) foreach ($menu as $k => $v) { ?>
                <li class="">
                    <a class="submenu-level1-children-a" href="<?= $v['l']?>" title="<?= $v['k']?>">
                        <?= $v['k']?>
                    </a>                
                </li>
            <?php } ?>
            
            <?php
                if(!empty($data)) foreach ($data as $k_0 => $v_0) {
                    echo '<li>';
                    echo '<a class="submenu-level1-children-a" href="'.$v_0['link'].'" title="'.$v_0['label'].'">
                            '.$v_0['label'].'<i class="fa fa-angle-right"></i>
                        </a>';

                    if(!empty($v_0['child']) && is_array($v_0['child'])){
                        echo '<ul class="dropdown-menu submenu-level1-children" role="menu">';
                        foreach ($v_0['child'] as $k_1 => $v_1) {
                            echo '<li>';
                            echo '<a class="submenu-level2-children-a" href="'.$v_1['link'].'" title="'.$v_1['label'].'">
                                    '.$v_1['label'].'<i class="fa fa-angle-right"></i>
                                </a>';
                            if(!empty($v_1['child']) && is_array($v_1['child'])){
                                echo '<ul class="dropdown-menu submenu-level2-children" role="menu">';
                                foreach ($v_1['child'] as $k_2 => $v_2) {
                                    echo '<li>';
                                    echo '<a href="'.$v_2['link'].'" class="">'.$v_2['label'].'</a>';
                                    echo '</li>';
                                }
                                echo '</ul>';
                            }
                            echo '</li>';
                        }                            
                        echo '</ul>';
                    }

                    echo '</li>';
                }  
            ?>
         

        </ul>
    </div>
</div>