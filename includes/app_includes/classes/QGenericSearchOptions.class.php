<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * User: vakopian
	 * Date: 3/23/12
	 * Time: 11:06 AM
	 */

	abstract class QStringComparisonMode {
		const exact = 'exact';
		const startsWith = 'startsWith';
		const endsWith = 'endsWith';
		const contains = 'contains';
		const like = 'like';
	}

	abstract class QDateComparisonMode {
		const exact = 'exact';
		const withinSecond = 'withinSecond';
		const withinMinute = 'withinMinute';
		const withinHour = 'withinHour';
		const withinDay = 'withinDay';
		const withinMonth = 'withinMonth';
		const withinYear = 'withinYear';
	}

	class SearchNode {
		/** @var string */
		public $strProperty;
		/** @var string[] */
		public $strPropertiesArray;
		/** @var string */
		public $strType;
		protected $objTerm;

		public function __construct($strProperty, $strType, $objTerm) {
			$this->strProperty = $strProperty;
			$this->strPropertiesArray = explode('->', $strProperty);
			$this->strType = $strType;
			$this->objTerm = $objTerm;
		}

		/**
		 * @param QQBaseNode $objBaseNode
		 * @param  string[] $strProperties
		 * @return QQBaseNode the node corresponding to the provided nested properties starting from QQN::Customer()
		 */
		public static function GetNestedNode($objBaseNode, $strProperties) {
			$objNode = $objBaseNode;
			if ($objNode != null) {
				foreach ($strProperties as $strProperty) {
					$objNode = $objNode->$strProperty;
				}
			}
			return $objNode;
		}

		protected function GetNode($objBaseNode) {
			return self::GetNestedNode($objBaseNode, $this->strPropertiesArray);
		}

		protected function GetTerm() {
			return $this->objTerm;
		}

		public function MakeCondition(QQBaseNode $objBaseNode, QGenericSearchOptions $objSearchOptions) {
			$searchTerm = $this->GetTerm();
			if (is_null($searchTerm) || $searchTerm === '')
				return null;
			return $objSearchOptions->MakeNodeCondition($this->GetNode($objBaseNode), $this->strProperty, $this->strType, $searchTerm);
		}
	}

	/**
	 * <dl>
	 * @property bool $MatchAllTerms if true, the conditions for all the terms will be joined with AND, otherwise with OR. Default: true.
	 * @property bool $SkipPrimaryKey if true, the primary key columns will not be used for searching. Default: true.
	 * @property bool|array $SkipTypeCast if true, the attempt to cast the term to the type of the column will be skipped. The value of this option can also be
	 *                             an associative array, where the key is the property, and value is boolean that control the skipping. Default: false.
	 * @property bool|array $SkipWrongTypes if true, and if type cast is not skipped, the condition will be skipped when the cast fails. Default: true.
	 * @property string $StringComparisonMode controls the way a string property is compared. Accepted values are constants from QStringComparisonMode:
	 *       <dl>
	 *         <dt>exact</dt> <dd>uses Equal query</dd>
	 *         <dt>startsWith</dt> <dd>uses Like query, appends '%' to the term</dd>
	 *         <dt>endsWith</dt> <dd>uses Like query, prepends '%' to the term</dd>
	 *         <dt>contains</dt> <dd>uses Like query, surrounds the term with '%'</dd>
	 *         <dt>like</dt> <dd>uses Like query, keeps the term as is</dd>
	 *       </dl>
	 *       The value of this option can also be an associative array, where the key is the property and the value is the comparison mode.
	 *       Default: 'contains'</dd>
	 * @property string $DateComparisonMode controls the way a date property is compared. Accepted values are constants from QDateComparisonMode:
	 *       <dl>
	 *         <dt>exact</dt> <dd>uses Equal query</dd>
	 *         <dt>withinSecond</dt> <dd>use a Between query, where the left hand side is the term, and the right hand side adds 1 second to it.</dd>
	 *         <dt>withinMinute</dt> <dd>use a Between query, where the left hand side is the term with truncated seconds, and the right hand side adds 1 minute to it.</dd>
	 *         <dt>withinHour</dt> <dd>use a Between query, where the left hand side is the term with truncated seconds and minutes, and the right hand side adds 1 hour to it.</dd>
	 *         <dt>withinDay</dt> <dd>use a Between query, where the left hand side is the term with truncated time, and the right hand side adds 1 day to it.</dd>
	 *         <dt>withinMonth</dt> <dd>use a Between query, where the left hand side is the term with truncated time and day, and the right hand side adds 1 month to it.</dd>
	 *         <dt>withinYear</dt> <dd>use a Between query, where the left hand side is the term with truncated time, day and month, and the right hand side adds 1 year to it.</dd>
	 *       </dl>
	 *       The value of this option can also be an associative array, where the key is the property and the value is the comparison mode.
	 *       Default: QDateComparisonMode::withinDay.</dd>
	 * @property string[] $ExcludeProperties an array of property names to be excluded from the search. Setting this property to 'all' will exclude all the properties. Default: []
	 * @property string[] $ExtraProperties an associative array (property name to type) of properties to be included in the search (even when $ExcludeProperties is set to 'all'. Default: []
	 */
	class QGenericSearchOptions {
		public $defaultSkipTypeCast = false;
		public $defaultSkipBooleans = true;
		public $defaultSkipNumbers = false;
		public $defaultSkipDates = false;
		public $defaultStringComparisonMode = QStringComparisonMode::contains;
		public $defaultDateComparisonMode = QDateComparisonMode::withinDay;

		/** @var bool */
		public $MatchAllTerms = true;
		/** @var bool */
		public $SkipPrimaryKey = true;
		/** @var bool */
		public $SkipWrongTypes = true;
		/** @var bool */
		public $TrimQuotes = true;
		/** @var bool[] */
		public $SkipTypeCast = array();
		/** @var bool[] */
		public $SkipBoolean = array();
		/** @var bool[] */
		public $SkipNumber = array();
		/** @var bool[] */
		public $SkipDate = array();
		/** @var string[] */
		public $StringComparisonMode = array();
		/** @var string[] */
		public $DateComparisonMode = array();
		/** @var array */
		public $ExcludeProperties = array();
		/** @var array */
		public $ExtraProperties = array();

		/**
		 * @return boolean
		 */
		public function IsMatchAllTerms() {
			return $this->MatchAllTerms;
		}

		/**
		 * @return boolean
		 */
		public function IsSkipPrimaryKey() {
			return $this->SkipPrimaryKey;
		}

		/**
		 * @return boolean
		 */
		public function IsSkipWrongTypes() {
			return $this->SkipWrongTypes;
		}

		/**
		 * @return boolean
		 */
		public function IsTrimQuotes() {
			return $this->TrimQuotes;
		}

		/**
		 * true if type cast should be skipped for the property
		 * @param string $strProperty name of property
		 * @return bool
		 */
		public function IsSkipTypeCast($strProperty) {
			if (array_key_exists($strProperty, $this->SkipTypeCast)) {
				return $this->SkipTypeCast[$strProperty];
			}
			return $this->defaultSkipTypeCast;
		}

		/**
		 * string comparison mode for the property
		 * @param string $strProperty name of property
		 * @return string
		 */
		public function GetStringComparisonMode($strProperty) {
			if (array_key_exists($strProperty, $this->StringComparisonMode)) {
				return $this->StringComparisonMode[$strProperty];
			}
			return $this->defaultStringComparisonMode;
		}

		/**
		 * string comparison mode for the property
		 * @param string $strProperty name of property
		 * @return string
		 */
		public function GetDateComparisonMode($strProperty) {
			if (array_key_exists($strProperty, $this->DateComparisonMode)) {
				return $this->DateComparisonMode[$strProperty];
			}
			return $this->defaultDateComparisonMode;
		}

		/**
		 * true if the property should be excluded from search
		 * @param string $strProperty name of property
		 * @param string $strType the type of the property: one of QType consts or a class name
		 * @return bool
		 */
		public function IsPropertyExcluded($strProperty, $strType) {
			if ($this->ExcludeProperties === 'all')
				return true;
			if ($this->ExcludeProperties && in_array($strProperty, $this->ExcludeProperties))
				return true;
			if ($strType == QType::Boolean) {
				if (array_key_exists($strProperty, $this->SkipBoolean)) {
					return $this->SkipBoolean[$strProperty];
				}
				return $this->defaultSkipBooleans;
			}
			if ($strType == QType::DateTime) {
				if (array_key_exists($strProperty, $this->SkipDate)) {
					return $this->SkipDate[$strProperty];
				}
				return $this->defaultSkipDates;
			}
			if ($strType == QType::Integer || $strType == QType::Float) {
				if (array_key_exists($strProperty, $this->SkipNumber)) {
					return $this->SkipNumber[$strProperty];
				}
				return $this->defaultSkipNumbers;
			}
			return false;
		}

		/**
		 * add a property to the exclusion list
		 * @param string $strProperty name of property
		 */
		public function ExcludeProperty($strProperty) {
			if ($this->ExcludeProperties === 'all')
				return;
			if (is_null($this->ExcludeProperties))
				$this->ExcludeProperties = array();
			$this->ExcludeProperties[] = $strProperty;
		}

		/**
		 * @return array
		 */
		public function GetExcludeProperties() {
			return $this->ExcludeProperties;
		}

		/**
		 * @return array
		 */
		public function GetExtraProperties() {
			return $this->ExtraProperties;
		}

		/**
		 * @param QQBaseNode $objBaseNode
		 * @param SearchNode[] $objSearchNodes
		 * @return QQCondition
		 */
		public function SearchConditionForNodes($objBaseNode, $objSearchNodes) {
			if (!$objSearchNodes) {
				return QQ::All();
			}
			$objConditions = array();
			foreach ($objSearchNodes as $objSearchNode) {
				$objCondition = $objSearchNode->MakeCondition($objBaseNode, $this);
				if (!$objCondition)
					continue;
				$objConditions[] = $objCondition;
			}

			if (!$objConditions)
				return QQ::All();
			return $this->IsMatchAllTerms() ? QQ::AndCondition($objConditions) : QQ::OrCondition($objConditions);
		}

		/**
		 * @param QQBaseNode $objBaseNode
		 * @param string|string[] $objTerms
		 * @param array $propTypes
		 * @return QQCondition
		 */
		public function SearchCondition($objBaseNode, $objTerms, $propTypes = array()) {
			if (!$objTerms) {
				return QQ::All();
			}
			if (is_string($objTerms)) {
				preg_match_all('/"(?:\\\\.|[^\\\\"])*"|\S+/', $objTerms, $matches);
				$objTerms = $matches[0];
			}
			$propTypes = array_merge($propTypes, $this->GetExtraProperties());
			$nodes = array();
			foreach ($propTypes as $strProp => $strType) {
				if ($this->IsPropertyExcluded($strProp, $strType) && !array_key_exists($strProp, $this->GetExtraProperties()))
					continue;
				$nodes[$strProp] = SearchNode::GetNestedNode($objBaseNode, explode('->', $strProp));
			}
			$objConditions = array();
			foreach ($objTerms as $strTerm) {
				if (is_string($strTerm) && $this->IsTrimQuotes() && $strTerm{0} == '"' && $strTerm{strlen($strTerm)-1} == '"') {
					$strTerm = substr($strTerm, 1, strlen($strTerm)-2);
				}
				$orConds = array();
				foreach ($nodes as $strProp => $objNode) {
					$strType = $propTypes[$strProp];
					$cond = $this->MakeNodeCondition($objNode, $strProp, $strType, $strTerm);
					if (!$cond)
						continue;
					$orConds[] = $cond;
				}
				$objConditions[] = QQ::OrCondition($orConds);
			}
			return $this->IsMatchAllTerms() ? QQ::AndCondition($objConditions) : QQ::OrCondition($objConditions);
		}

		public function MakeNodeCondition($objNode, $strProp, $strType, $objTerm) {
			$blnRange = $this->GetRange($objTerm, $objTermLeft, $objTermRight);
			$blnSkipTypeCast = $this->IsSkipTypeCast($strProp);
			if (!$blnSkipTypeCast && $strType !== QType::DateTime) {
				try {
					if ($blnRange) {
						$objTermLeft = QType::Cast($objTermLeft, $strType);
						$objTermRight = QType::Cast($objTermRight, $strType);
					} else {
						$objTerm = QType::Cast($objTerm, $strType);
					}
				} catch (QInvalidCastException $ex) {
					if ($this->IsSkipWrongTypes())
						return null;
				}
			}
			if ($strType === QType::String) {
				$strStringComparisonMode = $this->GetStringComparisonMode($strProp);
				switch ($strStringComparisonMode) {
					case QStringComparisonMode::exact:
						return QQ::Equal($objNode, $objTerm);
					case QStringComparisonMode::startsWith:
						return QQ::Like($objNode, $objTerm.'%');
					case QStringComparisonMode::endsWith:
						return QQ::Like($objNode, '%'.$objTerm);
					case QStringComparisonMode::like:
						return QQ::Like($objNode, $objTerm);
					case QStringComparisonMode::contains:
						return QQ::Like($objNode, '%'.$objTerm.'%');
					default:
						throw new QCallerException('When creating condition for Customer::'.$strProp.' unhandled value ['.$strStringComparisonMode.'] for option StringComparisonMode');
				}
			}
			if ($strType === QType::DateTime) {
				if ($blnSkipTypeCast) {
					// when not casting, we can't accomodate for the date comparison mode
					if ($blnRange) {
						return $this->makeRangeCondition($objNode, $objTermLeft, $objTermRight);
					}
					return QQ::Equal($objNode, $objTerm);
				}
				$strDateComparisonMode = $this->GetDateComparisonMode($strProp);
				if ($blnRange) {
					try {
						$dttFrom = null;
						$dttTo = null;
						if ($objTermLeft) {
							$dtt = new QDateTime($objTermLeft);
							$this->GetDateBounds($strProp, $dtt, $dttTo);
							$dttFrom = $dtt;
						}
						if ($objTermRight) {
							$dtt = new QDateTime($objTermRight);
							$this->GetDateBounds($strProp, $dtt, $dttTo);
						}

						return $this->makeRangeCondition($objNode, $dttFrom, $dttTo, false, $strDateComparisonMode != QDateComparisonMode::exact);
					} catch (Exception $ex) {
						if ($this->IsSkipWrongTypes())
							return null;
						// conversion failed, but not skiped; we can only do exact comparisons
						return $this->makeRangeCondition($objNode, $objTermLeft, $objTermRight);
					}
				} else {
					try {
						$dttFrom = new QDateTime($objTerm);
						$this->GetDateBounds($strProp, $dttFrom, $dttTo);
						return $this->makeRangeCondition($objNode, $dttFrom, $dttTo, false, $strDateComparisonMode != QDateComparisonMode::exact);
					} catch (Exception $ex) {
						if ($this->IsSkipWrongTypes())
							return null;
						// conversion failed, but not skiped; we can only do exact comparisons
						return QQ::Equal($objNode, $objTerm);
					}
				}
			}
			if ($blnRange) {
				if ($strType == QType::Integer) {
					return $this->makeRangeCondition($objNode, $objTermLeft, $objTermRight);
				} else if ($strType == QType::Float) {
					return $this->makeRangeCondition($objNode, $objTermLeft, $objTermRight, false, true);
				}
			}
			return QQ::Equal($objNode, $objTerm);
		}

		protected function makeRangeCondition($objNode, $objTermLeft, $objTermRight, $blnLeftOpen = false, $blnRightOpen = false) {
			$conds = array();
			if ($objTermLeft) {
				$conds[] = $blnLeftOpen ? QQ::GreaterThan($objNode, $objTermLeft) : QQ::GreaterOrEqual($objNode, $objTermLeft);
			}
			if ($objTermRight) {
				$conds[] = $blnRightOpen ? QQ::LessThan($objNode, $objTermRight) : QQ::LessOrEqual($objNode, $objTermRight);
			}
			return QQ::AndCondition($conds);
		}

		/**
		 * @param string $strProp
		 * @param QDateTime $dttFrom
		 * @param QDateTime|null $dttTo
		 * @throws QCallerException
		 */
		protected function GetDateBounds($strProp, &$dttFrom, &$dttTo) {
			$strDateComparisonMode = $this->GetDateComparisonMode($strProp);
			switch ($strDateComparisonMode) {
				case QDateComparisonMode::withinSecond:
					if ($dttFrom->IsTimeNull()) {
						$dttFrom->SetTime(0, 0, 0);
					}
					$dttTo = new QDateTime($dttFrom);
					$dttTo->AddSeconds(1);
					break;
				case QDateComparisonMode::withinMinute:
					if ($dttFrom->IsTimeNull()) {
						$dttFrom->SetTime(0, 0, 0);
					} else {
						$dttFrom->Second = 0;
					}
					$dttTo = new QDateTime($dttFrom);
					$dttTo->AddMinutes(1);
					break;
				case QDateComparisonMode::withinHour:
					if ($dttFrom->IsTimeNull()) {
						$dttFrom->SetTime(0, 0, 0);
					} else {
						$dttFrom->Second = 0;
						$dttFrom->Minute = 0;
					}
					$dttTo = new QDateTime($dttFrom);
					$dttTo->AddHours(1);
					break;
				case QDateComparisonMode::withinDay:
					if ($dttFrom->IsDateNull()) {
						$dttFrom->SetDate(2000, 1, 1);
					}
					$dttFrom->SetTime(0, 0, 0);
					$dttTo = new QDateTime($dttFrom);
					$dttTo->AddDays(1);
					break;
				case QDateComparisonMode::withinMonth:
					if ($dttFrom->IsDateNull()) {
						$dttFrom->SetDate(2000, 1, 1);
					} else {
						$dttFrom->Day = 1;
					}
					$dttFrom->SetTime(0, 0, 0);
					$dttTo = new QDateTime($dttFrom);
					$dttTo->AddMonths(1);
					break;
				case QDateComparisonMode::withinYear:
					if ($dttFrom->IsDateNull()) {
						$dttFrom->SetDate(2000, 1, 1);
					} else {
						$dttFrom->Day = 1;
						$dttFrom->Month = 1;
					}
					$dttFrom->SetTime(0, 0, 0);
					$dttTo = new QDateTime($dttFrom);
					$dttTo->AddYears(1);
					break;
				default:
					throw new QCallerException('When creating condition for Customer::'.$strProp.' unhandled value ['.$strDateComparisonMode.'] for option DateComparisonMode');
			}
		}

		private function GetRange($objTerm, &$objTermLeft, &$objTermRight) {
			$blnRange = is_array($objTerm);
			$objTermLeft = null;
			$objTermRight = null;
			if ($blnRange) {
				$objTermLeft = count($objTerm) > 0 ? $objTerm[0] : null;
				$objTermRight = count($objTerm) > 1 ? $objTerm[1] : null;
			}
			return $blnRange;
		}

	}
