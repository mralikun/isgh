/**

    This controller contains all methods needed for managing all users activity.
    @version 0.1
    @author Ali Hassan <ali.hassan@tooonme.com>

*/
    
angular.module("isgh" , [])
    .controller("UserController" , ["$scope" , "$http" , function( scope , http ){

        // we will make a request to get the online user's data.
        scope.user = {};

        scope.update = function (){
            // updating the address manually to the model becuase the model doesn't respond to input auto fill.
            ISGH.updateAddressComponents(scope.user);
            // Use ISGH.Validator.required to check for all fields
            
        }

        // available to admins ONLY

        scope.delete = function ( ){}

        scope.create = function ( _temp ){
            if( !ISGH.Validator.required( ["username" , "password" , "confirm_password" , "role"] , _temp ) ){
                ISGH.alertBox.init("Some required fields are missing ,Please review all form fields and make sure nothing is missing" , false);
                return false;
            }
            
            // use data from tempUser then delete it !
            // we need to make sure that every thing that is required still exists.
        }

        ////////////////////////////////////


        //  For account owners ONLY

        scope.rate = function (ev){}

        scope.setBlockedDates = function (){}

        ////////////////////////////////////

}]);