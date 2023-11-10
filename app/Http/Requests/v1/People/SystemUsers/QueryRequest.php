<?php

namespace App\Http\Requests\v1\People\SystemUsers;

use Diver\Database\Eloquent\Builder;
use Diver\Http\Requests\QueryRequest as Request;
use Src\People\SystemUser;
use Src\People\User;

class QueryRequest extends Request
{
    /**
     * Eloquent model class that to be filtered
     *
     * @var string
     */
    protected $model = SystemUser::class;

    /**
     * Filterable
     *
     * @var array
     */
    protected $filterable = [
        'search',
        'role',
        'status',
        'referred_by',
    ];

    /**
     * Filter search
     *
     * @param \Diver\Database\Eloquent\Builder $query
     * @param $value
     */
    protected function filterSearch(Builder $query, $value)
    {
        $profile = $query->link('profile');

        $query->where(function (Builder $query) use ($profile, $value){
            $query->orWhere("email", 'LIKE', "%{$value}%")
                ->orWhere("{$profile}.full_name", 'LIKE', "%{$value}%")
                ->orWhere("formatted_phone_number", 'LIKE', "%{$value}%");
        });
    }

    /**
     * Filter role
     *
     * @param \Diver\Database\Eloquent\Builder $query
     * @param $value
     */
    protected function filterRole(Builder $query, $value)
    {
        $query->whereIs($value);
    }

    /**
     * Filter status
     *
     * @param \Diver\Database\Eloquent\Builder $query
     * @param $value
     */
    protected function filterStatus(Builder $query, $value)
    {
        $query->where('status', $value);
    }


    /**
     * Status param
     *
     * @param $value
     *
     * @return array
     */
    protected function paramReferredBy($value)
    {
        $user = User::find($value);
        $info = $user ? $user->formatted_phone_number : '';

        return [
            'title' => 'Referred By',
            'formattedValue' => $info,
        ];
    }

    /**
     * Filter status
     *
     * @param \Diver\Database\Eloquent\Builder $query
     * @param $value
     */
    protected function filterReferredBy(Builder $query, $value)
    {
        $query->where('referred_by', $value);
    }
}
