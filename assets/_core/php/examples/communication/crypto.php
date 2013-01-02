<?php require_once('../qcubed.inc.php'); ?>
<?php require('../includes/header.inc.php'); ?>

<div id="instructions">
    <h1>Implementing Cryptography</h1>

    <p>The <strong>QCryptography</strong> class is used to implement cryptography for your site and
        back-end.  It primarly serves as a cohesive wrapper around the <strong>libmcrypt</strong> library,
        which must also be installed.  (According to the PHP documentation, you must have
        <strong>libmcrypt</strong> v2.5.6 or greater for PHP 5)</p>

    <p>By default, <strong>QCryptography</strong> will use the <strong>TripleDES</strong> cipher in <strong>Electronic
            Codebook</strong> mode.  It will also conveniently do base64 conversion (similar to MIME-based
        Base64 encoding) so that the resulting encrypted data can be used in text-based streams,
        GET/POST data, URLs, etc.</p>

    <p>However, note that any of these options can be changed at any time.  Through the <strong>libmcrypt</strong>
        library, <strong>QCryptography</strong> supports most of the industry accepted ciphers,
        including <strong>DES</strong>, <strong>ARC4</strong>, <strong>Blowfish</strong>, <strong>Rijndael</strong>, <strong>RC2</strong>, <strong>RC4</strong>,
        <strong>RC6</strong>, etc.</p>

    <p>You can statically specify a "default" cipher, mode, base64 flag and key by modifying
        the appropriate static member variable on the class, or you can specify these fields
        explicitly when constructing a new instance of <strong>QCryptography</strong>.</p>

    <p><strong>QCryptography</strong> also supports the encryption and decryption of entire files.</p>

    <p>For more information about the <strong>libmcrypt</strong> library, please refer to the
        <a href="http://www.php.net/manual/en/ref.mcrypt.php" class="bodyLink">PHP Documentation</a>.</p>
</div>

<h3>TripleDES, Electronic Codebook Encryption</h3>
<ul>
    <?php
    $strOriginal = 'The quick brown fox jumps over the lazy dog.';

    // Modify the cipher and base64 mode by modifying the "default" cipher and mode on the class, itself
    // Specify a Key (this would typically be defined as a constant (e.g. in _configuration.inc)
    QCryptography::$Key = 'SampleKey';

    // By default, let's leave Base64 encoding turned off
    QCryptography::$Base64 = false;

    try {
        $objCrypto = new QCryptography();
        $strEncrypted = $objCrypto->Encrypt($strOriginal);
        $strDecrypted = $objCrypto->Decrypt($strEncrypted);

        printf('<li>Original Data: <strong>%s</strong></li>', $strOriginal);
        printf('<li>Encrypted Data: <strong>%s</strong></li>', $strEncrypted);
        printf('<li>Decrypted Data: <strong>%s</strong></li>', $strDecrypted);
    } catch (QCryptographyException $e) {
        echo "<p>Cannot run the sample code because libmcrypt PHP module is not installed</p>";
    }
    ?>
</ul>
<h3>TripleDES, Electronic Codebook Encryption (with Base64 encoding)</h3>
<ul>
    <?php
    $strOriginal = 'Just keep examining every low bid quoted for zinc etchings.';

    // Modify the base64 mode while making the specification on the constructor, itself
    // By default, let's instantiate a QCryptography object with Base64 encoding enabled
    // Note: while the resulting encrypted data is safe for any text-based stream, including
    // use as GET/POST data, inside the URL, etc., the resulting encrypted data stream will
    // be 33% larger.
    try {
        $objCrypto = new QCryptography(null, true);
        $strEncrypted = $objCrypto->Encrypt($strOriginal);
        $strDecrypted = $objCrypto->Decrypt($strEncrypted);

        printf('<li>Original Data: <strong>%s</strong></li>', $strOriginal);
        printf('<li>Encrypted Data: <strong>%s</strong></li>', $strEncrypted);
        printf('<li>Decrypted Data: <strong>%s</strong></li>', $strDecrypted);
    } catch (QCryptographyException $e) {
        echo "<p>Cannot run the sample code because libmcrypt PHP module is not installed</p>";
    }
    ?>
</ul>
    <?php require('../includes/footer.inc.php'); ?>
