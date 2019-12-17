<?php
require_once("include/bittorrent.php");
dbconn();
cur_user_check();
require_once(get_langfile_path("", true));
require_once(get_langfile_path("", false, get_langfolder_cookie()));
global $iv, $showschool, $verification, $defaultclass_class, $BASEURL, $SITEEMAIL, $smtptype, $SITENAME, $iniupload_main, $defcss;
function bark($msg)
{
    global $lang_takesignup;
    stdhead();
    stdmsg($lang_takesignup['std_signup_failed'], $msg);
    stdfoot();
    exit;
}

$type = $_POST['type'];
if ($type == 'invite') {
    //registration_check();
    failedloginscheck("Invite Signup");
    if ($iv == "yes")
        check_code($_POST['imagehash'], $_POST['imagestring'], 'signup.php?type=invite&invitenumber=' . htmlspecialchars($_POST['hash']));
} else {
    registration_check("normal");
    failedloginscheck("Signup");
    if ($iv == "yes")
        check_code($_POST['imagehash'], $_POST['imagestring']);
}

if ($type == 'invite') {
    $inviter = $_POST["inviter"];
    int_check($inviter);
    $code = $_POST["hash"];
    $ip = getip();


    $res = sql_query("SELECT username FROM users WHERE id = $inviter") or sqlerr(__FILE__, __LINE__);
    $arr = mysql_fetch_assoc($res);
    $invusername = $arr['username'];
}

if (!mkglobal("wantusername:wantpassword:passagain:email"))
    die();

$email = htmlspecialchars(trim($email));
$email = safe_email($email);
if (!check_email($email))
    bark($lang_takesignup['std_invalid_email_address']);

if (EmailBanned($email))
    bark($lang_takesignup['std_email_address_banned']);

if (!EmailAllowed($email))
    bark($lang_takesignup['std_wrong_email_address_domains'] . allowedemails());

$country = $_POST["country"];
int_check($country);

if ($showschool == 'yes') {
    $school = $_POST["school"];
    int_check($school);
}

$gender = htmlspecialchars(trim($_POST["gender"]));
$allowed_genders = array("Male", "Female", "male", "female");
if (!in_array($gender, $allowed_genders, true))
    bark($lang_takesignup['std_invalid_gender']);

if (empty($wantusername) || empty($wantpassword) || empty($email) || empty($country) || empty($gender))
    bark($lang_takesignup['std_blank_field']);


if (mb_strlen($wantusername, 'UTF-8') > 12)
    bark($lang_takesignup['std_username_too_long']);

global $passagain;
if ($wantpassword != $passagain)
    bark($lang_takesignup['std_passwords_unmatched']);

if (strlen($wantpassword) < 6)
    bark($lang_takesignup['std_password_too_short']);

if (strlen($wantpassword) > 40)
    bark($lang_takesignup['std_password_too_long']);

if ($wantpassword == $wantusername)
    bark($lang_takesignup['std_password_equals_username']);

if (!validemail($email))
    bark($lang_takesignup['std_wrong_email_address_format']);

if (!validusername($wantusername))
    bark($lang_takesignup['std_invalid_username']);

// make sure user agrees to everything...
if ($_POST["rulesverify"] != "yes" || $_POST["faqverify"] != "yes" || $_POST["ageverify"] != "yes")
    stderr($lang_takesignup['std_signup_failed'], $lang_takesignup['std_unqualified']);

// check if email addy is already in use
$a = (@mysql_fetch_row(@sql_query("select count(*) from users where email='" . mysql_real_escape_string($email) . "'"))) or sqlerr(__FILE__, __LINE__);
if ($a[0] != 0)
    bark($lang_takesignup['std_email_address'] . $email . $lang_takesignup['std_in_use']);

if ($type == 'invite') {
    $a = (@mysql_fetch_row(@sql_query("SELECT count(*) FROM invites WHERE hash = '" . mysql_real_escape_string($code) . "'"))) or sqlerr(__FILE__, __LINE__);
    if ($a[0] == 0) bark($lang_takesignup['std_code_error']);
}

$secret = mksecret();
$wantpasshash = md5($secret . $wantpassword . $secret);
$editsecret = ($verification == 'admin' ? '' : $secret);
$invite_count = (int)$invite_count;

$wantusername = sqlesc($wantusername);
$wantpasshash = sqlesc($wantpasshash);
$secret = sqlesc($secret);
$editsecret = sqlesc($editsecret);
$send_email = $email;
$email = sqlesc($email);
$country = sqlesc($country);
$gender = sqlesc($gender);
$sitelangid = sqlesc(get_langid_from_langcookie());

$res_check_user = sql_query("SELECT * FROM users WHERE username = " . $wantusername);

if (mysql_num_rows($res_check_user) == 1)
    bark($lang_takesignup['std_username_exists']);

