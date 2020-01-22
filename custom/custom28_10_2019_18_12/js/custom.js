$(function() {
    var form = $(".js_cart_one_click_form");

    // Измененение трекера
    form.find('.form-group19 select').change(function () {
        switch ($(this).val()) {
            case "8":
                form.removeClass("tracker-no-select");
                form.find('.vvoz-hide').slideDown();
                form.find('.agree-hide').slideUp();
                break;
            case "9":
                form.removeClass("tracker-no-select");
                form.find(".form-group2 select :first").prop("selected", true);
                form.find('.vvoz-hide').slideUp();
                form.find('.agree-hide').slideDown();
                break;
            default:
                form.addClass("tracker-no-select");
                break;
        }
    });

    // Вид работ
    form.find('.form-group2 select').change(function () {
        switch ($(this).val()) {
            case "4": // Перемещение
                form.find('.peremesh').slideDown();
                form.find('.zamena').slideUp();
                form.find('.vvoz').slideUp();
                form.find('.vyvoz').slideUp();
                form.find('.novoform').slideUp();
                form.find('.form-group4').slideUp();
                form.find('.form-group30').slideUp();
                form.find('.form-group12').slideDown();
                break;
            case "5": // Замена
            case "14": // Ввоз
                form.find('.peremesh').slideUp();
                form.find('.zamena').slideDown();
                form.find('.vvoz').slideDown();
                form.find('.vyvoz').slideUp();
                form.find('.novoform').slideUp();
                form.find('.form-group4').slideDown();
                form.find('.form-group30').slideDown();
                form.find('.form-group12').slideUp();
                break;
            case "15": // Вывоз
                form.find('.peremesh').slideUp();
                form.find('.zamena').slideUp();
                form.find('.vvoz').slideUp();
                form.find('.vyvoz').slideDown();
                form.find('.novoform').slideUp();
                form.find('.form-group4').slideUp();
                form.find('.form-group30').slideDown();
                form.find('.form-group12').slideUp();
                break;
            case "6": // Новое оформление
                form.find('.peremesh').slideUp();
                form.find('.zamena').slideUp();
                form.find('.vvoz').slideUp();
                form.find('.vyvoz').slideUp();
                form.find('.novoform').slideDown();
                form.find('.form-group4').slideDown();
                form.find('.form-group30').slideUp();
                form.find('.form-group12').slideDown();
                break;
            case "":
            default:
                form.find('.peremesh').slideUp();
                form.find('.zamena').slideUp();
                form.find('.vvoz').slideUp();
                form.find('.vyvoz').slideUp();
                form.find('.novoform').slideUp();
                form.find('.form-group4').slideDown();
                form.find('.form-group30').slideUp();
                form.find('.form-group12').slideDown();
                break;
        }
    });

    // Вызываем события при загрузке
    form.find('.form-group19 select').change();
    form.find('.form-group2 select').change();

    // Установка даты
    var date_from = form.find(".form-group5 input");
    var date_to = form.find(".form-group17 input");
    date_from.datepicker({minDate: 3});
    date_to.datepicker({minDate: 4});
    date_from.datepicker('setDate', "today");
    date_to.datepicker('setDate', "today");
    date_from.change(function () {
        var selected = $(this).val().split(".");
        var next = new Date(selected[2], selected[1] - 1, selected[0]);
        next.setDate(next.getDate() + 1);
        date_to.datepicker('setDate', next);
    });

    // Блокировка даты после
    date_to.attr("disabled", "disabled");

    // Загрузка файлов
    $(".inpfiles").change(function (event) {
        $(this).parent().find(".count-drop-files").text("Выбрано файлов: " + event.target.files.length);
    });

    //Габариты
    $(document).on("change", ".gabarity input", function () {
        var data = '';
        $(".gabarity").each(function (i) {
            data += i + ") " + $(this).find(".gabarits").val() + "; " + $(this).find(".ves").val() + "; " + $(this).find(".moshnost").val() + "\n"
        });
        form.find(".form-group27 textarea").val(data);
    });

    // Клонирование полей
    $(".add-fields").click(function () {
        $(this).parent().clone().appendTo(".order_form_param27");
        $(".order_form_param27 .gabarity:last-child input").val("");
        $(".order_form_param27 .gabarity:last-child .add-fields").remove();
    });

});

diafan_ajax.before['cart_one_click'] = function (form) {

    console.log(form);

    return false;
};