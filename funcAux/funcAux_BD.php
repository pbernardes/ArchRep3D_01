<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function connectDatabase (){
    $con = mysql_connect('localhost', 'root', '');
    if (!$con)
      {
      die('Could not connect: ' . mysql_error());
      }
      
    return $con;
}

function selectDatabase( $con ){

    $db_selected = mysql_select_db('edificios', $con);

    if (!$db_selected)
      {
      die ("Can\'t use edificios : " . mysql_error());
      }  
   
      
    mysql_set_charset("utf8",$con);
}

?>
