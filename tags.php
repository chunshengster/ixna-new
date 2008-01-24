<?php
//xna artilces
DEFINE("_ROOT", ".");
include _ROOT . "/mainfile.php";
include _ROOT . "/inc/functions.php";
$tags = is_string($_GET[tags]) ? $_GET[tags]:"";
if (!empty($tags))
{
    $arg .= " where tags='$tags ' ";
}
else
{
    $arg .= "";
}

$sql = "select * from {$tablepre}xna_tags $arg order by id desc ";

$rt = $PlusDB->execute($sql);
while ($row = $rt->fetchRow())
{
    $row[$row[tags]][id] = $row[tags];
    $row[sum] = $xnum[$row[id]];
    $ret[] = $row;
}
define("_TPLPath_", _ROOT . "/template/$skin/");
define("_TPLCachePath_", _ROOT . '/cache/');
define("_TPLCacheLimit_", 1800);
include template("tags.html");


$action = $_REQUEST["action"];
if ($_POST['refer'] == 'refer')
{
    if (empty($_POST["tfield"]))
    {
        echo "" . _LANG_0103 . "";
        exit;
    }
    $sql = "select tags from $xnatags where tags='" . $_POST["tfield"] . "'";
    $rt = $PlusDB->execute($sql);
    while ($row = $rt->fetchRow())
    {
        if ($row[tags] = $_POST["tfield"] || empty($_POST["tfield"]))
        {
            echo "" . _LANG_0101 . "";
            exit;
        }
    }
    $record["tags"] = $_POST["tfield"];
    $ok = $PlusDB->autoexecute($xnadb, $record, "INSERT");
    if ($ok)
    {
        echo "" . _LANG_0102 . "";
        exit;
    }
    else
    {
        echo $PlusDB->errorMsg();
        echo "" . _LANG_1111 . "";
        exit;
    }
}
?>