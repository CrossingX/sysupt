<?php
require "include/bittorrent.php";
dbconn();

global $CURUSER, $SITENAME, $SITEEMAIL, $Cache;
if ($CURUSER['class'] < UC_MODERATOR) {
    stderr("没有权限", "你无权查看申请列表");
}

$id = 0 + $_GET['id'];
$action = $_GET['action'];
$recheck = 0 + $_GET['recheck'];

if ($id && $action) {
    $res = sql_query("SELECT * FROM needverify WHERE id = " . sqlesc($id));
    $row = mysql_fetch_array($res);

    if ($row['result'] != 0 && $recheck != 1) {
        $url = $_SERVER['REQUEST_URI'] . "&recheck=1";
        stderr("确认重新审核？", "该用户已被审核过，若重新审核请<a href='{$url}' class='faqlink'>点击这里</a>", false);
    }
    if ($recheck) {
        sql_query("UPDATE needverify SET result = 0 WHERE id = " . sqlesc($id));
        header("Location: verify_signup.php");
        die();
    }

    $email = mysql_fetch_row(@sql_query("SELECT email FROM users WHERE id = " . sqlesc($row['uid'])))[0];
    if ($action == 'accept') {
        sql_query("UPDATE users SET status = 'confirmed', enabled = 'yes', downloadpos = 'yes' WHERE id = " . sqlesc($row['uid']));
        sql_query("UPDATE needverify SET result = 1, verified_by = " . sqlesc($CURUSER['id']) . " WHERE id = " . sqlesc($id));
        $title = $SITENAME . "账户申请已被确认";
        $message = "你的账户申请已被确认，欢迎你加入" . $SITENAME . "。<br>----------------<br>" . $SITENAME . "管理组";
    } else {
        sql_query("UPDATE users SET status = 'confirmed', enabled = 'no', downloadpos = 'no' WHERE id = " . sqlesc($row['uid']));
        sql_query("UPDATE needverify SET result = -1, verified_by = " . sqlesc($CURUSER['id']) . " WHERE id = " . sqlesc($id));
        $title = $SITENAME . "账户申请已被驳回";
        $message = "你的账户申请已被驳回，有意申诉请加入<a href='//shang.qq.com/wpa/qunwpa?idkey=c584748ff16ae67f8f381f0d4e5f87132551ad01704b90075d90da4f4e659ee4'>北洋园PT临时群：637597613</a>" . "。<br>----------------<br>" . $SITENAME . "管理组";
    }
    $Cache->delete_value('staff_needverify_count');
    sent_mail($email, $SITENAME, $SITEEMAIL, change_email_encode(get_langfolder_cookie(), $title), change_email_encode(get_langfolder_cookie(), $message), "verify_user", false, false, '', get_email_encode(get_langfolder_cookie()));
    header("Location: verify_signup.php");
} else {
    if (mysql_fetch_row(sql_query("SELECT COUNT(*) FROM needverify"))[0] == 0) {
        stderr("无结果", "审核列表中无数据");
    }
    stdhead();
    begin_table();
    ?>
    <tr>
        <td class='colhead'>用户名</td>
        <td class='colhead'>IP/位置</td>
        <td class='colhead'>邀请者/获邀途径</td>
        <td class='colhead'>邀请/注册邮箱</td>
        <td class='colhead'>信息</td>
        <td class='colhead'>状态</td>
    </tr>
    <?php

    $res = sql_query("SELECT * FROM needverify ORDER BY FIELD(result, 0, 1, -1), uid ASC");
    while ($row = mysql_fetch_array($res)) {
        $ip = mysql_fetch_row(@sql_query("SELECT ip FROM iplog WHERE userid = " . sqlesc($row['uid']) . " ORDER BY id ASC LIMIT 1"))[0];
        $user = mysql_fetch_array(@sql_query("SELECT modcomment, invited_by, email FROM users WHERE id = " . sqlesc($row['uid']) . " LIMIT 1"));

        preg_match('/受邀原因：(.*?)；邀请邮箱：(.*?)。/i', $user['modcomment'], $matches);
        $reason = $matches[1];
        $invite_email = $matches[2];
        $inviter = $user['invited_by'] == 0 ? "<i>系统</i>" : get_username($user['invited_by']);
        $email = $user['email'] == $invite_email ? $user['email'] : $invite_email . "/" . $user['email'];


        switch ($row['result']) {
            case 0:
                $status = "<a href='verify_signup.php?id={$row['id']}&action=accept'><span style='color: green'>通过</span></a>&nbsp;&nbsp;&nbsp;<a href='verify_signup.php?id={$row['id']}&action=reject'><span style='color: red'>驳回</span></a>";
                break;
            case -1:
                $status = "<a href='verify_signup.php?id={$row['id']}&action=recheck'><span style='color: red'>已被驳回</span></a> - " . get_username($row['verified_by']);
                break;
            case 1:
                $status = "<a href='verify_signup.php?id={$row['id']}&action=recheck'><span style='color: green'>已被通过</span></a> - " . get_username($row['verified_by']);
                break;
        }

        ?>
        <tr>
            <td class="rowfollow"><?php echo get_username($row['uid']) ?></td>
            <td class="rowfollow"><?php echo $ip . " 「" . ip_to_location($ip) . "」" ?></td>
            <td class="rowfollow"><?php echo $inviter . ' -> ' . $reason ?></td>
            <td class="rowfollow"><?php echo $email ?></td>
            <td class="rowfollow" style="width: 40%"><?php echo $row['message'] ?></td>
            <td class="rowfollow"><?php echo $status ?></td>
        </tr>
        <?php
    }

    end_table();
    stdfoot();
}