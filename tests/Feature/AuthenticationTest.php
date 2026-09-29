<?php

namespace Tests\Feature;

use App\Http\Requests\LoginRequest;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, array{string, string}>
     */
    public static function protectedRoutes(): array
    {
        return [
            'dashboard' => ['get', '/'],
            'students index' => ['get', '/students'],
            'student create' => ['get', '/students/create'],
            'student store' => ['post', '/students'],
            'student show' => ['get', '/students/1'],
            'student update' => ['put', '/students/1'],
            'student delete' => ['delete', '/students/1'],
            'courses index' => ['get', '/courses'],
            'course store' => ['post', '/courses'],
            'course delete' => ['delete', '/courses/1'],
        ];
    }

    #[DataProvider('protectedRoutes')]
    public function test_guests_are_redirected_to_login(string $method, string $uri): void
    {
        Student::factory()->create();

        $this->{$method}($uri)->assertRedirect(route('login'));
    }

    public function test_guests_cannot_change_data(): void
    {
        $student = Student::factory()->create();

        $this->delete(route('students.destroy', $student))->assertRedirect(route('login'));

        $this->assertModelExists($student);
    }

    public function test_login_page_is_shown(): void
    {
        $this->get(route('login'))->assertOk()->assertSee('Sign in');
    }

    public function test_users_can_sign_in_and_are_sent_to_the_page_they_wanted(): void
    {
        $user = User::factory()->create();

        $this->get(route('students.index'))->assertRedirect(route('login'));

        $this->post(route('login'), ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect(route('students.index'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_users_cannot_sign_in_with_a_wrong_password(): void
    {
        $user = User::factory()->create();

        $this->post(route('login'), ['email' => $user->email, 'password' => 'wrong-password'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_sign_in_is_rate_limited_after_repeated_failures(): void
    {
        $user = User::factory()->create();

        for ($i = 0; $i < LoginRequest::MAX_ATTEMPTS; $i++) {
            $this->post(route('login'), ['email' => $user->email, 'password' => 'wrong-password']);
        }

        // Even the correct password is refused while locked out.
        $this->post(route('login'), ['email' => $user->email, 'password' => 'password'])
            ->assertSessionHasErrors('email');

        $this->assertStringContainsString('Too many login attempts', session('errors')->first('email'));

        $this->assertGuest();
    }

    public function test_signed_in_users_are_redirected_away_from_login(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('login'))
            ->assertRedirect(route('dashboard'));
    }

    public function test_users_can_sign_out(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_navigation_shows_the_signed_in_user(): void
    {
        $user = User::factory()->create(['name' => 'Grace Njeri']);

        $this->actingAs($user)->get(route('dashboard'))
            ->assertSee('Grace Njeri')
            ->assertSee('Sign out');
    }

    public function test_user_create_command_creates_an_account(): void
    {
        $this->artisan('user:create', ['--name' => 'Staff Member', '--email' => 'staff@example.com'])
            ->expectsQuestion('Password (min. 8 characters)', 'secret-pass')
            ->expectsQuestion('Confirm password', 'secret-pass')
            ->expectsOutput('Created user staff@example.com.')
            ->assertSuccessful();

        $this->post(route('login'), ['email' => 'staff@example.com', 'password' => 'secret-pass']);
        $this->assertAuthenticated();
    }

    public function test_user_create_command_rejects_short_or_mismatched_passwords(): void
    {
        $this->artisan('user:create', ['--name' => 'Staff', '--email' => 'staff@example.com'])
            ->expectsQuestion('Password (min. 8 characters)', 'short')
            ->expectsQuestion('Confirm password', 'different')
            ->assertFailed();

        $this->assertDatabaseMissing('users', ['email' => 'staff@example.com']);
    }
}
