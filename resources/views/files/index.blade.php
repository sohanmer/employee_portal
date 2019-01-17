@extends('layouts.app')

@section('content')
@if(count($abc)>0)
    @foreach($abc as $file)
        @if($file['parent_directory']=='kuch nahi' or $file['parent_directory']=='nothing')
            <div class="well col-md-3 m-2">
            <div class="row">
                <div class="col-md-3 col-sm-3">
                    @if($file['cover_image'] != "nothing")
                        @if($file['type'] = "jpg")
                            <h1><i class="fas fa-image"></i></h1>
                        @endif
                    @else
                        <h1><i class="fas fa-folder"></i></h1>
                    @endif
                </div>
                @if($file['parent_directory'] != 'kuch nahi' and $file['parent_directory'] == 'nothing' or $file['parent_directory'] == 'kuch nahi'  )
                    <div class="col-md-8 col-sm-8">
                    <h4><a href="/employee_portal/public/show?abc={{$file->id}}">{{$file['file_name']}}</a></h4>
                        <small>Uploaded By {{$file['created_at']}} by {{$file['owner_name']}}</small>
                    </div>
                @endif
            </div>
         </div>
        @endif              
    @endforeach
@else
    <p>No Document created</p>
@endif

@endsection