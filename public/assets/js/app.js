var ISGH = {
    
    /**
        This object manages the alert box which will appear to user when any warning
        or errors occur
    */
    alertBox: {
        // The message to show to user
        _msg: "",
        // this attribute indecates whether to show a yes/no option or an Ok button.
        _confirmable: true,
        
        /**
         * Sets the _msg attribute.
         * @param String msg the message letiral.
         */
        
        setMessage: function( msg ){
            this._msg = msg;
        },
        
        /**
         * Changes the _confirmable attribute to a given value or reverse it if the value was not provided.
         * @param Boolean _newMode New mode
         */
        
        changeMode: function( _newMode ){
            
            (_newMode !== undefined) ? this._confirmable = _newMode : this._confirmable = !this._confirmable;
            
        },
        
        /**
         * Shows the box to the user.
         */
        
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
                // we will need a way to return the result.
                self._result = parseInt( this.getAttribute("data-confirm") ) == 1;
                self.hide();
                console.log(self._result);
                
            });
            
        },
        
        /**
         * Hides the box from user.
         */
        
        hide: function() {
            $(".alert").fadeOut( 300 );
            $(".accept , .decline").unbind("click");
            
        },
        
        /**
         * Initializes the box functionality by setting the message , the mode and showing the box.
         * @param String msg  The message to show.
         * @param Boolean mode The mode to shwo the message in.
         */
        
        init: function( msg , mode ){
            this.setMessage( msg );
            this.changeMode( mode );
            this.show();
        }
        
    },
    
    /**
     * This function is used to manually update an address components to an object.
     * @param Object _for  The object to update these attributes for.
     */
    
    updateAddressComponents: function( _for ){
        _for.country = $("input[name='country']").val();
        _for.city = $("input[name='locality']").val();
        _for.state = $("input[name='administrative_area_level_1']").val();
        _for.postal_code = $("input[name='postal_code']").val();
        _for.address = $("input[name='address']").val();
    },
    
    // This object is used for data validation
    
    Validator:  {
        
        /**
         * Checks an Object model against a Collection of strings representing input names or object attributes.
         * if any of the strings doesn't match a key within the object model even if the input is the same it will still return false.
         * @param   Collection fields   set of strings 
         * @param   Object objModel the object to compage its keys agains the fields.
         * @returns Boolean  whether all fields exist in the form of keys within the object model
         */
        
        required: function ( fields , objModel ){
            
            for(var i = 0; i < fields.length; i++){
                if(!objModel.hasOwnProperty(fields[i]))
                    return false;
            }
            return true;
        },
        
        /**
         * Checks the length of the obj and returns whether of not it's bigger than or equal to the given limit
         * @param   Object obj   the object to check its length.
         * @param   Number limit an integer to compare the object length to.
         * @returns Boolean whether or not the object length is bigger than or equals to limit.
         */
        
        atLeast: function( obj , limit ){
            return ( typeof limit == "number" ) ? obj.length >= limit : undefined;
        },
        
        /**
         *  Checks the length of the obj and returns whether of not it's lower than or equal to the given limit
         * @param   Object obj   the object to check its length.
         * @param   Number limit an integer to compare the object length to.
         * @returns @returns Boolean whether or not the object length is lower than or equals to limit.
         */
        
        atMost: function( obj , limit ){
            return ( typeof limit == "number" ) ? obj.length <= limit : undefined;
        }
    },
    
    notify:function(msg){
        $(".notification p").text(msg);
        $(".notification").addClass("appear");
        window.setTimeout(function(){$(".notification").removeClass("appear")} , 5000);
    },
    
    init: function(){
        
        $(".profile-pic").on("change" , function(event){
            
            var reader = new FileReader();
            reader.onload = function(e){
                $(".edit-img").css("background-image" , "url("+ e.target.result +")");
            }
            reader.readAsDataURL(this.files[0]);
            
        });
        
        $(".dates-calendar").on("click" , ".date" , function(e){
            
            $(this).toggleClass("available");
            var $checkbox = $(this).find("[type='checkbox']");
            $checkbox.prop("checked" , !$checkbox.prop("checked"));
            
        });
        
        $(".select-all").on("click" , function(){
            $(".dates-calendar input[type='checkbox']").prop("checked" , true);
            $(".dates-calendar .date").addClass("available");
        });
        
        $(".unselect-all").on("click" , function(){
            $(".dates-calendar input[type='checkbox']").prop("checked" , false);
            $(".dates-calendar .date").removeClass("available");
        });
        
        $(".reverse-select").on("click" , function(){
            $(".dates-calendar input[type='checkbox']").each(function(index , element){
                $(element).prop("checked" , !$(element).prop("checked"));
            });
            $(".dates-calendar .date").toggleClass("available");
        });
        
        $(".notification").on("click" , function(){
            $(this).removeClass("appear");
        });
        
    }
    
}