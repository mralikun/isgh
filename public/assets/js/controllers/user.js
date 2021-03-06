/**

    This controller contains all methods needed for managing basic users activity.
    @version 0.2
    @author Ali Hassan <ali.hassan@tooonme.com>

*/
    
    app.controller("UserController" , ["$scope" , "$http" , function( scope , http ){
        //  DELETES AN EXISTING USER
        
        scope.chg_pass = {
            valid_creds: "false"
        };
        
        scope.checkCurrentPassword = function(){
            http.post("/user/checkpass" , {password: scope.chg_pass.old}).then(function(response){
                var result = response.data;
                console.log(result);
                scope.chg_pass.valid_creds = result;
            });
        }
        
        scope.saveNewPassword = function(){
            http.post("/user/changePass" , {pass1: scope.chg_pass.new  , pass2: scope.chg_pass.confirm_new}).then(function(response){
                var result = response.data.success;
                if(result) {
                    ISGH.notify("Password has been changed successfully!");
                    $("#chg_pass_modal").modal("hide");
                    if(window.location.pathname.indexOf("changePassword") !== -1)
                        window.location.href = "/user/profile";
                }
            });
        }
        
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
            else if(obj.role == 1)
                url = "/admin/deleteAdmin/" + ID;
            
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
        function valid(obj){
            return obj.username && obj.username.length >= 6 && obj.username.length <= 32
                    && obj.password && obj.password.length >= 8 && obj.password.length <= 32
                    && obj.confirm_password && obj.password === obj.confirm_password
                    && obj.role;
        }
        scope.create = function ( _temp ){
            
            if(!valid(_temp))
                return false;
            
            if(_temp.role == 2 && !_temp.email){
                ISGH.alertBox.init("Please Insert your email first!" , false);
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
                    delete scope.tempUser.email;
                    scope.registerForm.$setPristine(true);
                }
                
            } , function(err){
                
                ISGH.alertBox.init("Something went wrong ,Please refresh the page and try again" , false);
                
            });
            
        }
        
        ////////////////////////////////////

}]);