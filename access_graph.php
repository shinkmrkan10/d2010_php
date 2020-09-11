<html>

<head>

    <title>Access Graph</title>

</head>
<body>
<?php

require_once ('db_login.php');

require_once ('DB.php');

$connection = DB::connect("mysql://$db_username:$db_password@$db_host/$db_database");

if (DB::isError($connection)) {

    die ("Could not connect to the database :  <br>" . DB::errorMessage($connection));

}

$query = "SELECT * FROM a_log order by id desc";

$result = $connection->query($query);

if (DB::isError($result))

{

    die ("Could not query the database : <br>".$query. " ".DB::errorMessage($result));

}
echo '<br/><strong>Access log </strong>(last 10 access) 最近のアクセスログ10件<br />';

echo '<table border>';

echo '<tr><th>id</th><th>timestamp</th><th>ip</th><th>agent</th>';

echo '<th>date</th><th>time</th></tr>';


for ($i=0;$i<10;$i++)
{
    $result_row = $result->fetchRow(DB_FETCHMODE_ASSOC);

    echo '<tr class="box2-content"><td>';

    echo $result_row["id"] . '</td><td>';

    echo $result_row["time_st"] . '</td><td>';

    echo $result_row["a_ip"] . '</td><td>';

    echo $result_row["a_agent"] . '</td><td>';

    echo $result_row["a_date"] . '</td><td>';

    echo $result_row["a_time"].'</td></tr>';

}

echo "</table><br />";

echo "30日間のアクセス数グラフ<br />" ;

echo "<table>";
//    クエリを作成する。日付ごとのアクセス総数
$query = "SELECT  a_date,count(a_date) as d_count  FROM  counter group by a_date order by a_date desc " ;
//    クエリの実行
$result = $connection->query($query);
if (!$result){
    die ("Could not query the database: <br />". mysql_error(  ));
}

//    結果から行を取得して表示する
for ($i=0;$i<30;$i++)
{
    $result_row = $result->fetchRow(DB_FETCHMODE_ASSOC);

    $a_date = $result_row["a_date"] ;
    $a_count = result_$row["d_count"] ;
    echo "<tr>" ;
    echo "<td>$a_date</td>" ;
    printf("<td><pre><strong>(%3d)</strong></pre></td>",$a_count) ;
    echo "<td>" ;
    for ($j=0;$j<$a_count;$j++)
    {
        if (($j+1)%100 == 0){
            echo "||" ;
        }
        elseif (($j+1)%10 == 0){
            echo "|" ;
        }
        if ($j == 0){
            echo "|" ;
        }
        if ($j%5 == 2){
            echo "*" ;
        }
    }
    echo "</td>" ;
    echo "</tr>" ;
}

echo "</table>" ;

$connection->disconnect( );

?>

</body>

</html>