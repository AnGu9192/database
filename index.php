<?php
require_once "Db.php";
//Design Patterns
//Singleton
$db = Db::getInstance();
//$user = $db->select('users')
//    ->where(["id"=>1])
//    ->first();
//$users = $db->select('users')->all();
//$users = $db->select('users')->where(["id"=>1])->all();
//$users = $db->insert('users',[
//    'firstname'=>'New',
//    'lastname'=>'New',
//]);
//$db->delete('users')->where(['id'=>4]);
$users = $db->select('users')->paginate(10);
//$users = $db->select('users')->join("bla bla "); ToDo
echo $users["links"];

//$db->update('users',['firstname'=>'Changed'])->where(['id'=>1]);
var_dump($users);
