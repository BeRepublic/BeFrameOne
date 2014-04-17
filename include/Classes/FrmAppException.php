<?php
namespace Classes;

/**
 *
 * Thrown
 * attempting to execute a query.
 *
 */
class FrmAppException extends \Exception {

	protected $_ErrorTitle;
	
	/**
	 * Error Number / Code from the Database
	 *
	 * @var string $ErrorCode
	 */
	protected $_ErrorCode;

	/**
	 * Error Message from the Database
	 *
	 * @var string $ErrorMessage
	 */
	protected $_ErrorMessage;

	/**
	 * Class constructor
	 *
	 * @param DBQuery $db Query object which threw this exception.
	 */
	public function __construct($title='', $code=1) {
		/* get the calling stack */
		$backtrace = $this->GetTrace();
		$backtrace = array_reverse($backtrace);

		$message = array();
		foreach ($backtrace as $key=>$msg){
			$line = isset($msg['line']) ? $msg['line'] : '';
			$message[] = $msg['class'].':'.$msg['function'].':'.$line;
		}		
		
		$this->_ErrorMessage = $message;
		$this->_ErrorCode = $code;
		$this->_ErrorTitle = $title;
		/* call the super class Exception constructor */
		parent::__construct('Query Error ', $code);
	}


	/**
	 * Get Error Message
	 *
	 * @return string Error Message
	 */
	public function GetErrorMessage() {
		return $this->_ErrorMessage;
	}

	/**
	 * Called when the object is casted to a string.
	 * DIE!
	 */
	public function __toString() {
		$app = new \App();
		$html = $app->render('Error/exception', array('title'=>$this->_ErrorTitle,'messages'=>$this->_ErrorMessage, 'trace'=>$this->getTrace() ));
		die($html);
	}

}
