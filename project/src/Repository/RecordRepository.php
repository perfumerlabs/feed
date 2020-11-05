<?php

namespace Feed\Repository;

class RecordRepository
{
    public function format($record): ?array
    {
        $array = [
            'id' => $record['id'] ?? null,
            'recipient' => $record['recipient'] ?? null,
            'sender' => $record['sender'] ?? null,
            'thread' => $record['thread'] ?? null,
            'title' => $record['title'] ?? null,
            'text' => $record['text'] ?? null,
            'image' => $record['image'] ?? null,
            'is_read' => $record['is_read'] ?? false,
            'created_at' => $record['created_at'] ?? null,
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