function init () {
    $(document).ready(function () {
        $('#dateRange').daterangepicker({
            opens: 'left',
            drops: 'up'
        }, function(start, end, label) {
            $('#dateFrom').val(start.format('YYYY-MM-DD'));
            $('#dateBefore').val(end.format('YYYY-MM-DD'));
            // console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        });
    });
}

export default {
    init
}
