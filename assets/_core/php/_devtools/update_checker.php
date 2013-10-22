<?php
require_once('../qcubed.inc.php');

class UpdateCheckerForm extends QForm {
	const WEB_SERVICE_PATH = "http://examples.qcu.be/webservices/updatechecker.php";
	const QCUBED_FORUM = "http://qcu.be/forum";

	/* @var QDataGrid */
	protected $dtgUpdates;

	/* @var QDataGrid */
	protected $dtgNew;

	protected function Form_Run() {
		QApplication::CheckRemoteAdmin();
	}

	protected function Form_Create() {
		$arrLatestVersions = self::parseResponseIntoItems(self::makeHttpRequest(self::WEB_SERVICE_PATH));
		self::augmentWithInstalledInfo($arrLatestVersions);

		$this->dtgUpdates_Create($arrLatestVersions);
		$this->dtgNew_Create($arrLatestVersions);
	}

	private function dtgUpdates_Create($latestVersions) {
		$this->dtgUpdates = new QDataGrid($this, 'dtgUpdates');
		$this->dtgUpdates->AddColumn(new QDataGridColumn('Name',        '<?= $_FORM->RenderName($_ITEM) ?>', 'HtmlEntities=false'));
		$this->dtgUpdates->AddColumn(new QDataGridColumn('Description', '<?= $_ITEM->description ?>', 'HtmlEntities=false'));
		$this->dtgUpdates->AddColumn(new QDataGridColumn('Installed',   '<?= $_ITEM->installedVersion ?>'));
		$this->dtgUpdates->AddColumn(new QDataGridColumn('Available',   '<?= $_ITEM->availableVersion ?>'));

		$arrDataSource = array();
		foreach ($latestVersions as $item) {
			/** @var $item DownloadedItemt */
			if ($item->installedVersion != null && $item->availableVersion > $item->installedVersion) {
				$arrDataSource[] = $item;
			}
		}
		$this->dtgUpdates->DataSource = $arrDataSource;

		if (sizeof($this->dtgUpdates->DataSource) > 0) {
			QApplication::ExecuteJavaScript('jQuery("#lblNoUpdates").hide()');
		} else {
			QApplication::ExecuteJavaScript('jQuery("#dtgUpdates").hide()');
		}
	}

	private function dtgNew_Create($latestVersions) {
		$this->dtgNew = new QDataGrid($this, 'dtgNew');
		$this->dtgNew->AddColumn(new QDataGridColumn('Name', '<?= $_FORM->RenderName($_ITEM) ?>', 'HtmlEntities=false'));
		$this->dtgNew->AddColumn(new QDataGridColumn('Description', '<?= $_ITEM->description ?>', 'HtmlEntities=false'));

		$arrDataSource = array();
		foreach ($latestVersions as $item) {
			/** @var $item DownloadedItem */
			if ($item->installedVersion == null) {
				$arrDataSource[] = $item;
			}
		}
		$this->dtgNew->DataSource = $arrDataSource;

		if (sizeof($this->dtgNew->DataSource) > 0) {
			QApplication::ExecuteJavaScript('jQuery("#lblNoNew").hide()');
		} else {
			QApplication::ExecuteJavaScript('jQuery("#dtgNew").hide()');
		}
	}

	public function RenderName(DownloadedItem $objItem) {
		$result = "";
		if ($objItem->downloadUrl != null) {
			$result = "<a target='_blank' href='" . $objItem->downloadUrl . "'>" . $objItem->name . "</a>";
		} else {
			$result = $objItem->name;
		}
		return $result;
	}

	private static function parseResponseIntoItems($strResponse) {
		$result = array();
		$arrItems = json_decode($strResponse);
		if ($arrItems == null || !is_array($arrItems)) {
			throw new Exception("Update server error. Please report your problem on the QCubed forum at " . self::QCUBED_FORUM);
		}
		foreach ($arrItems as $returnedItem) {
			$objToInsert = new DownloadedItem();
			$objToInsert->name              = isset($returnedItem->name)        ? $returnedItem->name               : null;
			$objToInsert->description       = isset($returnedItem->description) ? $returnedItem->description        : null;
			$objToInsert->availableVersion  = isset($returnedItem->version)     ? floatval($returnedItem->version)  : null;
			$objToInsert->installedVersion  = null;
			$objToInsert->downloadUrl               = isset($returnedItem->downloadUrl)        ? $returnedItem->downloadUrl                 : null;

			if (!$objToInsert->name || !$objToInsert->availableVersion) {
				throw new Exception("Update server error. Please report your problem on the QCubed forum at " . self::QCUBED_FORUM);
			}
			$result[] = $objToInsert;
		}

		return $result;
	}

	private static function augmentWithInstalledInfo(& $arrLatestVersions) {
		$arrInstalledPlugins = QPluginConfigParser::parseInstalledPlugins();

		// Augment the list of instlaled plugins with QCubed core version
		$objCore  = new QPlugin();
		$objCore->strName = "QCubed";
		$objCore->strVersion = QCUBED_VERSION_NUMBER_ONLY;
		$arrInstalledPlugins[] = $objCore;

		foreach ($arrLatestVersions as $objDownloadedItem) {
			/** @var $objDownloadedItem DownloadedItem */
			foreach ($arrInstalledPlugins as $objInstalledPlugin) {
				/** @var $objInstalledPlugin QPlugin */
				if (strtolower($objInstalledPlugin->strName) == strtolower($objDownloadedItem->name)) {
					$objDownloadedItem->installedVersion = floatval($objInstalledPlugin->strVersion);
					break;
				}
			}
		}
	}

	private static function makeHttpRequest($url) {
		$defaults = array(
			CURLOPT_URL => $url,
			CURLOPT_HEADER => 0,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_TIMEOUT => 5
		);

		$ch = curl_init();
		curl_setopt_array($ch, $defaults);
                
                if(__CURLOPT_PROXY__){
                    curl_setopt($ch, CURLOPT_PROXY, __CURLOPT_PROXY__);
                }
                if(__CURLOPT_PROXYUSERPWD__){
                    curl_setopt($ch, CURLOPT_PROXYUSERPWD, __CURLOPT_PROXYUSERPWD__);
                }
                
		if( ! $result = curl_exec($ch)) {
			trigger_error(curl_error($ch));
		}
		curl_close($ch);
		return $result;
	}
}

class DownloadedItem {
	public $name;
	public $description;
	public $availableVersion;
	public $installedVersion;
	public $downloadUrl;
}

UpdateCheckerForm::Run('UpdateCheckerForm');
?>
