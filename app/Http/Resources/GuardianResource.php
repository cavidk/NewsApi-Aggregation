<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GuardianResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'sectionId' => $this->sectionId,
            'sectionName' => $this->sectionName,
            'webPublicationDate' => $this->webPublicationDate,
            'webTitle' => $this->webTitle,
            'webUrl' => $this->webUrl,
            'apiUrl' => $this->apiUrl,
            'isHosted' => $this->isHosted,
            'pillarId' => $this->pillarId,
            'pillarName' => $this->pillarName,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
