<?php
/**
 * Шаблонный тег: выводит основной контент страницы: заголовка (если не запрещен его вывод в настройке странице «Не показывать заголовок»), текста страницы и прикрепленного модуля. Заменяет три тега: show_h1, show_text, show_module.
 *
 * @package    DIAFAN.CMS
 * @author     diafan.ru
 * @version    6.0
 * @license    http://www.diafan.ru/license.html
 * @copyright  Copyright (c) 2003-2018 OOO «Диафан» (http://www.diafan.ru/)
 */

if (! defined('DIAFAN'))
{
	$path = __FILE__;
	while(! file_exists($path.'/includes/404.php'))
	{
		$parent = dirname($path);
		if($parent == $path) exit;
		$path = $parent;
	}
	include $path.'/includes/404.php';
}

function calculateNextStep() {
    $rows = DB::query_fetch_all("SELECT * FROM {reglament} WHERE trash='0' AND next_step='0'");
    foreach ($rows as $row) {
        $reglament = explode("|", $row["reglament"]);
        $next = 0;
        if($reglament[0] == 't1') {
            // Таб дни
            $t1_time = $reglament[3];

            $today_date = date("Y-m-d", time());
            $next = strtotime($today_date.' '.$t1_time);
        }
        elseif ($reglament[0] == 't2') {
            // Таб недели
            $t2_week = intval($reglament[1]);
            $t2_days = $reglament[2];
            $t2_time = $reglament[3];
            $t2_days_arr = explode(",", $t2_days);

            if(!empty($t2_days)) {
                // Выбраны дни недели
                $future_day = 0;
                $closed_time = strtotime(date("Y-m-d", time())." ".$t2_time);
                $start_number_of_week = date("W", $closed_time);
                while($future_day == 0) {

                    $current_day_of_week = date("N", $closed_time);
                    foreach ($t2_days_arr as $day) {
                        if($current_day_of_week == $day) {
                            if($closed_time > time()) {
                                $future_day = $closed_time;
                            }
                        }
                    }
                    $closed_time = strtotime("+1 days", $closed_time);
                }
                $next = $future_day;
            }
            else {
                // Не выбраны дни недели
                $last_monday = strtotime("next Monday");
                $next = strtotime(date("Y-m-d", $last_monday).' '.$t2_time);
            }
        }
        else {
            // Таб месяцы
            $radio = $reglament[1];
            if($radio == "r1") {
                $number = intval($reglament[2]);
                $month = intval($reglament[3]);
                $r3_time = $reglament[4];

                $current_month = date("Y-m-", time()).($number < 10 ? "0".$number : $number)." ".$r3_time;
                if(time() > strtotime($current_month)) {
                    $next = strtotime("+1 month", strtotime($current_month));
                }
                else {
                    $next = strtotime($current_month);
                }
            }
            else {
                $number = $reglament[2];
                $day = $reglament[3];
                $month = $reglament[4];
                $t3_time = $reglament[5];

                $number_name = '';
                switch ($number) {
                    case 1:
                        $number_name = "first";
                        break;
                    case 2:
                        $number_name = "second";
                        break;
                    case 3:
                        $number_name = "third";
                        break;
                    case 4:
                        $number_name = "fourth";
                        break;
                    case 5:
                        $number_name = "last";
                        break;
                }

                $day_name = '';
                switch ($day) {
                    case 1:
                        $day_name = "Monday";
                        break;
                    case 2:
                        $day_name = "Tuesday";
                        break;
                    case 3:
                        $day_name = "Wednesday";
                        break;
                    case 4:
                        $day_name = "Thursday";
                        break;
                    case 5:
                        $day_name = "Friday";
                        break;
                    case 6:
                        $day_name = "Saturday";
                        break;
                    case 7:
                        $day_name = "Sunday";
                        break;
                }

                $this_month_date_text = $number_name.' '.$day_name.' of this month';
                $this_month_date = strtotime($this_month_date_text, time());
                $this_month_time = strtotime(date("Y-m-d", $this_month_date)." $t3_time");
                if(time() > $this_month_time) {
                    $next_month_date_text = $number_name.' '.$day_name.' of next month';
                    $next_month_date = strtotime($next_month_date_text, time());
                    $next_month_time = strtotime(date("Y-m-d", $next_month_date)." $t3_time");
                    $next = $next_month_time;
                }
                else {
                    $next = $this_month_time;
                }
            }
        }
        if (!empty($next))
            setNextTime($next, $row["id"]);
    }
}

function setNextTime($time, $id) {
    echo date("Y-m-d H:i:s", $time);
    //DB::query("UPDATE {reglament} SET next_step=%d WHERE id=%d", $time, $id);
}

function init() {

    // Расчет следующего шага
    calculateNextStep();

    // Пересчет следующего шага
    //recalcNextStep();

}

init();