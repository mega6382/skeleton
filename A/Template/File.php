<?php
/**
 * File.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Template_File
 *
 * Base Template class for templates that are read as files
 *
 * @package A_Template
 */
class A_Template_File extends A_Template_Base
{

	protected $blocks = array();
	protected $blockprefix = '<!--{';
	protected $blocksuffix = '}-->';
	protected $auto_blocks;

	public function __construct($filename='', $data=array(), $auto_blocks=false)
	{
		parent::__construct($filename, $data);
		$this->auto_blocks = $auto_blocks;
	}

	public function readFile($filename)
	{
		return file_get_contents($filename);
	}

	public function setTemplate($template)
	{
	    $this->template = $template;
	    $this->blocks = array();
		return $this;
	}

	public function setFilename($filename)
	{
	    $this->filename = $filename;
	    $this->blocks = array();
		return $this;
	}

	public function loadTemplate()
	{
		if ($this->template) {
			$this->blocks[''] = $this->template;
		} elseif (! isset($this->blocks['']) || ! $this->blocks['']) {
			$this->blocks[''] = $this->readFile($this->filename);
		}
		return $this;
	}

	public function makeBlocks($prefix='', $suffix='')
	{
	   	$this->loadTemplate();
		if (!$prefix) {
			$prefix = $this->blockprefix;
		}
		if (strpos($this->blocks[''], $prefix) !== false) {
			if (!$suffix) {
				$suffix = $this->blocksuffix;
			}
			$arr = array();
			$blocks = explode($prefix, $this->blocks['']);
			foreach ($blocks as $str) {
				if ($str) {
					list($key, $val) = explode($suffix, $str);
					if ($key != '') {
						$this->blocks[$key] = $val;
					}
				}
			}
		}
		return $this;
	}

	public function hasBlock($block=null)
	{
	   	if ($block) {
			return isset($this->blocks[$block]);
	   	} else {
			return count($this->blocks) > 1;
	   	}
	}

}
