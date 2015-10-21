var components = {
    country: "long_name",
    locality: "long_name",
    administrative_area_level_1: "short_name",
    postal_code: "short_name"
};
    
function fillAddress(place){
    for ( var key in components ) {
        $("input[name='"+ key +"']").val("").removeAttr("disabled");
    }

    for ( var i = 0; i < place.address_components.length; i++ ){
        var type = place.address_components[i].types[0];
        if( components[type] ) {
            console.log();
            var v = place.address_components[i][components[type]];
            $("input[name='"+ type +"']").val(v);
        }
    }
}

function initAutocomplete () {
    
    // we only have 4 inputs to fill when an address is inserted

    var mgr = new google.maps.places.Autocomplete( $("input[name='address']")[0] , {types: ["geocode"]} );
    mgr.addListener( "place_changed" , function(){
        
        var place = mgr.getPlace();
        fillAddress(place);
        
    });
    var address = $("input[name='address']").val();
    if(address != ""){
        var postal = $("input[name='postal_code']").val();
        $.ajax({
            type: "post",
            url: "https://maps.googleapis.com/maps/api/geocode/json?address=" + address.replace(/\s+/g , "+") + "&key=AIzaSyByh3oCcAHKsHhGrd2widWjrkH2a14hVfU&signed_in=true&libiraries=places",
            dataType: "json",
            success: function(resp){
                fillAddress(resp.results[0]);
                $("input[name='postal_code']").val(postal);
            }
        });
    }
}