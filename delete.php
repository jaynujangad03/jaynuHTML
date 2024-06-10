<?php

    include_once('connect.php');

    $id = $_POST['id'] ?? null;

    if(!$id){
        header ('Location: index.php');
        exit;

    }
// echo '<pre>';  
// var_dump($_FILES);
// echo '</pre>';  

    $statement = $conn->prepare("DELETE FROM studentinfo WHERE s_id = :id");
    $statement->bindValue(':id',$id);
    $statement->execute();
    
    header('Location: index.php');

