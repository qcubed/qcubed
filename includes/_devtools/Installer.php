<?php

/**
 * Routines to assist in the installation of various parts of QCubed.
 *
 */

namespace QCubed\Devtools;

use Composer\Installer\PackageEvent;


$__CONFIG_ONLY__ = true;

class Installer {

	protected static function startsWith($haystack, $needle) {
		// search backwards starting from haystack length characters from the end
		return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
	}


	public static function postPackageInstall(PackageEvent $event)
	{
		$installedPackage = $event->getOperation()->getPackage();
		$strPackageName = $installedPackage->getName();

		if (self::startsWith($strPackageName, 'qcubed/plugin')) {
			echo 'Copying ' . $strPackageName . " files.\n";
			self::ComposerPluginInstall($strPackageName);
		}
	}

	public static function postPackageUpdate(PackageEvent $event)
	{
		$installedPackage = $event->getOperation()->getInitialPackage();
		$strPackageName = $installedPackage->getName();
		if (self::startsWith($strPackageName, 'qcubed/plugin')) {
			echo 'Copying ' . $strPackageName . " files.\n";
			self::ComposerPluginInstall($strPackageName);
		}
		elseif (self::startsWith($strPackageName, 'qcubed/framework')) {
			// updating the framework
			self::ComposerFrameworkUpdate();
		}
	}

	public static function postPackageUninstall(PackageEvent $event)
	{
		$installedPackage = $event->getOperation()->getInitialPackage();
		$strPackageName = $installedPackage->getName();
		if (self::startsWith($strPackageName, 'qcubed/plugin')) {
			echo 'Removing ' . $strPackageName . "\n";
			self::ComposerPluginUninstall($strPackageName);
		}
	}

	public static function postRootInstall(PackageEvent $event)
	{
		$installedPackage = $event->getOperation()->getPackage();
		$strPackageName = $installedPackage->getName();
		if (self::startsWith($strPackageName, 'qcubed')) {	// double check that we are installing a qcubed project
			self::ComposerFrameworkInstall($installedPackage->getExtra());

			// add the scripts above to manage plugin installations and framework updates
			$installedPackage->setScripts([
				"post-package-install"=>["QCubed\\Devtools\\Installer::postPackageInstall"],
				"post-package-update"=>["QCubed\\Devtools\\Installer::postPackageUpdate"],
    			"post-package-uninstall"=>["QCubed\\Devtools\\Installer::postPackageUninstall"]
			]);
		}
	}


	/**
	 * Move files out of the vendor directory and into the project directory that are in the plugin's install directory.
	 * @param $strPackageName
	 */
	public static function ComposerPluginInstall ($strPackageName) {
		require_once(dirname(__FILE__) . '/../../qcubed.inc.php');	// get the configuration options so we can know where to put the plugin files

		// recursively copy the contents of the install directory, providing each file is not there.
		$strPluginDir = dirname(__FILE__).'/../../../plugin/' . $strPackageName . '/install';
		$strDestDir = __INCLUDES__ . '/plugins';

		if (file_exists($strPluginDir)) {
			self::copy_dir($strPluginDir, $strDestDir);
		}
	}

	/**
	 * First time installation of framework. For first-time installation, we create the project directory and modify
	 * the configuration file.
	 *
	 * @param $strPackageName
	 */
	public static function ComposerFrameworkInstall ($extra) {
		// recursively copy the contents of the install directory, providing each file is not there.
		$strInstallDir = dirname(__FILE__).'/../../install/project';
		$strDestDir = dirname(__FILE__).'/../../../../../project';

		self::copy_dir($strInstallDir, $strDestDir);

		// Make sure particular directories are writable by the web server. These are listed in the extra section of the composer.json file.
		$strInstallDir = dirname(__FILE__).'/../../../../../';

		foreach ($extra['writePermission'] as $strDir) {
			chmod ($strInstallDir . $strDir, 0777);
		}
	}

	public static function ComposerFrameworkUpdate () {
		require_once(dirname(__FILE__) . '/../../qcubed.inc.php');	// get the configuration options so we can know where to put the plugin files

		// recursively copy the contents of the install directory, providing each file is not there.
		$strInstallDir = dirname(__FILE__).'/../../install/project';
		$strDestDir = __PROJECT__;

		// copy_dir will not overwrite files, but will add any new stub files
		self::copy_dir($strInstallDir, $strDestDir);
	}


	protected static function copy_dir($src,$dst) {
		$dir = opendir($src);

		if (!file_exists($dst)) {
			mkdir($dst);
		}
		while(false !== ( $file = readdir($dir)) ) {
			if (( $file != '.' ) && ( $file != '..' )) {
				if ( is_dir($src . '/' . $file) ) {
					self::copy_dir($src . '/' . $file,$dst . '/' . $file);
				}
				else {
					if (!file_exists($dst . '/' . $file)) {
						copy($src . '/' . $file,$dst . '/' . $file);
					}
				}
			}
		}
		closedir($dir);
	}

	public static function ComposerPluginUninstall ($strPackageName) {
		// recursively delete the contents of the install directory, providing each file is there.
		$strPluginDir = dirname(__FILE__).'/../../../plugin/' . $strPackageName . '/install';
		$strDestDir = __INCLUDES__ . '/plugins';

		self::remove_matching_dir($strPluginDir, $strDestDir);
	}

	protected static function remove_matching_dir($src,$dst) {
		if (!$dst || !$src) return;	// prevent deleting an entire disk by accidentally calling this with an empty string!
		$dir = opendir($src);

		while(false !== ( $file = readdir($dir)) ) {
			if (( $file != '.' ) && ( $file != '..' )) {
				if ( is_dir($src . '/' . $file) ) {
					remove_dir($dst . '/' . $file);
				}
				else {
					if (file_exists($dst . '/' . $file)) {
						unlink($dst . '/' . $file);
					}
				}
			}
		}
		closedir($dir);
	}

	protected static function remove_dir($dst) {
		if (!$dst) return;	// prevent deleting an entire disk by accidentally calling this with an empty string!
		$dir = opendir($dst);

		while(false !== ( $file = readdir($dir)) ) {
			if (( $file != '.' ) && ( $file != '..' )) {
				if ( is_dir($dst . '/' . $file) ) {
					remove_dir($dst . '/' . $file);
				}
				else {
					unlink($dst . '/' . $file);
				}
			}
		}
		closedir($dir);
		rmdir($dst);
	}



}
