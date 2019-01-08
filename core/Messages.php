<?php


/**
 * Messages Class
 * 
 * Prints each message with a difference state to the website and/or logfile
 */
class Messages
{
	/**
	 * Prints a success message with a green background
	 * used with Bootstrap
	 *
	 * @param String $message
	 * @return void
	 */
	public function success ($message, $die = false)
	{
		if ($die == true)
		{
			die("<div class=\"alert alert-success alert-dismissable\"><strong>Success!</strong> ". ucwords( html_entity_decode($message) ) ."</div>");
		}

		echo "<div class=\"alert alert-success alert-dismissable\"><strong>Success!</strong> ". ucwords( html_entity_decode($message) ) ."</div>";
	}

	/**
	 * Prints a informational message with a light blue background
	 * used with Bootstrap
	 *
	 * @param String $message
	 * @return void
	 */
	public function info ($message, $die = false)
	{
		if ($die == true)
		{
			die("<div class=\"alert alert-info alert-dismissable\"><strong>Info!</strong> ". ucwords( html_entity_decode($message) ) ."</div>");
		}

		echo "<div class=\"alert alert-info alert-dismissable\"><strong>Info!</strong> ". ucwords( html_entity_decode($message) ) ."</div>";
	}

	/**
	 * Prints a warning message with a orange like background
	 * used with Bootstrap
	 *
	 * @param String $message
	 * @return void
	 */
	public function warning ($message, $die = false)
	{
		if ($die == true)
		{
			die("<div class=\"alert alert-danger alert-dismissable\"><strong>Warning!</strong> ". ucwords( html_entity_decode($message) ) ."</div>");
		}

		echo "<div class=\"alert alert-danger alert-dismissable\"><strong>Warning!</strong> ". ucwords( html_entity_decode($message) ) ."</div>";
	}

	/**
	 * Prints a error message with a red like background
	 * used with Bootstrap and uses buildError
	 *
	 * @param String $message
	 * @return void
	 */
	public function error ($message, $log_error = null, $die = true)
	{
		if ( $log_error != null )
		{
			@$file = array_shift(debug_backtrace())['file'];
			@$line = array_shift(debug_backtrace())['line'];
			$this->buildError("($file)(Line: $line) ".$message." $log_error");
		}
		else
		{
			$this->buildError($message);
		}

		if ($die == true)
		{
			die("<div class=\"alert alert-danger\"><strong>Error!</strong> ". ucwords( html_entity_decode($message) ) ."</div>");
		}
		
		echo "<div class=\"alert alert-danger\"><strong>Error!</strong> ". ucwords( html_entity_decode($message) ) ."</div>";
	}	

	/**
	 * Prints an error message with a date/time before the error
	 * into a file i.e. error.log with a line break in the end of each line
	 *
	 * @param String $error
	 * @return void
	 */
	public function buildError($error)
	{
		if ($error != 8 && $error != 2048 && $error != 2)
		{
			$error_message = nl2br("[". date("e d-M-Y H:i:s A") ."]". ucwords($error) ."\n");
			$date = date("d-M-Y_g-A");				
			$log = fopen("$date.log", "a+");
			fwrite($log, $error_message);
			fclose($log);

			return true;
		}
		else
		{
			return false;
		}
	}
}