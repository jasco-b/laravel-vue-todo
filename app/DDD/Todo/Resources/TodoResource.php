<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-05-23
 * Time: 15:13
 */

namespace App\DDD\Todo\Resources;


use App\DDD\User\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TodoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'task' => $this->task,
            'statusText' => $this->getStatusText(),
            'status' => $this->status,
            'user' => $this->whenLoaded('user') ? new UserResource($this->user) : null,
        ];
    }
}
