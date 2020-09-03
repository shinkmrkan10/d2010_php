<html>

<head>

    <title>Welcome to my page!</title>

<?php
error_reporting(0);
$style = "default";
if ($_GET["style"])
        $style = $_GET["style"];

$files = array();
$dh = opendir("styles");
while ($file = @readdir($dh)) {
        if (preg_match("/[.]css$/", $file)) {
                $file = preg_replace("/[.]css$/", "", $file);
                $files[] = $file;
        }
}
?>
<style type="text/css" media="all">@import url(styles/<?php echo($style); ?>.css);</style>
</head>
<body>

<table width="800">
<tr>
<td width="200" class="menu" valign="top">
<div class="menu-active"><a href="home.php">ホーム</a></div>
<div class="menu-inactive"><a href="faq.php">よくある質問</a></div>
<div class="menu-inactive"><a href="contact.php">お問い合わせ</a></div>
</td>
<td width="600" valign="top">

        <table class="box">
        <tr>
        <td class="box-title">
                重要情報
        </td>
        </tr>
        <tr>
        <td class="box-content">
                連続してのアクセスは記録されません。<br />
                スタイルを変更することが可能です。<br />
                左のメニューは動作しません。<br />
                タイムスタンプはUnixのほうが利用しやすいでしょう
        </td>
        </tr>
        </table>

</td>
</tr>
</table>
<form>
スタイル: <select name="style">
<?php foreach ($files as $file) { ?>
<option value="<?php echo($file); ?>"
<?php echo($file == $style ? "selected" : ""); ?>
><?php echo($file); ?></option>
<?php } ?>
</select>
<input type="submit" value="選択" />
</form>

<?php

require_once ('db_login.php');

require_once ('DB.php');

$connection = DB::connect("mysql://$db_username:$db_password@$db_host/$db_database");

if (DB::isError($connection)) {

    die ("Could not connect to the database :  <br>" . DB::errorMessage($connection));

}

$time_st = time( );
$a_date = date("Y-m-d", $time_st);
$a_time = date("H:i:s", $time_st);
$a_ip = $_SERVER["REMOTE_ADDR"];
$a_agent = $_SERVER["HTTP_USER_AGENT"];
$time_st_p = $a_date . " " . $a_time ;
$m_date = date("Y-m%", $time_st);
$m_month = date("F", $time_st);

$n_date = date("Y年m月j日", $time_st);
$n_time = date("H時i分s秒", $time_st);
$day_night = date("H", $time_st);
if(5 <= $day_night AND $day_night <12 ){ $mes = 'おはようございます！';$mes_e = 'Good morning !';}
elseif(12 <= $day_night AND $day_night <18 ){ $mes = 'こんにちは！';$mes_e = 'Good day !';}
elseif(18 <= $day_night AND $day_night <24 ){ $mes = 'こんばんは！';$mes_e = 'Good evening !';}
else{ $mes = '夜更かしですね';$mes_e = 'Are you awake ?';}


$query = "SELECT * FROM a_log order by id desc";

$result = $connection->query($query);

if (DB::isError($result))

{

    die ("Could not query the database : <br>".$query. " ".DB::errorMessage($result));

}
    $result_row = $result->fetchRow(DB_FETCHMODE_ASSOC);

    $l_ip = $result_row["a_ip"];

    $l_agent = $result_row["a_agent"];

    $l_date = $result_row["a_date"];

if($l_ip == $a_ip AND $l_agent == $a_agent AND $l_date == $a_date )
{
    echo '<strong>リロードでアクセスログは記録されません!</strong><br />';
}
else
{



$query = "INSERT INTO a_log VALUES(NULL, '$time_st_p', '$a_ip', '$a_agent', '$a_date', '$a_time')";

$result = $connection->query($query);

if (DB::isError($result))

{

    die ("Could not query the database : <br>".$query. " ".DB::errorMessage($result));

}
}

?>

<table>

<?php

echo '<tr><td class="box" valign="top"><div class="box-title">' . $mes_e . '</div></td>';
echo '<td class="box" valign="top"><div class="box-title">' . $mes . '</div></td></tr>';
echo '<tr><td class="menu2" valign="top">' ;
echo ' <div class="menu2-active">' ;
echo 'Thanks for your access!</div>';
echo ' <div class="menu2-inactive">' ;
echo 'Today is ' . $a_date . '</div>';
echo ' <div class="menu2-inactive">' ;
echo 'Your access time is ' . $a_time . '</div>';
echo ' <div class="menu2-inactive">' ;
echo 'Your ip address is ' . $a_ip .  '</div>';
echo '</td>';
echo '<td class="menu2" valign="top">' ;
echo ' <div class="menu2-active">' ;
echo 'アクセスありがとうございます</div>';
echo ' <div class="menu2-inactive">' ;
echo '今日は' . $n_date . 'です</div>';
echo ' <div class="menu2-inactive">' ;
echo 'あなたがアクセスした時刻は' . $n_time . 'です</div>';
echo ' <div class="menu2-inactive">' ;
echo 'あなたのipアドレスは' . $a_ip . 'です</div>';
echo '</td></tr>';
echo '<tr><td class="menu2" colspan="2" valign="top">' ;
echo ' <div class="menu2-inactive">' ;
echo 'Your agent is ' . $a_agent . '</div>';
echo '</td></tr>';
echo '<tr><td class="menu2" colspan="2" valign="top">' ;
echo ' <div class="menu2-inactive">' ;
echo '（上記はあなたのブラウザです）</div>';
echo '</td></tr>';
echo '</table>';





$query = "SELECT count(id) FROM a_log WHERE a_date = '$a_date' ";

$result = $connection->query($query);

if (DB::isError($result))

{

    die ("Could not query the database : <br>".$query. " ".DB::errorMessage($result));

}

while ($result_row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {

    echo 'Access number (today) <strong>' . $result_row["count(id)"] . '</strong>　（今日のアクセス数）<br />';

}

$query = "SELECT count(id) FROM a_log WHERE a_date LIKE '$m_date' ";

$result = $connection->query($query);

if (DB::isError($result))

{

    die ("Could not query the database : <br>".$query. " ".DB::errorMessage($result));

}

while ($result_row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {

    echo 'Access number (' . $m_month . ') <strong>' . $result_row["count(id)"] . '</strong>　（今月のアクセス数）<br />';

}

$query = "SELECT count(a_date) FROM a_log";

$result = $connection->query($query);

if (DB::isError($result))

{

    die ("Could not query the database : <br>".$query. " ".DB::errorMessage($result));

}

while ($result_row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {

    echo 'Access number (total) <strong>' . $result_row["count(a_date)"] . '</strong>　（これまでのアクセス数）<br />';

}

$query = "SELECT * FROM a_log order by id desc";

$result = $connection->query($query);

if (DB::isError($result))

{

    die ("Could not query the database : <br>".$query. " ".DB::errorMessage($result));

}
echo '<br/><strong>Access log </strong>(last 10 access) 最近のアクセスログ10件<br />';

echo '<table class="box2">';

echo '<tr class="box2-title"><th>id</th><th>timestamp</th><th>ip</th><th>agent</th>';

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

echo "</table>";

$connection->disconnect( );

?>

</body>

</html>