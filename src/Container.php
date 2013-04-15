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
		 * Registered prepare hooks
		 *
		 * @static
		 * @access private
		 * @var array
		 */
		static private $prepareHooks = array();


		/**
		 * The elements contained in this instance
		 *
		 * @access private
		 * @var array
		 */
		private $elements = array();


		/**
		 * Register a prepare hook for a particular class
		 *
		 * The callback will be provided two parameters, the first is the instance of the object
		 * to be prepared, the second is an array which can be received as a reference
		 *
		 * @final
		 * @static
		 * @access protected
		 * @param string $class The class to register the prepare hook for
		 * @param callable $callback The callback to run
		 * @return void
		 */
		final static protected function prepare($class, $callback = NULL)
		{
			if (func_num_args() == 1) {
				$callback = func_get_args(0);
				$class    = get_called_class();
			}

			if (!is_callable($callback)) {
				throw new Flourish\ProgrammerException(
					'Cannot call prepare with non-callable entity'
				);
			}

			self::$prepareHooks[$class] = $callback;
		}


		/**
		 * The constructor for containers is final.
		 *
		 * The initialization logic of the class should be extended via prepare hooks
		 * which are much more flexible.
		 *
		 * @final
		 * @access public
		 * @param array $elements An list of elements for the container to hold
		 * @return void
		 */
		final public function __construct(Array $elements)
		{
			$this->elements = $elements;

			foreach (self::$prepareHooks as $class => $callback) {
				if ($this instanceof $class && is_callable($callback)) {
					if ($callback instanceof \Closure) {
						$callback($this);
					} else {
						call_user_func($callback, $this);
					}
				}
			}
		}


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
	}
}
