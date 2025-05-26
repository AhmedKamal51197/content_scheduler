<?php

namespace App\Http\Controllers\Api;

use App\Enums\PlatformStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostPlatformStoreRequest;
use App\Http\Requests\UpdatePostScheduledTimeRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Exception;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function postPlatfromStore(PostPlatformStoreRequest $request)
    {
        $data = $request->validated();
        try{
             if($request->hasFile('image_url')){
                $data['image_url'] = uploadImage($request->file('image_url'), 'Posts');
             }
            $post = auth()->user()->posts()->create([
                'title' => $data['title'],
                'content' => $data['content'],
                'image_url' => $data['image_url'] ?? null,
                'scheduled_time' => $data['scheduled_time'],
                'status' => $data['status'],
            ]);
            foreach($data['platforms'] as $platform){
                //check if the user is already joined the platform  and it is active
                
                if(!auth()->user()->platforms()->where('platform_id', $platform['id'])->wherePivot('status',PlatformStatus::Active)->exists()){
                    return $this->failure('User not joined this platform or platform is inactive');
                }
               
                $post->platforms()->attach($platform['id'], ['status' => PlatformStatus::Active]);
            }
          return $this->success(data: new PostResource($post), message: 'Post created successfully');
        }catch(Exception $e){
            return $this->failure('Error occurred: '.$e->getMessage().' => please try again');
        }
    }

    /**
     *  Get user's posts with filter status
     */ 
    public function userPosts()
    {
        $status = $this->getArrayFromRequest(request('status'));
        $dates = $this->getArrayFromRequest(request('dates'));
        $posts = auth()->user()->posts()
            ->when(!empty($status), function ($query) use ($status) {
                return $this->filterInArray($query, 'status', $status);
            })
            ->when(!empty($dates), function ($query) use ($dates) {
                return $this->filterInArray($query, 'scheduled_time', $dates);
            })
            ->latest()
            ->paginate(10);
            if( $posts->isEmpty()) {
                return $this->success(data: [], message: 'No posts found for this user');
            }
        return $this->success(data: PostResource::collection($posts), message: 'User posts retrieved successfully');
    }
    private function getArrayFromRequest($param): array
    {
        return is_array($param) ? $param : (isset($param) ? explode(',', $param) : []);
    }
    private function filterInArray($query, $column, $values)
    {   
        
        if(!is_array($values)) return $query->Where($column,$values);
        elseif (in_array('all', $values)) return $query; // Skip filtering if 'all' is present
        else return $query->WhereIn($column, $values);
    }

    /* 
     * Update  post scheduled time
     */
    public function updatePostScheduledTime(UpdatePostScheduledTimeRequest $request,Post $post){
                $data = $request->validated();
                // Check if the post belongs to the authenticated user
                if ($post->user_id !== auth()->id()) {
                    return $this->failure('Unauthorized action');
                }
                $post->update([
                    'scheduled_time' => $data['scheduled_time'],
                ]);
                return $this->success(data: new PostResource($post), message: 'Post scheduled time updated successfully');

    }

    /* 
     * Delete post
     */
    public function destroy(Post $post)
    {
        
        if ($post->user_id !== auth()->id()) {
            return $this->failure('Unauthorized action');
        }
        try {
            $post->platforms()->detach(); 
            $post->delete();
            return $this->success(message: 'Post deleted successfully');
        } catch (Exception $e) {
            return $this->failure('Error occurred: '.$e->getMessage().' => please try again');
        }
    }



    
}
