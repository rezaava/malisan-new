<?php

namespace App\Imports;

use App\Models\DiscountCode;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class DiscountCodeImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $key => $row) {

            // skip header row
            if ($key == 0) continue;

            $code = $row[0];         // ستون A
            $percent = $row[1];      // ستون B
            $productId = $row[2];    // ستون C

            // 1. ایجاد کد تخفیف با new و save
            $discount = new DiscountCode();
            $discount->code = $code . $productId;
            $discount->type = 'percent';
            $discount->value = $percent;
            $discount->max_usage = 1;
            $discount->used_count = 0;
            $discount->expire_at = now()->addDays(7);
            $discount->is_active = 1;
            $discount->save();

            // 2. ثبت ارتباط با محصول
            if ($productId) {
                $discount->products()->attach($productId);
            }
        }
    }
}