app.controller("ScheduleController" , ["$scope" , "$http" , function(scope , request){
    
    scope.schedule_generated = false;
    scope.schedule_approved = false;
    scope.processing = true;
    scope.msg = "Checking schedule status , Please wait...";
    scope.editing_mode = false;
    scope.editing_fri = 0;
    var schedule = this;
    var select_el = null;
    var khateebs_edited = [];
    var DataManager = {
        mapped_data: [],
        dates_record: [],
        date_id: function(date_string){
            return $(this.dates_record).filter(function(ind , d){
                return d.date == date_string;
            })[0].id;
        },
        
        date_date: function(id){
            return $(this.dates_record).filter(function(ind , e){
                return e.id == id;
            })[0].date;
        },
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
                    this.dates_record.push({id: obj.friday.id , date: obj.friday.date});
                }
                if(this.islamic_centers.indexOf(obj.islamic_center.name) === -1){
                    this.islamic_centers.push(obj.islamic_center.name);
                }
            }
//            console.log(this.dates_record);
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
    scope.approve_schedule = function(){
        scope.processing = true;
        request.post("/approve").then(function(){
            scope.processing = false;
            scope.schedule_approved = true;
        } , function(){
            ISGH.alertBox.init("Connection Error, Couldn't execute your request!" , false);
        });
    }
    scope.prep_edit = function(event){
        var ic = event.target.getAttribute("data-ic");
        scope.record = DataManager.get_islamic_center_schedule(ic);
//        console.log(scope.record);
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
//            console.log(resp.data[0])
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
        
        khateebs_edited.push({
            prev: (!!previous_value) ? 0 : previous_value,
            current: $(select_el).val()
        });
//        console.log(khateebs_edited);
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
//        console.log(grouped_data);
        return grouped_data;
    }
    
    scope.removeKhateeb = function(obj , kh , event){
        var parent = $(event.target).parent().remove();
        if(!obj.missing)
            obj.missing = [];
        obj.missing.push({
            date_id: kh.friday.id
        });
        khateebs_edited.push({
            islamic_center: scope.record.islamic_center.id,
            friday_id: kh.friday.id,
            prev_value: kh.khateeb_id
        });
    }
    
    scope.addKhateeb = function(obj , fri_id , event){
        request.post("/availableThisFriday/" + fri_id + "/" + scope.record.islamic_center.id).then(function(response){
            scope.available_ops = response.data;
            scope.editing_mode = true;
            scope.editing_fri = fri_id;
            var t = $(khateebs_edited).filter(function(ind , el){
                return el.islamic_center == scope.record.islamic_center.id && el.friday_id == fri_id && !el.current;
            });
            if(!t.length){
                khateebs_edited.push({
                    islamic_center: scope.record.islamic_center.id,
                    friday_id: fri_id,
                    prev_value: 0
                });
            }
            
        });
    }
    scope.markKhateeb = function(fri_id){
        var temp = $(khateebs_edited).filter(function(ind , el){
            return el.islamic_center == scope.record.islamic_center.id && el.friday_id == fri_id && !el.current;
        });
        
        if(temp.length){
            temp = temp[0];
        }
        
        temp.current = parseInt($("select[data-fri='"+fri_id+"']").val() , 10);
        
    }
    
    scope.schedule_edited = false;
    
    scope.editSchedule = function(){
        var d = [];
        for(var i = 0; i < khateebs_edited.length; i++){
            if(khateebs_edited[i].hasOwnProperty("current")){
                d.push(khateebs_edited[i]);
            }
        } 
        if(!khateebs_edited.length){
            ISGH.alertBox.init("You didnt't make any changes to the schedule to save!" , false);
            return false;
        }
        scope.processing = true;

        request.post("/editSchedule" , {data: d}).then(function(response){
            if(response.status == 200){
                scope.processing = false;
                scope.schedule_edited = true;
                for(var i = 0 ; i < khateebs_edited.length; i++){
                    var t = $(DataManager.mapped_data).filter(function(ind , el){
                        return el.islamic_center.id == khateebs_edited[i].islamic_center;
                    })[0];
                    var temp = $(t.khutbahs).filter(function(ind , el){
                        return el.date == DataManager.date_date(khateebs_edited[i].friday_id);
                    });
                    temp[0].missing.splice(temp[0].missing.length - 1 , 1);
                }
                khateebs_edited = [];
            }
        });
    }
    
    scope.generate = function(){
        scope.msg = "Generating , Please wait...";
        scope.processing = true;
        request.get("/when").then(function(res){
            scope.get_schedule();
        } , function(){});
    }
    
    scope.checkApproval = function(){
        request.post("/checkScheduleApprove").then(function(response){
            if(response.data == "false")
                scope.schedule_approved = false;
            else
                scope.schedule_approved = true;
        });
    }
    
    request.post("/checkScheduleExistence").then(function(resp){
        scope.processing = false;
        if(resp.data == 'true'){
            scope.get_schedule();
            scope.checkApproval();
        }else {
            scope.msg = "No Schedule has been generated!, Please click on the 'Generate Schedule' button on the top right.";
        }
    } , function(){});
    
}]);