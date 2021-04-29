<?php

namespace App\Observers;


use App\Sismu;
use App\Loan;
use App\Helpers\Util;

class SismuObserver
{
    /**
     * Handle the sismu "created" event.
     *
     * @param  \App\Sismu  $sismu
     * @return void
     */
    public function created(Sismu $object)
    {
        $loan=Loan::find($object->loan_id);
        Util::save_record($object, 'datos-de-un-tramite', 'registró datos de prestamo proviniente sismu: '. $object->code);
        Util::save_record($loan, 'datos-de-un-tramite', 'registró datos de un préstamo SISMU '.'con código '. $loan->data_loan->code.' para realizar: '.$loan->parent_reason);
    }

    /**
     * Handle the sismu "updated" event.
     *
     * @param  \App\Sismu  $sismu
     * @return void
     */
    public function updating(Sismu $object)
    {
        $loan=Loan::find($object->loan_id);
        Util::save_record($object, 'datos-de-un-tramite', 'actualizó datos de prestamo proviniente sismu: '. $object->code);
        Util::save_record($loan, 'datos-de-un-tramite', 'actualizó datos de un préstamo SISMU '.'con código '. $loan->data_loan->code);
    }

    /**
     * Handle the sismu "deleted" event.
     *
     * @param  \App\Sismu  $sismu
     * @return void
     */
    public function deleted(Sismu $object)
    {
        $loan=Loan::find($object->loan_id);
        Util::save_record($object, 'datos-de-un-tramite', 'eliminó datos de prestamo proviniente sismu: '. $object->code);
       // Util::save_record($loan, 'datos-de-un-tramite', 'eliminó datos de un préstamo SISMU '.'con código '. $loan->data_loan->code);
    }

    /**
     * Handle the sismu "restored" event.
     *
     * @param  \App\Sismu  $sismu
     * @return void
     */
    public function restored(Sismu $object)
    {
        //
    }

    /**
     * Handle the sismu "force deleted" event.
     *
     * @param  \App\Sismu  $sismu
     * @return void
     */
    public function forceDeleted(Sismu $object)
    {
        //
    }
}
