<?php
	
	/**
	 * Class QPgQConditionILike: For performing case insensitive search in PostgreSQL
	 */
	class QPgConditionILike extends QQConditionComparison {
		/**
		 * @param QQColumnNode $objQueryNode
		 * @param string       $strValue
		 *
		 * @throws Exception
		 * @throws QCallerException
		 * @throws QInvalidCastException
		 */
		public function __construct(QQColumnNode $objQueryNode, $strValue) {
			parent::__construct($objQueryNode);
			
			if ($strValue instanceof QQNamedValue) {
				$this->mixOperand = $strValue;
			} else {
				try {
					$this->mixOperand = QType::Cast($strValue, QType::String);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					$objExc->IncrementOffset();
					throw $objExc;
				}
			}
		}
		
		/**
		 * @param QQueryBuilder $objBuilder
		 */
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			$mixOperand = $this->mixOperand;
			if ($mixOperand instanceof QQNamedValue) {
				/** @var QQNamedValue $mixOperand */
				$objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' ILIKE ' . $mixOperand->Parameter());
			} else {
				$objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' ILIKE ' . $objBuilder->Database->SqlVariable($mixOperand));
			}
		}
	}
	
	/**
	 * Class QPgConditionJsonContainsValue: For performing '@>' JSON contains search in JSONB documents
	 *
	 */
	class QPgConditionJsonContains extends QQConditionComparison {
		/**
		 * @param QQColumnNode $objQueryNode
		 * @param string       $strValue
		 *
		 * @throws Exception
		 * @throws QCallerException
		 * @throws QInvalidCastException
		 */
		public function __construct(QQColumnNode $objQueryNode, $strValue) {
			parent::__construct($objQueryNode);
			
			if ($strValue instanceof QQNamedValue) {
				$this->mixOperand = $strValue;
			} else {
				try {
					$this->mixOperand = QType::Cast($strValue, QType::String);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					$objExc->IncrementOffset();
					throw $objExc;
				}
			}
		}
		
		/**
		 * @param QQueryBuilder $objBuilder
		 */
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			$mixOperand = $this->mixOperand;
			if ($mixOperand instanceof QQNamedValue) {
				/** @var QQNamedValue $mixOperand */
				$objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' @> ' . $mixOperand->Parameter());
			} else {
				$objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' @> ' . $objBuilder->Database->SqlVariable($mixOperand));
			}
		}
	}
	

	/**
	 * Class QPgQ: PostgreSQL specific querying which is not covered in QCubed base
	 */
	class QPgQ extends QQ {
		/**
		 * Case Insensitive search
		 *
		 * @param QQColumnNode $objQueryNode
		 * @param string       $strValue
		 *
		 * @return QPgQConditionILike
		 */
		static public function ILike(QQColumnNode $objQueryNode, $strValue) {
			return new QPgConditionILike($objQueryNode, $strValue);
		}
		
		/**
		 * Left hand operand contains the right hand operand
		 *
		 * @param QQColumnNode $objQueryNode
		 * @param string       $strValue
		 *
		 * @return QPgQConditionJsonbContainsValue
		 */
		static public function JsonContains(QQColumnNode $objQueryNode, $strValue) {
			return new QPgConditionJsonContains($objQueryNode, $strValue);
		}
	}