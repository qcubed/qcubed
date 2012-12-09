<?php
	class QQN {
		/**
		 * @return QQNodeComment
		 */
		static public function Comment() {
			return new QQNodeComment('comment', null, null);
		}
		/**
		 * @return QQNodePost
		 */
		static public function Post() {
			return new QQNodePost('post', null, null);
		}
	}
?>