var rates = {};
$(".stars").raty({
    number: 7,
    starType: "i",
    cancel: true,
    click: function(score , event){
        rates[this.id] = (!!score) ? score : 0;
    }
});
// Token Mismatch error.........although its not a form!
$("#submit-rates").on("click" , function(){
    $.ajax({
        url: "/user/rating",
        type: "POST",
        data: {ratings: rates},
        success: function(resp){},
        error: function(err){
            ISGH.alertBox.init("Something went wrong, Please refresh and try again!" , false);
        }
    });
});

$(document).ready(function(){
    $(".stars").each(function(offset , element){
        rates[element.id] = 0;
    });
});