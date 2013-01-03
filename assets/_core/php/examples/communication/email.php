<?php require_once('../qcubed.inc.php'); ?>
<?php require('../includes/header.inc.php'); ?>

<div id="instructions">
    <h1>Emailing via SMTP</h1>

    <p>The <strong>QEmailServer</strong> class can be used to send email messages via any accessible
        SMTP server.  Obviously, many PHP developers will be familiar with the PHP <strong>mail()</strong>
        function, which internally makes a shell call to <strong>sendmail</strong>.  But note that for
        <em>many</em> reasons (including security, maintenance and deployability), <strong>QEmailServer</strong>
        does <em>not</em> use PHP's <strong>mail()</strong> nor does it use <strong>sendmail</strong>.</p>

    <p><strong>QEmailServer</strong> is an abstract class which only has static variables (which
        define the location of the SMTP server and other preferences) and a single static
        <strong>Send</strong> method.  The <strong>Send</strong> method takes in a <strong>QEmailMessage</strong> object.</p>

    <p>The <strong>QEmailMessage</strong> object contains the relavent email addresses (e.g. From,
        To, Cc and Bcc), as well as the subject and body.  Note that the body can be in
        either plain text, HTML or both.  QCubed will automatically handle the multipart
        message encoding for you.</p>

    <p>You can easily add attachments to your message - using <strong>QEmailAttachment</strong> and
        its child classes, for example, <strong>QEmailStringAttachment</strong>. In the example below, 
        we create a new <strong>QEmailStringAttachment</strong> object and then call <strong>Attach()</strong> on
        the <strong>QEmailMessage</strong> object - that's all it takes.</p>

    <p>Finally, note that for development environments that do not have ready access
        to an SMTP server, the <strong>QEmailServer</strong> can be set to <strong>TestMode</strong>,
        where communication between the application and the SMTP server will be written
        to disk instead of an SMTP socket.  This allows developers to develop and test
        email capability without actually sending out any emails.</p>

    <p>Feel free to View Source the code.  Note that the final <strong>Send</strong> call is
        commented out, so this page is actually non-functional.  But you can view the
        code to get a sense as to how the <strong>QEmailServer</strong> and its associated
        <strong>QEmailMessage</strong> class work.</p>
</div>

<div id="demoZone">
	<p>For obvious reasons, this page is non-functional.  To view the commented out source,
		please click on <strong>View Source</strong> button.</p>
<?php
	// We want to define our email SMTP server (it defaults to "localhost")
	// This would typically be done in prepend.inc, and its value should probably be a constant
	// that is defined in _configuration.inc
	QEmailServer::$SmtpServer = 'mx.acme.com';
	
	// Create a new message
	// Note that you can list multiple addresses and that QCubed supports Bcc and Cc
	$objMessage = new QEmailMessage();
	$objMessage->From = 'ACME Reporting Service <reporting@acme.com>';
	$objMessage->To = 'John Doe <jdoe@acme.com>, Jane Doe <jdoe2@acme.com>';
	$objMessage->Bcc = 'audit-system@acme.com';
	$objMessage->Subject = 'Report for ' . QDateTime::NowToString(QDateTime::FormatDisplayDate);
	
	// Setup Plaintext Message
	$strBody = "Dear John and Jane Doe,\r\n\r\n";
	$strBody .= "You have new reports to review.  Please go to the ACME Portal at http://portal.acme.com/ to review.\r\n\r\n";
	$strBody .= "Regards,\r\nACME Reporting Service";
	$objMessage->Body = $strBody;
	
	// Also setup HTML message (optional)
	$strBody = 'Dear John and Jane Doe,</p>';
	$strBody .= '<strong>You have new reports to review.</strong>  Please go to the <a href="http://portal.acme.com/">ACME Portal</a> to review.</p>';
	$strBody .= 'Regards,<br/><strong>ACME Reporting Service</strong>';
	$objMessage->HtmlBody = $strBody;
	
	// Add random/custom email headers
	$objMessage->SetHeader('x-application', 'ACME Reporting Service v1.2a');
	
	// Add a text file attachment (you can also send non-text attachments with QEmailAttachment)
	$strAttachmentContents = "This is the text file's contents";
	$objAttachment = new QEmailStringAttachment($strAttachmentContents, QMimeType::PlainText, "attachment.txt");
	$objMessage->AddAttachment($objAttachment);
	
	// Send the Message (Commented out for obvious reasons)
	//	QEmailServer::Send($objMessage);
	// Note that you can also shortcut the Send command to one line for simple messages (similar to PHP's mail())
	$strBody = "Dear John and Jane Doe,\r\n\r\n";
	$strBody .= "You have new reports to review.  Please go to the ACME Portal at http://portal.acme.com/ to review.\r\n\r\n";
	$strBody .= "Regards,\r\nACME Reporting Service";
	//	QEmailServer::Send(new QEmailMessage('reporting@acme.com', 'jdoe@acme.com', 'Alerts Received!', $strBody));
?>
</div>

<?php require('../includes/footer.inc.php'); ?>