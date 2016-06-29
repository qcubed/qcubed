<?php
	
	/**
	 * Class QPgQConditionILike: For performing case insensitive search in PostgreSQL
	 */
	class QPgQConditionILike extends QQConditionComparison {
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
	 * Class QPgQJsonContains: For performing '@>' JSON contains search in JSONB documents
	 */
	class QPgQJsonContains extends QQConditionComparison {
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
	 * Class QPgQ: PostgreSQL specific querying which is not covered in QCubed base (QQuery)
	 */
	class QPgQ extends QQ {
		/**
		 * @param QQColumnNode $objQueryNode
		 * @param string       $strValue
		 *
		 * @return QPgQConditionILike
		 */
		static public function ILike(QQColumnNode $objQueryNode, $strValue) {
			return new QPgQConditionILike($objQueryNode, $strValue);
		}
		
		/**
		 * @param QQColumnNode $objQueryNode
		 * @param string       $strValue
		 *
		 * @return QPgQConditionILike
		 */
		static public function JsonContains(QQColumnNode $objQueryNode, $strValue) {
			return new QPgQJsonContains($objQueryNode, $strValue);
		}
	}