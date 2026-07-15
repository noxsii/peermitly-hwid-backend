<?php

declare(strict_types=1);

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\DB;

function createPasskeyRowForUser(int $userId): void
{
    DB::table('passkeys')->insert([
        'authenticatable_id' => $userId,
        'name' => 'Macbook',
        'credential_id' => 'cred-'.$userId,
        'data' => '{}',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

test('guests cannot delete an account', function (): void {
    $this->delete('/settings/account', [
        'current_password' => 'password',
    ])->assertRedirect('/login');
});

test('a wrong password is rejected and the account survives', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->from('/settings')
        ->delete('/settings/account', [
            'current_password' => 'wrong-password',
        ])
        ->assertRedirect('/settings')
        ->assertSessionHasErrors('current_password');

    expect(User::query()->whereKey($user->id)->exists())->toBeTrue();
});

test('a user can delete their account and all owned data', function (): void {
    $user = User::factory()->create();

    Subscription::factory()->for($user)->create();
    $user->createToken('api');
    createPasskeyRowForUser($user->id);

    $this->actingAs($user)
        ->delete('/settings/account', [
            'current_password' => 'password',
        ])
        ->assertRedirect('/login');

    expect(User::query()->whereKey($user->id)->exists())->toBeFalse();
    expect(Subscription::query()->where('user_id', $user->id)->exists())->toBeFalse();
    expect(DB::table('personal_access_tokens')->where('tokenable_id', $user->id)->exists())->toBeFalse();
    expect(DB::table('passkeys')->where('authenticatable_id', $user->id)->exists())->toBeFalse();
    $this->assertGuest();
});
