<!--/main col-->

</div>

</div>

</div>
<!--/.container-->
<footer class="container-fluid">
    <p class="text-right small">2017 Veg Potraviny</p>
</footer>




<!--scripts loaded here-->

<script src="/bower_components/jquery/jquery.min.js"></script>
<script src="/bower_components/tether/dist/js/tether.min.js"></script>
<script src="/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="/js/sortable.js"></script>
<script src="/bower_components/jquery-confirm/jquery.confirm.min.js"></script>
<script src="/bower_components/jquery-validation/jquery.validate.min.js"></script>



<script>
    $(document).ready(function() {

        $('[data-toggle=offcanvas]').click(function() {
            $('.row-offcanvas').toggleClass('active');
        });

        $("#deleteConfirm").confirm();

    });
</script>

<script>
$(function() {
  $('a[rel=popover]').popover({
    html: true,
    container: 'body',
    trigger: 'hover',
    content: function(){return '<img src="'+$(this).data('img') + '" />';}
  });
})
</script>

<script type="text/javascript">
    $(function() {
        $('#select-category').change(function(){
        var params = window.location.pathname.split("/");
        params.shift();
        // 0 - admin
        // 1 - produkty
        // 2 - category id
        // 3 - supermarket name
        params[2] = $(this).val();

        window.location = window.location.origin + "/" + params.join("/");

      })

      $('#select-supermarket').change(function(){
        var params = window.location.pathname.split("/");
        params.shift();
        if(typeof params[3] == "undefined") {
          if(typeof params[2] == "undefined"){
             params[2] = "0";
          }
        }
        params[3] = $(this).val();

        window.location = window.location.origin + "/" + params.join("/");
      })
    })
</script>

<script type="text/javascript">
    $(function() {
      $.validator.setDefaults({
        errorClass: 'form-control-feedback',
        highlight: function(element) {
          $(element).closest('.form-group').addClass('has-danger'),
          $(element).closest('input').addClass('form-control-danger')
        },
        unhighlight: function(element) {
          $(element).closest('.form-group').removeClass('has-danger')
        }




      })

        $.validator.addMethod('strongPassword', function(value, element) {
          return value.length >= 6;
        }, 'Your password must be at least 6 characters long')      

        $("#profile-update").validate({
            rules: {
                email: {
                    required: true,
                    email: true
                }
            },
            messages: {
                email: {
                    required: 'Please enter an email address',
                    email: 'Please enter a <b>valid<b> email address'
                }
            }
        })

         $("#password-update").validate({
          rules: {
            "old-password": {
              required: true,
            },
            "new-password": {
              required: true,
              strongPassword: true
            },
            "new-password2": {
              required: true,
              equalTo: "#new-password"
            }
          },
          messages: {
            required: 'This field is required.'
          }
        })
    })


</script>

</body>
</html>

