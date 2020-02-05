<?php
/**
 * Макрос для групповой операции: Изменение статуса
 *
 * @package    DIAFAN.CMS
 * @author     diafan.ru
 * @version    6.0
 * @license    http://www.diafan.ru/license.html
 * @copyright  Copyright (c) 2003-2019 OOO «Диафан» (http://www.diafan.ru/)
 */

if (!defined('DIAFAN')) {
    $path = __FILE__;
    while (!file_exists($path . '/includes/404.php')) {
        $parent = dirname($path);
        if ($parent == $path) exit;
        $path = $parent;
    }
    include $path . '/includes/404.php';
}

/**
 * Order_admin_group_status
 */
class Order_admin_group_download extends Diafan
{
    /**
     * Возвращает настройки
     *
     * @param string $value последнее выбранное групповое действие
     * @return array|false
     */
    public function show($value)
    {
        $config = array(
            'name' => 'Сформировать документ',
        );

        return $config;
    }

    /**
     * Изменение статуса
     *
     * @return void
     */
    public function action()
    {
        if (empty($_POST["ids"]))
            return;

        $ids = $this->diafan->filter($_POST["ids"], "integer");

        if (count($ids) == 1) {
            $file   = $this->getFile($ids[0]);
            $params = $this->getOrderParamElement($ids[0]);
            //print_r($params);
            //exit();
            require $_SERVER["DOCUMENT_ROOT"] . '/custom/custom28_10_2019_18_12/plugins/vendor/autoload.php';

            $phpWord = new PhpOffice\PhpWord\PhpWord();
            $doc     = new PhpOffice\PhpWord\TemplateProcessor('https://' . $_SERVER['HTTP_HOST'] . '/attachments/get/' . $file["id"] . '/' . $file["name"]);

            $arValues = array(
                'boss_name'  => $params[13]["value"],
                'boss_phone' => $params[25]["value"],
                'date'       => $params[17]["value"],
            );
            $doc->setValues($arValues);

            $doc->saveAs($_SERVER["DOCUMENT_ROOT"] . '/test3.docx');
        }
    }

    private function getFile($id)
    {
        return DB::query_fetch_array("
                SELECT file.id, file.name FROM {shop_order} AS orders
                RIGHT JOIN {shop_order_goods} AS goods ON goods.order_id=orders.id
                RIGHT JOIN {attachments} AS file ON goods.good_id=file.element_id
                WHERE orders.id='%d' AND file.param_id='%d'
            ", $id, 5);
    }

    private function getOrderParamElement($id)
    {
        return DB::query_fetch_key("SELECT * FROM {shop_order_param_element} WHERE element_id='%d' AND trash='0'", $id, "param_id");
    }
}