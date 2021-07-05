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
        $receipt_permissions = ['update-affiliate-secondary', 'show-affiliate', 'show-all-loan', 'show-loan', 'create-loan', 'create-address', 'update-address', 'delete-address', 'update-loan', 'delete-loan', 'print-contract-loan', 'show-deleted-loan','update-note','delete-note','update-documents-requirements','update-loan-calculations','update-reference-cosigner','update-warranty-hipotecary','show-history-loan','show-all-payment-loan'];
        $sequence_permissions = ['update-affiliate-secondary', 'show-affiliate', 'show-loan', 'update-address', 'update-loan','show-history-loan'];
        $leadership_permissions = ['show-all-loan', 'update-loan', 'delete-loan', 'show-setting', 'show-deleted-loan','update-values-commercial-rescue'];
        $executive_permissions = ['update-setting'];
        $reports_permissions = ['show-list-loans-generated'];
        $permissions_primary = ['update-affiliate-primary'];
        $file = ['show-all-loan','show-loan','update-loan','show-affiliate','show-history-loan'];
        $accounting = ['show-all-loan','show-loan','update-loan','update-accounting-voucher'];
        $budget = ['show-all-loan','show-loan','update-loan','update-accounting-voucher','show-history-loan'];
        $collection_court = ['show-all-loan','show-loan','update-loan','update-refinancing-balance','show-history-loan'];
        $pay_permissions_treasury = ['show-list-voucher','show-affiliate','show-loan','show-all-loan','print-payment-kardex-loan','print-payment-loan','print-payment-plan','delete-payment-loan','update-payment','create-payment','show-payment','show-payment-loan','delete-payment', 'print-payment-voucher', 'update-payment-loan'];
        $treasury_permissions = ['print-payment-plan', 'print-payment-kardex-loan', 'show-loan','disbursement-loan','delete-payment', 'update-loan', 'show-fund-rotatory'];
        $loan_collection = ['show-all-loan','show-affiliate','show-report-payment','print-payment-plan', 'print-payment-kardex-loan', 'show-payment-loan', 'create-payment-loan', 'update-payment-loan', 'delete-payment-loan', 'print-payment-loan','update-loan','show-list-payments-generated'];
        $legal_permissions = ['registration-delivery-return-contracts','update-documents-requirements', 'print-contract-loan'];
        $calification_permissions = ['update-loan-calculations','print-qualification-form','update-reference-cosigner'];
        $reports_supervition = ['show-report-payment','show-all-loan'];
        $sequence_roles = [
            [ 
                'name' => 'Plataforma',
                'action' => 'Recepcionado',             
            ], [ 
                'name' => 'Calificación',
                'action' => 'Calificado',
            ], [
                'name' => 'Aprobación Jefatura',
                'action' => 'Aprobado',
            ], [
                'name' => 'Autorización Dirección',
                'action' => 'Aprobado',
            ], [
                'name' => 'Revisión Legal',
                'action' => 'Revisado',
            ], [
                'name' => 'Revisión Jefatura',
                'action' => 'Aprobado',
            ], [
                'name' => 'Revisión Dirección',
                'action' => 'Aprobado',
            ], [
                'name' => 'Aprobación Legal',
                'action' => 'Aprobado',
            ], [
                'name' => 'Revisión Calificación',
                'action' => 'Aprobado',
            ], [
                'name' => 'Presupuesto',
                'action' => 'Presupuestado',
            ], [
                'name' => 'Contabilidad',
                'action' => 'Aprobado',
            ], [
                'name' => 'Tesorería',
                'action' => 'Desembolsado',
            ], [
                'name' => 'Cobranzas Corte',
                'action' => 'Pendiente de Pago',
            ]
        ];    $recovery_roles = [
    
            [
                'name' => 'Cobranzas',
                'action' => 'Liquidado',
            ], [
                'name' => 'Tesorería Cobros',
                'action' => 'Pago Confirmado',
            ], [
                'name' => 'Archivo',
                'action' => 'archivado',
            ], [
                'name' => 'Supervisor',
                'action' => 'Supervidor de tramites',
            ],
        ];
      
        if ($module) {
            foreach ($sequence_roles as $role) {
                $role = Role::firstOrCreate([
                    'name' => $module->shortened . '-' . Str::slug($role['name'], '-')
                ], [
                    'display_name' => $role['name'],
                    'action' => $role['action'],
                    'module_id' => $module->id,
                ]);
                if (in_array($role['display_name'], ['Revisión Jefatura','Aprobación Jefatura'])) {
                    $role->syncPermissions(array_merge($sequence_permissions, $leadership_permissions, $permissions_primary,$reports_permissions));
                }  elseif (in_array($role['display_name'], ['Plataforma'])) {
                    $role->syncPermissions(array_merge($receipt_permissions));
                } elseif (in_array($role['display_name'], ['Autorización Dirección', 'Revisión Dirección'])) {
                    $role->syncPermissions(array_merge($sequence_permissions, $leadership_permissions, $executive_permissions, $permissions_primary,$reports_permissions));
                } elseif (in_array($role['display_name'], ['Presupuesto'])) {
                    $role->syncPermissions(array_merge($budget,$reports_permissions));
                } elseif (in_array($role['display_name'], ['Contabilidad'])) {
                    $role->syncPermissions(array_merge($sequence_permissions,$reports_permissions));
                } elseif (in_array($role['display_name'], ['Cobranzas Corte'])) {
                    $role->syncPermissions(array_merge($collection_court,$reports_permissions));
                } elseif (in_array($role['display_name'], ['Tesorería'])) {
                    $role->syncPermissions(array_merge($treasury_permissions,$reports_permissions));
                }elseif (in_array($role['display_name'], ['Revisión Legal','Aprobación Legal'])) {
                    $role->syncPermissions(array_merge($sequence_permissions,$legal_permissions,$reports_permissions));
                }elseif (in_array($role['display_name'], ['Calificación','Revisión Calificación'])) {
                    $role->syncPermissions(array_merge($sequence_permissions,$calification_permissions,$reports_permissions));
                }elseif (in_array($role['display_name'], ['Archivo'])) {
                    $role->syncPermissions(array_merge($file,$reports_permissions));
                }elseif (in_array($role['display_name'], ['Supervisor'])) {
                    $role->syncPermissions(array_merge($reports_supervition));
                }else {
                    $role->syncPermissions($sequence_permissions,$reports_permissions);
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
                    $role->syncPermissions(array_merge($loan_collection,$reports_permissions));
                }
            }
        }
    }
}