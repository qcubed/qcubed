<?php
	/**
	 * QImageControl is defined in this file
	 *
	 * @package Controls
	 * @filesource
	 */


	/**
	 * This control is used to render an 'Image' (i.e. img HTML tag).
	 *
	 * While it might seem strange that QCubed provides a control for Images, it is to help you
	 * when you want to dynamically alter some properties of an image (e.g. via AJAX action!)
	 * @package Controls
	 */
	class QImageControl extends QImageControlBase {
		/**
		 * If you wish to set a cache for the generated images so that they
		 * are not dynamically recreated every time, specify a default CacheFolder here.
		 *
		 * The Cache Folder is an absolute folder location relative to the root of the
		 * QCubed application.  So for example, if you have the QCubed application installed
		 * at /var/www/my_application, and if docroot is "/var/www" then you would be having
		 * a subfolder defined as "/my_application" in "/var/www". Now, if you specify
		 * a CacheFolder of "/text_images", the following will happen:
		 *
		 * Cached images will be stored at /var/www/my_application/text_images/...
		 * Cached images will be accessed by <code><img src="/my_application/text_images/..."></code>
		 * Remember: CacheFolder *must* have a leading "/" and no trailing "/", and also
		 * be sure that the webserver process has WRITE access to the CacheFolder, itself.
		 * @var string
		 */
		protected $strCacheFolder = null;
	}
?>