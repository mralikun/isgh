/**

    This controller contains all methods needed for managing basic users activity.
    @version 0.1
    @author Ali Hassan <ali.hassan@tooonme.com>

*/
    
    app.controller("UserController" , ["$scope" , "$http" , function( scope , http ){
        
        //  DELETES AN EXISTING USER

        scope.delete = function ( obj ){
            
            var target = obj._ev.target;
            if(!$(target).hasClass("opt-delete"))
                return;
            var ID = target.getAttribute("data-member");
            var url = "";
            if(obj.role == 2)
                url = "/admin/DeleteKhateeb/" + ID;
            else if(obj.role == 3)
                url = "/admin/DeleteAd/" + ID;
            
            http.delete(url)
                .then(function(){
                $(target).parents("tr").addClass("removed");
                setTimeout(function(){
                    $(target).parents("tr").remove();
                } , 500);
            } , function(){
                ISGH.alertBox.init("Something went wrong ,Please refresh the page and try again!" , false);
            });
        }
        
        //  CREATES A NEW USER

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

}]);