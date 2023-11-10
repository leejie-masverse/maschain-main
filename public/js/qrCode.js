$(document).ready(() => {
    var $element = document.getElementById("affiliate_qr_code");

    if ($element !== undefined && $element !== null) {
        var affiliateUrl;
        affiliateUrl = $('#url-link').val();
        var options = {
            text: affiliateUrl,
            //logo:"/main/images/logo.png",
            width: 1266,
            height:1266,
            logoWidth:843,
            logoHeight:159,
        };
        new QRCode($element, options);
        $('#affiliate_qr_code img').width('100%');
    }
});
