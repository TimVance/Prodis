<?php

if(! defined("DIAFAN"))
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
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <insert name="show_head">
        <link rel="shortcut icon" href="<insert name="path">favicon.ico" type="image/x-icon">
        <insert name="show_css" files="main.css">
            </head>
<body>


    <?php

    $address = DB::query_fetch_array("
                    SELECT r.rewrite FROM {rewrite} AS r
                    LEFT JOIN {shop} AS s
                    ON s.id=r.element_id
                    WHERE r.module_name='shop'
                    AND r.element_type='element'
                    AND r.trash='0'
                    AND s.cat_id='2'
                    ORDER BY r.id
                    ");

    if (!empty($address["rewrite"])) $this->diafan->redirect($address["rewrite"]);

    ?>


    <insert name="show_js">
    <script type="text/javascript" asyncsrc="<insert name="custom" path="js/main.js" absolute="true" compress="js">" charset="UTF-8"></script>


    <!--<insert name="show_privacy" hash="false" text="">-->
    <insert name="show_include" file="counters">

</body>
</html>