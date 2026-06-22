<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Backups\StoreBackupAction;
use App\Http\Requests\Api\StoreBackupRequest;
use App\Http\Resources\Api\BackupResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class BackupController
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $user = $request->user();
        abort_unless($user instanceof User, 401);

        return BackupResource::collection(
            $user->backups()->latest('client_created_at')->latest()->get(),
        );
    }

    public function store(StoreBackupRequest $request, StoreBackupAction $store): JsonResponse
    {
        $user = $request->user();
        abort_unless($user instanceof User, 401);

        $backup = $store->handle($user, $request->payload());

        return (new BackupResource($backup))
            ->response()
            ->setStatusCode($backup->wasRecentlyCreated ? 201 : 200);
    }
}
