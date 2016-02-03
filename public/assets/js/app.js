var app = angular.module("isgh" , ["ngMessages"])
    .config(function($interpolateProvider){
        $interpolateProvider.startSymbol("[[");
        $interpolateProvider.endSymbol("]]");
    });

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
        // this attribute indecates whether to show a yes/no option when true or an Ok button otherwise.
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
    
    notify: function(msg){
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
        
        Array.prototype.chunk = Array.prototype.chunk || function(size){
            var chunked = [];
            while(this.length){
                chunked.push(this.splice(0,size))
            }
            return chunked;
        }
        
    },
    
    Dates: {
        choosen: [],
        visitors: [],
        path: "",
        init: function(url){
            this.path = url;
            var IDS = $(".date.available").map(function(ind , el){
                return el.id;
            });
            
            var visitors = $(".visitor-name").map(function(ind , el){
                return $(el).text();
            });
            
            for(var i = 0; i < IDS.length; i++){
                this.select(parseInt(IDS[i]));
            }
            
            for(var i = 0; i < visitors.length; i++){
                this.add_visitor(visitors[i]);
            }
            
        },
        select: function(_id){
            if(this.choosen.indexOf(_id) === -1)
                this.choosen.push(_id);
        },
        deselect: function(id){
            if(this.choosen.indexOf(id) !== -1)
                this.choosen.splice( this.choosen.indexOf(id) , 1 );
        },
        patch: function(){
            var u = this.path;
            var data_obj = {
                dates: this.choosen,
                _token: $("input[name='_token']").val(),
                names: this.visitors
            }
            $.ajax({
                url: u,
                type: "POST",
                data: data_obj,
                success: function(){
                    ISGH.notify("Your selection has been saved!");
                },
                error: function(){
                    ISGH.alertBox.init("Couldn't set your selection, Please refresh and try again!" , false);
                }
            });
        },
        reset: function(){
            for(var i = 0; i < this.choosen.length; i++)
                this.deselect(this.choosen[i]);
        },
        add_visitor: function(visitor_name){
            this.visitors.push(visitor_name);
        },
        remove_visitor: function(friday_id){
            var the_index = this.choosen.indexOf(friday_id);
            this.visitors.splice(the_index ,1);
        }
    },
    
    floodStack: function(){
        
        /**
            UPDATING PROFILE INFORMATION FOR KHATEEB / AD
        */
        var self = this;
        $("form#update-profile-form").on("submit" , function(){
            var fd = new FormData(this);
            var $editingUser = $(this).find("input[name='userID']");
            var url = "/user/updateProfile" + (($editingUser.length) ? "/"+$editingUser.val() : "");
            var $for_ic = $(this).find("input[name='islamic_center']").val();
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

                    if( resp instanceof Object ){
                        if(resp.missing.length > 0){
                            var res = resp["missing"].reflectValues(fields);
                            ISGH.alertBox.init("Field(s) " + res.join(" , ") + " are missing" , false);
                        }else if(!resp.email){
                            ISGH.alertBox.init("You can't use this email, it already exists associated with another account." , false);
                        }
 
                    }else if(resp == false){
                        ISGH.alertBox.init("Something went wrong, Please refresh and try again!");
                    }else if(resp == true){
                        ISGH.notify("The information was updated successfully!");
                        if(!isAdmin()){
                            var preg = new RegExp(window.location.pathname);
                            var url = window.location.href.replace(preg , "/user/profile");
                            setTimeout(function(){
                                window.location.assign(url);
                            },1500);
                            
                        }
                        // After the information update...
                        // i need to know who is editing wether its the user or the admin!!!!
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
        
        $(".visitor_name_save").on("click" , function(){
            var id = $(this).attr("data-id");
            var name = $("#visitor_name_value").val();
            $(".date#"+id).nextAll().slice(0,3).each(function(ind , ele){
                var $el = $(ele);
                if($el.hasClass("available")){
                    var temp_id = parseInt( $el.attr("id") , 10 );
                    self.Dates.remove_visitor(temp_id);
                    self.Dates.deselect(temp_id);
                }
            });
            if(name){
                self.Dates.select( parseInt( id , 10 ) );
                self.Dates.add_visitor(name);
                $("#visitor-name").modal("hide");
                $(".temp-reserved").addClass("reserved").removeClass("available");
                $(".last-reserved").removeClass("last-reserved reserved temp-reserved");
            }
            else
                alert("Please insert a visitor name!");
        });
        
        $(".visitor-canceled").on("click" , function(){
            var id = $(this).attr("data-id");
            $(".date[id='"+id+"']").removeClass("available");
            self.Dates.deselect(id);
        });
        
        $(".dates-calendar").on("click" , ".date:not(.reserved)" , function(e){
            
            // blocked or available ?
            
            var route = window.location.pathname;
            var blocked_dates_view = (route.indexOf("Blocked") !== -1) ? true : false;
            
            // handling the view part
            
            $(this).toggleClass("available"); // scales the date
            
            // handling the data part.
            
            var ID = undefined;
            
            if(!$(this).hasClass("date")){
                ID = parseInt( $(this).parents(".date").attr("id") );
                
            }else {
                ID = parseInt( $(this).attr("id") );
            }
            
            $(".visitor_name_save , .visitor-canceled").attr("data-id" , ID);
            
            
            function block_for_a_month(par){
                var next_siblings = $(par).nextAll().slice(0,3).each(function(index , element){
                    $el = $(element);
                    if(index == 2){
                        if($el.next().hasClass("temp-reserved")){
                            $el.nextUntil(".date.available:not(.temp-reserved)" , ".temp-reserved.reserved").each(function(ind , e){
                                if($(e).hasClass("temp-reserved"))
                                    $(e).addClass("last-reserved");
                            });
                        }
                    }
                    if(!$(element).hasClass("reserved"))
                        $(element).addClass("temp-reserved");
                });
            }
            
            function release_for_a_month(par){
                var next_siblings = $(par).nextAll().slice(0,3).each(function(index , element){
                    var $el = $(element);
                    if($el.hasClass("temp-reserved reserved")){
                        $el.removeClass("temp-reserved reserved");
                    }
                });
            }
            
            if($(this).hasClass("available")){
                block_for_a_month(this); // disable the next 3 fridays from being choosen.
                if(blocked_dates_view){
                    $("#visitor-name").modal({keyboard: false , backdrop: "static"}).modal("show");
                }else {
                    self.Dates.select(ID);
                }
            }else {
                release_for_a_month(this); // enables the next 3 fridays to be choosen.
                if(blocked_dates_view)
                    self.Dates.remove_visitor(ID);
                self.Dates.deselect(ID);
            }
        });
        
        //  SELECTS ALL DATES.
        
        $(".select-all").on("click" , function(){
            $(".dates-calendar .date:not(.reserved)").addClass("available").each(function(index , element){
                var ID = undefined;
                if($(element).hasClass("date"))
                    ID = parseInt( $(element).attr("id") );
                else
                    ID = parseInt( $(element).parent("date").attr("id") );
                
                self.Dates.select(ID);
            });
        });
        
        //  DESELECT ALL DATES
        
        $(".unselect-all").on("click" , function(){
            $(".dates-calendar .date:not(.reserved)").removeClass("available").each(function(index , element){
                var ID = undefined;
                if($(element).hasClass("date"))
                    ID = parseInt( $(element).attr("id") );
                else
                    ID = parseInt( $(element).parent("date").attr("id") );
                
                self.Dates.deselect(ID);
            });
        });
        
        //  SELECTS ALL UNSELECTED DATES AND DESELECT ALL SELECTED DATES
        
        $(".reverse-select").on("click" , function(){
            $(".dates-calendar .date:not(.reserved)").toggleClass("available").each(function(index , element){
                
                var ID = undefined;
                if($(element).hasClass("date"))
                    ID = parseInt( $(element).attr("id") );
                else
                    ID = parseInt( $(element).parent("date").attr("id") );
                
                if($(element).hasClass("available"))
                    self.Dates.select(ID);
                else
                    self.Dates.deselect(ID);
                
            });
        });
        
        $("#blocked-dates-form").on("submit" , function(){
            if(self.Dates.choosen.length === 0){
                ISGH.alertBox.init("Please choose at least 1 Friday" , false);
                return false;
            }
            self.Dates.patch();
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