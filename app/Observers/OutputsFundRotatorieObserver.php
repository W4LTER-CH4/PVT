<?php

namespace App\Observers;
use App\OutputsFundRotatorie;
use App\Helpers\Util;
use App\User;

class OutputsFundRotatorieObserver
{
    /**
    * Handle the contract "created" event.
    *
    * @param  \App\OutputsFundRotatorie  $contract
    * @return void
    */
    public function created(OutputsFundRotatorie $object)
    {
        Util::save_record($object, 'datos-de-un-tramite', 'registró fondo rotatorio : '. $object->code);
    }
    /**
    * Handle the OutputsFundRotatorie "updating" event.
    *
    * @param  \App\OutputsFundRotatorie  $OutputsFundRotatorie
    * @return void
    */
    public function updating(OutputsFundRotatorie $object)
    {
        Util::save_record($object, 'datos-de-un-tramite', Util::concat_action($object));
    }
    /**
    * Handle the OutputsFundRotatorie "deleted" event.
    *
    * @param  \App\OutputsFundRotatorie  $OutputsFundRotatorie
    * @return void
    */
    public function deleted(OutputsFundRotatorie $object)
    {
        Util::save_record($object, 'datos-de-un-tramite', 'eliminó registro de fondo rotatorio: ' . $object->code);
    }

    public function pivotUpdating(OutputsFundRotatorie $object, $relationName, $pivotIds, $pivotIdsAttributes)
    {
        Util::save_record($object, 'datos-de-un-tramite', Util::relation_action($object, $relationName, $pivotIds, $pivotIdsAttributes, 'modificó'));
    }

    /**
     * Handle the aid contribution "force deleted" event.
     *
     * @param  \App\OutputsFundRotatorie  $OutputsFundRotatorie
     * @return void
    */
    public function forceDeleted(OutputsFundRotatorie $object)
    {
       // Util::save_record($object, 'datos-de-un-tramite', 'rehízo el registro de fondo rotatorio: ' . $object->code);
    }
}
