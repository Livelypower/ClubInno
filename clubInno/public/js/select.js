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
    $('.firstDate').datepicker(datePickerObj);
    $('.secondDate').datepicker(datePickerObj);

    $('.timepicker').timepicker({
        twelveHour: false
    });

    $('.carousel').carousel();
    var elems = document.querySelectorAll('.fixed-action-btn');
    var instances = M.FloatingActionButton.init(elems, {
        direction: 'left'
    });

    $('.firstDate').change(function(){
        var restrictedDateObj = jQuery.extend({}, datePickerObj);
        var restrictedDate = $(this).val().split("/");
        restrictedDateObj.minDate = new Date(restrictedDate[2], restrictedDate[0]-1, restrictedDate[1]);

        $(".secondDate").datepicker(restrictedDateObj);
    });

    $('.secondDate').change(function(){
        var restrictedDateObj = jQuery.extend({}, datePickerObj);
        var restrictedDate = $(this).val().split("/");
        restrictedDateObj.maxDate = new Date(restrictedDate[2], restrictedDate[0]-1, restrictedDate[1]);

        $(".firstDate").datepicker(restrictedDateObj);
    });

});

