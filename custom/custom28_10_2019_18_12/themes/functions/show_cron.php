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

            $t1_day = $reglament[1];
            $t1_month = $reglament[2];
            $t1_time = $reglament[3];

            // Каждый день раз в несколько месяцев
            if($t1_day > 1) {
                $last_date = ($row["last_action"] ? $row["last_action"] : $row["created"]);
                echo $last_date;
            }
            else {
                // Каждый день
                $today_date = date("Y-m-d", time());
                $today_time = strtotime($today_date.' '.$t1_time);

                if(time() > $today_time) $next = strtotime("+1 days", $today_time);
                else $next = $today_time;
            }
        }
        elseif ($reglament[0] == 't2') {
            // Таб недели
            echo 't2';
        }
        else {
            // Таб месяцы
            echo 't3';
        }
        if (!empty($next))
            setNextTime($next, $row["id"]);
    }
}

function setNextTime($time, $id) {
    //DB::query("UPDATE {reglament} SET next_step=%d WHERE id=%d", $time, $id);
}

function init() {

    // Расчет следующего шага
    calculateNextStep();


}

init();