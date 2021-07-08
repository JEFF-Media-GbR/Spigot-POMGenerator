<?php

require_once("funcs.php");



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>
        Spigot POM Generator - JEFF Media GbR
    </title>
</head>
<body>

<?php
if(!isset($_GET['action'])) {
    require("form.php");
} else {
    switch ($_GET['action']) {
        case "result":
            require("form.php");
            require ("result.php");
            break;
    }
}

?>

</body>
</html>
