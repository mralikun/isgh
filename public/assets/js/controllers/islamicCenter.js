angular.module("isgh" , [])
    .controller("IslamicCenterController" , ["$scope", "$http" , function(scope , http){
        
        scope.center = {};
        
        scope.create = function(){
            ISGH.updateAddressComponents(scope.center);
            //if(!ISGH.Validator.required(["name" , "address" , "country" , "locality" , "administrative_area_level_1" , "postal_code" , "director_name" , "khutbah_start" , "khutbah_end" , "parking_information"] , scope.center)){
            //    ISGH.alertBox.init("Some required fields are missing, Please review all form fields and make shure that nothing required is missing");
            //    return false;
            //}
            
            http.post("/admin/createIslamicCenter" , scope.center)
                .then(function(resp){
                
                if(resp.data == "true"){
                    ISGH.notify("The Islamic center " + scope.center.name+ " has been successfully created!");
                    var au = document.getElementsByTagName("audio")[0];
                    au.play();
                }
                else
                    ISGH.alertBox.init("This Islamic center already exists" , false);
                
            } , function(err){
                ISGH.alertBox.init("Something went wrong, Please refresh and try again" , false);
            });
                
        }
        
        scope.updateDirectorCellPhone = function(){
            http.post("/user/getCellPhone" , {"id" : scope.center.director_name})
                .then(function(resp){
                scope.center.director_cell_phone = resp.data;
            } , function(err){
                ISGH.alertBox.init("something went wrong retriving the director's cell phone." , false);
            });
        }
        
        scope.update = function(){
            
        }
        
        scope.delete = function(){
            
        }
        
    }]);