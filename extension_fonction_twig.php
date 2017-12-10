<?php



class MonExtension extends Twig_Extension 
{

	public function getFilters() 
	{
		return 
		[
			new Twig_SimpleFilter('pretty_date', [$this, 'date']),
			new Twig_SimpleFilter('money', [$this, 'money']),
			new Twig_SimpleFilter('month', [$this, 'month'])
			
		];
	}

	public function getFunctions() 
	{
		return 
		[
			new Twig_SimpleFunction('currentMonth', [$this, 'currentMonth']),
			new Twig_SimpleFunction('array_key_exists', [$this, 'array_key_exists'])
		];
	}

	public function date($date) 
	{
		return date("d M, Y h:i A", strtotime($date));
	}

	public function money($number)
	{
		return '$'.number_format($number,2);
	}

	public function month($i) 
	{
		
		$month = DateTime::createFromFormat('!m', $i);
		return $month->format("F");
	}

	public function currentMonth($i) 
	{
		if(date("m") == $i) 
		{
			return true;
		} 
		else 
		{
			return false;
		}
	}

	public function array_key_exists($key, $array) 
	{
		if(array_key_exists($key, $array)) 
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}



?>