<?php

namespace PixelMacroableExtenders\MacroableExtenders;


use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection as StdCollection;
use Illuminate\Support\Facades\Response;
use Illuminate\Routing\ResponseFactory;

class PixelReponseExtender extends PixelMacroableExtender
{ 
    public function extendMacroable() : void
    {
        $this->defineResponseSuccessMacro();
        $this->defineResponseErrorMacro();
        $this->defineResponseSuccessJsonMacro();
        $this->defineResponseErrorJsonMacro();
        $this->defineResponseSuccessListMacro();
    }

    protected function defineResponseSuccessMacro() : void
    { 
        Response::macro('success', function (array|EloquentCollection|StdCollection $data, string |array $messages = "", int $code = 200): JsonResponse {
            
            /** @var ResponseFactory $this */

            if (is_array($messages)) {
                $messages = join(", ", $messages);
            }
        
            return $this->json([
                'status' => 'success',
                'messages' => $messages,
                'data' => (!is_array($data) ? $data->toArray() : $data)
            ], $code);
        });
    }

    protected function defineResponseErrorMacro() : void
    { 
        Response::macro('error', function (string | array $messages = "", int $code = 500, array $data = []): JsonResponse {
        
            /** @var ResponseFactory $this */

            if (is_array($messages)) {
                $messages = join(", ", $messages);
            }
        
            return $this->json([
                'status' => 'error',
                'message' => $messages,
                'data' =>  $data
            ], $code);
        }); 
    }

    protected function defineResponseSuccessJsonMacro() : void
    { 
        
         //success message or array response
        Response::macro('successJson', function(array $data, int $code = 200): JsonResponse {
            
            /** 
             * @var ResponseFactory $this  
             */
            
            return $this->json($data, $code);
        });
    }
    
    protected function defineResponseErrorJsonMacro() : void
    {
        //error message response
        Response::macro('errorJson', function(string $data, int $code = 500): JsonResponse { 
            
            /** 
             * @var ResponseFactory $this  
             */

            return $this->json($data, $code);
        });
    }

    protected function defineResponseSuccessListMacro() : void
    {
        Response::macro('successList', function (int $total, array|EloquentCollection|StdCollection $data, array $messages = [], int $code = 200): JsonResponse {
            
            /** 
             * @var ResponseFactory $this  
             */

            return $this->json([
                'status' => 'success',
                'data' => (!is_array($data) ? $data->toArray() : $data),
                'total' => $total
            ], $code);
        });
    }
}
 
