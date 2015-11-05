var model = {
    users: []
}

var view = {
    render: function(dataArray){
        for(var i = 0; i < dataArray.length; i++){
            $("#allUsers").append("<div class='row rating-row'><div class='col-sm-5 sol-md-5 col-lg-5'><div class='row'><div class='col-sm-6 col-md-6 col-lg-6'><img class='rating-img' src='/images/khateeb_images/"+dataArray[i].picture_url+"'></div><div class='col-sm-6 col-md-6 col-lg-6'><h3 class='rating-name'>"+dataArray[i].name+"</h3></div></div></div><div class='col-sm-7 col-md-7 col-lg-7 stars' id='"+dataArray[i].id+"'></div></div>");
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
                console.log(resp);
                view.render(resp);
                    $(".stars").raty({
                        number: 7,
                        starType: "i",
                        cancel: true,
                        click: function(score , event){
                            $.ajax({
                                type: "POST",
                                url: "/user/rate",
                                dataType: "json",
                                data: {id: event.target.id , rate: (!!score) ? score : 0},
                                success: function(resp){
                                    console.log(resp);
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