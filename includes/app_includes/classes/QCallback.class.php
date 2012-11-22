<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * User: vakopian
	 * Date: 3/31/12
	 * Time: 8:45 AM
	 */
	class QCallback {
		protected $objFunction;
		protected $args;

		public function __construct($objFunction, array $args = null) {
			$this->objFunction = func_get_arg(0);
			$this->args = $args;
		}

		/**
		 * Execute this callback
		 * @return mixed the callback result or false if error
		 */
		public function Call(/* $arg1, $arg2, ...*/) {
			if (func_num_args() == 0) {
				return $this->CallArray($this->args);
			} else {
				return $this->CallArray(func_get_args());
			}
		}

		protected function CallArray(array $args = null) {
			if (is_null($args))
				return call_user_func($this->objFunction);
			return call_user_func_array($this->objFunction, $args);
		}
	}

	abstract class QProxyCallback extends QCallback {
		/** @var null|\QCallback */
		protected $objTargetCallback;

		public function __construct(QCallback $objTargetCallback = null) {
			$this->objTargetCallback = $objTargetCallback;
		}

		abstract protected function doBefore();
		abstract protected function doAfter(&$objTargetCallbackResult);

		protected function CallArray(array $arg = null) {
			$this->doBefore();
			$res = null;
			if ($this->objTargetCallback) {
				$this->objTargetCallback->CallArray($arg);
			}
			$this->doAfter($res);
			return $res;
		}
	}

	class QSimpleCallback extends QCallback {
		public function __construct($objFunction /*, $arg1, $arg2, ... */) {
			parent::__construct(func_get_arg(0), array_slice(func_get_args(), 1));
		}
	}

	class QLambdaCallback extends QCallback {
		public function __construct($strArgs, $strBody /*, $arg1, $arg2, ... */) {
			parent::__construct(create_function($strArgs, $strBody), array_slice(func_get_args(), 2));
		}
	}

	class QClosureCallback extends QCallback {
		public function __construct($objClosure) {
			parent::__construct($objClosure);
		}
	}

	class QMethodCallback extends QCallback {
		public function __construct($objObject, $strMethod /*, $arg1, $arg2, ... */) {
			parent::__construct(array($objObject, $strMethod), array_slice(func_get_args(), 2));
		}
	}

	class DialogClosingCallback extends QProxyCallback {
		/** @var \QDialog */
		protected $objDialog;

		public function __construct(QDialog $objDialog, QCallback $objTargetCallback = null) {
			parent::__construct($objTargetCallback);
			$this->objDialog = $objDialog;
		}

		protected function doBefore() {
			$this->objDialog->RemoveChildControls(true);
			$this->objDialog->HideDialogBox();
		}

		protected function doAfter(&$objTargetCallbackResult) {
		}
	}

