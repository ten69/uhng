<?php

use barcode\barcode\BarcodeGenerator;


foreach ($model as $k => $v) {
    echo '<div class="barcode"><div id="showBarcode_'.$k.'"></div>'; //the same id should be given to the extension item id  
    echo '<div class="content">'.$v['content'].'</div></div>'; //the same id should be given to the extension item id  
    $optionsArray = [
        'elementId'=> 'showBarcode_'.$k, /*id of div or canvas*/
        'value'=> $v['code'], /* value for EAN 13 be careful to set right values for each barcode type */
        'type'=>'codabar',/*supported types  ean8, ean13, upc, std25, int25, code11, code39, code93, code128, codabar, msi, datamatrix*/ 
        'settings'=>[
                'output'=>'svg' /*css, bmp, canvas note- bmp and canvas incompatible wtih IE*/,
            ],
        ];
    echo BarcodeGenerator::widget($optionsArray);    
}

?>

<style type="text/css">
    .barcode {
        float: left;
        margin: 0 10px;
        width: 140px;
        height: 150px;
    }
    .content{
        font-size: 12px;
        text-align: justify;
    }
</style>

