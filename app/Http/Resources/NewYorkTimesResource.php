<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewYorkTimesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'rank' => $this->rank,
            'subtype'=> $this->subtype,
            'caption'=> $this->caption->nullable(),
            'credit'=> $this->credit->nullable(),
            'type'=> $this->type,
            'url'=> $this->url,
            'height'=> $this->height,
            'width'=> $this->width,
            'legacy'=> $this->legacy,
            'subType'=> $this->subType,
            'crop_name'=> $this->crop_name,
        ];
    }
}
