<?php
//THIS FILE IS A PART OF CROPSATH, LICENSED UNDER GPLv3.
//MADE BY MORICZGERGO A.K.A. SKIILAA
//CREATED: 2017.01.22.
//LAST MODIFIED: 2017.01.22.

include_once "include.php";

$conn = new mysqli($config->sqlServ, $config->sqlUser, $config->sqlPass, $config->dbName);

if (!isset($_GET["q"]) || strlen(trim($_GET["q"])) == 0){
  echo "<center><h1>" . $langfile->req_field_err . "</h1></center>";
  die();
}

if ($conn->connect_error){
  echo "<center><h1>" . $langfile->db_conn_fail . "</h1></center>";
  echo $conn->connect_error;
  die();
}

$sql = "SELECT questionName, id FROM questions WHERE questionName LIKE ? ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$query = "%" . $_GET["q"] . "%";
$stmt->bind_param("i", $query);
$result = $stmt->execute();

if ($result === FALSE){
  echo "<center><h1>" . $langfile->sql_query_err . "</h1><h2>" . $conn->error . "</h2></center>";
  die();
}

$stmt->bind_result($questionName, $id);
$stmt->store_result();

if ($stmt->num_rows > 0){
  $breakIndex = 1;
  while ($stmt->fetch()){
    echo "<a href=\"question.php?id=" . $id . "\"><div class=\"card\" style=\"width: 20rem; display: table-cell;\"><div class=\"card-block\"><h4 class=\"card-text\">" . $questionName . "</h4></div></div></a>";
    $breakIndex++;
    if ($breakIndex == 6) { // "responsivity"
      echo "<br>";
      $breakIndex = 1;
    }
  }
  echo "</center>";
} else {
  echo "<center><h1>" . $langfile->no_q . "</h1></center>";
}
?>
