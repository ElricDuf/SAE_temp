<?php

namespace App\Models;

use CodeIgniter\Model;

class BeatFileModel extends Model
{
    protected $table      = 'beat_files';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'beat_id',
        'type',
        'path',
        'mime_type',
        'size_bytes',
        'sha256',
        'duration_sec',
        'created_at',
    ];

    protected $useTimestamps = false;

    public function getPathByType(int $beatId, string $type): ?string
    {
        $row = $this->where('beat_id', $beatId)
            ->where('type', $type)
            ->first();

        return $row['path'] ?? null;
    }

    public function getPreviewPath(int $beatId): ?string
    {
        return $this->getPathByType($beatId, 'preview_mp3');
    }

    public function getMasterPath(int $beatId): ?string
    {
        $p = $this->getPathByType($beatId, 'master_wav');
        if ($p !== null) {
            return $p;
        }

        return $this->getPathByType($beatId, 'original_wav');
    }

    /**
     * Upsert basé sur la contrainte unique (beat_id, type)
     * -> si existe, update ; sinon insert.
     */
    public function upsertFile(
        int $beatId,
        string $type,
        string $path,
        string $mime,
        int $sizeBytes,
        ?string $sha256 = null,
        ?int $durationSec = null
    ): void {
        $existing = $this->where('beat_id', $beatId)
            ->where('type', $type)
            ->first();

        $data = [
            'beat_id'       => $beatId,
            'type'          => $type,
            'path'          => $path,
            'mime_type'     => $mime,
            'size_bytes'    => $sizeBytes,
            'sha256'        => $sha256,
            'duration_sec'  => $durationSec,
            'created_at'    => date('Y-m-d H:i:s'),
        ];

        if ($existing) {
            $this->update((int) $existing['id'], $data);
        } else {
            $this->insert($data);
        }
    }
}
