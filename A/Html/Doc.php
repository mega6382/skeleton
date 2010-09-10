<?php
/**
 * Generate HTML document
 *
 * @package A_Html
 */

class A_Html_Doc {
	const HTML_4_01_STRICT = 1;
	const HTML_4_01_TRANSITIONAL = 2;
	const HTML_4_01_FRAMESET = 3;
	const XHTML_1_0_STRICT = 4;
	const XHTML_1_0_TRANSITIONAL = 5;	
	const XHTML_1_0_FRAMESET = 6;
	const XHTML_1_1 = 7; 
	const HTML_5 = 8; 
	
	protected $_config = array(
					'doctype' => '',
					'title' => '',
					'base' => '',
					'style_links' => '',
					'rss_links' => '',
					'script_links' => '',
					'metadata' => '',
					'body_attr' => '',
					); 
	protected $_config_is_string = array(
					'title' => 1,
					'doctype' => 1,
					'base' => 1,
					);
	protected $_style_links = array();
	protected $_styles = array();
	protected $_rss_links = array();
	protected $_script_links = array();
	protected $_scripts = array();
	protected $_metadata = array();
	protected $_body_attr = array();
	protected $_body = '';
	
	/*
	* name=string, value=string or renderer
	*/
	public function renderDoctype($doctype=null) {
		$doctypes = array(
			self::HTML_5 => '<!DOCTYPE html>',
			self::HTML_4_01_STRICT => "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\"\n\"http://www.w3.org/TR/html4/strict.dtd\">",
			self::HTML_4_01_TRANSITIONAL => "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"\n\"http://www.w3.org/TR/html4/loose.dtd\">",
			self::HTML_4_01_FRAMESET => "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Frameset//EN\"\n\"http://www.w3.org/TR/html4/frameset.dtd\">",
			self::XHTML_1_0_STRICT => "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\"\n\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">",
			self::XHTML_1_0_TRANSITIONAL => "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"\n\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">",	
			self::XHTML_1_0_FRAMESET => "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Frameset//EN\"\n\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd\">",
			self::XHTML_1_1 => "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\"\n\"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">",
			);
		if ($doctype === null) {
			$doctype = $this->_config['doctype'];
		}
		if (! isset($doctypes[$doctype])) {
			$doctype = self::HTML_4_01_TRANSITIONAL;
		}
		return $doctypes[$doctype] . "\n";
	}
	
	public function __call($name, $value) {
		$mode = substr($name, 0, 3);
		$name = substr($name, 3);
		switch ($mode) {
		case 'set':
			if (isset($this->_config[$name])) {
				if (!isset($this->_config_is_string[$name]) && !is_array($value)) {
					break;
				}
				$this->_config[$name] = $value;
			}
		break;
		case 'get':
			if (isset($this->_config[$name])) {
				return $this->_config[$name];
			}
			break;
		}
		return $this;
	}

	public function setTitle($title) {
		$this->_config['title'] = $title;
		return $this;
	}

	public function setBase($url) {
		$this->_config['base'] = $url;
		return $this;
	}

	public function getTitle() {
		return $this->_config['title'];
	}

	public function addScript($script, $media='all', $label='') {
		if ($filename) {
			$this->scripts[] = array('label'=>$label, 'filename'=>'', 'script'=>$script, 'media'=>$media);
		}
		return $this;
	}
	 
	/**
	 * http://www.w3schools.com/tags/tag_link.asp
	 * @param $filename
	 * @param $media
	 * @param $label
	 * @return unknown_type
	 */
	public function addLink($type='stylesheet', $href, $media='all', $label='') {
		if ($href) {
			$this->scripts[] = array('rel'=>$type, 'label'=>$label, 'href'=>$href, 'media'=>$media);
		}
		return $this;
	}
	
	/**
	 * 
http-equiv:
content-type
content-style-type
expires
refresh
set-cookie

name:
author
description
keywords
generator
revised

scheme:
format/URI
	 */
	public function addMeta($httpequiv, $content, $value='') {
		if ($content) {
			$this->meta[] = array('httpequiv'=>$httpequiv, 'content'=>$content, 'value'=>$value);
		}
		return $this;
	}
	
	public function ifIE($logic) {
		if ($logic) {
			$this->ifIElogic = $logic;
		}
		return $this;
	}
	
	public function before($label) {
		if ($label) {
			$this->beforeLabel= $label;
		}
		return $this;
	}
	
	public function after($label) {
		if ($label) {
			$this->afterLabel= $label;
		}
		return $this;
	}

	/**
	 * Rendering methods
	 */
	
	public function renderTitle() {
		$str = '';
		return "<title>{$this->_config['title']}</title>\n";
	}

	public function renderStyleLinks() {
		$str = '';
		if (is_array($this->_config['style_links'])) {
			foreach ($this->_config['style_links'] as $style) {
				$str .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"{$style['url']}\" media=\"{$style['media']}\"//>\n";
			}		
		}
	}

	public function renderStyles() {
		$str = '';
		if (is_array($this->_config['style_links'])) {
			foreach ($this->styles as $style) {
				$str .= "<style type=\"text/css\" media=\"{$style['media']}/>\n{$style['url']}\n</script>\n";
			}
		}
	}

	public function renderScriptLinks() {
		$str = '';
		if (is_array($this->_config['style_links'])) {
			foreach ($this->_script_links as $url) {
				$str .= "<script type=\"text/javascript\" src=\"$url\"></script>\n";
			}
		}
	}

	public function renderScripts() {
		$str = '';
		if (is_array($this->_config['style_links'])) {
			foreach ($this->_script as $script) {
				$str .= "<script type=\"text/javascript\">\n$script\n</script>\n";
			}
		}
	}

	public function renderMetadata() {
		$str = '';
		if (is_array($this->_config['style_links'])) {
			foreach ($this->_metadata() as $name => $content) {
				$str .= "<meta name=\"$name\" content=\"$content\">\n";
			}
		}
		return $str;
	}

	public function renderBase($base=null) {
		return "<base href=\"{$this->_config['base']}\"/>\n";
	}

	/*
	* name=string, value=string or renderer
	*/
	public function render($attr=array(), $content=null) {
		$html = $this->renderDoctype();
		$html .= "<html>\n<head>\n";
		$html .= "</head>\n<body>\n";
		$html .= "</body>\n</html>\n";
		return $html;
	}

	public function __call($type, $args) {
	}

	public function __toString() {
		$this->render();
	}
}
