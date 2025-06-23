<?php

namespace App\Http\Utils\Traits;

use App\Models\Event;

trait HasEvents
{
    public function events()
    {
        return $this->morphMany(Event::class, 'eventable');
    }

    /**
     * Summary of recordEvent
     * @param string $type ['add', 'delete', 'update']
     * @param array $data
     * @param mixed $userId
     * @return Event
     */
    public function recordEvent(string $type, array $data = [], $userId = null): Event
    {
        $jsonData = json_encode($data);
        return $this->events()->create([
            'user_id' => $userId ?? auth()->user()->id,
            'type' => $type,
            'data' => $jsonData,
        ]);
    }
}
