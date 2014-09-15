<?php

require(__QCUBED__ . '/codegen/QCodeGen.class.php');

/**
 * Class QMetaEditDlg
 *
 * A dialog that lets you specify code generation options for a control. These options control how a control
 * is generated, and includes additional parameters that can be specified for a control.
 *
 * This dialog pops up when designer mode is turned on and the user right clicks on a control.
 *
 * The code below will set up the dialog and display options that are generic to all QControls. Individual
 * controls can add parameters to this dialog by implementing the GetMetaParams function.
 *
 * Everything gets saved in the configuration/codegen_options.json file.
 *
 *
 */
class QMetaEditDlg extends QDialog {

	protected $objCurrentControl;
	protected $tabs;

	protected $txtName;
	protected $txtControlId;
	protected $txtControlClass;
	protected $lstFormGen;

	protected $params;
	protected $objMetacontrolOptions;

	protected $definitionOptions;
	protected $dtgDefinitionOptions;

	protected $generalOverrides;
	protected $dtgGeneralOverrides;

	protected $specificOverrides;
	protected $dtgSpecificOverrides;

	protected $tabSpecific;

	public function __construct($objParentObject, $strControlId) {
		parent::__construct ($objParentObject, $strControlId);

		$this->AutoRenderChildren = true;
		$this->Width = 700;

		$this->objMetacontrolOptions = new QMetacontrolOptions();

		$this->tabs = new QTabs ($this, "tabPanel");
		$this->tabs->HeightStyle = "auto";

		//$panel = new QPanel ($this->tabs, 'panel');
		//$panel->AutoRenderChildren = true;

		$this->dtgDefinitionOptions = new QSimpleTable($this->tabs, 'definitionTab');
		$this->dtgDefinitionOptions->ShowHeader = false;
		$this->dtgDefinitionOptions->Name = "Definition Options";
		$this->dtgDefinitionOptions->CreatePropertyColumn('Attribute', 'Name');
		$col = $this->dtgDefinitionOptions->AddColumn (new QSimpleTableClosureColumn('Attribute', array ($this, 'dtg_ValueRender'), $this->dtgDefinitionOptions));
		$col->HtmlEntities = false;
		$this->dtgDefinitionOptions->SetDataBinder('definitionsBind', $this);

		$this->definitionOptions = array (
			new QMetaParam ('FormGen',
				'Whether or not to generate this object, just a label for the object, just the metacontrol, or both the control and label',
				QType::ArrayType,
				array (null=>'Both', 'none'=>'None', 'meta'=>'Meta', 'label'=>'Label'),
				QMetaParam::Quote),
			new QMetaParam ('Name', 'Control\'s Name', QType::String),
			new QMetaParam ('ControlClass', 'Override of the PHP type for the control', QType::String)
		);

		// General overrides coming from QControl
		$this->generalOverrides  = array(
			new QMetaParam ('CssClass', 'Css Class assigned to the control', QType::String),
			new QMetaParam ('AccessKey', 'Access Key to focus control', QType::String),
			new QMetaParam ('CausesValidation', 'How and what to validate. Can also be set to a control.', QType::ArrayType,
				array(
					null=>'None',
					'QCausesValidation::AllControls'=>'All Controls',
					'QCausesValidation::SiblingsAndChildren'=>'Siblings And Children',
					'QCausesValidation::SiblingsOnly'=>'Siblings Only'
				)
			),
			new QMetaParam ('Enabled', 'Will it start as enabled (default true)?', QType::Boolean),
			new QMetaParam ('Required', 'Will it fail validation if nothing is entered (default depends on data definition, if NULL is allowed.)?', QType::Boolean),
			new QMetaParam ('TabIndex', '', QType::Integer),
			new QMetaParam ('ToolTip', '', QType::String),
			new QMetaParam ('Visible', '', QType::Boolean),
			new QMetaParam ('Height', 'Height in pixels. However, you can specify a different unit (e.g. 3.0 em).', QType::String),
			new QMetaParam ('Width', 'Width in pixels. However, you can specify a different unit (e.g. 3.0 em).', QType::String),
			new QMetaParam ('Instructions', 'Additional help for user.', QType::String),
			new QMetaParam ('Moveable', '', QType::Boolean),
			new QMetaParam ('Resizable', '', QType::Boolean),
			new QMetaParam ('Droppable', '', QType::Boolean),
			new QMetaParam ('UseWrapper', 'Control will be forced to be wrapped with a div', QType::Boolean),
			new QMetaParam ('WrapperCssClass', '', QType::String)

		);

		// a big table to scroll
		$panel = new QPanel ($this->tabs);
		$panel->SetCustomStyle('overflow-y', 'scroll');
		$panel->SetCustomStyle('max-height', '200');
		$panel->AutoRenderChildren = true;
		$panel->Name = 'QControl';

		$this->dtgGeneralOverrides = new QSimpleTable($panel, 'generalTab');
		$this->dtgGeneralOverrides->ShowHeader = false;
		$this->dtgGeneralOverrides->Name = "QControl";
		$this->dtgGeneralOverrides->CreatePropertyColumn('Attribute', 'Name');
		$col = $this->dtgGeneralOverrides->AddColumn (new QSimpleTableClosureColumn('Attribute', array ($this, 'dtg_ValueRender'), $this->dtgGeneralOverrides));
		$col->HtmlEntities = false;
		$this->dtgGeneralOverrides->SetDataBinder('generalBind', $this);


		// Options read by the codegen process to modify how the control is initially created
		//$this->dtgDefinitionOptions->DataSource = $this->definitionOptions;

		$this->tabSpecific = new QPanel ($this->tabs);
		$this->tabSpecific->SetCustomStyle('overflow-y', 'scroll');
		$this->tabSpecific->SetCustomStyle('max-height', '200');
		$this->tabSpecific->AutoRenderChildren = true;

		$this->dtgSpecificOverrides = new QSimpleTable($this->tabSpecific, 'specificTab');
		$this->dtgSpecificOverrides->ShowHeader = false;
		$this->dtgSpecificOverrides->CreatePropertyColumn('Attribute', 'Name');
		$col = $this->dtgSpecificOverrides->AddColumn (new QSimpleTableClosureColumn('Attribute', array ($this, 'dtg_ValueRender'), $this->dtgSpecificOverrides));
		$col->HtmlEntities = false;

		$this->AddButton ('Save', 'save');
		$this->AddButton ('Save, Regenerate and Reload', 'saveRefresh');
		$this->AddButton ('Cancel', 'cancel');

		$this->AddAction(new QDialog_ButtonEvent(), new QAjaxControlAction($this, 'ButtonClick'));
	}

