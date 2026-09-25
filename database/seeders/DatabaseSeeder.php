<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Cargar datos iniciales del sistema (Roles, Permisos, Configuración, Umbrales, etc.)
        $this->call([
            InitialDataSeeder::class,
        ]);

        // 2. Obtener IDs de Roles creados por InitialDataSeeder
        $adminRoleId = DB::table('roles')->where('code', 'ADMIN')->value('id') ?? 1;
        $sstRoleId   = DB::table('roles')->where('code', 'SST')->value('id')   ?? 2;
        $instRoleId  = DB::table('roles')->where('code', 'INSTRUCTOR')->value('id') ?? 3;

        // Contraseña común para los usuarios de pruebas
        $passwordHash = Hash::make('Airsense2026');

        // 3. Usuarios iniciales con todos los campos requeridos
        $users = [
            // ADMIN
            [
                'role_id' => $adminRoleId,
                'name' => 'Diego Mendez (Coordinador)',
                'email' => 'ing.diego.mendez@gmail.com',
                'password' => $passwordHash,
                'is_active' => true,
                'email_verified_at' => now(),
                'last_login_at' => null,
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // EHS / SST
            [
                'role_id' => $sstRoleId,
                'name' => 'Sonia Carolina',
                'email' => 'soncarolinaehs@airsense.cefa.co',
                'password' => $passwordHash,
                'is_active' => true,
                'email_verified_at' => now(),
                'last_login_at' => null,
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // INSTRUCTORES
            [
                'role_id' => $instRoleId,
                'name' => 'Ruben Dario',
                'email' => 'rubennet85@hotmail.com',
                'password' => $passwordHash,
                'is_active' => true,
                'email_verified_at' => now(),
                'last_login_at' => null,
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => $instRoleId,
                'name' => 'Martha Rivera',
                'email' => 'riveramarthika@gmail.com',
                'password' => $passwordHash,
                'is_active' => true,
                'email_verified_at' => now(),
                'last_login_at' => null,
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => $instRoleId,
                'name' => 'Andrea Casaab',
                'email' => 'casaab86@gmail.com',
                'password' => $passwordHash,
                'is_active' => true,
                'email_verified_at' => now(),
                'last_login_at' => null,
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => $instRoleId,
                'name' => 'Felipe Martinez',
                'email' => 'felipe55065@hotmail.com',
                'password' => $passwordHash,
                'is_active' => true,
                'email_verified_at' => now(),
                'last_login_at' => null,
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insertar o actualizar usuarios asegurando la integridad de datos
        foreach ($users as $user) {
            DB::table('users')->updateOrInsert(
                ['email' => $user['email']],
                $user
            );
        }
    }
}

