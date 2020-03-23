$(function() {

    // Табы
    $(".tabs-period > span").click(function() {
        $(".tabs-period > span, .tabs-wrapper > div").removeClass("active");
        $(this).addClass("active");
        $(".tabs-wrapper").find("." + $(this).attr("data-class")).addClass("active");
    });

    // Дни
    function days() {
        let day = 0;
        let month = 0;
        let time = 0;
        let text = 'Задача повторяется';

        day = parseInt($(".tabs-wrapper .days .day").val());
        month = $(".tabs-wrapper .days .month").val();
        time = $(".tabs-wrapper .days .timepickerr").val();

        if (day > 1) text += ' каждый ' + day + ' день';
        else text += ' каждый день';

        if(month != 0) {
            if (month > 1) text += ', раз в ' + month + ' месяца';
            else text += '';
        }

        text += ' в ' + time;

        $(".period-text").text(text);
        $("input[name='reglament']").val('t1|' + day + '|' + month + '|' + time);
    }
    $(".tabs-wrapper .days input").change(days);
    $(".tabs-period .days").click(days);
    days();

    // Недели
    function weeks() {
        let week = parseInt($(".tabs-wrapper .weeks .week").val());
        let weeks = $(".tabs-wrapper .weeks .weeks-day input:checked");
        let time = $(".tabs-wrapper .weeks .timepickerr").val();
        let text = 'Задача повторяется';
        let weekstext = '';
        let weeknumbers = '';

        if (weeks.length) {
            weeks.each(function(index) {
                if (index == 0) {
                    weekstext += $(this).attr("data-name");
                    weeknumbers += $(this).val();
                }
                else {
                    weekstext += ', ' + $(this).attr("data-name");
                    weeknumbers += ',' + $(this).val();
                }
            });
        }

        if(week > 1) text += ' каждую ' + week + ' неделю';
        else text += ' каждую неделю';

        if(weekstext != '') {
            weekstext = '(' + weekstext +  ')';
            text += weekstext;
        }

        text += ' в ' + time;

        $(".period-text").text(text);
        $("input[name='reglament']").val('t2|' + week + '|' + weeknumbers + '|' + time);
    }
    $(".tabs-period .weeks").click(weeks);
    $(".tabs-wrapper .weeks input").change(weeks);

    // Месяцы
    function months() {
        if ($(this).attr("data-radio")) {
            $(".tabs-wrapper > .months > div").removeClass("active");
            $(".tabs-wrapper .months input[type='radio']").prop('checked', false);
            $(this).prop("checked", true).parent().parent().addClass("active");
        }

        let text = 'Задача повторяется';
        let radio = $(".tabs-wrapper .months input[type='radio']:checked").attr("data-radio");
        let time = $(".tabs-wrapper .months .timepickerr").val();

        if (radio == 1) {
            let number = parseInt($(".number-month .number").val());
            let month = parseInt($(".number-month .month").val());

            text += ' каждое ' + number + ' число';
            if(month > 1) text += ' каждого ' + month + ' месяца';
            else text += ' каждого месяца';

            $("input[name='reglament']").val('t3|r' + radio + '|' + number + '|' + month + "|" + time);
        }
        else {
            let day = $(".number-week .day-week option:selected").val();
            let name = $(".number-week .name-week option:selected").val();
            let dayTitle = $(".number-week .day-week option:selected").text().toLowerCase();
            let nameTitle = $(".number-week .name-week option:selected").text().toLowerCase();
            let month = $(".number-week .month").val();
            let ever = 'каждый';

            if (name == 3 || name == 5 || name == 6) {
                ever = 'каждую';

                if(day == 1) dayTitle = 'первую';
                if(day == 2) dayTitle = 'вторую';
                if(day == 3) dayTitle = 'третью';
                if(day == 4) dayTitle = 'четвертую';
                if(day == 5) dayTitle = 'последнюю';

                if(name == 3) nameTitle = 'среду';
                if(name == 5) nameTitle = 'пятницу';
                if(name == 6) nameTitle = 'субботу';
            }
            if (name == 7) {
                ever = 'каждое';
                if(day == 1) dayTitle = 'первое';
                if(day == 2) dayTitle = 'второе';
                if(day == 3) dayTitle = 'третье';
                if(day == 4) dayTitle = 'четвертое';
                if(day == 5) dayTitle = 'последнее';
            }

            text += ' ' + ever + ' ' + dayTitle;
            text += ' ' + nameTitle;

            if(month > 1) text += ' каждого ' + month + ' месяца';
            else text += ' каждого месяца';

            $("input[name='reglament']").val('t3|r' + radio + '|' + day + '|' + name + "|" + '|' + month + '|' + time);
        }

        text += ' в ' + time;

        $(".period-text").text(text);
    }
    $(".tabs-period .months").click(months);
    $(".tabs-wrapper .months input").change(months);
    $(".tabs-wrapper .months select").change(months);

});