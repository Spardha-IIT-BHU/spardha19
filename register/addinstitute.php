<?php
if (!isset($institute_name))
    $institute_name = "IIT (BHU) VARANASI";
$inp = file_get_contents('../institutelist.json');
$tempArray = json_decode($inp, true);
$len = sizeof($tempArray);
$flag = 0;
foreach ($tempArray as $tmp) {
    if ($tmp['name'] == $institute_name) {$flag = 1; break;}
}
if ($flag == 0) {
    $data = array("id"=>$len+1, "name"=>$institute_name);
    array_push($tempArray, $data);
    $jsonData = json_encode($tempArray, JSON_PRETTY_PRINT);
    file_put_contents('../institutelist.json', $jsonData);
}
?>
