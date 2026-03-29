<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\PaymentMethod;
use App\Models\Mylist;
use App\Models\Comment;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;

class HelloTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_page()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertViewIs('register');
    }

    public function test_name_is_required()
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['name' => 'お名前を入力してください']);
    }

    public function test_email_is_required()
    {
        $response = $this->post('/register', [
            'name' => 'test',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
    }

    public function test_password_is_required()
    {
        $response = $this->post('/register', [
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
    }

    public function test_password_min_length()
    {
        $response = $this->post('/register', [
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => '1234567',
            'password_confirmation' => '1234567',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードは8文字以上で入力してください']);
    }

    public function test_password_confirmation_mismatch()
    {
        $response = $this->post('/register', [
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => '12345678',
        ]);

        $response->assertSessionHasErrors(['password_confirmation' => 'パスワードと一致しません']);
    }

    public function test_user_can_register()
    {
        $response = $this->post('/register', [
            'name' => 'test_1',
            'email' => 'test_1@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $this->assertDatabaseHas('users', [
            'email' => 'test_1@example.com',
        ]);
        $response->assertRedirect('/mypage/profile');
    }

    public function test_login_page()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    public function test_login_email_is_required()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
    }

    public function test_login_password_is_required()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
    }

    public function test_login_fails_with_invalid_credentials()
    {
        $response = $this->post('/login', [
            'email' => 'notfound@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors(['email' => 'ログイン情報が登録されていません']);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'id' => '100',
            'email' => 'test_2@example.com',
            'password' => Hash::make('password123'),
        ]);

        $Profile = Profile::create([
            'user_id' => '100',
            'postal_code' => '000-0000',
            'address' => 'テスト',
            'profile_image' => 'テスト',
        ]);

        $response = $this->post('/login', [
            'email' => 'test_2@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();

        $response->assertRedirect('/mypage/profile');
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create([
            'email' => 'test_logout@example.com',
            'password' => Hash::make('password123'),
        ]);

        $this->actingAs($user);

        $response = $this->post('/logout');

        $this->assertGuest();

        $response->assertRedirect('/login');
    }

    public function test_index_page()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('index');
    }

    public function test_sold_label_is_displayed_for_purchased_item()
    {
        $user = User::factory()->create();

        $item = Item::create([
            'item_name' => 'テスト商品',
            'item_detail' => 'テスト',
            'brand_name' => 'テスト',
            'price' => 1000,
            'condition' => '良好',
            'user_id' => $user->id,
            'item_image' =>'テスト'
        ]);

        $paymentMethod = PaymentMethod::create([
        'payment_method' => 'コンビニ支払い',
        ]);

        $purchase = Purchase::create([
            'item_id' => $item->id,
            'payment_methods_id' => $paymentMethod->id,
            'postal_code' => '000-0000',
            'address' => 'テスト',
            'user_id' => $user->id,
        ]);

        $response = $this->get('/');

        $response->assertSee('sold');
    }

    public function test_user_items_are_not_displayed_in_index()
    {
        $user = User::factory()->create();

        $item = Item::create([
            'item_name' => '自分の商品',
            'item_detail' => 'テスト',
            'brand_name' => 'テスト',
            'price' => 1000,
            'condition' => '良好',
            'item_image' => 'test.jpg',
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $response = $this->get('/');

        $response->assertDontSee('自分の商品');
    }


    public function test_only_liked_items_are_displayed()
    {
        $user = User::factory()->create();

        $item1 = Item::create([
            'item_name' => 'いいね商品',
            'item_detail' => 'テスト',
            'brand_name' => 'テスト',
            'price' => 1000,
            'condition' => '良好',
            'item_image' => 'test.jpg',
            'user_id' => $user->id,
        ]);

        $item2 = Item::create([
            'item_name' => 'いいねしてない商品',
            'item_detail' => 'テスト',
            'brand_name' => 'テスト',
            'price' => 1000,
            'condition' => '良好',
            'item_image' => 'test.jpg',
            'user_id' => $user->id,
        ]);

        Mylist::create([
            'user_id' => $user->id,
            'item_id' => $item1->id,
        ]);

        $this->actingAs($user);

        $response = $this->get('/mylist');

        $response->assertSee('いいね商品');
        $response->assertDontSee('いいねしてない商品');
    }

    public function test_sold_label_is_displayed_in_mylist()
    {
        $user = User::factory()->create();

        $item = Item::create([
            'item_name' => 'いいね商品',
            'item_detail' => 'テスト',
            'brand_name' => 'テスト',
            'price' => 1000,
            'condition' => '良好',
            'item_image' => 'test.jpg',
            'user_id' => $user->id,
        ]);

        Mylist::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $paymentMethod = PaymentMethod::create([
            'payment_method' => 'コンビニ支払い',
        ]);

        Purchase::create([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'postal_code' => '000-0000',
            'address' => 'テスト',
            'payment_methods_id' => $paymentMethod->id,
        ]);

        $this->actingAs($user);

        $response = $this->get('/mylist');

        $response->assertSee('sold');
    }

    public function test_guest_cannot_see_mylist()
    {
        $response = $this->get('/mylist');

        $response->assertDontSee('商品');
    }

    public function test_items_can_be_searched_by_name()
    {
        $user = User::factory()->create();
        $otherUser = User::create([
            'id' => '200',
            'name' => '200',
            'email' => 'test_200@example.com',
            'password' => Hash::make('password123'),
        ]);

        Item::create([
            'item_name' => 'りんご',
            'item_detail' => 'テスト',
            'brand_name' => 'テスト',
            'price' => 100,
            'condition' => '良好',
            'item_image' => 'test.jpg',
            'user_id' => $otherUser->id,
        ]);

        Item::create([
            'item_name' => 'バナナ',
            'item_detail' => 'テスト',
            'brand_name' => 'テスト',
            'price' => 100,
            'condition' => '良好',
            'item_image' => 'test.jpg',
            'user_id' => $user->id,
        ]);

        $response = $this->get('/item/search?keyword=りん'
        );

        $response->assertSee('りんご');
        $response->assertDontSee('バナナ');
    }

    public function test_search_keyword_is_kept_in_mylist()
    {
        $user = User::factory()->create();

        $item = Item::create([
            'item_name' => 'りんご',
            'item_detail' => 'テスト',
            'brand_name' => 'テスト',
            'price' => 100,
            'condition' => '良好',
            'item_image' => 'test.jpg',
            'user_id' => $user->id,
        ]);

        Mylist::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->actingAs($user);

        $this->post('/item/search', [
            'keyword' => 'りんご',
        ]);

        $response = $this->get('/mylist');

        $response->assertSee('りんご');
    }

    public function test_item_detail_page_displays_information()
    {
        $user = User::factory()->create();

        Profile::create([
            'user_id' => $user->id,
            'profile_image' => 'test.jpg',
            'postal_code' => '000-0000',
            'address' => 'テスト',
        ]);

        $item = Item::create([
            'item_name' => 'テスト商品',
            'item_detail' => '説明文',
            'brand_name' => 'ブランド',
            'price' => '1000',
            'condition' => '良好',
            'item_image' => 'test.jpg',
            'user_id' => $user->id,
        ]);

        Comment::create([
            'comment' => 'コメントテスト',
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get("/item/{$item->id}");

        $response->assertSee('テスト商品');
        $response->assertSee('説明文');
        $response->assertSee('ブランド');
        $response->assertSee('1,000');
        $response->assertSee('良好');
        $response->assertSee('コメントテスト');
    }

    public function test_item_detail_shows_multiple_categories()
    {
        $user = User::factory()->create();

        $item = Item::create([
            'item_name' => 'ソファ',
            'item_detail' => 'テスト',
            'brand_name' => 'テスト',
            'price' => 100,
            'condition' => '良好',
            'item_image' => 'test.jpg',
            'user_id' => $user->id,
        ]);

        $category1 = Category::create([
            'name' => '家電',
        ]);

        $category2 = Category::create([
            'name' => '家具',
        ]);

        $item->categories()->attach([$category1->id, $category2->id]);

        $response = $this->get("/item/{$item->id}");

        $response->assertSee('家電');
        $response->assertSee('家具');
    }

    public function test_user_can_like_item()
    {
        $user = User::factory()->create();

        $item = Item::create([
            'item_name' => 'テスト商品',
            'item_detail' => 'テスト',
            'brand_name' => 'テスト',
            'price' => 1000,
            'condition' => '良好',
            'item_image' => 'test.jpg',
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $this->post("/item/{$item->id}/like");

        $this->assertDatabaseHas('mylists', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    public function test_user_can_unlike_item()
    {
        $user = User::factory()->create();

        $item = Item::create([
            'item_name' => 'テスト商品',
            'item_detail' => 'テスト',
            'brand_name' => 'テスト',
            'price' => 1000,
            'condition' => '良好',
            'item_image' => 'test.jpg',
            'user_id' => $user->id,
        ]);

        Mylist::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->actingAs($user);

        $this->delete("/item/{$item->id}/like");

        $this->assertDatabaseMissing('mylists', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    public function test_like_count_increases()
    {
        $user = User::factory()->create();

        $item = Item::create([
            'item_name' => 'テスト商品',
            'item_detail' => 'テスト',
            'brand_name' => 'テスト',
            'price' => 1000,
            'condition' => '良好',
            'item_image' => 'test.jpg',
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $this->post("/item/{$item->id}/like");

        $response = $this->get("/item/{$item->id}");

        $response->assertSee('1');
    }

    public function test_like_count_decreases()
    {
        $user = User::factory()->create();

        $item = Item::create([
            'item_name' => 'テスト商品',
            'item_detail' => 'テスト',
            'brand_name' => 'テスト',
            'price' => 1000,
            'condition' => '良好',
            'item_image' => 'test.jpg',
            'user_id' => $user->id,
        ]);

        Mylist::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->actingAs($user);

        $this->delete("/item/{$item->id}/like");

        $response = $this->get("/item/{$item->id}");

        $response->assertSee('0');
    }

    public function test_user_can_post_comment()
    {
        $user = User::factory()->create();

        Profile::create([
            'user_id' => $user->id,
            'profile_image' => 'test.jpg',
            'postal_code' => '000-0000',
            'address' => 'テスト住所',
        ]);

        $item = Item::create([
            'item_name' => 'テスト商品',
            'item_detail' => 'テスト',
            'brand_name' => 'テスト',
            'price' => 1000,
            'condition' => '良好',
            'item_image' => 'test.jpg',
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $this->post("/item/{$item->id}/comments", [
            'comment' => 'テストコメント',
        ]);

        $this->assertDatabaseHas('comments', [
            'comment' => 'テストコメント',
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    public function test_guest_cannot_post_comment()
    {
        $user = User::factory()->create();

        $item = Item::create([
            'item_name' => 'テスト商品',
            'item_detail' => 'テスト',
            'brand_name' => 'テスト',
            'price' => 1000,
            'condition' => '良好',
            'item_image' => 'test.jpg',
            'user_id' => $user->id,
        ]);

        $response = $this->post("/item/{$item->id}/comments", [
            'comment' => 'テストコメント',
        ]);

        $response->assertDontSee('商品へのコメント');
    }


    public function test_comment_is_required()
    {
        $user = User::factory()->create();

        Profile::create([
            'user_id' => $user->id,
            'profile_image' => 'test.jpg',
            'postal_code' => '000-0000',
            'address' => 'テスト住所',
        ]);

        $item = Item::create([
            'item_name' => 'テスト商品',
            'item_detail' => 'テスト',
            'brand_name' => 'テスト',
            'price' => 1000,
            'condition' => '良好',
            'item_image' => 'test.jpg',
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $response = $this->post("/item/{$item->id}/comments", [
            'comment' => '',
        ]);

        $response->assertSessionHasErrors('comment');
    }

public function test_comment_max_length()
    {
        $user = User::factory()->create();

        Profile::create([
            'user_id' => $user->id,
            'profile_image' => 'test.jpg',
            'postal_code' => '000-0000',
            'address' => 'テスト住所',
        ]);

        $item = Item::create([
            'item_name' => 'テスト商品',
            'item_detail' => 'テスト',
            'brand_name' => 'テスト',
            'price' => 1000,
            'condition' => '良好',
            'item_image' => 'test.jpg',
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $response = $this->post("/item/{$item->id}/comments", [
            'comment' => str_repeat('あ', 256),
        ]);

        $response->assertSessionHasErrors('comment');
    }

    public function test_user_can_purchase_item()
    {
        $user = User::factory()->create();

        $Profile = Profile::create([
            'user_id' => $user->id,
            'postal_code' => '000-0000',
            'address' => 'テスト住所',
            'profile_image' => 'テスト',
        ]);

        $item = Item::create([
            'item_name' => 'テスト商品',
            'item_detail' => 'テスト',
            'brand_name' => 'テスト',
            'price' => 1000,
            'condition' => '良好',
            'item_image' => 'test.jpg',
            'user_id' => $user->id,
        ]);

        $paymentMethod = PaymentMethod::create([
            'payment_method' => 'コンビニ支払い',
        ]);

        $this->actingAs($user);

        $this->post("/purchase/{$item->id}", [
            'payment_methods_id' => $paymentMethod->id,
            'postal_code' => '000-0000',
            'address' => 'テスト住所',
        ]);

        $this->assertDatabaseHas('purchases', [
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_purchased_item_is_displayed_as_sold()
    {
        $user = User::factory()->create();

        $item = Item::create([
            'item_name' => 'テスト商品',
            'item_detail' => 'テスト',
            'brand_name' => 'テスト',
            'price' => 1000,
            'condition' => '良好',
            'item_image' => 'test.jpg',
            'user_id' => $user->id,
        ]);

        $paymentMethod = PaymentMethod::create([
            'payment_method' => 'コンビニ支払い',
        ]);

        Purchase::create([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'payment_methods_id' => $paymentMethod->id,
            'postal_code' => '000-0000',
            'address' => 'テスト住所',
        ]);

        $response = $this->get('/');

        $response->assertSee('sold');
    }

    public function test_purchased_item_is_shown_in_profile()
    {
        $user = User::factory()->create();

        Profile::create([
            'user_id' => $user->id,
            'postal_code' => '000-0000',
            'address' => 'テスト',
            'profile_image' => 'test.jpg',
        ]);

        $item = Item::create([
            'item_name' => '購入商品',
            'item_detail' => 'テスト',
            'brand_name' => 'テスト',
            'price' => 1000,
            'condition' => '良好',
            'item_image' => 'test.jpg',
            'user_id' => $user->id,
        ]);

        $paymentMethod = PaymentMethod::create([
            'payment_method' => 'コンビニ支払い',
        ]);

        Purchase::create([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'payment_methods_id' => $paymentMethod->id,
            'postal_code' => '000-0000',
            'address' => 'テスト住所',
        ]);

        $this->actingAs($user);

        $response = $this->get('/mypage?tab=buy');

        $response->assertSee('購入商品');
    }

    public function test_payment_method_is_reflected()
    {
        $user = User::factory()->create();

        Profile::create([
            'user_id' => $user->id,
            'postal_code' => '000-0000',
            'address' => 'テスト住所',
            'profile_image' => 'test.jpg',
        ]);

        $item = Item::create([
            'item_name' => 'テスト商品',
            'item_detail' => 'テスト',
            'brand_name' => 'テスト',
            'price' => 1000,
            'condition' => '良好',
            'item_image' => 'test.jpg',
            'user_id' => $user->id,
        ]);
        $paymentMethod = PaymentMethod::create([
            'payment_method' => 'コンビニ支払い',
        ]);

        $this->actingAs($user);

        $this->post("/purchase/{$item->id}", [
            'payment_methods_id' => $paymentMethod->id,
            'postal_code' => '000-0000',
            'address' => 'テスト住所',
        ]);

        $this->assertDatabaseHas('purchases', [
            'payment_methods_id' => $paymentMethod->id,
        ]);
    }

    public function test_shipping_address_is_saved()
    {
        $user = User::factory()->create();

        Profile::create([
            'user_id' => $user->id,
            'postal_code' => '111-1111',
            'address' => '初期住所',
            'building' => '初期建物',
            'profile_image' => 'test.jpg',
        ]);

        $item = Item::create([
            'item_name' => 'テスト商品',
            'item_detail' => 'テスト',
            'brand_name' => 'テスト',
            'price' => 1000,
            'condition' => '良好',
            'item_image' => 'test.jpg',
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $this->post("/purchase/{$item->id}/address", [
            'postal_code' => '222-2222',
            'address' => '変更後の住所',
            'building' => '変更後の建物',
        ]);

        $response = $this->get("/purchase/{$item->id}");

        $response->assertSee('222-2222');
        $response->assertSee('変更後の住所');
        $response->assertSee('変更後の建物');
}

    public function test_purchase_has_shipping_address()
    {
        $user = User::factory()->create();

        Profile::create([
            'user_id' => $user->id,
            'postal_code' => '111-1111',
            'address' => '初期住所',
            'building' => '初期建物',
            'profile_image' => 'test.jpg',
        ]);

        $item = Item::create([
            'item_name' => 'テスト商品',
            'item_detail' => 'テスト',
            'brand_name' => 'テスト',
            'price' => 1000,
            'condition' => '良好',
            'item_image' => 'test.jpg',
            'user_id' => $user->id,
        ]);

        $paymentMethod = PaymentMethod::create([
            'payment_method' => 'コンビニ支払い',
        ]);

        $this->actingAs($user);

        $this->post("/purchase/{$item->id}/address", [
            'postal_code' => '222-2222',
            'address' => '変更後の住所',
            'building' => '変更後の建物',
        ]);

        $this->withSession([
            'shipping' => [
                'postal_code' => '222-2222',
                'address' => '変更後の住所',
                'building' => '変更後の建物',
            ]
        ])->post("/purchase/{$item->id}", [
            'payment_methods_id' => $paymentMethod->id,
            'postal_code' => '222-2222',
            'address' => '変更後の住所',
                'building' => '変更後の建物',
            ]);

            $this->assertDatabaseHas('purchases', [
                'user_id' => $user->id,
                'item_id' => $item->id,
                'payment_methods_id' => $paymentMethod->id,
                'postal_code' => '222-2222',
                'address' => '変更後の住所',
            ]);
        }
    public function test_profile_get_user_information_and_items()
    {
        $user = User::factory()->create([
            'name' => 'テストユーザー',
        ]);

        Profile::create([
            'user_id' => $user->id,
            'postal_code' => '000-0000',
            'address' => 'テスト住所',
            'building' => 'テスト建物',
            'profile_image' => 'test.jpg',
        ]);

        $sellItem = Item::create([
            'item_name' => '出品商品A',
            'item_detail' => '説明',
            'brand_name' => 'ブランド',
            'price' => 1000,
            'condition' => '良好',
            'item_image' => 'sell.jpg',
            'user_id' => $user->id,
        ]);

        $seller = User::factory()->create();
        $buyItem = Item::create([
            'item_name' => '購入商品B',
            'item_detail' => '説明',
            'brand_name' => 'ブランド',
            'price' => 2000,
            'condition' => '良好',
            'item_image' => 'buy.jpg',
            'user_id' => $seller->id,
        ]);

        $paymentMethod = PaymentMethod::create([
            'payment_method' => 'コンビニ支払い',
        ]);

        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $buyItem->id,
            'payment_methods_id' => $paymentMethod->id,
            'postal_code' => '000-0000',
            'address' => 'テスト住所',
            'building' => '',
        ]);

        $this->actingAs($user);

        $responseSell = $this->get('/mypage?tab=sell');

        $responseSell->assertStatus(200);
        $responseSell->assertSee('テストユーザー');
        $responseSell->assertSee('storage/test.jpg');
        $responseSell->assertSee('出品商品A');

        $responseBuy = $this->get('/mypage?tab=buy');

        $responseBuy->assertStatus(200);
        $responseBuy->assertSee('購入商品B');
    }

    public function test_profile_edit_page_displays_initial_values()
    {
        $user = User::factory()->create([
            'name' => 'テストユーザー',
        ]);

        Profile::create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => '神奈川県相模原市テスト町1-2-3',
            'building' => 'テストビル101',
            'profile_image' => 'test.jpg',
        ]);

        $this->actingAs($user);

        $response = $this->get('/mypage/profile');

        $response->assertStatus(200);

        $response->assertSee('テストユーザー');

        $response->assertSee('123-4567');

        $response->assertSee('神奈川県相模原市テスト町1-2-3');

        $response->assertSee('テストビル101');

        $response->assertSee('storage/test.jpg');
    }
}