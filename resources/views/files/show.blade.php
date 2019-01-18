@extends('layouts.app')

@section('content')
@if (!Auth::guest())
<div class="border-bottom border-primary" style="height:4em">
    <div class="container">
        <div class="d-inline-block font-weight-bold ml-4"><h4><strong><i class="fas fa-folder mr-2"></i>{{$abc['file_name']}}</strong></h4></div>            
            <button class="btn btn-primary dropdown-toggle float-right pull-right align-middle " type="button" id="dropdownMenuButton" data-toggle="dropdown" >
            Add +
            </button>
            <a href="/employee_portal/public/home" class="btn btn-primary pull-right mr-2">Home</a>
            <span data-toggle="tooltip" data-placement="right" title="Created By {{$abc['owner_name']}} on {{$abc['created_at']}}"><h2><i class="far fa-question-circle pull-right text-primary mr-2 mt-4"></i></h2></span>
    
            <div class="dropdown-menu bg-success" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item" href="files/create?abc={{$abc['id']}}">Document</a>
            <a class="dropdown-item" href="#">Folder</a>
            </div>
        </div>
    </div>
@endif
<div class="container">
        <h1>{{$abc['id']}}</h1> 
        </div>
        <br>
  
   
   
</div>
@endsection