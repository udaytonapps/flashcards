/*Card Flipper Javascript File*/
$(document).ready(function() {
    var theContainer = $('#play-card-container');
    theContainer.flip({axis: 'x', trigger: 'click'});
    theContainer.on('click', function(){
        $('audio').toggle();
        $('iframe').toggle();
        var sess = $('input#sess').val();
        $.ajax({
            url: "AddActivity.php?PHPSESSID="+sess,
            success: function(response){
                console.log(response);
            }
        });
    });
    // Needed to fix SideB flicker
    $('#play-card-column').show();
    $('#next-link').focus();
});