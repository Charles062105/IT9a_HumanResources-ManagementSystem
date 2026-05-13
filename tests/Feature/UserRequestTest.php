<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRequestTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_view_user_requests(): void
    {
        $admin = User::factory()->create(['role' => 'super_admin', 'status' => 'active']);
        $user = User::factory()->create(['status' => 'pending']);
        UserRequest::factory()->create(['user_id' => $user->id, 'type' => 'Account Activation']);

        $response = $this->actingAs($admin)->get('/requests');

        $response->assertStatus(200);
        $response->assertSee($user->name);
    }

    /** @test */
    public function non_admin_cannot_view_user_requests(): void
    {
        $employee = User::factory()->create(['role' => 'employee', 'status' => 'active']);

        $response = $this->actingAs($employee)->get('/requests');

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_approve_registration_request(): void
    {
        $admin = User::factory()->create(['role' => 'super_admin', 'status' => 'active']);
        $user = User::factory()->create(['status' => 'pending']);
        $userRequest = UserRequest::factory()->create([
            'user_id' => $user->id,
            'type' => 'Account Activation',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)
            ->patch("/requests/{$userRequest->id}/approve");

        // Verify user status changed to active
        $this->assertSame('active', $user->fresh()->status);

        // Verify request status changed to approved
        $this->assertSame('approved', $userRequest->fresh()->status);

        // Verify admin is recorded as resolver
        $this->assertSame($admin->id, $userRequest->fresh()->resolved_by);

        // Verify notification was created
        $this->assertDatabaseHas('hrms_notifications', [
            'user_id' => $user->id,
            'title' => 'Account Activated',
            'type' => 'success',
        ]);

        // Verify redirect to employee setup
        $response->assertRedirect(route('employees.setup', ['user' => $user->id]));
    }

    /** @test */
    public function admin_can_reject_registration_request(): void
    {
        $admin = User::factory()->create(['role' => 'super_admin', 'status' => 'active']);
        $user = User::factory()->create(['status' => 'pending']);
        $userRequest = UserRequest::factory()->create([
            'user_id' => $user->id,
            'type' => 'Account Activation',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)
            ->patch("/requests/{$userRequest->id}/reject");

        // Verify user status changed to rejected
        $this->assertSame('rejected', $user->fresh()->status);

        // Verify request status changed to rejected
        $this->assertSame('rejected', $userRequest->fresh()->status);

        // Verify admin is recorded as resolver
        $this->assertSame($admin->id, $userRequest->fresh()->resolved_by);

        // Verify notification was created
        $this->assertDatabaseHas('hrms_notifications', [
            'user_id' => $user->id,
            'title' => 'Account Rejected',
            'type' => 'error',
        ]);

        $response->assertSessionHas('success', 'Request rejected.');
    }

    /** @test */
    public function approved_user_can_login(): void
    {
        $user = User::factory()->create([
            'email' => 'approved@example.com',
            'password' => bcrypt('password'),
            'status' => 'active',
        ]);

        $response = $this->post('/login', [
            'email' => 'approved@example.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard'));
    }

    /** @test */
    public function pending_user_cannot_login(): void
    {
        $user = User::factory()->create([
            'email' => 'pending@example.com',
            'password' => bcrypt('password'),
            'status' => 'pending',
        ]);

        $response = $this->post('/login', [
            'email' => 'pending@example.com',
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors(['email' => 'Your account is pending admin approval. Please wait for notification.']);
    }

    /** @test */
    public function rejected_user_cannot_login(): void
    {
        $user = User::factory()->create([
            'email' => 'rejected@example.com',
            'password' => bcrypt('password'),
            'status' => 'rejected',
        ]);

        $response = $this->post('/login', [
            'email' => 'rejected@example.com',
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors(['email' => 'Your account has been rejected or is inactive. Please contact support.']);
    }

    /** @test */
    public function inactive_user_cannot_login(): void
    {
        $user = User::factory()->create([
            'email' => 'inactive@example.com',
            'password' => bcrypt('password'),
            'status' => 'inactive',
        ]);

        $response = $this->post('/login', [
            'email' => 'inactive@example.com',
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors(['email' => 'Your account has been rejected or is inactive. Please contact support.']);
    }

    /** @test */
    public function admin_can_make_employee_admin(): void
    {
        $admin = User::factory()->create(['role' => 'super_admin', 'status' => 'active']);
        $employee = User::factory()->create(['role' => 'employee', 'status' => 'active']);

        $response = $this->actingAs($admin)
            ->patch("/users/{$employee->id}/assign-admin-role", ['role' => 'super_admin']);

        $this->assertSame('super_admin', $employee->fresh()->role);

        // Verify notification was created
        $this->assertDatabaseHas('hrms_notifications', [
            'user_id' => $employee->id,
            'title' => 'Admin Role Granted',
        ]);

        $response->assertSessionHas('success');
    }

    /** @test */
    public function non_admin_cannot_make_someone_admin(): void
    {
        $employee1 = User::factory()->create(['role' => 'employee', 'status' => 'active']);
        $employee2 = User::factory()->create(['role' => 'employee', 'status' => 'active']);

        $response = $this->actingAs($employee1)
            ->get("/users/{$employee2->id}/make-admin");

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_cannot_promote_themselves(): void
    {
        $admin1 = User::factory()->create(['role' => 'super_admin', 'status' => 'active']);

        $response = $this->actingAs($admin1)
            ->get("/users/{$admin1->id}/make-admin");

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_revoke_admin_role(): void
    {
        $admin1 = User::factory()->create(['role' => 'super_admin', 'status' => 'active']);
        $admin2 = User::factory()->create(['role' => 'sub_admin', 'status' => 'active']);

        $response = $this->actingAs($admin1)
            ->patch("/users/{$admin2->id}/revoke-admin");

        $this->assertSame('employee', $admin2->fresh()->role);

        // Verify notification was created
        $this->assertDatabaseHas('hrms_notifications', [
            'user_id' => $admin2->id,
            'title' => 'Admin Role Revoked',
        ]);

        $response->assertSessionHas('success');
    }

    /** @test */
    public function cannot_revoke_admin_role_if_only_one_admin_remains(): void
    {
        $lastAdmin = User::factory()->create(['role' => 'super_admin', 'status' => 'active']);
        $actingAdmin = User::factory()->create(['role' => 'super_admin', 'status' => 'active']);

        // Demote actingAdmin so only lastAdmin remains
        $actingAdmin->update(['role' => 'employee']);

        $response = $this->actingAs($actingAdmin)
            ->patch("/users/{$lastAdmin->id}/revoke-admin");

        $response->assertStatus(403);
        $this->assertSame('super_admin', $lastAdmin->fresh()->role);
    }

    /** @test */
    public function admin_cannot_revoke_their_own_admin_role(): void
    {
        $admin1 = User::factory()->create(['role' => 'super_admin', 'status' => 'active']);
        $admin2 = User::factory()->create(['role' => 'super_admin', 'status' => 'active']);

        $response = $this->actingAs($admin1)
            ->patch("/users/{$admin1->id}/revoke-admin");

        $response->assertStatus(403);
        $this->assertSame('super_admin', $admin1->fresh()->role);
    }

    /** @test */
    public function can_search_user_requests(): void
    {
        $admin = User::factory()->create(['role' => 'super_admin', 'status' => 'active']);
        $user1 = User::factory()->create(['name' => 'John Doe', 'status' => 'pending']);
        $user2 = User::factory()->create(['name' => 'Jane Smith', 'status' => 'pending']);
        UserRequest::factory()->create(['user_id' => $user1->id]);
        UserRequest::factory()->create(['user_id' => $user2->id]);

        $response = $this->actingAs($admin)->get('/requests?search=John');
        $response->assertStatus(200);
        $response->assertSee('John Doe');
        $response->assertDontSee('Jane Smith');
    }

    /** @test */
    public function can_filter_user_requests_by_type(): void
    {
        $admin = User::factory()->create(['role' => 'super_admin', 'status' => 'active']);
        $matchingUser = User::factory()->create(['status' => 'pending']);
        $otherUser = User::factory()->create(['status' => 'pending']);
        UserRequest::factory()->create([
            'user_id' => $matchingUser->id,
            'type' => 'Account Activation',
        ]);
        UserRequest::factory()->create([
            'user_id' => $otherUser->id,
            'type' => 'Role Change',
        ]);

        $response = $this->actingAs($admin)->get('/requests?type=Account+Activation');

        $response->assertStatus(200);
        $response->assertSee($matchingUser->name);
        $response->assertDontSee($otherUser->name);
    }

    /** @test */
    public function can_filter_user_requests_by_status(): void
    {
        $admin = User::factory()->create(['role' => 'super_admin', 'status' => 'active']);
        $pendingUser = User::factory()->create(['status' => 'pending']);
        $approvedUser = User::factory()->create(['status' => 'active']);
        UserRequest::factory()->create([
            'user_id' => $pendingUser->id,
            'status' => 'pending',
        ]);
        UserRequest::factory()->create([
            'user_id' => $approvedUser->id,
            'status' => 'approved',
        ]);

        $response = $this->actingAs($admin)->get('/requests?status=pending');

        $response->assertStatus(200);
        $response->assertSee($pendingUser->name);
        $response->assertDontSee($approvedUser->name);
    }
}
