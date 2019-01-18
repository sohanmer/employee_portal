@extends("layouts.app")

@section('content')
<div class="container">
@if($_GET['abc']!=0)
{!! Form::open(['action' => 'Eportalcontroller@store', 'method'=>'POST','enctype'=>'multipart/form-data']) !!}
{{Form::file('cover_image')}}
{{ Form::hidden('abc', $_GET['abc']) }}
{{Form::submit('Submit', ['class'=> 'btn btn-primary'])}}
{!! Form::close() !!}

{!! Form::open(['action' => 'Eportalcontroller@store', 'method'=>'POST','enctype'=>'multipart/form-data']) !!}
{{Form::text('file_name')}}
{{ Form::hidden('abc', $_GET['abc']) }}
{{Form::submit('Submit', ['class'=> 'btn btn-primary'])}}
{!! Form::close() !!}
@else
{!! Form::open(['action' => 'Eportalcontroller@store', 'method'=>'POST','enctype'=>'multipart/form-data']) !!}
{{Form::file('cover_image')}}
{{Form::submit('Submit', ['class'=> 'btn btn-primary'])}}
{!! Form::close() !!}

{!! Form::open(['action' => 'Eportalcontroller@store', 'method'=>'POST','enctype'=>'multipart/form-data']) !!}
{{Form::text('file_name')}}
{{Form::submit('Submit', ['class'=> 'btn btn-primary'])}}
{!! Form::close() !!}
@endif
</div>


@endsection

