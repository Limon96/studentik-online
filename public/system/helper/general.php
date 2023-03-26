<?php
function token($length = 32) {
	// Create random token
	$string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

	$max = strlen($string) - 1;

	$token = '';

	for ($i = 0; $i < $length; $i++) {
		$token .= $string[mt_rand(0, $max)];
	}

	return $token;
}

/**
 * Backwards support for timing safe hash string comparisons
 *
 * http://php.net/manual/en/function.hash-equals.php
 */

if(!function_exists('hash_equals')) {
	function hash_equals($known_string, $user_string) {
		$known_string = (string)$known_string;
		$user_string = (string)$user_string;

		if(strlen($known_string) != strlen($user_string)) {
			return false;
		} else {
			$res = $known_string ^ $user_string;
			$ret = 0;

			for($i = strlen($res) - 1; $i >= 0; $i--) $ret |= ord($res[$i]);

			return !$ret;
		}
	}
}

function translit($s)
{
    $s = (string) $s;
    $s = strip_tags($s);
    $s = str_replace(array("\n", "\r"), " ", $s);
    $s = preg_replace("/\s+/", ' ', $s);
    $s = trim($s);
    $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s);
    $s = strtr($s, array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>''));
    $s = preg_replace("/[^0-9a-z-_ .]/i", "", $s);
    $s = str_replace(" ", "-", $s);
    return $s;
}

function format_date($str, $format = "d.m.Y") {

    if (is_numeric($str)) {
        $str = date("Y-m-d H:i:s", $str);
    }

    if ($format == "full_datetime") {
        $month = ['', 'Янв', 'Фев', 'Мар', 'Апр', 'Мая', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'];

        $ts = strtotime($str);

        $m = $month[date('n', $ts)];

        return str_replace('%', $m, date('j % Y в H:i', $ts));
    } elseif ($format == "full_date") {
        $month = ['', 'Янв', 'Фев', 'Мар', 'Апр', 'Мая', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'];

        $ts = strtotime($str);

        $m = $month[date('n', $ts)];

        return str_replace('%', $m, date('j % Y', $ts));
    } elseif ($format == "dMt") {
        $month = ['', 'Января', 'Февраля', 'Марта', 'Апреля', 'Мая', 'Июня', 'Июля', 'Августа', 'Сентября', 'Октября', 'Ноября', 'Декабря'];

        $ts = strtotime($str);

        $m = $month[date('n', $ts)];

        return str_replace('%', $m, date('j % H:i', $ts));
    } elseif ($format == "dM") {
        $month = ['', 'Января', 'Февраля', 'Марта', 'Апреля', 'Мая', 'Июня', 'Июля', 'Августа', 'Сентября', 'Октября', 'Ноября', 'Декабря'];

        $ts = strtotime($str);

        $m = $month[date('n', $ts)];

        return str_replace('%', $m, date('j %', $ts));
    } else {
        return date($format, strtotime($str));
    }
}

function format_size( $bytes, $decimals = 0 ) {
    $base = log($bytes, 1024);
    $suffixes = array('', 'Kбайт', 'Mбайт', 'Гбайт', 'Tбайт');

    return round(pow(1024, $base - floor($base)), $decimals) .' '. $suffixes[floor($base)];
}

function dd(...$args) {
    foreach ($args as $arg) {
        print_r($arg);
        print_r("\n\n");
    }

    exit();
}

function num_word($value, $words, $show = true)
{
    $num = $value % 100;
    if ($num > 19) {
        $num = $num % 10;
    }

    $out = ($show) ?  $value . ' ' : '';
    switch ($num) {
        case 1:  $out .= $words[0]; break;
        case 2:
        case 3:
        case 4:  $out .= $words[1]; break;
        default: $out .= $words[2]; break;
    }

    return $out;
}

function format_number($number) {
    if ($number > 1000000000000) {
        $number = round($number / 1000000000000, 1) . 'T';
    } elseif ($number > 1000000000) {
        $number = round($number / 1000000000, 1) . 'B';
    } elseif ($number > 1000000) {
        $number = round($number / 1000000, 1) . 'M';
    } elseif ($number > 1000) {
        $number = round($number / 1000, 1) . 'K';
    }

    return $number;
}

function seo_translit($value) {
    $converter = array(
        'а' => 'a',    'б' => 'b',    'в' => 'v',    'г' => 'g',    'д' => 'd',
        'е' => 'e',    'ё' => 'e',    'ж' => 'zh',   'з' => 'z',    'и' => 'i',
        'й' => 'y',    'к' => 'k',    'л' => 'l',    'м' => 'm',    'н' => 'n',
        'о' => 'o',    'п' => 'p',    'р' => 'r',    'с' => 's',    'т' => 't',
        'у' => 'u',    'ф' => 'f',    'х' => 'h',    'ц' => 'c',    'ч' => 'ch',
        'ш' => 'sh',   'щ' => 'sch',  'ь' => '',     'ы' => 'y',    'ъ' => '',
        'э' => 'e',    'ю' => 'yu',   'я' => 'ya',
    );

    $value = mb_strtolower($value);
    $value = strtr($value, $converter);
    $value = mb_ereg_replace('[^-0-9a-z]', '-', $value);
    $value = mb_ereg_replace('[-]+', '-', $value);
    $value = trim($value, '-');

    return $value;
}
