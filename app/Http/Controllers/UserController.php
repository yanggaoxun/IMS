<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * 用户列表（搜索 + 分页）
     */
    public function index(Request $request)
    {
        $users = User::query()
            ->with('roles:id,name')
            ->when($request->input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Users/Index', [
            'users' => $users,
            'roles' => Role::orderBy('id')->get(['id', 'name']),
            'filters' => $request->only('search'),
        ]);
    }

    /**
     * 新建用户
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'role' => ['nullable', 'string', Rule::exists('roles', 'name')],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);
        $user->syncRoles($validated['role'] ?? 'user');

        return back()->with('success', true);
    }

    /**
     * 更新用户（密码留空则不修改）
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'role' => ['nullable', 'string', Rule::exists('roles', 'name')],
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        // 禁止摘掉自己的 admin 角色，防止系统失去管理员
        if (array_key_exists('role', $validated) && ! ($user->id === $request->user()->id)) {
            $user->syncRoles($validated['role'] ? [$validated['role']] : []);
        }

        return back()->with('success', true);
    }

    /**
     * 删除用户（禁止删除自己）
     */
    public function destroy(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return back()->withErrors(['user' => 'cannot_delete_self']);
        }

        $user->delete();

        return back()->with('success', true);
    }
}
