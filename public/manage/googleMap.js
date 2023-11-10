function initialize() {

    $('#store-hub').on('submit', function() {
        $('input,select').prop('disabled', false);
    });
    $('form').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });
    const locationInputs = document.getElementsByClassName("map-input");

    const autocompletes = [];
    const geocoder = new google.maps.Geocoder;
    for (let i = 0; i < locationInputs.length; i++) {

        const input = locationInputs[i];
        const fieldKey = input.id.replace("-input", "");
        const isEdit = document.getElementById(fieldKey + "-latitude").value != '' && document.getElementById(fieldKey + "-longitude").value != '';

        const latitude = parseFloat(document.getElementById(fieldKey + "-latitude").value) || 3.139003;
        const longitude = parseFloat(document.getElementById(fieldKey + "-longitude").value) || 101.686855;
        let zoom = 15;

        if(latitude ===3.139003 && longitude===101.686855){
            zoom = 13;
        }
        const map = new google.maps.Map(document.getElementById(fieldKey + '-map'), {
            center: {lat: latitude, lng: longitude},
            zoom: zoom
        });
        const marker = new google.maps.Marker({
            map: map,
            position: {lat: latitude, lng: longitude},
        });

        marker.setVisible(isEdit);

        const autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.key = fieldKey;
        autocompletes.push({input: input, map: map, marker: marker, autocomplete: autocomplete});
    }

    for (let i = 0; i < autocompletes.length; i++) {
        const input = autocompletes[i].input;
        const autocomplete = autocompletes[i].autocomplete;
        const map = autocompletes[i].map;
        const marker = autocompletes[i].marker;

        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            marker.setVisible(false);
            const place = autocomplete.getPlace();

            if (!place.geometry) {
                window.alert("No details available for input: '" + place.name + "'");
                input.value = "";
                return;
            }

            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);
            }

            clearAddress();

            const validAddress= place.address_components.every(checkAddress);

            if(validAddress){
                place.address_components.forEach(insertAddress);

                if( ($( "input[name='street1']" ).val()!=place.name)){
                    $( "input[name='building_name']" ).val(place.name);
                }

                if( ($( "input[name='postal_code']" ).val()=='') || ($( "input[name='street1']" ).val()=='')
                    ||($("select[name=subdivision_id]").val()==='')||($("select[name=country_id]").val()==='')){
                    disableAddress(false);
                }

                $( "input[name='building_name']" ).prop("disabled", false);
                $( "input[name='unit_no']" ).prop("disabled", false);
                $( "input[name='formatted_address']" ).val(place.formatted_address);
                $( "input[name='address_latitude']" ).val(place.geometry.location.lat());
                $( "input[name='address_longitude']" ).val(place.geometry.location.lng());
                marker.setPosition(place.geometry.location);
                marker.setVisible(true);
            }else{
                window.alert("Please insert valid address");
                $( "input[name='address_address']" ).val('');
                disableAddress(true);
                clearAddress();
            }

        });
    }
}

function setLocationCoordinates(key, lat, lng) {
    const latitudeField = document.getElementById(key + "-" + "latitude");
    const longitudeField = document.getElementById(key + "-" + "longitude");
    latitudeField.value = lat;
    longitudeField.value = lng;
}

function insertAddress(item, index){
    if(item.types[0]==='administrative_area_level_1'){
        if(item.long_name.indexOf('Kuala Lumpur') >= 0){
            $('select[name="subdivision_id"]').val($('select[name="subdivision_id"] option[name="Kuala Lumpur"]').val()).trigger('change');
        }else if(item.long_name=='Labuan Federal Territory'){
            $('select[name="subdivision_id"]').val($('select[name="subdivision_id"] option[name="Labuan"]').val()).trigger('change');
        }else if(item.long_name=='Malacca'){
            $('select[name="subdivision_id"]').val($('select[name="subdivision_id"] option[name="Melaka"]').val()).trigger('change');
        }else if(item.long_name=='Pulau Pinang'){
            $('select[name="subdivision_id"]').val($('select[name="subdivision_id"] option[name="Penang"]').val()).trigger('change');
        }else{
            $('select[name="subdivision_id"]').val($('select[name="subdivision_id"] option[name="'+item.long_name+'"]').val()).trigger('change');
        }
    }else if(item.types[0]==='locality'){
        $( "input[name='city']" ).val(item.long_name);
    }else if(item.types[0]==='country'){
        $('select[name="country_id"]').val( $('select[name="country_id"] option[name="'+item.long_name+'"]').val()).trigger('change');
    }else if(item.types[0]==='postal_code'){
        $( "input[name='postal_code']" ).val(item.long_name);
    }else if(item.types[0]==='sublocality_level_1' ||item.types[0]==='sublocality_level_2'){
        if($( "input[name='street2']" ).val()!=''){
            $( "input[name='street2']" ).val($( "input[name='street2']" ).val()+' '+item.long_name);
        }else{
            $( "input[name='street2']" ).val(item.long_name);
        }
    }else if(item.types[0]==='floor' ||item.types[0]==='street_number' ||item.types[0]==='route'){
        if($( "input[name='street1']" ).val()!=''){
            $( "input[name='street1']" ).val($( "input[name='street1']" ).val()+', '+item.long_name);
        }else{
            $( "input[name='street1']" ).val(item.long_name);
        }
    }
}

function clearAddress() {
    $("input[name='street1']").val('');
    $("input[name='street2']").val('');
    $("input[name='postal_code']").val('');
    $("input[name='state']").val('');
    $("input[name='city']").val('');
    $("input[name='country']").val('');
}

function checkAddress(item,index){

    return !item.long_name.toString().match(/[\u3400-\u9FBF]/);

}

function disableAddress(item) {
    $("input[name='building_name']").prop("disabled", item);
    $("input[name='unit_no']").prop("disabled", item);
    $("input[name='street1']").prop("disabled", item);
    $("input[name='street2']").prop("disabled", item);
    $("input[name='postal_code']").prop("disabled", item);
    $("input[name='city']").prop("disabled", item);
    $("select[name='subdivision_id']").prop("disabled", item);
    $("select[name='country_id']").prop("disabled", item);
}
