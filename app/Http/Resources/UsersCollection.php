<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UsersCollection extends ResourceCollection
{
    public $collects = UserResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);

        return [
            'data' => $this->collection,
            'links' => [
                'self' => 'link-value',
            ],
            'other' => [
                'self2' => 'link-value2',
            ],
            'conditional_attribute' => $this->when(true, 'secret-value'), // Auth::user()->isAdmin()
        ];
    }
}
