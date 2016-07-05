<?php require_once('../qcubed.inc.php'); ?>
<?php require('../includes/header.inc.php'); ?>

	<div id="instructions">
		<h1>Setting Up an RSS Feed</h1>

		The <b>QRssFeed</b> class can be used to create an <b>RSS 2.0</b>-compliant XML feed.
		For more information about <b>RSS 2.0</b>, please view the official
		<b><a href="http://blogs.law.harvard.edu/tech/rss" class="bodyLink">RSS 2 Specification</a></b>.
		(Yes, it goes to a Harvard Law School server.  Why Harvard Law School hosts the <b>RSS 2.0 Specification</b>
		is completely beyond us.  But they do. =)<br/><br/>
		
		The <b>QRssFeed</b> and its associated classes should be pretty self-explanatory.  The public properties
		map directly to the fields of the RSS message, itself.  When your feed object is complete, you can call
		the <b>Run</b> method to output the feed.<br/><br/>
		
		For purposes of this example, we've also added the appropriate &lt;link&gt; tag on this <b>rss.php</b> to inform
		web browsers of the availability of the RSS feed.  Please run or view the code for <b>rss_feed.php</b> to
		view <b>QRssFeed</b> in action.
	</div>

<div id="demoZone">
	<p>To view an example RSS feed of the Examples Site Projects, please run <a href="rss_feed.php" class="bodyLink"><b>rss_feed.php</b></a>.</p>

	<!-- Note, X/HTML Standards Compliance dictates that this "<link>" tag be in the "<head>" of this XHTML document. -->
	<!-- For purposes of this demonstration, we've put the link tag here, in the body. -->
	<!-- In your own applications, it is recommended that this reside in the <HEAD> section of your document. -->
	<link rel="alternate" type="application/rss+xml" title="Qcubed Examples Site Projects" href="<?php _p(__VIRTUAL_DIRECTORY__ . __EXAMPLES__); ?>/communication/rss_feed.php" />
</div>
	
<?php require('../includes/footer.inc.php'); ?>