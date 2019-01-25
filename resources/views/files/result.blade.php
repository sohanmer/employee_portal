@extends('layouts.app')

@section('content')
<div class="container">
@foreach($allRecord as $file)
@if($file['file_name']==$searchResult[0])
<div class="well col-md-3 mt-5 mr-5 ml-5 mb-2">
        <div class="row" style="height:25px">
            <div class="col-md-3 col-sm-3"> 
                @if($file['cover_image'] != null)
                    @if($file['type'] != null)
                        <h1 style="margin-top:0"><i class="fas fa-image"></i></h1>
                    @endif
                @else
                    <h1 style="margin-top:0"><i class="fas fa-folder"></i></h1>
                @endif
            </div>
            
            @if($file['cover_image'] == null )
            <div class="col-md-8 col-sm-8">
                    <div class="dropdown">
                            <button class="btn btn-secondary border-0 bg-transparent text-secondary pull-right align-top" style="position:absolute;top:-26px;right:-45px" type="button" id="folder" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                            </button>
                        <h5><a href="{{ URL::route('files.show', $file->id) }}">{{$file['file_name']}}</a></span></h5>
                                                                                                            
                        <div class="dropdown-menu" aria-labelledby="folder">
                          <a class="mb-2 dropdown-item"><i class="far fa-question-circle mr-5 ml-3"></i>  Details</a>
                          <div class="dropdown-divider"></div>
                          <a class="dropdown-item" >
                                @if(!Auth::guest())
                                @if(Auth::user()->id == $file["owner_id"])
                                    {!!Form::open(['action'=>['Eportalcontroller@destroy', $file['id']], 'method'=>'POST'])!!}
                                        {{Form::hidden('_method','DELETE')}}
                                        {{Form::button('<i class="fas fa-trash-alt mr-5"></i>Delete', ['type' => 'submit', 'class' => ' text-danger border-0 align-top bg-transparent border-0 dropdown-item']  )}}                                              
                                    {!!Form::close()!!}
                                @endif
                                @endif 
                         </a>                                       
                     </div>                                  
                    </div>
            </div>
        @else                      
            <div class="col-md-8 col-sm-8">
                    <div class="dropdown">
                            <button class="btn btn-secondary border-0 bg-transparent text-secondary pull-right align-top " style="position:absolute;top:-26px;right:-45px" type="button" id="file" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                            </button>
                <h5>{{substr($file['file_name'],0,8)}}... ({{$file['type']}})</h5>
                            <div class="dropdown-menu" aria-labelledby="file">
                            <a href="files/create?id={{$file['id']}}" class="dropdown-item"><i class="fas fa-download  ml-4 mr-4"></i>Download</a>
                            <a class="mb-2 dropdown-item"><i class="far fa-question-circle mr-3 ml-4"></i>  Details</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" >
                    @if(!Auth::guest())
                        @if(Auth::user()->id == $file["owner_id"])
                            {!!Form::open(['action'=>['Eportalcontroller@destroy', $file['id']], 'method'=>'POST'])!!}
                                {{Form::hidden('_method','DELETE')}}
                                {{Form::button('<i class="fas fa-trash-alt"></i>', ['type' => 'submit', 'class' => ' text-danger border-0 align-top border-0 bg-transparent mr-2 ml-3']  )}} Delete
                            {!!Form::close()!!}
                        @endif
                    @endif 
                </a>                                       
            </div>                                  
           </div>
   </div>                  
        @endif                                                    
        </div>
    </div>
@php
    $flag = 1;
@endphp
@endif
@endforeach
@if($flag!=1){
<h1>No Record Found</h1>
@endif
</div>
@endsection
    
