<?php
class Mark {
	public $mark=0;
	public $total=0;

	public function __construct (int $mark,
		int $total)
	{
		assert(mark<total);
		$this->mark=$mark;
		$this->total=$total;
	}
};

class GetResponseError extends Exception 
{
	public $str;

	public function __construct (int $sttr)
	{
		$this->str=$sttr;
	}
};

function get_response(string $url)
{
	$curlHandle=curl_init();
	curl_setopt($curlHandle, CURLOPT_URL, $url);
	curl_setopt($curlHandle, CURLOPT_VERBOSE, false);
	curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curlHandle, CURLOPT_BINARYTRANSFER, true);
	curl_setopt($curlHandle, CURLOPT_HEADER, false);

	if (!$v=curl_exec($curlHandle))
	{
		throw new GetResponseError (curl_errno($curlHandle));
	}
	curl_close($curlHandle);
	return $v;
}
function down_this(string $url)
{
	$e=date("d,m,y",time());
	if (!file_exists($e))
	{
		mkdir($e);
		$f=fopen("$e/res.json", "a+");
		try {
			$dt=get_response ("$url")
				if ($dt==null)
				{
					throw GetResponseError ("Aaaah");
				}
			fwrite($f,$dt);
		} catch (GetResponseError $s)
		{
			delete("$e/res.json");
			delete($e);
		}
		fflush($f);
		fclose($f);
	}
}
down_this ("https://www.google.com");
?>
