<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\CoinSpin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherCoinController extends Controller
{
    public function coin()
    {
        return view('teacher.coin');
    }

    public function daily()
    {
        $user = auth()->user();

        $lastSpin = CoinSpin::where('user_id', $user->id)
            ->latest('created_at')
            ->first();

        if ($lastSpin && $lastSpin->created_at->gt(now()->subDay())) {
            return back();
        }

        return view('teacher.coin.daily');
    }

    public function dailyStore(Request $request)
    {
        $request->validate([
            'receive_coin' => ['required', 'integer', 'min:0'],
        ]);

        $user = auth()->user();

        $lastSpin = CoinSpin::where('user_id', $user->id)
            ->latest('created_at')
            ->first();

        if ($lastSpin && $lastSpin->created_at->gt(now()->subDay())) {
            return back();
        }

        DB::transaction(function () use ($user, $request) {
            $user->increment('coin', $request->receive_coin);

            $coinSpin = new CoinSpin();
            $coinSpin->user_id = $user->id;
            $coinSpin->receive_coin = $request->receive_coin;
            $coinSpin->save();
        });

        return redirect()
            ->route('teacher.coin.coin')
            ->with('success', 'گردونه با موفقیت ثبت شد.');
    }
}