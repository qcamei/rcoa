<?php

use yii\web\View;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>

<?php  
$js =   
<<<JS
    window.onresize = function(){
        fix();
    }
    fix();
    function fix(){
        var width = $('.wrap').width(),
            height = $('.wrap').height(),
            stageWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth,
            stageHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight,
            targetWidth = stageWidth,
            targetHeight = stageHeight,
            scaleW = targetWidth/width,
            scaleH = targetHeight/height,  
            scale = Math.min(scaleW,scaleH);
	width = width*scale;
	height = height*scale;
        $('.cbp-spmenu-vertical').css('height',height+"px");
        $('#content').css('height',height+"px");
        //$('#content iframe').css('width',width+"px");
        $('#content iframe').css('height',height+"px");
        $('#showLeftPush').css('top',(height >> 1)+"px");
        
    }
        
    var menuLeft = document.getElementById( 'cbp-spmenu-s1' ),
        menuRight = document.getElementById( 'cbp-spmenu-s2' ),
        showLeftImg = document.getElementById( 'showLeftImg' ),
        showLeftPush = document.getElementById( 'showLeftPush' ),
        className = 'col-xs-1 col-sm-1 col-md-1';
    
    if($(window).width() <= 768){
        classie.toggle( menuLeft, 'cbp-spmenu-left');
        classie.toggle( menuRight, 'cbp-spmenu-right');
        classie.toggle( showLeftPush, 'active' );
        showLeftImg.src = '/filedata/image/sidebar-arrow-right.jpg';
        showLeftPush.onclick = function() {
            classie.toggle( this, 'active' );
            classie.toggle( menuLeft, 'cbp-spmenu-left');
            classie.toggle( menuRight, 'cbp-spmenu-right');
            if(showLeftPush.className == className)
                showLeftImg.src = '/filedata/image/sidebar-arrow-left.jpg';
            else
                showLeftImg.src = '/filedata/image/sidebar-arrow-right.jpg';
        };
    }
    else{
        showLeftPush.onclick = function() {
            classie.toggle( this, 'active' );
            classie.toggle( menuLeft, 'cbp-spmenu-left' );
            classie.toggle( menuRight, 'cbp-spmenu-right');
            if(showLeftPush.className == className)
                showLeftImg.src = '/filedata/image/sidebar-arrow-left.jpg';
            else
                showLeftImg.src = '/filedata/image/sidebar-arrow-right.jpg';
        };
    }
JS;
    $this->registerJs($js,  View::POS_READY);
?> 