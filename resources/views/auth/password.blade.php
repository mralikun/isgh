@extends("templates.master")


@section("navigation")

    <li><a href="/user/dates">Login</a></li>

@stop


@section("pageTitle")

    Password Reset

@stop


@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2" >
			<div class="panel panel-default" style="margin-top: 20px; padding-bottom: 10px;">
				<div style="background-color: #007bb3 !important;border-radius: 0;color: white;font-size: 1.25em;" class="panel-heading">Password Reset</div>
				<div class="panel-body" style="padding: 20px;">
					@if (session('status'))
						<div class="alert alert-success">
							{{ session('status') }}
						</div>
					@endif

					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>We encountered some errors.</strong>.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					<form class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<div class="form-group">
							<label class="col-md-4 control-label">E-Mail Address</label>
							<div class="col-md-6">
								<input type="email" class="form-control" name="email" value="{{ old('email') }}">
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-isgh">
									Send Password Reset Link
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
