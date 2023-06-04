<?php
require_once "Db.php";
//Design Patterns
//Singleton
//MVC
$db = Db::getInstance();
//$user = $db->select('users')
//    ->where(["id"=>1])
//    ->first();

//$users = $db->select('users')->all();

//$users = $db->select('users')->where(["id"=>1])->all();
//$users = $db->insert('users',[
//    'firstname'=>'aa',
//    'lastname'=>'aa',
//]);

//$db->delete('users')->where(['id'=>6]);

//$users = $db->select('users')->whereLike(['firstname'=>'%d%'])->paginate(6,"paginate",'tttt');
//$users = $db->select('users')->leftJoin(['users','id'],['projects','user_id'])->paginate(3);
//$users = $db->select('users')
//    ->leftJoin(['users','id'],['projects','user_id'])
//    ->leftJoin(['test','id'],['test','user_id'])
//    ->paginate(3);
//var_dump($users);
//echo $users["links"];
$users = $db->select('users')->orderBy('id', 'desc')->all();
var_dump($users );
//$name = 'Anna';
//$name = $name . " Shahnazaryan";
////$name .= " Shahnazaryan";
//
//$a = 10;
//$a = $a +  2;
//$a +=  2;

//$db->update('users',['firstname'=>'Changed'])->where(['id'=>1]);
