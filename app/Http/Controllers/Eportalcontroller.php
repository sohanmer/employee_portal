<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Directories;
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
        $abc = Directories::all();
        return view('files.index')->with('abc', $abc);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('files.create');
        
    }
    
   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        if($request->hasFile('cover_image')){
            $this->validate($request,[
                'cover_image'=>'mimes:docx,txt,pdf,ppt,jpeg,jpg,png,bmp|nullable|max:10000000'                     
                ]);
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);

            $file = new Directories;
            $file->owner_id = auth()->user()->id;
            $file->uploaded_by = auth()->user()->email;
            $file->parent_directory = "kuch nahi";
            $file->cover_image = $path;
            $file->owner_name = auth()->user()->name;
            $file->type = $extension;
            $file->file_name = $filenameWithExt;
            $file->save();
            return redirect('/files');
            
        }
        
        
        else{
            
            $file = new Directories;
            $file->owner_id = auth()->user()->id;
            $file->uploaded_by = auth()->user()->email;
            $file->parent_directory = $request->id;
            $file->cover_image = 'nothing';
            $file->owner_name = auth()->user()->name;
            $file->type = 'directory';
            $file->file_name = $request->input('file_name');
            $file->save();
            return redirect('/files');
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
        $a=$request->abc;
        $abc = Directories::find($a);
        return view('files.show')->with('abc',$abc);
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
                
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
        $abc = Directories::find($id);
        //var_dump($id).die();
       if($id == $abc["owner_id"]){
            $abc = DB::table('directories')->where('owner_id',$id);
            
        }
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

