<?php

use Illuminate\Database\Seeder;
use App\LoanGlobalParameter;

class LoanGlobalParameterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $global_parameters = [
            ['offset_ballot_day' => 7,
            'offset_interest_day' => 16,
            'livelihood_amount' => 510,
            'min_service_years' =>1,
            'min_service_years_adm' =>2,
            'max_guarantor_active' =>3,
            'max_guarantor_passive' =>2,
            'date_delete_payment'=>1,
            'max_loans_active' => 2,
            'max_loans_process' => 1,
            'days_current_interest' => 31,
            'grace_period' => 3,
            'consecutive_manual_payment' => 3,
            'max_months_go_back' => 10,
            'min_percentage_paid' => 25,
            'min_remaining_installments' => 3,
            'min_amount_fund_rotary' =>10000
            ]
        ];
        foreach ($global_parameters as $global_parameter) {
            LoanGlobalParameter::firstOrCreate($global_parameter);
        }
    }
}
