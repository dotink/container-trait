<?php namespace Dotink\Traits
{
	use Dotink\Flourish;

	/**
	 * The container trait, a nice way to abstract dependencies
	 *
	 * @copyright Copyright (c) 2012, Matthew J. Sahagian
	 * @author Matthew J. Sahagian [mjs] <msahagian@dotink.org>
	 *
	 * @license Please reference the LICENSE.txt file at the root of this distribution
	 *
	 * @package Dotink\Traits
	 * @uses Dotink\Flourish\ProgrammerException
	 */
	trait Container
	{
		/**
		 * The elements contained in this instance
		 *
		 * @access private
		 * @var array
		 */
		private $elements = array();


		/**
		 * Sets a peer element via array access (NOT ALLOWED)
		 *
		 * @access public
		 * @param mixed $offset The element offset to set
		 * @param mixed $value The value to set for the offset
		 * @return void
		 */
		final public function offsetSet($offset, $value)
		{
			$this->element[$offset] = $value;
		}


		/**
		 * Checks whether or not a element exists
		 *
		 * @access public
		 * @param mixed $offset The element offset to check for existence
		 * @return boolean TRUE if the peer exists, FALSE otherwise
		 */
		final public function offsetExists($offset)
		{
			return isset($this->elements[$offset]);
		}


		/**
		 * Attempts to unset a element (NOT ALLOWED)
		 *
		 * @access public
		 * @param mixed $offset The element offset to unset
		 * @return void
		 */
		final public function offsetUnset($offset)
		{
			if (!$this->offsetExists($offset)) {
				throw new Flourish\ProgrammerException(
					'Element "%s" not set on %s',
					$offset,
					get_class($this)
				);
			}

			unset($this->element[$offset]);
		}


		/**
		 * Gets an element
		 *
		 * @access public
		 * @param mixed $offset The element offset to get
		 * @return void
		 */
		final public function offsetGet($offset)
		{
			if (!$this->offsetExists($offset)) {
				throw new Flourish\ProgrammerException(
					'Element "%s" not set on %s',
					$offset,
					get_class($this)
				);
			}

			return $this->elements[$offset];
		}


		final public function setContext(Array $context)
		{
			$this->elements = $context;

			return $this;
		}
	}
}

