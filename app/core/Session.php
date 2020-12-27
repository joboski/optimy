<?php 

namespace optimy\app\core;

class Session 
{
	protected const FLASH_MESSAGES = "flash_messages";
	

	public function __construct()
	{
		session_start();
		$flashMessages = $_SESSION[self::FLASH_MESSAGES] ?? [];

		foreach ($flashMessages as $key => &$message) {
			// mark for removal
			$message['remove'] = true;
		}

		$_SESSION[self::FLASH_MESSAGES] = $flashMessages;
	}

	public function __destruct()
	{
		$flashMessages = $_SESSION[self::FLASH_MESSAGES] ?? [];

		foreach ($flashMessages as $key => &$message) {
			if ($message['remove']) {
				unset($flashMessages[$key]);
			}	
		}

		$_SESSION[self::FLASH_MESSAGES] = $flashMessages;
	}

	public function setFlash($key, $msg)
	{
		$_SESSION[self::FLASH_MESSAGES][$key] = [
			'remove' => false,
			'message' => $msg
		];
	}

	public function getFlash($key){

		return $_SESSION[self::FLASH_MESSAGES][$key]['message'] ?? false;
	}
}
