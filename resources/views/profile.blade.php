@extends('layouts.master')

@section('content')
@auth

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
                   <form method="POST" action="/update-profile" id="update-profile">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}">
                        </div>
                 
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}">
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
        <script type="text/javascript">
	        $('#update-profile').on('submit', function(e){
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
	                        $("#update-profile")[0].reset();
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




        </script>

@else 

	<p>unauthorized</p>

@endauth
@endsection