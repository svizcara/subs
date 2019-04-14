//script for closing/hiding alert messages
setTimeout(function() { 
    $(".alert").fadeTo(5000, 0).slideUp(500, function(){
            $(".alert").alert('close');
        });
    }, 4500);

//script for changing active links
$('.nav-link').filter(function(){return        this.href==location.href}).parent().addClass('active').siblings().removeClass('active')


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