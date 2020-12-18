$(function () {

    $('form').on('submit', function (e) {

      e.preventDefault();
        //Do the ajax request
      $.ajax({
        type: 'post',
        data: $('form').serialize(),

      })
      .done(function(data) {

        // log data to the console so we can see
        console.log(data);
        data  = JSON.parse(data);
        // here we will handle errors and validation messages
        if ( ! data.success) {
                $('#messagebox').html('<div class="uk-alert uk-alert-danger uk-animation-fade">' + data.error + '</div>');
            


        } else {
          // ALL GOOD! just show the success message!
          $('form').html('<div class="uk-alert uk-alert-success uk-animation-fade">' + data.message + '</div>');
          setTimeout(function() { window.location.href="index.php"; }, 1200);
          
        }

    });

    });

  });