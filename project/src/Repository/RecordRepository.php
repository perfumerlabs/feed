<?php

namespace Feed\Repository;

class RecordRepository
{
    protected $timezone;

    public function __construct($timezone)
    {
        $this->timezone = $timezone;
    }

    public function format($record): ?array
    {
        $created_at = $record['created_at'] ?? null;

        if ($created_at) {
            $date = new \DateTime($created_at);
            $date->setTimezone(new \DateTimeZone($this->timezone));
            $created_at = $date->format('Y-m-d H:i:s');
        }

        $array = [
            'id' => $record['id'] ?? null,
            'recipient' => $record['recipient'] ?? null,
            'sender' => $record['sender'] ?? null,
            'thread' => $record['thread'] ?? null,
            'title' => $record['title'] ?? null,
            'text' => $record['text'] ?? null,
            'image' => $record['image'] ?? null,
            'is_read' => $record['is_read'] ?? false,
            'created_at' => $created_at,
            'payload' => $record['payload'] ?? new \stdClass(),
        ];

        return $array;
    }

    /**
     * @param array $records
     * @return array
     */
    public function formatCollection($records): array
    {
        $array = [];

        foreach ($records as $record) {
            $array[] = $this->format($record);
        }

        return $array;
    }
}