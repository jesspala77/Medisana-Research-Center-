<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(SynergiaSeeder::class);

        User::updateOrCreate(
            ['email' => 'admin@synnexus.local'],
            [
                'name' => 'SynNexus Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_platform_admin' => true,
                'organization_key' => 'global-synergia-group',
                'module_access' => ['remodeling', 'capital-funding', 'bond-agency', 'clinical-recruitment', 'cnc-quote'],
            ]
        );

        $jessica = User::firstOrNew(['email' => 'jess@globalsynergiagroup.com']);
        $jessica->forceFill([
            'name' => 'Jessica M Palacio',
            'email_verified_at' => $jessica->email_verified_at ?: now(),
            'is_platform_admin' => true,
            'organization_key' => 'global-synergia-group',
            'module_access' => ['remodeling', 'capital-funding', 'bond-agency', 'clinical-recruitment', 'cnc-quote'],
        ]);

        if (! $jessica->exists) {
            $jessica->password = Hash::make('password');
        }

        $jessica->save();

        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_platform_admin' => true,
                'organization_key' => 'global-synergia-group',
                'module_access' => ['remodeling', 'capital-funding', 'bond-agency', 'clinical-recruitment', 'cnc-quote'],
            ]
        );

        User::updateOrCreate(
            ['email' => 'construction@synnexus.local'],
            [
                'name' => 'Construction User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_platform_admin' => false,
                'organization_key' => 'k-and-g-art-designs',
                'module_access' => ['remodeling', 'cnc-quote'],
            ]
        );

        User::updateOrCreate(
            ['email' => 'funding@synnexus.local'],
            [
                'name' => 'Funding User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_platform_admin' => false,
                'organization_key' => 'funding-clients',
                'module_access' => ['capital-funding'],
            ]
        );

        User::updateOrCreate(
            ['email' => 'clinical@synnexus.local'],
            [
                'name' => 'Clinical Research User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_platform_admin' => false,
                'organization_key' => 'research-clients',
                'module_access' => ['clinical-recruitment'],
            ]
        );

        User::updateOrCreate(
            ['email' => 'bond@synnexus.local'],
            [
                'name' => 'Bond Agency User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_platform_admin' => false,
                'organization_key' => 'bail-bond-clients',
                'module_access' => ['bond-agency'],
            ]
        );
    }
}
