<?php

namespace Tests\Feature;

use App\Models\BurialPermit;
use App\Models\DeceasedPerson;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Carbon\Carbon;

class BurialPermitTest extends TestCase
{
    use DatabaseMigrations;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    /** @test */
    public function it_prevents_duplicate_permits_based_on_identical_names()
    {
        $this->actingAs($this->admin);

        // Create initial permit
        $deceased = DeceasedPerson::create([
            'first_name' => 'John',
            'last_name'  => 'Doe',
            'middle_name' => 'Middle',
            'name_extension' => 'Jr.',
            'date_of_death' => now()->subDays(10),
        ]);

        BurialPermit::create([
            'permit_number' => 'BP-2026-00001',
            'deceased_id'   => $deceased->id,
            'status'        => 'active',
        ]);

        // Attempt to create identical record via Controller
        $response = $this->from(route('permits.index'))->post(route('permits.store'), [
            'first_name'     => 'John',
            'last_name'      => 'Doe',
            'middle_name'    => 'Middle',
            'name_extension' => 'Jr.',
            'date_of_death'  => now()->toDateString(),
            'burial_fee_type' => 'ordinary',
            'kind_of_burial' => 'Niche',
            'requestor_name' => 'Applicant Name',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('error', 'Cannot create permit: A person with the name "Doe, John M. Jr." already has an existing record.');
    }

    /** @test */
    public function it_alerts_similarity_when_first_and_last_names_match()
    {
        $this->actingAs($this->admin);

        // Create initial permit
        DeceasedPerson::create([
            'first_name' => 'John',
            'last_name'  => 'Doe',
            'middle_name' => 'Alpha',
        ]);

        // Attempt to create similar (but not identical) record
        $response = $this->from(route('permits.index'))->post(route('permits.store'), [
            'first_name'     => 'John',
            'last_name'      => 'Doe',
            'middle_name'    => 'Beta', // Different middle name
            'date_of_death'  => now()->toDateString(),
            'burial_fee_type' => 'ordinary',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('error', 'Cannot create permit: A person with the name "Doe, John A." already exists. Please provide distinguishing details.');
    }

    /** @test */
    public function it_renews_expired_permits_correctly()
    {
        $this->actingAs($this->admin);

        $expiredDate = now()->subYear();
        $deceased = DeceasedPerson::create(['first_name' => 'Jane', 'last_name' => 'Doe']);
        $permit = BurialPermit::create([
            'permit_number' => 'BP-2024-00123',
            'deceased_id'   => $deceased->id,
            'status'        => 'expired',
            'expiry_date'   => $expiredDate,
        ]);

        $response = $this->post(route('permits.renew', $permit->id));

        $response->assertStatus(302);
        $permit->refresh();

        $this->assertEquals('active', $permit->status);
        $this->assertTrue(Carbon::parse($permit->expiry_date)->isFuture());
        $this->assertEquals(1, $permit->renewal_count);
    }
}
