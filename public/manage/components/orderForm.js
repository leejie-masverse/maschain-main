function init () {
    $(document).ready(function () {

        $('.trash-button').on('click', function () {
            $('#' + this.value).remove();
        });

        createOrderProductRepeater();
        createBundleProductRepeater();
    });
}

function createBundleProductRepeater() {
    $('.bundle-product-repeater').repeater({
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

function createOrderProductRepeater() {
    $('.order-product-repeater').repeater({
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

export default {
    init
}
