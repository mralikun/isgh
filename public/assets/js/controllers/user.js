/**

    This controller contains all methods needed for managing all users activity.
    @version 0.1
    @author Ali Hassan <ali.hassan@tooonme.com>

*/
    
angular.module("isgh" , ["ngMessages"])
    .controller("UserController" , ["$scope" , "$http" , function( scope , http ){

        // we will make a request to get the online user's data.
        http.post("/user/onlineUserRole")
            .then(function(resp){
            var temp = resp.data;
            if(temp != "khateeb"){
                $(".pic-holder").remove();
                $("input[name='edu_background']").parents(".form-group").remove();
            }
        } , function(err){
            ISGH.alertBox.init("Something went wrong, Please refresh the page!" , false);
        });
        // we should request this if the user goes to the dates page.
        scope.cycleDates = [];
        
        scope.dates = {
            available: [],
//            blocked: [], it might be useful later
            append: function(ID , availability){
                var where = this[availability];
                
                if(this.exists(ID , where)){
                    this.remove(ID , where);
                }else {
                    where.push(ID)
                }
            },
            
            exists: function(ID , where){
                return where.indexOf(ID) !== -1;
            },
            
            remove: function(what ,where){
                where.splice(where.indexOf(what) , 1);
            }
        };

        // available to admins ONLY

        scope.delete = function ( ){}

        scope.create = function ( _temp ){
            
            if( !ISGH.Validator.required( ["username" , "password" , "confirm_password" , "role"] , _temp ) ){
                ISGH.alertBox.init("Some required fields are missing ,Please review all form fields and make sure nothing is missing" , false);
                return false;
            }
            
            var roles = ["ad" , "khateeb" , "admin"];
            _temp.role = roles[_temp.role];
            
            http.post("/admin/createUser" , _temp)
                .then(function(resp){
                
                if(resp.data == "true"){
                    ISGH.alertBox.init("The user '"+ _temp.username +"' already exists" , false);
                }else{
                    ISGH.notify("The user '" + _temp.username + "' has been successfully created!");
                    delete scope.tempUser.username;
                    delete scope.tempUser.password;
                    delete scope.tempUser.confirm_password;
                    delete scope.tempUser.role;
                    scope.registerForm.$setPristine(true);
                }
                
            } , function(err){
                
                ISGH.alertBox.init("Something went wrong ,Please refresh the page and try again" , false);
                
            });
            
        }

        ////////////////////////////////////


        //  For account owners ONLY

        scope.handleDatesClick = function(_event){
            // This will filter out the ID from the click target ,beacuse the click target might be a child of the .date element.
            // this is when a date is clicked.
            var id = 0;
            if(!_event.target.classList.contains("date")){
                id = $(_event.target).parents(".date").prop("id");
            }else
                id = _event.target.id;
            
            scope.dates.append(id , "available");
        }
        
        scope.rate = function (){}

        scope.setBlockedDates = function (){
            // Use this method to make the blocked dates before sending the data to the server.
            // we aren't going to use the blocked array within the dates object just yet but it might be useful later
            // console.log($(arr1).not(arr2).get());
            
        }
        ////////////////////////////////////

}]);