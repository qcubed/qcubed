<?php
	/**
	 * QImageLabel is defined in this file
	 * @package Controls
	 * @filesource
	 */

	/**
	 * This class can render an Image/Bitmapped version of any Text string
	 *
	 * t extends the QImageLabelBase class. When writing your code, use this class instead of QImageLabelBase.
	 * This class can be modified by the developer to add functions and alter the already present functions.
	 * @package Controls
	 */
	class QImageLabel extends QImageLabelBase {
		/**
		 *  If you wish to set a cache for the generated images so that they
		 * are not dynamically recreated every time, specify a default CacheFolder here.
		 *
		 * The Cache Folder is an absolute folder location relative to the root of the
		 * QCubed application.  So for example, if you have the QCubed application installed
		 * at /var/www/my_application, and if docroot is "/var/www" then you should have
		 *  a subfolder named "/my_application". Now if you specify
		 * a CacheFolder of "/text_images", the following will happen:
		 *
		 * Cached images will be stored at /var/web/wwwroot/my_application/text_images/...
		 * Cached images will be accessed by <code><img src="/my_application/text_images/..."></code>
		 * Remember: CacheFolder *must* have a leading "/" and no trailing "/", and also
		 * be sure that the webserver process has WRITE access to the CacheFolder, itself.
		 * @var string
		 */
		protected $strCacheFolder = null;
	}
?>