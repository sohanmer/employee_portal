<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Directorie;
use DB;

class Eportalcontroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $abc = Directorie::all();
        return view('files.index')->with('abc', $abc);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {   
        $abc = Directorie::find($request->id);
        
        if($abc["cover_image"] != null) {     
        $filename=storage_path("app/".$abc['cover_image']);              
        return response()->download($filename);
        }
        else{
            var_dump("Folder");
        }

        
        
    }
    
   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {       
            if($request->hasFile('cover_image') ){
            $this->validate($request,[
                'cover_image'=>'required|mimes:docx,txt,pdf,pptx,jpeg,jpg,png,bmp|nullable|max:100000000'                     
                ]);
           
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
            
            
            $file = new Directorie;
            $file->owner_id = auth()->user()->id;
            $file->uploaded_by = auth()->user()->email;
            $file->parent_directory =$request->abc ;
            $file->cover_image = $path;
            $file->owner_name = auth()->user()->name;
            $file->type = $extension;
            $file->file_name = $filename;
            $file->save();
            if($request->abc != null)
            {
                return redirect('/show?abc='.$file->parent_directory)->with("success","Successfully Uploaded");
            }
            else{
                return redirect('/files')->with('success','Successfully Uploaded');
            }
            
        }              
        
        else{       
                      
            $file = new Directorie;
            $file->owner_id = auth()->user()->id;
            $file->uploaded_by = auth()->user()->email;
            $file->parent_directory = $request->abc;
            $file->cover_image =  null ;
            $file->owner_name = auth()->user()->name;
            $file->type = null;
            $file->file_name = $request->input('file_name');
            $file->save();
            if($request->abc != null)
            {
                return redirect('/show?abc='.$file->parent_directory)->with("success","Folder Created");
            }
            else{
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
    public function show($filesID)
    {   

        $abc = Directorie::find($filesID);
        $xyz = Directorie::all();
        $abc -> getHierarchy();
        $hierarchy = $abc -> getHierarchy();
        return view('files.show')->with('abc',$abc)->with('xyz',$xyz)->with( 'hierarchy', $hierarchy );
        
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        $abc = Directorie::find($id);
              
        if($id == $abc["owner_id"]){
            $abc = DB::table('directories')->where('owner_id',$id);        
            
        }
        $innercontent = DB::table('directories')->where('parent_directory',$id)->delete();
        $abc->delete();
               
        return redirect('/files')->with('error','Post Removed');

    }
   public function search(Request $request){
        $allRecord = Directorie::all();
        $searchResult = DB::table('directories')->where('file_name',$request->search)->pluck('file_name');
        $typeFound = DB::table('directories')->where('file_name',$request->search)->pluck('type');
        if(count($searchResult)>0){
            return view('files.result')->with('searchResult',$searchResult)->with('typeFound',$typeFound)->with('allRecord',$allRecord);
        }
        else{
            return redirect('/files')->with('error','No File(s) Found');
        }

}

}

