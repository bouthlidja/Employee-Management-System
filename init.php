<?php
include "connect.php";
//rootes
$tpl = "includes/templates/";    //template directory
$func = "includes/functions/";    // functions directory
$lib = "../includes/libraris/";    // libraris directory
$css = "layout/css/";            // css directory
$js = "layout/js/";             // js directory 


// include the important files
include $func . "functions.php";
include $tpl . "header.php";

// include $tpl . "sidebar.php"; 
// include $tpl . "navbar.php";



//Include navbar file on all pages except the page containing variable $noNavbar
// if (!isset($noNavbar)) { include $tpl . "navbar.php"; }
// if (isset($php)) { include $func . "functions.php"; }
// if (!isset($noSidebar)) { include $tpl . "sidebar.php"; }




?>