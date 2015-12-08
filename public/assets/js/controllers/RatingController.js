app.controller("RatingController" , ["$scope" , "$http" , function(scope , request){
    var pages_data = [];
    scope.page = 1;
    scope.pages_num = pages_data.length;
    scope.current_page_data = [];
    
    function chunk(arr , size){
        var chunked = [];
        while(arr.length){
            chunked.push(arr.splice(0 , size));
        }
        return chunked;
    }
    
    function init(){
        scope.current_page_data = pages_data[scope.page - 1];
        
    }
    

    scope.next = function(){
        if(scope.page < scope.pages_num){
            
            scope.page++;
            init();
        }
    };
    scope.prev = function(){
        if(scope.page > 1){
            scope.page--;
            init();
        }
    }

    
    request.post("/user/startRate")
    .then(function(resp){
        pages_data = chunk(resp.data , 7);
        scope.pages_num = pages_data.length;
        init();
    } , function(err){})
    
}]);