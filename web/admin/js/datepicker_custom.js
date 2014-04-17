// Fix: Add "afterShow" event to datepicker core.
// http://bugs.jqueryui.com/ticket/6885
$.datepicker._updateDatepicker_original = $.datepicker._updateDatepicker;
$.datepicker._updateDatepicker = function(inst) {
    $.datepicker._updateDatepicker_original(inst);
    var afterShow = this._get(inst, 'afterShow');
    if (afterShow)
        afterShow.apply((inst.input ? inst.input[0] : null));  // trigger custom callback
}


$(document).on('ready', function(){

    var defaultDate;

    // Crea un jquery calendar.
    $(".calendar").datepicker({
        showOn: "button",
        showOptions: {
            direction: 'up'
        },
        //buttonImage: website+"/img/calendar.png",
        buttonImageOnly: true,
        minDate: 4,
        dateFormat: 'dd/mm/yy',
        defaultDate: new Date(2013, 11, 2),
        locale: 'es',
        hideIfNoPrevNext: true,
        beforeShow: function(input, inst) {},
        beforeShowDay: function(date){
            var valid_date  = true,
                current_day = date.getDay(),
                current_date = date.getDate(),
                current_month = date.getMonth()
            minDate = new Date();

            // sumo 4 días al mínimo día seleccionable
            minDate.setDate( minDate.getDate()+4 );

            // sábados y domingos
            if ( current_day == 6 || date.getDay() == 0 ) {
                valid_date = false;
            }
            // 6 de diciembre
            if ( current_date == 6 & current_month == 11 ) {
                valid_date = false;
            }

            // 25 de diciembre
            if ( current_date == 25 & current_month == 11 ) {
                valid_date = false;
            }
            // 26 de diciembre
            if ( current_date == 26 & current_month == 11 ) {
                valid_date = false;
            }

            // 6 de enero
            if ( current_date == 6 & current_month == 0 ) {
                valid_date = false;
            }

            if ( date.getTime() < minDate.getTime() ) {
                valid_date = false;
            }

            // seteo primer fecha seleccionable
            if ( defaultDate == null && valid_date === true ) {
                defaultDate = date;
            }

            return [
                valid_date,
                valid_date ? 'ui-datepicker-selectable' : ''
            ];
        },
        afterShow: function(input, inst) {
            if ( ! defaultDate ) {
                //$(".calendar").datepicker("option", "defaultDate", "+1m");
                //$(".calendar").datepicker("option", "setDate", defaultDate);
                defaultDate = true;
            }
            //$(".calendar").datepicker("option", "setDate", defaultDate);
        }
    });
});