@extends('layouts.admin')

@section('content')
    <div class="space-y-6">

        {{-- کارت‌های KPI --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-slate-900 rounded-xl shadow p-4 flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">کل کاربران</p>
                    <h3 class="text-2xl font-bold mt-1">{{$userCount}}</h3>
                </div>
                <div class="text-indigo-500 text-3xl">👥</div>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-xl shadow p-4 flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">کل مانهوا ها</p>
                    <h3 class="text-2xl font-bold mt-1">{{$manhwaCount}}</h3>
                </div>
                <div class="text-indigo-500 text-3xl">🛒</div>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-xl shadow p-4 flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">کل موجودی کاربران</p>
                    <h3 class="text-2xl font-bold mt-1"> {{number_format($totalBalance)}}</h3>
                </div>
                <div class="text-indigo-500 text-3xl">💰</div>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-xl shadow p-4 flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">تیکت های باز</p>
                    <h3 class="text-2xl font-bold mt-1">{{$ticket}}</h3>
                </div>
                <div class="text-indigo-500 text-3xl">📈</div>
            </div>
        </div>


        {{-- نمودارها --}}
        <h3 class="text-lg font-bold mb-3 text-center"> نمودار افزایش کاربران سایت در 15 روز اخیر</h3>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <canvas id="usersChart" class="w-full h-64"></canvas>

            <script>
                const ctxUsers = document.getElementById('usersChart').getContext('2d');
                const usersChart = new Chart(ctxUsers, {
                    type: 'line',
                    data: {
                        labels: @json($userStats['dates']),
                        datasets: [{
                            label: 'تعداد کاربران',
                            data: @json($userStats['counts']),
                            backgroundColor: 'rgba(99, 102, 241, 0.2)',
                            borderColor: 'rgba(99, 102, 241, 1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.3,
                            pointBackgroundColor: 'rgba(99, 102, 241, 1)'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { ticks: { color: '#fff' } },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    color: '#fff',
                                    precision: 0, // اعداد Y عدد صحیح
                                    stepSize: 1   // هر قدم ۱ واحد
                                }
                            }
                        }
                    }
                });
            </script>


        </div>

        {{-- جدول سفارشات اخیر --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow p-6">
            <h3 class="text-lg font-bold mb-4">سفارشات اخیر</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead>
                    <tr class="bg-gray-100 dark:bg-gray-800">
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-300">نام مشتری</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-300">محصول</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-300">مقدار</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-300">وضعیت</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-300">تاریخ</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <tr>
                        <td class="px-4 py-2">آریان</td>
                        <td class="px-4 py-2">کلاس خصوصی</td>
                        <td class="px-4 py-2">1</td>
                        <td class="px-4 py-2 text-green-500 font-semibold">تکمیل شده</td>
                        <td class="px-4 py-2">1402/05/10</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2">بهناز</td>
                        <td class="px-4 py-2">وبینار</td>
                        <td class="px-4 py-2">3</td>
                        <td class="px-4 py-2 text-yellow-500 font-semibold">در انتظار</td>
                        <td class="px-4 py-2">1402/05/12</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2">محمد</td>
                        <td class="px-4 py-2">کلاس گروهی</td>
                        <td class="px-4 py-2">2</td>
                        <td class="px-4 py-2 text-red-500 font-semibold">کنسل شده</td>
                        <td class="px-4 py-2">1402/05/15</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- فعالیت اخیر --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow p-6">
            <h3 class="text-lg font-bold mb-4">فعالیت اخیر</h3>
            <ul class="space-y-2">
                <li class="flex justify-between">
                    <span>آریان کلاس خصوصی را رزرو کرد</span>
                    <span class="text-gray-500 text-sm">5 دقیقه قبل</span>
                </li>
                <li class="flex justify-between">
                    <span>بهناز وبینار را لغو کرد</span>
                    <span class="text-gray-500 text-sm">10 دقیقه قبل</span>
                </li>
                <li class="flex justify-between">
                    <span>محمد کلاس گروهی را تکمیل کرد</span>
                    <span class="text-gray-500 text-sm">30 دقیقه قبل</span>
                </li>
            </ul>
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // نمودار خطی فروش
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: ['فروردین','اردیبهشت','خرداد','تیر','مرداد','شهریور'],
                datasets: [{
                    label: 'درآمد',
                    data: [1200,1900,3000,2500,3200,4000],
                    borderColor: '#4f46e5',
                    backgroundColor: 'rgba(79, 70, 229,0.2)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: { responsive:true, plugins:{legend:{display:false}}, scales:{y:{beginAtZero:true}} }
        });

        // نمودار دایره‌ای سفارشات
        const orderCtx = document.getElementById('orderChart').getContext('2d');
        new Chart(orderCtx, {
            type: 'doughnut',
            data: {
                labels: ['تکمیل شده','در انتظار','کنسل شده'],
                datasets:[{
                    data:[12,5,3],
                    backgroundColor:['#22c55e','#eab308','#ef4444']
                }]
            },
            options: { responsive:true, plugins:{legend:{position:'bottom'}} }
        });
    </script>
@endsection
