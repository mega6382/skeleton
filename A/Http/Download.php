<?php
/**
 * Download.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Http_Download
 *
 * Support for downloading data with different MIME types and settings
 *
 * @package A_Http
 */
class A_Http_Download
{

	protected $mime_type = 'text/none';
	protected $encoding = 'none';
	protected $content_length = 0;
	protected $source_file = '';
	protected $target_file = '';
	protected $target_file_type = 'attachment';
	protected $headers = array();
	protected $error = 0;

	/**
	 * Set the mime type of the file to be downloaded to be specified in the header
	 *
	 * @param string $type
	 * @return $this
	 */
	public function setMimeType($type)
	{
		if ($type) {
			$this->mime_type = $type;
		}
		return $this;
	}

	/**
	 * Set the Transfer Encoding of the file to be downloaded to be specified in the header
	 *
	 * @param string $encoding
	 * @return $this
	 */
	public function setEncoding($encoding)
	{
		if ($encoding) {
			$this->encoding = $encoding;
		}
		return $this;
	}

	/**
	 * Set the path to a file on the server.  The contents will be dumped following outputing the header and content length will be set
	 *
	 * @param string $path
	 * @return $this
	 */
	public function setSourceFilePath($path)
	{
		if ($path) {
			$this->source_file = $path;
		}
		return $this;
	}

	/**
	 * Set the filename to be used on the client.  Use if no source file or if you want a different name than the source file.
	 *
	 * @param string $name
	 * @param string $type 'attachment' or 'inline' or 'hidden'
	 * @return $this
	 */
	public function setTargetFileName($name, $type='')
	{
		if ($name) {
			$this->target_file = $name;
		}
		if ($type) {
			$this->target_file_type = $type;
		}
		return $this;
	}

	/**
	 * Optional - if no source filename specified then use this to set length.
	 *
	 * @param int $length
	 * @return $this
	 */
	public function setContentLength($length)
	{
		if ($length >= 0) {
			$this->content_length = $length;
		}
		return $this;
	}

	/**
	 * Add additional header to be sent.
	 *
	 * @param string $name
	 * @param string $value
	 * @return $this
	 */
	public function setHeader($name, $value)
	{
		if ($name) {
			$this->headers[$name] = $value;
		}
		return $this;
	}

	protected function _header($name, $value)
	{
		if (is_array($value)) {
			foreach ($value as $val) {
				header("$name: $val");
			}
		} else {
			header("$name: $value");
		}
	}

	/**
	 * Send headers, followed by the contents of the source file if specified.  Output will be included in the file
	 *
	 * @return string Errors acumulated
	 */
	public function doDownload()
	{
		if (!headers_sent()) {
			if ($this->mime_type) {
				// set the mime type of the data to be downloaded
				$this->_header('Content-type', $this->mime_type);

				if ($this->encoding) {
					$this->_header('Content-Transfer-Encoding', $this->encoding);
				}
				/*
				 // maybe implement some support for these
				 header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
				 header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1
				 header('Content-Transfer-Encoding: none');
				 header('Content-Type: application/octetstream');	//	IE and Opera
				 header('Content-Type: application/octet-stream');	//	All other browsers
				 header('Content-Transfer-Encoding: Binary');
				 header('Content-Disposition: attachment; filename="' . $name . '"');
				 header("Pragma: public");	//	Stop old IEs saving the download script by mistake
				 */
				// if target file name is supplied add it to header
				if ($this->target_file_type) {
					if ($this->target_file_type) {
						$type = $this->target_file_type . '; ';
					} else {
						$type = '';
					}
					$this->_header('Content-Disposition', $type . 'filename=' . $this->target_file);
				}
				if ($this->headers) {
					foreach ($this->headers as $name => $value) {
						$this->_header($name, $value);
					}
				}

				// if source file path is specified then dump the file following the header
				if ($this->source_file) {
					header('Content-Length: ' . @filesize($this->source_file));
					if (@readfile($this->source_file) === false) {
						$this->error = 3;
					}
				} elseif ($this->content_length > 0) {
					header('Content-Length: ' . $this->content_length);
				}
			} else {
				$this->error = 2;
			}
		} else {
			$this->error = 1;
		}
		return $this->error;
	}

	public function isError()
	{
		return $this->error;
	}

	public function getError()
	{
		return $this->error;
	}

	public function getErrorMsg()
	{
		$messages = array(
			1 => 'Headers sent. ',
			2 => 'No MIME type. ',
			3 => 'Error reading file ' . $this->source_file . '. ',
			);
		return isset($messages[$this->error]) ? $messages[$this->error] : '';
	}

}
