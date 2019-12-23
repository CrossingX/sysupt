<?php
require_once("include/bittorrent.php");
dbconn();
global $securelogin, $CURLANGDIR, $maxloginattempts, $securetracker;
$langid = 0 + $_GET['sitelanguage'];
if ($langid) {
    $lang_folder = validlang($langid);
    if (get_langfolder_cookie() != $lang_folder) {
        set_langfolder_cookie($lang_folder);
        header("Location: " . $_SERVER['PHP_SELF']);
    }
}
require_once(get_langfile_path("", false, $CURLANGDIR));

failedloginscheck();
cur_user_check();
stdhead($lang_login['head_login']);

$s = "<select name=\"sitelanguage\" onchange='submit()'>\n";

$langs = langlist("site_lang");

foreach ($langs as $row) {
    if ($row["site_lang_folder"] == get_langfolder_cookie()) $se = "selected=\"selected\""; else $se = "";
    $s .= "<option value=\"" . $row["id"] . "\" " . $se . ">" . htmlspecialchars($row["lang_name"]) . "</option>\n";
}
$s .= "\n</select>";

unset($returnto);
if (!empty($_GET["returnto"])) {
    $returnto = $_GET["returnto"];
    if (!$_GET["nowarn"]) {
        print("<h1>" . $lang_login['h1_not_logged_in'] . "</h1>\n");
        print("<p><b>" . $lang_login['p_error'] . "</b> " . $lang_login['p_after_logged_in'] . "</p>\n");
    }
}
?>

    <form method="post" action="takelogin.php">
        <p><?php echo $lang_login['p_you_have'] ?>
            <b><?php echo remaining(); ?></b> <?php echo $lang_login['p_remaining_tries'] ?>
            ！[<b><?php echo $maxloginattempts; ?></b>] <?php echo $lang_login['p_fail_ban'] ?></p>
        <table border="0" cellpadding="5">
            <tr>
                <td class="rowhead"><?php echo $lang_login['rowhead_username'] ?></td>
                <td class="rowfollow" align="left"><input type="text" name="username"
                                                          style="width: 180px; border: 1px solid gray"/></td>
            </tr>
            <tr>
                <td class="rowhead"><?php echo $lang_login['rowhead_password'] ?></td>
                <td class="rowfollow" align="left"><input type="password" name="password"
                                                          style="width: 180px; border: 1px solid gray"/></td>
            </tr>

            <?php
            //show_image_code ();
            if ($securelogin == "yes")
                $sec = "checked=\"checked\" disabled=\"disabled\"";
            elseif ($securelogin == "no")
                $sec = "disabled=\"disabled\"";
            elseif ($securelogin == "op")
                $sec = "";

            if ($securetracker == "yes")
                $sectra = "checked=\"checked\" disabled=\"disabled\"";
            elseif ($securetracker == "no")
                $sectra = "disabled=\"disabled\"";
            elseif ($securetracker == "op")
                $sectra = "";
            ?>

            <tr>
                <td class="toolbox" colspan="2" align="left"><?php echo $lang_login['text_advanced_options'] ?></td>
            </tr>
            <tr>
                <td class="rowhead"><?php echo $lang_login['text_auto_logout'] ?></td>
                <td class="rowfollow" align="left">
                    <select name="logout">
                        <option value="off"><?php echo $lang_login['off'] ?></option>
                        <option value="1day"><?php echo $lang_login['1day'] ?></option>
                        <option value="7days" selected><?php echo $lang_login['7days'] ?></option>
                        <option value="14days"><?php echo $lang_login['14days'] ?></option>
                        <option value="30days"><?php echo $lang_login['30days'] ?></option>
                        <option value="365days"><?php echo $lang_login['365days'] ?></option>
                        <option value="forever"><?php echo $lang_login['forever'] ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="rowhead"><?php echo $lang_login['text_restrict_ip'] ?></td>
                <td class="rowfollow" align="left">
                    <input class="checkbox" type="checkbox" name="securelogin"
                           value="yes"/><?php echo $lang_login['checkbox_restrict_ip'] ?>
                </td>
            </tr>
            <tr>
                <td class="toolbox" colspan="2" align="left"><?php echo $lang_login['p_no_account_signup'] ?></td>
            </tr>
            <tr>
                <td class="toolbox" colspan="2" align="right"><input type="submit"
                                                                     value="<?php echo $lang_login['button_login'] ?>"
                                                                     class="btn"/>
                    <input type="reset" value="<?php echo $lang_login['button_reset'] ?>" class="btn"/>
                </td>
            </tr>
        </table>
        <?php

        if (isset($returnto))
            print("<input type=\"hidden\" name=\"returnto\" value=\"" . htmlspecialchars($returnto) . "\" />\n");

        ?>
    </form>
    <p><a href="recover.php"><b>找回密码</b></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="confirm_resend.php"><b>重新发送验证邮件</b></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a
                href="punishment.php"><b>查看被封禁原因</b></a></p>
<?php
$ip = getip();
$nip = ip2long($ip);
if ($nip) {

    if (!check_tjuip($nip)) {
        $nontju = 1;
    }
}
$showhelpbox_main = 'no'; // I don't see how this thing came here. I'll just set it to NO.
if ($showhelpbox_main != 'no' && $nontju != 1) {
    ?>
    <table width="700" class="main" border="0" cellspacing="0" cellpadding="0">
    <tr>
    <td class="embedded">
    <h2><?php echo $lang_login['text_helpbox'] ?><font class="small"> - <?php echo $lang_login['text_helpbox_note'] ?>
            <font color="red"><?php echo $lang_login['text_helpbox_QQ'] ?></font><font id="waittime" color="red"></font>
    </h2>
    <?php
    print("<table width='100%' border='1' cellspacing='0' cellpadding='1'><tr><td class=\"text\">\n");
    print("<iframe src='shoutbox.php?type=helpbox' width='650' height='180' frameborder='0' name='sbox' marginwidth='0' marginheight='0'></iframe><br /><br />\n");
    print("<form action='shoutbox.php' id='helpbox' method='post' target='sbox' name='shbox'>\n");
    print($lang_login['text_message'] . "<input type='text' id=\"hbtext\" name='shbox_text' autocomplete='off' style='width: 500px; border: 1px solid gray' ><input type='submit' id='hbsubmit' class='btn' name='shout' value=\"" . $lang_login['sumbit_shout'] . "\" /><input type='reset' class='btn' value=" . $lang_login['submit_clear'] . " /> <input type='hidden' name='sent' value='yes'><input type='hidden' name='type' value='helpbox' />\n");
    print("<div id=sbword style=\"display: none\" >" . $lang_login['sumbit_shout'] . "</div>");
    print(smile_row("shbox", "shbox_text"));
    print("</td></tr></table></form></td></tr></table>");
}
stdfoot();
