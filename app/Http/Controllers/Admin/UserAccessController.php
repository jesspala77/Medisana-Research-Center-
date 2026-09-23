<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\LeadModuleService;
use Illuminate\Http\Request;

class UserAccessController extends Controller
{
    public function index(LeadModuleService $moduleService)
    {
        $modules = $moduleService->accessModules();

        return view('admin.user-access', [
            'users' => User::orderBy('name')->orderBy('email')->get(),
            'modules' => $modules,
            'moduleKeys' => array_keys($modules),
        ]);
    }

    public function update(Request $request, User $user, LeadModuleService $moduleService)
    {
        $allowedModules = array_keys($moduleService->accessModules());

        $validated = $request->validate([
            'is_platform_admin' => 'nullable|boolean',
            'organization_key' => 'nullable|string|max:255',
            'module_access' => 'nullable|array',
            'module_access.*' => 'string|in:'.implode(',', $allowedModules),
        ]);

        $user->update([
            'is_platform_admin' => (bool) ($validated['is_platform_admin'] ?? false),
            'organization_key' => $validated['organization_key'] ?: null,
            'module_access' => array_values($validated['module_access'] ?? []),
        ]);

        return back()->with('success', 'User access updated for '.$user->email);
    }
}
