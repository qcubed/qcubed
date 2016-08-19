<?php
	/**
	 * This file contains the QPaginatedControl and QDataBindException class.
	 *
	 * @package Controls
	 */

	/**
	 * @package Controls
	 * @property string         $Noun Name of the items which are being paginated (book, movie, post etc.)
	 * @property string         $NounPlural Plural form of name of the items which are being paginated (books, movies, posts etc.)
	 * @property QPaginatorBase $Paginator
	 * @property QPaginatorBase $PaginatorAlternate
	 * @property boolean        $UseAjax
	 * @property integer        $ItemsPerPage   is how many items you want to display per page when Pagination is enabled
	 * @property integer        $TotalItemCount is the total number of items in the ENTIRE recordset -- only used when Pagination is enabled
	 * @property mixed          $DataSource     is an array of anything.  THIS MUST BE SET EVERY TIME (DataSource does NOT persist from postback to postback
	 * @property-read mixed     $LimitClause
	 * @property-read mixed     $LimitInfo      is what should be passed in to the LIMIT clause of the sql query that retrieves the array of items from the database
	 * @property-read integer   $ItemCount
	 * @property integer        $PageNumber     is the current page number you are viewing
	 * @property-read integer   $PageCount
	 * @property-read integer   $ItemsOffset    Current offset of Items from the result
	 */
	abstract class QPaginatedControl extends QControl {
		use QDataBinder;

		// APPEARANCE
		/** @var string Name of the items which are being paginated (books, movies, posts etc.) */
		protected $strNoun;
		/**  @var string Plural form of name of the items which are being paginated (books, movies, posts etc.) */
		protected $strNounPlural;

		// BEHAVIOR
		/** @var null|QPaginator Paginator at the top */
		protected $objPaginator = null;
		/** @var null|QPaginator Paginator at the bottom */
		protected $objPaginatorAlternate = null;
		/** @var bool Determines whether this QDataGrid wll use AJAX or not */
		protected $blnUseAjax = true;

		// MISC
		/** @var array DataSource from which the items are picked and rendered */
		protected $objDataSource;

		// SETUP
		/** @var bool Is this paginator a block element? */
		protected $blnIsBlockElement = true;

		/**
		 * @param QControl|QControlBase|QForm $objParentObject
		 * @param null|string                       $strControlId
		 *
		 * @throws Exception
		 * @throws QCallerException
		 */
		public function __construct($objParentObject, $strControlId = null) {
			parent::__construct($objParentObject, $strControlId);

			$this->strNoun = QApplication::Translate('item');
			$this->strNounPlural = QApplication::Translate('items');
		}

		// PaginatedControls should (in general) never have anything that ever needs to be validated -- so this always
		// returns true.
		public function Validate() {
			return true;
		}

		public function DataBind() {
			// Run the DataBinder (if applicable)
			if (($this->objDataSource === null) && ($this->HasDataBinder()) && (!$this->blnRendered))
			{
				try {
					$this->CallDataBinder();
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
				
				if ($this->objPaginator && $this->PageNumber > $this->PageCount) {
					$this->PageNumber = max($this->PageCount,1);
				}
			}
		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		/**
		 * PHP magic method
		 * @param string $strName Property name
		 *
		 * @return mixed
		 * @throws Exception|QCallerException
		 */
		public function __get($strName) {
			switch ($strName) {
				// APPEARANCE
				case "Noun": return $this->strNoun;
				case "NounPlural": return $this->strNounPlural;

				// BEHAVIOR
				case "Paginator": return $this->objPaginator;
				case "PaginatorAlternate": return $this->objPaginatorAlternate;
				case "UseAjax": return $this->blnUseAjax;
				case "ItemsPerPage":
					if ($this->objPaginator)
						return $this->objPaginator->ItemsPerPage;
					else
						return null;
				case "ItemsOffset":
					if ($this->objPaginator)
						return ($this->objPaginator->PageNumber - 1) * $this->objPaginator->ItemsPerPage;
					else
						return null;
				case "TotalItemCount":
					if ($this->objPaginator)
						return $this->objPaginator->TotalItemCount;
					else
						return null;

				// MISC
				case "DataSource": return $this->objDataSource;
				case "LimitClause":
					if ($this->objPaginator) {
//						if ($this->objPaginator->TotalItemCount > 0) {
							$intOffset = $this->ItemsOffset;
							return QQ::LimitInfo($this->objPaginator->ItemsPerPage, $intOffset);
//						}
					}
					return null;
				case "LimitInfo":
					if ($this->objPaginator) {
//						if ($this->objPaginator->TotalItemCount > 0) {
							$intOffset = $this->ItemsOffset;
							return $intOffset . ',' . $this->objPaginator->ItemsPerPage;
//						}
					}
					return null;
				case "ItemCount": return count($this->objDataSource);

				case 'PageNumber':
					if ($this->objPaginator)
						return $this->objPaginator->PageNumber;
					else
						return null;

				case 'PageCount':
					if ($this->objPaginator)
						return $this->objPaginator->PageCount;
					else
						return null;

				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}





		/////////////////////////
		// Public Properties: SET
		/////////////////////////
		public function __set($strName, $mixValue) {
			switch ($strName) {
				// APPEARANCE
				case "Noun":
					try {
						$this->strNoun = QType::Cast($mixValue, QType::String);
						$this->blnModified = true;
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "NounPlural":
					try {
						$this->strNounPlural = QType::Cast($mixValue, QType::String);
						$this->blnModified = true;
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				// BEHAVIOR
				case "Paginator":
					try {
						$this->objPaginator = QType::Cast($mixValue, 'QPaginatorBase');
						if ($this->objPaginator) {
							if ($this->objPaginator->Form->FormId != $this->Form->FormId)
								throw new QCallerException('The assigned paginator must belong to the same form that this control belongs to.');
							$this->objPaginator->SetPaginatedControl($this);
						}
						$this->blnModified = true;
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "PaginatorAlternate":
					try {
						$this->objPaginatorAlternate = QType::Cast($mixValue, 'QPaginatorBase');
						if ($this->objPaginatorAlternate->Form->FormId != $this->Form->FormId)
							throw new QCallerException('The assigned paginator must belong to the same form that this control belongs to.');
						$this->objPaginatorAlternate->SetPaginatedControl($this);
						$this->blnModified = true;
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "UseAjax":
					try {
						$this->blnUseAjax = QType::Cast($mixValue, QType::Boolean);
						
						if ($this->objPaginator)
							$this->objPaginator->UseAjax = $this->blnUseAjax;
						if ($this->objPaginatorAlternate)
							$this->objPaginatorAlternate->UseAjax = $this->blnUseAjax;

						$this->blnModified = true;
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ItemsPerPage":
					if ($this->objPaginator) {
						try {
							$intItemsPerPage = QType::Cast($mixValue, QType::Integer);
							$this->objPaginator->ItemsPerPage = $intItemsPerPage;

							if ($this->objPaginatorAlternate) {
								$this->objPaginatorAlternate->ItemsPerPage = $intItemsPerPage;
							}

							$this->blnModified = true;
							break;
						} catch (QCallerException $objExc) {
							$objExc->IncrementOffset();
							throw $objExc;
						}
					} else
						throw new QCallerException('Setting ItemsPerPage requires a Paginator to be set');
				case "TotalItemCount":
					if ($this->objPaginator) {
						try {
							$intTotalCount = QType::Cast($mixValue, QType::Integer);
							$this->objPaginator->TotalItemCount = $intTotalCount;

							if ($this->objPaginatorAlternate) {
								$this->objPaginatorAlternate->TotalItemCount = $intTotalCount;
							}

							$this->blnModified = true;
							break;
						} catch (QCallerException $objExc) {
							$objExc->IncrementOffset();
							throw $objExc;
						}
					} else
						throw new QCallerException('Setting TotalItemCount requires a Paginator to be set');

				// MISC
				case "DataSource": 
					$this->objDataSource = $mixValue;
					$this->blnModified = true;
					break;

				case "PageNumber":
					if ($this->objPaginator) {
						try {
							$intPageNumber = QType::Cast($mixValue, QType::Integer);
							$this->objPaginator->PageNumber = $intPageNumber;

							if ($this->objPaginatorAlternate) {
								$this->objPaginatorAlternate->PageNumber = $intPageNumber;
							}
							$this->blnModified = true;
							break;
						} catch (QCallerException $objExc) {
							$objExc->IncrementOffset();
							throw $objExc;
						}
					} else
						throw new QCallerException('Setting PageNumber requires a Paginator to be set');

				default:
					try {
						parent::__set($strName, $mixValue);
						break;
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
	}