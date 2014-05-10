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
$db = dbConnect();
        //$select = $db->query("SELECT * FROM posts");
$select = $db->query("SELECT pid FROM `posts_status` WHERE (`closed` = 0) AND (main = 1)");
date_default_timezone_set('Asia/Shanghai');

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
    <link href="styles/buttons.css" rel="stylesheet">
    <link href="jqm/jquery.mobile-1.4.2.css" rel="stylesheet">
    <link rel="stylesheet" href="themes/new.min.css" />
    <link rel="stylesheet" href="themes/jquery.mobile.icons.min.css" />
    <script src="jqm/jquery-1.11.1.min.js"></script>
    <script src="jqm/jquery.mobile-1.4.2.min.js"></script>
    <script src="jquery.form.min.js"></script>

    <!--script src="https://s3.amazonaws.com/codiqa-cdn/codiqa.ext.js"></script-->


</head>

<body>


    <div data-role="page" data-control-title="pg_home" id="pg_home">
        <!--view事件页面-->
        <div id="idHeader" data-theme="a" data-role="header">
            <h1 class="ui-title">
                Share
            </h1>
        </div>

        <ul data-role="listview" data-inset="true">
            <?php
            while($post = $select->fetch()) {
                $pid = $post['pid'];
                $getpost = $db->query("SELECT title, slots, expire FROM posts where pid = $pid");
                while($row = $getpost->fetch()) {
                    $url = '"viewpost.php?pid=' . $pid . '"';
                        //echo "<a href=$url><h2>" . $row['title'] . "</h2></a>";
                    echo '<li>
                    <a href=';
                    echo $url . '>
                    <img id="postLogo" src="img/laundry.png">
                    <h2 id="postTitle">' . $row['title'] . '</h2>';
                    $getattend = $db->query("SELECT id FROM posts_status where pid = $pid");
                    $count = 0;
                    while($doget = $getattend->fetch()){
                        $count++;
                    }
                    $count = $row['slots'] - $count;
                        //echo '<h2>' . $count . '</h2>';
                        //echo '<h2>' . counttime($row['expire'], 'now') . '</h2>
                    $expire = counttime($row['expire'], "now");
                    if ($expire[0] == '-') {
                        $expire = 'Ended';

                    }
                    echo '
                    <div class="ui-grid-c">
                        <div class="ui-block-a">

                        </div>
                        <div class="ui-block-b">

                        </div>
                        <div class="ui-block-c">
                            <img src="img/time.png" alt="Time Left" class="ui-li-icon">
                            <p class="descText" id="timeLeft"> Time Left: ' . $expire . '</p>';
                            echo '</div>
                            <div class="ui-block-d">
                            <img src="img/seat.png" alt="Time Left" class="ui-li-icon">
                                <p class="descText" id="numVanct"> ' . $count . ' Vacancy(ies)</p>
                            </div>
                        </div>
                    </a>
                    <!-- /grid-c -->

                </li>';
            }
        }
        ?>

    </ul>
    <div data-role="footer" data-position="fixed">
        <div data-role="navbar">
            <ul>
            <li ><a href="createEvent.html" data-icon="plus">Add New Post</a></li>
            </ul>
        </div>
    </div>
</div>
</body>

</html>