<?php
include dirname(__DIR__) . "/config/connection.php";

function selectOne($table, $conditions = [], $what = '*'){

    $whereArr = [];
    $data = [];

    foreach ($conditions as $field => $value){
        $whereArr[] = $field . "='".$value."'";
    }

    $whereStr = implode(' AND ',$whereArr);

    $sql = "SELECT $what FROM $table WHERE $whereStr";
    $result = query($sql);
    $row = mysqli_fetch_assoc($result);

    return $row;
}

function select($table, $conditions = [], $what = '*'){
    $whereArr = [];
    $data = [];
    foreach ($conditions as $field => $value){
        $whereArr[] = $field . "='".$value."'";
    }

    $whereStr = implode(' AND ',$whereArr);

    $sql = "SELECT $what FROM $table WHERE $whereStr";

    $result = query($sql);

    while ($row = mysqli_fetch_assoc($result))
    {
        $data[] = $row;
    }

    return $data;
    
}

function insert($table,$data){
    $fieldsArr = [];
    $valuesArr = [];
    foreach ($data as $fields => $value){
        $fieldsArr[] = "`" . $fields . "`";
        $valuesArr[] = "'" . $value . "'";
    }
    $fields = implode(',',$fieldsArr);
    $values = implode(',',$valuesArr);
    $sql = "INSERT INTO $table ($fields) VALUES ($values)";
    return query($sql);
}

function update($table, $conditions, $id){
    $valuesArr = [];
    foreach ($conditions as $fields => $value){
        $valuesArr[] = $fields .  "='" . $value . "'";
    }
    $values = implode(',',$valuesArr);

    $sql = "UPDATE $table SET  $values WHERE  id=$id";
    var_dump($sql);
    return query($sql);

}


function delete($table, $id){
    $sql = "DELETE FROM $table WHERE  id=$id";
    return query($sql);

}

function upload(){
//Todo
 
} 


function paginate($table, $conditions,$count=20,$what = '*'){
    $whereArr = [];
    $data = [];

    foreach ($conditions as $field => $value){
        $whereArr[] = $field . "='".$value."'";
    }

    $whereStr = implode(' AND ',$whereArr);
    $page = 1;
    if(isset($_GET["page"])){
        $page = $_GET["page"];
    }
    $offset = ($page-1)*$count;
    $sql = "SELECT $what FROM $table WHERE $whereStr   LIMIT $count OFFSET $offset";
    $result = query($sql);
    while ($row = mysqli_fetch_assoc($result))
    {
        $data[] = $row;
    }

    return $data;
}

function query($sql){
    global $connection;
    return mysqli_query($connection, $sql);
}




