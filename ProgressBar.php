<?php

/**
 * ProgressBar -- for use with CocoaDialog (http://cocoadialog.sourceforge.net/)
 * 
 * A PHP 5 port of the ProgressBar Python class created by Paul Bissex.
 *
 * @author Jay Williams <jay@myd3.com>
 * @author Paul Bissex <pb@e-scribe.com>
 * @version 0.1
 * @license MIT
 **/

/**
* Simple class for displaying progress bars using CocoaDialog
*/
class ProgressBar
{
	/**
	 * Change CD_BASE to reflect the location of Cocoadialog on your system
	 */
	const CD_BASE = '/Applications/';
	const CD_PATH = 'CocoaDialog.app/Contents/MacOS/CocoaDialog';
	
	protected $percent;
	protected $message;
	
	private $pipe;
	
	/**
	 * Create progress bar dialog
	 */
	public function __construct($title="Progress", $message="", $percent=0)
	{
		$template = "%s progressbar --title %s --text %s --percent %d";
		$command = sprintf($template,self::CD_BASE.self::CD_PATH, escapeshellarg($title), escapeshellarg($message), $percent);
		
		$this->pipe = popen($command,"w");
		$this->percent = $percent;
		$this->message = $message;
	}
	
	/**
	 * Update progress bar (and message if desired)
	 */
	public function update($percent, $message=False)
	{
		if ($message)
			$this->message = $message;
		
		$command = sprintf("%d %s\n", $percent, $message);
		fwrite($this->pipe,$command);
	}
	
	/**
	 * Close progress bar window
	 */
	public function finish()
	{
		pclose($this->pipe);
	}
}

/**
 * Sample Usage
 */
$bar = new ProgressBar("ProgressBar.php Test");

for ($percent=0; $percent < 25; $percent++) { 
	usleep(150000); // .15 sec
	$bar->update($percent, "Test Starting...");
}

for ($percent=25; $percent < 100; $percent++) { 
	usleep(20000); // .02 sec
	$bar->update($percent, "Test Finishing...");
}

usleep(500000); // .5 sec
$bar->finish();

?>