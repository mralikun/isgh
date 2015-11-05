var model = {
    users: []
}

var view = {
    render: function(dataArray){
        console.log(dataArray[0].id);
        for(var i = 0; i < dataArray.length; i++){
            $("#allUsers").append("<div class='row rating-row'><div class='col-sm-7 sol-md-7 col-lg-7'><div class='row'><div class='col-sm-4 col-md-4 col-lg-4'><img class='img-responsive rating-img' src='/images/khateeb_pictures/"+dataArray[i].picture_url+"'></div><div class='col-sm-8 col-md-8 col-lg-8'><h3 class='rating-name'>"+dataArray[i].name+"</h3></div></div></div><div class='col-sm-5 col-md-5 col-lg-5 stars' id='"+dataArray[i].id+"'></div></div>");
        }
    }
}

$(document).ready(function(){
    var getMoreUsers = function(){
        var _id = (model.users.length) ? model.users[model.users.length - 1].id : 1;
        $.ajax({
            type: "POST",
            url: "/user/startRate",
            data: {id: _id , _token: $("input[name='_token']").val()},
            dataType: "json",
            success: function(resp){
                view.render(resp);
                $(".stars").raty({
                    number: 7,
                    starType: "i",
                    cancel: true,
                    click: function(score , event){
                        var _ID = parseInt($(event.target).parents(".stars").attr("id"));
                        $.ajax({
                            type: "POST",
                            url: "/user/rate",
                            dataType: "json",
                            data: {id: _ID , rate: (!!score) ? score : 0 , _token: $("input[name='_token']").val()},
                            success: function(resp){
                                
                            },
                            error: function(err){
                                ISGH.alertBox.init("Something went wrong trying to sumbit your rates, Please refresh and try again" , false);
                            }
                        });
                    }
                });
            },
            error: function(err){
                ISGH.alertBox.init("Couldn't retrieve user data, Please refresh and try again!" , false);
            }
        });
    }
    getMoreUsers();
});