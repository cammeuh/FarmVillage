<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html lang="en" xml:lang="en">
<head>
	<title>Index FarmVillage</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />

</head>
<?php
    set_include_path('..'.DIRECTORY_SEPARATOR.'Model'.DIRECTORY_SEPARATOR.'DAO');
    foreach (glob('..'.DIRECTORY_SEPARATOR.'Model'.DIRECTORY_SEPARATOR.'DAO/*.php') as $filename)
    {
        include_once $filename;
    }
    //require_once('Unite.class.php');
    set_include_path('..'.DIRECTORY_SEPARATOR.'Model'.DIRECTORY_SEPARATOR.'Externe');
    //require_once('Unite.class.php');
    set_include_path('..'.DIRECTORY_SEPARATOR.'Model'.DIRECTORY_SEPARATOR.'Helper');
    require_once('Constant.php');
    set_include_path('..'.DIRECTORY_SEPARATOR.'Model'.DIRECTORY_SEPARATOR.'Metier');
    require_once('Unite.class.php');
?>
<body>
    <div id="head">
        HEADER
    </div>
    
    <div id="body">
        <?php
            $unite = new Unite();
            $unite->setId(rand(0,100));
            echo $unite->getId();
            echo "<br/>";
            echo CONSTANTEEXEMPLE;
            echo "<br/>";
            echo $unite->test();
        ?>
    </div>
    
    <div id="foot">
        FOOTER
    </div>
</body>
</html>