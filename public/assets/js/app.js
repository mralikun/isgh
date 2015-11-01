var app = angular.module("isgh" , ["ngMessages"]);

var ISGH = {
    
    Tabs: {
        
        activate: function(contentNum){
            this.deactivate(contentNum);
            setTimeout(function(){
                $(".tab-content[data-content='"+ contentNum +"']").fadeIn(300);
            } , 400);
        },
        deactivate: function(Num){
            
            $(".tab-content[data-content!='"+ Num +"']").fadeOut(300);
            
        },
        init: function(){
            
            var self = this;
            $(".tabs-links").on("click" , "button" , function(){
                var active = $(this).hasClass("active-tab");
                if(active){
                    return;
                } else {
                    $(this).addClass("active-tab").siblings("button").removeClass("active-tab");
                }
                self.activate(this.getAttribute("data-content"));
            });
            
            var activeTab = $(".tabs-links > button.active-tab").attr("data-content");
            
            $(".tab-content[data-content='"+ activeTab +"']").fadeIn(300);
            
        }
    },
    
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
        
    },
    
    notify:function(msg){
        $(".notification p").text(msg);
        $(".notification").addClass("appear");
        var au = document.getElementsByTagName("audio")[0];
        au.play();
        window.setTimeout(function(){$(".notification").removeClass("appear")} , 5000);
    },
    
    initHelperFunctions: function(){
        
        this.updateAddressComponents = function( _for ){
            _for.country = $("input[name='country']").val();
            _for.city = $("input[name='locality']").val();
            _for.state = $("input[name='administrative_area_level_1']").val();
            _for.postal_code = $("input[name='postal_code']").val();
            _for.address = $("input[name='address']").val();
        }
        
        Array.prototype.reflectValues = Array.prototype.reflectValues || function(target){
            
            var reflected = [];
            
            for(var i = 0; i < this.length; i++){
                reflected.push(target[this[i]]);
            }
            return reflected;
        }
        
    },
    
    floodStack: function(){
        
        /**
            UPDATING PROFILE INFORMATION FOR KHATEEB / AD
        */
        
        $("form#update-profile-form").on("submit" , function(){
            var fd = new FormData(this);
            var $editingUser = $(this).find("input[name='userID']");
            var url = "/user/updateProfile/" + (($editingUser.length) ? $editingUser.val() : "");
            var fields =  {
                name: "Name",
                address: "Address",
                country: "Country",
                locality: "City",
                administrative_area_level_1: "State",
                postal_code: "Postal Code",
                cell_phone: "Cell Phone",
                bio: "Biography",
                edu_bg: "Educational Background",
                email: "E-mail"
            };
            
            $.ajax({
                url: url,
                type: "POST",
                data: fd,
                dataType: "json",
                xhr: function(){
                    var x = $.ajaxSettings.xhr();
                    return x;
                },
                success: function(resp){
                    if(resp instanceof Array){
                        var res = resp.reflectValues(fields);
                        ISGH.alertBox.init("Field(s) " + res.join(" , ") + " are missing" , false);
                    }else{
                        ISGH.notify("Your information was updated successfully!");
                        window.setTimeout(function(){
                            var r = new RegExp(window.location.pathname); 
                            var url = window.location.href.replace(r,"/user/profile");
                            window.location.assign(url);
                        } , 3000);

                    }
                },
                error: function(err){
                    ISGH.alertBox.init("Something went wrong ,Please refresh and try again" , false);
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });
        
        //  HANDLING CLICK EVENT FOR DATES.
        
        $(".dates-calendar").on("click" , ".date" , function(e){
            
            $(this).toggleClass("available"); // scales the date
            var $checkbox = $(this).find("[type='checkbox']");
            $checkbox.prop("checked" , !$checkbox.prop("checked")); // toggles the checkbox
            
        });
        
        //  SELECTS ALL DATES.
        
        $(".select-all").on("click" , function(){
            $(".dates-calendar input[type='checkbox']").prop("checked" , true);
            $(".dates-calendar .date").addClass("available");
        });
        
        //  DESELECT ALL DATES
        
        $(".unselect-all").on("click" , function(){
            $(".dates-calendar input[type='checkbox']").prop("checked" , false);
            $(".dates-calendar .date").removeClass("available");
        });
        
        //  SELECTS ALL UNSELECTED DATES AND DESELECT ALL SELECTED DATES
        
        $(".reverse-select").on("click" , function(){
            $(".dates-calendar input[type='checkbox']").each(function(index , element){
                $(element).prop("checked" , !$(element).prop("checked"));
            });
            $(".dates-calendar .date").toggleClass("available");
        });
        
        // WHEN CLICKING ON THE NOTIFICATION...IT HIDES
        
        $(".notification").on("click" , function(){
            $(this).removeClass("appear");
        });
        
        //  CANCELING THE SPACE BAR FROM THE USERNAME FIELD
        
        $("input[ng-model $= 'username']").on("keypress" , function(e){
            var pressedKey = String.fromCharCode(e.charCode).toLocaleLowerCase();
            if(pressedKey == " ")
                return false;
        });
        
        //  SHOWING THE SELECTED IMAGE LOCALLY BEFORE UPLOADING IT.
        
        $(".profile-pic").on("change" , function(event){
            
            var file = this.files[0];
            var types = ["png" , "jpg" , "jpeg"];
            var fileType = file.type.substring(file.type.lastIndexOf("/") + 1 , file.type.length);
            if( types.indexOf(fileType) == -1 ){
                ISGH.alertBox.init("Please Choose another image ,Supported formats are '.png' , '.jpg' and '.jpeg'" , false);
                return false;
            }
            
            var reader = new FileReader();
            reader.onload = function(e){
                $(".edit-img").css("background-image" , "url("+ e.target.result +")");
            }
            reader.readAsDataURL(this.files[0]);
            
        });
    },
    
    //  STARTS THE MAGIC
    
    init: function(){
        
        this.initHelperFunctions();
        this.floodStack();
        
    }
    
}