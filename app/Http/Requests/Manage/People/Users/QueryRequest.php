<?php

namespace App\Http\Requests\Manage\People\Users;

use Carbon\Carbon;
use Diver\Database\Eloquent\Builder;
use Diver\Http\Requests\QueryRequest as Request;
use Src\People\Membership;
use Src\People\User;

class QueryRequest extends Request
{
    /**
     * Eloquent model class that to be filtered
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Filterable
     *
     * @var array
     */
    protected $filterable = [
        'search',
        'status',
        'referred_by',
        'dateFrom',
        'dateBefore',
        'specificDate',
    ];

    /**
     * Search param
     *
     * @param $value
     *
     * @return array
     */
    protected function paramSearch($value)
    {
        return [
            'title' => 'Search',
            'formattedValue' => $value,
        ];
    }

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
                ->orWhere("formatted_phone_number", 'LIKE', "%{$value}%")
                ->orWhere("affiliate_id", 'LIKE', "%{$value}%");
        });
    }

    /**
     * Status param
     *
     * @param $value
     *
     * @return array
     */
    protected function paramStatus($value)
    {
        return [
            'title' => 'Status',
            'formattedValue' => $value,
        ];
    }

    /**
     * Filter status
     *
     * @param \Diver\Database\Eloquent\Builder $query
     * @param $value
     */
    protected function filterStatus(Builder $query, $value)
    {
        $query->whereIn('status', $value);
    }

    /**
     * Level param
     *
     * @param $value
     *
     * @return array
     */
    protected function paramReferredBy($value)
    {
        return [
            'title' => 'Referred By',
            'formattedValue' => $value,
        ];
    }

    /**
     * Filter level
     *
     * @param \Diver\Database\Eloquent\Builder $query
     * @param $value
     */
    protected function filterReferredBy(Builder $query, $value)
    {
        $query->where('referred_by', $value);
    }

    /**
     * From date param
     *
     * @param $value
     *
     * @return array
     */
    protected function paramDateFrom($value)
    {
        return [
            'title' => 'From',
            'formattedValue' => Carbon::parse($value)->toFormattedDateString(),
        ];
    }

    /**
     * Filter search
     *
     * @param \Diver\Database\Eloquent\Builder $query
     * @param $value
     */
    protected function filterDateFrom(Builder $query, $value)
    {
        $value = $value . " 00:00:00";
        $value = Carbon::parse($value);

        $query->where(function (Builder $query) use ($value){
            $query->orWhere("created_at", '>=', $value);
        });
    }

    /**
     * From date param
     *
     * @param $value
     *
     * @return array
     */
    protected function paramDateBefore($value)
    {
        return [
            'title' => 'Before',
            'formattedValue' => Carbon::parse($value)->toFormattedDateString(),
        ];
    }

    /**
     * Filter search
     *
     * @param \Diver\Database\Eloquent\Builder $query
     * @param $value
     */
    protected function filterDateBefore(Builder $query, $value)
    {
        $value = $value . " 23:59:59";
        $value = Carbon::parse($value);

        $query->where(function (Builder $query) use ($value){
            $query->orWhere("created_at", '<=', $value);
        });
    }

    /**
     * Status param
     *
     * @param $value
     *
     * @return array
     */
    protected function paramSpecificDate($value)
    {
        if($value==='one_week') {
            $displayValue= 'One Week';
        }else if($value==='today'){
            $displayValue ='Today';
        }else if($value==='this_month'){
            $displayValue ='This Month';
        }else{
            $displayValue =$value;
        }
        return [
            'title' => 'Date',
            'formattedValue' => $displayValue,
        ];
    }

    protected function filterSpecificDate(Builder $query, $value)
    {
        if($value==='today'){
            $query->where(function (Builder $query) {
                $query->whereDate("created_at", Carbon::today('Asia/Kuala_Lumpur'));
            });
        }else if($value==='one_week'){
            $query->where(function (Builder $query){
                $query->where("created_at",'>=', Carbon::now('Asia/Kuala_Lumpur')->subDays(7))
                    ->where("created_at",'<=', Carbon::now('Asia/Kuala_Lumpur'));
            });
        }else if($value==='this_month'){
            $query->where(function (Builder $query){
                $query->whereRaw('MONTH(created_at) = ?',Carbon::now('Asia/Kuala_Lumpur')->month);
            });
        }
    }
}
