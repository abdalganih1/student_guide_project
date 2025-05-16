<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SpecializationCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection, // $this->collection يحتوي على SpecializationResource لكل عنصر
            'links' => [ // يمكنك إضافة معلومات التصفح (pagination) هنا إذا استخدمتها
                // 'first' => $this->url(1),
                // 'last' => $this->url($this->lastPage()),
                // 'prev' => $this->previousPageUrl(),
                // 'next' => $this->nextPageUrl(),
            ],
            // 'meta' => [
            //     'current_page' => $this->currentPage(),
            //     'from' => $this->firstItem(),
            //     'last_page' => $this->lastPage(),
            //     'path' => $this->path(),
            //     'per_page' => $this->perPage(),
            //     'to' => $this->lastItem(),
            //     'total' => $this->total(),
            // ],
        ];
    }
}