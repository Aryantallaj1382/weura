<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Manhwa;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;

class DashboardController extends Controller
{
    public function index()
    {
        // تعداد کاربران
        $userCount = User::count();

        // تعداد مانهوآها
        $manhwaCount = Manhwa::count();

        // مجموع موجودی کیف پول
        $totalBalance = Wallet::sum('balance');

        // تعداد تیکت‌های باز
        $ticket = Ticket::where('status', 'open')->count();

        $dates = collect();
        $userCounts = collect();

        // حلقه ۳۰ روز اخیر
        for ($i = 15; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dates->push(Jalalian::fromCarbon($date)->format('d / m')); // روز / ماه شمسی

            $count = User::whereDate('created_at', $date)->count();
            $userCounts->push((int) $count); // عدد صحیح
        }

        // آماده‌سازی userStats برای ویو
        $userStats = [
            'dates' => $dates,
            'counts' => $userCounts,
        ];

        // ارسال داده‌ها به ویو
        return view('admin.dashboard', compact('userCount', 'manhwaCount', 'totalBalance', 'ticket', 'userStats'));
    }

}
