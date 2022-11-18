<?php

namespace Tests\Acceptance;

use App\Models\User\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Str;
use Tests\FrameworkTest;

class UserControllerTest extends FrameworkTest
{
    /** @var UserRepository */
    private $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = app(UserRepository::class);
    }

    public function testShowRequestReturnsUserData()
    {
        /** @var User $user */
        $user   = $this->userFactory->create();
        $result = $this->get("/api/users/$user->id");
        $result->assertSuccessful();
        $this->assertEquals(
            [
                'id'    => $user->id,
                'name'  => $user->name,
                'nickname'  => $user->nickname,
                'email' => $user->email,
            ],
            json_decode($result->getContent(), true)
        );
    }

    public function testUpdateRequestUpdatesUserData()
    {
        /** @var User $user */
        $user   = $this->userFactory->create();
        $data = [
            'id'    => $user->id,
            'name'  => $this->faker->name,
            'nickname'  => $this->faker->userName,
            'email' => $user->email,
        ];
        $result = $this->put("/api/users/$user->id", $data);
        $result->assertSuccessful();
        $this->assertEquals($data, json_decode($result->getContent(), true));
    }

    public function testUpdateRequestUpdatesUserNickname()
    {
        /** @var User $user */
        $user = $this->userFactory->create();
        $maxLength = config('validation.user.nickname.max');
        $minLength = config('validation.user.nickname.min');

        $data = [
            'id'        => $user->id,
            'name'      => $user->name,
            'nickname'  => $this->faker->realTextBetween($minLength, $maxLength),
            'email'     => $user->email,
        ];
        $result = $this->putJson("/api/users/$user->id", $data);
        $result->assertSuccessful();
        $this->assertEquals($data['nickname'], $result->json('nickname'));
        $this->assertTrue($this->repository->getModel()->newQuery()->where('nickname', $data['nickname'])->exists());
    }

    public function testUpdateRequestFailsNicknameNotUnique()
    {
        /** @var User $user1 */
        $user1 = $this->userFactory->create();

        /** @var User $user2 */
        $user2 = $this->userFactory->create();
        $data = [
            'id'        => $user2->id,
            'name'      => $user2->name,
            'nickname'  => $user1->nickname,
            'email'     => $user2->email,
        ];
        $result = $this->putJson("/api/users/$user2->id", $data);
        $result->assertJsonValidationErrorFor('nickname');
    }

    public function testUpdateRequestFailsNicknameLength()
    {
        /** @var User $user */
        $user = $this->userFactory->create();
        $maxLength = config('validation.user.nickname.max');
        $minLength = config('validation.user.nickname.min');

        $data = [
            'id'        => $user->id,
            'name'      => $user->name,
            'nickname'  => $this->faker->realTextBetween($maxLength + 1, $maxLength + 255),
            'email'     => $user->email,
        ];
        $result = $this->putJson("/api/users/$user->id", $data);
        $result->assertJsonValidationErrorFor('nickname');

        $data['nickname'] = Str::random($maxLength + 1);
        $result = $this->putJson("/api/users/$user->id", $data);
        $result->assertJsonValidationErrorFor('nickname');

        $data['nickname'] = Str::random($minLength - 1);
        $result = $this->putJson("/api/users/$user->id", $data);
        $result->assertJsonValidationErrorFor('nickname');
    }

    public function testCreateRequestCreatesUser()
    {
        $data = [
            'name'     => $this->faker->name,
            'nickname' => $nickname = $this->faker->userName,
            'email'    => $email = $this->faker->unique()->email,
            'password' => 'hen rooster chicken duck',
        ];
        $this->assertFalse($this->repository->getModel()->newQuery()->where('email', $email)->exists());
        $this->assertFalse($this->repository->getModel()->newQuery()->where('nickname', $nickname)->exists());
        $result = $this->post("/api/users", $data);
        $result->assertSuccessful();
        $this->assertTrue($this->repository->getModel()->newQuery()->where('email', $email)->exists());
        $this->assertTrue($this->repository->getModel()->newQuery()->where('nickname', $nickname)->exists());
    }

    public function testCreateRequestFailsNicknameNotUnique()
    {
        /** @var User $user */
        $user = $this->userFactory->create();

        $data = [
            'name'      => $this->faker->name,
            'nickname'  => $user->nickname,
            'email'     => $this->faker->unique()->email,
            'password'  => 'StrongPassword',
        ];
        $result = $this->postJson("/api/users", $data);
        $result->assertJsonValidationErrorFor('nickname');
    }

    public function testCreateRequestFailsNicknameLength()
    {
        $maxLength = config('validation.user.nickname.max');
        $minLength = config('validation.user.nickname.min');

        $data = [
            'name'      => $this->faker->name,
            'nickname'  => $this->faker->realTextBetween($maxLength + 1, $maxLength + 255),
            'email'     => $this->faker->unique()->email,
            'password'  => 'StrongPassword',
        ];
        $result = $this->postJson("/api/users", $data);
        $result->assertJsonValidationErrorFor('nickname');

        $data['nickname'] = Str::random($maxLength + 1);
        $result = $this->postJson("/api/users", $data);
        $result->assertJsonValidationErrorFor('nickname');

        $data['nickname'] = Str::random($minLength - 1);
        $result = $this->postJson("/api/users", $data);
        $result->assertJsonValidationErrorFor('nickname');
    }
}
