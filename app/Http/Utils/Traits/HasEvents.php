<?php

namespace App\Http\Utils\Traits;

use App\Models\Event;

trait HasEvents
{
    public function events()
    {
        return $this->morphMany(Event::class, 'eventable');
    }

    public function recordEvent(string $type, array $data = [], $userId = null): Event
    {
        $jsonData = json_encode($data);
        return $this->events()->create([
            'user_id' => 1,
            'type' => $type,
            'data' => $jsonData,
        ]);
    }
}
