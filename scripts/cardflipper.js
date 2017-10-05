/*Card Flipper Javascript File*/
$(document).ready(function() {
    var theContainer = $('#play-card-container');
    theContainer.flip({axis: 'x', trigger: 'click'});
    theContainer.on('click', function(){
        flip();
    });
    $('#flip-link').on('click', function(){
        theContainer.click();
    });
    $('div.back').css('visibility', 'visible');
});

var flip = function() {
    $('audio').toggle();
    $('iframe').toggle();
    var sess = $('input#sess').val();
    $.ajax({
        url: "AddActivity.php?PHPSESSID="+sess,
        success: function(response){
            console.log(response);
        }
    });
};