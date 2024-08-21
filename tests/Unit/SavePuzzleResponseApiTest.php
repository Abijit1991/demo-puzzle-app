<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

/**
 * SavePuzzleResponseApiTest
 *
 * Test cases to test the show puzzle toppers api.
 *
 * @author Abijit <abijit.a.1991@gmail.com>
 *
 * @version 1.0.0
 */
class SavePuzzleResponseApiTest extends TestCase
{
    /**
     * Reset database after each test cases.
     */
    use DatabaseTransactions;

    /**
     * Prevent all middleware from being executed for this test class.
     */
    use WithoutMiddleware;

    /**
     * Set up the test environment before each test case.
     *
     * @return void
     */
    public function setUp(): void
    {
        // Calls the setUp method of the parent class.
        parent::setUp();

        // Define the api url.
        $this->apiUrl = '/student/puzzle/response/submit';

        // Create a fake user.
        $this->user = $this->createFakeUser();
    }

    /**
     * Method to test the api with invalid data.
     *
     * @return void
     */
    public function test_show_puzzle_api_invalid_data(): void
    {
        // Manually authenticate the user.
        Auth::login($this->user);

        // Assert that the user is authenticated
        $this->assertEquals($this->user->id, Auth::id());

        // Form an array with the invalid testing data.
        $apiParameters = [
            [], // Without Puzzle Id & Response.
            [
                'puzzle_id' => 'A23423A', // Invalid Puzzle Id
                'response' => 'add@dafasdf123334' // Invalid Response
            ],
            [
                'puzzle_id' => $this->generateInvalidPuzzleId(), // Non-exist Puzzle Id
                'response' => 'add'
            ]
        ];

        // Iterate through each invalid data and assert the response.
        foreach ($apiParameters as $apiParameter) {
            // Visit the given URI with a GET request and return response.
            $response = $this->post($this->apiUrl, $apiParameter);

            // Assert whether the response is redirecting to a given URI.
            $response->assertRedirect(route('student.home'));
        }
    }

    /**
     * Method to test the api with valid data.
     *
     * @return void
     */
    public function test_show_puzzle_api_valid_data(): void
    {
        // Manually authenticate the user.
        Auth::login($this->user);

        // Assert that the user is authenticated
        $this->assertEquals($this->user->id, Auth::id());

        // Set api.
        $api = $this->apiUrl;

        $apiParameters = [
            'puzzle_id' => $this->getOldPuzzleId(),
            'response' => 'puzzle'
        ];

        // Visit the given URI with a POST request and return response.
        $response = $this->post($api, $apiParameters);

        // Assert that the response has the given status code.
        $response->assertStatus(RESPONSE::HTTP_FOUND);

        // Assert whether the response is redirecting to a given URI.
        $response->assertRedirect(route('student.showpuzzle', ['puzzle_id' => $this->getOldPuzzleId()]));
    }
}
