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

if(! empty($result["error"]))
{
	echo '<p>'.$result["error"].'</p>';
	return;
}

if(empty($result["ajax"]))
{
	echo '<div class="js_shop_list shop_list">';
}

//вывод подкатегории
if(! empty($result["children"]))
{
	foreach($result["children"] as $child)
	{
		echo '<div class="shop_cat_link">';

		//вывод изображений подкатегории
		if(! empty($child["img"]))
		{
			echo '<div class="shop_cat_img">';
			foreach($child["img"] as $img)
			{
				switch ($img["type"])
				{
					case 'animation':
						echo '<a href="'.BASE_PATH.$img["link"].'" data-fancybox="gallery'.$child["id"].'shop">';
						break;
					case 'large_image':
						echo '<a href="'.BASE_PATH.$img["link"].'" rel="large_image" width="'.$img["link_width"].'" height="'.$img["link_height"].'">';
						break;
					default:
						echo '<a href="'.BASE_PATH_HREF.$img["link"].'">';
						break;
				}
				echo '<img src="'.$img["src"].'" width="'.$img["width"].'" height="'.$img["height"].'" alt="'.$img["alt"].'" title="'.$img["title"].'">'
				. '</a> ';
			}
			echo '</div>';
		}

		//название и ссылка подкатегории
		echo '<a href="'.BASE_PATH_HREF.$child["link"].'">'.$child["name"].' ('.$child["count"].')</a>';		

		//краткое описание подкатегории
		if($child["anons"])
		{
			echo '<div class="shop_cat_anons">'.$child['anons'].'</div>';
		}
		echo '</div>';

		//вывод списка товаров подкатегории
		if(! empty($child["rows"]))
		{
			$res = $result; unset($res["show_more"]);
			$res["rows"] = $child["rows"];
                        echo '<div class="shop-pane">';
			echo $this->get($result["view_rows"], 'shop', $res);
                        echo '</div>';
		}
	}
}

//вывод списка товаров
if(! empty($result["rows"]))
{

	echo '<div class="shop-pane">';
	echo $this->get($result["view_rows"], 'shop', $result);
	echo '</div>';
}
else {
    echo 'Торговых центров не обнаружено';
}

//постраничная навигация
if(! empty($result["paginator"]))
{
	echo $result["paginator"];
}

if (!empty($result["rows"]) && empty($result["hide_compare"]))
{
	echo $this->get('compared_goods_list', 'shop', array("site_id" => $this->diafan->_site->id, "shop_link" => $result['shop_link']));
}

//вывод комментариев ко всей категории товаров (комментарии к конкретному товару в функции id())
if(! empty($result["comments"]))
{
	echo $result["comments"];
}

if(empty($result["ajax"]))
{
	echo '</div>';
}