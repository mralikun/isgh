app.controller("ScheduleController" , ["$scope" , "$http" , function(scope , request){
    
    scope.schedule_generated = false;
    scope.schedule_approved = false;
    scope.processing = true;
    scope.msg = "Checking schedule status , Please wait...";
    var select_el = null;
    var khateebs_edited = [];
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
            
            for(var i = 0 ; i < this.islamic_centers.length; i++){
                var ic = this.islamic_centers[i];
                var dat = $(this.data).filter(function(ind , e){
                    return e.islamic_center.name == ic;
                })[0];
                this.mapped_data.push(
                    {
                        
                        islamic_center: {id: dat.islamic_center.id , name: dat.islamic_center.name , speach_num: dat.islamic_center.speech_num},
                        khutbahs: groupByDate(this.data , this.islamic_centers[i] , this.dates , dat.islamic_center.speech_num)
                        
                    }
                );
            }
            console.log(this.mapped_data);
        },
        
        get_islamic_center_schedule: function(islamic_center){
            var temp = $(this.mapped_data).filter(function(index , element){
                return element.islamic_center.name == islamic_center;
            });
            return temp[0];
        }
    };
    scope.record = undefined ;
    
    scope.prep_edit = function(event){
        var ic = event.target.getAttribute("data-ic");
        scope.record = DataManager.get_islamic_center_schedule(ic);
        console.log(scope.record);
    }
    
    function formatTime(d){
        var hours = d.getHours();
        var mints = d.getMinutes();
        return ((hours < 10) ? "0"+hours : hours) + ":" + ((mints < 10) ? "0"+mints : mints);
    }
    
    scope.get_schedule = function(){
        scope.msg = "Retriving data...";
        scope.processing = true;
        request.post("/schedule").then(function(resp){
            console.log(resp.data[0])
            scope.schedule_generated = true;
            scope.processing = false;
            DataManager.init(resp.data);
            scope.dates = DataManager.dates;
            scope.schedule = DataManager.mapped_data;
            scope.processing = false;
        } , function(){});
    }
    
    scope.handle_change = function(){
        var previous_value = select_el.getAttribute("data-prev-value");
        var date_id = parseInt(select_el.getAttribute("data-date") , 10);
        console.log(previous_value);
    }
    
    scope.set_element = function(ev){
        select_el = ev.target;
    }
    
    function groupByDate(arr  , ic , dates , limit){
        var grouped_data = [];
        for(var i = 0; i < dates.length; i++){
            var data_to_push = $(arr).filter(function(index ,element){
                                    return element.friday.date == dates[i] && element.islamic_center.name == ic;
                                });
            var missing = [];
//            console.log(data_to_push instanceof Array);
            if(data_to_push.length < limit)
                for(var j = 0 ; j < limit - data_to_push.length; j++){
                    var d = $(arr).filter(function(ind ,el){
                        return el.friday.date == dates[i];
                    })[0].friday_id;
                    missing.push({
                        date_id: d
                    });
                }
            else
                missing = [];
            
            grouped_data.push(
                {
                    date: dates[i],
                    data: data_to_push,
                    missing: (missing.length) ? missing : undefined
                }
            );
        }
        return grouped_data;
    }
    scope.handle_khutbah_edit = function(e){
        console.log(e);
//        var el = event.target;

//        
//        var obj = $(kahteebs_edited).filter(function(ind , el){
//            return el.friday == date_id && el.islamic_center == scope.record.islamic_center.id;
//        })[0];
//        console.log(obj);
//        
//        if(!!previous_value){
//            previous_value = parseInt(previous_value , 10);
//            khateebs_edited[kahteebs_edited.indexOf(previous_value)] = el.value;
//        }else {
//            khateebs_edited.push(parseInt(el.value , 10));
//        }
//        el.setAttribute("data-prev-value" , el.value);
    }
    
    scope.generate = function(){
        scope.msg = "Generating , Please wait...";
        scope.processing = true;
        request.get("/when").then(function(res){
            scope.get_schedule();
        } , function(){});
    }
    
    request.post("/checkScheduleExistence").then(function(resp){
        scope.processing = false;
        if(resp.data == 'true'){
            scope.get_schedule();
        }else {
            scope.msg = "No Schedule has been generated!, Please click on the 'Generate Schedule' button on the top right.";
        }
    } , function(){});
    
}]);