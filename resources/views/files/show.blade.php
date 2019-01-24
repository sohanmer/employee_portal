@extends('layouts.app')

@section('content')

@if (!Auth::guest())
    <div style="height:4em">
        <div class="container">
            <div class="d-inline-block font-weight-bold ml-4"><h4><strong><nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent">
                      <li class="breadcrumb-item"><a href="/home">Home</a></li>
                      @foreach($hierarchy as $parent)
                      @foreach($xyz as $file)
                        @if($file['file_name']== $parent)
                      <li class="breadcrumb-item"><a href="/show?abc={{$file->id}}">{{$parent}}</a></li>
                      @endif
                      @endforeach
                      @endforeach
                      <li class="breadcrumb-item active" aria-current="page">{{$abc['file_name']}}</li>
                    </ol>
                  </nav></strong></h4></div>            
            <button class="btn btn-primary dropdown-toggle float-right pull-right align-middle " type="button" id="dropdownMenuButton" data-toggle="dropdown" >
            New
            </button>
                     
    
            <div class="dropdown-menu bg-default" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" data-toggle="modal" data-target="#fileUpload">File</a>
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
                                    
                                    @if($file['cover_image'] == null and $file['parent_directory']!=null)
                                    <div class="col-md-8 col-sm-8">
                                            <div class="dropdown">
                                                    <button class="btn btn-secondary border-0 bg-transparent text-secondary pull-right align-top" style="position:absolute;top:-26px;right:-45px" type="button" id="folder" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                <h5><a href="/show?abc={{$file->id}}">{{$file['file_name']}}</a></span></h5>
                                                                                                                                    
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
                        @endif              
                    @endforeach
                @else
                    <p>No Document created</p>
                @endif
    </div>
@endif
@endsection