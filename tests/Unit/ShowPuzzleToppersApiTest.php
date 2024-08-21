<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

/**
 * ShowPuzzleToppersApiTest
 *
 * Test cases to test the show puzzle toppers api.
 *
 * @author Abijit <abijit.a.1991@gmail.com>
 *
 * @version 1.0.0
 */
class ShowPuzzleToppersApiTest extends TestCase
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
        $this->apiUrl = '/student/puzzle/toppers/';

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
        $apiUrls = [
            ['api' => $this->apiUrl . 'd232'], // Not an integer Puzzle Id
            ['api' => $this->apiUrl . $this->generateInvalidPuzzleId()] // Non-exist Puzzle Id
        ];

        // Iterate through each invalid data and assert the response.
        foreach ($apiUrls as $apiUrl) {
            // Visit the given URI with a GET request and return response.
            $response = $this->get($apiUrl['api']);

            // Assert that the response has the given status code.
            $response->assertStatus(RESPONSE::HTTP_FOUND);

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
        $api = $this->apiUrl . $this->getOldPuzzleId();

        // Visit the given URI with a GET request and return response.
        $response = $this->get($api);

        // Assert that the response has the given status code.
        $response->assertStatus(RESPONSE::HTTP_OK);

        // Get the view from the response
        $view = $response->baseResponse->original;

        // Retrieve the name from the view
        $viewData = $view->name();

        // Asserts that two variables are equal.
        $this->assertEquals($viewData, 'student.showpuzzletopscorers');
    }
}
