<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\ManageAuthedController;
use Carbon\Carbon;
use Src\Store\Booking\Booking;
use Src\Store\Booking\BookingAddOn;
use Src\Store\Booking\BookingPackage;
use Src\Store\Venue\VenueAddOn;
use Src\Store\Venue\VenuePackage;
use function array_push;
use function compact;
use function with;

class DashboardController extends ManageAuthedController
{
    public function dashboard()
    {
        return view('manage.dashboard');
    }
}
