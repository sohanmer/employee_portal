@extends('layouts.app')

@section('content')
    <div class="container">
    <a href="/employee_portal/public/home" class="btn btn-default">Go Back</a>
    @if($abc['cover_image'] != "nothing")
        <h1>{{$abc['file_name']}}</h1>
        <img src="/storage/cover_images/{{$abc['cover_images']}}">
        <br><br>
        <hr>
        <small>Written On {{$abc['created_at']}} by {{$abc['owner_name']}}</small>
        <hr>
    
    @else
        <h1><i class="fas fa-folder"></i></h1>
        <h4>{{$abc['file_name']}}</h4>
        <br><br>
        <hr>
        <small>Written On {{$abc['created_at']}} by {{$abc['owner_name']}}</small>
        <hr>
        <div>
                        
                             
               
            
        </div>
        <br>
        <div class="container">
                {!!Form::open(['action' => 'Eportalcontroller@store', 'method'=>'POST','enctype'=>'multipart/form-data']) !!}
                {{Form::file('cover_image')}}
                {{Form::submit('Submit', ['class'=> 'btn btn-primary'])}}
                {!! Form::close() !!}
            
            
                <h3>Or Create A Directory</h3>
                {!!Form::open(['action' => 'Eportalcontroller@store' ,$abc->id, 'method'=>'POST', 'enctype'=>'multipart/form-data']) !!}
                {!! Form::hidden('id', $abc['id']) !!}
                {{Form::text('file_name')}}
                {{Form::submit('Create', ['class'=> 'btn btn-primary'])}}
                {!! Form::close()!!}
            </div>
        
    @endif
   
    @if(!Auth::guest())
        @if(Auth::user()->id == $abc["owner_id"])
            {!!Form::open(['action'=>['Eportalcontroller@destroy', $abc['id']], 'method'=>'POST'])!!}
                {{Form::hidden('_method','DELETE')}}
                {{Form::Submit('Delete',['class'=>'btn btn-danger'])}}
            {!!Form::close()!!}
        @endif
    @endif
    <div>
            <div class="btn btn-success"><a href={{$abc["cover_images"]}} download="{{$abc->cover_images}}" style="color:white">Download</a></div>  
            
            
    </div>
</div>
@endsection