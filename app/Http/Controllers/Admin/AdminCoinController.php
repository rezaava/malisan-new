<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ViraCoin;
use Illuminate\Http\Request;

class AdminCoinController extends Controller
{
    public function coin()
    {
        $coins = ViraCoin::orderBy('id', 'desc')->get();
        return view('admin.coin', compact('coins'));
    }

    // ذخیره کوین جدید با new و save
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'value' => 'required|numeric|min:0',
        ]);

        // استفاده از new و save
        $coin = new ViraCoin();
        $coin->name = $request->name;
        $coin->value = $request->value;
        $coin->save();

        return redirect()->route('admin.coin')->with('success', 'ویرا کوین با موفقیت اضافه شد!');
    }

    // حذف کوین
    public function destroy($id)
    {
        $coin = ViraCoin::findOrFail($id);
        $coin->delete();

        return redirect()->route('admin.coin')->with('success', 'ویرا کوین با موفقیت حذف شد!');
    }
}