<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function connectDatabase (){
    $con = mysqli_connect('localhost', 'root', '');
    if (!$con)
      {
      die('Could not connect: ' . mysql_error());
      }
      
    return $con;
}

function selectDatabase( $con ){

    $db_selected = mysqli_select_db( $con, 'edificios');

    if (!$db_selected)
      {
      die ("Can\'t use edificios : " . mysql_error());
      }  
   
      
    mysqli_set_charset($con, "utf8");
}

?>
