<?php
require_once('../qcubed.inc.php');

class ExampleForm extends QForm {

	/** @var QHtmlTable */
	protected $tblProjects;

	protected function Form_Create() {
		// Define the DataGrid
		$this->tblProjects = new QHtmlTable($this);

		// This css class is used to style alternate rows and the header, all in css
		$this->tblProjects->CssClass = 'simple_table';

		// Define Columns

		// Show the name of the project
		$this->tblProjects->CreateNodeColumn('Project', QQN::Project()->Name);

		// Date column formatting. Uses the Format string to format the date object that is in the column.
		$col = $this->tblProjects->CreateNodeColumn('Start Date', QQN::Project()->StartDate);
		$col->Format = 'MM/DD/YY';
		$col = $this->tblProjects->CreateNodeColumn('End Date', QQN::Project()->EndDate);
		$col->Format = 'DDD, MMM D, YYYY';

		// PersonAsTeamMemberArray is an array of names. Use a callback to format the array into a string.
		$col = $this->tblProjects->CreatePropertyColumn('Members', 'PersonAsTeamMemberArray');
		$col->PostCallback = 'ExampleForm::RenderTeamMemberArray';

		//
		$col = $this->tblProjects->CreateCallableColumn('Balance', [$this, 'dtgPerson_BalanceRender']);
		$col->CellParamsCallback = [$this, 'dtgPerson_BalanceAttributes'];

		$this->tblProjects->SetDataBinder('tblProjects_Bind');

	}

	/**
	 * Bind the Projects table to the html table.
	 *
	 * @throws QCallerException
	 */
	protected function tblProjects_Bind() {
		// Expand the PersonAsTeamMember node as an array so that it will be included in each item sent to the columns.
		$clauses = QQ::ExpandAsArray(QQN::Project()->PersonAsTeamMember);

		// We load the data source, and set it to the datagrid's DataSource parameter
		$this->tblProjects->DataSource = Project::LoadAll($clauses);
	}

	/**
	 * Render the team member array as a string.
	 *
	 * @param array $a
	 * @return string
	 */
	public static function RenderTeamMemberArray($a) {
		if ($a) {
			return implode(', ',
				array_map(function($val) {return $val->FirstName . ' ' . $val->LastName; }, $a));
		}
		else {
			return '';
		}
	}

	/**
	 * Render the number in the column. If the number is negative, uses parentheses to show its negative.
	 *
	 * @param $item
	 * @return string
	 */
	public function dtgPerson_BalanceRender($item) {
		$val = $item->Budget - $item->Spent;
		if ($val < 0) {
			return '(' . number_format(-$val) . ')';
		}
		else {
			return number_format($val);
		}
	}

	/**
	 * Style the number in the column. All number columns will use the amount class. If the number is negative, make
	 * the cell red.
	 *
	 * @param $item
	 * @return mixed
	 */
	public function dtgPerson_BalanceAttributes($item)
	{
		$ret['class'] ='amount';
		$val = $item->Budget - $item->Spent;

		if ($val < 0) {
			$ret['style'] = 'color:red';
		}
		return $ret;
	}
}

ExampleForm::Run('ExampleForm');
?>