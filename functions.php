<?

$table_excel = [];
$table_db = [];

// DB vars
$host = 'localhost';
$dbname = 'markavolga_scrpt';
$login = 'markavolga_scrpt';
$password = 'markavolgadb2020nlf';
$db = getDbConnect($host, $dbname, $login, $password);

$table_name = 'filter_country';

$file_path_data_set_all_data = 'files/data-set_all_data.xls';

function getDbConnect($host, $dbname, $login, $password){
    $db = new PDO("mysql:host=$host;dbname=$dbname", $login, $password);
    $db->exec("SET CHARACTER SET utf8");
    return $db;
}

function getQuery($db, $sql){
    $stmt = $db->query($sql);
    $rows = $stmt->fetchAll();
    return $rows;
}
function getQueryObj($db, $sql){
    $stmt = $db->query($sql);
    $rows = $stmt->fetchAll(PDO::FETCH_CLASS);
    return $rows;
}

?>