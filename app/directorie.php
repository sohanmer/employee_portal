<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use HasRoles;
class directorie extends Model
{
    protected $table = 'directories';
   

    protected $guard_name = 'web';
    
    public function getHierarchy(){
        $currentDirectory = $this->id;
        $parentDirectory = $this->parent_directory;
        $parentFile = $this->file_name;
        $hierarchy = array();
        while($parentDirectory != null){
            
            $parentDirectory=Directorie::find($parentDirectory);
            $parentFile = $parentDirectory['file_name'];
            $parentDirectory = $parentDirectory->parent_directory;
            array_push($hierarchy,$parentFile);
            
        }
            return array_reverse($hierarchy);
    }
}

