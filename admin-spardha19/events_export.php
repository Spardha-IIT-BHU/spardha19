<?php
$mode = "events_export";
include("save_log.php");
include('../register/db.php');
$delimiter = ",";

if( is_dir("events") === false ) mkdir("events");
$events = array('aquatics', 'athletics', 'badminton', 'basketball', 'boxing', 'carrom', 'chess', 'cricket', 'football', 'handball', 'hockey', 'kabaddi', 'kho-kho', 'powerlifting', 'squash', 'taekwondo', 'table-tennis', 'tennis', 'volleyball', 'weightlifting', 'f-aquatics', 'f-athletics', 'f-badminton', 'f-basketball', 'f-boxing', 'f-carrom', 'f-kabaddi', 'f-kho-kho', 'f-taekwondo', 'f-table-tennis', 'f-tennis', 'f-volleyball');
$maxplayer = array(1, 1, 5 ,12, 20, 4, 6, 16, 16, 12, 16, 12, 12, 10, 4, 4, 16, 4, 12, 10, 1, 1, 4, 12, 20, 4, 12, 12, 4, 16, 4, 12);
$query = "SELECT * FROM `users`";
$result = mysqli_query ($conn, $query);
$notactive = array();
if(mysqli_num_rows ($result) > 0){
  while($row = mysqli_fetch_assoc($result)) if ($row['status'] != '1') array_push($notactive, $row['id']);
}

// $f = fopen('events/events.csv', 'w');
// $fields = array('ID', 'Email', 'Institute Name', 'Total Boys', 'Total Girls', 'Total Officials', 'Contingent Leader', 'Contingent Phone');
// $fields = array_merge($fields, $events);
// fputcsv($f, $fields, $delimiter);

$cur = 0;
foreach ($events as $event) {
  $f = fopen('events/' . $event . '.csv', 'w');
  $fields = array('ID', 'Email', 'Institute Name', 'Captain Name', 'Captain Phone');
  if ($event == 'aquatics' || $event == 'athletics') array_push($fields, "No. of Boys");
  else if ($event == 'f-aquatics' || $event == 'f-athletics') array_push($fields, "No. of Girls");
  else if ($event == 'boxing' || $event == 'f-boxing') array_push($fields, '46kg - 49kg', '46kg - 49kg', '49kg - 52kg', '49kg - 52kg', '52kg - 56kg', '52kg - 56kg', '56kg - 60kg', '56kg - 60kg', '60kg - 64kg', '60kg - 64kg', '64kg - 69kg', '64kg - 69kg', '69kg - 75kg', '69kg - 75kg', '75kg - 81kg', '75kg - 81kg', '81kg - 91kg', '81kg - 91kg', '91kg and above', '91kg and above');
  else if ($event == 'powerlifting') array_push($fields, 'Below 59kg', 'Below 59kg', '59kg - 66kg', '59kg - 66kg', '66kg - 74kg', '66kg - 74kg', '74kg - 83kg', '74kg - 83kg', '83kg and above', '83kg and above');
  else if ($event == 'taekwondo' || $event == 'f-taekwondo') array_push($fields, 'Fin', 'Fin', 'Fly', 'Fly', 'Bantham', 'Bantham', 'Feather', 'Feather', 'Light', 'Light', 'Welter', 'Welter', 'Middle', 'Middle', 'Heavy', 'Heavy');
  else if ($event == 'weightlifting') array_push($fields, 'Below 56kg', 'Below 56kg', '56kg - 62kg', '56kg - 62kg', '62kg - 69kg', '62kg - 69kg', '69kg - 77kg', '69kg - 77kg', '77kg - 85kg', '77kg - 85kg');
  else
  for ($i = 1; $i <= $maxplayer[$cur]; $i++)
    array_push($fields, "P" . $i);
  fputcsv($f, $fields, $delimiter);
  $cur++;
}

$query = "SELECT * FROM `events`";
$result = mysqli_query ($conn, $query);
if(mysqli_num_rows ($result) > 0){
  $f = fopen('events/events.csv', 'a');
  while ($row = mysqli_fetch_row($result)) {
    $flag = 0;
    foreach($notactive as $notactiveid) {
      if ($notactiveid == $row[0]) $flag = 1;
    }
    if ($flag) continue;
    fputcsv($f, $row, $delimiter);
  }
}

$query = "";
foreach ($events as $event) {
  $query .= "SELECT * FROM `" . $event . "`; ";
}

$idx = 0;
if (mysqli_multi_query($conn, $query)) {
  do {
    $f = fopen('events/' . $events[$idx++] . '.csv', 'a');
    if ($result = mysqli_store_result($conn)) {
      while ($row = mysqli_fetch_row($result)) {
        $flag = 0;
        foreach($notactive as $notactiveid) {
          if ($notactiveid == $row[0]) $flag = 1;
        }
        if ($flag || $row[3] == 'N') continue;
        array_splice($row, 3, 1);  
        fputcsv($f, $row, $delimiter);
      }
    }
  } while (mysqli_more_results($conn) && mysqli_next_result($conn));
}
else if (mysqli_connect_errno()) {
  echo "Could not connect: ". mysqli_connect_error($conn);
}
$zipname = "events_" . date('Y-m-d') . ".zip";
$zip = new ZipArchive;
$zip->open($zipname, ZipArchive::CREATE);
// $zip->addFile('events/events.csv');
foreach ($events as $file) {
  $zip->addFile('events/' . $file . '.csv');
}
$zip->close();

header('Content-Type: application/zip');
header('Content-disposition: attachment; filename=' . $zipname);
header('Content-Length: ' . filesize($zipname));
readfile($zipname);
?>
