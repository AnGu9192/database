<?php
class Db
{
public static $instance;
public $count = 0;
public $pageSize;
public $sql = null;
private $connection;

public function __construct()
{
$this->connect();
}

static public function getInstance()
{
if (!self::$instance) {
self::$instance = new self;
}

return self::$instance;
}

public function connect()
{
$config = parse_ini_file("config/db.ini");
$this->connection = new MySqli($config["HOST"], $config["USER"], $config["PASSWORD"], $config["DB"]);
}

public function first()
{
$result = $this->query();
return (object)$result->fetch_assoc();
}

public function all()
{
$data = [];
$result = $this->query();


while ($row = $result->fetch_assoc()) {
$data[] = (object)$row;
}

return $data;

}

public function select($table, $what = '*')
{

$this->sql = "SELECT $what FROM `$table`";

$this->count = $this->count();

return $this;
}

public function where($conditions)
{
$whereArr = [];
foreach ($conditions as $field => $value) {
$whereArr[] = $field . "='" . $value . "'";
}

$whereStr = implode(' AND ', $whereArr);
$this->sql .= " WHERE $whereStr";
$this->query();
$this->count = $this->count();
return $this;
}

public function insert($table, $data)
{
$fieldsArr = [];
$valuesArr = [];
foreach ($data as $fields => $value) {
$fieldsArr[] = "`" . $fields . "`";
$valuesArr[] = "'" . $value . "'";
}
$fields = implode(',', $fieldsArr);
$values = implode(',', $valuesArr);
$this->sql = "INSERT INTO $table ($fields) VALUES ($values)";
return $this->query();
}

public function update($table, $conditions)
{
$valuesArr = [];
foreach ($conditions as $fields => $value) {
$valuesArr[] = $fields . "='" . $value . "'";
}
$values = implode(',', $valuesArr);

$this->sql = "UPDATE $table SET  $values";

return $this;

}

public function delete($table)
{
$this->sql = "DELETE FROM $table";
return $this;
}

public function paginate($pageSize = 20, $paginationClass = "pagination", $queryParam = "page")
{
$page = 1;
$this->pageSize = $pageSize;
if (isset($_GET[$queryParam])) {
$page = $_GET[$queryParam];
}
$offset = ($page - 1) * $pageSize;
$this->limit($pageSize);
$this->offset($offset);

$data = $this->all();

$data['links'] = $this->links($paginationClass, $queryParam);

return $data;
}


public function limit($limit)
{
$this->sql .= " LIMIT $limit";

}

public function offset($offset)
{
$this->sql .= " OFFSET  $offset";

}

public function query()
{
return $this->connection->query($this->sql);
}

public function links($paginationClass = "pagination", $queryParam = 'page')
{
$pagesCount = ceil($this->count / $this->pageSize);

$links = '<ul class="' . $paginationClass . '">';
    for ($page = 1; $page <= $pagesCount; $page++) {
    $links .= "<li>";
        $links .= '<a href="?' . $queryParam . '=' . $page . '">';
            $links .= $page;
            $links .= '</a>';
        $links .= "</li>";
    }

    $links .= "</ul>";
return $links;
}

public function leftJoin($table1Field, $table2Field)
{
$this->sql .= " LEFT JOIN $table2Field[0] on $table1Field[0].$table1Field[1] = $table2Field[0].$table2Field[1]";
return $this;
}

public function rightJoin($table1Field, $table2Field)
{
$this->sql .= " RIGHT JOIN $table2Field[0] on $table1Field[0].$table1Field[1] = $table2Field[0].$table2Field[1]";
return $this;

}

public function innerJoin($table1Field, $table2Field)
{
$this->sql .= " INNER JOIN $table2Field[0] on $table1Field[0].$table1Field[1] = $table2Field[0].$table2Field[1]";
return $this;

}


public function whereLike($conditions)
{
$whereArr = [];
foreach ($conditions as $field => $value) {
$whereArr[] = $field . " LIKE '" . $value . "'";
}

$whereStr = implode(' AND ', $whereArr);
$this->sql .= " WHERE $whereStr";
$this->query();
$this->count = $this->count();

return $this;

}


public function count()
{
$this->count = count($this->all());
return $this->count;
}

public function orderBy($field, $sortingType = 'ASC')
{
$this->sql .= " ORDER BY $field $sortingType";
return $this;
}

}