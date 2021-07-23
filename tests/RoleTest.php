<?php

namespace Programic\Permission\Test;

class RoleTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_has_user_models_of_the_right_class()
    {
        $this->testUser->assignRole($this->testRole);

        $this->assertCount(1, $this->testRole->users);
        $this->assertTrue($this->testRole->users->first()->is($this->testUser));
        $this->assertInstanceOf(User::class, $this->testRole->users->first());
    }

    /** @test */
    public function it_can_be_given_a_permission()
    {
        $this->testRole->givePermission('edit-articles');

        $this->assertCount(1, $this->testRole->permissions);
    }

    /** @test */
    public function it_can_be_given_multiple_permissions_using_an_array()
    {
        $this->testRole->givePermission(['edit-articles', 'edit-news']);

        $this->assertTrue($this->testRole->hasPermission('edit-articles'));
        $this->assertTrue($this->testRole->hasPermission('edit-news'));
    }

    /** @test */
    public function it_can_revoke_a_permission()
    {
        $this->testRole->givePermission('edit-articles');

        $this->assertTrue($this->testRole->hasPermission('edit-articles'));

        $this->testRole->revokePermission('edit-articles');

        $this->testRole = $this->testRole->fresh();

        $this->assertFalse($this->testRole->hasPermission('edit-articles'));
    }
}
