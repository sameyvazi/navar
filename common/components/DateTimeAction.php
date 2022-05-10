<?php
namespace common\components;

use Yii;
use yii\base\Component;

class DateTimeAction extends Component {

    private $jalali = true; //Use Jalali Date, If set to false, falls back to gregorian
    private $convert = true; //Convert numbers to Farsi characters in utf-8
    private $timezone = null; //Timezone String e.g Asia/Tehran, Defaults to Server Timezone Settings
    private $temp = array();

    /**
     * convert unix time stamp to readable datetime
     * 
     * @author S.Eyvazi <saman3yvazi@gmail.com>
     * 
     * @param string $format
     * @param integer $time
     * @return string
     */
    public function timeToDate($format, $time, $convertDigit = true) {
        if (!$time){
            return null;
        }
        if (Yii::$app->language == 'fa-IR') {
            return $this->date($format, $time, $convertDigit);
        } else {
            return Yii::$app->formatter->asDatetime($time, 'php:' . $format);
            //return date($format, $time);
        }
    }
    
    public function getMongoDate($time = null)
    {
        if ($time instanceof \MongoDB\BSON\UTCDateTime)
        {
            return $time;
        }
        if (is_null($time))
        {
            $time = time();
        }
        
        $time *= 1000;
        
        return new \MongoDB\BSON\UTCDateTime($time);
    }
    
    public function getMongoDateInteger($date, $miliSecond = false)
    {
        $date = (int)(string)$date;
        if ($miliSecond)
        {
            return $date;
        }
        return (int)($date / 1000);
    }
    
    public function validateTime($time)
    {
        $time = $this->convertNumbers($time, true);
        return preg_match('#^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$#', $time);
    }
    
    public function validateDate($date, $delimiter = '-', $isJalali = true)
    {
        $date = $this->convertNumbers($date, true);
        if (count($date = explode($delimiter, $date)) == 3)
        {
            try
            {
                return $this->checkdate($date[1], $date[2], $date[0], $isJalali);
            } catch (\Exception $ex) {}
            
        }
        
        return false;
    }
    
    public function getTimestampedTime($time)
    {
        if (is_int($time))
        {
            return $time;
        }
        $time = $this->convertNumbers($time, true);
        if (count($time = explode(':', $time)) == 2)
        {
            return ($time[0] * 3600) + ($time[1] * 60);
        }

       return $time;

    }
    
    public function getTimestampedDate($date)
    {
        if ($date instanceof \MongoDB\BSON\UTCDateTime)
        {
            return $this->getMongoDateInteger($date);
        }
        
        $date = $this->convertNumbers($date, true);
        if (is_int($date))
        {
            return $date;
        }
        if (count($date = explode('-', $date)) == 3)
        {
            if ($date = Yii::$app->dateTimeAction->toGregorian($date[0], $date[1], $date[2]))
            {
                return strtotime(implode('-', $date));
            }
            
        }
        
        return $date;
    }
    
    public function getMongoDateForRestFormat($date)
    {
        $time = $date instanceof \MongoDB\BSON\UTCDateTime ? $this->getMongoDateInteger($date) : (int)$date;
        return [
            'timestamp' => $time,
            'gregorian' => date("Y-m-d H:i:s", $time),
            'jalali' => Yii::$app->formatter->asDatetime($time, 'php:Y-m-d H:i:s')
        ];
    }
    
    /**
     * jDateTime::Constructor
     *
     * Pass these parameteres when creating a new instance
     * of this Class, and they will be used as defaults.
     * e.g $obj = new jDateTime(false, true, 'Asia/Tehran');
     * To use system defaults pass null for each one or just
     * create the object without any parameters.
     *
     * @param $convert bool Converts numbers to Farsi
     * @param $jalali bool Converts date to Jalali
     * @param $timezone string Timezone string
     */
    /*
      public function __construct($convert = null, $jalali = null, $timezone = null)
      {
      if($jalali !== null)
      $this->jalali = (bool) $jalali;
      if($convert !== null)
      $this->convert = (bool) $convert;
      if($timezone !== null)
      $this->timezone = $timezone;
      }
     */

