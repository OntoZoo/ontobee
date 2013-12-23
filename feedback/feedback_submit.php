<!-- 
Copyright © 2013 The Regents of the University of Michigan
 
Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at
 
http://www.apache.org/licenses/LICENSE-2.0
 
Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.
 
For more information, questions, or permission requests, please contact:
Yongqun “Oliver” He - yongqunh@med.umich.edu
Unit for Laboratory Animal Medicine, Center for Computational Medicine & Bioinformatics
University of Michigan, Ann Arbor, MI 48109, USA
He Group:  http://www.hegroup.org
-->
<!--
Author: Yongqun 'Oliver' He, Zuoshuang Xiang
The University Of Michigan
He Group
Date: June 2008 - December 2013
Purpose: Ontobee feedback submission page.
-->

<?php include_once('../inc/Classes.php');
include("../inc/recaptcha-php-1.9/recaptchalib.php");
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html><!-- InstanceBegin template="/Templates/default.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<title>Ontobee</title>
<!-- InstanceEndEditable --><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="shortcut icon" href="/favicon.ico" />
<link href="../css/styleMain.css" rel="stylesheet" type="text/css">
<!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable -->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-4869243-9']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>

<body>
<div id="topbanner"><a href="/index.php" style="font-size:36px; color:#111144; text-decoration:none"><img src="../images/logo.gif" alt="Logo" width="280" height="49" border="0"></a></div>
<div id="topnav"><a href="../index.php" class="topnav">Home</a><a href="../introduction.php" class="topnav">Introduction</a><a href="../ontostat/index.php" class="topnav">Statistics</a><a href="../sparql/index.php" class="topnav">SPARQL</a><a href="../ontobeep/index.php" class="topnav">Ontobeep</a><a href="../tutorial/index.php" class="topnav">Tutorial</a><a href="../faqs.php" class="topnav">FAQs</a><a href="../references.php" class="topnav">References</a><a href="../links.php" class="topnav">Links</a><a href="../contactus.php" class="topnav">Contact</a><a href="../acknowledge.php" class="topnav">Acknowledge</a><a href="../news.php" class="topnav">News</a></div>
<div id="mainbody">
<!-- InstanceBeginEditable name="Main" -->
<?php 
$vali=new Validation($_REQUEST);

$c_email = $vali->getEmail('c_email', 'Email Address', true);
$c_subject = $vali->getInput('c_subject', 'Subject', 2, 200);
$c_category = $vali->getInput('c_category', 'Category', 2, 45);
$c_body = $vali->getInput('c_body', 'Message body', 2, 6000);
$c_submit_ip = $_SERVER['REMOTE_ADDR'];

$resp = recaptcha_check_answer (RECAPTCHA_PRIVATEKEY,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);

if (!$resp->is_valid) {
?>

      <p align="center"> Something wrong with your input. Please check the following fields:<br />
        <font color="red">The reCAPTCHA wasn't entered correctly.</font> <a href="javascript:history.go(-1);">Go back and try again</a>. </p>


<?php }
elseif (strlen($vali->getErrorMsg())>0) {
?>
<?php include('../inc/input_error.php');?>
<?php }
else {
	include('Mail.php');

	$headers['From']    = $c_email;
	$headers['Reply-To'] = $c_email;
	$headers['Date']      = date('r (T)');
	$headers['To']      = $adminEmail;
	$headers['Subject'] = $systemEmailFlag . "Feedback ($c_category): ". $c_subject;
	$headers['Content-Type']      = "text/html; charset=iso-8859-1";
	$body = "<html></p>".htmlspecialchars($c_body)."</p>";
	$body .= "</html>";
	$params['host'] = $mail_relay_host;
	// Create the mail object using the Mail::factory method
	$mail_object =& Mail::factory('smtp', $params);
	$mail_object->send($headers['To'], $headers, $body);
?>
      <p align="center" class="notice">Feedback submitted. Thank you very much for your support!</p>
		  <p align="center"><a href="../index.php">Go back to home page</a>.</p>
<?php }
?>
      <!-- InstanceEndEditable -->
</div>
<div id="footer">
  <div id="footer_hl"></div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><div id="footer_left"><a href="http://www.hegroup.org" target="_blank">He Group</a><br>
University of Michigan Medical School<br>
Ann Arbor, MI 48109</div></td>
		<td width="300"><div id="footer_right"><a href="http://www.umich.edu" target="_blank"><img src="../images/wordmark_m_web.jpg" alt="UM Logo" width="166" height="20" border="0"></a></div></td>
	</tr>
</table>
</div>
</body>
<!-- InstanceEnd --></html>
