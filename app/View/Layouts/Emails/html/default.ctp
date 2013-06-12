<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
<title>Email Template</title>
</head>
<body>
	<div id="BodyNewsletter" style="margin: 0px auto; width: 600px;">
	    <table id="NewsletterHeader" border="0" cellspacing="0" cellpadding="0" width="600">
	        <tr>
	            <td style="background: #CCCCCC; font-family: Helvetica, Arial, sans-serif; padding:18px; font-size:14px;">
	                <?php echo $content_for_layout;?>
	            </td>
	        </tr>
	        <tr>
	        	<td style="color:#666666;font-size:12px; padding: 18px;" align="center">
					Delivered by GreyBack Labs, LLP<br />
					You are receiving this email because you registered with <?php echo $currentUrl ?><br />
					Be sure to add us to your address book or safe sender list so our emails get to your inbox.
	        	</td>
	        </tr>
	    </table>
	</div>
</body>
</html>