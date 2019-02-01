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

class ActionController extends Controller
{   
    use HasRoles;

   protected $guard_name = 'web';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $allFile = folder::all();
        return view('files.index')->with('allFile', $allFile);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    
   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
                
                
                $file = new folder;
                $file->owner_id = auth()->user()->id;
                $file->uploaded_by = auth()->user()->email;
                $file->parent_directory =$request->fileId ;
                $file->cover_image = $path;
                $file->owner_name = auth()->user()->name;
                $file->type = $extension;
                $file->file_name = $filename;
                $file->save();


                if($request->fileId != null)
                {
                    return redirect(route('files.show',$file->parent_directory))->with("success","Successfully Uploaded");
                }
                else
                {
                    
                    return redirect('/files')->with('success','Successfully Uploaded');
                }            
        }              
        
        else{       
                      
                $file = new folder;
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
                else
                {                    
                    return redirect('/files')->with('success','Folder Created');
                }
                
        }
       
       
    }   
   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($fileId)
    {   
      
        $file = folder::find($fileId);
        folder::where('id',$fileId)->update(['count'=> $file['count']+ 1]);
        $allItem = folder::all();
        $file -> getHierarchy();
        $hierarchy = $file -> getHierarchy();
        return view('files.show')->with([
                                            'file'=>$file,
                                            'allItem'=>$allItem,
                                            'hierarchy'=> $hierarchy 
                                        ]);
                                        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $content = $directory->content();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {   
        $parentDirectory = folder::find($request->parentDirectory); 
        DB::table("folders")->where('id',$request->id)->update(["file_name"=>$request->newName]);
        if($request->page=="home")
        {
            return redirect('/files')->with("success","File Renamed Successfully");
        }
        else
        {
            return redirect(route("files.show",$parentDirectory->id))->with("success","File Renamed Successfully");
        }
       
    }

   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        $deleteFile = folder::find($id);
              
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
        else
        {    
            return redirect(route('/files',$parent[0]))->with('error','Post Removed');   
        }
    }


   public function search(Request $request)
   {
        $allRecord = folder::all();
        $searchResult = DB::table('folders')->where('file_name','like',$request->search.'%')->pluck('file_name');
        $typeFound = DB::table('folders')->where('file_name','like',$request->search.'%')->pluck('type');
        
        if(count($searchResult)>0){
            return view('files.result')->with([
                                                'searchResult'=>$searchResult,
                                                'typeFound'=>$typeFound,
                                                'allRecord'=>$allRecord
                                                ]);
        }
        else
        {
            return redirect('/files')->with('error','No File(s) Found');
        }

    }

}

