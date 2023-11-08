<?php 

namespace App;

use Carbon\Carbon;
use App\JDateTime;

class JDate
{
	protected $time;
	protected $isGregorian = false;

	protected $formats = array(
		'datetime' => '%Y-%m-%d %H:%M:%S',
		'date'     => '%Y-%m-%d',
		'time'     => '%H:%M:%S',
	);

	public static function parse($str = null)
	{
		$class = __CLASS__;
		return new $class($str);
	}

	public static function forge($str = null){
		return self::parse($str);
	}

	public static function now(){
		return self::parse(null);
	}

	public static function today(){
		return self::now();
	}

	public static function tomorrow(){
		return self::now()->addDay(1);
	}

	public static function yesterday(){
		return self::now()->addDay(-1);
	}

	public static function nextWeek(){
		return self::now()->addDay(7);
	}

	public static function previousWeek(){
		return self::now()->addDay(-7);
	}

	public static function prevWeek(){
		return self::previousWeek();
	}

	public static function weekStart($dateType = 'jalali'){
		$now = self::now();

		if ($dateType == 'jalali')
			$weekDay = $now->getWeekday();
		elseif ($dateType == 'gregorian')
			$weekDay = $now->toGregorian()->getWeekday();

		return $now->addDays(($weekDay - 1) * -1);
	}

	public static function gregorianWeekStart(){
		return self::weekStart('gregorian');
	}

	public static function jalaliWeekStart(){
		return self::weekStart('jalali');
	}

	public static function weekEnd($dateType = 'jalali'){
		$now = self::now();

		if ($dateType == 'jalali')
			$weekDay = $now->getWeekday();
		elseif ($dateType == 'gregorian')
			$weekDay = $now->toGregorian()->getWeekday();

		return $now->addDays(7- $weekDay);
	}

	public static function gregorianWeekEnd(){
		return self::weekEnd('gregorian');
	}

	public static function jalaliWeekEnd(){
		return self::weekEnd('jalali');
	}

	public static function monthStart(){
		$instance = self::now();

		$instance->time = JDateTime::mktime(0 , 0 , 0 , $instance->getMonth() , 1 , $instance->getYear() , true , 'UTC');
		return $instance;
	}

	public static function monthEnd(){
		$instance = self::now()->addMonth();

		$instance->time = JDateTime::mktime(0 , 0 , 0 , $instance->getMonth() , 1 , $instance->getYear() , true , 'UTC');
		return $instance->subDay();
	}

    public static function seasonStart(){
        $monthStart = self::now()->gotoMonthStart();
        $month = $monthStart->getMonth();
        return $monthStart->subMonths($month - (floor($month / 3) + 1));
    }

    public static function seasonEnd(){
        return self::seasonStart()->gotoSeasonStart()->addMonths(3)->subDay();
    }

    public static function yearStart($dateType = 'jalali'){
        $now = self::now();

        if ($dateType == 'jalali')
            $yearDay = $now->getYearday();
        elseif ($dateType == 'gregorian')
            $yearDay = $now->toGregorian()->getYearday();

        return $now->addDays(($yearDay - 1) * -1);
    }

    public static function yearEnd($dateType = 'jalali'){
        if ($dateType == 'jalali'){
            return self::now()->gotoYearStart()->addYear()->subDay();
        }elseif ($dateType == 'gregorian'){
            return self::now()->toGregorian()->gotoYearStart()->addYear()->subDay();
        }
    }

	public function __construct($str = null)
	{
		if ($str === null){
			$this->time = time();
		}else{
			if (is_numeric($str)){
				$this->time = $str;
			}else{
				$time = strtotime($str);

				if (!$time){
					$this->time = false;
				}else{
					$this->time = $time;
				}
			}
		}
	}

	public function getTime()
	{
		return $this->time;
	}

	public function format($str){
		// convert alias string
		if (in_array($str, array_keys($this->formats))){
			$str = $this->formats[$str];
		}

		// if valid unix timestamp...
		if ($this->time !== false){
			if ($this->isGregorian === true){
                return Carbon::createFromTimestamp($this->time, 'Asia/Tehran')->format($str);
                //return date($str, $this->time);
            }else{
                return JDateTime::strftime($str, $this->time);
            }
		}
		else{
			return false;
		}
	}

	public function reforge($str)
	{
		if ($this->time !== false)
		{
			// amend the time
			$time = strtotime($str, $this->time);

			// if conversion fails...
			if (!$time){
				// set time as false
				$this->time = false;
			}
			else{
				// accept time value
				$this->time = $time;
			}
		}

		return $this;
	}

