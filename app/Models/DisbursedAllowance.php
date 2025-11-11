<?php

namespace App\Models;

use App\Enums\AllowanceGroups;
use App\Http\Utils\Traits\onBootTrait;
use App\Models\Scopes\AuthUserCompanyScope;
use Illuminate\Database\Eloquent\Model;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class DisbursedAllowance extends Model
{

    use onBootTrait;
    // create a enum that i can user for type ['individual', 'group', '']

    public function __construct()
    {

    }

    protected $fillable = [
        'type',
        'amount',
        'company_id',
        'employee_id',
        'status',
        'disbursable_id',
        'disbursable_type',
        'allowance_id',
    ];

    public function disbursable()
    {
        return $this->morphTo();
    }

    public function allowance()
    {
        return $this->belongsTo(Allowance::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public static function getIndividialDisbursements()
    {
        return self::where('type', AllowanceGroups::INDIVIDUAL)
            ->paginateByMinute(10);
    }

    public static function getIndividualMinuteGrouped($count = 10)
    {
        return self::where('type', AllowanceGroups::INDIVIDUAL)
            ->paginateByMinute($count);
    }

    public static function getGroupDisbursements()
    {
        return self::where('type', AllowanceGroups::GROUP)->get();
    }

    public static function getCategorizedDisbursements()
    {
        return self::where('type', AllowanceGroups::CATEGORY)->get();
    }


    public function getDisbursementDay()
    {
        return $this->create_at;
    }


    /**
     * Paginate records grouped by minute.
     */
    public function scopePaginateByMinute(Builder $query, int $perPage = 15): LengthAwarePaginator
    {
        $page = max(1, (int) Request::query('page', 1));
        $offset = ($page - 1) * $perPage;

        // 1. Get total distinct minutes (fast, indexed)
        $total = (clone $query)
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d %H:%i")')
            ->distinct()
            ->count();

        // 2. Get paginated minute keys (single query)
        $minuteKeys = (clone $query)
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d %H:%i") as minute_key')
            ->groupByRaw('DATE_FORMAT(created_at, "%Y-%m-%d %H:%i")')
            ->orderByDesc('minute_key')
            ->offset($offset)
            ->limit($perPage)
            ->pluck('minute_key');

        if ($minuteKeys->isEmpty()) {
            return new LengthAwarePaginator([], $total, $perPage, $page, [
                'path' => Request::url(),
                'query' => Request::query(),
            ]);
        }

        // 3. ONE query: get ALL records for ALL paginated minutes
        $records = (clone $query)
            ->whereIn(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H:%i")'), $minuteKeys)
            ->orderBy('created_at')
            ->get()
            ->groupBy(fn($item) => $item->created_at->format('Y-m-d H:i'));

        // 4. Map results
        $results = $minuteKeys->map(function ($minute) use ($records) {
            $group = $records->get($minute, collect());

            return [
                'minute' => $minute,
                'total' => $group->count(),
                'data' => $group->values(), // full models
            ];
        });

        return new LengthAwarePaginator(
            $results,
            $total,
            $perPage,
            $page,
            ['path' => Request::url(), 'query' => Request::query()]
        );
    }

}
