<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ViraCoin;
use Illuminate\Http\Request;

class AdminCoinController extends Controller
{
    public function index()
    {
        $coins = ViraCoin::orderBy('id', 'desc')->get();
        return view('admin.coin', compact('coins'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'coin_value' => 'required|numeric|min:0',
        ]);

        $coin = new ViraCoin();
        $coin->title = $request->title;
        $coin->coin_value = $request->coin_value;
        $coin->is_active = true;
        $coin->save();

        return redirect()->route('admin.coin')->with('success', 'فعالیت با موفقیت اضافه شد!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'coin_value' => 'required|numeric|min:0',
        ]);

        $coin = ViraCoin::findOrFail($id);
        $coin->coin_value = $request->coin_value;
        $coin->save();

        return redirect()->route('admin.coin')->with('success', 'مقدار ویراکوین با موفقیت به‌روزرسانی شد!');
    }

    public function toggleActive($id)
    {
        $coin = ViraCoin::findOrFail($id);
        $coin->is_active = !$coin->is_active;
        $coin->save();

        $status = $coin->is_active ? 'فعال' : 'غیرفعال';
        return redirect()->route('admin.coin')->with('success', "وضعیت فعالیت به {$status} تغییر یافت!");
    }
}