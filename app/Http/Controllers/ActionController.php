<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Folder;
use Auth;
use DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;
use Response;

class ActionController extends Controller
{   
    use HasRoles;

   protected $guard_name = 'web';
    
    public function index(Request $request )
    {   
        if($request->type == null){
        $allFile = Folder::all();
        }
        else 
        {   
            $filteredFiles = array();
            foreach(Folder::all() as $item)
            {
                if(in_array($item['type'], config('constants.filters')[$request->type]) || $item['type'] == null  )
                array_push( $filteredFiles,$item);
            }
            $allFile = $filteredFiles;
        }
        return view('files.index')->with('allFile', $allFile);
    }

    
    public function store(Request $request)
    {       
            if($request->hasFile('cover_image'))
            {   
                $this->validate($request,[
                    'cover_image'=>'required|mimes:docx,txt,pdf,pptx,jpeg,jpg,png,bmp|nullable|max:100000000'                     
                    ]);
            
                $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('cover_image')->getClientOriginalExtension();
                $fileNameToStore = $filename.'_'.time().'.'.$extension;
                $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
                
                
                $file = new Folder;
                $file->owner_id = auth()->user()->id;
                $file->uploaded_by = auth()->user()->email;
                $file->parent_directory = $request->fileId;
                $file->cover_image = $path;
                $file->owner_name = auth()->user()->name;
                $file->type = $extension;
                $file->file_name = $filename;
                $file->save();


                if($request->fileId != null)
                {
                    return redirect(route('files.show',$file->parent_directory))->with("success","Successfully Uploaded");
                }
                                   
                    return redirect('/files')->with('success','Successfully Uploaded');
                           
        }              
        
        else{       
                      
                $file = new Folder;
                $file->owner_id = auth()->user()->id;
                $file->uploaded_by = auth()->user()->email;
                $file->parent_directory = $request->fileId;
                $file->cover_image =  null ;
                $file->owner_name = auth()->user()->name;
                $file->type = null;
                $file->file_name = $request->input('file_name');
                $file->save();

                if($request->fileId != null)
                {
                    return redirect(route('files.show',$file->parent_directory))->with("success","Folder Created");
                }
                                 
                    return redirect('/files')->with('success','Folder Created');
                
                
        }
       
       
    }   
   
    
    public function show($fileId, Request $request)
    {   
        
        if((($request->session()->get('filter') == null || $request->session()->get('filter')== '') && $request->type==null) || $request->type == null)
        {
        $allItem = Folder::all();
        }
        else 
        {
               
                $filteredFiles = array();
                foreach(Folder::all() as $item)
                {
                    if(in_array($item['type'], config('constants.filters')[$request->type]) || $item['type'] == null  )
                    {   
                       
                        array_push( $filteredFiles,$item);
                    }
                }

                $allItem = $filteredFiles;
                
            
        }
        $file = Folder::find($fileId);
        Folder::where('id',$fileId)->update(['count'=> $file['count']+ 1]);
        $hierarchy = $file -> getHierarchy();

        return view('files.show')->with([
                                            'file'=>$file,
                                            'allItem'=>$allItem,
                                            'hierarchy'=> $hierarchy,
                                                                                       
                                        ]);
        }
                                               
    

   
    public function edit($id)
    {   
        $content = $directory->content();
    }

    
    public function update(Request $request)
    {   
        $parentDirectory = Folder::find($request->parentDirectory); 
        DB::table("folders")->where('id',$request->id)->update(["file_name"=>$request->newName]);
        if($request->page=="home")
        {
            return redirect('/files')->with("success","File Renamed Successfully");
        }
      
        return redirect(route("files.show",$parentDirectory->id))->with("success","File Renamed Successfully");
             
    }

   
    
    public function destroy($id)
    {   
        $deleteFile = Folder::find($id);
              
        if($id == $deleteFile["owner_id"])
        {
            $deleteFile = DB::table('folders')->where('owner_id',$id);      
        }

        $parent = DB::table('folders')->where('id',$id)->pluck('parent_directory');
        $children = DB::table('folders')->where('parent_directory',$id)->delete();
        $deleteFile->delete();

        if($parent == null)
        {       
            return redirect('/files')->with('error','Post Removed');
        }
           
            return redirect(route('files.show',$parent[0]))->with('error','Post Removed');   
        
    }


   public function search(Request $request)
   {
        $allRecord = Folder::all();
        $searchResult = DB::table('folders')->where('file_name','like',$request->search.'%')->pluck('file_name');
        $typeFound = DB::table('folders')->where('file_name','like',$request->search.'%')->pluck('type');
        
        if(count($searchResult)>0){
            return view('files.result')->with([
                                                'searchResult'=>$searchResult,
                                                'typeFound'=>$typeFound,
                                                'allRecord'=>$allRecord
                                                ]);
        }
       
        return redirect('/files')->with('error','No File(s) Found');
        
    }
    public function download(Request $request)
    {
        $file = Folder::where('id', $request->id)->pluck('cover_image');
        return Response::download(trim(public_path(),'public').'storage/app/'.$file[0]);

        

       
    }
    

}

