<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
    "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html lang="en" xml:lang="en">
<head>
    <title>Index FarmVillage</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />

</head>
<?php
    require_once('..'.DIRECTORY_SEPARATOR.'Helper'.DIRECTORY_SEPARATOR.'Constant.php');
    require_once(CONTROLLERPATH.'indexController.class.php');
    
    $controller = new indexController();
?>
<body>
    <div id="head">
        HEADER
    </div>
    
    <div id="body">
        <?php
            //$controller->createTestUnit();
            $controller->initDB();
        ?>
    </div>
    
    <div id="foot">
        FOOTER
    </div>
</body>
</html>