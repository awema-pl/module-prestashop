<?php

namespace AwemaPL\Prestashop\Admin\Sections\Settings\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EloquentSetting extends JsonResource
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
            'key' => $this->key,
            'value' => $this->value,
        ];
    }
}
