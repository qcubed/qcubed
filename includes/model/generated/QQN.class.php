<?php
	class QQN {
		/**
		 * @return QQNodeAddress
		 */
		static public function Address() {
			return new QQNodeAddress('address', null, null);
		}
		/**
		 * @return QQNodeLogin
		 */
		static public function Login() {
			return new QQNodeLogin('login', null, null);
		}
		/**
		 * @return QQNodeMilestone
		 */
		static public function Milestone() {
			return new QQNodeMilestone('milestone', null, null);
		}
		/**
		 * @return QQNodePerson
		 */
		static public function Person() {
			return new QQNodePerson('person', null, null);
		}
		/**
		 * @return QQNodePersonWithLock
		 */
		static public function PersonWithLock() {
			return new QQNodePersonWithLock('person_with_lock', null, null);
		}
		/**
		 * @return QQNodeProject
		 */
		static public function Project() {
			return new QQNodeProject('project', null, null);
		}
	}
?>