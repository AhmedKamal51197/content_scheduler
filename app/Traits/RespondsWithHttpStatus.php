<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait RespondsWithHttpStatus
{
    protected function success($message='The operation has been completed successfully', $data = [], $status = Response::HTTP_OK)
    {
        return response([
            'success' => true,
            'data' => $data,
            'message' => $message,
        ], $status);
    }

    protected function successWithPagination($message, $data = [], $status = Response::HTTP_OK)
    {
         
        $meta = [
            'total' => $data->total(),
            'per_page' => $data->perPage(),
            'current_page' =>   $data->currentPage(), 
            'last_page' => $data->lastPage(),
            'current_data_on_this_page' => $data->count(),

             // Add any additional meta information you need
        ];
        
        $links = [
            'prev' => $data->previousPageUrl(),
            'next' => $data->nextPageUrl(),
            // Add any additional links you need
        ];
        return response([
            'success' => true,
            'data' => $data->items(),
            'links' => $links,
            'meta' => $meta,
            'message' => $message,
        ], $status);
    }

  
    protected function failure($message, $status = Response::HTTP_BAD_REQUEST)
    {
        return response([
            'success' => false,
            'message' => $message,
        ], $status);
    }

    
}
