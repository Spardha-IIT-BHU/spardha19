<?php
$mode = "users_export";
include("save_log.php");
//include database configuration file
include('../register/db.php');

//get records from database
$query = "SELECT * FROM `users`";
$result = mysqli_query ($conn, $query);
$query2 = "SELECT * FROM `events`";
$result2 = mysqli_query ($conn, $query2);
if(mysqli_num_rows ($result) > 0){
    $delimiter = ",";
    $filename = "users_" . date('Y-m-d') . ".csv";
    
    //create a file pointer
    $f = fopen('php://memory', 'w');
    
    //set column headers
    $fields = array('ID', 'Email', 'Username', 'Name', 'Designation', 'Institute Name', 'Total Boys', 'Total Girls', 'Total Officials', 'Phone', 'Events', 'Count');
    fputcsv($f, $fields, $delimiter);
    
    //output each row of the data, format line as csv and write to file pointer
    while($row = mysqli_fetch_assoc($result)){
        $row2 = mysqli_fetch_row($result2);
        $events = "";
        $cnt = 0;
        if ($row['status'] != '1') continue;
        $events_list = array('id', 'email', 'institute_name', 'boys', 'girls', 'officials', 'contingent_leader', 'contingent_phone', 'aquatics', 'athletics', 'badminton', 'basketball', 'boxing', 'carrom', 'chess', 'cricket', 'football', 'handball', 'hockey', 'kabaddi', 'kho-kho', 'powerlifting', 'squash', 'taekwondo', 'table-tennis', 'tennis', 'volleyball', 'weightlifting', 'f-aquatics', 'f-athletics', 'f-badminton', 'f-basketball', 'f-boxing', 'f-carrom', 'f-kabaddi', 'f-kho-kho', 'f-taekwondo', 'f-table-tennis', 'f-tennis', 'f-volleyball');
        for ($i = 8; $i <= 39; $i++) {
            if ($row2[$i] == 'Y') {
                $events .= $events_list[$i];
                $events .= ', ';
                $cnt++;
            }
        }
        $events = substr($events, 0, -2);
        $lineData = array($row['id'], $row['email'], $row['username'], $row['name'], $row['designation'], $row['institute_name'], $row2[3], $row2[4], $row2[5], $row['phone_number'], $events, $cnt);
        fputcsv($f, $lineData, $delimiter);
    }
    
    //move back to beginning of file
    fseek($f, 0);
    
    //set headers to download file rather than displayed
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '";');
    
    //output all remaining data on a file pointer
    fpassthru($f);
}
exit;

?>
