app.controller("ScheduleController" , ["$scope" , "$http" , function(scope , request){
    
    scope.schedule_generated = false;
    scope.schedule_approved = false;
    var DataManager = {
        mapped_data: [],
        init: function(d){
            this.data = d;
            this.setup();
            this.map();
        },
        setup: function(){
            this.dates = [];
            this.islamic_centers = [];
            for(var i = 0; i< this.data.length; i++){
                var obj = this.data[i];
                if(this.dates.indexOf(obj.friday.date) === -1){
                    this.dates.push(obj.friday.date);
                }
                if(this.islamic_centers.indexOf(obj.islamic_center.name) === -1){
                    this.islamic_centers.push(obj.islamic_center.name);
                }
            }
        },
        map: function(){
            var ds = this.dates;
            var ic_data = $(this.data).map(function(ind , e){
                return {
                    islamic_center: e.islamic_center.name,
                    date: e.friday.date,
                    khateeb: e.khateeb.name
                }
            });
            for(var i = 0; i < this.islamic_centers.length; i++){
                var ic = this.islamic_centers[i];
                var obj = {
                    islamic_center: ic,
                    khutbahs: groupByDate(ic_data , ic , ds )
                }
                this.mapped_data.push(obj);
            }
        }
    };
    function formatTime(d){
        var hours = d.getHours();
        var mints = d.getMinutes();
        return ((hours < 10) ? "0"+hours : hours) + ":" + ((mints < 10) ? "0"+mints : mints);
    }
    
    scope.get_schedule = function(){
        scope.schedule_generated = "Hello!";
        request.post("/schedule").then(function(resp){
            DataManager.init(resp.data);
            scope.dates = DataManager.dates;
            scope.schedule = DataManager.mapped_data;
        } , function(){});
    }
    
    function groupByDate(arr  , ic , dates){
        var grouped_data = [];
        for(var i = 0; i< dates.length; i++){
            grouped_data.push(
                $(arr).filter(function(index ,element){
                    return element.date == dates[i] && element.islamic_center == ic;
                })
            );
        }
        return grouped_data;
    }
    
    
    request.post("/checkScheduleExistence").then(function(resp){
        var has_schedule = Boolean(resp.data);
        if(has_schedule){
            scope.get_schedule();
        }
    } , function(){});
    
    scope.generate = function(){
        
    }
    
}]);