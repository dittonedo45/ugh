<?php
class GetResponseError extends Exception 
{
};

class GetCurl {
	public $curl;
	public function __construct (string $url)
	{
		$this->curl=curl_init ();
		curl_setopt($this->curl, CURLOPT_URL, $url);
		curl_setopt($this->curl, CURLOPT_VERBOSE, false);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->curl, CURLOPT_BINARYTRANSFER, true);
		curl_setopt($this->curl, CURLOPT_HEADER, false);
	}
	function exec ()
	{
		return curl_exec ($this->curl);
	}
	public function __destruct ()
	{
		curl_close($this->curl);
	}
};

function get_response(string $url)
{
	if (!$v=(new GetCurl($url))->exec())
	{
		throw new GetResponseError ();
	}
	return $v;
}
class Res {
	public $file;
	public function __construct (string $e)
	{
		$this->file=fopen("$e/res.json", "a+");
	}
	public function write(string $dt)
	{
		fwrite($this->file,$dt);
	}
	public function __destruct ()
	{
		fflush($this->file);
		fclose($this->file);
	}
}
function down_this(string $url)
{
	$e=date("d,m,y",time());
	if (!file_exists($e))
	{
		mkdir($e);
		$f=new Res($e);
		try {
			global $f;
			$dt=get_response ("$url");
			if ($dt==null)
			{
				throw GetResponseError ("Aaaah");
			}
			$f->write ($dt);
		} catch (GetResponseError $s)
		{
			unlink("$e/res.json");
			rmdir($e);
		}
	}
}
function get_url($arg)
{
	return get_response ($arg);
}
down_this ("https://www.google.com");
/*UGH*/
?>
