function init () {
    $(document).ready(function () {

        $('.trash-button').on('click', function () {
            $('#' + this.value).remove();
        });

        $('#id_type_input').on('change', function () {
            var selected = this.value;

            if (selected === 'variance') {
                $("#id_variance_accordion").removeClass('hide');
                $("#id_bundled_accordion").addClass('hide');
                $("#id_referral_accordion").removeClass('hide');
            }
            else if (selected === 'bundled') {
                $("#id_variance_accordion").addClass('hide');
                $("#id_bundled_accordion").removeClass('hide');
                $("#id_referral_accordion").addClass('hide');

                // $(".select2").each(function () {
                //     if (!$(this).hasClass("select2-hidden-accessible") && $(this).data('prevent-default-init') === 'true') {
                //         console.log('init when show select2');
                //         $(this).select2({
                //             width: '100%',
                //             placeholder: "------",
                //             allowClear: true,
                //             theme: "bootstrap",
                //         });
                //     }
                // })
            }
            else if (selected === 'simple') {
                $("#id_variance_accordion").addClass('hide');
                $("#id_bundled_accordion").addClass('hide');
                $("#id_referral_accordion").removeClass('hide');
            }
        });

        createVarianceProductRepeater();
        createBundledProductRepeater();
    });
}

function createVarianceProductRepeater() {
    $('.variance-product-repeater').repeater({
        initEmpty: true,
        show: function () {
            $(this).slideDown();
        },
        hide: function (remove) {
            if (confirm('Are you sure you want to remove this item?')) {
                $(this).slideUp(remove);
            }
        }
    });
}

function createBundledProductRepeater() {
    $('.bundled-product-repeater').repeater({
        initEmpty: true,
        show: function () {
            $(this).slideDown();
            var $dropdown = $(this).find('select');
            console.log('init product form select2');

            $($dropdown).select2({
                width: '100%',
                placeholder: "------",
                allowClear: true,
                theme: "bootstrap",
                closeOnSelect: false,
            });
        },
        hide: function (remove) {
            if (confirm('Are you sure you want to remove this item?')) {
                $(this).slideUp(remove);
            }
        }
    });
}

export default {
    init
}
