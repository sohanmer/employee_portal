@extends('layouts.app') 
@section('content') @if (!Auth::guest())

<!-- Modal -->
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
                    {!! Form::open(['action' => 'ActionController@store', 'method'=>'POST','enctype'=>'multipart/form-data']) !!} {{Form::file('cover_image')}}
                    {{Form::submit('Submit', ['class'=> 'btn btn-primary'])}} {!! Form::close() !!}
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
                    {!! Form::open(['action' => 'ActionController@store', 'method'=>'POST','enctype'=>'multipart/form-data']) !!} {{Form::text('file_name')}}
                    {{Form::submit('Submit', ['class'=> 'btn btn-primary'])}} {!! Form::close() !!}
                </center>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>

<div style="height:4em">
    <div class="container">
        <div class="d-inline-block font-weight-bold ml-4">
            <h4><strong>HOME</strong></h4>
        </div>
        <button class="btn btn-primary dropdown-toggle float-right pull-right align-middle ml-3" type="button" id="dropdownMenuButton"
            data-toggle="dropdown">
            New
            </button>

        <div class="dropdown-menu bg-default" aria-labelledby="dropdownMenuButton" aria-haspopup="true" aria-expanded="false">
            <a class="dropdown-item" data-toggle="modal" data-target="#fileUpload" href="">File</a>
            <a class="dropdown-item" data-toggle="modal" data-target="#createFolder" href="">Folder</a>
        </div>
        <button class="btn btn-primary float-right pull-right align-middle ml-3" type="button" id="buttonview" onClick="switchVisible()">
                    <i class="fas fa-list-ul"></i>
            </button> {!! Form::open(['action'=>'ActionController@search', 'method'=>'POST', 'enctype'=>'multipart/form-data','class'=>
        'form-inline pull-right']) !!} {{Form::text('search')}} {{Form::button('
        <i class="fas fa-search "></i>', ['type' => 'submit', 'class' => ' border-0 align-top ml-sm-1 btn btn-primary'])}}
        {!! Form::close() !!}
    </div>
</div>
@endif
<div class="container" id="grid" style="display:none">
    @if(!Auth::guest()) @if(count($allFile)>0) @foreach($allFile as $file) @if($file['parent_directory']==null)
    <div class="well col-md-3 mt-5 mr-5 ml-5 mb-2">
        <div class="row" style="height:25px">
            <div class="col-md-3 col-sm-3">
                @if($file['cover_image'] != null) @if($file['type'] != null)
                <h1 style="margin-top:0"><i class="fas fa-image"></i></h1>
                @endif @else
                <h1 style="margin-top:0"><i class="fas fa-folder"></i></h1>
                @endif
            </div>
            <div class="modal fade" id="rename{{$file->id}}" tabindex="-1" role="dialog" aria-labelledby="rename" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="fileUploadLabel">Rename ({{$file['file_name']}})</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                        </div>
                        <div class="modal-body">
                            <center>
                                {!! Form::open(['action' => 'ActionController@update', 'method'=>'POST','enctype'=>'multipart/form-data']) !!} {{Form::hidden('page',
                                'home')}} {{Form::hidden('id', $file['id'])}} {{Form::text('newName')}} {{Form::submit('Submit',
                                ['class'=> 'btn btn-primary'])}} {!! Form::close() !!}
                            </center>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="folderinfo{{$file->id}}" tabindex="-1" role="dialog" aria-labelledby="folderinfo" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="folderinfo">Item Details</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                        </div>
                        <div class="modal-body">
                            <h6>Name: {{$file["file_name"]}}</h6>
                            <h6>Owned By: {{$file["owner_name"]}}</h6>
                            <h6>Created On: {{date('d-m-Y',strtotime($file["created_at"]))}}</h6>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
            @if($file['cover_image'] == null and $file['parent_directory']==null)
            <div class="col-md-8 col-sm-8">
                <div class="dropdown">
                    <button class="btn btn-secondary border-0 bg-transparent text-secondary pull-right align-top" style="position:absolute;top:-26px;right:-45px"
                        type="button" id="folder" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                        </button>
                    <h5><a href="{{ URL::route('files.show', $file->id) }}">{{$file['file_name']}}</a></h5>

                    <div class="dropdown-menu" aria-labelledby="folder">
                        <a class="mb-2 dropdown-item" data-toggle="modal" data-target="#folderinfo{{$file->id}}"><i class="far fa-question-circle mr-5 ml-3"></i>  Details</a>
                        <a class="dropdown-item">
                                            @if(!Auth::guest())
                                            @hasrole('admin')
                                                {!!Form::open(['action'=>['ActionController@destroy', $file['id']], 'method'=>'POST'])!!}
                                                    {{Form::hidden('_method','DELETE')}}
                                                    {{Form::button('<i class="fas fa-trash-alt mr-5"></i>Delete', ['type' => 'submit', 'class' => ' text-danger border-0 align-top bg-transparent border-0 dropdown-item']  )}}                                              
                                                {!!Form::close()!!}
                                        <a class="mb-2 dropdown-item" data-toggle="modal" data-target="#rename{{$file->id}}"><i class="fas fa-edit  ml-3 mr-5"></i>Rename</a>                        @else @if((Auth::user()->id == $file["owner_id"])) {!!Form::open(['action'=>['ActionController@destroy',
                        $file['id']], 'method'=>'POST'])!!} {{Form::hidden('_method','DELETE')}} {{Form::button('
                        <i class="fas fa-trash-alt mr-5"></i>Delete', ['type' => 'submit', 'class' => ' text-danger border-0
                        align-top bg-transparent border-0 dropdown-item'] )}} {!!Form::close()!!}
                        <a class="mb-2 dropdown-item" data-toggle="modal" data-target="#rename{{$file->id}}"><i class="fas fa-edit  ml-3 mr-5"></i>Rename</a>                        @endif @endhasrole @endif
                        </a>
                    </div>
                </div>
            </div>
            @else
            <div class="col-md-8 col-sm-8">
                <div class="dropdown">
                    <button class="btn btn-secondary border-0 bg-transparent text-secondary pull-right align-top " style="position:absolute;top:-26px;right:-45px"
                        type="button" id="file" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                            </button>
                    <h5>{{substr($file['file_name'],0,8)}}... ({{$file['type']}})</h5>
                    <div class="dropdown-menu" aria-labelledby="file">
                        <a href="files/create?id={{$file['id']}}" class="dropdown-item"><i class="fas fa-download  ml-4 mr-4"></i>Download</a>
                        <a class="mb-2 dropdown-item" data-toggle="modal" data-target="#folderinfo{{$file->id}}"><i class="far fa-question-circle mr-3 ml-4"></i>  Details</a>                        @if(!Auth::guest()) @hasrole('admin')
                        <a class="dropdown-item">
                                            {!!Form::open(['action'=>['ActionController@destroy', $file['id']], 'method'=>'POST'])!!}
                                                {{Form::hidden('_method','DELETE')}}
                                                {{Form::button('<i class="fas fa-trash-alt"></i>', ['type' => 'submit', 'class' => ' text-danger border-0 align-top border-0 bg-transparent mr-2 ml-3']  )}} Delete
                                            {!!Form::close()!!}
                                        </a>
                        <a class="mb-2 dropdown-item" data-toggle="modal" data-target="#rename{{$file->id}}"><i class="fas fa-edit  ml-4 mr-4"></i>Rename</a>                        @else @if(Auth::user()->id == $file["owner_id"])
                        <a class="dropdown-item">
                                            {!!Form::open(['action'=>['ActionController@destroy', $file['id']], 'method'=>'POST'])!!}
                                                {{Form::hidden('_method','DELETE')}}
                                                {{Form::button('<i class="fas fa-trash-alt"></i>', ['type' => 'submit', 'class' => ' text-danger border-0 align-top border-0 bg-transparent mr-2 ml-3']  )}} Delete
                                            {!!Form::close()!!}
                                        </a>
                        <a class="mb-2 dropdown-item" data-toggle="modal" data-target="#rename{{$file->id}}"><i class="fas fa-edit  ml-4 mr-4"></i>Rename</a>                        @endif @endhasrole @endif

                    </div>
                </div>
            </div>

            @endif
        </div>
    </div>
    @endif @endforeach @else
    <p>No Document created</p>
    @endif @else
    <h1>Please Login First</h1>
    @endif
</div>
<div class="container" id="list" style="display:none">
    @if(!Auth::guest()) @if(count($allFile)>0)
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Owner</th>
                <th scope="col">Last Modified</th>
                <th scope="col">Action Permitted</th>
            </tr>
        </thead>
        @foreach($allFile as $file) @if($file['parent_directory']==null)
        <tbody>
            <tr>
                @if($file['cover_image'] != null) @if($file['type'] != null)
                <h3>
                    <th><i class="fas fa-image mr-2"></i>{{$file['file_name']}}</th>
                </h3>
                @endif @else
                <h3>
                    <th><a href="{{ URL::route('files.show', $file->id) }}"><i class="fas fa-folder mr-2 "></i>{{$file['file_name']}}</a></th>
                </h3>
                @endif

                <td>{{$file['owner_name']}}</td>
                <td>{{date('d-m-Y',strtotime($file["updated_at"]))}}</td>
                <td>
                    <ul class="list-inline">
                        @if($file['type']!=null)
                        <li><a href="files/create?id={{$file['id']}}" class="dropdown-item"><i class="fas fa-download "></i></a></li>
                        @else
                        <li style="padding-left:3.5em;"></li>
                        @endif @if(!Auth::guest())
                        <div class="modal fade" id="renamelist{{$file->id}}" tabindex="-1" role="dialog" aria-labelledby="rename" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="fileUploadLabel">Rename</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                  </button>
                                    </div>
                                    <div class="modal-body">
                                        <center>
                                            {!! Form::open(['action' => 'ActionController@update', 'method'=>'POST','enctype'=>'multipart/form-data']) !!} {{Form::hidden('page',
                                            'home')}} {{Form::hidden('id', $file['id'])}} {{Form::text('newName')}} {{Form::submit('Submit',
                                            ['class'=> 'btn btn-primary'])}} {!! Form::close() !!}
                                        </center>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                        @hasrole('admin')
                        <li>
                            {!!Form::open(['action'=>['ActionController@destroy', $file['id']], 'method'=>'POST'])!!} {{Form::hidden('_method','DELETE')}}
                            {{Form::button('
                            <i class="fas fa-trash-alt"></i>', ['type' => 'submit', 'class' => ' text-danger border-0 border-0
                            bg-transparent'] )}} {!!Form::close()!!}
                        </li>
                        <li data-toggle="modal" data-target="#renamelist{{$file->id}}"><i class="fas fa-edit  ml-4 mr-4"></i></li>
                        @else @if(Auth::user()->id == $file["owner_id"])
                        <li>
                            {!!Form::open(['action'=>['ActionController@destroy', $file['id']], 'method'=>'POST'])!!} {{Form::hidden('_method','DELETE')}}
                            {{Form::button('
                            <i class="fas fa-trash-alt"></i>', ['type' => 'submit', 'class' => ' text-danger border-0 border-0
                            bg-transparent'] )}} {!!Form::close()!!}
                        </li>
                        <li data-toggle="modal" data-target="#renamelist{{$file->id}}"><i class="fas fa-edit  ml-4 mr-4"></i></li>
                        @endif @endhasrole @endif

                    </ul>
                </td>
            </tr>
        </tbody>
        @endif @endforeach
    </table>
    @else
    <p>No Document created</p>
    @endif @else
    <h1>Please Login First</h1>
    @endif
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="jquery.ui.widget.js"></script>
<!-- The basic File Upload plugin -->
<script src="jquery.fileupload.js"></script>

<script>
    if (sessionStorage.getItem('currentView') =='list'){
                document.getElementById('grid').style.display = 'none';
                document.getElementById('list').style.display = 'block';
                $("#buttonview").find("i").removeClass("fa-list-ul").addClass("fa-th-large");
                                                                   
            }
            
            else{
                document.getElementById('grid').style.display = 'block';
                document.getElementById('list').style.display = 'none';
                $("#buttonview").find("i").removeClass("fa-th-large").addClass("fa-list-ul");
            }
            function switchVisible() {
            
                if (document.getElementById('grid').style.display == 'none' && (sessionStorage.getItem("currentView")=="list")) {
                        document.getElementById('grid').style.display = 'block';
                        document.getElementById('list').style.display = 'none';
                        sessionStorage.setItem('currentView', "grid");
                        $("#buttonview").find("i").removeClass("fa-th-large").addClass("fa-list-ul");
                        
                    }
                else {
                        document.getElementById('grid').style.display = 'none';
                        document.getElementById('list').style.display = 'block';
                        sessionStorage.setItem("currentView", "list");
                        $("#buttonview").find("i").removeClass("fa-list-ul").addClass("fa-th-large");
                        
                    }
                
            }

</script>
@endsection