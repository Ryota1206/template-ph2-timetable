<?php

namespace Tests\Feature;

use App\Models\TimetableEntry;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // 授業の正本データや開発用DBを変更せず、メモリ内だけで検証する。
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->integer('day');
            $table->integer('period');
            $table->string('name');
            $table->string('teacher');
            $table->string('room');
        });
        Schema::create('timetable_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignId('course_id')->constrained();
            $table->unique(['user_id', 'course_id']);
            $table->timestamps();
        });
    }

    private function registration(array $overrides = []): array
    {
        return array_replace([
            'name' => '受講生',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ], $overrides);
    }

    public function test_registration_hashes_password_and_logs_in(): void
    {
        $this->get('/register')->assertOk()->assertSee('確認用パスワード');
        $this->withSession(['_token' => 'before']);
        $before = session()->getId();
        $this->post('/register', $this->registration())->assertRedirect('/entries');
        $user = User::firstOrFail();
        $this->assertAuthenticatedAs($user);
        $this->assertTrue(Hash::check('password123', $user->password));
        $this->assertNotSame($before, session()->getId());
        $this->get('/entries')->assertOk()->assertSee('ログアウト');
        $this->get('/login')->assertRedirect('/entries');
        $this->get('/register')->assertRedirect('/entries');
    }

    public static function invalidRegistration(): array
    {
        return [
            '名前必須' => ['name', '', '名前を入力してください'],
            '名前文字数' => ['name', str_repeat('あ', 256), '名前は255文字以内で入力してください'],
            'メール必須' => ['email', '', 'メールアドレスを入力してください'],
            'メール形式' => ['email', 'invalid', 'メールの形式が違います。直してください'],
            'メール文字数' => ['email', str_repeat('a', 244).'@example.com', 'メールアドレスは255文字以内で入力してください'],
            'パスワード必須' => ['password', '', 'パスワードを入力してください'],
            'パスワード文字数' => ['password', 'short', 'パスワードは８文字以上で入力してください'],
            '確認不一致' => ['password_confirmation', 'different', 'パスワードが違います'],
        ];
    }

    #[DataProvider('invalidRegistration')]
    public function test_registration_validation(string $field, string $value, string $message): void
    {
        $data = $this->registration([$field => $value]);
        $this->post('/register', $data)
            ->assertRedirect('/register')
            ->assertSessionHasErrors([$field === 'password_confirmation' ? 'password' : $field => $message])
            ->assertSessionHasInput('name', $data['name'])
            ->assertSessionHasInput('email', $data['email'])
            ->assertSessionMissing('_old_input.password')
            ->assertSessionMissing('_old_input.password_confirmation');
        $this->assertGuest();
        $this->assertDatabaseCount('users', 0);
        $this->get('/register')->assertSee($message);
    }

    public function test_email_uniqueness_and_login_are_case_sensitive(): void
    {
        User::create($this->registration());
        $this->post('/register', $this->registration())
            ->assertSessionHasErrors(['email' => 'このメールアドレスは既に登録済みです']);
        $this->post('/login', ['email' => 'TEST@example.com', 'password' => 'password123'])
            ->assertRedirect('/login')->assertSessionHasErrors('email');
        $this->assertGuest();
        $this->post('/register', $this->registration(['email' => 'TEST@example.com']))
            ->assertRedirect('/entries');
        $this->assertDatabaseCount('users', 2);
        $this->assertSame('TEST@example.com', auth()->user()->email);
    }

    public function test_login_failures_keep_only_email(): void
    {
        User::create($this->registration());
        foreach (['wrong', '', ['invalid']] as $password) {
            $this->post('/login', ['email' => 'test@example.com', 'password' => $password])
                ->assertRedirect('/login')
                ->assertSessionHasErrors(['email' => 'メールアドレスかパスワードが違います'])
                ->assertSessionHasInput('email', 'test@example.com')
                ->assertSessionMissing('_old_input.password');
            $this->assertGuest();
        }
        $this->get('/login')->assertOk()->assertSee('メールアドレスかパスワードが違います');
    }

    public function test_login_regenerates_session_and_logout_invalidates_it(): void
    {
        $user = User::create($this->registration());
        $this->withSession(['marker' => 'ログイン前', '_token' => 'before']);
        $before = session()->getId();
        $this->post('/login', ['email' => $user->email, 'password' => 'password123'])
            ->assertRedirect('/entries');
        $this->assertAuthenticatedAs($user);
        $this->assertNotSame($before, session()->getId());
        $before = session()->getId();
        $token = session()->token();
        $this->post('/logout')->assertRedirect('/login')->assertSessionMissing('marker');
        $this->assertGuest();
        $this->assertNotSame($before, session()->getId());
        $this->assertNotSame($token, session()->token());
        $this->get('/entries')->assertRedirect('/login');
        $this->get('/logout')->assertStatus(405);
    }

    public function test_all_timetable_routes_require_login(): void
    {
        foreach (['/entries', '/entries/create', '/courses', '/timetable'] as $url) {
            $this->get($url)->assertRedirect('/login');
        }
        $this->post('/entries', ['course_id' => 1])->assertRedirect('/login');
        $this->delete('/entries/1')->assertRedirect('/login');
        $this->post('/logout')->assertRedirect('/login');
    }

    public function test_entries_are_registered_listed_and_deleted_per_user(): void
    {
        $owner = User::create($this->registration());
        $other = User::create($this->registration(['email' => 'other@example.com']));
        DB::table('courses')->insert([
            ['id' => 1, 'day' => 1, 'period' => 1, 'name' => '情報学', 'teacher' => '先生', 'room' => '101'],
            ['id' => 2, 'day' => 1, 'period' => 1, 'name' => '数学', 'teacher' => '先生', 'room' => '102'],
        ]);
        $this->actingAs($owner)->post('/entries', ['course_id' => 1, 'user_id' => $other->id])
            ->assertRedirect('/entries');
        $entry = TimetableEntry::firstOrFail();
        $this->assertSame($owner->id, $entry->user_id);
        $this->get('/entries')->assertOk()->assertSee('情報学');
        $this->get('/entries/create')->assertOk();
        $this->get('/courses')->assertOk()->assertSee('登録済み');
        $this->from('/entries/create')->post('/entries', ['course_id' => 2])->assertSessionHasErrors('course_id');
        $this->actingAs($other)->get('/entries')->assertOk()->assertDontSee('情報学');
        $this->get('/courses')->assertOk()->assertDontSee('登録済み');
        $this->delete('/entries/'.$entry->id)->assertForbidden();
        $this->post('/entries', ['course_id' => 1])->assertRedirect('/entries');
        $this->assertDatabaseCount('timetable_entries', 2);
        $this->actingAs($owner)->delete('/entries/'.$entry->id)->assertRedirect('/entries');
        $this->assertDatabaseMissing('timetable_entries', ['id' => $entry->id]);
        $this->assertDatabaseHas('timetable_entries', ['user_id' => $other->id, 'course_id' => 1]);
    }

    public function test_forms_use_csrf_protection(): void
    {
        // 通常テスト時に無効になるCSRF検証を有効にして確認する。
        $this->app['env'] = 'local';
        $this->get('/register')->assertSee('name="_token"', false);
        $this->post('/register', $this->registration())->assertStatus(419);
        $this->post('/login', [])->assertStatus(419);
        $this->actingAs(User::create($this->registration()))->post('/logout')->assertStatus(419);
    }
}