	public static function checkLeapYear($year){
	    $a = 0.025;
	    $b = 266;
	    if ($year > 0){
	        $leapDays0 = (($year + 38) % 2820) * 0.24219 + $a;  # 0.24219 ~ extra days of one year
	        $leapDays1 = (($year + 39) % 2820) * 0.24219 + $a;  # 38 days is the difference of epoch to 2820-year cycle
	    }elseif ($year < 0){
	        $leapDays0 = (($year + 39) % 2820) * 0.24219 + $a;
	        $leapDays1 = (($year + 40) % 2820) * 0.24219 + $a;
	    }else{
	        return false;
	    }

	    $frac0 = intval(($leapDays0 - intval($leapDays0)) * 1000);
	    $frac1 = intval(($leapDays1 - intval($leapDays1)) * 1000);

	    if ($frac0 <= $b and $frac1 > $b)
	        return true;
	    else
	        return false;
	}

	public function isLeapYear(){
		$thisTtmp = $this;
		return self::checkLeapYear($thisTtmp->toJalali()->getYear());
	}

	public function addYears($amount){
		if ($this->time == false/* || $amount < 0*/)
			return $this;

		if ($this->isGregorian){
			$numOfDays = $amount * 365;
		}else{
			$currentYear = $incrementedYear = self::now()->getYear();
	        $numOfDays = 0;
	        for ($i = 1 ; $i <= $amount ; $i++){
	        	$numOfDays += 365;
	        	if (self::checkLeapYear($incrementedYear))
	        		$numOfDays++;

	        	$incrementedYear++;
	        }
		}

		return $this->addDays($numOfDays);
	}

	public function addYear($amount = 1){
		return $this->addYears($amount);
	}

	public function subYears($amount){
		if ($this->time == false/* || $amount < 0*/)
			return $this;

		if ($this->isGregorian){
			$numOfDays = $amount * 365;
		}else{
			$currentYear = self::now()->getYear();
			$decrementedYear = $currentYear - 1;
	        $numOfDays = 0;
	        for ($i = 0 ; $i < $amount ; $i++){
	        	$numOfDays += 365;
	        	if (self::checkLeapYear($decrementedYear))
	        		$numOfDays++;

	        	$decrementedYear--;
	        }
		}

		return $this->subDays($numOfDays);
	}

	public function subYear($amount = 1){
		return $this->subYears($amount);
	}

	public function addMonths($amount){
		if ($this->time == false/* || $amount < 0*/)
			return $this;

		$jalaliDate = $this->format('Y/m/d');
		$jalaliDateArray = explode('/' , $jalaliDate);
		$jalaliTime = $this->format('H:i:s');
		$jalaliTimeArray = explode(':' , $jalaliTime);

        $monthAdded = $jalaliDateArray[1] + $amount;

        $this->time = JDateTime::mktime($jalaliTimeArray[0] , $jalaliTimeArray[1] , $jalaliTimeArray[2] , (($monthAdded % 12 > 0) ? $monthAdded % 12 : 12) , $jalaliDateArray[2] , floor($jalaliDateArray[0] + (($monthAdded - 1) / 12)) , true , 'GMT');

        // $date = self::parse(JDateTime::mktime(12 , 0 , 0 , (($monthAdded % 12 > 0) ? $monthAdded % 12 : 12) , $jalaliDateArray[2] , floor($jalaliDateArray[0] + (($monthAdded - 1) / 12)) , true , 'GMT'));
        // $month = $this->getMonth();
        // $mod = $monthAdded % 12;
        // if ($mod == 0)
        // 	$mod = 12;

        // if ($month + $mod)

		return $this;
	}

	public function addMonth($amount = 1){
		return $this->addMonths($amount);
	}

	public function subMonths($amount){
		if ($this->time == false/* || $amount < 0*/)
			return $this;

		$jalaliDate = $this->format('Y/m/d');
		$jalaliDateArray = explode('/' , $jalaliDate);
		$jalaliTime = $this->format('H:i:s');
		$jalaliTimeArray = explode(':' , $jalaliTime);

        $month = $monthAdded = $jalaliDateArray[1] - $amount;
        if ($month <= 0)
        	$month = 12 - (abs($month) % 12);

        $this->time = JDateTime::mktime($jalaliTimeArray[0] , $jalaliTimeArray[1] , $jalaliTimeArray[2] , $month , $jalaliDateArray[2] , floor($jalaliDateArray[0] + (($monthAdded - 1) / 12)) , true , 'GMT');

        // $date = self::parse(JDateTime::mktime(12 , 0 , 0 , (($monthAdded % 12 > 0) ? $monthAdded % 12 : 12) , $jalaliDateArray[2] , floor($jalaliDateArray[0] + (($monthAdded - 1) / 12)) , true , 'GMT'));
        // $month = $this->getMonth();
        // $mod = $monthAdded % 12;
        // if ($mod == 0)
        // 	$mod = 12;

        // if ($month + $mod)

		return $this;
	}

