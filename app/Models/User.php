<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\UserRole;
use App\Models\Builders\SubscriptionBuilder;
use App\Observers\UserObserver;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\PersonalAccessToken;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\LaravelPasskeys\Models\Concerns\HasPasskeys;
use Spatie\LaravelPasskeys\Models\Concerns\InteractsWithPasskeys;
use Spatie\LaravelPasskeys\Models\Passkey;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property UserRole $role
 * @property string|null $security_code
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property bool $is_active
 * @property-read Subscription|null $activeSubscription
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 * @property-read Collection<int, Activity> $activitiesAsSubject
 * @property-read int|null $activities_as_subject_count
 * @property-read DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection<int, Passkey> $passkeys
 * @property-read int|null $passkeys_count
 * @property-read Collection<int, Subscription> $subscriptions
 * @property-read int|null $subscriptions_count
 * @property-read Collection<int, Backup> $backups
 * @property-read int|null $backups_count
 * @property-read Collection<int, PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 *
 * @method static UserFactory factory($count = null, $state = [])
 * @method static Builder<static>|User newModelQuery()
 * @method static Builder<static>|User newQuery()
 * @method static Builder<static>|User query()
 * @method static Builder<static>|User whereCreatedAt($value)
 * @method static Builder<static>|User whereEmail($value)
 * @method static Builder<static>|User whereEmailVerifiedAt($value)
 * @method static Builder<static>|User whereId($value)
 * @method static Builder<static>|User whereIsActive($value)
 * @method static Builder<static>|User whereName($value)
 * @method static Builder<static>|User wherePassword($value)
 * @method static Builder<static>|User whereRememberToken($value)
 * @method static Builder<static>|User whereRole($value)
 * @method static Builder<static>|User whereUpdatedAt($value)
 *
 * @mixin Model
 */
#[ObservedBy(UserObserver::class)]
#[Fillable(
    'name',
    'email',
    'email_verified_at',
    'password',
    'role',
    'security_code',
    'is_active',
    'remember_token',
)]
#[Hidden([
    'password',
    'remember_token',
])]
final class User extends Authenticatable implements HasPasskeys, MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, InteractsWithPasskeys, LogsActivity, Notifiable;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name',
                'email',
                'role',
                'is_active',
                'email_verified_at',
            ])
            ->logOnlyDirty()
            ->dontLogEmptyChanges()
            ->useLogName('user');
    }

    /**
     * @return MorphMany<Activity, $this>
     */
    public function activities(): MorphMany
    {
        return $this->activitiesAsSubject();
    }

    /**
     * @return HasMany<Subscription, $this>
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * @return HasMany<Backup, $this>
     */
    public function backups(): HasMany
    {
        return $this->hasMany(Backup::class);
    }

    /**
     * The current access-granting subscription, if any (active and not expired,
     * picking the one that runs longest).
     *
     * @return HasOne<Subscription, $this>
     */
    public function activeSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class)
            ->ofMany(
                ['ends_at' => 'max'],
                /** @param SubscriptionBuilder<Subscription> $query */
                static function (SubscriptionBuilder $query): void {
                    $query->whereActive();
                },
            );
    }

    /**
     * @return array<string, string>
     */
    public function casts(): array
    {
        return [
            'id' => 'integer',
            'name' => 'string',
            'email' => 'string',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'security_code' => 'encrypted',
            'is_active' => 'boolean',
            'remember_token' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
