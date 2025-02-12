<?php
require("connectmsql.php");

function parseToXML($htmlStr)
{
$xmlStr=str_replace('<','&lt;',$htmlStr);
$xmlStr=str_replace('>','&gt;',$xmlStr);
$xmlStr=str_replace('"','&quot;',$xmlStr);
$xmlStr=str_replace("'",'&#39;',$xmlStr);
$xmlStr=str_replace('>','&gt;',$xmlStr);
$xmlStr=str_replace('"','&quot;',$xmlStr);
$xmlStr=str_replace('"','&quot;',$xmlStr);
return $xmlStr;
}


$query = "SELECT * FROM user;";
$result = mysqli_query($mysqli,$query);
if (!$result) {
  die('Invalidproyecto query: ' . mysqli_error());
}

header("Content-type: text/xml");


echo "<?xml version='1.0' ?>";
echo '<markers>';
$ind=0;

while ($row = @mysqli_fetch_assoc($result)){

  echo '<marker ';
  echo 'IdUsuario="' . $row['id'] . '" ';
  echo 'Nombre="' . $row['username'] . '" ';
  echo 'Descripcion="' . $row['description'] . '" ';
  echo 'Mail="' . $row['email'] . '" ';
  echo '/>';
  $ind = $ind + 1;
}


echo '</markers>';

?>
