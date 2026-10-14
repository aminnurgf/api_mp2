<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * GET /api/admin
     * Daftar semua admin (opsional: ?search=keyword).
     */
    public function index(Request $request)
    {
        $query = Admin::query()->orderBy('id');

        if ($search = $request->query('search')) {
            $query->where('username', 'like', "%{$search}%");
        }

        return $this->success($query->get(), 'Daftar admin');
    }

    /**
     * GET /api/admin/{id}
     */
    public function show($id)
    {
        $admin = Admin::find($id);

        if (!$admin) {
            return $this->error('Admin tidak ditemukan', 404);
        }

        return $this->success($admin, 'Detail admin');
    }

    /**
     * POST /api/admin
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'username' => 'required|string|min:3|max:255|unique:admin,username',
            'password' => 'required|string|min:6',
        ]);

        $admin = Admin::create($data);

        return $this->success($admin, 'Admin berhasil dibuat', 201);
    }

    /**
     * PUT /api/admin/{id}
     * Field bersifat opsional, hanya field yang dikirim yang diubah.
     */
    public function update(Request $request, $id)
    {
        $admin = Admin::find($id);

        if (!$admin) {
            return $this->error('Admin tidak ditemukan', 404);
        }

        $data = $this->validate($request, [
            'username' => 'sometimes|required|string|min:3|max:255|unique:admin,username,' . $admin->id,
            'password' => 'sometimes|required|string|min:6',
        ]);

        $admin->update($data);

        return $this->success($admin, 'Admin berhasil diperbarui');
    }

    /**
     * DELETE /api/admin/{id}
     */
    public function destroy($id)
    {
        $admin = Admin::find($id);

        if (!$admin) {
            return $this->error('Admin tidak ditemukan', 404);
        }

        $admin->delete();

        return $this->success(null, 'Admin berhasil dihapus');
    }
}

