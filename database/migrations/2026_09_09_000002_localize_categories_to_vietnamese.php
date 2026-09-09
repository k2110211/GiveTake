<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $map = [
            'Clothing & Fashion'    => 'Thời trang & Quần áo',
            'Electronics & Gadgets' => 'Đồ điện tử & Công nghệ',
            'Books & Stationery'    => 'Sách & Văn phòng phẩm',
            'Home & Kitchen'        => 'Đồ gia dụng & Bếp',
            'Toys & Baby Care'      => 'Đồ chơi & Mẹ bé',
            'Sports & Outdoors'     => 'Thể thao & Dã ngoại',
            'Other Items'           => 'Đồ dùng khác',
        ];

        foreach ($map as $en => $vi) {
            DB::table('categories')->where('name', $en)->update(['name' => $vi]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $map = [
            'Thời trang & Quần áo'   => 'Clothing & Fashion',
            'Đồ điện tử & Công nghệ' => 'Electronics & Gadgets',
            'Sách & Văn phòng phẩm'  => 'Books & Stationery',
            'Đồ gia dụng & Bếp'      => 'Home & Kitchen',
            'Đồ chơi & Mẹ bé'        => 'Toys & Baby Care',
            'Thể thao & Dã ngoại'    => 'Sports & Outdoors',
            'Đồ dùng khác'           => 'Other Items',
        ];

        foreach ($map as $vi => $en) {
            DB::table('categories')->where('name', $vi)->update(['name' => $en]);
        }
    }
};
