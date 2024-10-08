<?php
//index.php : embeds app

include_once('settings.php');

//access existing room or create a new one as performer
//all IDs generated randomly for demonstrative purposes; on integrations should be from database

$roomID = intval($_GET['r'] ?? 0);

if (!$roomID)
{
$roomID = rand(9000, 9999);
$isPerformer = 1;
$userID = 10000 + $roomID;
}
else
{
$userID = rand(9000, 9999);
$isPerformer = 0;
}

$sessionID = $userID;
$sessionKey = $userID;

//setcookie('userID', $userID);


//embed the app: all integrations should contain this part
$dataCode = "window.VideoWhisper = {userID: $userID, sessionID: $sessionID, sessionKey: '$sessionKey', modeVersion: '', roomID: $roomID, performer: $isPerformer, serverURL: '" . VW_H5V_CALL . "'}";

$bodyCode = <<<HTMLCODE
<!--VideoWhisper.com - HTML5 Videochat web app - uid:$userID p:$isPerformer s:$sessionID-->
<noscript>You need to enable JavaScript to run this app.</noscript>
<div style="display:block;min-height:50px;background-color:#eee;position:relative;z-index:102!important;"><div style="display:block;width:100%; height:100%; position:absolute;z-index:102!important;" id="videowhisperVideochat"></div></div>
<script>$dataCode;</script>
HTMLCODE;

//app requires semantic ui
$headCode = '<link href="//cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.css" rel="stylesheet" type="text/css">';

//app css & js
$CSSfiles = scandir(dirname(  __FILE__ ) . '/static/css/');
foreach($CSSfiles as $filename)
	if (strpos($filename,'.css')&&!strpos($filename,'.css.map'))
		$headCode .= '<link href="' . VW_H5V_URL . 'static/css/' . $filename . '" rel="stylesheet" type="text/css">';

$JSfiles = scandir(dirname(  __FILE__ ) . '/static/js/');
foreach ($JSfiles as $filename)
	if ( strpos($filename,'.js') && !strpos($filename,'.js.map'))
		$bodyCode .= '<script src="' . VW_H5V_URL . 'static/js/' . $filename. '" type="text/javascript"></script>';


//room link
$roomURL = $_SERVER['REQUEST_SCHEME'] .'://'. $_SERVER['HTTP_HOST'] . dirname(explode('?', $_SERVER['REQUEST_URI'], 2)[0]) . '/?r=' . $roomID;
$bodyCode .= '<div class="ui segment"><h4 class="ui header">Booth URL</h4>Review or record videos at this address:<br>' . $roomURL .'</div>';
?>
<head>
<?php echo $headCode ?>
</head>
<body>
<?php echo $bodyCode ?>
</body>