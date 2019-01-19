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
    public function create()
    {
        $xyz = Directorie::all();
        return view('files.create')->with('xyz',$xyz);
        
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
                'cover_image'=>'mimes:docx,txt,pdf,ppt,jpeg,jpg,png,bmp|nullable|max:10000000'                     
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
                return redirect('/files');
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
                return redirect('/show?abc='.$file->parent_directory)->with("success","Successfully Uploaded");
            }
            else{
                return redirect('/files');
            }
            
        }
       
       
    }
          
    
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {   

        $abc = Directorie::find($request->abc);
        $xyz = Directorie::all();
        return view('files.show')->with('abc',$abc)->with('xyz',$xyz);
       
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
               
        return redirect('/files')->with('success','Post Removed');

    }
    /*public function download($id){
    var_dump($id).die();
        $abc = Directories::find($id);
          
        $abc = DB::table('directories')->where('owner_id',$id);
        var_dump($abc['cover_image']).die();
        //return response()->download($filename);
    
    }
    // ...*/
}

