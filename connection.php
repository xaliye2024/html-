<?php
try{
    $pdo = new pdo('mysql:host=localhost;dbname=hrm_management','root','');
 
   
}catch(PDOException $f){
    echo $f->getmessage();
    echo ("waad ku guulaysatay");
}


?>