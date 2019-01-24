<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class directorie extends Model
{
    protected $table = 'directories';
    

    public function getHierarchy(){
        $currentDirectory = $this->id;
        $parentDirectory = $this->parent_directory;
        $parentFile = $this->file_name;
        $hierarchy = array();
        while($parentDirectory != null){
            
            $parentDirectory=Directorie::find($parentDirectory);
            $parentFile = $parentDirectory['file_name'];
            $parentDirectory = $parentDirectory->parent_directory;
            //$parentFile = $parentDirectory['file_name'];
            array_push($hierarchy,$parentFile);
            
        }
            return array_reverse($hierarchy);
    }

}

