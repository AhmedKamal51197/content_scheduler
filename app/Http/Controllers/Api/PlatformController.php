<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlatFormRequest;
use App\Http\Requests\ToggleActivePlatforms;
use App\Http\Requests\UserJoinPlatfromRequest;
use App\Http\Resources\PlatformResource;
use App\Models\Platform;
use Exception;

class PlatformController extends Controller
{
    public function index()
    {
        $platforms = Platform::paginate(10);
        if ($platforms->isEmpty()) {
            return $this->success(data:[],message:'No platforms found');
        }
        return $this->success(data:PlatformResource::collection($platforms));
    }
    public function store(StorePlatFormRequest $request)
    {
        $data = $request->validated();
        try {
            $platform = Platform::create([
                'name' => $data['name'],
                'type' => $data['type'],
            ]);
            return $this->success(data:new PlatformResource($platform),message:'Platform created successfully');
        } catch (Exception $e) {
            return $this->failure('Error occurred: '.$e->getMessage().' => please try again');
        }
    }
    // user join platfroms 
   public function userJoinPlatform(Platform $platform, UserJoinPlatfromRequest $request)
   {
        $data=$request->validated();
        try {
            // Check if the user is already joined the platform  
            if ($platform->users()->where('user_id', auth()->user()->id)->exists()) {
                return $this->failure('User already joined this platform');
            }
            $platform->users()->attach(['user_id'=>auth()->user()->id], ['status' => $data['status']]);
            return $this->success(message:'User joined platform successfully');
        } catch (Exception $e) {
            return $this->failure('Error occurred: '.$e->getMessage().' => please try again');
        }
   }
    public function userPlatforms()
    {
        
        $platforms = Platform::whereHas('users', function($q){
            return  $q->where('user_id',auth()->user()->id);

        })->paginate(10);
        if ($platforms->isEmpty()) {
            return $this->success(data:[],message:'No platforms found for this user');
        }
        return $this->success(data:$platforms);
    }   
    // public function show(Platform $platform)
    // {
    //     return $this->success(data:$platform);
    // }
    


    public function TogglePlatformsActive(ToggleActivePlatforms $request){
            $data = $request->validated();
            $platforms = $data['platforms'];
            foreach ($platforms as $platform) {
                $platformModel = Platform::find($platform['id']);
                if (!$platformModel) {
                    return $this->failure('Platform with id '.$platform['id'].' not found');
                }
                // check if the user is already joined the platform
                if (!$platformModel->users()->where('user_id', auth()->user()->id)->exists()) {
                    return $this->failure('User not joined this platform with id '.$platform['id']);
                }
                // Update the status of the platform for the user
                $platformModel->users()->updateExistingPivot(auth()->user()->id, ['status' => $platform['active'] ? 1 : 0]);

            }
    }

   
}
