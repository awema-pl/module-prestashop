<?php

namespace AwemaPL\Prestashop\User\Sections\Shops\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EloquentShop extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'url' => $this->url,
           'api_key' => $this->api_key,
            'local_language' => $this->local_language,
            'created_at' =>$this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
