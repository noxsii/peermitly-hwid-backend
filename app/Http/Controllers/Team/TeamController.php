<?php

declare(strict_types=1);

namespace App\Http\Controllers\Team;

use App\Actions\Team\UpdateTeamAction;
use App\Data\Team\UpdateTeamData;
use App\Http\Requests\Team\UpdateTeamRequest;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final readonly class TeamController
{
    public function __construct(
        private UpdateTeamAction $updateTeam,
    ) {}

    public function index(Request $request): Response
    {
        /** @var User $user */
        $user = $request->user();

        return Inertia::render('team/Index', [
            'teams' => Inertia::defer(static fn (): array => $user->ownedTeams()
                ->orderBy('name')
                ->get(['id', 'uuid', 'name'])
                ->map(static fn (Team $team): array => [
                    'id' => $team->id,
                    'uuid' => $team->uuid,
                    'name' => $team->name,
                ])
                ->all()),
        ]);
    }

    public function update(UpdateTeamRequest $request, Team $team): RedirectResponse
    {
        $this->updateTeam->handle(
            $team,
            new UpdateTeamData(
                name: $request->string('name')->toString(),
            ),
        );

        return back()->with('success', 'Team updated.');
    }
}
