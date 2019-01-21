@extends('layouts.app')

@section('content')
@if (!Auth::guest())
    <div class="border-bottom border-primary" style="height:4em">
        <div class="container">
            <div class="d-inline-block font-weight-bold ml-4"><h4><strong><i class="fas fa-folder mr-2"></i>{{$abc['file_name']}}</strong></h4></div>            
            <button class="btn btn-primary dropdown-toggle float-right pull-right align-middle " type="button" id="dropdownMenuButton" data-toggle="dropdown" >
            Add +
            </button>
            <a href="/home" class="btn btn-primary pull-right mr-2">Home</a>
            <span data-toggle="tooltip" data-placement="right" title="Created By {{$abc['owner_name']}} on {{$abc['created_at']}}"><h2><i class="far fa-question-circle pull-right text-primary mr-2 mt-4"></i></h2></span>
    
            <div class="dropdown-menu bg-success" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" data-toggle="modal" data-target="#fileUpload">Upload Document</a>
                    <a class="dropdown-item" data-toggle="modal" data-target="#createFolder">Folder</a>
            </div>
        </div>
    </div>
    
 <div class="modal fade" id="fileUpload" tabindex="-1" role="dialog" aria-labelledby="uploadFileLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="fileUploadLabel">Upload File</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <center>   
                    {!! Form::open(['action' => 'Eportalcontroller@store', 'method'=>'POST','enctype'=>'multipart/form-data']) !!}
                        {{Form::file('cover_image')}}  
                        {{Form::hidden('abc', $abc['id'])}}                     
                        {{Form::submit('Submit', ['class'=> 'btn btn-primary'])}}
                    {!! Form::close() !!} 
                </center>                 
            </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
    <div class="modal fade" id="createFolder" tabindex="-1" role="dialog" aria-labelledby="createFolderLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="createFolderLabel">Create Folder</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <center>   
                    {!! Form::open(['action' => 'Eportalcontroller@store', 'method'=>'POST','enctype'=>'multipart/form-data']) !!}
                        {{Form::text('file_name')}}          
                        {{Form::hidden('abc', $abc['id'])}}             
                        {{Form::submit('Submit', ['class'=> 'btn btn-primary'])}}
                    {!! Form::close() !!} 
                </center>                 
            </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
@endif
        <div class="container">
            @if(!Auth::guest())            
                @if(count($abc)>0)
                    @foreach($xyz as $file)
                        @if($file['parent_directory']== $abc['id'])
                            <div class="well col-md-3 m-5">
                                <div class="row">
                                    <div class="col-md-3 col-sm-3"> 
                                        @if($file['cover_image'] != null)
                                            @if($file['type'] = "jpg")
                                                <h1><i class="fas fa-image"></i></h1>
                                            @endif
                                        @else
                                            <h1><i class="fas fa-folder"></i></h1>
                                        @endif
                                    </div>
                                    
                                        @if($file['cover_image'] == null and $file['parent_directory']!=null)
                                            <div class="col-md-8 col-sm-8">
                                                <h4><a href="/show?abc={{$file['id']}}">{{substr($file['file_name'],0,13)}}</a></h4>
                                                <a href="#"><i class="fas fa-download pull-right ml-4"></i></a>
                                                <div data-toggle="tooltip" data-placement="right" title="Uploaded By {{$file['created_at']}} by {{$file['owner_name']}}"><i class="far fa-question-circle pull-right align-bottom"></i></div>
                                                    @if(!Auth::guest())
                                                        @if(Auth::user()->id == $file["owner_id"])
                                                            {!!Form::open(['action'=>['Eportalcontroller@destroy', $file['id']], 'method'=>'POST'])!!}
                                                                {{Form::hidden('_method','DELETE')}}
                                                                {{Form::button('<i class="fas fa-trash-alt"></i>', ['type' => 'submit', 'class' => ' text-danger border-0 align-top pull-right mr-4', 'style'=>'background-color:transparent']  )}}
                                                            {!!Form::close()!!}
                                                        @endif
                                                    @endif 
                                            </div>
                                        @else                      
                                            <div class="col-md-8 col-sm-8">
                                                <h4>{{substr($file['file_name'],0,13)}}...</h4>
                                                <a href="#"><i class="fas fa-download pull-right ml-4"></i></a>
                                                <div data-toggle="tooltip" data-placement="right" title="Uploaded By {{$file['created_at']}} by {{$file['owner_name']}}"><i class="far fa-question-circle pull-right align-bottom"></i></div>
                                                    @if(!Auth::guest())
                                                        @if(Auth::user()->id == $file["owner_id"])
                                                            {!!Form::open(['action'=>['Eportalcontroller@destroy', $file['id']], 'method'=>'POST'])!!}
                                                                {{Form::hidden('_method','DELETE')}}
                                                                {{Form::button('<i class="fas fa-trash-alt"></i>', ['type' => 'submit', 'class' => ' text-danger border-0 align-top pull-right mr-4']  )}}
                                                            {!!Form::close()!!}
                                                        @endif
                                                    @endif 
                                                </div>     
                                        @endif                                        
                                </div>
                            </div>
                        @endif              
                    @endforeach
                @else
                    <p>No Document created</p>
                @endif
    </div>
@endif
@endsection