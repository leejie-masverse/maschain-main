<?php


namespace App\Http\Exports;


use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StatementsExport implements FromView,ShouldAutoSize
{
    use Exportable;

    public $bookings;

    public function __construct(Collection $bookings)
    {
        $this->bookings = $bookings;
    }

    public function view(): View
    {
        $bookings = $this->bookings;

        return view('manage.store.statement.export', [
            'bookings' => $bookings,
        ]);
    }
}

