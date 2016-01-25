$(window).scroll(function () {
    var winTop = $(this).scrollTop();
    var redHeight = ($('#header').height())/2;
    if (winTop >= redHeight) {
    /*if the scroll reaches the bottom of the red <div> make set '#move' element
      position to absolute so it will move up with the red <div> */

        $('#hamburger').css({
          'top' : '0px'
            // 'position': 'fixed',
            // 'top': '100px',
            //  'bottom': 'auto'
        });

        $('nav').css({
          'top' : '0px'
            // 'position': 'fixed',
            // 'top': '100px',
            //  'bottom': 'auto'
        });

        $('.hambackground').css({
          'top' : '0px'
            // 'position': 'fixed',
            // 'top': '100px',
            //  'bottom': 'auto'
        });

    } else {
      //else revert '#move' position back to fixed

        $('#hamburger').css({
          'top' : '91px'
          // 'bottom' : '20px'
            // 'position': 'fixed',
            // 'top': 'auto',
          //   'bottom': 'auto'
        });

        $('nav').css({
          'top' : '91px'
          // 'bottom' : '20px'
            // 'position': 'fixed',
            // 'top': 'auto',
          //   'bottom': 'auto'
        });

        $('.hambackground').css({
          'top' : '91px'
          // 'bottom' : '20px'
            // 'position': 'fixed',
            // 'top': 'auto',
          //   'bottom': 'auto'
        });
    }
});
