<?php

use yii\helpers\Url;
use yii\web\View;
use yii\web\Session;

$this->title = 'АРМ Оператора';

$session = new Session;
$session->open();

$voidMainJS = '
    
var ap = new itwww( "#itwwwContainer" , 
                    "#status-string" ,
                    "' . $session->id . '" ,
                    "' . Url::toRoute('site/ajax'). '",
                    "' . Url::toRoute('site/logout'). '");

ap.start();

$(".close-card").click(function() {
   ap.closeCard();
   ap.start();
});

';

$this->registerJS($voidMainJS,View::POS_READY, 'itwww-js-processing');
$this->registerJsFile(
                        Yii::$app->request->baseUrl . '/js/operActions.js', 
                        [ 'depends' => [ 'yii\web\JqueryAsset' ] ] 
                     );

?>
<div class="site-index">
    <div class="body-content">
        <div class="row">
            
            <div id="itwwwContainer" style="overflow:hidden;width:100%;height: 1000px;"></div>
            
        </div>
    </div>
</div>
