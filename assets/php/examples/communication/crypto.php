<?php require_once('../qcubed.inc.php'); ?>
<?php require('../includes/header.inc.php'); ?>

<div id="instructions">
	<h1>Implementing Cryptography</h1>

	<p>The <strong>QCryptography</strong> class is used to implement cryptography for your site and
		back-end. QCryptography uses methods from <strong>openssl</strong> library integrated into PHP </p>

	<p>By default, <strong>QCryptography</strong> will use the <strong>AES 256-bit</strong> cipher in <strong>CBC (Cipher Block Chaining)</strong> mode. We choose AES-256-CBC because it is a strong cipher and is recommended by the US government for it's own secret documents and hence is widely accepted too. QCryptography can also conveniently do base64 conversion (similar to MIME-based
		Base64 encoding) so that the resulting encrypted data can be used in text-based streams,
		GET/POST data, URLs, etc.  By default Base64 encoding is enabled, since we mostly deal with HTTP on the web and it's just easier to transport text than binary.</p>

	<p>However, note that any of these options can be changed at any time.  Through the <strong>openssl</strong>
		library, <strong>QCryptography</strong> supports most of the industry accepted ciphers. You can use the <strong><a href="http://php.net/manual/en/function.openssl-get-cipher-methods.php" target="_blank">openssl-get-cipher-methods</a></strong> method to see the list of supported encryption methods.</p>

	<p>You can specify a "default" cipher, base64 flag, key and Initialization Vector by modifying
		the arguments when constructing a new instance of <strong>QCryptography</strong>.</p>

	<p><em>Asymmetric Cryptography (using public-private key pairs) is not yet supported.</em></p>

	<p><strong>QCryptography</strong> also supports the encryption and decryption of entire files.</p>

	<p><strong>TIP:</strong> Remember to change the default Key in the configuration file after installation. QCubed is an open-source project. Leaving the defaults in your configuration will open a vulnerability in your cryptographic attempts.</p>
</div>

<div id="demoZone">
	<h2>Default Settings - AES 256-bit CBC with default IV and Key</h2>
	<ul>
		<?php
			$strOriginal = 'The quick brown fox jumps over the lazy dog.';

			try {
				$objCrypto = new QCryptography();
				$strEncrypted = $objCrypto->Encrypt($strOriginal);
				$strDecrypted = $objCrypto->Decrypt($strEncrypted);

				printf('<li>Original Data: <strong>%s</strong></li>', $strOriginal);
				printf('<li>Encrypted Data: <pre><code>%s</code></pre></li>', $strEncrypted);
				printf('<li>Decrypted Data: <strong>%s</strong></li>', $strDecrypted);
			} catch (QCryptographyException $e) {
				throw $e;
			}
		?>
	</ul>

	<h2>Blowfish, Cipher Block Chaining, with Base64 encoding and a custom IV)</h2>
	<ul>
		<?php
			$strOriginal = 'The quick brown fox jumps over the lazy dog.';

			// Modify the base64 mode while making the specification on the constructor, itself
			// By default, let's instantiate a QCryptography object with Base64 encoding enabled
			// Note: while the resulting encrypted data is safe for any text-based stream, including
			// use as GET/POST data, inside the URL, etc., the resulting encrypted data stream will
			// be 33% larger.
			try {
				$objCrypto = new QCryptography(null, true, 'BF-CBC', 'QCUBED12');
				$strEncrypted = $objCrypto->Encrypt($strOriginal);
				$strDecrypted = $objCrypto->Decrypt($strEncrypted);

				printf('<li>Original Data: <strong>%s</strong></li>', $strOriginal);
				printf('<li>Encrypted Data: <pre><code>%s</code></pre></li>', $strEncrypted);
				printf('<li>Decrypted Data: <strong>%s</strong></li>', $strDecrypted);
			} catch (QCryptographyException $e) {
				throw $e;
			}
		?>
	</ul>

	<h2>Blowfish, Cipher Block Chaining, without Base64 encoding and the same custom IV as above)</h2>
	<ul>
		<?php
			$strOriginal = 'The quick brown fox jumps over the lazy dog.';

			// Modify the base64 mode while making the specification on the constructor, itself
			// By default, let's instantiate a QCryptography object with Base64 encoding enabled
			// Note: while the resulting encrypted data is safe for any text-based stream, including
			// use as GET/POST data, inside the URL, etc., the resulting encrypted data stream will
			// be 33% larger.
			try {
				$objCrypto = new QCryptography(null, false, 'BF-CBC', 'QCUBED12');
				$strEncrypted = $objCrypto->Encrypt($strOriginal);
				$strDecrypted = $objCrypto->Decrypt($strEncrypted);

				printf('<li>Original Data: <strong>%s</strong></li>', $strOriginal);
				printf('<li>Encrypted Data: <pre><code>%s</code></pre></li>', $strEncrypted);
				printf('<li>Decrypted Data: <strong>%s</strong></li>', $strDecrypted);
			} catch (QCryptographyException $e) {
				throw $e;
			}
		?>
	</ul>
</div>

<?php require('../includes/footer.inc.php'); ?>
