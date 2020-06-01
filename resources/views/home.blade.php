@extends('layouts.master')

	<div class="title m-b-md">
	    Finance test
	</div>

	<div class="links">
	    <a href="javascript:void(0)" data-toggle="modal" data-target="#registerModal">Register</a>
	    <a href="javascript:void(0)" data-toggle="modal" data-target="#loginModal">Login</a>
	</div>

    <!-- Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Register</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
               <form method="POST" action="/register" id="register">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
             
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
             
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
             
                    <div class="form-group">
                        <label for="password_confirmation">Password Confirmation:</label>
                        <input type="password" class="form-control" id="password_confirmation"
                               name="password_confirmation">
                    </div>
             
                    <div class="form-group">
                        <label for="password_confirmation">Agree to toc:</label>
                        <input type="checkbox" name="toc" class="form-control" id="toc">
                        <input type="hidden" class="form-control" id="checkemail" value="/check-email">
                    </div>
             
                    <div class="form-group">
                        <button style="cursor:pointer" type="submit" class="btn btn-primary">Submit</button>
                    </div>
                    <div id="validation-errors"></div>
                </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Login</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
               <form method="POST" action="/login" id="login">
                    {{ csrf_field() }}
             
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" name="email">
                    </div>
             
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
             
                    <div class="form-group">
                        <button style="cursor:pointer" type="submit" class="btn btn-primary">Submit</button>
                    </div>
                    <div id="validation-errors_l"></div>
                </form>
          </div>
        </div>
      </div>
    </div>


<script>
    $(document).ready(function(){
        $('#register').on('submit', function(e){
            e.preventDefault();
            /* Submit form data using ajax*/
            if ($("#toc").is(":checked")) {
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response){
                     //------------------------
                        $('#validation-errors').html('');
                        $('.close').click();
                        $("#register")[0].reset();
                     //--------------------------
                    },
                    error: function(error){
                        $('#validation-errors').html('');
                        $.each(error.responseJSON.errors, function(key,value) {
                            $('#validation-errors').append('<div class="alert alert-danger">'+value+'</div');
                        });
                    }
                });
            } else {
                $('#validation-errors').html('');
                $('#validation-errors').append('<div class="alert alert-danger">'+'You must agtee to the terms'+'</div');
            }

        });


       $("#email").keyup(function(){
            var email = $(this).val().trim();

            if(email != ''){
                $.ajax({
                    url: '/check-email',
                    type: 'post',
                    data: { email: email, "_token": "{{ csrf_token() }}", },
                    success: function(response){
                        $('#validation-errors').html('');
                    },
                    error: function(error) {
                        $('#validation-errors').html('');
                        $.each(error.responseJSON.errors, function(key,value) {
                            $('#validation-errors').append('<div class="alert alert-danger">'+value+'</div');
                        });
                    }
                });
            }
        });

        $('#login').on('submit', function(e){
            e.preventDefault();
            $('#validation-errors_l').html('');
            /* Submit form data using ajax*/
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function(response){
                 //------------------------
                    $('#validation-errors_l').html('');
                    $('.close').click();
                    $("#login")[0].reset();
                    location.href = "/profile";
                 //--------------------------
                },
                error: function(error){
                    $('#validation-errors_l').html('');
                    $('#validation-errors_l').append('<div class="alert alert-danger">'+error.responseJSON.message+'</div');
                }
            });

        });

    });

</script>