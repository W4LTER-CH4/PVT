<?php

use Illuminate\Database\Seeder;
use App\Permission;

class LoanPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            [
                'name' => 'create-loan',
                'display_name' => 'Crear trámites de préstamo'
            ], [
                'name' => 'update-loan',
                'display_name' => 'Editar trámites de préstamo'
            ], [
                'name' => 'show-loan',
                'display_name' => 'Ver trámites de préstamo para el rol'
            ], [
                'name' => 'show-all-loan',
                'display_name' => 'Ver todos los trámites de préstamo'
            ], [
                'name' => 'delete-loan',
                'display_name' => 'Anular trámites de préstamo'
            ], [
                'name' => 'print-contract-loan',
                'display_name' => 'Imprimir contrato de préstamo'
            ], [
                'name' => 'show-deleted-loan',
                'display_name' => 'Ver trámites anulados de préstamo'
            ], [
                'name' => 'print-payment-plan',
                'display_name' => 'Imprimir plan de pagos'
            ],  [
                'name' => 'print-payment-kardex-loan',
                'display_name' => 'Imprimir kardex de pagos de préstamo'
            ],  [
                'name' => 'create-payment-loan',
                'display_name' => 'Crear tramites de cobranzas'
            ],  [
                'name' => 'update-payment-loan',
                'display_name' => 'Editar tramites de cobranzas'
            ],  [
                'name' => 'show-payment-loan',
                'display_name' => 'Ver trámites de cobranzas para el rol'
            ],  [
                'name' => 'delete-payment-loan',
                'display_name' => 'Anular trámites de cobranzas'
            ],  [
                'name' => 'show-all-payment-loan',
                'display_name' => 'Ver todos los trámites de cobranzas'
            ],  [
                'name' => 'print-payment-loan',
                'display_name' => 'Imprimir registro de pago de cobranzas'
            ],  [
                'name' => 'show-payment',
                'display_name' => 'Ver cobros de tesorería'
            ],  [
                'name' => 'create-payment',
                'display_name' => 'Crear cobros de tesorería'
            ],  [
                'name' => 'update-payment',
                'display_name' => 'Editar cobros de tesorería'
            ],  [
                'name' => 'delete-payment',
                'display_name' => 'Anular cobros de tesorería'
            ],  [
                'name' => 'disbursement-loan',
                'display_name' => 'Permisos de desembolso de préstamo'
            ],  [
                'name' => 'print-payment-voucher',
                'display_name' => 'Imprimir registro de pago de tesorería'
            ], [
                'name' => 'show-deleted-payment',
                'display_name' => 'Ver trámites anulados de cobros'
            ], [
                'name' => 'validate-submitted-documents',
                'display_name' => 'Validar documentos presentados'
            ], [
                'name' => 'release-loan-user',
                'display_name' => 'Liberar usuario de préstamo'
            ], [
                'name' => 'update-accounting-loan',
                'display_name' => 'Editar tramite en contabilidad'
            ]
        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate($permission);
        }
    }
}