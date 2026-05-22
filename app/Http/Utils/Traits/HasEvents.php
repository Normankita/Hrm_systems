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
    public function recordEvent(string $type, array $data = [], $userId = null, $companyId = null): Event
    {
        $jsonData = json_encode($data);
        $eventData = [
            'user_id' => $userId ?? auth()->user()->id,
            'type' => $type,
            'data' => $jsonData,
        ];
        if ($companyId) {
            $eventData['company_id'] = $companyId;
        }
        return $this->events()->create($eventData);
    }
}
