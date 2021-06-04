<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Module;

class ModuleNameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modules = Module::get();
        foreach ($modules as $module)
        {
            $module->update(['name' => Str::slug($module->display_name, '-')]);
            switch($module->name) {
                case 'tecnologia':
                    $module->update(['shortened' => 'TE']);
                    break;
                case 'complemento-economico':
                    $module->update(['shortened' => 'CE']);
                    break;
                case 'fondo-de-retiro':
                    $module->update(['shortened' => 'FR']);
                    break;
                case 'asuntos-financieros':
                    $module->update(['shortened' => 'AF']);
                    break;
                case 'prestamos':
                    $module->update(['shortened' => 'PRE']);
                    break;
                case 'cuota-y-auxilio-mortuorio':
                    $module->update(['shortened' => 'CAM']);
                    break;
                case 'contribuciones':
                    $module->update(['shortened' => 'CON']);
                    break;
            }
        }
    }
}