	public function subMonth($amount = 1){
		return $this->subMonths($amount);
	}

	public function addDays($amount){
		if ($this->time == false/* || $amount < 0*/)
			return $this;

		$this->time += $amount * 86400;

		return $this;
	}

	public function addDay($amount = 1){
		return $this->addDays($amount);
	}

	public function subDays($amount){
		if ($this->time == false/* || $amount < 0*/)
			return $this;

		$this->time -= $amount * 86400;

		return $this;
	}

	public function subDay($amount = 1){
		return $this->subDays($amount);
	}

	public function addHours($amount){
		if ($this->time == false/* || $amount < 0*/)
			return $this;

		$this->time += $amount * 3600;

		return $this;
	}

	public function addHour($amount = 1){
		return $this->addHours($amount);
	}

	public function subHours($amount){
		if ($this->time == false/* || $amount < 0*/)
			return $this;

		$this->time -= $amount * 3600;

		return $this;
	}

	public function subHour($amount = 1){
		return $this->subHours($amount);
	}

	public function addMinutes($amount){
		if ($this->time == false/* || $amount < 0*/)
			return $this;

		$this->time += $amount * 60;

		return $this;
	}

	public function addMinute($amount = 1){
		return $this->addMinutes($amount);
	}

	public function subMinutes($amount){
		if ($this->time == false/* || $amount < 0*/)
			return $this;

		$this->time -= $amount * 60;

		return $this;
	}

	public function subMinute($amount = 1){
		return $this->subMinutes($amount);
	}

	public function addSeconds($amount){
		if ($this->time == false/* || $amount < 0*/)
			return $this;

		$this->time += $amount;

		return $this;
	}

	public function addSecond($amount = 1){
		return $this->addSeconds($amount);
	}

	public function subSeconds($amount){
		if ($this->time == false/* || $amount < 0*/)
			return $this;

		$this->time -= $amount;

		return $this;
	}

	public function subSecond($amount = 1){
		return $this->subSeconds($amount);
	}

	public function ago()
	{
		$now = time();
		$time = $this->time();

		// catch error
		if (!$time) return false;

		// build period and length arrays
		$periods = array('ثانیه', 'دقیقه', 'ساعت', 'روز', 'هفته', 'ماه', 'سال', 'قرن');
		$lengths = array(60, 60, 24, 7, 4.35, 12, 10);

		// get difference
		$difference = $now - $time;

		// set descriptor
		if ($difference < 0)
		{
			$difference = abs($difference); // absolute value
			$negative = true;
		}

		// do math
		for($j = 0; $difference >= $lengths[$j] and $j < count($lengths)-1; $j++){
			$difference /= $lengths[$j];
		}

		// round difference
		$difference = intval(round($difference));

		// return
		return number_format($difference).' '.$periods[$j].' '.(isset($negative) ? '' : 'پیش');
	}

	public function until()
	{
		return $this->ago();
	}

	public function toGregorian(){
		if (!isset($this->isGregorian))
			return $this;

		$this->isGregorian = true;
		return $this;
	}

	public function toJalali(){
		if (!isset($this->isGregorian))
			return $this;

		$this->isGregorian = false;
		return $this;
	}

	public function toDateString($separator = null){
		if (!$separator)
			if ($this->isGregorian === false)
				$separator = '/';
			else
				$separator = '-';

		return $this->format('Y' . $separator . 'm' . $separator . 'd');
	}

	public function toTimeString($addSeconds = true){
		$format = 'H:i';

		if ($addSeconds == true) $format .= ':s';

		return $this->format($format);
	}

	public function toDateTimeString($separator = null , $addSeconds = true){
		return $this->toDateString($separator) . ' ' . $this->toTimeString($addSeconds);
	}

	public function getYear(){
		return $this->format('Y');
	}

	public function getMonth(){
		return $this->format('m');
	}

	public function getDay(){
		return $this->format('d');
	}

	public function getHour(){
		return $this->format('H');
	}

	public function getMinute(){
		return $this->format('i');
	}

	public function getSecond(){
		return $this->format('s');
	}

	public function getWeekday(){
		return $this->format('N');
	}

	public function getYearday(){
		return $this->format('z');
	}

	public function getWeekdayName(){
		return $this->format('l');
	}

	public function getShortWeekdayName(){
		return $this->format('D');
	}

	public function getWeekdayTitles(){
		return [
			'' => '',
		];
	}

