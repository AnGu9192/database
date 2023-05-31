<?php
class Db {
    public static $instance;
    public $sql;
    private $connection;

    public function __construct(){
        $this->connect();
    }
    static public function getInstance(){
        if(!self::$instance){
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function connect(){
        $config = parse_ini_file("config/db.ini");
        $this->connection = new MySqli($config["HOST"],$config["USER"],$config["PASSWORD"],$config["DB"]);
    }

    public function first(){
        $result = $this->query();
        return (object)$result->fetch_assoc();
    }
    public function all(){
        $data = [];
        $result = $this->query();
        while ($row = $result->fetch_assoc())
        {
            $data[] = (object)$row;
        }

        return $data;
    }

    public function select($table, $what = '*'){
        $this->sql = "SELECT $what FROM `$table`";

        return $this;
    }

    public function where($conditions){
        $whereArr = [];
        foreach ($conditions as $field => $value){
            $whereArr[] = $field . "='".$value."'";
        }

        $whereStr = implode(' AND ',$whereArr);
        $this->sql .= " WHERE $whereStr";
        $this->query();
        return $this;
    }

    public function insert($table,$data){
        $fieldsArr = [];
        $valuesArr = [];
        foreach ($data as $fields => $value){
            $fieldsArr[] = "`" . $fields . "`";
            $valuesArr[] = "'" . $value . "'";
        }
        $fields = implode(',',$fieldsArr);
        $values = implode(',',$valuesArr);
        $this->sql = "INSERT INTO $table ($fields) VALUES ($values)";
        return $this->query();
    }

    public function update($table, $conditions){
        $valuesArr = [];
        foreach ($conditions as $fields => $value){
            $valuesArr[] = $fields .  "='" . $value . "'";
        }
        $values = implode(',',$valuesArr);

        $this->sql = "UPDATE $table SET  $values";

        return $this;

    }

    public function delete($table){
        $this->sql = "DELETE FROM $table";
        return $this;
    }

    public function paginate($count = 20){

        $page = 1;
        if(isset($_GET["page"])){
            $page = $_GET["page"];
        }
        $offset = ($page-1)*$count;

        $this->limit($count);
        $this->offset($offset);
        $data = $this->all();
        $data['links'] = $this->links();
        return $data;
    }

    public function limit($limit){
        $this->sql .= " LIMIT $limit" ;
    }

    public function offset($offset){
        $this->sql .= " OFFSET  $offset" ;
    }
    public function query(){
        return $this->connection->query($this->sql);
    }

    public function links($paginationClass = "pagination"){
        $pages = 5;
        $links = '<ul class="$paginationClass">';
        for($page = 1; $page <= $pages; $page++){
            $links .= "<li>";
            $links .= $page;
            $links .= "</li>";
        }

        $links .= "</ul>";
//        Links
        return $links;
    }

    public function join(){

    }


}