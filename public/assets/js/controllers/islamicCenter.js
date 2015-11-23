app.controller("IslamicCenterController" , ["$scope", "$http" , function(scope , http){
        
    //  CREATE A NEW ISLAMIC CENTER
        scope.center = {};
        scope.create = function(){
            
            ISGH.updateAddressComponents(scope.center);
            var $edit = window.location.pathname;
            var icID = $edit.substr($edit.lastIndexOf("/")+1 , 1);
            if(isNaN( parseInt(icID , 10) )){
                http.post("/admin/createIslamicCenter" , scope.center)
                    .then(function(resp){

                    if(resp.data == "true"){
                        ISGH.notify("The Islamic center '" + scope.center.name+ "' has been successfully created!");
                        delete scope.center;
                        scope.icForm.$setPristine(true);

                    }
                    else
                        ISGH.alertBox.init("The Islamic center '" + scope.center.name + "' already exists" , false);

                } , function(err){
                    ISGH.alertBox.init("Something went wrong, Please refresh and try again" , false);
                });
            }else {
                console.log("EDITING");
                icID = parseInt(icID , 10);
                scope.update(icID);
            }
            window.location.assign(window.location);
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
            console.log($("input[name='icID']").val());
            http.post("/admin/createIslamicCenter/" + _id , scope.center)
                .then(function(resp){
                if(resp.data == "true"){
                    ISGH.notify("The Islamic center '" + scope.center.name+ "' has been successfully edited!");
                }else {
                    ISGH.alertBox.init("An Unknown error occurred while updating the islamic center data, Please refresh and try again!" , false);
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