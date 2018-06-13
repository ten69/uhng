<?php 
    use backend\models\Cauhinh;
    use common\components\Tuyen;

    $cache = Aabc::$app->dulieu; 
    $data = $cache->get('menu');

    // $menu = $cache->get('cauhinh'.Cauhinh::menu);
    // $menu = json_decode($menu,true);

    $menu = Tuyen::_dulieu('cauhinh',Cauhinh::menu, 'array');

    // echo '<pre>';
    // print_r($menu);
    // echo '</pre>';
?>


<nav class="hidden-sm hidden-xs">
    <style type="text/css">
        .subcate.gd-menu{
            background: #fff;
            overflow: visible !important;
        }
    </style>

    <div class="col-md-3 no-padding">
        <div class="mainmenu ">
            <div class="line">
                <i></i>
                <i></i>
                <i></i>
            </div>
            <span>Danh mục sản phẩm</span>
            <div class="nav-cate">
                <ul id="menu2017">


                    <?php
                    if(!empty($data)) {
                    foreach ($data as $k_0 => $v_0) {
                        echo '<li '.($k_0 > 9?"style=\"display: none;\"":"").' class="dropdown menu-item-count '.($k_0 > 9?"toggleable":"").'">';
                        echo '<h3>      
                                <img src="'.$v_0['icon'].'" alt=""/>                          
                                <a href="'.$v_0['link'].'">'.$v_0['label'].'</a>
                            </h3>';
                        if(!empty($v_0['child']) && is_array($v_0['child'])){
                            echo '<div class="subcate gd-menu">'; 
                            echo '<img src="'.$v_0['background'].'" style="position: absolute;right: -52px;bottom: 0px;">';                           
                            foreach ($v_0['child'] as $k_1 => $v_1) {
                                echo '<aside>';
                                echo '<strong>
                                        <a href="'.$v_1['link'].'">'.$v_1['label'].'</a>
                                    </strong>';
                                if(!empty($v_1['child']) && is_array($v_1['child'])){
                                    foreach ($v_1['child'] as $k_2 => $v_2) {
                                        echo '<a href="'.$v_2['link'].'" class="">'.$v_2['label'].'</a>';
                                    }
                                }
                                echo '</aside>';
                            }                            
                            echo '</div>';
                        }

                        echo '</li>';
                    }
                    }   

                    ?>

                    
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-9 no-padding" style="padding: 0 0 0 10px !important;">
        <ul id="nav" class="nav">
            <div class="m-info hide">Module Menu</div>
            <?php foreach ($menu as $k => $v) { ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $v['l']?>"><?= $v['k']?></a>
                </li>
            <?php } ?>
            
            </ul>
    </div>
</nav>