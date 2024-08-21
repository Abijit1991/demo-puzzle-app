<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Puzzle;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * API URL.
     *
     * @var string
     */
    protected $apiUrl;

    /**
     * Fake User.
     *
     * @var mixed
     */
    protected $user;

    public function generateInvalidPuzzleId()
    {
        $maxPuzzleId = Puzzle::max('id');

        return is_null($maxPuzzleId) ? 1 : $maxPuzzleId + 1;
    }

    public function getStudentRoleId()
    {
        return Role::where('slug', config('constants.ROLES.STUDENT'))->value('id');
    }

    public function getOldPuzzleId()
    {
        return Puzzle::oldest('id')->value('id');
    }

    public function createFakeUser()
    {
        return User::factory()->create([
            'role_id' => $this->getStudentRoleId(),
            'name' => 'AAAAAAA',
            'email' => 'AAAAAA@AAAAAAA.com'
        ]);
    }
}
