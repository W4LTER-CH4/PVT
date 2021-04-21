<?php

use Illuminate\Database\Seeder;
use App\ProcedureModality;
use App\ProcedureType;
use App\ProcedureDocument;
use App\ProcedureRequirement;
use App\Module;
class LoanStructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $module = Module::whereName('prestamos')->first();
        $data = [
        'procedures' => [
          'anticipo' => [
            'type' => ['module_id' => $module->id,'name'=>'Préstamo Anticipo','second_name'=>'Anticipo'],
            'limits' => ['maximum_amount' => 2000,'minimum_amount' => 200,'maximum_term' => 2,'minimum_term' => 1],
            'modalities' => [
                // ANTICIPO SECTOR ACTIVO
                ['name'=>'Anticipo sector activo','shortened'=>'ANT-SA','requirements'=>[
                  ['name' => 'Cédula de Identidad  del(la) Titular (Original y Fotocopia).','number'=>1],
                  ['name' => 'Última Boleta de Pago (Original y Fotocopia) y/o Certificado de haberes.','number'=>2],
                  ['name' => 'Estado de cuenta original, vigente y emitido por el Banco Unión S.A.','number'=>3],
                  ['name' => 'Formulario SIGEP.','number'=>4],
                  //['name' => 'Certificado de haberes considerando el último mes percibido.','number'=>2],  // En caso de no contar con la boleta de pago
                  //['name' => 'Formulario de Calificación y Aprobación de Préstamos FORM/CAYAP/PTMO/UIP/004.','number'=>4],
                  /*Estos documentos son solicitados por MUSERPOL en caso de ser requeridos*/
                  ['name' => 'Memorándum de alta ( Categoría 0%).','number'=>0],
                  //['name' => 'Solicitud de aclaración de datos personales en la Boleta de Pago.','number'=>0],  // En caso de que el afiliado tenga datos erroneos en su boleta de pago
                  ['name' => 'Certificado de no adeudo, emitido por la instancia correspondiente.','number'=>0],  // en caso de que el afiliado haya tenido deudas de otras entidades (Ej: COMIPOL , COVIPOL)
                  ['name' => 'Certificado de haberes (En caso que el afiliado no cuente con la boleta de pago).','number'=>0],
                  //['name' => 'Certificado de pago emitido por la entidad correspondiente.','number'=>0],  // en caso de que el afiliado tenga deudas con otras entidades (Ej: COMIPOL , COVIPOL)
                  //['name' => 'Conformidad de devolucion de descuento por garantía original o fotocopia legalizada.','number'=>0],

                ],
                'parameters' => [
                    'debt_index' => 90,
                    'quantity_ballots' => 1,
                    'guarantors' => 0,
                    'max_lenders' => 1,
                    'max_cosigner'=>0
                ],
                'interest' => ['annual_interest' => 36,'penal_interest' => 6]
            ],
                    // ANTICIPO SECTOR PASIVO Y VIUDAS
                ['name'=>'Anticipo sector pasivo','shortened'=>'ANT-SP','requirements'=>[
                  ['name' => 'Cédula de Identidad  del(la) Titular (Original y Fotocopia).','number'=>1], 
                  ['name' => 'Última Boleta de Pago (Original y Fotocopia) y/o  Certificado de haberes.','number'=>2], 
                  ['name' => 'Estado de cuenta original, vigente y emitido por el Banco Unión S.A.','number'=>3], // Nuevo
                  ['name' => 'Formulario SIGEP.','number'=>4],
                  ['name' => 'Memorándum de agradecimiento o cualquier otro documento que avale el grado del titular.','number'=>5],
                  //['name' => 'Certificado de haberes considerando el último mes percibido.','number'=>2],  // En caso de no contar con la boleta de pago
                  //['name' => 'Formulario de Calificación y Aprobación de Préstamos FORM/CAYAP/PTMO/UIP/004.','number'=>4],
                  /*Estos documentos son solicitados por MUSERPOL en caso de ser requeridos*/
                  ['name' => 'Certificado de no adeudo, emitido por la instancia correspondiente.','number'=>0],  // en caso de que el afiliado haya tenido deudas de otras entidades (Ej: COMIPOL , COVIPOL)
                  //['name' => 'Certificado de Renta de jubilación o fotocopia legalizada emitida por el servicio Nacional del Sistema de Reparto.','number'=>0],  //En caso de perdida de boleta de pago
                  //['name' => 'Solicitud de aclaración de datos personales en la Boleta de Pago.','number'=>0],  // En caso de que el afiliado tenga datos erroneos en su boleta de pago
                  ['name' => 'Certificado de Aportes Voluntarios.','number'=>0],
                  //['name' => 'Certificado de pago emitido por la entidad correspondiente.','number'=>0],  // en caso de que el afiliado tenga deudas con otras entidades (Ej: COMIPOL , COVIPOL)
                  //['name' => 'Certificado de aportes para el Auxilio Mortuorio de los 3 últimos meses de la unidad de Fondo de Retiro.','number'=>0], // para solicitantes AFPś
                  //['name' => 'Memorándum de agradecimiento de servicios en copia simple emitido por el Comando General de la Policía Boliviana.','number'=>0],  // *caso pasivo con renta en AFP , quien solicita el préstamo
                  //['name' => 'Conformidad de devolución de descuento por garantía original o fotocopia legalizada.','number'=>0],
                ],
                'parameters' => [
                    'debt_index' => 90,
                    'quantity_ballots' => 1,
                    'guarantors' => 0,
                    'max_lenders' => 1,
                    'max_cosigner'=>0
                ],
                'interest' => ['annual_interest' => 36,'penal_interest' => 6]
            ],
            ]
          ],
          'corto' => [
            // CORTO PLAZO
            'type' => ['module_id' => $module->id,'name'=>'Préstamo a corto plazo','second_name'=>'Corto plazo'],
            'limits' => ['maximum_amount' => 25000,'minimum_amount' => 2001,'maximum_term' => 30,'minimum_term' => 3],
            'modalities' => [
              // CORTO PLAZO SECTOR ACTIVO SERVICIO
              ['name'=>'Corto plazo sector activo','shortened'=>'PCP-SA','requirements'=>[
                ['name' => 'Cédula de Identidad  del(la) Titular (Original y Fotocopia).','number'=>1],
                ['name' => 'Tres últimas boletas (Original y Fotocopia) y/o Certificado de haberes.','number'=>2],
               // ['name' => 'Certificado de haberes considerando el último mes percibido.','number'=>2],  // En caso de no contar con la boleta de pago
                ['name' => 'Estado de cuenta original, vigente y emitido por el Banco Unión S.A.','number'=>3],
                ['name' =>'Formulario SIGEP.','number'=>4],  
                //['name' => 'Formulario de Calificación y Aprobación de Préstamos FORM/CAYAP/PTMO/UIP/004.','number'=>4],
                /*Estos documentos son solicitados por MUSERPOL en caso de ser requeridos*/
                ['name' => 'Certificado de años de servicio (Categoría 100%).','number'=>0],
                ['name' => 'Memorándum de alta (Categoría 0%).','number'=>0],
                ['name' => 'Certificado de no adeudo, emitido por la instancia correspondiente.','number'=>0],  // en caso de que el afiliado haya tenido deudas de otras entidades (Ej: COMIPOL , COVIPOL)
                ['name' => 'Certificado de haberes (En caso que el afiliado no cuente con las boletas de pago).','number'=>0],
                //['name' => 'Solicitud de aclaración de datos personales en la Boleta de Pago.','number'=>0],  // En caso de que el afiliado tenga datos erroneos en su boleta de pago
                //['name' => 'Certificado de pago emitido por la entidad correspondiente.','number'=>0],  // en caso de que el afiliado tenga deudas con otras entidades (Ej: COMIPOL , COVIPOL)
                //['name' => 'Conformidad de devolución de descuento por garantía original o fotocopia legalizada.','number'=>0],
              ],
              'parameters' => [
                  'debt_index' => 50,
                  'quantity_ballots' => 3,
                  'guarantors' => 0,
                  'personal_reference' => true,
                  'max_lenders' => 1,
                  'max_cosigner'=>0
              ],
              'interest' => ['annual_interest' => 20,'penal_interest' => 6]
            ],
              // CORTO PLAZO SECTOR ACTIVO DISPONIBILIDAD LETRA, el refinanciamiento de esta sub mod no corresponde según reglamento de prestamos ART 76 I. c) 
              ['name'=>'Corto plazo con disponibilidad de letra','shortened'=>'PCP-DLA','requirements'=>[
                ['name' => 'Cédula de Identidad  del(la) Titular (Original y Fotocopia).','number'=>1],
                ['name' => 'Tres últimas boletas (Original y Fotocopia) y/o Certificado de haberes.','number'=>2], //( 3 boletas)
                ['name' => 'Memorándum de asignación de la Letra (Original y Fotocopia).','number'=>3],
                ['name' => 'Certificado de años de servicio.','number'=>4],
                ['name' => 'Estado de cuenta original, vigente y emitido por el Banco Unión S.A.','number'=>5],
                ['name' => 'Formulario SIGEP.','number'=>6],
                //['name' => 'Certificado de haberes considerando el último mes percibido.','number'=>2],  // En caso de no contar con la boleta de pago
            	  //['name' => 'Memorándum de destino a disponibilidad a la letra "A" en copia simple.','number'=>3], // Nuevo
                //['name' => 'Certificado de años de servicio desglosado en copia simple emitido por el Comando General de la Policía Boliviana.','number'=>5],
                //['name' => 'Certificado de años de servicio desglosado en copia simple emitido por los Comandos Regionales de la Policía Boliviana.','number'=>5],
                //['name' => 'Certificado de calificación de años de servicio CAS emitido por el Ministerio de Economia y Finanzas Publicas','number'=>6],
                //['name' => 'Formulario de Calificación y Aprobación de Préstamos FORM/CAYAP/PTMO/UIP/004.','number'=>7],
                /*Estos documentos son solicitados por MUSERPOL en caso de ser adicionales*/
                ['name' => 'Certificado de no adeudo, emitido por la instancia correspondiente.','number'=>0],
                ['name' => 'Certificado de haberes (En caso que el afiliado no cuente con las boletas de pago).','number'=>0],
                //['name' => 'Solicitud de aclaración de datos personales en la Boleta de Pago.','number'=>0],  // En caso de que el afiliado tenga datos erroneos en su boleta de pago
                //['name' => 'Certificado de no adeudo, emitido por la instancia correspondiente.','number'=>0],  // en caso de que el afiliado haya tenido deudas de otras entidades (Ej: COMIPOL , COVIPOL)
                //['name' => 'Certificado de pago emitido por la entidad correspondiente.','number'=>0],  // en caso de que el afiliado tenga deudas con otras entidades (Ej: COMIPOL , COVIPOL)
                //['name' => 'Conformidad de devolución de descuento por garantía original o fotocopia legalizada.','number'=>0],
              ],
              'parameters' => [
                'debt_index' => 50,
                'quantity_ballots' => 3,
                'guarantors' => 0,
                'personal_reference' => true,
                'max_lenders' => 1,
                'max_cosigner'=>0
              ],
              'interest' => ['annual_interest' => 20,'penal_interest' => 6]
            ],
               // CORTO PLAZO SECTOR PASIVO Y VIUDAS AFP
              ['name'=>'Corto plazo el sector pasivo y viudas AFPs','shortened'=>'PCP-SP-AFP','requirements'=>[
                ['name' => 'Cédula de Identidad  del(la) Titular (Original y Fotocopia).','number'=>1],
                ['name' => 'Tres últimas boletas (Original y Fotocopia) y/o Certificado de haberes.','number'=>2],      //Nuevo *caso pasivo con renta en AFP , quien solicita el préstamo
                ['name' => 'Cédula de Identidad  - Garante (Original y Fotocopia).','number'=>3],
                ['name' => 'Última Boleta de Pago - Garante (Original y Fotocopia).','number'=>4],
                //['name' => 'Certificado de haberes considerando el último mes percibido.','number'=>2],  // En caso de no contar con la boleta de pago
                //['name' => 'Tres últimas boletas de pago del garante en copia simple.','number'=>3], //garante solidario
                //['name' => 'Certificado de aportes para el Auxilio Mortuorio de los 3 últimos meses de la unidad de Fondo de Retiro.','number'=>4], // para solicitantes AFPś
                ['name' => 'Estado de cuenta original, vigente y emitido por el Banco Unión S.A.','number'=>5],
                ['name' => 'Formulario SIGEP.','number'=>6],
                ['name' => 'Memorándum de agradecimiento o cualquier otro documento que avale el grado del titular.','number'=>7],
                ['name' => 'Certificado de Aportes de la Unidad Fondo de Retiro.','number'=>8],
                //['name' => 'Formulario de Calificación y Aprobación de Préstamos FORM/CAYAP/PTMO/UIP/004.','number'=>6],
                  /*Estos documentos son solicitados por MUSERPOL en caso de ser requeridos*/
                //['name' => 'Certificado de Renta de jubilación o fotocopia legalizada emitida por el servicio Nacional del Sistema de Reparto.','number'=>0],  //En caso de perdida de boleta de pago
                //['name' => 'Solicitud de aclaración de datos personales en la Boleta de Pago.','number'=>0],  // En caso de que el afiliado tenga datos erroneos en su boleta de pago
                ['name' => 'Certificado de no adeudo, emitido por la instancia correspondiente.','number'=>0],  // en caso de que el afiliado haya tenido deudas de otras entidades (Ej: COMIPOL , COVIPOL)
                //['name' => 'Certificado de pago emitido por la entidad correspondiente.','number'=>0],  // en caso de que el afiliado tenga deudas con otras entidades (Ej: COMIPOL , COVIPOL)
                //['name' => 'Memorándum de agradecimiento de servicios en copia simple emitido por el Comando General de la Policía Boliviana.','number'=>0],  // *caso pasivo con renta en AFP , quien solicita el préstamo
                //['name' => 'Conformidad de devolución de descuento por garantía original o fotocopia legalizada.','number'=>0],

              ],
              'parameters' => [
                'debt_index' => 50,
                'quantity_ballots' => 3,
                'guarantors' => 1,
                'min_guarantor_category' =>0.35,
                'max_guarantor_category' =>0.85,
                'max_lenders' => 1,
                'max_cosigner'=>0
              ],
              'interest' => ['annual_interest' => 20,'penal_interest' => 6]
            ],
              // CORTO PLAZO SECTOR PASIVO Y VIUDAS SENASIR
              ['name'=>'Corto plazo el sector pasivo y viudas Senasir','shortened'=>'PCP-SP-SEN','requirements'=>[
                ['name' => 'Cédula de Identidad  del(la) Titular (Original y Fotocopia).','number'=>1],
                ['name' => 'Tres últimas boletas (Original y Fotocopia) y/o Certificado de haberes.','number'=>2], //Nuevo *caso pasivo con renta de SENASIR, quien solicita el préstamo
                //['name' => 'Certificado de haberes considerando el último mes percibido.','number'=>2],  // En caso de no contar con la boleta de pago
                ['name' => 'Estado de cuenta original, vigente y emitido por el Banco Unión S.A.','number'=>3],
                ['name' => 'Formulario SIGEP.','number'=>4],
                ['name' => 'Memorándum de agradecimiento o cualquier otro documento que avale el grado del titular.','number'=>5],
                  /*Estos documentos son solicitados por MUSERPOL en caso de ser requeridos*/
                //['name' => 'Certificado de Renta de jubilación o fotocopia legalizada emitida por el servicio Nacional del Sistema de Reparto.','number'=>0],  //En caso de perdida de boleta de pago
                //['name' => 'Solicitud de aclaración de datos personales en la Boleta de Pago.','number'=>0],  // En caso de que el afiliado tenga datos erroneos en su boleta de pago
                ['name' => 'Certificado de no adeudo, emitido por la instancia correspondiente.','number'=>0],  // en caso de que el afiliado haya tenido deudas de otras entidades (Ej: COMIPOL , COVIPOL)
                //['name' => 'Certificado de pago emitido por la entidad correspondiente.','number'=>0],  // en caso de que el afiliado tenga deudas con otras entidades (Ej: COMIPOL , COVIPOL)
                //['name' => 'Memorándum de agradecimiento de servicios en copia simple emitido por el Comando General de la Policía Boliviana.','number'=>0],  // *caso pasivo con renta en AFP , quien solicita el préstamo
                //['name' => 'Conformidad de devolución de descuento por garantía original o fotocopia legalizada.','number'=>0],
              ],
              'parameters' => [
                'debt_index' => 50,
                'quantity_ballots' => 3,
                'guarantors' => 0,
                'personal_reference' => true,
                'max_lenders' => 1,
                'max_cosigner'=>0
              ],
              'interest' => ['annual_interest' => 20,'penal_interest' => 6]
            ],
            ]
          ],
          'refinanciamiento corto' => [
            // REFINANCIMIENTO CORTO PLAZO
            'type' => ['module_id' => $module->id,'name'=>'Refinanciamiento Préstamo a corto plazo','second_name'=>'R. Corto plazo'],
            'limits' => ['maximum_amount' => 25000,'minimum_amount' => 2001,'maximum_term' => 30,'minimum_term' => 3],
            'modalities' => [
              // REFINANCIAMIENTO ACTIVO
              ['name'=>'Refinanciamiento de Préstamo a corto plazo para el sector Activo','shortened'=>'PCP-R-SA','requirements'=>[
                ['name' => 'Cédula de Identidad  del(la) Titular (Original y Fotocopia).','number'=>1],
                ['name' => 'Tres últimas boletas (Original y Fotocopia) y/o Certificado de haberes.','number'=>2],
                //['name' => 'Cédula de Identidad del (la) titular en copia simple.','number'=>1],
                //['name' => 'Tres últimas Boletas de pago en copia simple.','number'=>2],
                //['name' => 'Certificado de haberes considerando el último mes percibido.','number'=>2],  // En caso de no contar con la boleta de pago
                ['name' => 'Estado de cuenta original, vigente y emitido por el Banco Unión S.A.','number'=>3],
                ['name' => 'Formulario SIGEP.','number'=>4],
                //['name' => 'Formulario de Calificación y Aprobación de Préstamos FORM/CAYAP/PTMO/UIP/004.','number'=>4],
                /*Estos documentos son solicitados por MUSERPOL en caso de ser requeridos*/
                ['name' => 'Certificado de años de servicio (Categoría 100%).','number'=>0],
                ['name' => 'Memorándum de alta (Categoría 0%).','number'=>0],
                ['name' => 'Certificado de no adeudo, emitido por la instancia correspondiente.','number'=>0],  // en caso de que el afiliado haya tenido deudas de otras entidades (Ej: COMIPOL , COVIPOL)
                ['name' => 'Certificado de haberes (En caso que el afiliado no cuente con las boletas de pago).','number'=>0],
                //['name' => 'Solicitud de aclaración de datos personales en la Boleta de Pago.','number'=>0],  // En caso de que el afiliado tenga datos erroneos en su boleta de pago
                //['name' => 'Certificado de pago emitido por la entidad correspondiente.','number'=>0],  // en caso de que el afiliado tenga deudas con otras entidades (Ej: COMIPOL , COVIPOL)
                //['name' => 'Conformidad de devolución de descuento por garantía original o fotocopia legalizada.','number'=>0],
              ],
              'parameters' => [
                'debt_index' => 50,
                'quantity_ballots' => 3,
                'guarantors' => 0,
                'personal_reference' => true,
                'max_lenders' => 1,
                'max_cosigner'=>0
              ],
              'interest' => ['annual_interest' => 20,'penal_interest' => 6]
            ],
               // REFINANCIAMIENTO PASIVOS Y VIUDAS - AFP
              ['name'=>'Refinanciamiento de Préstamo a corto plazo para el sector Pasivo y Viudas AFPs','shortened'=>'PCP-R-SP-AFP','requirements'=>[
                ['name' => 'Cédula de Identidad del(la) Titular (Original y Fotocopia).','number'=>1],
                ['name' => 'Tres últimas boletas (Original y Fotocopia) y/o Certificado de haberes.','number'=>2],
                ['name' => 'Cédula de Identidad - Garante (Original y Fotocopia).','number'=>3],
                ['name' => 'Última Boleta de Pago - Garante (Original y Fotocopia).','number'=>4],
                ['name' => 'Estado de cuenta original, vigente y emitido por el Banco Unión S.A.','number'=>5],
                ['name' => 'Formulario SIGEP.','number'=>6],
                ['name' => 'Memorándum de agradecimiento o cualquier otro documento que avale el grado del titular.','number'=>7],
                ['name' => 'Certificado de Aportes de la Unidad Fondo de Retiro.','number'=>8],
                //['name' => 'Cédula de Identidad del (la) titular en copia simple.','number'=>1],
                //['name' => 'Tres últimas boletas de pago con renta en AFP en copia simple.','number'=>2],      //Nuevo *caso pasivo con renta en AFP , quien solicita el préstamo
                //['name' => 'Certificado de haberes considerando el último mes percibido.','number'=>2],  // En caso de no contar con la boleta de pago
                //['name' => 'Tres últimas boletas de pago del garante en copia simple.','number'=>3], //garante solidario
                //['name' => 'Certificado de aportes para el Auxilio Mortuorio de los 3 últimos meses de la unidad de Fondo de Retiro.','number'=>4], // para solicitantes AFPś
                //['name' => 'Formulario de Calificación y Aprobación de Préstamos FORM/CAYAP/PTMO/UIP/004.','number'=>6],
                /*Estos documentos son solicitados por MUSERPOL en caso de ser requeridos*/
                //['name' => 'Certificado de Renta de jubilación o fotocopia legalizada emitida por el servicio Nacional del Sistema de Reparto.','number'=>0],  //En caso de perdida de boleta de pago
                //['name' => 'Solicitud de aclaración de datos personales en la Boleta de Pago.','number'=>0],  // En caso de que el afiliado tenga datos erroneos en su boleta de pago
                ['name' => 'Certificado de no adeudo, emitido por la instancia correspondiente.','number'=>0],  // en caso de que el afiliado haya tenido deudas de otras entidades (Ej: COMIPOL , COVIPOL)
                //['name' => 'Certificado de pago emitido por la entidad correspondiente.','number'=>0],  // en caso de que el afiliado tenga deudas con otras entidades (Ej: COMIPOL , COVIPOL)
                //['name' => 'Memorándum de agradecimiento de servicios en copia simple emitido por el Comando General de la Policía Boliviana.','number'=>0],  // *caso pasivo con renta en AFP , quien solicita el préstamo
                //['name' => 'Conformidad de devolución de descuento por garantía original o fotocopia legalizada.','number'=>0],
              ],
              'parameters' => [
                'debt_index' => 50,
                'quantity_ballots' => 3,
                'guarantors' => 1,
                'min_guarantor_category' =>0.35,
                'max_guarantor_category' =>0.85,
                'max_lenders' => 1,
                'max_cosigner'=>0

              ],
              'interest' => ['annual_interest' => 20,'penal_interest' => 6]
            ],
              // REFINANCIAMIENTO PASIVOS Y VIUDAS - SENASIR
              ['name'=>'Refinanciamiento de Préstamo a corto plazo para el sector Pasivo y Viudas Senasir','shortened'=>'PCP-R-SP-SEN',
              'requirements'=>[
                ['name' => 'Cédula de Identidad  del(la) Titular (Original y Fotocopia).','number'=>1],
                ['name' => 'Tres últimas boletas (Original y Fotocopia) y/o Certificado de haberes.','number'=>2],
                ['name' => 'Estado de cuenta original, vigente y emitido por el Banco Unión S.A.','number'=>3],
                ['name' => 'Formulario SIGEP.','number'=>4],
                ['name' => ' Memorándum de agradecimiento o cualquier otro documento que avale el grado del titular.','number'=>5],
                //['name' => 'Cédula de Identidad del (la) titular en copia simple.','number'=>1],
                //['name' => 'Tres últimas boletas de pago con renta en SENASIR en copia simple.','number'=>2], //Nuevo *caso pasivo con renta de SENASIR, quien solicita el préstamo
                //['name' => 'Certificado de haberes considerando el último mes percibido.','number'=>2],  // En caso de no contar con la boleta de pago
                //['name' => 'Formulario de Calificación y Aprobación de Préstamos FORM/CAYAP/PTMO/UIP/004.','number'=>4],
                /*Estos documentos son solicitados por MUSERPOL en caso de ser requeridos*/
                //['name' => 'Certificado de Renta de jubilación o fotocopia legalizada emitida por el servicio Nacional del Sistema de Reparto.','number'=>0],  //En caso de perdida de boleta de pago
                //['name' => 'Solicitud de aclaración de datos personales en la Boleta de Pago.','number'=>0],  // En caso de que el afiliado tenga datos erroneos en su boleta de pago
                ['name' => 'Certificado de no adeudo, emitido por la instancia correspondiente.','number'=>0],  // en caso de que el afiliado haya tenido deudas de otras entidades (Ej: COMIPOL , COVIPOL)
                //['name' => 'Certificado de pago emitido por la entidad correspondiente.','number'=>0],  // en caso de que el afiliado tenga deudas con otras entidades (Ej: COMIPOL , COVIPOL)
                //['name' => 'Memorándum de agradecimiento de servicios en copia simple emitido por el Comando General de la Policía Boliviana.','number'=>0],  // *caso pasivo con renta en AFP , quien solicita el préstamo
                //['name' => 'Conformidad de devolución de descuento por garantia original o fotocopia legalizada.','number'=>0],
            	],
                'parameters' => [
                  'debt_index' => 50,
                  'quantity_ballots' => 3,
                  'guarantors' => 0,
                  'personal_reference' => true,
                  'max_lenders' => 1,
                  'max_cosigner'=>0
                ],
                'interest' => ['annual_interest' => 20,'penal_interest' => 6]
              ]
            ]
          ],
          'largo' => [
            'type' => ['module_id' => $module->id,'name'=>'Préstamo a largo plazo','second_name'=>'Largo plazo'],
            'limits' => ['maximum_amount' => 150000,'minimum_amount' => 2001,'maximum_term' => 96,'minimum_term' => 25],
            'modalities' => [
                // LARGO PLAZO SECTOR ACTIVO Y ADMINISTRATIVO CON GARANTÍA PERSONAL(2 GARANTES ACTIVOS)
              ['name'=>'Largo Plazo con garantía personal para el sector activo y personal Adm Policial','shortened'=>'PLP-GP-SAYADM','requirements'=>[
                ['name' => 'Cédula de Identidad  del(la) Titular (Original y Fotocopia).','number'=>1],
                ['name' => 'Última Boleta de Pago del(la) Titular (Original y Fotocopia) y/o Certificado de haberes.','number'=>2],
                ['name' => 'Cédula de Identidad - 2 Garantes (Original y Fotocopia).','number'=>3],
                ['name' => 'Última Boleta de Pago - 2 Garantes (Original y Fotocopia).','number'=>4],
                ['name' => 'Estado de cuenta original, vigente y emitido por el Banco Unión S.A.','number'=>5],
                ['name' => 'Formulario SIGEP.','number'=>6],
                //['name' => 'Cédula de Identidad del (la) titular en copia simple','number'=>1],
                //['name' => 'Última Boleta de pago en copia simple.','number'=>2], // Tambien de los 2 garantes
                //['name' => 'Certificado de haberes considerando el último mes percibido.','number'=>2],  // En caso de no tener la boleta de pago
                //['name' => 'Certificado de años de servicio desglosado en original emitido por el Comando General de la Policía Boliviana','number'=>3],
                //['name' => 'Certificado de años de servicio desglosado en copia legalizada emitido por el Comando General de la Policía Boliviana.','number'=>3],
                //['name' => 'Certificado de años de servicio desglosado emitido por los Comandos Regionales de la Policía Boliviana.','number'=>3],
                //['name' => 'Formulario de Calificación y Aprobación de Préstamos FORM/CAYAP/PTMO/UIP/004.','number'=>5],
                /*Estos documentos son solicitados por MUSERPOL en caso de ser requeridos*/
                ['name' => 'Certificado de años de servicio (Categoría 100% y Personal Administrativo).','number'=>0],
                ['name' => 'Memorándum de alta (Categoría 0%).','number'=>0],
                ['name' => 'Certificado de no adeudo, emitido por la instancia correspondiente.','number'=>0], // en caso de que el afiliado haya tenido deudas de otras entidades (Ej: COMIPOL , COVIPOL)
                ['name' => 'Certificado de haberes (En caso que el afiliado no cuente con las boletas de pago).','number'=>0],
                //['name' => 'Solicitud de aclaración de datos personales en la Boleta de Pago.','number'=>0],  // En caso de que el afiliado tenga datos erroneos en su boleta de pago    
                //['name' => 'Certificado de pago emitido por la entidad correspondiente.','number'=>0],  // en caso de que el afiliado tenga deudas con otras entidades (Ej: COMIPOL , COVIPOL)
                //['name' => 'Fotocopia Testimonio de propiedad.','number'=>0],  // en caso de que el plazo sea mayor a 60 meses un prestamo para vivienda pero no hipotecario
                //['name' => 'Folio Real Actualizado o Información rapida del inmueble.','number'=>0],  // en caso de que el plazo sea mayor a 60 meses un prestamo para vivienda pero no hipotecario
                //['name' => 'Fotocopia de catastro.','number'=>0],  // en caso de que el plazo sea mayor a 60 meses un prestamo para vivienda pero no hipotecario
                //['name' => 'Conformidad de devolución de descuento por garantía original o fotocopia legalizada.','number'=>0],
              ],
              'parameters' => [
                'debt_index' => 50,
                'quantity_ballots' => 1,
                'guarantors' => 2,
                'min_guarantor_category' =>0.35,
                'max_guarantor_category' =>1.00,
                'personal_reference' => true,
                'max_lenders' => 1,
                'max_cosigner'=>0
              ],
              'interest' => ['annual_interest' => 13.2,'penal_interest' => 6]
            ],
                  // LARGO PLAZO SECTOR PASIVO CON GARANTÍA PERSONAL
              ['name'=>'Largo Plazo con garantía personal para el sector pasivo','shortened'=>'PLP-GP-SP','requirements'=>[
                ['name' => 'Cédula de Identidad  del(la) Titular (Original y Fotocopia).','number'=>1],
                ['name' => 'Última Boleta de Pago del(la) Titular (Original y Fotocopia) y/o Certificado de haberes.','number'=>2],
                ['name' => 'Cédula de Identidad - Garante (Original y Fotocopia).','number'=>3],
                ['name' => 'Última Boleta de Pago - Garante (Original y Fotocopia).','number'=>4],
                ['name' => 'Estado de cuenta original, vigente y emitido por el Banco Unión S.A.','number'=>5],
                ['name' => 'Formulario SIGEP.','number'=>6],
                ['name' => 'Memorándum de agradecimiento o cualquier otro documento que avale el grado del titular.','number'=>7],
                //['name' => 'Cédula de Identidad del (la) titular en copia simple.','number'=>1],// del solicitante y los garantes
                //['name' => 'Última boleta de pago en copia simple.','number'=>2],
                //['name' => 'Certificado de haberes considerando el último mes percibido.','number'=>2],  // En caso de no tener la boleta de pago
                //['name' => 'Formulario de Calificación y Aprobación de Préstamos FORM/CAYAP/PTMO/UIP/004.','number'=>4],
                /*Estos documentos son solicitados por MUSERPOL en caso de ser requeridos*/
                ['name' => 'Certificado de Aportes Voluntarios del Área Fondo de Retiro (Afiliados AFP).','number'=>0],
                ['name' => 'Certificado de no adeudo, emitido por la instancia correspondiente.','number'=>0],  // en caso de que el afiliado haya tenido deudas de otras entidades (Ej: COMIPOL , COVIPOL)
                //['name' => 'Certificado de Renta de jubilación o fotocopia legalizada emitida por el servicio Nacional del Sistema de Reparto.','number'=>0],  //En caso de perdida de boleta de pago
                //['name' => 'Certificado de aportes para el Auxilio Mortuorio de los 3 últimos meses de la unidad de Fondo de Retiro.','number'=>0], // para solicitantes AFPś
                //['name' => 'Solicitud de aclaración de datos personales en la Boleta de Pago.','number'=>0],  // En caso de que el afiliado tenga datos erroneos en su boleta de pago     
                //['name' => 'Certificado de pago emitido por la entidad correspondiente.','number'=>0],  // en caso de que el afiliado tenga deudas con otras entidades (Ej: COMIPOL , COVIPO
                //['name' => 'Memorándum de agradecimiento de servicios en copia simple emitido por el Comando General de la Policía Boliviana.','number'=>0],  // *caso pasivo con renta en AFP , quien solicita el préstamo
                //['name' => 'Conformidad de devolución de descuento por garantía original o fotocopia legalizada.','number'=>0],

              ],
              'parameters' => [
                'debt_index' => 50,
                'quantity_ballots' => 1,
                'guarantors' => 1,
                'min_guarantor_category' =>0.35,
                'max_guarantor_category' =>0.85,
                'personal_reference' => true,
                'max_lenders' => 1,
                'max_cosigner'=>0
              ],
              'interest' => ['annual_interest' => 13.2,'penal_interest' => 6]
            ],
                // LARGO PLAZO SECTOR PASIVO CON UN SOLO GARANTE - CPOP  //Eliminar en caso de que no proceda
                ['name'=>'Largo Plazo con un solo garante para el sector pasivo - CPOP','shortened'=>'PLP-SP-CPOP','requirements'=>[
                  ['name' => 'Cédula de Identidad  del(la) Titular (Original y Fotocopia).','number'=>1],
                  ['name' => 'Última Boleta de Pago del(la) Titular (Original y Fotocopia) y/o Certificado de haberes.','number'=>2],
                  ['name' => 'Cédula de Identidad – 1 Garante (Original y Fotocopia).','number'=>3],
                  ['name' => 'Última Boleta de Pago – 1 Garante (Original y Fotocopia).','number'=>4],
                  ['name' => 'Estado de cuenta original, vigente y emitido por el Banco Unión S.A.','number'=>5],
                  ['name' => 'Formulario SIGEP.','number'=>6],
                  ['name' => 'Memorándum de agradecimiento o cualquier otro documento que avale el grado del titular.','number'=>7],
                  ['name' => 'Certificado de Aportes Voluntarios del Área Fondo de Retiro (Afiliados AFP).','number'=>8],
                  //['name' => 'Cédula de Identidad del (la) titular en copia simple','number'=>1],
                  //['name' => 'Última boleta de pago en copia simple.','number'=>2],
                  //['name' => 'Certificado de haberes considerando el último mes percibido.','number'=>2],  // En caso de no tener la boleta de pago
                  //['name' => 'Certificado de años de servicio desglosado en original emitido por el Comando General de la Policía Boliviana','number'=>3],
                  //['name' => 'Certificado de años de servicio desglosado en fotocopia Legalizada emitido por el Comando General de la Policía Boliviana','number'=>3],
                  //['name' => 'Certificado de años de servicio desglosado en original emitido por el Comando Regional de la Policía Boliviana','number'=>3],
                  //['name' => 'Formulario de Calificación y Aprobación de Préstamos FORM/CAYAP/PTMO/UIP/004.','number'=>5],
                  /*condiciones COP MUSERPOL*/
                  /*Estos documentos son solicitados por MUSERPOL en caso de ser requeridos*/
                  ['name' => 'Certificado de no adeudo, emitido por la instancia correspondiente','number'=>0],  // en caso de que el afiliado haya tenido deudas de otras entidades (Ej: COMIPOL , COVIPOL)
                  //['name' => 'Certificado de Renta de jubilación o fotocopia legalizada emitida por el servicio Nacional del Sistema de Reparto.','number'=>0],  //En caso de perdida de boleta de pago
                  //['name' => 'Certificado de aportes para el Auxilio Mortuorio de los 3 últimos meses de la unidad de Fondo de Retiro.','number'=>0], // para solicitantes AFPś
                  //['name' => 'Solicitud de aclaración de datos personales en la Boleta de Pago.','number'=>0],  // En caso de que el afiliado tenga datos erroneos en su boleta de pago
                  //['name' => 'Certificado de pago emitido por la entidad correspondiente.','number'=>0],  // en caso de que el afiliado tenga deudas con otras entidades (Ej: COMIPOL , COVIPO
                  //['name' => 'Memorándum de agradecimiento de servicios en copia simple emitido por el Comando General de la Policía Boliviana.','number'=>0],  // *caso pasivo con renta en AFP , quien solicita el préstamo
                  //['name' => 'Conformidad de devolución de descuento por garantía original o fotocopia legalizada.','number'=>0],
                ],
                'parameters' => [
                  'debt_index' => 60,
                  'quantity_ballots' => 1,
                  'guarantors' => 1,
                  'min_guarantor_category' =>0.35,
                  'max_guarantor_category' =>0.85,
                  'personal_reference' => true,
                  'max_lenders' => 1,
                  'max_cosigner'=>0
                ],
                'interest' => ['annual_interest' => 13.2,'penal_interest' => 6]
              ],

                // LARGO PLAZO UN SOLO GARANTE SECTOR ACTIVO --Afiliados CPOP
              ['name'=>'Largo Plazo con un solo garante para el sector activo - CPOP','shortened'=>'PLP-CPOP','requirements'=>[
                ['name' => 'Cédula de Identidad  del(la) Titular (Original y Fotocopia).','number'=>1],
                ['name' => 'Última Boleta de Pago del(la) Titular (Original y Fotocopia) y/o Certificado de haberes.','number'=>2],
                ['name' => 'Cédula de Identidad - 1 Garante (Original y Fotocopia).','number'=>3],
                ['name' => 'Última Boleta de Pago - 1 Garante (Original y Fotocopia).','number'=>4],
                ['name' => 'Estado de cuenta original, vigente y emitido por el Banco Unión S.A.','number'=>5],
                ['name' => 'Formulario SIGEP.','number'=>6],
                //['name' => 'Última Boleta de pago en copia simple.','number'=>2]
                //['name' => 'Cédula de Identidad del (la) titular en copia simple','number'=>1],
                //['name' => 'Última Boleta de pago en copia simple.','number'=>2], // Tambien de los 2 garantes
                //['name' => 'Certificado de haberes considerando el último mes percibido.','number'=>2],  // En caso de no tener la boleta de pago
                //['name' => 'Certificado de años de servicio desglosado en original emitido por el Comando General de la Policía Boliviana','number'=>3],
                //['name' => 'Certificado de años de servicio desglosado en fotocopia Legalizada emitido por el Comando General de la Policía Boliviana','number'=>3],
	              //['name' => 'Certificado de años de servicio desglosado en original emitido por el Comando Regional de la Policía Boliviana','number'=>3],
                //['name' => 'Certificado de años de servicio desglosado en fotocopia Legalizada emitido por el Comando Regional de la Policía Boliviana','number'=>3],
                //['name' => 'Formulario de Calificación y Aprobación de Préstamos FORM/CAYAP/PTMO/UIP/004.','number'=>5],
                /*Estos documentos son solicitados por MUSERPOL en caso de ser requeridos*/
                ['name' => 'Certificado de años de servicio (Categoría 100% y Personal Administrativo).','number'=>0],
                ['name' => 'Memorándum de alta (Categoría 0%).','number'=>0],
                ['name' => 'Certificado de no adeudo, emitido por la instancia correspondiente.','number'=>0],  // en caso de que el afiliado haya tenido deudas de otras entidades (Ej: COMIPOL , COVIPOL)
                ['name' => 'Certificado de haberes (En caso que el afiliado no cuente con las boletas de pago).','number'=>0],
                //['name' => 'Solicitud de aclaración de datos personales en la Boleta de Pago.','number'=>0],  // En caso de que el afiliado tenga datos erroneos en su boleta de pago
                //['name' => 'Certificado de pago emitido por la entidad correspondiente.','number'=>0],  // en caso de que el afiliado tenga deudas con otras entidades (Ej: COMIPOL , COVIPOL)
                //['name' => 'Certificado de no ingreso a disponibilidad de las letras “C” y “A” en original emitido por el Comando General de la Policía Boliviana.','number'=>0], //verificacion, no encontrarse en disponibilidad de letra A o C o item 0
                //['name' => 'Conformidad de devolución de descuento por garantía original o fotocopia legalizada.','number'=>0],
              ]
              ,
              'parameters' => [
                'debt_index' => 60,
                'quantity_ballots' => 1,
                'guarantors' => 1,
                'min_guarantor_category' =>0.35,
                'max_guarantor_category' =>1.00,
                'personal_reference' => true,
                'max_lenders' => 1,
                'max_cosigner'=>0
              ],
              'interest' => ['annual_interest' => 13.2,'penal_interest' => 6]
            ],
            ]
          ],
          'refinanciamiento largo' => [
            'type' => ['module_id' => $module->id,'name'=>'Refinanciamiento Préstamo a largo plazo','second_name'=>'R. Largo plazo'],
            'limits' => ['maximum_amount' => 150000,'minimum_amount' => 2001,'maximum_term' => 96,'minimum_term' => 25],
            'modalities' => [
              // REFINANCIANCIAMIENTO SECTOR ACTIVO CON UN SOLO GARANTE ---> En este caso el garante solo puede ser activo
              ['name'=>'Refinanciamiento de Préstamo a largo Plazo para el sector activo - CPOP','shortened'=>'PLP-R-SA-CPOP','requirements'=>[
                ['name' => 'Cédula de Identidad  del(la) Titular (Original y Fotocopia).','number'=>1],
                ['name' => 'Última Boleta de Pago del(la) Titular (Original y Fotocopia) y/o Certificado de haberes.','number'=>2],
                ['name' => 'Cédula de Identidad - 1 Garante (Original y Fotocopia).','number'=>3],
                ['name' => 'Última Boleta de Pago - 1 Garante (Original y Fotocopia).','number'=>4],
                ['name' => 'Estado de cuenta original, vigente y emitido por el Banco Unión S.A.','number'=>5],
                ['name' => 'Formulario SIGEP.','number'=>6],
                //['name' => 'Cédula de Identidad del (la) titular en copia simple','number'=>1],
                //['name' => 'Última Boleta de pago en copia simple.','number'=>2], // Tambien de los 2 garantes
                //['name' => 'Certificado de haberes considerando el último mes percibido.','number'=>2],  // En caso de no tener la boleta de pago
            		//['name' => 'Certificado de años de servicio desglosado en original emitido por el Comando General de la Policía Boliviana','number'=>3],
            		//['name' => 'Certificado de años de servicio desglosado en fotocopia Legalizada emitido por el Comando General de la Policía Boliviana','number'=>3],
                //['name' => 'Certificado de años de servicio desglosado en original emitido por el Comando Regional de la Policía Boliviana','number'=>3],
                //['name' => 'Formulario de Calificación y Aprobación de Préstamos FORM/CAYAP/PTMO/UIP/004.','number'=>5],
                /*condiciones COP MUSERPOL*/
                /*Estos documentos son solicitados por MUSERPOL en caso de ser requeridos*/
                //['name' => 'Solicitud de aclaración de datos personales en la Boleta de Pago.','number'=>0],  // En caso de que el afiliado tenga datos erroneos en su boleta de pago
                ['name' => 'Certificado de años de servicio (Categoría 100% y Personal Administrativo).','number'=>0],
                ['name' => 'Memorándum de alta (Categoría 0%).','number'=>0],  // en caso de que el afiliado haya tenido deudas de otras entidades (Ej: COMIPOL , COVIPOL)   
                ['name' => 'Certificado de no adeudo, emitido por la instancia correspondiente.','number'=>0],
                ['name' => 'Certificado de haberes (En caso que el afiliado no cuente con las boletas de pago.','number'=>0],
                
                //['name' => 'Certificado de pago emitido por la entidad correspondiente.','number'=>0],  // en caso de que el afiliado tenga deudas con otras entidades (Ej: COMIPOL , COVIPOL)
                //['name' => 'Fotocopia Testimonio de propiedad.','number'=>0],  // en caso de que el plazo sea mayor a 60 meses un prestamo para vivienda pero no hipotecario
                //['name' => 'Folio Real Actualizado o Información rapida del inmueble.','number'=>0],  // en caso de que el plazo sea mayor a 60 meses un prestamo para vivienda pero no hipotecario
                //['name' => 'Fotocopia de catastro.','number'=>0],  // en caso de que el plazo sea mayor a 60 meses un prestamo para vivienda pero no hipotecario
                //['name' => 'Conformidad de devolución de descuento por garantía original o fotocopia legalizada.','number'=>0],

              ],
              'parameters' => [
                'debt_index' => 60,
                'quantity_ballots' => 1,
                'guarantors' => 1,
                'min_guarantor_category' =>0.35,
                'max_guarantor_category' =>1.00,
                'personal_reference' => true,
                'max_lenders' => 1,
                'max_cosigner'=>0
              ],
              'interest' => ['annual_interest' => 13.2,'penal_interest' => 6]
            ],
                 // REFINANCIANCIAMIENTO SECTOR PASIVO CON GARANTIA PERSONAL 
                ['name'=>'Refinanciamiento de Préstamo a largo Plazo con garantía personal para el sector pasivo','shortened'=>'PLP-R-GP-SP','requirements'=>[
                  ['name' => 'Cédula de Identidad  del(la) Titular (Original y Fotocopia).','number'=>1],
                  ['name' => 'Última Boleta de Pago del(la) Titular (Original y Fotocopia) y/o Certificado de haberes.','number'=>2],
                  ['name' => 'Cédula de Identidad -  Garante (Original y Fotocopia).','number'=>3],
                  ['name' => 'Última Boleta de Pago - Garante (Original y Fotocopia).','number'=>4],
                  ['name' => 'Estado de cuenta original, vigente y emitido por el Banco Unión S.A.','number'=>5],
                  ['name' => 'Formulario SIGEP.','number'=>6],
                  ['name' => 'Memorándum de agradecimiento o cualquier otro documento que avale el grado del titular.','number'=>7],
                 // ['name' => 'Cédula de Identidad del (la) titular en copia simple.','number'=>1],// del solicitante y los garantes
                  //['name' => 'Última boleta de pago en copia simple.','number'=>2],
                  //['name' => 'Certificado de haberes considerando el último mes percibido.','number'=>2],  // En caso de no tener la boleta de pago
                  //['name' => 'Formulario de Calificación y Aprobación de Préstamos FORM/CAYAP/PTMO/UIP/004.','number'=>4],
                  /*Estos documentos son solicitados por MUSERPOL en caso de ser requeridos*/
                  ['name' => 'Certificado de Aportes Voluntarios del Área Fondo de Retiro (Afiliados AFP).', 'number'=>0],
                  //['name' => 'Certificado de Renta de jubilación o fotocopia legalizada emitida por el servicio Nacional del Sistema de Reparto.','number'=>0],  //En caso de perdida de boleta de pago
                  //['name' => 'Certificado de aportes para el Auxilio Mortuorio de los 3 últimos meses de la unidad de Fondo de Retiro.','number'=>0], // para solicitantes AFPś
                  //['name' => 'Solicitud de aclaración de datos personales en la Boleta de Pago.','number'=>0],  // En caso de que el afiliado tenga datos erroneos en su boleta de pago
                  ['name' => 'Certificado de no adeudo, emitido por la instancia correspondiente','number'=>0],  // en caso de que el afiliado haya tenido deudas de otras entidades (Ej: COMIPOL , COVIPOL)
                  //['name' => 'Certificado de pago emitido por la entidad correspondiente.','number'=>0],  // en caso de que el afiliado tenga deudas con otras entidades (Ej: COMIPOL , COVIPO
                  //['name' => 'Memorándum de agradecimiento de servicios en copia simple emitido por el Comando General de la Policía Boliviana.','number'=>0],  // *caso pasivo con renta en AFP , quien solicita el préstamo
                  //['name' => 'Conformidad de devolución de descuento por garantía original o fotocopia legalizada.','number'=>0],
  
                ],
                'parameters' => [
                  'debt_index' => 50,
                  'quantity_ballots' => 1,
                  'guarantors' => 1,
                  'min_guarantor_category' =>0.35,
                  'max_guarantor_category' =>0.85,
                  'personal_reference' => true,
                  'max_lenders' => 1,
                  'max_cosigner'=>0
                ],
                'interest' => ['annual_interest' => 13.2,'penal_interest' => 6]
              ],
                 // REFINANCIANCIAMIENTO SECTOR PASIVO CON UN SOLO GARANTE - CPOP //Eliminar en caso de que no proceda
              ['name'=>'Refinanciamiento de Préstamo a largo Plazo para el sector pasivo - CPOP','shortened'=>'PLP-R-SP-CPOP','requirements'=>[
                ['name' => 'Cédula de Identidad  del(la) Titular (Original y Fotocopia).','number'=>1],
                ['name' => 'Última Boleta de Pago del(la) Titular (Original y Fotocopia) y/o Certificado de haberes.','number'=>2],
                ['name' => 'Cédula de Identidad –  Garante (Original y Fotocopia).','number'=>3],
                ['name' => 'Última Boleta de Pago – Garante (Original y Fotocopia).','number'=>4],
                ['name' => 'Estado de cuenta original, vigente y emitido por el Banco Unión S.A.','number'=>5],
                ['name' => 'Formulario SIGEP.','number'=>6],
                ['name' => 'Memorándum de agradecimiento o cualquier otro documento que avale el grado del titular.','number'=>7],
                //['name' => 'Cédula de Identidad del (la) titular en copia simple','number'=>1],
                //['name' => 'Última boleta de pago en copia simple.','number'=>2],
                //['name' => 'Certificado de haberes considerando el último mes percibido.','number'=>2],  // En caso de no tener la boleta de pago
                //['name' => 'Certificado de años de servicio desglosado en original emitido por el Comando General de la Policía Boliviana','number'=>3],
            		//['name' => 'Certificado de años de servicio desglosado en fotocopia Legalizada emitido por el Comando General de la Policía Boliviana','number'=>3],
                //['name' => 'Certificado de años de servicio desglosado en original emitido por el Comando Regional de la Policía Boliviana','number'=>3],
                //['name' => 'Formulario de Calificación y Aprobación de Préstamos FORM/CAYAP/PTMO/UIP/004.','number'=>5],
                /*condiciones COP MUSERPOL*/
                /*Estos documentos son solicitados por MUSERPOL en caso de ser requeridos*/
                ['name' => 'Certificado de Aportes Voluntarios del Área Fondo de Retiro (Afiliados AFP).','number'=>0],
                //['name' => 'Certificado de pago emitido por la entidad correspondiente.','number'=>0],
                //['name' => 'Certificado de Renta de jubilación o fotocopia legalizada emitida por el servicio Nacional del Sistema de Reparto.','number'=>0],  //En caso de perdida de boleta de pago
                //['name' => 'Certificado de aportes para el Auxilio Mortuorio de los 3 últimos meses de la unidad de Fondo de Retiro.','number'=>0], // para solicitantes AFPś
               //['name' => 'Solicitud de aclaración de datos personales en la Boleta de Pago.','number'=>0],  // En caso de que el afiliado tenga datos erroneos en su boleta de pago
                ['name' => 'Certificado de no adeudo, emitido por la instancia correspondiente','number'=>0],  // en caso de que el afiliado haya tenido deudas de otras entidades (Ej: COMIPOL , COVIPOL)
                //['name' => 'Certificado de pago emitido por la entidad correspondiente.','number'=>0],  // en caso de que el afiliado tenga deudas con otras entidades (Ej: COMIPOL , COVIPO
                //['name' => 'Memorándum de agradecimiento de servicios en copia simple emitido por el Comando General de la Policía Boliviana.','number'=>0],  // *caso pasivo con renta en AFP , quien solicita el préstamo
                //['name' => 'Conformidad de devolución de descuento por garantía original o fotocopia legalizada.','number'=>0],
              ],
              'parameters' => [
                'debt_index' => 60,
                'quantity_ballots' => 1,
                'guarantors' => 1,
                'min_guarantor_category' =>0.35,
                'max_guarantor_category' =>0.85,
                'personal_reference' => true,
                'max_lenders' => 1,
                'max_cosigner'=>0
              ],
              'interest' => ['annual_interest' => 13.2,'penal_interest' => 6]
            ],
                //REFINANCIAMIENTO LARGO PLAZO SECTOR ACTIVO Y ADMINISTRATIVO CON GARANTÍA PERSONAL(2 GARANTES ACTIVOS) **
                ['name'=>'Refinanciamiento de Préstamo a largo Plazo con garantía personal para el sector activo y personal Adm Policial','shortened'=>'PLP-R-GP-SAYADM','requirements'=>[
                  ['name' => 'Cédula de Identidad  del(la) Titular (Original y Fotocopia).','number'=>1],
                  ['name' => 'Última Boleta de Pago del(la) Titular (Original y Fotocopia) y/o Certificado de haberes.','number'=>2],
                  ['name' => 'Cédula de Identidad - 2 Garantes (Original y Fotocopia).','number'=>3],
                  ['name' => 'Última Boleta de Pago - 2 Garantes (Original y Fotocopia).','number'=>4],
                  ['name' => 'Estado de cuenta original, vigente y emitido por el Banco Unión S.A.','number'=>5],
                  ['name' => 'Formulario SIGEP.','number'=>6],
                  //['name' => 'Cédula de Identidad del (la) titular en copia simple','number'=>1],
                  //['name' => 'Última Boleta de pago en copia simple.','number'=>2], // Tambien de los 2 garantes
                  //['name' => 'Certificado de haberes considerando el último mes percibido.','number'=>2],  // En caso de no tener la boleta de pago
                  //['name' => 'Certificado de años de servicio desglosado en original emitido por el Comando General de la Policía Boliviana','number'=>3],
                  //['name' => 'Certificado de años de servicio desglosado en copia legalizada emitido por el Comando General de la Policía Boliviana.','number'=>3],
                  //['name' => 'Certificado de años de servicio desglosado emitido por los Comandos Regionales de la Policía Boliviana.','number'=>3],
                  //['name' => 'Formulario de Calificación y Aprobación de Préstamos FORM/CAYAP/PTMO/UIP/004.','number'=>5],
                  /*Estos documentos son solicitados por MUSERPOL en caso de ser requeridos*/
                  //['name' => 'Solicitud de aclaración de datos personales en la Boleta de Pago.','number'=>0],  // En caso de que el afiliado tenga datos erroneos en su boleta de pago
                  ['name' => 'Certificado de años de servicio (Categoría 100% y Personal Administrativo).','number'=>0],
                  ['name' => 'Memorándum de alta (Categoría 0%).','number'=>0],
                  ['name' => 'Certificado de no adeudo, emitido por la instancia correspondiente.','number'=>0],  // en caso de que el afiliado haya tenido deudas de otras entidades (Ej: COMIPOL , COVIPOL)
                  ['name' => 'Certificado de haberes (En caso que el afiliado no cuente con las boletas de pago).','number'=>0]
                  //['name' => 'Certificado de pago emitido por la entidad correspondiente.','number'=>0],  // en caso de que el afiliado tenga deudas con otras entidades (Ej: COMIPOL , COVIPOL)
                  //['name' => 'Fotocopia Testimonio de propiedad.','number'=>0],  // en caso de que el plazo sea mayor a 60 meses un prestamo para vivienda pero no hipotecario
                  //['name' => 'Folio Real Actualizado o Información rapida del inmueble.','number'=>0],  // en caso de que el plazo sea mayor a 60 meses un prestamo para vivienda pero no hipotecario
                  //['name' => 'Fotocopia de catastro.','number'=>0],  // en caso de que el plazo sea mayor a 60 meses un prestamo para vivienda pero no hipotecario
                  //['name' => 'Conformidad de devolución de descuento por garantía original o fotocopia legalizada.','number'=>0],
                ],
                'parameters' => [
                  'debt_index' => 50,
                  'quantity_ballots' => 1,
                  'guarantors' => 2,
                  'min_guarantor_category' =>0.35,
                  'max_guarantor_category' =>1.00,
                  'personal_reference' => true,
                  'max_lenders' => 1,
                  'max_cosigner'=>0
                ],
                'interest' => ['annual_interest' => 13.2,'penal_interest' => 6]
              ]
            ]
          ],
          'hipotecario' => [
            'type' => ['module_id' => $module->id,'name'=>'Préstamo hipotecario','second_name'=>'Hipotecario'],
            'limits' => ['maximum_amount' => 700000,'minimum_amount' => 25001,'maximum_term' => 240,'minimum_term' => 25],
            'modalities' => [
               // LARGO PLAZO GARANTÍA HIPOTECARIA
              ['name'=>'Préstamo con garantia hipotecaria de bien inmueble para el sector activo','shortened'=>'PLP-GH-SA','requirements'=>[
              ['name' => 'Cedula de Identidad  del(la) Titular (Original y Fotocopia).','number'=>1],
              ['name' => 'Tres Últimas Boleta de Pago del(la) Titular (Original y Fotocopia) y/o Certificado de haberes.','number'=>2],
              ['name' => 'Certificado de años de servicio (Original y/o fotocopia legalizada).','number'=>3],
              ['name' => 'Croquis de ubicación del inmueble.','number'=>4],
              ['name' => 'Factura de Agua o Luz del inmueble (Con antigüedad no mayor a 60 días).','number'=>5],
              ['name' => 'Folio Real actualizado (No mayor a 90 días de su emisión).','number'=>6],
              ['name' => 'Información rápida del inmueble - Original (En caso que el Folio Real este desactualizado, mayor a 90 días).','number'=>7],
              ['name' => 'Fotocopia de Testimonio de Propiedad.','number'=>8],
              ['name' => 'Fotocopia de Pago de Impuestos de los 3 últimos años.','number'=>9],
              ['name' => 'Fotocopia del Plano del Lote.','number'=>10],
              ['name' => 'Fotocopia del Catastro.','number'=>11],
              ['name' => 'Certificado de Estado Civil o Soltería de todos los participantes del Préstamo (Original).','number'=>12],
              ['name' => 'Estado de cuenta original, vigente y emitido por el Banco Unión S.A.','number'=>13],
              ['name' => 'Formulario SIGEP.','number'=>14],
              ['name' => 'En caso que el cónyuge sea policía debe cumplir con los mismos requisitos que el solicitante, excepto  la documentación de la garantía.','number'=>15],
              /*['name' => 'Cédula de Identidad del (la) titular en copia simple','number'=>1],
              ['name' => 'Tres últimas Boletas de pago en copia simple.','number'=>2],
              ['name' => 'Certificado de haberes considerando el último mes percibido.','number'=>2],  // En caso de no contar con la boleta de pago
              ['name' => 'Certificado de años de servicio desglosado en original emitido por el Comando General de la Policía Boliviana','number'=>3],
              ['name' => 'Certificado de años de servicio desglosado en fotocopia Legalizada emitido por el Comando General de la Policía Boliviana','number'=>3],
              ['name' => 'Certificado de años de servicio desglosado en original emitido por el Comando Regional de la Policía Boliviana','number'=>3],
              ['name' => 'Croquis de ubicación del inmueble','number'=>4], //nuevo
              ['name' => 'Factura de Luz del Inmueble','number'=>5], //nuevo
              ['name' => 'Factura de Agua del Inmueble','number'=>6], //nuevo
              ['name' => 'Folio Real','number'=>7], //nuevo
              ['name' => 'Información rapida del Inmueble','number'=>8], //nuevo
              ['name' => 'Fotocopia de Testimonio de Propiedad','number'=>9], //nuevo
              ['name' => 'Fotocopia de Impuestos 3 Ultimos Años','number'=>10], //nuevo
              ['name' => 'Fotocopia de Plano de lote','number'=>11], //nuevo
              ['name' => 'Fotocopia de Catastro','number'=>12], //nuevo
              ['name' => 'Estado de cuenta original, vigente y emitido por el Banco Unión S.A','number'=>13],
              ['name' => 'Certificado de estado civil en original emitido por el SERECI.','number'=>14],
              ['name' => 'Formulario de Calificación y Aprobación de Préstamos FORM/CAYAP/PTMO/UIP/004.','number'=>15],*/
              /*Estos documentos son solicitados por MUSERPOL en caso de ser requeridos*/
              //['name' => 'Solicitud de aclaración de datos personales en la Boleta de Pago.','number'=>0],  // En caso de que el afiliado tenga datos erroneos en su boleta de pago
              ['name' => 'Certificado de no adeudo, emitido por la instancia correspondiente.','number'=>0],  // en caso de que el afiliado haya tenido deudas de otras entidades (Ej: COMIPOL , COVIPOL)
              //['name' => 'Certificado de pago emitido por la entidad correspondiente.','number'=>0],  // en caso de que el afiliado tenga deudas con otras entidades (Ej: COMIPOL , COVIPOL)
              //['name' => 'Conformidad de devolución de descuento por garantía original o fotocopia legalizada.','number'=>0],
              /**/
            ],
            'parameters' => [
            'debt_index' => 80,
            'quantity_ballots' => 3,
            'min_lender_category'=> 0.35,
            'guarantors' => 0,
            'personal_reference' => true,
            'max_lenders' => 2,
            'max_cosigner'=>2
            ],
            'interest' => ['annual_interest' => 9,'penal_interest' => 6]
            ],
             // LARGO PLAZO GARANTÍA HIPOTECARIA CPOP //Eliminar en caso de que no proceda
              ['name'=>'Préstamo con garantia hipotecaria de bien inmueble para el sector activo - CPOP','shortened'=>'PLP-GH-CPOP','requirements'=>[
                ['name' => 'Cedula de Identidad  del(la) Titular (Original y Fotocopia).','number'=>1],
                ['name' => 'Tres Últimas Boleta de Pago del(la) Titular (Original y Fotocopia) y/o Certificado de haberes','number'=>2],
                ['name' => 'Certificado de años de servicio (Original y/o fotocopia legalizada).','number'=>3],
                ['name' => 'Croquis de ubicación del inmueble.','number'=>4],
                ['name' => 'Factura de Agua o Luz del inmueble (Con antigüedad no mayor a 60 días).','number'=>5],
                ['name' => 'Folio Real actualizado (No mayor a 90 días de su emisión).','number'=>6],
                ['name' => 'Información rápida del inmueble – Original (En caso que el Folio Real este desactualizado, mayor a 90 días).','number'=>7],
                ['name' => 'Fotocopia de Testimonio de Propiedad.','number'=>8],
                ['name' => 'Fotocopia de Pago de Impuestos de los 3 últimos años','number'=>9],
                ['name' => 'Fotocopia del Plano del Lote.','number'=>10],
                ['name' => 'Fotocopia del Catastro.','number'=>11],
                ['name' => 'Certificado de Estado Civil o Soltería de todos los participantes del Préstamo (Original).','number'=>12],
                ['name' => 'Estado de cuenta original, vigente y emitido por el Banco Unión S.A.','number'=>13],
                ['name' => 'Formulario SIGEP.','number'=>14],
                ['name' => 'En caso que el cónyuge sea policía debe cumplir con los mismos requisitos que el solicitante, excepto  la documentación de la garantía..','number'=>15],
                //['name' => 'Cédula de Identidad del (la) titular en copia simple','number'=>1],
                //['name' => 'Tres últimas Boletas de pago en copia simple.','number'=>2],
                //['name' => 'Certificado de haberes considerando el último mes percibido.','number'=>2],  // En caso de no contar con la boleta de pago
                //['name' => 'Certificado de años de servicio desglosado en original emitido por el Comando General de la Policía Boliviana','number'=>3],
                //['name' => 'Certificado de años de servicio desglosado en fotocopia Legalizada emitido por el Comando General de la Policía Boliviana','number'=>3],
                //['name' => 'Certificado de años de servicio desglosado en original emitido por el Comando Regional de la Policía Boliviana','number'=>3],
                //['name' => 'Croquis de ubicación del inmueble','number'=>4], //nuevo
                //['name' => 'Factura de Luz del Inmueble','number'=>5], //nuevo
                //['name' => 'Factura de Agua del Inmueble','number'=>6], //nuevo
                //['name' => 'Folio Real','number'=>7], //nuevo
                //['name' => 'Información rapida del Inmueble','number'=>8], //nuevo
                //['name' => 'Fotocopia de Testimonio de Propiedad','number'=>9], //nuevo
                //['name' => 'Fotocopia de Impuestos 3 Ultimos Años','number'=>10], //nuevo
                //['name' => 'Fotocopia de Plano de lote','number'=>11], //nuevo
                //['name' => 'Fotocopia de Catastro','number'=>12], //nuevo
                //['name' => 'Estado de cuenta original, vigente y emitido por el Banco Unión S.A','number'=>13],
                //['name' => 'Certificado de estado civil en original emitido por el SERECI.','number'=>14],
                //['name' => 'Formulario de Calificación y Aprobación de Préstamos FORM/CAYAP/PTMO/UIP/004.','number'=>15],
                /*Estos documentos son solicitados por MUSERPOL en caso de ser requeridos*/
                //['name' => 'Solicitud de aclaración de datos personales en la Boleta de Pago.','number'=>0],  // En caso de que el afiliado tenga datos erroneos en su boleta de pago
                ['name' => 'Certificado de no adeudo, emitido por la instancia correspondiente.','number'=>0],  // en caso de que el afiliado haya tenido deudas de otras entidades (Ej: COMIPOL , COVIPOL)
                //['name' => 'Certificado de pago emitido por la entidad correspondiente.','number'=>0],  // en caso de que el afiliado tenga deudas con otras entidades (Ej: COMIPOL , COVIPOL)
                //['name' => 'Conformidad de devolución de descuento por garantía original o fotocopia legalizada.','number'=>0],
                /**/
            ],
            'parameters' => [
              'debt_index' => 80,
              'quantity_ballots' => 3,
              'min_lender_category'=> 0.35,
              'guarantors' => 0,
              'personal_reference' => true,
              'max_lenders' => 2,
              'max_cosigner'=>2
            ],
            'interest' => ['annual_interest' => 9,'penal_interest' => 6]
            ],
           ]
          ],
          'refinanciamiento hipotecario' => [
            'type' => ['module_id' => $module->id,'name'=>'Refinanciamiento Préstamo hipotecario','second_name'=>'R. Hipotecario'],
            'limits' => ['maximum_amount' => 700000,'minimum_amount' => 25001,'maximum_term' => 240,'minimum_term' => 25],
            'modalities' => [
              //REFINANCIAMIENTO DE PRÉSTAMOS A LARGO PLAZO GARANTÍA HIPOTECARIA PARA EL SECTOR ACTIVO //Eliminar en caso de que no proceda
              ['name'=>'Refinanciamiento de Préstamo con garantía hipotecaria de bien inmueble para el sector activo','shortened'=>'PLP-R-GH-SA','requirements'=>[
              ['name' => 'Cedula de Identidad  del(la) Titular (Original y Fotocopia).','number'=>1],
              ['name' => 'Tres Últimas Boleta de Pago del(la) Titular (Original y Fotocopia) y/o Certificado de haberes','number'=>2],
              ['name' => 'Certificado de años de servicio (Original y/o fotocopia legalizada).','number'=>3],
              ['name' => 'Croquis de ubicación del inmueble.','number'=>4],
              ['name' => 'Factura de Agua o Luz del inmueble (Con antigüedad no mayor a 60 días).','number'=>5],
              ['name' => 'Folio Real actualizado (No mayor a 90 días de su emisión).','number'=>6],
              ['name' => 'Información rápida del inmueble – Original (En caso que el Folio Real este desactualizado, mayor a 90 días).','number'=>7],
              ['name' => 'Fotocopia de Testimonio de Propiedad.','number'=>8],
              ['name' => 'Fotocopia de Pago de Impuestos de los 3 últimos años','number'=>9],
              ['name' => 'Fotocopia del Plano del Lote.','number'=>10],
              ['name' => 'Fotocopia del Catastro.','number'=>11],
              ['name' => 'Certificado de Estado Civil o Soltería de todos los participantes del Préstamo (Original).','number'=>12],
              ['name' => 'Estado de cuenta original, vigente y emitido por el Banco Unión S.A.','number'=>13],
              ['name' => 'Formulario SIGEP.','number'=>14],
              ['name' => 'En caso que el cónyuge sea policía debe cumplir con los mismos requisitos que el solicitante, excepto  la documentación de la garantía..','number'=>15],
              //['name' => 'Cédula de Identidad del (la) titular en copia simple','number'=>1],
              //['name' => 'Tres últimas Boletas de pago en copia simple.','number'=>2],
              //['name' => 'Certificado de haberes considerando el último mes percibido.','number'=>2],  // En caso de no contar con la boleta de pago
              //['name' => 'Certificado de años de servicio desglosado en original emitido por el Comando General de la Policía Boliviana','number'=>3],
              //['name' => 'Certificado de años de servicio desglosado en fotocopia Legalizada emitido por el Comando General de la Policía Boliviana','number'=>3],
              //['name' => 'Certificado de años de servicio desglosado en original emitido por el Comando Regional de la Policía Boliviana','number'=>3],
              //['name' => 'Croquis de ubicación del inmueble','number'=>4], //nuevo
              //['name' => 'Factura de Luz del Inmueble','number'=>5], //nuevo
              //['name' => 'Factura de Agua del Inmueble','number'=>6], //nuevo
              //['name' => 'Folio Real','number'=>7], //nuevo
              //['name' => 'Información rapida del Inmueble','number'=>8], //nuevo
              //['name' => 'Fotocopia de Testimonio de Propiedad','number'=>9], //nuevo
              //['name' => 'Fotocopia de Impuestos 3 Ultimos Años','number'=>10], //nuevo
              //['name' => 'Fotocopia de Plano de lote','number'=>11], //nuevo
              //['name' => 'Fotocopia de Catastro','number'=>12], //nuevo
              //['name' => 'Estado de cuenta original, vigente y emitido por el Banco Unión S.A','number'=>13],
              //['name' => 'Certificado de estado civil en original emitido por el SERECI.','number'=>14],
              //['name' => 'Formulario de Calificación y Aprobación de Préstamos FORM/CAYAP/PTMO/UIP/004.','number'=>15],
              /*Estos documentos son solicitados por MUSERPOL en caso de ser requeridos*/
              //['name' => 'Solicitud de aclaración de datos personales en la Boleta de Pago.','number'=>0],  // En caso de que el afiliado tenga datos erroneos en su boleta de pago
              ['name' => 'Certificado de no adeudo, emitido por la instancia correspondiente.','number'=>0],  // en caso de que el afiliado haya tenido deudas de otras entidades (Ej: COMIPOL , COVIPOL)
              //['name' => 'Certificado de pago emitido por la entidad correspondiente.','number'=>0],  // en caso de que el afiliado tenga deudas con otras entidades (Ej: COMIPOL , COVIPOL)
              //['name' => 'Conformidad de devolución de descuento por garantía original o fotocopia legalizada.','number'=>0],
              /**/
            ],
            'parameters' => [
            'debt_index' => 80,
            'quantity_ballots' => 3,
            'min_lender_category'=> 0.35,
            'guarantors' => 0,
            'personal_reference' => true,
            'max_lenders' => 2,
            'max_cosigner'=>2
            ],
            'interest' => ['annual_interest' => 9,'penal_interest' => 6]
            ],
          //REFINANCIAMIENTO DE PRÉSTAMOS A LARGO PLAZO GARANTÍA HIPOTECARIA CPOP * //Eliminar en caso de que no proceda
          ['name'=>'Refinanciamiento de Préstamo con garantía hipotecaria de bien inmueble para el sector activo - CPOP','shortened'=>'PLP-R-GH-CPOP','requirements'=>[
            ['name' => 'Cedula de Identidad  del(la) Titular (Original y Fotocopia).','number'=>1],
              ['name' => 'Tres Últimas Boleta de Pago del(la) Titular (Original y Fotocopia) y/o Certificado de haberes','number'=>2],
              ['name' => 'Certificado de años de servicio (Original y/o fotocopia legalizada).','number'=>3],
              ['name' => 'Croquis de ubicación del inmueble.','number'=>4],
              ['name' => 'Factura de Agua o Luz del inmueble (Con antigüedad no mayor a 60 días).','number'=>5],
              ['name' => 'Folio Real actualizado (No mayor a 90 días de su emisión).','number'=>6],
              ['name' => 'Información rápida del inmueble – Original (En caso que el Folio Real este desactualizado, mayor a 90 días).','number'=>7],
              ['name' => 'Fotocopia de Testimonio de Propiedad.','number'=>8],
              ['name' => 'Fotocopia de Pago de Impuestos de los 3 últimos años','number'=>9],
              ['name' => 'Fotocopia del Plano del Lote.','number'=>10],
              ['name' => 'Fotocopia del Catastro.','number'=>11],
              ['name' => 'Certificado de Estado Civil o Soltería de todos los participantes del Préstamo (Original).','number'=>12],
              ['name' => 'Estado de cuenta original, vigente y emitido por el Banco Unión S.A.','number'=>13],
              ['name' => 'Formulario SIGEP.','number'=>14],
              ['name' => 'En caso que el cónyuge sea policía debe cumplir con los mismos requisitos que el solicitante, excepto  la documentación de la garantía..','number'=>15],
              //['name' => 'Cédula de Identidad del (la) titular en copia simple','number'=>1],
              //['name' => 'Tres últimas Boletas de pago en copia simple.','number'=>2],
              //['name' => 'Certificado de haberes considerando el último mes percibido.','number'=>2],  // En caso de no contar con la boleta de pago
              //['name' => 'Certificado de años de servicio desglosado en original emitido por el Comando General de la Policía Boliviana','number'=>3],
              //['name' => 'Certificado de años de servicio desglosado en fotocopia Legalizada emitido por el Comando General de la Policía Boliviana','number'=>3],
              //['name' => 'Certificado de años de servicio desglosado en original emitido por el Comando Regional de la Policía Boliviana','number'=>3],
              //['name' => 'Croquis de ubicación del inmueble','number'=>4], //nuevo
              //['name' => 'Factura de Luz del Inmueble','number'=>5], //nuevo
              //['name' => 'Factura de Agua del Inmueble','number'=>6], //nuevo
              //['name' => 'Folio Real','number'=>7], //nuevo
              //['name' => 'Información rapida del Inmueble','number'=>8], //nuevo
              //['name' => 'Fotocopia de Testimonio de Propiedad','number'=>9], //nuevo
              //['name' => 'Fotocopia de Impuestos 3 Ultimos Años','number'=>10], //nuevo
              //['name' => 'Fotocopia de Plano de lote','number'=>11], //nuevo
              //['name' => 'Fotocopia de Catastro','number'=>12], //nuevo
              //['name' => 'Estado de cuenta original, vigente y emitido por el Banco Unión S.A','number'=>13],
              //['name' => 'Certificado de estado civil en original emitido por el SERECI.','number'=>14],
              //['name' => 'Formulario de Calificación y Aprobación de Préstamos FORM/CAYAP/PTMO/UIP/004.','number'=>15],
              /*Estos documentos son solicitados por MUSERPOL en caso de ser requeridos*/
              //['name' => 'Solicitud de aclaración de datos personales en la Boleta de Pago.','number'=>0],  // En caso de que el afiliado tenga datos erroneos en su boleta de pago
              ['name' => 'Certificado de no adeudo, emitido por la instancia correspondiente.','number'=>0],  // en caso de que el afiliado haya tenido deudas de otras entidades (Ej: COMIPOL , COVIPOL)
              //['name' => 'Certificado de pago emitido por la entidad correspondiente.','number'=>0],  // en caso de que el afiliado tenga deudas con otras entidades (Ej: COMIPOL , COVIPOL)
              //['name' => 'Conformidad de devolución de descuento por garantía original o fotocopia legalizada.','number'=>0],
              /**/
          ],
          'parameters' => [
            'debt_index' => 80,
            'quantity_ballots' => 3,
            'min_lender_category'=> 0.35,
            'guarantors' => 0,
            'personal_reference' => true,
            'max_lenders' => 2,
            'max_cosigner'=>2
          ],
          'interest' => ['annual_interest' => 9,'penal_interest' => 6]
          ]
          ]
          ],
        ]
      ];
      foreach ($data['procedures'] as $procedure) {
        $new_procedure = ProcedureType::firstOrCreate($procedure['type']);
        $new_procedure->interval()->firstOrCreate($procedure['limits']);
        foreach ($procedure['modalities'] as $modality) {
          $new_modality = ProcedureModality::firstOrCreate([
            'procedure_type_id' => $new_procedure->id,
            'name'=>$modality['name'],
            'shortened'=>$modality['shortened']
          ]);
          foreach ($modality['requirements'] as $requirement) {
            $new_document = ProcedureDocument::firstOrCreate([
              'name'=>$requirement['name']
            ]);
            ProcedureRequirement::firstOrCreate([
              'procedure_modality_id'=>$new_modality->id,
              'procedure_document_id'=>$new_document->id,
              'number'=>$requirement['number']
            ]);
          }
          if (isset($modality['parameters']))
            $new_modality->loan_modality_parameter()->firstOrCreate($modality['parameters']);
          if (isset($modality['interest']))
            $new_modality->loan_interests()->firstOrCreate($modality['interest']);
        }
      }

    }
}
