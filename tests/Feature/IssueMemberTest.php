<?php

namespace Tests\Feature;

use App\Models\Issue;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IssueMemberTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_attach_a_member_to_an_issue_via_ajax(): void
    {
        $user = User::factory()->create();
        $member = User::factory()->create();
        $issue = Issue::factory()->create();

        $response = $this
            ->actingAs($user)
            ->postJson(route('issues.members.attach', $issue), [
                'user_id' => $member->id,
            ]);

        $response->assertOk();
        $response->assertJsonFragment([
            'id' => $member->id,
            'name' => $member->name,
        ]);

        $this->assertDatabaseHas('issue_user', [
            'issue_id' => $issue->id,
            'user_id' => $member->id,
        ]);
    }

    public function test_user_can_detach_a_member_from_an_issue_via_ajax(): void
    {
        $user = User::factory()->create();
        $member = User::factory()->create();
        $issue = Issue::factory()->create();

        $issue->members()->attach($member->id);

        $response = $this
            ->actingAs($user)
            ->deleteJson(route('issues.members.detach', ['issue' => $issue, 'user' => $member]));

        $response->assertOk();
        $this->assertDatabaseMissing('issue_user', [
            'issue_id' => $issue->id,
            'user_id' => $member->id,
        ]);
    }
}
