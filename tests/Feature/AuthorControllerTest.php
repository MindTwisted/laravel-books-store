<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use JWTAuth;
use App\User;
use App\Author;

class AuthorControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected static $adminToken;
    protected static $userToken;

    public function setUp()
    {
        parent::setUp();

        if (!static::$adminToken)
        {
            static::$adminToken = JWTAuth::attempt([
                'email' => 'john@example.com',
                'password' => 'secret'
            ]);
        }

        if (!static::$userToken)
        {
            static::$userToken = JWTAuth::attempt([
                'email' => 'smith@example.com',
                'password' => 'secret'
            ]);
        }
    }
    
    public function testCanGetAllAuthors()
    {
        $response = $this
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->json('GET', route('api.authors.index'));

        $response
            ->assertStatus(200)
            ->assertJsonCount(40, 'data.authors')
            ->assertJsonStructure([
                'data' => [
                    'authors' => [
                        '*' => [
                            'id',
                            'name'
                        ]
                    ]
                ]
            ]);
    }

    public function testCanGetSingleAuthor()
    {
        $response = $this
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->json('GET', route('api.authors.show', ['id' => 5]));

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'author' => [
                        'id',
                        'name'
                    ]
                ]
            ]);
    }

    public function testUnauthorizedUserCanNotStoreNewAuthor()
    {
        $response = $this
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->json('POST', route('api.authors.store'));

        $response
            ->assertStatus(401);
    }

    public function testNotAdminUserCanNotStoreNewAuthor()
    {
        $response = $this
            ->withHeaders([
                'Authorization' => 'Bearer ' . static::$userToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->json('POST', route('api.authors.store'));

        $response
            ->assertStatus(403);
    }

    public function testAdminUserCanStoreNewAuthor()
    {
        $response = $this
            ->withHeaders([
                'Authorization' => 'Bearer ' . static::$adminToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->json('POST', route('api.authors.store'));

        $response
            ->assertStatus(422);
    }

    public function testStoreNewAuthorWithoutName()
    {
        $response = $this
            ->withHeaders([
                'Authorization' => 'Bearer ' . static::$adminToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->json('POST', route('api.authors.store'));

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => [
                    'name'
                ]
            ]);
    }

    public function testStoreNewAuthorWithDuplicateName()
    {
        $author = Author::find(1);

        $response = $this
            ->withHeaders([
                'Authorization' => 'Bearer ' . static::$adminToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->json('POST', route('api.authors.store'), [
                'name' => $author->name
            ]);

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => [
                    'name'
                ]
            ]);
    }

    public function testAdminUserCanStoreNewAuthorWithUniqueName()
    {
        $uniqueName = 'Some really unique name';

        $response = $this
            ->withHeaders([
                'Authorization' => 'Bearer ' . static::$adminToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->json('POST', route('api.authors.store'), [
                'name' => $uniqueName
            ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'author' => [
                        'name',
                        'id'
                    ]
                ]
            ])
            ->assertJson([
                'data' => [
                    'author' => [
                        'name' => $uniqueName
                    ]
                ]
            ]);
    }

    public function testUnauthorizedUserCanNotUpdateAuthor()
    {
        $response = $this
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->json('PUT', route('api.authors.update', ['id' => 5]));

        $response
            ->assertStatus(401);
    }

    public function testNotAdminUserCanNotUpdateAuthor()
    {
        $response = $this
            ->withHeaders([
                'Authorization' => 'Bearer ' . static::$userToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->json('PUT', route('api.authors.update', ['id' => 5]));

        $response
            ->assertStatus(403);
    }

    public function testAdminUserCanUpdateAuthor()
    {
        $response = $this
            ->withHeaders([
                'Authorization' => 'Bearer ' . static::$adminToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->json('PUT', route('api.authors.update', ['id' => 5]));

        $response
            ->assertStatus(422);
    }

    public function testUpdateAuthorWithoutName()
    {
        $response = $this
            ->withHeaders([
                'Authorization' => 'Bearer ' . static::$adminToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->json('PUT', route('api.authors.update', ['id' => 5]));

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => [
                    'name'
                ]
            ]);
    }

    public function testUpdateAuthorWithDuplicateName()
    {
        $author = Author::find(1);

        $response = $this
            ->withHeaders([
                'Authorization' => 'Bearer ' . static::$adminToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->json('PUT', route('api.authors.update', ['id' => 5]), [
                'name' => $author->name
            ]);

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => [
                    'name'
                ]
            ]);
    }

    public function testUpdateAuthorWithUnchangedName()
    {
        $author = Author::find(1);

        $response = $this
            ->withHeaders([
                'Authorization' => 'Bearer ' . static::$adminToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->json('PUT', route('api.authors.update', ['id' => 1]), [
                'name' => $author->name
            ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'author' => [
                        'name',
                        'id'
                    ]
                ]
            ])
            ->assertJson([
                'data' => [
                    'author' => [
                        'name' => $author->name
                    ]
                ]
            ]);
    }

    public function testUpdateAuthorWithUniqueName()
    {
        $uniqueName = 'Some really unique name';

        $response = $this
            ->withHeaders([
                'Authorization' => 'Bearer ' . static::$adminToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->json('PUT', route('api.authors.update', ['id' => 1]), [
                'name' => $uniqueName
            ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'author' => [
                        'name',
                        'id'
                    ]
                ]
            ])
            ->assertJson([
                'data' => [
                    'author' => [
                        'name' => $uniqueName
                    ]
                ]
            ]);
    }

    public function testUnauthorizedUserCanNotDeleteAuthor()
    {
        $response = $this
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->json('DELETE', route('api.authors.destroy', ['id' => 5]));

        $response
            ->assertStatus(401);
    }

    public function testNotAdminUserCanNotDeleteAuthor()
    {
        $response = $this
            ->withHeaders([
                'Authorization' => 'Bearer ' . static::$userToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->json('DELETE', route('api.authors.destroy', ['id' => 5]));

        $response
            ->assertStatus(403);
    }

    public function testAdminUserCanDeleteAuthor()
    {
        $response = $this
            ->withHeaders([
                'Authorization' => 'Bearer ' . static::$adminToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->json('DELETE', route('api.authors.destroy', ['id' => 5]));

        $response
            ->assertStatus(200);
    }
}
