<?php
if($_GET['css']){
    include_once('do_not_edit.php');
    $css = '';
    $root = $_SERVER['DOCUMENT_ROOT'].'/css/'; //directory where the css lives
    $files = explode(',',$_GET['css']);
    if(sizeof($files)){
        foreach($files as $file)
        {
            $css.= (is_file($root.$file.'.css') ? file_get_contents($root.$file.'.css') : '');
        }
    }
    header("Content-type: text/css", true);
    echo str_replace('; ',';',str_replace(' }','}',str_replace('{ ','{',str_replace(array("\r\n","\r","\n","\t",'  ','    ','    '),"",preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!','',$css)))));
}