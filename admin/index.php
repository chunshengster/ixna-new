<?
//header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
//header( "Cache-Control: no-cache, must-revalidate" );
//header( "Pragma: no-cache" );
//
DEFINE("_ROOT", "..");
include _ROOT . "/mainfile.php";
//include _ROOT . "/inc/user.php";
include _ROOT . "/lang/$language/global.php";
$action = $_REQUEST["action"];
if ($_GET['action'] == "logout"){
    setcookie("password", "");
    setcookie("user@name", "");
    header("Location: ./index.php\n");
    exit;
}
$username = "";
$password = "";
if (isset($_COOKIE['password'])){
    $password = $_COOKIE['password'];
}

if (isset($_COOKIE['user@name'])){
    $username = $_COOKIE['user@name'];
}

$cookieLogin = false;

if (empty($username) || empty($password)){
    $username = $_POST[UserName];
    $password = md5($_POST[PassWord]);
}else{
    $cookieLogin = true;
}

if ($_POST['login'] == 'login'){
	session_start();
    if($_SESSION["verifyCode"] != $_POST['authnum']) {
        echo "" . _LANG_1112 . "";
        return false;
    }

    if (empty($username) || empty($password)) {
        echo "" . _LANG_0202 . "";
        loginpage();
    }
}else{
    if (empty($username) || empty($password)) {
        loginpage();
    }
}
global $PlusDB;
$sql = "select username,password from {$tablepre}xna_users where username='" . $username . "' and Password='" . $password . "'";
$rt = $PlusDB->execute($sql);
while ($row = $rt->fetchRow()){
    $user = $row[username];
    $pass = $row[password];
}

if ($username == $user && $password == $pass){
    if (!$cookieLogin){
        session_start();
        //$_SESSION["user@name"]=md5("$user");
        setcookie("password", $pass, time() + (1 * 24 * 3600));
        setcookie("user@name", $user, time() + (1 * 24 * 3600));
			$admin_ip = $_SERVER["REMOTE_ADDR"];
			$admin_time = strtotime(date("Y-m-d H:i:s"));
		$sql = "
			UPDATE {$tablepre}xna_users SET
				lastip='$admin_ip' ,
				lasttime='$admin_time'
			WHERE uid=1";
		$rt = $PlusDB->execute($sql);
        header("Location: ./index.php\n");
        exit;
    }
}else{
    echo $PlusDB->errorMsg();
    echo "" . _LANG_0204 . "";
    exit;
}
?>
<?php
function loginpage(){
    global $hidden, $alexa;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ixna Login</title>
</head>
<body>
<form id="add" name="add" action="index.php" method="post">
  <table border="0" cellpadding="0" cellspacing="0" height="110" width="100%">
    <tbody>
      <tr>
        <td width="33%">&nbsp;</td>
        <td height="5" colspan="2"></td>
      </tr>
      <tr>
        <td rowspan="3" align="center" valign="middle"><div align="right"><img src="../images/admin/logo.png" height=60 > &nbsp;</div></td>
        <td width="7%" height="20" valign="middle">UserName:</td>
        <td width="60%"><input name="UserName" maxlength="16" id="username" class="txtinput" style="width: 190px;" type="text" /></td>
      </tr>
      <tr>
        <td height="10" valign="middle">PassWord:</td>
        <td height="10" valign="middle"><input name="PassWord" maxlength="16" id="password" class="txtinput" style="width: 190px;" type="password" /></td>
      </tr>
      <tr>
        <td height="10" valign="middle">VerifyCode:</td>
        <td height="10"><input name="authnum" class="txtinput" id="authnum" style="width: 80px;" maxlength="16" />
          <img src='../inc/authnum.php' onClick="this.src='../inc/authnum.php?r='+Math.random()" /></td>
      </tr>
      <tr>
        <td align="center" valign="middle">&nbsp;</td>
        <td height="45" colspan="2">
        <div id="info"></div>
          	<input type="hidden" name="login" value="login">
            <input type="submit" name="submit" value="Login">
            </td>
      </tr>
    </tbody>
  </table>
</form>
</body>
</html>
<?php
    exit;
}
?>
<?
include _ROOT . "/inc/manage.php";
switch ($action){
    case "doDelete":
        doDelete();
        break;
    case "doDeleteTypes":
        doDeleteTypes();
        break;
    case "doClear":
        doClear();
        break;
    case "doUpdate":
        doUpdate();
        break;
    case "doUpdateTypes":
        doUpdateTypes();
        break;
    case "doEdit":
        doEdit();
        break;
    case "doEditTypes":
        doEditTypes();
        break;
    case "doFetch":
        doFetch();
        break;
    case "doSave":
        doSave();
        break;
    case "doSaveTypes":
        doSaveTypes();
        break;
    case "doAdd":
        doAdd();
        break;
    case "doAddTypes":
        doAddTypes();
        break;
    case "doListTypes":
        doListTypes();
        break;
    case "doOpml":
        doOpml();
        break;
    case "doOpml":
        doOpml();
        break;
    case "doListAudit":
        doListAudit();
        break;
    case "doUpdateAudit":
        doUpdateAudit();
        break;
    case "doUser":
        doUser();
        break;
    case "doAddUser":
        doAddUser();
        break;
    case "doEditUser":
        doEditUser();
        break;       
    case "doUpdateUser":
        doUpdateUser();
        break;
    case "doSiteMAP":
        doSiteMAP();
        break;
    case "doListFeed":
        doListFeed();
        break;
    case "doEditFeed":
        doEditFeed();
        break;
    case "doDeleteFeed":
        doDeleteFeed();
        break;
    case "doUpdateFeed":
        doUpdateFeed();
        break;
    case "doUpFeed":
        doUpFeed();
        break;
    case "doSaveFeed":
        doSaveFeed();
        break;
    case "doListFeedAudit":
        doListFeedAudit();
        break;
    case "doListLinks":
        doListLinks();
        break;
    case "doAddLinks":
        doAddLinks();
        break;
    case "doSaveLinks":
        doSaveLinks();
        break;
    case "doEditLinks":
        doEditLinks();
        break;
    case "doUpdateLinks":
        doUpdateLinks();
        break;
    case "doDeleteLinks":
        doDeleteLinks();
        break;
    case "doCache":
        doCache();
    case "doListComm":
        doListComm();
        break;
    case "doEditComm":
        doEditComm();
        break;
    case "doUpdateComm":
        doUpdateComm();
        break;
    case "doDelComm":
        doDelComm();
        break;
    case "doIcon":
        doIcon();
        break;
    case "doConfig":
        doConfig();
        break;
    case "doUpdateConfig":
        doUpdateConfig();
        break;
    case "doOpml":
        doOpml();
        break;
    default:
        doList();
}
?>