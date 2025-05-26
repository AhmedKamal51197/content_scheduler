<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


// upload image
if (!function_exists('uploadImage')) {

    function uploadImage($request, $model = '')
    {
        $model  = Str::plural($model);
        $model  = Str::ucfirst($model);
        $path   = "/Images/" . $model;
        $originalName = $request->getClientOriginalName();
        $imageName = str_replace(' ', '', 'GetPayIn_' . time() . $originalName);
        $request->storeAs($path, $imageName, 'public');
        return $imageName;
    }
}

//delete image
if (!function_exists('deleteImage')) {
    function deleteImage($imageName, $model = '')
    {
        $model  = Str::plural($model);
        $model  = Str::ucfirst($model);
        if ($imageName != 'default.png') {
            $path   = "/Images/" . $model . '/' . $imageName;
            Storage::disk('public')->delete($path);
        }

        Storage::disk('public')->delete($path . '/' . $imageName);
    }
}

//get image
if(!function_exists('getImagePathFromDirectory')){
    function getImagePathFromDirectory( $imageName , $directory = null ,$defaultImage = 'default.svg'  ): string
    {
        $imagePath = public_path('/storage/Images'.'/'.$directory.'/'.$imageName);
        if($imageName && $directory && file_exists($imagePath))
            return asset('/storage/Images') . '/' .$directory.'/'.$imageName;
        else
            return asset('placeholder_images/'.$defaultImage) ;

    }
}
?>