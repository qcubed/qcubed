
		/**
		 * Lookup a VirtualAttribute value (if applicable).  Returns NULL if none found.
		 * @param string $strName
		 * @return string|null
		 */
		public function GetVirtualAttribute($strName) {
			$strName = QQ::GetVirtualAlias($strName);
			if (isset($this->__strVirtualAttributeArray[$strName])) {
				return $this->__strVirtualAttributeArray[$strName];
			}
			return null;
		}

		/**
		 * Returns true if a virtual attribute exists. Useful for telling that the attribute exists, but is null.
		 * @param string $strName
		 * @return boolean
		 */
		public function HasVirtualAttribute($strName) {
			$strName = QQ::GetVirtualAlias($strName);
			if (array_key_exists($strName, $this->__strVirtualAttributeArray)) {
				return true;
			}
			return false;
		}