	public function definitionsBind() {
		$this->dtgDefinitionOptions->DataSource = $this->definitionOptions;
	}

	public function generalBind() {
		$this->dtgGeneralOverrides->DataSource = $this->generalOverrides;
	}

	public function dtg_ValueRender (QMetaParam $objControlParam, $objParent) {
		$objControl = $objControlParam->GetControl ($objParent);
		return $objControl->Render(false);
	}

	public function EditControl ($objControl) {
		$this->objCurrentControl = $objControl;

		$this->Title = $objControl->Name . ' Edit';

		$this->dtgSpecificOverrides->Name = get_class($objControl);
		$this->ReadParams();
		$this->LoadParams();
		$this->Open();
		$this->tabs->Refresh();
	}

	public function ButtonClick ($strFormId, $strControlId, $mixParam) {
		if ($mixParam == 'save') {
			$this->UpdateControlInfo();
			$this->WriteParams();
		} elseif ($mixParam == 'saveRefresh') {
			$this->UpdateControlInfo();
			$this->WriteParams();
			QCodeGen::Run(__CONFIGURATION__ . '/codegen_settings.xml');
			foreach (QCodeGen::$CodeGenArray as $objCodeGen) {
				$objCodeGen->GenerateAll(); // silently codegen
			}
			QApplication::Redirect($_SERVER['PHP_SELF']);
		}

		$this->Close();
	}

