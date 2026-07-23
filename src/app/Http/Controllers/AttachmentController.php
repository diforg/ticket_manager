<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class AttachmentController extends Controller
{
    public function show(Request $request, Attachment $attachment): Response|RedirectResponse
    {
        $this->authorize('view', $attachment);

        $disk = config('filesystems.default');
        /** @var FilesystemAdapter $storage */
        $storage = Storage::disk($disk);

        if ($disk === 's3') {
            return redirect()->away(
                $storage->temporaryUrl($attachment->file_path, now()->addMinutes(10))
            );
        }

        return $storage->response(
            $attachment->file_path,
            $attachment->original_name,
            ['Content-Type' => $attachment->mime_type ?? 'application/octet-stream']
        );
    }
}