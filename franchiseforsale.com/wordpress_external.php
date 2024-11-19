<?php
if(in_array(@$_GET['section'],['head','header','filter','footer'])){
    include_once('includes/global.php');
    include_once('includes/'.$_GET['section'].'.php');
    die;
}