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
    $('#toggle-review-card').on('click', function(){
        toggleReviewCard();
    });
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

var toggleReviewCard = function() {
    var sess = $('input#sess').val();
    $.ajax({
        url: "ToggleReviewed_Submit.php?PHPSESSID="+sess,
        success: function(response){
            var reviewCard = $('#toggle-review-card');
            var toggleIcon = reviewCard.find('span.fa');
            toggleIcon.toggleClass('fa-square-o');
            toggleIcon.toggleClass('fa-check-square-o');
            reviewCard.blur();
        }
    });
};
