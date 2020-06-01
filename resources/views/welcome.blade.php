<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>


        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    Finance test
                </div>

                <div class="links">
                    <a href="javascript:void(0)" data-toggle="modal" data-target="#registerModal">Register</a>
                    <a href="javascript:void(0)" data-toggle="modal" data-target="#loginModal">Login</a>
                </div>
            </div>
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


    </body>
</html>
