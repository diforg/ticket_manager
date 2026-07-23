<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

#[Fillable(['message_id', 'file_path', 'original_name', 'mime_type', 'size'])]
class Attachment extends Model
{
    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }

    protected function publicUrl(): Attribute
    {
        return Attribute::get(function (): string {
            $disk = config('filesystems.default');
            /** @var FilesystemAdapter $storage */
            $storage = Storage::disk($disk);

            if ($disk === 's3') {
                return $storage->temporaryUrl($this->file_path, now()->addMinutes(10));
            }

            return route('attachments.show', $this);
        });
    }

    protected function displayType(): Attribute
    {
        return Attribute::get(function (): string {
            if (str_starts_with((string) $this->mime_type, 'image/')) {
                return 'image';
            }

            if ($this->mime_type === 'application/pdf') {
                return 'pdf';
            }

            return 'file';
        });
    }
}