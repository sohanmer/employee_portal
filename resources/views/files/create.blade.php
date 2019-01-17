@extends('layouts.app')

@section('content')
<div class="container">
    {!!Form::open(['action' => 'Eportalcontroller@store', 'method'=>'POST','enctype'=>'multipart/form-data']) !!}
    {{Form::file('cover_image')}}
    {{Form::submit('Submit', ['class'=> 'btn btn-primary'])}}
    {!! Form::close() !!}


    <h3>Or Create A Directory</h3>
    {!!Form::open(['action' => 'Eportalcontroller@store' , 'method'=>'POST', 'enctype'=>'multipart/form-data']) !!}
    {{Form::text('file_name')}}
    {{Form::submit('Create', ['class'=> 'btn btn-primary'])}}
    {!! Form::close()!!}
</div>

@endsection