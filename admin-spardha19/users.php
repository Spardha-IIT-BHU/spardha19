<html>
<head>
<title>Users</title>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <a href="401.php" class="btn btn-danger pull-right">Logout</a>
            &nbsp;
            <a href="users_export.php" class="btn btn-success">Export Users (.csv)</a>
            &nbsp;  &nbsp;
            <a href="events_export.php" class="btn btn-success">Export Events (.csv)</a>
        </div>
        <div class="panel-body table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                      <th>Id</th>
                      <th>Email</th>
                      <th>Username</th>
                      <th>Name</th>
                      <th>Designation</th>
                      <th>Institute Name</th>
                      <th>Total Boys</th>
                      <th>Total Girls</th>
                      <th>Total Officials</th>
                      <th>Phone Number</th>
                      <th>Events Registered</th>
                      <th> </th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $mode = "users";
                    include("save_log.php");
                    //include database configuration file
                    include('../register/db.php');
                    
                    //get records from database
                    $query = "SELECT * FROM `users`";
                    $result = mysqli_query ($conn, $query);
                    $query2 = "SELECT * FROM `events`";
                    $result2 = mysqli_query ($conn, $query2);
                    if(mysqli_num_rows ($result) > 0){ 
                        while($row = mysqli_fetch_assoc($result)){
                            $row2 = mysqli_fetch_row($result2);
                    ?>
                    <?php if ($row['status'] != '1') continue; ?>            
                    <tr>
                      <td><?php echo $row['id']; ?></td>
                      <td><?php echo $row['email']; ?></td>
                      <td><?php echo $row['username']; ?></td>
                      <td><?php echo $row['name']; ?></td>
                      <td><?php echo $row['designation']; ?></td>
                      <td><?php echo $row['institute_name']; ?></td>
                      <td><?php echo $row2[3]; ?></td>
                      <td><?php echo $row2[4]; ?></td>
                      <td><?php echo $row2[5]; ?></td>
                      <td><?php echo $row['phone_number']; ?></td>
                      <td><?php
                      $events = "";
                      $cnt = 0;
                      $events_list = array('id', 'email', 'institute_name', 'boys', 'girls', 'officials', 'contingent_leader', 'contingent_phone', 'aquatics', 'athletics', 'badminton', 'basketball', 'boxing', 'carrom', 'chess', 'cricket', 'football', 'handball', 'hockey', 'kabaddi', 'kho-kho', 'powerlifting', 'squash', 'taekwondo', 'table-tennis', 'tennis', 'volleyball', 'weightlifting', 'f-aquatics', 'f-athletics', 'f-badminton', 'f-basketball', 'f-boxing', 'f-carrom', 'f-kabaddi', 'f-kho-kho', 'f-taekwondo', 'f-table-tennis', 'f-tennis', 'f-volleyball');
                      for ($i = 8; $i <= 39; $i++) {
                        if ($row2[$i] == 'Y') {
                            $events .= $events_list[$i];
                            $events .= ', ';
                            $cnt++;
                        }
                      }
                      $events = substr($events, 0, -2);
                      echo $events;
                      ?></td>
                      <td><?php echo $cnt ?></td>
                    </tr>
                    <?php } }else{ ?>
                    <tr><td colspan="9">No user(s) found.....</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>