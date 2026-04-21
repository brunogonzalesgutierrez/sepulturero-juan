<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Empleado;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $empleado = Empleado::firstOrCreate(
            ['ci' => '00000001'],
            [
                'nombre'  => 'Administrador',
                'paterno' => 'Sistema',
                'materno' => '',
                'cargo'   => 'Administrador',
                'estado'  => 'activo',
            ]
        );

        $user = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'empleado_id' => $empleado->id,
                'email'       => 'admin@sepulturero.com',
                'password'    => bcrypt('Admin1234!'),
                'estado'      => 'activo',
            ]
        );

        $user->assignRole('Administrador');
    }
}
