@extends('layouts.app') 
@section('content') 
        @if (!Auth::guest())
            @php
            if(session()->get('filter') != null && !isset($_GET['type'])){
                session()->put('filter',session()->get('filter'));
            }
            elseif(isset($_GET['type'])){
                session()->put('filter',$_GET['type']);
            }   
            
            else  {
                    session()->put('filter', null);
                }
            
            @endphp
            <div>
                <div class="container">
                    <div class="font-weight-bold">
                        <h3><strong><nav aria-label="breadcrumb">
                                <ol class="breadcrumb bg-transparent">
                                    <li class="breadcrumb-item" ><a href="/home?type={{session()->get('filter')}}"  style="text-decoration: none">Home</a></li>
                                            @foreach($hierarchy as $parent)
                                                @foreach($allItem as $item)
                                                    @if($item['file_name']== $parent)
                                                        <li class="breadcrumb-item"><a href="{{ URL::route('files.show',  $item->id) }}?type={{session()->get('filter')}}" style="text-decoration: none">{{$parent}}</a></li>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        <li class="breadcrumb-item active" aria-current="page">{{$file['file_name']}}</li>
                                </ol>
                        </nav></strong></h3>
                    </div>
                    <div class="mt-4 mb-5">
                        <button class="btn btn-primary dropdown float-right pull-right align-middle ml-3 " type="button" id="dropdownMenuButton" data-toggle="dropdown">
                            Add
                        </button>
                        <div class="dropdown-menu bg-default" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" data-toggle="modal" data-target="#fileUpload" href="">File</a>
                            <a class="dropdown-item" data-toggle="modal" data-target="#createFolder" href="">Folder</a>
                        </div>
                        <button class="btn btn-primary float-right pull-right align-middle ml-3" type="button" id="buttonview" onClick="switchVisible()">
                            <i class="fas fa-list-ul"></i>
                        </button>
                        <div class="dropdown pull-right ml-3">
                            <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-filter"></i>
                            </a>          
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item disabled" href="#">Show me only...</a>
                                <div class="dropdown-divider"></div>
                                <a href="{{ URL::route('files.show',  $file->id) }}"></a>
                                <a href="{{route('files.show',  $file->id)}}?type={{null}}" class="dropdown-item">All</a>
                                @foreach( array_keys(config('constants.filters')) as $filterType)
                                    <a href="{{route('files.show',  $file->id)}}?type={{$filterType}}" class="dropdown-item">{{$filterType}}</a>
                                @endforeach   
                            </div>
                        </div> 
                        {!! Form::open(['action'=>'ActionController@search', 'method'=>'POST', 'enctype'=>'multipart/form-data','class'=>'form-inline ml-3']) !!}
                        {{Form::text('search')}} 
                        {{Form::button('<i class="fas fa-search "></i>', ['type' => 'submit', 'class' => ' border-0 align-top ml-sm-1 btn btn-primary'])}}
                        {!! Form::close() !!}
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
                                {!! Form::open(['action' => 'ActionController@store', 'method'=>'POST','enctype'=>'multipart/form-data']) !!} 
                                {{Form::file('cover_image')}}
                                {{Form::hidden('fileId', $file['id'])}} 
                                {{Form::submit('Submit', ['class'=> 'btn btn-primary'])}} 
                                {!! Form::close()!!}
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
                                {!! Form::open(['action' => 'ActionController@store', 'method'=>'POST','enctype'=>'multipart/form-data']) !!} 
                                    {{Form::text('file_name')}}
                                    {{Form::hidden('fileId', $file['id'])}} 
                                    {{Form::submit('Submit', ['class'=> 'btn btn-primary'])}}
                                {!!Form::close() !!}
                            </center>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        @endif
        <div class="container" id="grid" style="display:none">
            @if(!Auth::guest()) 
                @if(count($file)>0)     
                    @foreach($allItem as $item) 
                        @if($item['parent_directory']== $file['id'])
                            <div class="well col-md-3 mt-5 mr-5 ml-3 mb-2">
                                <div class="row" style="height:25px">
                                    <div class="col-md-3 col-sm-3">
                                        @if($item['cover_image'] != null) 
                                            @if($item['type'] != null)
                                            <h1 style="margin-top:0"><i class="fas fa-image"></i></h1>
                                            @endif 
                                        @else
                                            <h1 style="margin-top:0"><i class="fas fa-folder"></i></h1>
                                        @endif
                                    </div>
                                    <div class="modal fade" id="rename{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="rename" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="fileUploadLabel">Rename: {{$item['file_name']}}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                            <div class="modal-body">
                                                <center>
                                                    {!! Form::open(['action' => 'ActionController@update', 'method'=>'POST','enctype'=>'multipart/form-data']) !!}
                                                        {{Form::hidden('page','show')}} 
                                                        {{Form::hidden('parentDirectory',$item['parent_directory'])}} 
                                                        {{Form::hidden('id',$item['id'])}} {{Form::text('newName')}}
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
                                <div class="modal fade" id="folderinfo{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="folderinfo" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="folderinfo">Item Details</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <h6>Name: {{$item["file_name"]}}</h6>
                                                <h6>Owned By: {{$item["owner_name"]}}</h6>
                                                <h6>Created On: {{date('d-m-Y',strtotime($item["created_at"]))}}</h6>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>


                                @if($item['cover_image'] == null and $item['parent_directory']!=null)
                                    <div class="col-md-8 col-sm-8">
                                        <div class="dropdown">
                                            <button class="btn btn-secondary border-0 bg-transparent text-secondary pull-right align-top" style="position:absolute;top:-26px;right:-45px"type="button" id="folder" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <h5><a href="{{URL::route('files.show', $item->id) }}?type={{session()->get('filter')}}" onClick="parseURLParams()">{{$item['file_name']}}</a></span> </h5>
                                            <div class="dropdown-menu" aria-labelledby="folder">
                                                <a class="mb-2 dropdown-item" data-toggle="modal" data-target="#folderinfo{{$item->id}}"><i class="far fa-question-circle mr-5 ml-3" data-toggle="modal" data-target="#folderinfo{{$item->id}}"></i>  Details</a>
                                                <a class="dropdown-item">
                                                @if(!Auth::guest())
                                                    @hasrole('admin')
                                                        {!!Form::open(['action'=>['ActionController@destroy', $item['id']], 'method'=>'POST'])!!}
                                                                {{Form::hidden('_method','DELETE')}}
                                                                {{Form::button('<i class="fas fa-trash-alt mr-5"></i>Delete', ['type' => 'submit', 'class' => ' text-danger border-0 align-top bg-transparent border-0 dropdown-item']  )}}                                              
                                                        {!!Form::close()!!}

                                                        <a class="mb-2 dropdown-item" data-toggle="modal" data-target="#rename{{$item->id}}"><i class="fas fa-edit  ml-4 mr-4"></i>Rename</a>                       
                                                @else 
                                                    @if(Auth::user()->id == $item["owner_id"]) 
                                                    {!!Form::open(['action'=>['ActionController@destroy',$item['id']], 'method'=>'POST'])!!} 
                                                        {{Form::hidden('_method','DELETE')}} 
                                                        {{Form::button('<i class="fas fa-trash-alt mr-5"></i>Delete', ['type' => 'submit', 'class' => ' text-danger border-0 align-top bg-transparent border-0 dropdown-item'] )}} 
                                                    {!!Form::close()!!}
                                            <a class="mb-2 dropdown-item" data-toggle="modal" data-target="#rename{{$item->id}}"><i class="fas fa-edit  ml-4 mr-4"></i>Rename</a>     
                                                    @endif 
                                                    @endhasrole 
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
                                            <h5>{{substr($item['file_name'],0,8)}}... ({{$item['type']}})</h5>
                                            <div class="dropdown-menu" aria-labelledby="file">
                                                <a href="/download?id={{$file['id']}}" class="dropdown-item"><i class="fas fa-download  ml-4 mr-4"></i>Download</a>
                                                <a class="mb-2 dropdown-item" data-toggle="modal" data-target="#folderinfo{{$item->id}}"><i class="far fa-question-circle mr-3 ml-4"></i>  Details</a>
                                                <a class="dropdown-item">
                                                    @if(!Auth::guest())
                                                        @hasrole('admin')
                                                            {!!Form::open(['action'=>['ActionController@destroy', $file['id']], 'method'=>'POST'])!!}
                                                                {{Form::hidden('_method','DELETE')}}
                                                                {{Form::button('<i class="fas fa-trash-alt"></i>', ['type' => 'submit', 'class' => ' text-danger border-0 align-top border-0 bg-transparent mr-2 ml-3']  )}} Delete
                                                            {!!Form::close()!!}
                                                            <a class="mb-2 dropdown-item" data-toggle="modal" data-target="#rename{{$item->id}}"><i class="fas fa-edit  ml-4 mr-4"></i>Rename</a>
                                                    @else 
                                                        @if(Auth::user()->id == $item["owner_id"]) 
                                                            {!!Form::open(['action'=>['ActionController@destroy',$file['id']], 'method'=>'POST'])!!} 
                                                                {{Form::hidden('_method','DELETE')}} {{Form::button('<i class="fas fa-trash-alt"></i>', ['type' => 'submit', 'class' => ' text-danger border-0 align-top border-0 bg-transparent mr-2 ml-3'] )}} Delete 
                                                            {!!Form::close()!!}
                                                            <a class="mb-2 dropdown-item" data-toggle="modal" data-target="#rename{{$item->id}}"><i class="fas fa-edit  ml-4 mr-4"></i>Rename</a> 
                                                        @endif 
                                                        @endhasrole 
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
        <div class="container" id="list" style="display:none">
            @if(!Auth::guest()) 
                @if(count($file)>0)
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Owner</th>
                                <th scope="col">Last Modified</th>
                                <th scope="col">Action Permitted</th>
                            </tr>
                        </thead>
                    @foreach($allItem as $item) 
                        @if($item['parent_directory']== $file['id'])
                            <tbody>
                                <tr>
                                    @if($item['cover_image'] != null) 
                                        @if($item['type'] != null)
                                        <h3>
                                            <th><i class="fas fa-image mr-2"></i>{{$item['file_name']}}</th>
                                        </h3>
                                        @endif
                                    @else
                                        <h3>
                                            <th> <a href="{{URL::route('files.show', $item->id) }}?type={{session()->get('filter')}}" onClick="parseURLParams()">{{$item['file_name']}}</a></span> </th>
                                        </h3>
                                    @endif
                                    <td>{{$item['owner_name']}}</td>
                                    <td>{{date('d-m-Y',strtotime($item["updated_at"]))}}</td>
                                    <td>
                                        <ul class="list-inline">
                                            @if($item['type']!=null)
                                                <li><a href="/download?id={{$item['id']}}" class="dropdown-item"><i class="fas fa-download "></i></a></li>
                                            @else
                                                <li style="padding-left:3.5em;"></li>
                                            @endif 
                                            @if(!Auth::guest())
                                                <div class="modal fade" id="renamelist{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="rename" aria-hidden="true">
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
                                                                    {!! Form::open(['action' => 'ActionController@update', 'method'=>'POST','enctype'=>'multipart/form-data']) !!}
                                                                    {{Form::hidden('page','show')}} {{Form::hidden('parentDirectory',$item['parent_directory'])}}
                                                                    {{Form::hidden('id', $item['id'])}} {{Form::text('newName')}} 
                                                                    {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
                                                                    {!! Form::close() !!}
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
                                                        {!!Form::open(['action'=>['ActionController@destroy', $item['id']], 'method'=>'POST'])!!} 
                                                            {{Form::hidden('_method','DELETE')}}
                                                            {{Form::button('<i class="fas fa-trash-alt"></i>', ['type' => 'submit', 'class' => ' text-danger border-0 border-0 bg-transparent'] )}}
                                                        {!!Form::close()!!}
                                                    </li>
                                                    <li data-toggle="modal" data-target="#renamelist{{$item->id}}"><i class="fas fa-edit  ml-4 mr-4"></i></li>
                                            @else 
                                                @if(Auth::user()->id == $item["owner_id"])
                                                    <li>
                                                        {!!Form::open(['action'=>['ActionController@destroy', $item['id']], 'method'=>'POST'])!!} {{Form::hidden('_method','DELETE')}}
                                                            {{Form::button('<i class="fas fa-trash-alt"></i>', ['type' => 'submit', 'class' => ' text-danger border-0 border-0 bg-transparent'] )}}
                                                        {!!Form::close()!!}
                                                    </li>
                                                    <li data-toggle="modal" data-target="#renamelist{{$item->id}}"><i class="fas fa-edit  ml-4 mr-4"></i></li>
                                                @endif 
                                                @endhasrole 
                                            @endif
                                        </ul>
                                    </td>
                                </tr>
                            </tbody>
                        @endif 
                    @endforeach
                </table>
            @else
                <p>No Document created</p>
            @endif
        </div>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

        <script src="jquery.ui.widget.js"></script>

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
        @endif
@endsection