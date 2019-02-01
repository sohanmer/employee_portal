@extends("layouts.app") 
@section("content") @hasrole('admin')

<div class="container-fluid">

    <div class="d-inline-block font-weight-bold ml-4">
        <h4><strong>Admin Panel</strong></h4>
    </div>
    <button class="btn btn-primary float-right pull-right align-middle mr-5" type="button">
            <a href="/home" class="text-white" style="text-decoration:none">
                Home
            </a>
        </button>

</div>
<div class="container-fluid">
    <div class="col-md-3 border-right align-items-stretch">
        <table class="table mt-5">
            <tr>
                <td><button onclick="switchUser()" class="bg-transparent border-0">Manage User</button></td>
            </tr>
            <tr>
                <td><button onclick="switchMostViewed()" class="bg-transparent border-0">Most viewed items</button></td>
            </tr>
        </table>
    </div>
    <div class="col-md-6" style="display:none" id="users">
        <div>
            <h4 class="bold "><strong> Users</strong></h4>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">No. Of Uploads</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                @for($i=0; $i
                <$total; $i++) <tr>
                    <td>{{$users[$i]['name']}}</td>
                    <td>{{$users[$i]['email']}}</td>
                    <td>{{$counts[$i]}}</td>
                    <td> {!!Form::open(['action'=>'AdminController@deleteUser', 'method'=>'get','enctype'=>'multipart/form-data'])!!}
                        {{Form::hidden('id',$users[$i]->id)}} {{Form::hidden('_method','DELETE')}} {{Form::button('
                        <i class="fas fa-trash-alt"></i>', ['type' => 'submit', 'class' => ' text-danger border-0 align-top
                        border-0 bg-transparent mr-2 ml-3'] )}} {!!Form::close()!!}
                    </td>
                    </tr>
                    @endfor
            </table>
        </div>

    </div>
    <div class="col-md-6" style="display: none" id="mostviewed">
        <div>
            <h4 class="bold "><strong> Most Viewed Items</strong></h4>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Frequency</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                @for($i=0; $i
                <count($mostViewed); $i++) <tr>
                    <td>{{$mostViewed[$i]['file_name']}}</td>
                    <td>{{$mostViewed[$i]['uploaded_by']}}</td>
                    <td>@if($mostViewed[$i]['count'] != null) {{$mostViewed[$i]['count']}} @else 0 @endif
                    </td>
                    <td> {!!Form::open(['action'=>'AdminController@admindelete', 'method'=>'get','enctype'=>'multipart/form-data'])!!}
                        {{Form::hidden('id',$mostViewed[$i]->id)}} {{Form::hidden('_method','DELETE')}} {{Form::button('
                        <i class="fas fa-trash-alt"></i>', ['type' => 'submit', 'class' => ' text-danger border-0 align-top
                        border-0 bg-transparent mr-2 ml-3'] )}} {!!Form::close()!!}
                    </td>
                    </tr>
                    @endfor
            </table>
        </div>

    </div>
    <div class="col-md-2 pull-right mt-5">
        <h4 class=" bold"><strong>Statistics</strong></h4>
        <h5 class="bold mt-5">Total Items: {{$itemcounts}}</h5>
        <h5 class="bold">Files: {{$itemcounts-$folderCount}}</h5>
        <h5 class="bold">Folder: {{$folderCount}}</h5>

    </div>
</div>
@else
<h1>
    <center> You do not have authorization</center>
</h1>
@endhasrole

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="jquery.ui.widget.js"></script>
<!-- The basic File Upload plugin -->
<script src="jquery.fileupload.js"></script>
<script>
    if (sessionStorage.getItem('currentPage') =='mostviewed'){
                document.getElementById('users').style.display = 'none';
                document.getElementById('mostviewed').style.display = 'block';
                                                                                 
            }
            
            else{
                document.getElementById('users').style.display = 'block';
                document.getElementById('mostviewed').style.display = 'none';
            }
          
         function switchUser() {          

                      document.getElementById('users').style.display = 'block';
                      document.getElementById('mostviewed').style.display = 'none';
                      sessionStorage.setItem('currentPage','users');
               }
         function switchMostViewed(){
                      document.getElementById('users').style.display = 'none';
                      document.getElementById('mostviewed').style.display = 'block';
                      sessionStorage.setItem('currentPage','mostviewed');
          }

</script>
@endsection