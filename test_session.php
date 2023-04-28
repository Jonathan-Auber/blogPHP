<?php

session_start();
if(isset($_SESSION['expire']) && time() >= $_SESSION['expire'] && time() <= ($_SESSION['expire'] + 60)) {
    var_dump($_SESSION['expire']);
    var_dump($_SESSION['expire'] + 60);
    var_dump(time());
    echo "ok";
} else {
    echo"pas ok" ;
}

