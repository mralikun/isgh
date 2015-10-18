function initAutocomplete () {
    
    // we only have 4 inputs to fill when an address is inserted
    var components = {
        country: "long_name",
        locality: "long_name",
        administrative_area_level_1: "short_name",
        postal_code: "short_name"
    };
    
    var mgr = new google.maps.places.Autocomplete( $("input[name='address']")[0] , {types: ["geocode"]} );
    mgr.addListener( "place_changed" , function(){
        
        var place = mgr.getPlace();
        
        for ( var key in components ) {
            $("input[name='"+ key +"']").val("").removeAttr("disabled");
        }
        
        for ( var i = 0; i < place.address_components.length; i++ ){
            var type = place.address_components[i].types[0];
            if( components[type] ) {
                var v = place.address_components[i][components[type]];
                $("input[name='"+ type +"']").val(v);
            }
        }
        
    });
}