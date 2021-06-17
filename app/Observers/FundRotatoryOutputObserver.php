<?php

namespace App\Observers;
use App\FundRotatoryOutput;
use App\Helpers\Util;
use App\User;

class FundRotatoryOutputObserver
{
    /**
    * Handle the contract "created" event.
    *
    * @param  \App\FundRotatoryOutput  $contract
    * @return void
    */
    public function created(FundRotatoryOutput $object)
    {
        Util::save_record($object, 'datos-de-un-tramite', 'registró fondo rotatorio : '. $object->code);
    }
    /**
    * Handle the FundRotatoryOutput "updating" event.
    *
    * @param  \App\FundRotatoryOutput  $FundRotatoryOutput
    * @return void
    */
    public function updating(FundRotatoryOutput $object)
    {
        Util::save_record($object, 'datos-de-un-tramite', Util::concat_action($object));
    }
    /**
    * Handle the FundRotatoryOutput "deleted" event.
    *
    * @param  \App\FundRotatoryOutput  $FundRotatoryOutput
    * @return void
    */
    public function deleted(FundRotatoryOutput $object)
    {
        Util::save_record($object, 'datos-de-un-tramite', 'eliminó registro de fondo rotatorio: ' . $object->code);
    }

    public function pivotUpdating(FundRotatoryOutput $object, $relationName, $pivotIds, $pivotIdsAttributes)
    {
        Util::save_record($object, 'datos-de-un-tramite', Util::relation_action($object, $relationName, $pivotIds, $pivotIdsAttributes, 'modificó'));
    }

    /**
     * Handle the aid contribution "force deleted" event.
     *
     * @param  \App\FundRotatoryOutput  $FundRotatoryOutput
     * @return void
    */
    public function forceDeleted(FundRotatoryOutput $object)
    {
       // Util::save_record($object, 'datos-de-un-tramite', 'rehízo el registro de fondo rotatorio: ' . $object->code);
    }
}
