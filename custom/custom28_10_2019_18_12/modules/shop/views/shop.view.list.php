<?php
/**
 * Шаблон списка товаров
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


$address = DB::query_fetch_array("
                    SELECT rewrite FROM {rewrite} AS r
                    LEFT JOIN {shop} AS s ON s.id=r.element_id
                    WHERE r.module_name='shop'
                    AND r.element_type='element'
                    AND r.trash='0'
                    AND s.cat_id='%d'
                    ORDER BY r.id
                    ", $result["id"]);

if (!empty($address["rewrite"])) $this->diafan->redirect($address["rewrite"]);