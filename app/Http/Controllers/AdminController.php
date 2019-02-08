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


class AdminController extends Controller
{
    public function admin()
    {   
        if(!Auth::guest())
        {  
            $itemcounts=Folder::all();
            $folderCount=Folder::all()->where('type',null);
            $users = Auth::user()->all();
            $counts = array();
            $mostViewed = Folder::where('type','!=', null )->orderBy('count', 'desc')->take(10)->get();
            foreach($users as $user){
                $items = Folder::all()->where('uploaded_by',$user['email']);
                array_push($counts , count($items));
       
        }
       
        return view("admin.adminpanel")->with([
                                                'users' => $users,
                                                'counts'=>$counts,
                                                'total'=>count($users),
                                                'itemcounts'=>count($itemcounts),
                                                'folderCount'=>count($folderCount),
                                                'files'=>$itemcounts,
                                                'mostViewed'=>$mostViewed
                                                ]);
        }

        else
        {
            return view('/home');
        }  
    }



    public function deleteUser(Request $request)
    {
        
        $user = Auth::user()->find($request->id);
        $innercontent = DB::table('folders')->where('owner_id',$request->id)->delete();
        $user->delete();
        return redirect('/admin')->with('error','User Removed');
    }



        
    public function admindelete(Request $request)
    {   
        $abc = Folder::find($request->id);
              
        if($request->id == $abc["owner_id"])
        {
            $abc = DB::table('folders')->where('owner_id',$request->id);     
        }
        $parent = DB::table('folders')->where('id',$request->id)->pluck('parent_directory');
        $innercontent = DB::table('folders')->where('parent_directory',$request->id)->delete();
        $abc->delete();

   
        return redirect('/admin')->with('error','User Removed');
       
    }

}
