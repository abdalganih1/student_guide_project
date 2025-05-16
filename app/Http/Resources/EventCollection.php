<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class EventCollection extends ResourceCollection
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string|null
     */
    public static $wrap = 'data'; // أو null إذا كنت لا تريد غلاف 'data'

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => EventResource::collection($this->collection),
            // يمكنك تضمين معلومات التصفح (pagination) هنا إذا كنت تستخدمها في المتحكم
            // سأضيف مثالاً عامًا، يمكنك تكييفه إذا كنت تستخدم paginate() في دالة index
            $this->mergeWhen($this->resource instanceof \Illuminate\Pagination\AbstractPaginator, [
                'links' => [
                    'first' => $this->url(1),
                    'last' => $this->url($this->lastPage()),
                    'prev' => $this->previousPageUrl(),
                    'next' => $this->nextPageUrl(),
                ],
                'meta' => [
                    'current_page' => $this->currentPage(),
                    'from' => $this->firstItem(),
                    'last_page' => $this->lastPage(),
                    'path' => $this->path(),
                    'per_page' => $this->perPage(),
                    'to' => $this->lastItem(),
                    'total' => $this->total(),
                ],
            ])
        ];
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function with(Request $request)
    {
        return [
            'meta' => [
                'source' => 'University Events API',
                'timestamp' => now()->toIso8601String(),
            ],
        ];
    }
}