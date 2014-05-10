<?php
function dbConnect() {
    include '../protected/project_share.php';
    $db = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    return $db;
}
function counttime($time1,$time2)
{
    $time = strtotime($time1) - strtotime($time2);
    $day = floor($time / 3600 / 24);
    $hour = floor(($time - $day * 3600 * 24) / 3600);
    $min = floor(($time - $hour * 3600 - $day * 3600 * 24) / 60);
    $sec = floor($time - $hour * 3600 - $day * 3600 * 24 - $min * 60);
    $hour += $day * 24;
    return $hour.':'.$min.':'.$sec;
}
date_default_timezone_set('Asia/Shanghai');
$db = dbConnect();
$pid = $_GET['pid'];
$getpost = $db->query("SELECT title, slots, expire, details FROM posts where pid = $pid");
$getstatus = $db->query("SELECT * FROM posts_status where pid = $pid");
while($row = $getpost->fetch()) {
    $title = $row['title'];
        //echo "Slots: " . $row['slots'] . "<br>";
    $status = $getstatus->fetch();
    if ($status['closed'] == 0) {
        $closed = "No";
    } else {
        $closed = "Yes";
    }
        //echo "Closed: " . $closed . "<br>";
    $details = $row['details'];
    $expire = counttime($row['expire'], "now");
    if ($expire[0] == '-') {
        $expire = 'Ended';

    }
        //echo "Attendances: ";
    $count = 1;
    $getuid = $status['uid'];
    $getname = $db->query("SELECT name FROM user_info where uid = $getuid");
    while ($names = $getname->fetch()) {
        $host = $names['name'];
    }
    $attends = "$host" . " (Host)";
    while($attend = $getstatus->fetch()){
        $getuid = $attend['uid'];
        $getname = $db->query("SELECT name FROM user_info where uid = $getuid");
        while ($names = $getname->fetch()) {
            $attends = $attends . ", " . $names['name'];
            $count ++;
        }
    }
    $left = $row['slots'] - $count;
    $attends = $attends . ", " . $left . " vacancy(ies).";
        //echo "<a href='join.php?pid=$pid'>Join</a>";
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link href="styles/codiqa.ext.css" rel="stylesheet">
    <link href="styles/fixinset.css" rel="stylesheet">
    <link href="jqm/jquery.mobile-1.4.2.css" rel="stylesheet">
    <link rel="stylesheet" href="themes/new.min.css" />
    <link rel="stylesheet" href="themes/jquery.mobile.icons.min.css" />
    <script src="jqm/jquery-1.11.1.min.js"></script>
    <script src="jqm/jquery.mobile-1.4.2.min.js"></script>
    <script src="jquery.form.min.js"></script>
    

    
    <!--script src="https://s3.amazonaws.com/codiqa-cdn/codiqa.ext.js"></script-->


</head>

<body>
    <div data-role="page" data-control-title="pg_viewPost" id="pg_viewPost">
        <!--view事件页面-->
        <script>    
        // wait for the DOM to be loaded 
        $(document).ready(function() { 
            // bind 'myForm' and provide a simple callback function 
            $('#join').ajaxForm(function() { 
                alert("Joined!"); 
                $.mobile.changePage( "<?php echo "viewpost.php?pid=" . $pid; ?>", {reloadPage: true},{ allowSamePageTranstion: true},{ transition: 'none'});
            }); 
        }); 
    </script> 
        <div id="idHeader" data-theme="a" data-role="header">
            <a href="#" data-icon="back" data-rel="back" title="Go back">Back</a>
            <h1 class="ui-title">
                View Details
            </h1>
        </div>
        <div role="main" class="ui-content">
            <form id="join" action="join.php?pid=<?php echo $pid; ?>" method="get">
                <ul data-role="listview" data-inset="true">

                    <li>
                        <img src="img/laundry.png">
                        <h4 id="title_v"><?php echo $title; ?></h4>
                    </li>

                    <!--li class="ui-field-contain">
                        <label for="txtTitle_e">Title</label>
                        <h4 id="txtTitle_e">Placeholder Beefy Text</h4>
                    </li-->

                    <li>
                        <div class="ui-grid-a">
                            <div class="ui-block-a">
                                <ul data-role="listview" class="fixinset" data-inset="false">
                                    <li>
                                        <img src="img/user.png" alt="User" class="ui-li-icon ui-corner-none"><?php echo $host; ?>
                                    </li>
                                    <li>
                                        <img src="img/credit.png" alt="Credits" class="ui-li-icon">Credits
                                    </li>
                                </ul>
                            </div>



                            <div class="ui-block-b">
                                <ul data-role="listview" class="fixinset" data-inset="false">
                                    <li>
                                        <img src="img/time.png" alt="Time left" class="ui-li-icon ui-corner-none">Ends in
                                    </li>
                                    <li>
                                        <img src="img/dummy.png" alt="dummypichere" class="ui-li-icon ui-corner-none"><?php echo $expire; ?>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>

                    <li class="ui-field-contain">
                        <label for="pNum_v">Participants:</label>
                        <p id="pNum_v"><?php echo $attends; ?></p>
                    </li>

                    <li class="ui-field-contain">
                        <label for="pDetails_v">Details</label>
                        <p name="details" id="pDetails_v"><?php echo $details; ?></p>
                    </li>
                    <?php if ($expire !== "Ended") {
                        echo '<li class="ui-body ui-body-b">
                        <div class="ui-block">
                            <button type="submit" class="ui-btn ui-corner-all ui-btn-a">Join</button>
                        </div>
                    </li>';
                } ?>
                
            </ul>
        </form>
    </div>
</div>
</body>

</html>