    /**
     * jDateTime::Date
     *
     * Formats and returns given timestamp just like php's
     * built in date() function.
     * e.g:
     * $obj->date("Y-m-d H:i", time());
     * $obj->date("Y-m-d", time(), false, false, 'America/New_York');
     *
     * @param $format string Acceps format string based on: php.net/date
     * @param $stamp int Unix Timestamp (Epoch Time)
     * @param $convert bool (Optional) forces convert action. pass null to use system default
     * @param $jalali bool (Optional) forces jalali conversion. pass null to use system default
     * @param $timezone string (Optional) forces a different timezone. pass null to use system default
     * @return string Formatted input
     */
    public function date($format, $stamp = false, $convert = null, $jalali = null, $timezone = null) {
        //Timestamp + Timezone
        $stamp = ($stamp !== false) ? $stamp : time();
        $timezone = ($timezone != null) ? $timezone : (($this->timezone != null) ? $this->timezone : date_default_timezone_get());
        $obj = new \DateTime('@' . $stamp, new \DateTimeZone($timezone));
        $obj->setTimezone(new \DateTimeZone($timezone));

        if (($this->jalali === false && $jalali === null) || $jalali === false) {
            return $obj->format($format);
        } else {

            //Find what to replace
            $chars = (preg_match_all('/([a-zA-Z]{1})/', $format, $chars)) ? $chars[0] : array();

            //Intact Keys
            $intact = array('B', 'h', 'H', 'g', 'G', 'i', 's', 'I', 'U', 'u', 'Z', 'O', 'P');
            $intact = $this->filterArray($chars, $intact);
            $intactValues = array();

            foreach ($intact as $k => $v) {
                $intactValues[$k] = $obj->format($v);
            }
            //End Intact Keys
            //Changed Keys
            list($year, $month, $day) = array($obj->format('Y'), $obj->format('n'), $obj->format('j'));
            list($jyear, $jmonth, $jday) = $this->toJalali($year, $month, $day);

            $keys = array('d', 'D', 'j', 'l', 'N', 'S', 'w', 'z', 'W', 'F', 'm', 'M', 'n', 't', 'L', 'o', 'Y', 'y', 'a', 'A', 'c', 'r', 'e', 'T');
            $keys = $this->filterArray($chars, $keys, array('z'));
            $values = array();

            foreach ($keys as $k => $key) {

                $v = '';
                switch ($key) {
                    //Day
                    case 'd':
                        $v = sprintf('%02d', $jday);
                        break;
                    case 'D':
                        $v = $this->getDayNames($obj->format('D'), true);
                        break;
                    case 'j':
                        $v = $jday;
                        break;
                    case 'l':
                        $v = $this->getDayNames($obj->format('l'));
                        break;
                    case 'N':
                        $v = $this->getDayNames($obj->format('l'), false, 1, true);
                        break;
                    case 'S':
                        $v = 'ام';
                        break;
                    case 'w':
                        $v = $this->getDayNames($obj->format('l'), false, 1, true) - 1;
                        break;
                    case 'z':
                        if ($jmonth > 6) {
                            $v = 186 + (($jmonth - 6 - 1) * 30) + $jday;
                        } else {
                            $v = (($jmonth - 1) * 31) + $jday;
                        }
                        $this->temp['z'] = $v;
                        break;
                    //Week
                    case 'W':
                        $v = is_int($this->temp['z'] / 7) ? ($this->temp['z'] / 7) : intval($this->temp['z'] / 7 + 1);
                        break;
                    //Month
                    case 'F':
                        $v = $this->getMonthNames($jmonth);
                        break;
                    case 'm':
                        $v = sprintf('%02d', $jmonth);
                        break;
                    case 'M':
                        $v = $this->getMonthNames($jmonth, true);
                        break;
                    case 'n':
                        $v = $jmonth;
                        break;
                    case 't':
                        if ($jmonth >= 1 && $jmonth <= 6)
                            $v = 31;
                        else if ($jmonth >= 7 && $jmonth <= 11)
                            $v = 30;
                        else if ($jmonth == 12 && $jyear % 4 == 3)
                            $v = 30;
                        else if ($jmonth == 12 && $jyear % 4 != 3)
                            $v = 29;
                        break;
                    //Year
                    case 'L':
                        $tmpObj = new \DateTime('@' . (time() - 31536000));
                        $v = $tmpObj->format('L');
                        break;
                    case 'o':
                    case 'Y':
                        $v = $jyear;
                        break;
                    case 'y':
                        $v = $jyear % 100;
                        break;
                    //Time
                    case 'a':
                        $v = ($obj->format('a') == 'am') ? 'ق.ظ' : 'ب.ظ';
                        break;
                    case 'A':
                        $v = ($obj->format('A') == 'AM') ? 'قبل از ظهر' : 'بعد از ظهر';
                        break;
                    //Full Dates
                    case 'c':
                        $v = $jyear . '-' . sprintf('%02d', $jmonth) . '-' . sprintf('%02d', $jday) . 'T';
                        $v .= $obj->format('H') . ':' . $obj->format('i') . ':' . $obj->format('s') . $obj->format('P');
                        break;
                    case 'r':
                        $v = $this->getDayNames($obj->format('D'), true) . ', ' . sprintf('%02d', $jday) . ' ' . $this->getMonthNames($jmonth, true);
                        $v .= ' ' . $jyear . ' ' . $obj->format('H') . ':' . $obj->format('i') . ':' . $obj->format('s') . ' ' . $obj->format('P');
                        break;
                    //Timezone
                    case 'e':
                        $v = $obj->format('e');
                        break;
                    case 'T':
                        $v = $obj->format('T');
                        break;
                }
                $values[$k] = $v;
            }
            //End Changed Keys
            //Merge
            $keys = array_merge($intact, $keys);
            $values = array_merge($intactValues, $values);

            //Return
            $ret = strtr($format, array_combine($keys, $values));
            return
                    ($convert === false ||
                    ($convert === null && $this->convert === false) ||
                    ( $jalali === false || $jalali === null && $this->jalali === false )) ? $ret : $this->convertNumbers($ret);
        }
    }