	public static function j2g($dateTime, $format = 'date'){
        $dateTime = self::formatNumbers($dateTime);

        $dateTime = preg_replace('/\s+/', ' ', $dateTime);

        $dateTimeArray = explode(' ', $dateTime);

        $dateValue = trim(substr($dateTime , 0 , 10));
        $timeValue = (isset($dateTimeArray[1])) ? trim($dateTimeArray[1]) : null;

        //check if both dateValue and timeValue are not empty
        if (!strlen($dateValue) && !strlen($timeValue))
            return null;

        //date and time calcualtions
        $nowJDate = JDate::now();

        $dateArray = [];
        if ($dateValue)
            $dateArray = explode('/', $dateValue);

        $year = (isset($dateArray[0])) ? $dateArray[0] : $nowJDate->format('Y');
        $month = (isset($dateArray[1])) ? $dateArray[1] : $nowJDate->format('m');
        $day = (isset($dateArray[2])) ? $dateArray[2] : $nowJDate->format('d');

        $timeArray = [];
        if ($timeValue)
            $timeArray = explode(':', $timeValue);

        $hour = (isset($timeArray[0])) ? $timeArray[0] : '12';
        $minute = (isset($timeArray[1])) ? $timeArray[1] : '00';
        $second = (isset($timeArray[2])) ? $timeArray[2] : '00';

        try{
            $time = JDateTime::mktime($hour, $minute, $second, $month, $day, $year, true, 'Asia/Tehran');
        }catch (\Exception $e){
            \Log::info('Datepicker save value error. Error: ' . $e->getMessage() . ' value: ' . $dateValue . ' ' . $timeValue);
            return null;
        }

        //make output
        if ($format == 'datetime'){
            return date('Y-m-d H:i:s', $time);
        }elseif ($format == 'date'){
            return date('Y-m-d', $time);
        }elseif ($format == 'time'){
            return date('H:i:s', $time);
        }
	}

    public function gotoWeekStart($dateType = 'jalali'){
        if ($dateType == 'jalali')
            $weekDay = $this->getWeekday();
        elseif ($dateType == 'gregorian')
            $weekDay = $this->toGregorian()->getWeekday();

        return $this->addDays(($weekDay - 1) * -1);
    }

    public function gotoWeekEnd($dateType = 'jalali'){
        if ($dateType == 'jalali')
            $weekDay = $this->getWeekday();
        elseif ($dateType == 'gregorian')
            $weekDay = $this->toGregorian()->getWeekday();

        return $this->addDays(7- $weekDay);
    }

    public function gotoMonthStart(){
        $this->time = JDateTime::mktime(0 , 0 , 0 , $this->getMonth() , 1 , $this->getYear() , true , 'UTC');
        return $this;
    }

    public function gotoMonthEnd(){
        $instance = $this->addMonth();

        $instance->time = JDateTime::mktime(0 , 0 , 0 , $instance->getMonth() , 1 , $instance->getYear() , true , 'UTC');
        return $instance->subDay();
    }

    public function gotoSeasonStart(){
        $monthStart = $this->gotoMonthStart();
        $month = $monthStart->getMonth();
        //return $monthStart->subMonths($month - (floor($month / 3) + 1));

        $mod = $month % 3;
        switch ($mod){
            case 0:
                $sub = 2;
                break;
            case 1:
                $sub = 0;
                break;
            case 2:
                $sub = 1;
                break;
        }

        return $monthStart->subMonths($sub);
    }

    public function gotoSeasonEnd(){
        return $this->gotoSeasonStart()->addMonths(3)->subDay();
    }

    public function gotoHalfStart(){
        $monthStart = $this->gotoMonthStart();
        $month = $monthStart->getMonth();
        //return $monthStart->subMonths($month - (floor($month / 3) + 1));

        $mod = $month % 6;
        switch ($mod){
            case 1:
                $sub = 0;
                break;
            case 2:
                $sub = 1;
                break;
            case 3:
                $sub = 2;
                break;
            case 4:
                $sub = 3;
                break;
            case 5:
                $sub = 4;
                break;
            case 0:
                $sub = 5;
                break;
        }

        return $monthStart->subMonths($sub);
    }

    public function gotoHalfEnd(){
        return $this->gotoHalfStart()->addMonths(6)->subDay();
    }

    public function gotoYearStart($dateType = 'jalali'){
        if ($dateType == 'jalali')
            $yearDay = $this->getYearday();
        elseif ($dateType == 'gregorian')
            $yearDay = $this->toGregorian()->getYearday();

        return $this->addDays(($yearDay - 1) * -1);
    }

    public function gotoYearEnd($dateType = 'jalali'){
        return $this->gotoYearStart()->addYear()->subDay();
    }






    public static function formatNumbers($string){
        $persianNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabicNumbers =  ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $standardNumbers = range(0, 9);

        $string = str_replace($persianNumbers, $standardNumbers, $string);
        $string = str_replace($arabicNumbers, $standardNumbers, $string);

        return $string;
    }
}