	protected function UpdateControlInfo() {
		$objParams = $this->definitionOptions;
		foreach ($objParams as $objParam) {
			$objControl = $objParam->GetControl ($this->dtgDefinitionOptions);
			$strName = $objControl->Name;
			$value = $objControl->Value;

			if (!is_null($value)) {
				$this->params[$strName] = $value;
			} else {
				unset ($this->params[$strName]);
			}
		}

		$objParams = $this->generalOverrides;
		foreach ($objParams as $objParam) {
			$objControl = $objParam->GetControl ($this->dtgGeneralOverrides);
			$strName = $objControl->Name;
			$value = $objControl->Value;

			if (!is_null($value)) {
				$this->params['Overrides'][$strName] = $value;
			} else {
				unset ($this->params['Overrides'][$strName]);
			}
		}

		$objParams = $this->specificOverrides;
		if ($objParams) foreach ($objParams as $objParam) {
			$objControl = $objParam->GetControl ($this->dtgSpecificOverrides);
			$strName = $objControl->Name;
			$value = $objControl->Value;

			if (!is_null($value)) {
				$this->params['Overrides'][$strName] = $value;
			} else {
				unset ($this->params['Overrides'][$strName]);
			}
		}

		if (empty($this->params['Overrides'])) {
			unset ($this->params['Overrides']);
		}
	}


	protected function WriteParams() {
		$node = $this->objCurrentControl->LinkedNode;
		$strTable = $node->_ParentNode->_TableName;
		$this->objMetacontrolOptions->SetOptions ($strTable, $node->_Name, $this->params);
		$this->objMetacontrolOptions->Save();
	}

	protected function ReadParams() {
		$node = $this->objCurrentControl->LinkedNode;
		if ($node) {
			$strTable = $node->_ParentNode->_TableName;
			$this->params = $this->objMetacontrolOptions->GetOptions ($strTable, $node->_Name);
		}
	}

	protected function LoadParams() {
		$objParams = $this->definitionOptions;
		foreach ($objParams as $objParam) {
			$objControl = $objParam->GetControl ($this->dtgDefinitionOptions);
			$strName = $objControl->Name;

			if (isset($this->params[$strName])) {
				$objControl->Value = $this->params[$strName];
			} else {
				$objControl->Value = null;
			}
		}

		$objParams = $this->generalOverrides;
		foreach ($objParams as $objParam) {
			$objControl = $objParam->GetControl ($this->dtgGeneralOverrides);
			$strName = $objControl->Name;

			if (isset($this->params['Overrides'][$strName])) {
				$objControl->Value = $this->params['Overrides'][$strName];
			} else {
				$objControl->Value = null;
			}
		}


		$strControlType = get_class ($this->objCurrentControl);
		$this->tabSpecific->Name = $strControlType;

		if (method_exists($strControlType, 'GetMetaParams')) {
			$this->tabSpecific->Visible = true;
			$this->tabSpecific->SetParentControl($this->tabs);
			$this->specificOverrides = $strControlType::GetMetaParams();

			$objParams = $this->specificOverrides;
			foreach ($objParams as $objParam) {
				$objControl = $objParam->GetControl ($this->dtgSpecificOverrides);
				$strName = $objControl->Name;

				if (isset($this->params['Overrides'][$strName])) {
					$objControl->Value = $this->params['Overrides'][$strName];
				} else {
					$objControl->Value = null;
				}
			}


			$this->dtgSpecificOverrides->DataSource = $this->specificOverrides;
			$this->dtgSpecificOverrides->Refresh();
		} else {
			$this->tabSpecific->Visible = false;
			$this->tabSpecific->SetParentControl($this); // remove from tabs
		}
	}
}