$ret = sql_query("INSERT INTO users (username, passhash, secret, editsecret, email, country, gender, status, class, invites, " . ($type == 'invite' ? "invited_by," : "") . " added, last_access, ip, lang, stylesheet" . ($showschool == 'yes' ? ", school" : "") . ", uploaded) VALUES (" . $wantusername . "," . $wantpasshash . "," . $secret . "," . $editsecret . "," . $email . "," . $country . "," . $gender . ", 'pending', " . $defaultclass_class . "," . $invite_count . ", " . ($type == 'invite' ? "'$inviter'," : "") . " '" . date("Y-m-d H:i:s") . "' , " . " '" . date("Y-m-d H:i:s") . "' , " . " '" . getip() . "' , " . $sitelangid . "," . $defcss . ($showschool == 'yes' ? "," . $school : "") . "," . ($iniupload_main > 0 ? $iniupload_main : 0) . ")") or sqlerr(__FILE__, __LINE__);
$id = mysql_insert_id();
if ($type == 'invite') sql_query("DELETE FROM invites WHERE hash = '" . mysql_real_escape_string($code) . "'");
if ($inviter == 9 && $_SERVER['HTTP_USERNAME'] && $_SERVER['HTTP_INSTITUTION']) sql_query("INSERT INTO carsimapping (sysuptid, username, institution) VALUES($id,'" . $_SERVER['HTTP_USERNAME'] . "','" . $_SERVER['HTTP_INSTITUTION'] . "')") or sqlerr(__FILE__, __LINE__);
sql_query("INSERT INTO iplog (ip, userid, access) VALUES('" . getip() . "' , $id,'" . date("Y-m-d H:i:s") . "')") or sqlerr(__FILE__, __LINE__);
//delete confirmed invitee's hash code from table invites
$dt = sqlesc(date("Y-m-d H:i:s"));
$subject = sqlesc($lang_takesignup['msg_subject'] . $SITENAME . "!");
$msg = sqlesc($lang_takesignup['msg_congratulations'] . htmlspecialchars($wantusername) . $lang_takesignup['msg_you_are_a_member']);
sql_query("INSERT INTO messages (sender, receiver, subject, added, msg) VALUES(0, $id, $subject, $dt, $msg)") or sqlerr(__FILE__, __LINE__);

//write_log("User account $id ($wantusername) was created");
$res = sql_query("SELECT passhash, secret, editsecret, status FROM users WHERE id = " . sqlesc($id)) or sqlerr(__FILE__, __LINE__);
$row = mysql_fetch_assoc($res);
$psecret = md5($row['secret']);
$ip = getip();
$usern = htmlspecialchars($wantusername);
$title = $SITENAME . $lang_takesignup['mail_title'];
$body = <<<EOD
{$lang_takesignup['mail_one']}$usern{$lang_takesignup['mail_two']}($email){$lang_takesignup['mail_three']}$ip{$lang_takesignup['mail_four']}
<b><a href="https://$BASEURL/confirm.php?id=$id&secret=$psecret" target="_blank">
{$lang_takesignup['mail_this_link']} </a></b><br />
https://$BASEURL/confirm.php?id=$id&secret=$psecret
{$lang_takesignup['mail_four_1']}
<b><a href="https://$BASEURL/confirm_resend.php" target="_blank">{$lang_takesignup['mail_here']}</a></b><br />
https://$BASEURL/confirm_resend.php
<br />
{$lang_takesignup['mail_five']}
EOD;

if ($verification == 'admin') {
    if ($type == 'invite')
        header("Location: ok.php?type=inviter");
    else
        header("Location: ok.php?type=adminactivate");
} elseif ($verification == 'automatic' || $smtptype == 'none') {
    header("Location: confirm.php?id=$id&secret=$psecret");
} else {
    sent_mail($send_email, $SITENAME, $SITEEMAIL, change_email_encode(get_langfolder_cookie(), $title), change_email_encode(get_langfolder_cookie(), $body), "signup", false, false, '', get_email_encode(get_langfolder_cookie()));
    header("Location: ok.php?type=signup&email=" . rawurlencode($send_email));
}
if ($type == 'invite') {
    $dt = sqlesc(date("Y-m-d H:i:s"));
    $subject = sqlesc($lang_takesignup_target[get_user_lang($inviter)]['msg_invited_user_has_registered']);
    $msg = sqlesc($lang_takesignup_target[get_user_lang($inviter)]['msg_user_you_invited'] . $usern . $lang_takesignup_target[get_user_lang($inviter)]['msg_has_registered']);
    //sql_query("UPDATE users SET uploaded = uploaded + 10737418240 WHERE id = $inviter"); //add 10GB to invitor's uploading credit
    if ($inviter != "9999" && $inviter != "9" && $inviter) {
        sql_query("INSERT INTO messages (sender, receiver, subject, added, msg) VALUES(0, $inviter, $subject, $dt, $msg)") or sqlerr(__FILE__, __LINE__);
    }
    $Cache->delete_value('user_' . $inviter . '_unread_message_count');
    $Cache->delete_value('user_' . $inviter . '_inbox_count');
}