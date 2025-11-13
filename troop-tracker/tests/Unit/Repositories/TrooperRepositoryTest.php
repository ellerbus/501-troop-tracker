<?php

declare(strict_types=1);

namespace Tests\Unit\Repositories;

use App\Enums\MembershipStatus;
use App\Models\Club;
use App\Models\Squad;
use App\Models\Trooper;
use App\Models\TrooperClub;
use App\Repositories\ClubRepository;
use App\Repositories\TrooperRepository;
use Database\Seeders\ClubSeeder;
use Database\Seeders\SquadSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrooperRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private TrooperRepository $subject;
    private Club $club_501st;
    private Club $club_mando;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ClubSeeder::class);
        $this->seed(SquadSeeder::class);

        $this->club_501st = Club::where('name', '501st')->first();
        $this->club_mando = Club::where('name', 'Mando Mercs')->first();

        $this->subject = new TrooperRepository(new ClubRepository());
    }

    public function test_get_by_id(): void
    {
        // Arrange
        $trooper = Trooper::factory()->create();

        // Act
        $found_trooper = $this->subject->getById($trooper->id);
        $not_found_trooper = $this->subject->getById(999);

        // Assert
        $this->assertInstanceOf(Trooper::class, $found_trooper);
        $this->assertEquals($trooper->id, $found_trooper->id);
        $this->assertNull($not_found_trooper);
    }

    public function test_get_by_username(): void
    {
        // Arrange
        $trooper = Trooper::factory()->create(['forum_id' => 'testuser']);

        // Act
        $found_trooper = $this->subject->getByForumUsername('testuser');
        $not_found_trooper = $this->subject->getByForumUsername('nonexistent');

        // Assert
        $this->assertInstanceOf(Trooper::class, $found_trooper);
        $this->assertEquals($trooper->id, $found_trooper->id);
        $this->assertNull($not_found_trooper);
    }

    public function test_club_identifier_exists_in_troopers_table(): void
    {
        // Arrange
        Trooper::factory()->create(['tkid' => 'TK12345']);

        // Act
        $exists = $this->subject->clubIdentifierExists($this->club_501st->id, 'TK12345');
        $does_not_exist = $this->subject->clubIdentifierExists($this->club_501st->id, 'TK54321');

        // Assert
        $this->assertTrue($exists);
        $this->assertFalse($does_not_exist);
    }

    public function test_club_identifier_exists_in_trooper_clubs_table(): void
    {
        // Arrange
        $trooper = Trooper::factory()->create();

        TrooperClub::factory()->create([
            'trooper_id' => $trooper->id,
            'club_id' => $this->club_501st->id,
            'identifier' => 'ID-ABC',
        ]);

        // Act
        $exists = $this->subject->clubIdentifierExists($this->club_501st->id, 'ID-ABC');
        $does_not_exist = $this->subject->clubIdentifierExists($this->club_501st->id, 'ID-XYZ');

        // Assert
        $this->assertTrue($exists);
        $this->assertFalse($does_not_exist);
    }

    public function test_register_creates_trooper_and_associations(): void
    {
        // Arrange
        $squad = Squad::where('name', 'Makaze Squad')->first();
        $auth_user_id = 12345;

        $data = [
            'name' => 'Test Trooper',
            'email' => 'test@trooper.com',
            'phone' => '555-1234',
            'username' => 'test_trooper_forum',
            'password' => 'password123',
            'account_type' => 1, // Member
            'clubs' => [
                $this->club_501st->id => [
                    'selected' => '1',
                    'identifier' => 'TK54321',
                    'squad_id' => $squad->id,
                ],
            ]
        ];

        // Act
        $trooper = $this->subject->register($data, $auth_user_id);

        // Assert Trooper details
        $this->assertInstanceOf(Trooper::class, $trooper);
        $this->assertDatabaseHas('troopers', [
            'id' => $trooper->id,
            'name' => 'Test Trooper',
            'email' => 'test@trooper.com',
            'phone' => '555-1234',
            'user_id' => $auth_user_id,
            'forum_id' => 'test_trooper_forum',
        ]);

        // Assert password was hashed
        $this->assertTrue(password_verify('password123', $trooper->password));

        // Assert legacy club identifier and status fields were set
        $this->assertEquals(MembershipStatus::Member, $trooper->p501);
        $this->assertEquals('TK54321', $trooper->tkid);

        // Assert legacy squad was set
        $this->assertEquals($squad->id, $trooper->squad);

        // Assert trooper_clubs pivot table association
        $this->assertDatabaseHas('trooper_clubs', [
            'trooper_id' => $trooper->id,
            'club_id' => $this->club_501st->id,
            'membership_status' => MembershipStatus::Member->value,
        ]);

        // Assert trooper_squads pivot table association
        $this->assertDatabaseHas('trooper_squads', [
            'trooper_id' => $trooper->id,
            'squad_id' => $squad->id,
            'membership_status' => MembershipStatus::Member->value,
        ]);

        // Assert club 2 was NOT associated
        $this->assertDatabaseMissing('trooper_clubs', [
            'trooper_id' => $trooper->id,
            'club_id' => $this->club_mando->id,
        ]);
    }
}
