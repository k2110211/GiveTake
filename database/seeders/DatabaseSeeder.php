<?php
 
namespace Database\Seeders;
 
use App\Models\User;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
 
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;
 
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ─── Seed Cities & Districts ─────────────────────────────────────
        $locationsData = [
            'Hồ Chí Minh' => ['Quận 1', 'Quận 3', 'Quận 10', 'Bình Thạnh', 'Thủ Đức'],
            'Hà Nội' => ['Cầu Giấy', 'Đống Đa', 'Ba Đình', 'Hoàn Kiếm', 'Hai Bà Trưng'],
            'Đà Nẵng' => ['Hải Châu', 'Thanh Khê', 'Sơn Trà', 'Liên Chiểu', 'Ngũ Hành Sơn']
        ];

        $citiesMap = [];
        $districtsMap = [];

        foreach ($locationsData as $cityName => $districts) {
            $cityObj = \App\Models\City::create(['name' => $cityName]);
            $citiesMap[$cityName] = $cityObj->id;
            
            foreach ($districts as $districtName) {
                $districtObj = \App\Models\District::create([
                    'city_id' => $cityObj->id,
                    'name' => $districtName
                ]);
                $districtsMap[$cityName . '_' . $districtName] = $districtObj->id;
            }
        }

        // ─── Admin Account ───────────────────────────────────────────────
        User::factory()->create([
            'name'         => 'GiveTake Admin',
            'email'        => 'admin@givetake.vn',
            'password'     => bcrypt('password'),
            'phone'        => '0900000000',
            'city'         => 'Hồ Chí Minh',
            'district'     => 'Quận 1',
            'karma_points' => 999,
            'trust_score'  => 5.0,
            'is_admin'     => true,
            'is_banned'    => false,
        ]);
  
        // ─── Default Categories ──────────────────────────────────────────
        $categoriesData = [
            'Clothing & Fashion',
            'Electronics & Gadgets',
            'Books & Stationery',
            'Home & Kitchen',
            'Toys & Baby Care',
            'Sports & Outdoors',
            'Other Items'
        ];
  
        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[$cat] = Category::create([
                'name' => $cat,
                'slug' => Str::slug($cat)
            ]);
        }
  
        // Seed some test users
        $userA = User::factory()->create([
            'name' => 'Nguyễn Văn A',
            'email' => 'userA@example.com',
            'password' => bcrypt('password'),
            'phone' => '0987654321',
            'city' => 'Hồ Chí Minh',
            'district' => 'Quận 1',
            'karma_points' => 100,
            'trust_score' => 4.8,
        ]);
  
        $userB = User::factory()->create([
            'name' => 'Trần Thị B',
            'email' => 'userB@example.com',
            'password' => bcrypt('password'),
            'phone' => '0912345678',
            'city' => 'Hà Nội',
            'district' => 'Cầu Giấy',
            'karma_points' => 50,
            'trust_score' => 4.5,
        ]);
  
        $userC = User::factory()->create([
            'name' => 'Lê Văn C',
            'email' => 'userC@example.com',
            'password' => bcrypt('password'),
            'phone' => '0905123456',
            'city' => 'Đà Nẵng',
            'district' => 'Hải Châu',
            'karma_points' => 75,
            'trust_score' => 4.6,
        ]);
  
        // Seed mock items
        Item::create([
            'user_id' => $userA->id,
            'category_id' => $categories['Clothing & Fashion']->id,
            'title' => 'Áo khoác gió nam Uniqlo size L',
            'description' => 'Áo khoác gió chống nước nhẹ màu xanh đen của Uniqlo, size L (phù hợp người từ 65-75kg). Áo còn khá mới, khoá kéo mượt mà, không bị rách hay sờn vải.',
            'images' => ['https://images.unsplash.com/photo-1591047139829-d91aecb6caea?auto=format&fit=crop&w=600&q=80'],
            'type' => 'give',
            'exchange_wish' => null,
            'status' => 'available',
            'city_id' => $citiesMap['Hồ Chí Minh'],
            'district_id' => $districtsMap['Hồ Chí Minh_Quận 1']
        ]);
  
        Item::create([
            'user_id' => $userA->id,
            'category_id' => $categories['Electronics & Gadgets']->id,
            'title' => 'Điện thoại Vsmart Joy 3 cũ',
            'description' => 'Máy màu đen 3GB RAM / 32GB ROM, hoạt động bình thường, màn hình xước nhẹ. Thích hợp làm máy phụ hoặc cho học sinh học tập.',
            'images' => ['https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=crop&w=600&q=80'],
            'type' => 'exchange',
            'exchange_wish' => 'Muốn đổi lấy truyện tranh Conan trọn bộ hoặc sách văn học cũ.',
            'status' => 'available',
            'city_id' => $citiesMap['Hồ Chí Minh'],
            'district_id' => $districtsMap['Hồ Chí Minh_Bình Thạnh']
        ]);
  
        Item::create([
            'user_id' => $userB->id,
            'category_id' => $categories['Books & Stationery']->id,
            'title' => 'Sách giáo khoa Toán lớp 10 mới 90%',
            'description' => 'Bộ sách giáo khoa Toán lớp 10 (tập 1 và 2) chương trình mới Cánh Diều. Sách bọc bìa cẩn thận, không viết vẽ bậy bên trong.',
            'images' => ['https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&w=600&q=80'],
            'type' => 'give',
            'exchange_wish' => null,
            'status' => 'available',
            'city_id' => $citiesMap['Hà Nội'],
            'district_id' => $districtsMap['Hà Nội_Cầu Giấy']
        ]);
  
        Item::create([
            'user_id' => $userB->id,
            'category_id' => $categories['Home & Kitchen']->id,
            'title' => 'Bộ nồi chảo chống dính Sunhouse',
            'description' => 'Gồm 1 nồi nhôm và 1 chảo chống dính cỡ trung. Chảo còn lớp chống dính tốt, nồi sạch sẽ không móp méo.',
            'images' => ['https://images.unsplash.com/photo-1556911220-e15b29be8c8f?auto=format&fit=crop&w=600&q=80'],
            'type' => 'exchange',
            'exchange_wish' => 'Đổi lấy bàn ủi (bàn là) quần áo hoạt động tốt.',
            'status' => 'available',
            'city_id' => $citiesMap['Hà Nội'],
            'district_id' => $districtsMap['Hà Nội_Đống Đa']
        ]);
  
        Item::create([
            'user_id' => $userC->id,
            'category_id' => $categories['Toys & Baby Care']->id,
            'title' => 'Gấu bông Teddy size lớn 1m2',
            'description' => 'Gấu bông Teddy màu nâu, cao khoảng 1m2, sạch sẽ đã giặt sấy thơm tho. Muốn tặng lại cho bé nào yêu thích.',
            'images' => ['https://images.unsplash.com/photo-1539627831859-a911cf04d3cd?auto=format&fit=crop&w=600&q=80'],
            'type' => 'give',
            'exchange_wish' => null,
            'status' => 'available',
            'city_id' => $citiesMap['Đà Nẵng'],
            'district_id' => $districtsMap['Đà Nẵng_Hải Châu']
        ]);
  
        Item::create([
            'user_id' => $userC->id,
            'category_id' => $categories['Sports & Outdoors']->id,
            'title' => 'Vợt cầu lông Yonex Carbon cũ',
            'description' => 'Vợt đơn Yonex dòng Carbon nhẹ, căng cước 10kg chơi tốt. Khung vợt trầy xước nhẹ do sử dụng nhưng không nứt gãy.',
            'images' => ['https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?auto=format&fit=crop&w=600&q=80'],
            'type' => 'exchange',
            'exchange_wish' => 'Đổi lấy balo đi học cũ hoặc vợt bóng bàn.',
            'status' => 'available',
            'city_id' => $citiesMap['Đà Nẵng'],
            'district_id' => $districtsMap['Đà Nẵng_Sơn Trà']
        ]);
  
        Item::create([
            'user_id' => $userA->id,
            'category_id' => $categories['Other Items']->id,
            'title' => 'Đàn guitar acoustic gỗ hồng đào',
            'description' => 'Đàn tập chơi cho người mới bắt đầu. Gỗ hồng đào bền bỉ, âm thanh trầm ấm, đã thay dây mới tinh. Tặng kèm bao da.',
            'images' => ['https://images.unsplash.com/photo-1510915361894-db8b60106cb1?auto=format&fit=crop&w=600&q=80'],
            'type' => 'give',
            'exchange_wish' => null,
            'status' => 'available',
            'city_id' => $citiesMap['Hồ Chí Minh'],
            'district_id' => $districtsMap['Hồ Chí Minh_Thủ Đức']
        ]);
  
        Item::create([
            'user_id' => $userB->id,
            'category_id' => $categories['Books & Stationery']->id,
            'title' => 'Bản đồ thế giới khổ lớn treo tường',
            'description' => 'Kích thước 1m2 x 0.8m, bản dịch tiếng Việt rõ nét, thích hợp treo phòng làm việc hoặc phòng trẻ em học địa lý.',
            'images' => ['https://images.unsplash.com/photo-1543002588-bfa74002ed7e?auto=format&fit=crop&w=600&q=80'],
            'type' => 'give',
            'exchange_wish' => null,
            'status' => 'available',
            'city_id' => $citiesMap['Hà Nội'],
            'district_id' => $districtsMap['Hà Nội_Hoàn Kiếm']
        ]);
    }
}
