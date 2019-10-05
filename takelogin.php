<?php
require_once("include/bittorrent.php");
header("Content-Type: text/html; charset=utf-8");
if (!mkglobal("username:password"))
    die();
dbconn();
require_once(get_langfile_path("", false, get_langfolder_cookie()));
global $securelogin, $securetracker;

failedloginscheck();
cur_user_check();

function bark($text = "")
{
    global $lang_takelogin;
    $text = ($text == "" ? $lang_takelogin['std_login_fail_note'] : $text);
    stderr($lang_takelogin['std_login_fail'], $text, false);
}

/*if ($iv == "yes")
	check_code ($_POST['imagehash'], $_POST['imagestring'],'login.php',true);
*/
if ($username[0] == '0')
    $res = sql_query("SELECT id, passhash, secret, enabled, status FROM users WHERE username = " . sqlesc($username)) or sqlerr();
elseif (is_numeric($username))
    $res = sql_query("SELECT id, passhash, secret, enabled, status FROM users WHERE username = '" . sqlesc($username) . "'") or sqlerr();
else
    $res = sql_query("SELECT id, passhash, secret, enabled, status FROM users WHERE username = " . sqlesc($username));
$row = mysql_fetch_array($res);

if (!$row)
    failedlogins();
if ($row['status'] != 'confirmed')
    failedlogins($lang_takelogin['std_user_account_unconfirmed']);
global $password;
if ($row["passhash"] != md5($row["secret"] . $password . $row["secret"]))
    login_failedlogins();

if ($row["enabled"] == "no")
    bark($lang_takelogin['std_account_disabled'] . $lang_takelogin['std_enable_with_bonus']);

if ($_POST["securelogin"] == "yes") {
    $securelogin_indentity_cookie = true;
    $passh = md5($row["passhash"] . $_SERVER["REMOTE_ADDR"]);
} else {
    $securelogin_indentity_cookie = false;
    $passh = md5($row["passhash"]);
}

if ($securelogin == 'yes' || $_POST["ssl"] == "yes") {
    $pprefix = "https://";
    $ssl = true;
} else {
    $pprefix = "http://";
    $ssl = false;
}
if ($securetracker == 'yes' || $_POST["trackerssl"] == "yes") {
    $trackerssl = true;
} else {
    $trackerssl = false;
}
$time = array('off' => 0, '1day' => 86400, '7days' => 604800, '14days' => 1209600, '30days' => 2592000, '365days' => 31536000, 'forever' => 0x7fffffff);
if (isset($time[$_POST["logout"]])) {
    logincookie($row["id"], $passh, 1, $time[$_POST["logout"]], $securelogin_indentity_cookie, $ssl, $trackerssl);
    //sessioncookie($row["id"], $passh,true);
} else {
    logincookie($row["id"], $passh, 1, 0, $securelogin_indentity_cookie, $ssl, $trackerssl);
}

if (!empty($_POST["returnto"]))
    header("Location: $_POST[returnto]");
else
    header("Location: index.php");