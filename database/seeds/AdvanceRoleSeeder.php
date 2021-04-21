<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Module;
use App\Role;
use App\RoleSequence;

class AdvanceRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RoleSequence::flushEventListeners();
        Role::flushEventListeners();
        $old_receipt = Role::whereName('PRE-area-de-recepcion')->first();
        if ($old_receipt) {
            $old_receipt->users()->sync([]);
            $old_receipt->permissions()->sync([]);
            $old_receipt->records()->delete();
            $old_receipt->delete();
        }
        $module = Module::whereName('prestamos')->first();
        $receipt_permissions = ['update-affiliate-secondary', 'show-affiliate', 'show-all-loan', 'show-loan', 'create-loan', 'create-address', 'update-address', 'delete-address', 'update-loan', 'delete-loan', 'print-contract-loan', 'show-deleted-loan','update-note','delete-note'];
        $sequence_permissions = ['update-affiliate-secondary', 'show-affiliate', 'show-loan', 'update-address', 'update-loan'];
        $leadership_permissions = ['show-all-loan', 'update-loan', 'delete-loan', 'show-setting', 'show-deleted-loan'];
        $executive_permissions = ['update-setting'];
        $permissions_primary = ['update-affiliate-primary'];
        $accounting = ['show-all-loan','show-loan','update-loan','update-accounting-loan'];
        $budget = ['show-all-loan','show-loan','update-loan','update-accounting-loan'];
        $collection_court = ['show-all-loan','show-loan','update-loan' ];
        $pay_permissions_treasury = ['show-affiliate','show-loan','show-all-loan','print-payment-kardex-loan','print-payment-loan','print-payment-plan','delete-payment-loan','update-payment','create-payment','show-payment','show-payment-loan','delete-payment', 'print-payment-voucher', 'update-payment-loan'];
        $treasury_permissions = ['print-payment-plan', 'print-payment-kardex-loan', 'show-loan','disbursement-loan','delete-payment', 'update-loan'];
        $loan_collection = ['show-all-loan', 'show-loan', 'show-affiliate', 'print-payment-plan', 'print-payment-kardex-loan', 'show-payment-loan', 'create-payment-loan', 'update-payment-loan', 'delete-payment-loan', 'print-payment-loan','update-loan'];
        $receipt_roles = ['Regional Santa Cruz', 'Regional Cochabamba', 'Regional Oruro', 'Regional Potosí', 'Regional Sucre', 'Regional Tarija', 'Regional Trinidad', 'Regional Cobija', 'Recepción'];
        $sequence_roles = [
            [
                'name' => 'Calificación',
                'action' => 'Calificado',
            ], [
                'name' => 'Revisión Legal',
                'action' => 'Revisado',
            ], [
                'name' => 'Jefatura',
                'action' => 'Aprobado',
            ], [
                'name' => 'Aprobación Dirección',
                'action' => 'Aprobado',
            ], [
                'name' => 'Revisión Dirección',
                'action' => 'Aprobado',
            ], [
                'name' => 'Aprobación Legal',
                'action' => 'Aprobado',
            ], [
                'name' => 'Aprobación Calificación',
                'action' => 'Aprobado',
            ], [
                'name' => 'Presupuesto',
                'action' => 'Presupuestado',
            ], [
                'name' => 'Contabilidad',
                'action' => 'Aprobado',
            ],  [
                'name' => 'Tesorería',
                'action' => 'Desembolsado',
            ], [
                'name' => 'Cobranzas Corte',
                'action' => 'Pendiente de Pago',
            ],
        ];    $recovery_roles = [
    
            [
                'name' => 'Cobranzas',
                'action' => 'Liquidado',
            ],
            [
                'name' => 'Tesorería Cobros',
                'action' => 'Pago Confirmado',
            ]
        ];
      
        if ($module) {
            foreach ($receipt_roles as $role) {
                $role = Role::firstOrCreate([
                    'name' => $module->shortened . '-' . Str::slug($role, '-')
                ], [
                    'display_name' => $role,
                    'action' => 'Recepcionado',
                    'module_id' => $module->id,
                ]);
                $role->syncPermissions($receipt_permissions);
            }

            foreach ($sequence_roles as $role) {
                $role = Role::firstOrCreate([
                    'name' => $module->shortened . '-' . Str::slug($role['name'], '-')
                ], [
                    'display_name' => $role['name'],
                    'action' => $role['action'],
                    'module_id' => $module->id,
                ]);
                if (in_array($role['display_name'], ['Jefatura'])) {
                    $role->syncPermissions(array_merge($sequence_permissions, $leadership_permissions, $permissions_primary));
                } elseif (in_array($role['display_name'], ['Aprobación Dirección', 'Revisión Dirección'])) {
                    $role->syncPermissions(array_merge($sequence_permissions, $leadership_permissions, $executive_permissions, $permissions_primary));
                } elseif (in_array($role['display_name'], ['Presupuesto'])) {
                    $role->syncPermissions(array_merge($budget));
                } elseif (in_array($role['display_name'], ['Contabilidad'])) {
                    $role->syncPermissions(array_merge($accounting));
                } elseif (in_array($role['display_name'], ['Cobranzas Corte'])) {
                    $role->syncPermissions(array_merge($collection_court));
                } elseif (in_array($role['display_name'], ['Tesorería'])) {
                    $role->syncPermissions(array_merge($treasury_permissions));
                }
                else {
                    $role->syncPermissions($sequence_permissions);
                }
            }

            foreach ($recovery_roles as $role) {
                $role = Role::firstOrCreate([
                    'name' => $module->shortened . '-' . Str::slug($role['name'], '-')
                ], [
                    'display_name' => $role['name'],
                    'action' => $role['action'],
                    'module_id' => $module->id,
                ]);
                if (in_array($role['display_name'], ['Tesorería Cobros'])) {
                    $role->syncPermissions(array_merge($pay_permissions_treasury));
                } elseif (in_array($role['display_name'], ['Cobranzas'])) {
                    $role->syncPermissions(array_merge($loan_collection));
                }
            }
        }
    }
}