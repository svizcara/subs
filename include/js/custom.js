//script for closing/hiding alert messages
setTimeout(function() { 
    $(".alert").fadeTo(2000, 0).slideUp(500, function(){
            $(".alert").alert('close');
        });
    }, 2500);

//script for changing active links
$('.nav-link').filter(function(){return        this.href==location.href}).parent().addClass('active').siblings().removeClass('active')
