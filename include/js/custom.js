//script for closing/hiding alert messages
setTimeout(function() { 
    $(".alert").fadeTo(5000, 0).slideUp(500, function(){
            $(".alert").alert('close');
        });
    }, 4500);

//script for changing active links
$('.nav-link').filter(function(){return this.href==location.href}).parent().addClass('active').siblings().removeClass('active')


// display preview
function displayImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function (e) {
            $('#photo-preview').attr('src', e.target.result);
            $('#photo-preview').removeAttr('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

//display mobile
$('#displayMobile').click(function(){
    if($('#displayMobile').attr('name')==1) {
        $('#displayMobile').text($('#displayMobile').val());
    } else {
        alert("Sorry, mobile number is kept confidential by seller. Please register and send an online message. Thank you!");
    }
});