    /**
     * jDateTime::gDate
     *
     * Same as jDateTime::Date method
     * but this one works as a helper and returns Gregorian Date
     * in case someone doesn't like to pass all those false arguments
     * to Date method.
     *
     * e.g. $obj->gDate("Y-m-d") //Outputs: 2011-05-05
     *      $obj->date("Y-m-d", false, false, false); //Outputs: 2011-05-05
     *      Both return the exact same result.
     *
     * @param $format string Acceps format string based on: php.net/date
     * @param $stamp int Unix Timestamp (Epoch Time)
     * @param $timezone string (Optional) forces a different timezone. pass null to use system default
     * @return string Formatted input
     */
    public function gDate($format, $stamp = false, $timezone = null) {
        return $this->date($format, $stamp, false, false, $timezone);
    }

    /**
     * jDateTime::Strftime
     *
     * Format a local time/date according to locale settings
     * built in strftime() function.
     * e.g:
     * $obj->strftime("%x %H", time());
     * $obj->strftime("%H", time(), false, false, 'America/New_York');
     *
     * @author Omid Pilevar
     * @param $format string Acceps format string based on: php.net/date
     * @param $stamp int Unix Timestamp (Epoch Time)
     * @param $convert bool (Optional) forces convert action. pass null to use system default
     * @param $jalali bool (Optional) forces jalali conversion. pass null to use system default
     * @param $timezone string (Optional) forces a different timezone. pass null to use system default
     * @return string Formatted input
     */
    public function strftime($format, $stamp = false, $convert = null, $jalali = null, $timezone = null) {
        $str_format_code = array(
            '%a', '%A', '%d', '%e', '%j', '%u', '%w',
            '%U', '%V', '%W',
            '%b', '%B', '%h', '%m',
            '%C', '%g', '%G', '%y', '%Y',
            '%H', '%I', '%l', '%M', '%p', '%P', '%r', '%R', '%S', '%T', '%X', '%z', '%Z',
            '%c', '%D', '%F', '%s', '%x',
            '%n', '%t', '%%'
        );

        $date_format_code = array(
            'D', 'l', 'd', 'j', 'z', 'N', 'w',
            'W', 'W', 'W',
            'M', 'F', 'M', 'm',
            'y', 'y', 'y', 'y', 'Y',
            'H', 'h', 'g', 'i', 'A', 'a', 'h:i:s A', 'H:i', 's', 'H:i:s', 'h:i:s', 'H', 'H',
            'D j M H:i:s', 'd/m/y', 'Y-m-d', 'U', 'd/m/y',
            '\n', '\t', '%'
        );

        //Change Strftime format to Date format
        $format = str_replace($str_format_code, $date_format_code, $format);

        //Convert to date
        return $this->date($format, $stamp, $convert, $jalali, $timezone);
    }

