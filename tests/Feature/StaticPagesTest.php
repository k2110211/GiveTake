<?php

namespace Tests\Feature;

use Tests\TestCase;

class StaticPagesTest extends TestCase
{
    public function test_about_page_can_be_rendered(): void
    {
        $response = $this->get('/about');

        $response->assertStatus(200);
        $response->assertSee('Về Cho & Nhận', false);
        $response->assertSee('Trao yêu thương, Nhận nụ cười');
    }

    public function test_guide_page_can_be_rendered(): void
    {
        $response = $this->get('/guide');

        $response->assertStatus(200);
        $response->assertSee('Hướng dẫn sử dụng nền tảng Cho & Nhận', false);
        $response->assertSee('Người Tặng đồ');
        $response->assertSee('Người Nhận đồ');
        $response->assertSee('Quay số may mắn');
        $response->assertSee('Shipper');
    }

    public function test_privacy_page_can_be_rendered(): void
    {
        $response = $this->get('/privacy');

        $response->assertStatus(200);
        $response->assertSee('Chính sách bảo mật thông tin');
        $response->assertSee('privacy@chonhan.vn');
        $response->assertSee('shipper');
    }

    public function test_terms_page_can_be_rendered(): void
    {
        $response = $this->get('/terms');

        $response->assertStatus(200);
        $response->assertSee('Điều khoản sử dụng dịch vụ');
        $response->assertSee('Danh mục hàng hóa NGHIÊM CẤM');
        $response->assertSee('Thuê đơn vị vận chuyển (Shipper)');
    }
}
