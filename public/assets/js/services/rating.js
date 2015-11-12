var model = {
    users: []
}

var view = {
    render: function(dataArray){
        if(!dataArray.length){
            $(".heading > *").remove();
            $(".heading").append("<h4 class='text-center'>There's is no data yet to be viewed, Please come back later and check this section again.</h4>");
        }
        for(var i = 0; i < dataArray.length; i++){
            $("#allUsers").append("<div class='row rating-row'><div class='col-sm-7 sol-md-7 col-lg-7'><div class='row'><div class='col-sm-4 col-md-4 col-lg-4'><img class='img-responsive rating-img' src='/images/khateeb_pictures/"+dataArray[i].picture_url+"'></div><div class='col-sm-8 col-md-8 col-lg-8'><h3 class='rating-name'>"+dataArray[i].name+"</h3></div></div></div><div class='col-sm-4 col-md-4 col-lg-4 stars' id='"+dataArray[i].id+"' data-rating='"+(dataArray[i].khateeb_rate_ad || dataArray[i].ad_rate_khateeb)+"'></div><span class='col-sm-1 col-md-1 col-lg-1 ok-mark fa'></span></div>");
        }
        disableImgs();
    }
}

$(document).ready(function(){
    var getMoreUsers = function(){
        $.ajax({
            type: "POST",
            url: "/user/startRate",
            data: {_token: $("input[name='_token']").val()},
            dataType: "json",
            success: function(resp){
                view.render(resp);
                $(".stars").raty({
                    number: 7,
                    starType: "i",
                    cancel: true,
                    score: function(){
                        return $(this).attr("data-rating");
                    },
                    click: function(score , event){
                        var _ID = parseInt($(event.target).parents(".stars").attr("id"));
                        var $thisOK = $(event.target).parents(".stars").siblings("span.ok-mark");
                        $.ajax({
                            type: "POST",
                            url: "/user/rate",
                            dataType: "json",
                            data: {id: _ID , rate: (!!score) ? score : 0 , _token: $("input[name='_token']").val()},
                            success: function(resp){
                                if(resp){
                                    $thisOK.removeClass("fa-times").addClass("fa-check");
                                    $thisOK.css("color" , "green").css("visibility" , "visible");
                                    setTimeout(function(){
                                        $thisOK.css("visibility" , "hidden");
                                    },1500);
                                }
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