    /**
     * jDateTime::Mktime
     *
     * Creates a Unix Timestamp (Epoch Time) based on given parameters
     * works like php's built in mktime() function.
     * e.g:
     * $time = $obj->mktime(0,0,0,2,10,1368);
     * $obj->date("Y-m-d", $time); //Format and Display
     * $obj->date("Y-m-d", $time, false, false); //Display in Gregorian !
     *
     * You can force gregorian mktime if system default is jalali and you
     * need to create a timestamp based on gregorian date
     * $time2 = $obj->mktime(0,0,0,12,23,1989, false);
     *
     * @param $hour int Hour based on 24 hour system
     * @param $minute int Minutes
     * @param $second int Seconds
     * @param $month int Month Number
     * @param $day int Day Number
     * @param $year int Four-digit Year number eg. 1390
     * @param $jalali bool (Optional) pass false if you want to input gregorian time
     * @param $timezone string (Optional) acceps an optional timezone if you want one
     * @return int Unix Timestamp (Epoch Time)
     */
    public function mktime($hour, $minute, $second, $month, $day, $year, $jalali = null, $timezone = null) {
        //Defaults
        $month = (intval($month) == 0) ? $this->date('m') : $month;
        $day = (intval($day) == 0) ? $this->date('d') : $day;
        $year = (intval($year) == 0) ? $this->date('Y') : $year;

        //Convert to Gregorian if necessary
        if ($jalali === true || ($jalali === null && $this->jalali === true)) {
            list($year, $month, $day) = $this->toGregorian($year, $month, $day);
        }

        //Create a new object and set the timezone if available
        $date = $year . '-' . sprintf('%02d', $month) . '-' . sprintf('%02d', $day) . ' ' . $hour . ':' . $minute . ':' . $second;

        if ($this->timezone != null || $timezone != null) {
            $obj = new \DateTime($date, new \DateTimeZone(($timezone != null) ? $timezone : $this->timezone));
        } else {
            $obj = new \DateTime($date);
        }

        //Return
        return $obj->format('U');
    }

    /**
     * jDateTime::Checkdate
     *
     * Checks the validity of the date formed by the arguments.
     * A date is considered valid if each parameter is properly defined.
     * works like php's built in checkdate() function.
     * Leap years are taken into consideration.
     * e.g:
     * $obj->checkdate(10, 21, 1390); // Return true
     * $obj->checkdate(9, 31, 1390);  // Return false
     *
     * You can force gregorian checkdate if system default is jalali and you
     * need to check based on gregorian date
     * $check = $obj->checkdate(12, 31, 2011, false);
     *
     * @author Omid Pilevar
     * @param $month int The month is between 1 and 12 inclusive.
     * @param $day int The day is within the allowed number of days for the given month.
     * @param $year int The year is between 1 and 32767 inclusive.
     * @param $jalali bool (Optional) pass false if you want to input gregorian time
     * @return bool
     */
    public function checkdate($month, $day, $year, $jalali = null) {
        //Defaults
        $month = (intval($month) == 0) ? $this->date('n') : intval($month);
        $day = (intval($day) == 0) ? $this->date('j') : intval($day);
        $year = (intval($year) == 0) ? $this->date('Y') : intval($year);

        //Check if its jalali date
        if ($jalali === true || ($jalali === null && $this->jalali === true)) {
            $epoch = $this->mktime(0, 0, 0, $month, $day, $year);

            if ($this->date('Y-n-j', $epoch, false) == "$year-$month-$day") {
                $ret = true;
            } else {
                $ret = false;
            }
        } else { //Gregorian Date
            $ret = checkdate($month, $day, $year);
        }

        //Return
        return $ret;
    }

    /**
     * System Helpers below
     * ------------------------------------------------------
     */

    /**
     * Filters out an array
     */
    private function filterArray($needle, $heystack, $always = array()) {
        return array_intersect(array_merge($needle, $always), $heystack);
    }

    /**
     * Returns correct names for week days
     */
    private function getDayNames($day, $shorten = false, $len = 1, $numeric = false) {
        $days = array(
            'sat' => array(1, 'شنبه'),
            'sun' => array(2, 'یکشنبه'),
            'mon' => array(3, 'دوشنبه'),
            'tue' => array(4, 'سه شنبه'),
            'wed' => array(5, 'چهارشنبه'),
            'thu' => array(6, 'پنجشنبه'),
            'fri' => array(7, 'جمعه')
        );

        $day = substr(strtolower($day), 0, 3);
        $day = $days[$day];

        return ($numeric) ? $day[0] : (($shorten) ? $this->substr($day[1], 0, $len) : $day[1]);
    }

