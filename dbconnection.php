<?php

    $dbcon = mysqli_connect('localhost','root','#Balaji@2003','dosharedb');

    if($dbcon==false)
    {
        echo "Database is not Connected!";
    }
   
?>