    <script src="{{ asset('js/global.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
    <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery('.datepicker').datepicker();

            jQuery.datepicker.regional['fr'] = {
                closeText: 'Fermer',
                prevText: '<Préc',
                nextText: 'Suiv>',
                currentText: 'Courant',
                monthNames: ['Janvier','Février','Mars','Avril','Mai','Juin', 'Juillet','Août','Septembre','Octobre','Novembre','Décembre'],
                monthNamesShort: ['Jan','Fév','Mar','Avr','Mai','Jun','Jul','Aoû','Sep','Oct','Nov','Déc'],
                dayNames: ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'],
                dayNamesShort: ['Dim','Lun','Mar','Mer','Jeu','Ven','Sam'],
                dayNamesMin: ['Di','Lu','Ma','Me','Je','Ve','Sa'],
                weekHeader: 'Sm',
                dateFormat: 'dd/mm/yy',
                firstDay: 1,
                isRTL: false,
                showMonthAfterYear: false,
                yearSuffix: ''
            };
            jQuery.datepicker.setDefaults(jQuery.datepicker.regional['fr']);
        });
    </script>
</body>
</html>