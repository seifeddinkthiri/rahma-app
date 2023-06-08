<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\Assert;
use Tests\TestCase;

class familiesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'account_id' => Account::create(['name' => 'Acme Corporation'])->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'johndoe@example.com',
            'owner' => true,
        ]);

        $this->user->account->families()->createMany([
            [
                'name' => 'Apple',
                'photo' => '',

            ],
        ]);
    }

    public function test_can_view_families()
    {
        $this->actingAs($this->user)
            ->get('/families')
            ->assertInertia(fn (Assert $assert) => $assert
                ->component('families/Index')
                ->has('families.data', 2)
                ->has('families.data.0', fn (Assert $assert) => $assert
                    ->where('id', 1)
                    ->where('name', 'Apple')
                    ->where('photo', '')
                    ->where('deleted_at', null)
                )

            );
    }

    public function test_can_search_for_families()
    {
        $this->actingAs($this->user)
            ->get('/families?search=Apple')
            ->assertInertia(fn (Assert $assert) => $assert
                ->component('families/Index')
                ->where('filters.search', 'Apple')
                ->has('families.data', 1)
                ->has('families.data.0', fn (Assert $assert) => $assert
                    ->where('id', 1)
                    ->where('name', 'Apple')
                    ->where('photo', '')
                    ->where('deleted_at', null)
                )
            );
    }

    public function test_cannot_view_deleted_families()
    {
        $this->user->account->families()->firstWhere('name', 'Microsoft')->delete();

        $this->actingAs($this->user)
            ->get('/families')
            ->assertInertia(fn (Assert $assert) => $assert
                ->component('families/Index')
                ->has('families.data', 1)
                ->where('families.data.0.name', 'Apple')
            );
    }

    public function test_can_filter_to_view_deleted_families()
    {
        $this->user->account->families()->firstWhere('name', 'Microsoft')->delete();

        $this->actingAs($this->user)
            ->get('/families?trashed=with')
            ->assertInertia(fn (Assert $assert) => $assert
                ->component('families/Index')
                ->has('families.data', 2)
                ->where('families.data.0.name', 'Apple')
                ->where('families.data.1.name', 'Microsoft')
            );
    }
}
