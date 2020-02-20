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
            $files  = $this->getFile($ids[0]);
            $params = $this->getOrderParamElement($ids[0]);
            require $_SERVER["DOCUMENT_ROOT"] . '/custom/custom28_10_2019_18_12/plugins/vendor/autoload.php';
            $phpWord = new PhpOffice\PhpWord\PhpWord();
            $doc     = new PhpOffice\PhpWord\TemplateProcessor('https://' . $_SERVER['HTTP_HOST'] . '/attachments/get/' . $files["id"] . '/' . $files["name"]);

            $arValues = array(
                'boss_name'  => (!empty($params[13]["value"]) ? $params[13]["value"] : ''),
                'boss_phone' => (!empty($params[25]["value"]) ? $params[25]["value"] : ''),
                'date'       => date("d.m.y", strtotime($params[5]["value"])) . ' - ' . date("d.m.y", strtotime($params[17]["value"])),
                'work'       => (!empty($params[19]["value"] == 9) ? 'Разрешение на проведение работ' : 'Заявка на ввоз/вывоз'),
                'extra'      => (!empty($params[24]["value"]) ? $params[24]["value"] : ''),
            );
            $doc->setValues($arValues);

            $names = [];
            if (!empty($params[28]["value"])) {
                $names = explode("<br />", $params[28]["value"]);
            }
            $passports = [];
            if (!empty($params[31]["value"])) {
                $passports = explode("<br />", $params[31]["value"]);
            }
            $values = [];
            if (count($names) > 0) {
                for ($i = 0; $i < count($names); $i++) {
                    $values[] = ['staff_number' => $i + 1, 'staff_fio' => $names[$i], 'staff_passport' => $passports[$i]];
                }
            }
            $doc->cloneRowAndSetValues('staff_number', $values);

            $doc->saveAs($_SERVER["DOCUMENT_ROOT"] . '/test3.docx');
            $this->downloadFile($_SERVER["DOCUMENT_ROOT"] . '/test3.docx');
        }
        else {
            //город тц трекер
            $orders = $this->getOrdersInfo($ids);

            // Проверка на одинаковый товары и трекер
            $good_id = 0;
            $k = 0;
            $order_id = 0;
            foreach($orders as $i => $order) {
                if ($k == 0) {
                    $good_id = $order["id"];
                    $order_id = $i;
                }
                else {
                    if ($good_id == $order["id"]) continue;
                    else {
                        echo 'Товары разные';
                        exit();
                    }
                }
                $k++;
            }

            // Формируем файл
            if (!empty($order_id)) {
                $files = $this->getFile($order_id);
                $params = $this->getOrdersParamElement($ids);

                require $_SERVER["DOCUMENT_ROOT"] . '/custom/custom28_10_2019_18_12/plugins/vendor/autoload.php';
                $phpWord = new PhpOffice\PhpWord\PhpWord();
                $doc     = new PhpOffice\PhpWord\TemplateProcessor('https://' . $_SERVER['HTTP_HOST'] . '/attachments/get/' . $files["id"] . '/' . $files["name"]);

                $names = [];
                $passports = [];
                $j = 0;
                $first_id = '';
                foreach ($ids as $id) {
                    if (!empty($params[$id][28]["value"])) {
                        $names_array = [];
                        $passports_array = [];
                        $names_array = explode("<br />", $params[$id][28]["value"]);
                        $passports_array = explode("<br />", $params[$id][31]["value"]);
                        $names[] = $names_array[0];
                        $names[] = $names_array[1];
                        $passports[] = $passports_array[0];
                        $passports[] = $passports_array[1];
                        unset($names_array);
                        unset($passports_array);
                        $j++;
                    }
                }
                $values = [];
                $staff_fio = [];
                if (count($names) > 0) {
                    for ($i = 0; $i < count($names); $i++) {
                        if (!in_array($names[$i], $staff_fio)) {
                            $staff_fio[] = $names[$i];
                            $values[] = ['staff_number' => $i + 1, 'staff_fio' => $names[$i], 'staff_passport' => $passports[$i]];
                        }
                    }
                }
                $doc->cloneRowAndSetValues('staff_number', $values);

                foreach ($params as $param) {
                    $arValues = array(
                        'boss_name'  => (!empty($param[13]["value"]) ? $param[13]["value"] : ''),
                        'boss_phone' => (!empty($param[25]["value"]) ? $param[25]["value"] : ''),
                        'date'       => date("d.m.y", strtotime($param[5]["value"])) . ' - ' . date("d.m.y", strtotime($param[17]["value"])),
                        'work'       => (!empty($param[19]["value"] == 9) ? 'Разрешение на проведение работ' : 'Заявка на ввоз/вывоз'),
                        'extra'      => (!empty($param[24]["value"]) ? $param[24]["value"] : ''),
                    );
                    break;
                }
                $doc->setValues($arValues);


                $doc->saveAs($_SERVER["DOCUMENT_ROOT"] . '/test3.docx');
                $this->downloadFile($_SERVER["DOCUMENT_ROOT"] . '/test3.docx');
            }
        }
    }

    // Получение файла
    private function getFile($id)
    {
        return DB::query_fetch_array("
                SELECT file.id, file.name FROM {shop_order} AS orders
                RIGHT JOIN {shop_order_goods} AS goods ON goods.order_id=orders.id
                RIGHT JOIN {attachments} AS file ON goods.good_id=file.element_id
                WHERE orders.id='%d' AND file.param_id='%d'
            ", $id, 5);
    }

    // Получение параметров списка товаров
    private function getOrdersParamElement($ids) {
        $params = [];
        foreach ($ids as $id) {
            $params[$id] = $this->getOrderParamElement($id);
        }
        return $params;
    }

    // Получение параметров
    private function getOrderParamElement($id)
    {
        return DB::query_fetch_key("SELECT * FROM {shop_order_param_element} WHERE element_id='%d' AND trash='0'", $id, "param_id");
    }

    // Загрузка файла
    private function downloadFile($file)
    {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit();
    }

    // Получение заказов
    private function getOrdersInfo($ids) {
        return DB::query_fetch_key("
                SELECT orders.id AS order_id, goods.good_id AS id
                FROM {shop_order} AS orders
                RIGHT JOIN {shop_order_goods} AS goods ON orders.id=goods.order_id
                WHERE orders.id IN (".implode(",", $ids).") 
                AND orders.trash='0' AND goods.trash='0'
                 ",
            "order_id");
    }
}