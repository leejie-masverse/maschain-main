$(document).ready(() => {

    var $target = $('input:radio[name=partner_level]:checked').val();
    if($target==='master-partner'){
        $("option[data-partner=partner]").hide();
    }else{
        $("option[data-partner=partner]").show();
    }

    $('input:radio[name=partner_level]').change(function(e) {
        var $target = $(e.target).val();
        if($target==='master-partner'){
            $("option[data-partner=partner]").hide();
        }else{
            $("option[data-partner=partner]").show();
        }
    });


    $('a.print-order').click(function(e) {
        var $target = $(e.currentTarget);
        var printWindow = window.open( $target.data('target'), 'Print', 'left=200, top=200, width=950, height=500, toolbar=0, resizable=0');

        printWindow.addEventListener('load', function() {
            if (Boolean(printWindow.chrome)) {
                printWindow.print();
                setTimeout(function(){
                    printWindow.close();
                }, 500);
            } else {
                printWindow.print();
                printWindow.close();
            }
        }, true);
    });

    $('select[name=referred_by]').change(function(e) {
        var $target = $(e.target).children("option:selected").data('phone');
        console.log($target);
        $("input[name=referred_phone_number]").val($target);
    })
});

