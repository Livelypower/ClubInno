$(document).ready(function(){
    var datePickerObj = {
        format: 'mm/dd/yyyy',
        firstDay: 1,
        i18n: {
            months: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            monthsShort: ['Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aou', 'Sep', 'Oct', 'Nov', 'Dec'],
            weekdays: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi' ],
            weekdaysShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
            weekdaysAbbrev: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
            cancel: 'Fermer',
            clear: 'Réinitialiser'
        }
    };
    $('select').formSelect();
    $('.modal').modal();
    if($('.firstDate').val() !== null){
        restrictSecondDate(datePickerObj);
    }else{
        $('.firstDate').datepicker(datePickerObj);
    }

    if($('.secondDate').val() !== null){
        restrictFirstDate(datePickerObj);
    }else{
        $('.secondDate').datepicker(datePickerObj);
    }


    $('.timepicker').timepicker({
        twelveHour: false
    });

    $('.carousel').carousel();
    var elems = document.querySelectorAll('.fixed-action-btn');
    var instances = M.FloatingActionButton.init(elems, {
        direction: 'left'
    });

    $('.firstDate').change(restrictSecondDate(datePickerObj));

    $('.secondDate').change(restrictFirstDate(datePickerObj));

});

function restrictFirstDate(datePickerObj){
    var restrictedDateObj = jQuery.extend({}, datePickerObj);
    var restrictedDate = $(".secondDate").val().split("/");
    restrictedDateObj.maxDate = new Date(restrictedDate[2], restrictedDate[0]-1, restrictedDate[1]);

    $(".firstDate").datepicker(restrictedDateObj);
}

function restrictSecondDate(datePickerObj){
    var restrictedDateObj = jQuery.extend({}, datePickerObj);
    var restrictedDate = $(".firstDate").val().split("/");
    restrictedDateObj.minDate = new Date(restrictedDate[2], restrictedDate[0]-1, restrictedDate[1]);

    $(".secondDate").datepicker(restrictedDateObj);
}