    /**
     * Returns correct names for months
     */
    private function getMonthNames($month, $shorten = false, $len = 3) {
        // Convert
        $months = array(
            'فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'
        );
        $ret = $months[$month - 1];

        // Return
        return ($shorten) ? $this->substr($ret, 0, $len) : $ret;
    }

    /**
     * Converts latin numbers to farsi script
     */
    public function convertNumbers($matches, $farsiToEnglish = false) {
        $farsi_array = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
        $english_array = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');

        if ($farsiToEnglish)
        {
            return str_replace($farsi_array, $english_array, $matches);
        }
        return str_replace($english_array, $farsi_array, $matches);
    }
    
    

    /**
     * Division
     */
    private function div($a, $b) {
        return (int) ($a / $b);
    }

    /**
     * Substring helper
     */
    private function substr($str, $start, $len) {
        if (function_exists('mb_substr')) {
            return mb_substr($str, $start, $len, 'UTF-8');
        } else {
            return substr($str, $start, $len * 2);
        }
    }

    /**
     * Gregorian to Jalali Conversion
     * Copyright (C) 2000  Roozbeh Pournader and Mohammad Toossi
     */
    public function toJalali($g_y, $g_m, $g_d) {

        $g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        $j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);

        $gy = $g_y - 1600;
        $gm = $g_m - 1;
        $gd = $g_d - 1;

        $g_day_no = 365 * $gy + $this->div($gy + 3, 4) - $this->div($gy + 99, 100) + $this->div($gy + 399, 400);

        for ($i = 0; $i < $gm; ++$i)
            $g_day_no += $g_days_in_month[$i];
        if ($gm > 1 && (($gy % 4 == 0 && $gy % 100 != 0) || ($gy % 400 == 0)))
            $g_day_no++;
        $g_day_no += $gd;

        $j_day_no = $g_day_no - 79;

        $j_np = $this->div($j_day_no, 12053);
        $j_day_no = $j_day_no % 12053;

        $jy = 979 + 33 * $j_np + 4 * $this->div($j_day_no, 1461);

        $j_day_no %= 1461;

        if ($j_day_no >= 366) {
            $jy += $this->div($j_day_no - 1, 365);
            $j_day_no = ($j_day_no - 1) % 365;
        }

        for ($i = 0; $i < 11 && $j_day_no >= $j_days_in_month[$i]; ++$i)
            $j_day_no -= $j_days_in_month[$i];
        $jm = $i + 1;
        $jd = $j_day_no + 1;

        return array($jy, $jm, $jd);
    }

    /**
     * Jalali to Gregorian Conversion
     * Copyright (C) 2000  Roozbeh Pournader and Mohammad Toossi
     *
     */
    public function toGregorian($j_y, $j_m, $j_d) {

        $g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        $j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);

        $jy = $j_y - 979;
        $jm = $j_m - 1;
        $jd = $j_d - 1;
        
        if ($jm > count($j_days_in_month))
        {
            return false;
        }

        $j_day_no = 365 * $jy + $this->div($jy, 33) * 8 + $this->div($jy % 33 + 3, 4);
        for ($i = 0; $i < $jm; ++$i)
            $j_day_no += $j_days_in_month[$i];

        $j_day_no += $jd;

        $g_day_no = $j_day_no + 79;

        $gy = 1600 + 400 * $this->div($g_day_no, 146097);
        $g_day_no = $g_day_no % 146097;

        $leap = true;
        if ($g_day_no >= 36525) {
            $g_day_no--;
            $gy += 100 * $this->div($g_day_no, 36524);
            $g_day_no = $g_day_no % 36524;

            if ($g_day_no >= 365)
                $g_day_no++;
            else
                $leap = false;
        }

        $gy += 4 * $this->div($g_day_no, 1461);
        $g_day_no %= 1461;

        if ($g_day_no >= 366) {
            $leap = false;

            $g_day_no--;
            $gy += $this->div($g_day_no, 365);
            $g_day_no = $g_day_no % 365;
        }

        for ($i = 0; $g_day_no >= $g_days_in_month[$i] + ($i == 1 && $leap); $i++)
            $g_day_no -= $g_days_in_month[$i] + ($i == 1 && $leap);
        $gm = $i + 1;
        $gd = $g_day_no + 1;

        return array($gy, $gm, $gd);
    }

}
