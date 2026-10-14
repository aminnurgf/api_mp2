<?php

namespace Tests;

use App\Models\Admin;
use Laravel\Lumen\Testing\DatabaseMigrations;

/**
 * Test otomatis untuk API admin.
 * Berjalan di database SQLite in-memory (lihat phpunit.xml),
 * jadi data di MySQL tidak tersentuh.
 *
 * Jalankan: vendor\bin\phpunit
 */
class AdminApiTest extends TestCase
{
    use DatabaseMigrations;

    public function test_create_admin()
    {
        $this->post('/api/admin', ['username' => 'budi', 'password' => 'rahasia123'])
            ->seeStatusCode(201)
            ->seeJson(['success' => true, 'username' => 'budi']);

        $this->seeInDatabase('admin', ['username' => 'budi']);

        // password harus tersimpan dalam bentuk hash & tidak muncul di response
        $this->assertNotEquals('rahasia123', Admin::first()->password);
        $this->assertArrayNotHasKey('password', $this->response->json('data'));
    }

    public function test_create_admin_validation_error()
    {
        $this->post('/api/admin', ['username' => 'ab', 'password' => '123'])
            ->seeStatusCode(422)
            ->seeJsonStructure(['success', 'message', 'errors' => ['username', 'password']]);
    }

    public function test_username_must_be_unique()
    {
        Admin::create(['username' => 'budi', 'password' => 'rahasia123']);

        $this->post('/api/admin', ['username' => 'budi', 'password' => 'rahasia123'])
            ->seeStatusCode(422);
    }

    public function test_list_admin()
    {
        Admin::create(['username' => 'budi', 'password' => 'rahasia123']);
        Admin::create(['username' => 'siti', 'password' => 'rahasia123']);

        $this->get('/api/admin')->seeStatusCode(200);
        $this->assertCount(2, $this->response->json('data'));

        $this->get('/api/admin?search=sit')->seeStatusCode(200);
        $this->assertCount(1, $this->response->json('data'));
    }

    public function test_show_admin()
    {
        $admin = Admin::create(['username' => 'budi', 'password' => 'rahasia123']);

        $this->get("/api/admin/{$admin->id}")
            ->seeStatusCode(200)
            ->seeJson(['id' => $admin->id, 'username' => 'budi']);
    }

    public function test_show_admin_not_found()
    {
        $this->get('/api/admin/999')
            ->seeStatusCode(404)
            ->seeJson(['success' => false]);
    }

    public function test_update_admin()
    {
        $admin = Admin::create(['username' => 'budi', 'password' => 'rahasia123']);

        $this->put("/api/admin/{$admin->id}", ['username' => 'budi_baru'])
            ->seeStatusCode(200)
            ->seeJson(['username' => 'budi_baru']);

        $this->seeInDatabase('admin', ['id' => $admin->id, 'username' => 'budi_baru']);
    }

    public function test_delete_admin()
    {
        $admin = Admin::create(['username' => 'budi', 'password' => 'rahasia123']);

        $this->delete("/api/admin/{$admin->id}")->seeStatusCode(200);

        $this->notSeeInDatabase('admin', ['id' => $admin->id]);
    }

    public function test_login_success()
    {
        Admin::create(['username' => 'admin', 'password' => 'admin123']);

        $this->post('/api/login', ['username' => 'admin', 'password' => 'admin123'])
            ->seeStatusCode(200)
            ->seeJson(['success' => true, 'username' => 'admin']);
    }

    public function test_login_wrong_password()
    {
        Admin::create(['username' => 'admin', 'password' => 'admin123']);

        $this->post('/api/login', ['username' => 'admin', 'password' => 'salah'])
            ->seeStatusCode(401)
            ->seeJson(['success' => false]);
    }
}

