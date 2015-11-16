<?php
    require_once('..'.DIRECTORY_SEPARATOR.'Helper'.DIRECTORY_SEPARATOR.'Constant.php');
    
    foreach (glob(METIERPATH.'*.php') as $filename)
    {
        include_once $filename;
    }
    
    foreach (glob(EXTERNEPATH.'*.php') as $filename)
    {
        include_once $filename;
    }
    
    foreach (glob(DAOPATH.'*.php') as $filename)
    {
        include_once $filename;
    }
?>