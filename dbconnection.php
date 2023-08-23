<?php

    $dbcon = mysqli_connect('localhost','root','#Balaji@2003','courierdb');

    if($dbcon==false)
    {
        echo "Database is not Connected!";
    }
   
?>