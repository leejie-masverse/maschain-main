<?php

namespace Src\People;

use Diver\Database\Eloquent\Traits\SoftDeleteModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Src\Auth\Ability;
use Src\Auth\Role;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use Silber\Bouncer\Database\Queries\Roles as RolesQuery;
use Src\Reward\Reward\Reward;
use Src\Reward\Reward\RewardEligibleMembership;
use Src\Reward\Reward\RewardTransaction;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasRolesAndAbilities;
    use Notifiable;
    use SoftDeleteModel;

    CONST TYPE_SYSTEM = 'system';

    CONST STATUS_VERIFYING = 'verifying';
    CONST STATUS_ACTIVE = 'active';
    CONST STATUS_BANNED = 'banned';

    CONST DEFAULT_PHONE_CODE = "+60";

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'profile',
        'roles',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Eloquent Model method
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Morph one profile
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function profile()
    {
        return $this->morphOne(UserProfile::class, 'entity');
    }

    public function linkProfile(JoinClause $query, $related)
    {
        $query->where("{$related['modelAlias']}.field", 'profile');
    }

    /**
     * Morph one portrait
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function portrait()
    {
        return $this->morphOne(UserPortrait::class, 'entity')->withDefault([
            'path' => null,
        ]);
    }

    /**
     * Get portrait directory
     *
     * @return string
     */
    public function getPortraitDirectoryAttribute()
    {
        return 'users/' . $this->id . '/portrait/';
    }

    /**
     * Get role attribute
     *
     * @return \Src\Auth\Role
     */
    public function getRoleAttribute()
    {
        return $this->roles->first();
    }

    /**
     * Set password attribute
     *
     * @param string $password
     */
    public function setPasswordAttribute($password)
    {
        if (empty($password)) {
            return;
        }

        if (Hash::needsRehash($password)) {
            $password = Hash::make($password);
        }

        $this->attributes['password'] = $password;
    }

    /**
     * Check if user is active
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Check if user is banned
     *
     * @return bool
     */
    public function isBanned()
    {
        return $this->status === self::STATUS_BANNED;
    }

    public function isVerifying()
    {
        return $this->status === self::STATUS_VERIFYING;
    }

    /**
     * Check if user is authed
     *
     * @return bool
     */
    public function isAuthed()
    {
        return $this->id === Auth::user()->id;
    }

    /**
     * Check if user is admin
     *
     * @return bool
     */
    public function isAdmin()
    {
        if ($this->isA(Role::SYSTEM_USER)) {
            return false;
        }

        return true;
    }

    /**
     * Active user scope
     *
     * @param Builder $query
     */
    public function scopeActive(Builder $query)
    {
        $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Banned user scope
     *
     * @param Builder $query
     */
    public function scopeBanned(Builder $query)
    {
        $query->where('status', self::STATUS_BANNED);
    }

    /**
     * Verifyinh user scope
     *
     * @param Builder $query
     */
    public function scopeVerifying(Builder $query)
    {
        $query->where('status', self::STATUS_VERIFYING);
    }

    /**
     * Constrain the given query by the provided role.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  array $roles
     *
     * @return void
     */
    public function scopeWhereIs(Builder $query, $roles)
    {
        $constraint = new RolesQuery;

        $params = (array)$roles;
        array_unshift($params, $query);

        call_user_func_array([$constraint, 'constrainWhereIs'], $params);
    }

    /**
     * Constrain the given query by all provided roles.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  array $roles
     *
     * @return void
     */
    public function scopeWhereIsAll(Builder $query, $roles)
    {
        $constraint = new RolesQuery;

        $params = $roles;
        array_unshift($params, $query);

        call_user_func_array([$constraint, 'constrainWhereIsAll'], $params);
    }

    /**
     * Constrain the given query by the provided role.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  array $roles
     *
     * @return void
     */
    public function scopeWhereIsNot(Builder $query, $roles)
    {
        $constraint = new RolesQuery;

        $params = (array)$roles;
        array_unshift($params, $query);

        call_user_func_array([$constraint, 'constrainWhereIsNot'], $params);
    }

    /**
     * Owned by scope
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Src\People\User|null $user
     */
    public function scopeOwnedBy(Builder $query, User $user = null)
    {
        $user = $user ?: auth()->user();
        if (!$user) {
            return;
        }

        $query->whereIs($user->getAccessibleRoles());
    }

    /**
     * Ordered scope
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeOrdered(Builder $query)
    {
        $query->orderByLinked('profile.full_name');
    }

    /**
     * Get accessible roles
     *
     * @return array
     */
    public function getAccessibleRoles()
    {
        $accessibleRoleMaps = [
            Role::SYSTEM_ROOT => Ability::MANAGE_ROOTS,
            Role::SYSTEM_ADMINISTRATOR => Ability::MANAGE_ADMINISTRATORS,
            Role::SYSTEM_USER => Ability::MANAGE_USERS,
        ];

        $roles = [];
        foreach ($accessibleRoleMaps as $role => $ability) {
            if ($this->can($ability)) {
                $roles[] = $role;
            }
        }

        return $roles;
    }

    /**
     * Generate unique affiliate id
     * @group
     * @return string
     */
    public static function createAffiliateId() {
        $affiliateId = rand(100000000000, 999999999999);
        $affiliateId = str_pad($affiliateId,12,'0',STR_PAD_LEFT);

        if (self::where('affiliate_id', $affiliateId)->exists()) {
            return self::createAffiliateId();
        }

        return $affiliateId;
    }

    public static function getFormattedPhoneNumber($phoneCode, $phoneNumber)
    {
        $phone = (string) $phoneCode . (string) $phoneNumber;
        return str_replace('+', '', $phone);
    }

    /**
     * Extract form phone number to suit our db structure
     *
     * @group
     * @param $prefix
     * @param $phoneNumber
     */
    public static function extractPhoneInfo($prefix, $phoneNumber)
    {
        $data = [];

        $number = (string) $prefix . (string) $phoneNumber;
        $data['formatted_phone_number'] = substr($number, 1);
        $data['phone_number'] = substr($number, 3);
        $data['phone_code'] = User::DEFAULT_PHONE_CODE;

        return $data;
    }

    /**
     * Extract form phone number to suit our db structure
     *
     * @group
     * @param $prefix
     * @param $phoneNumber
     */
    public static function getPhonePrefixAndNumber($phoneNumber)
    {
        $data['phone_prefix'] = substr($phoneNumber, 0,4);
        $data['phone_number'] = substr($phoneNumber, 4);
        return $data;
    }

    public function getUserReferred($isGetCount = false)
    {
        $user = User::where('referred_by', $this->id);

        if ($isGetCount) {
            return $user->count();
        }

        $user = $user->get();

        return $user;
    }

}
