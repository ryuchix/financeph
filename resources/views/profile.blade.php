@extends('layouts.master')
@section('style')
    <link rel="stylesheet" href="{{ asset('css/jquery.Jcrop.min.css') }}">
@endsection
@section('content')
@auth

	<div class="image-container">
		<img src="{{ asset('storage/profile_images/crop/'.auth()->user()->image) }}" width="200px" id="profile-image">
	</div>
	You are login

	<a href="javascript:void(0)" data-toggle="modal" data-target="#editProfileModal">Edit profile</a>
	<a href="{{ url('/logout') }}">logout</a>

		<table id="example1" class="table table-bordered table-striped">
		  <thead>
		    <tr>
		      <th>#</th>
		      <th>Name</th>
		      <th>Email</th>
		      <th>Login date</th>
		      <th>Status</th>
		    </tr>
		  </thead>
		  <tbody>
		    @php $count = 1 @endphp
		    @foreach ($users as $user)
		    <tr>
		      <td>{{ $count }}</td>
		      <td>{{ $user->name }}</td>
		      <td>{{ $user->email }}</td>
		      <td>{{ \Carbon\Carbon::createFromTimeStamp(strtotime($user->last_login))->diffForHumans() }}</td>
		      <td>{{ $user->online == '1' ? 'Online' : 'Offline' }}
		      </td>
		    </tr>
		    @php $count++ @endphp
		    @endforeach
		  </tbody>
		  <tfoot>
		    <tr>
		      <th>#</th>
		      <th>Name</th>
		      <th>Email</th>
		      <th>Date Created</th>
		      <th>Action</th>
		    </tr>
		  </tfoot>
		</table>


        <!-- Modal -->
        <div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit profile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                   <form method="POST" action="/update-profile" id="update-profile" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}">
                        </div>
                 
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}">
                        </div>
						@if(session('success'))
						    <div class="alert alert-success">{{session('success')}}</div>
						@endif

				        <div class="form-group">
				            <label for="exampleInputImage">Image:</label>
				            <input type="file" name="profile_image" id="exampleInputImage" class="image">
				            <input type="hidden" name="x1" value="" />
				            <input type="hidden" name="y1" value="" />
				            <input type="hidden" name="w" value="" />
				            <input type="hidden" name="h" value="" />
				        </div>

						<div class="form-group">
						    <p><img id="previewimage" style="width: 100%; height: auto !important"/></p>
						    @if(session('path'))
						        <img src="{{ session('path') }}" />
						    @endif
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

@section('footer')
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="{{ asset('js/jquery.Jcrop.min.js') }}"></script>
    <script>
        jQuery(function($) {
			$(document).on('change', '.image', function() {
				$('#previewimage').data('Jcrop', '');
				$('.jcrop-holder').remove(); 

			   	if(window.FileReader) {
			      	var reader = new FileReader();
			      	reader.onload = function(e) {
						$('#previewimage').attr('src',reader.result);
						$('#previewimage').Jcrop({
							onSelect: showCoords,
							onChange: resetCoords,
							onRelease: resetCoords
					    },function() {
					    	//
					    });
			       }
			       reader.readAsDataURL(this.files[0]);  
			    }
			 });
        });

		function showCoords(c) {
            $('input[name="x1"]').val(c.x);
            $('input[name="y1"]').val(c.y);
            $('input[name="w"]').val(c.w);
            $('input[name="h"]').val(c.h); 
		}

		function resetCoords() {
            $('input[name="x1"]').val(0);
            $('input[name="y1"]').val(0);
            $('input[name="w"]').val(0);
            $('input[name="h"]').val(0); 
		};



        $('#update-profile').on('submit', function(e){
            e.preventDefault();
            var form = $(this);
            /* Submit form data using ajax*/
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data:  new FormData(this),
				processData: false,
				contentType: false,
                success: function(response){
                 //------------------------
                    $('#validation-errors').html('');
                    $('.close').click();
                    $("#update-profile")[0].reset();

                    $('#profile-image').attr('src', response.path);
  
					$('#previewimage').data('Jcrop', '');
					$('.jcrop-holder').remove(); 
					// $(​'.jcrop-holder').empty();
                 //--------------------------
                },
                error: function(error){
                    $('#validation-errors').html('');
                    $.each(error.responseJSON.errors, function(key,value) {
                        $('#validation-errors').append('<div class="alert alert-danger">'+value+'</div');
                    });
                }
            });

        });

    </script>
@endsection


@else 

	<p>unauthorized</p>

@endauth
@endsection