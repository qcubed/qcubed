<?php
require_once('../qcubed.inc.php');

class ExampleForm extends QForm {

	/** @var QHtmlTable */
	protected $tblProjects;
	protected $pnlClick;
	protected $pxyLink;

	protected function Form_Create() {
		// define the proxy that we will use later
		$this->pxyLink = new QControlProxy($this);
		$this->pxyLink->AddAction(new QMouseOverEvent(), new QAjaxAction('mouseOver'));

		// Define the DataGrid
		$this->tblProjects = new QHtmlTable($this);

		// This css class is used to style alternate rows and the header, all in css
		$this->tblProjects->CssClass = 'simple_table';

		// Define Columns

		// Create a link column that shows the name of the project, and when clicked, calls back to this page with an id
		// of the item clicked on
		$this->tblProjects->CreateLinkColumn('Project', '->Name', QApplication::$ScriptName, ['intId'=>'->Id']);

		// Create a link column using a proxy
		$col = $this->tblProjects->CreateLinkColumn('Status', '->ProjectStatusType', $this->pxyLink, '->Id');

		$this->tblProjects->SetDataBinder('tblProjects_Bind');

		$this->pnlClick = new QPanel($this);

		if (($intId = QApplication::QueryString('intId')) && ($objProject = Project::Load($intId))) {
			$this->pnlClick->Text = 'You clicked on ' . $objProject->Name;
		}

	}

	/**
	 * Bind the Projects table to the html table.
	 *
	 * @throws QCallerException
	 */
	protected function tblProjects_Bind() {
		// We load the data source, and set it to the datagrid's DataSource parameter
		$this->tblProjects->DataSource = Project::LoadAll();
	}

	public function mouseOver($strFormId, $strControlId, $param) {
		if ($objProject = Project::Load($param)) {
			$this->pnlClick->Text = 'You hovered over ' . $objProject->Name;
		}
	}

}

ExampleForm::Run('ExampleForm');
?>