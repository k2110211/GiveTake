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
        $admin = User::factory()->create([
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
            'Clothing & Fashion' => 'https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?auto=format&fit=crop&w=400&q=80',
            'Electronics & Gadgets' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?auto=format&fit=crop&w=400&q=80',
            'Books & Stationery' => 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&w=400&q=80',
            'Home & Kitchen' => 'https://images.unsplash.com/photo-1556911220-e15b29be8c8f?auto=format&fit=crop&w=400&q=80',
            'Toys & Baby Care' => 'https://images.unsplash.com/photo-1539627831859-a911cf04d3cd?auto=format&fit=crop&w=400&q=80',
            'Sports & Outdoors' => 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?auto=format&fit=crop&w=400&q=80',
            'Other Items' => 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=400&q=80'
        ];
  
        $categories = [];
        foreach ($categoriesData as $cat => $image) {
            $categories[$cat] = Category::create([
                'name' => $cat,
                'slug' => Str::slug($cat),
                'image' => $image
            ]);
        }

        // ─── Default News ────────────────────────────────────────────────
        \App\Models\News::create([
            'title' => 'Chương trình Quyên góp sách giáo khoa cũ niên khoá 2026',
            'slug' => Str::slug('Chương trình Quyên góp sách giáo khoa cũ niên khoá 2026'),
            'summary' => 'Chung tay giúp đỡ các em học sinh có hoàn cảnh khó khăn bước vào năm học mới bằng cách chia sẻ những bộ sách cũ của bạn.',
            'content' => 'Hàng năm, hàng triệu cuốn sách giáo khoa cũ bị lãng phí sau mỗi mùa tựu trường. Nhằm lan tỏa tinh thần tiết kiệm và tương thân tương ái, cộng đồng GiveTake chính thức khởi động chiến dịch "Sách cũ nâng bước tương lai". Quý thành viên có sách giáo khoa lớp 1 đến lớp 12 không sử dụng nữa có thể đăng tin chia sẻ lên hệ thống hoặc gửi về các điểm tiếp nhận chính thức của chúng tôi. Mỗi cuốn sách được trao đi là một niềm hy vọng được thắp sáng!',
            'image' => 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=800&q=80',
            'user_id' => $admin->id
        ]);

        \App\Models\News::create([
            'title' => 'Cập nhật tính năng Đăng tin chia đôi Ảnh đại diện & Mô tả',
            'slug' => Str::slug('Cập nhật tính năng Đăng tin chia đôi Ảnh đại diện và Mô tả'),
            'summary' => 'Hệ thống vừa cập nhật giao diện giúp người dùng phân loại ảnh bìa hiển thị và album mô tả phụ tiện lợi.',
            'content' => 'Lắng nghe phản hồi từ các thành viên tích cực, ban quản trị GiveTake đã chính thức ra mắt tính năng đăng tin với cơ chế phân tách ảnh. Bây giờ, khi đăng tin mới, bạn có thể chọn riêng một hình ảnh đẹp nhất làm Ảnh đại diện chính (để thu hút sự chú ý ngoài trang chủ) và tải thêm tối đa 3 ảnh phụ để mô tả chi tiết tình trạng của đồ dùng. Tính năng này hứa hẹn sẽ mang đến trải nghiệm duyệt tin mượt mà và trực quan hơn.',
            'image' => 'https://images.unsplash.com/photo-1542744094-3a31f103e35f?auto=format&fit=crop&w=800&q=80',
            'user_id' => $admin->id
        ]);

        \App\Models\News::create([
            'title' => 'Cảnh báo lừa đảo phí vận chuyển và quy tắc an toàn',
            'slug' => Str::slug('Cảnh báo lừa đảo phí vận chuyển và quy tắc an toàn'),
            'summary' => 'Hãy bảo vệ bản thân và cộng đồng bằng cách tuân thủ nguyên tắc giao dịch trực tiếp hoặc sử dụng dịch vụ ship COD uy tín.',
            'content' => 'Cộng đồng GiveTake hoạt động trên tinh thần sẻ chia phi lợi nhuận. Tuy nhiên, thời gian gần đây đã xuất hiện một số đối tượng lợi dụng lòng tốt để yêu cầu chuyển khoản trước tiền ship với giá cao rồi cắt đứt liên lạc. Chúng tôi khuyến cáo thành viên ưu tiên gặp mặt trực tiếp tại khu vực công cộng để giao nhận đồ hoặc thỏa thuận ship hàng qua các ứng dụng giao hàng uy tín có hỗ trợ thanh toán khi nhận hàng (COD). Tuyệt đối không chuyển khoản trước cho người lạ.',
            'image' => 'https://images.unsplash.com/photo-1508847154043-be12a62861c1?auto=format&fit=crop&w=800&q=80',
            'user_id' => $admin->id
        ]);
  
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
            'thumbnail' => 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?auto=format&fit=crop&w=600&q=80',
            'images' => [
                'https://images.unsplash.com/photo-1551028719-00167b16eac5?auto=format&fit=crop&w=600&q=80',
                'https://images.unsplash.com/photo-1544441893-675973e31985?auto=format&fit=crop&w=600&q=80'
            ],
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
            'thumbnail' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=crop&w=600&q=80',
            'images' => [],
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
            'thumbnail' => 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&w=600&q=80',
            'images' => [],
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
            'thumbnail' => 'https://images.unsplash.com/photo-1556911220-e15b29be8c8f?auto=format&fit=crop&w=600&q=80',
            'images' => [],
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
            'thumbnail' => 'https://images.unsplash.com/photo-1539627831859-a911cf04d3cd?auto=format&fit=crop&w=600&q=80',
            'images' => [],
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
            'thumbnail' => 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?auto=format&fit=crop&w=600&q=80',
            'images' => [],
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
            'thumbnail' => 'https://images.unsplash.com/photo-1510915361894-db8b60106cb1?auto=format&fit=crop&w=600&q=80',
            'images' => [],
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
            'thumbnail' => 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?auto=format&fit=crop&w=600&q=80',
            'images' => [],
            'type' => 'give',
            'exchange_wish' => null,
            'status' => 'available',
            'city_id' => $citiesMap['Hà Nội'],
            'district_id' => $districtsMap['Hà Nội_Hoàn Kiếm']
        ]);
    }
}
