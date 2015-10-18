var ISGH = {
    
    alertBox: {
        _msg: "",
        _confirmable: true,
        
        setMessage: function( msg ){
            this._msg = msg;
        },
        
        changeMode: function( _newMode ){
            
            (_newMode !== undefined) ? this._confirmable = _newMode : this._confirmable = !this._confirmable;
            
        },
        
        show: function() {
            if( this._msg !== "" )
                $(".alert").fadeIn( 300 );
            
            var self = this;
            
            if( !this._confirmable ){
                $(".decline").hide();
                $(".accept").text("Ok");
            }else {
                $(".decline").show();
                $(".accept").text("Yes");
            }
            
            $(".message").text(this._msg);
            
            $(".accept , .decline").on("click" , function( _event ){
                
                self._result = parseInt( this.getAttribute("data-confirm") ) == 1;
                self.hide();
                console.log(self._result);
                
            });
            
        },
        
        hide: function() {
            $(".alert").fadeOut( 300 );
            $(".accept , .decline").unbind("click");
            
        },
        
        init: function( msg , mode ){
            this.setMessage( msg );
            this.changeMode( mode );
            this.show();
        }
        
    },
    
    updateAddressComponents: function( _for ){
        _for.country = $("input[name='country']").val();
        _for.city = $("input[name='locality']").val();
        _for.state = $("input[name='administrative_area_level_1']").val();
        _for.postal_code = $("input[name='postal_code']").val();
        _for.address = $("input[name='address']").val();
    }
    
}