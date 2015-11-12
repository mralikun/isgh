app.controller("IslamicCenterController" , ["$scope", "$http" , function(scope , http){
        
    //  CREATE A NEW ISLAMIC CENTER
        scope.center = {};
        scope.create = function(){
            ISGH.updateAddressComponents(scope.center);
            var $edit = $("form > input[name='userID']");
            if($edit.length){
                scope.update($edit.val());
                return;
            }
//            scope.center.khutbah_start.setHours(scope.center.khutbah_start.getHours() + (scope.center.khutbah_start.getTimezoneOffset()*-1 / 60));
//            scope.center.khutbah_end.setHours(scope.center.khutbah_end.getHours() + (scope.center.khutbah_end.getTimezoneOffset()*-1 / 60));
            http.post("/admin/createIslamicCenter" , scope.center)
                .then(function(resp){

                if(resp.data == "true"){
                    ISGH.notify("The Islamic center '" + scope.center.name+ "' has been successfully created!");
                    delete scope.center;
                    scope.icForm.$setPristine(true);
                    window.location.assign(window.location);
                }
                else
                    ISGH.alertBox.init("The Islamic center '" + scope.center.name + "' already exists" , false);

            } , function(err){
                ISGH.alertBox.init("Something went wrong, Please refresh and try again" , false);
            });
                
        }
        
        //  REQUEST THE DIRECTOR'S CELL PHONE ONCE IT'S SELECTED
        
        scope.updateDirectorCellPhone = function(){
            http.post("/user/getCellPhone" , {"id" : scope.center.director_name})
                .then(function(resp){
                scope.center.director_cell_phone = resp.data;
            } , function(err){
                ISGH.alertBox.init("Something went wrong retriving the director's cell phone." , false);
            });
        }
        
        //  UPDATES AN ISLAMIC CENTER'S DATA
        
        scope.update = function( _id ){
            http.post("/admin/createIslamicCenter/" + _id , scope.center)
                .then(function(resp){
                if(resp.data == "true"){
                    ISGH.notify("The Islamic center '" + scope.center.name+ "' has been successfully created!");
                    delete scope.center;
                    scope.icForm.$setPristine(true);
                }else {
                    ISGH.alertBox.init("The Islamic center '" + scope.center.name + "' already exists" , false);
                }
            } , function(err){
                ISGH.alertBox.init("Something went wrong, Please refresh and try again" , false);
            });
        }
        
        //  DELETES AN EXISTING ISLAMIC CENTER
        
        scope.delete = function(_event){
            var target = _event.target;
            if(!$(target).hasClass("opt-delete"))
                return;
            var ID = target.getAttribute("data-member");
            var url = "/admin/DeleteIslamicCenter/" + ID;
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
        
    }]);