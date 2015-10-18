angular.module("isgh" , [])
    .controller("IslamicCenterController" , ["$scope" , function(scope){
        
        scope.create = function(){
            ISGH.updateAddressComponents(scope.center);
            
        }
        
        scope.update = function(){
            
        }
        
        scope.delete = function(){
            
        }
        
    }]);