<template>
  <v-container fluid class="py-0 px-0">
    <ValidationObserver ref="observer">
      <v-form>
        <!--BOTONES CUANDO SE REALICE LA EDICIÓN-->
        <v-row justify="center" >
            <v-col cols="12" class="py-0 px-0">
              <v-container fluid class="py-0 px-0  ">
                <v-row class="py-0">
                  <v-col cols="12" class="py-0">
                    <v-card flat tile class="py-0">
                      <v-card-text class="py-0">
                         <v-tabs vertical>
                          <v-tab class="py-0">
                            <v-icon left>
                              mdi-account
                            </v-icon>
                            EVALUACION DEL TITULAR
                          </v-tab>
                          <v-tab>
                            <v-icon left>
                              mdi-lock
                            </v-icon>
                            GARANTIA
                          </v-tab>
                          <v-tab>
                            <v-icon left>
                              mdi-access-point
                            </v-icon>
                            DATOS DEL PRESTAMO
                          </v-tab>
                          <v-tab>
                            <v-icon left>
                              mdi-account-arrow-left
                            </v-icon>
                            PERSONA DE REFERENCIA
                          </v-tab>
                             <v-tab>
                            <v-icon left>
                              mdi-account-check
                            </v-icon>
                            PERSONA CODEUDORA
                          </v-tab>
                          <v-tab>
                            <v-icon left>
                              mdi-book-open-page-variant
                            </v-icon>
                            DOCUMENTOS PRESENTADOS
                          </v-tab>
                             <v-tab>
                            <v-icon left>
                              mdi-alert
                            </v-icon>
                            OBSERVACIONES
                          </v-tab>
                             <v-tab>
                            <v-icon left>
                              mdi-monitor-cellphone
                            </v-icon>
                            HISTORIAL DEL TRAMITE
                          </v-tab>
                          <v-tab-item>
                            <v-card flat>
                              <v-card-text>
                                <v-row>
                                  <v-col cols="12" md="4" v-show="!calificacion_edit" class="py-0">
                                    <p style="color:teal"><b>MONTO SOLICITADO: </b> {{loan.amount_approved | moneyString}} Bs.</p>
                                  </v-col>
                                  <v-col cols="12" md="4" class="py-0">
                                    <p style="color:teal"><b>PROMEDIO LIQUIDO PAGABLE: </b> {{loan.lenders[0].pivot.payable_liquid_calculated | moneyString }} Bs.</p>
                                  </v-col>
                                  <v-col cols="12" md="4" class="py-0" >
                                    <p style="color:teal"><b>LIQUIDO PARA CALIFICACION: </b> {{loan.liquid_qualification_calculated | moneyString}} Bs.</p>
                                  </v-col>
                                  <v-col cols="12" md="4" v-show="!calificacion_edit" class="py-0">
                                    <p style="color:teal"><b>PLAZO EN MESES:</b>{{' '+loan.loan_term}}</p>
                                  </v-col>
                                  <v-col cols="12" md="4" class="py-0">
                                    <p style="color:teal"><b>TOTAL BONOS:</b> {{loan.lenders[0].pivot.bonus_calculated | moneyString}}</p>
                                  </v-col>
                                  <v-col cols="12" md="4" class="py-0">
                                    <p style="color:teal"><b>INDICE DE ENDEUDAMIENTO:</b> {{loan.indebtedness_calculated|percentage }}% </p>
                                  </v-col>
                                  <v-col cols="12" md="4" class="py-0">
                                    <p style="color:teal"><b>CALCULO DE CUOTA: </b> {{loan.estimated_quota | moneyString}} Bs.</p>
                                  </v-col>
                                  <v-col cols="12" md="12" >
                                    <div v-for="procedure_type in procedure_types" :key="procedure_type.id">
                                      <div v-if="procedure_type.name === 'Préstamo Hipotecario'">
                                        <v-progress-linear></v-progress-linear><br>
                                        <p style="color:teal"><b>CODEUDOR</b></p>
                                          <div v-for="(lenders,i) in loan.lenders" :key="i">
                                            <div  v-if="(lenders,i)>0">
                                              <p style="color:teal"><b>PROMEDIO LIQUIDO PAGABLE:</b> {{lenders.pivot.payable_liquid_calculated | money}}</p>
                                              <p style="color:teal"><b>TOTAL BONOS:</b> {{lenders.pivot.bonus_calculated | money}}</p>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </v-col>
                                  </v-row>
                                </v-card-text>
                              </v-card>
                            </v-tab-item>
                            <v-tab-item>
                              <v-card flat class=" py-0">
                                <v-card-text  class=" py-0">
                                  <v-col cols="12" md="12" color="orange">
                                    <v-card-text class="pa-0 mb-0">
                                      <div v-for="procedure_type in procedure_types" :key="procedure_type.id" class="pa-0 py-0" >
                                        <ul style="list-style: none" class="pa-0" v-if="procedure_type.name == 'Préstamo a Largo Plazo' || procedure_type.name == 'Préstamo a Corto Plazo'|| procedure_type.name == 'Refinanciamiento Préstamo a Corto Plazo' || procedure_type.name == 'Refinanciamiento Préstamo a Largo Plazo'">
                                          <li v-for="guarantor in loan.guarantors" :key="guarantor.id">
                                            <v-col cols="12" md="12" class="pa-0">
                                              <v-row class="pa-2">
                                                <v-col cols="12" md="12" class="py-0">
                                                  <p style="color:teal"><b>GARANTE </b></p>
                                                </v-col>
                                                <v-progress-linear></v-progress-linear><br>
                                                <v-col cols="12" md="3">
                                                  <p><b>NOMBRE:</b> {{$options.filters.fullName(guarantor, true)}}</p>
                                                </v-col>
                                                <v-col cols="12" md="3">
                                                  <p><b>CÉDULA DE IDENTIDAD:</b> {{guarantor.identity_card +" "+ identityCardExt(guarantor.city_identity_card_id) }}</p>
                                                </v-col>
                                                <v-col cols="12" md="3">
                                                  <p><b>TELÉFONO:</b> {{guarantor.cell_phone_number}}</p>
                                                </v-col>
                                                <v-col cols="12" md="3">
                                                  <p><b>PORCENTAJE DE PAGO:</b> {{guarantor.pivot.payment_percentage|percentage }}%</p>
                                                </v-col>
                                                <v-col cols="12" md="3">
                                                  <p><b>LIQUIDO PARA CALIFICACION:</b> {{guarantor.pivot.payable_liquid_calculated | moneyString}}</p>
                                                </v-col>
                                                <v-col cols="12" md="3">
                                                  <p><b>PROMEDIO DE BONOS:</b> {{guarantor.pivot.bonus_calculated| moneyString }}</p>
                                                </v-col>
                                                <v-col cols="12" md="3">
                                                  <p><b>LIQUIDO PARA CALIFICACION CALCULADO:</b> {{guarantor.pivot.liquid_qualification_calculated | moneyString}}</p>
                                                </v-col>
                                                <v-col cols="12" md="3">
                                                  <p><b>INDICE DE ENDEUDAMIENTO CALCULADO:</b> {{guarantor.pivot.indebtedness_calculated|percentage }}%</p>
                                                </v-col>
                                              </v-row>
                                            </v-col>
                                          </li>
                                          <br>
                                          <p  style="color:teal" v-if="loan.guarantors.length==0" ><b> NO TIENE GARANTES </b></p>
                                        </ul>
                                        <v-col cols="12" md="12" v-if="procedure_type.name == 'Préstamo Hipotecario' || procedure_type.name == 'Refinanciamiento Préstamo Hipotecario'">
                                          <p style="color:teal"><b>GARANTIA HIPOTECARIA </b></p>
                                          <v-row>
                                            <v-progress-linear></v-progress-linear><br>
                                            <v-col cols="12" md="4">
                                              <v-select
                                                dense
                                                :readonly="true"
                                                :items="city"
                                                item-text="name"
                                                item-value="id"
                                                label="CIUDAD"
                                                v-model="loan_properties.real_city_id"
                                              ></v-select>
                                            </v-col>
                                            <v-col cols="12" md="4">
                                              <v-text-field
                                                :readonly="true"
                                                :label="'UBICACION'"
                                                dense
                                                v-model="loan_properties.location"
                                              ></v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="4">
                                              <v-text-field
                                                :readonly="true"
                                                :label="'NUMERO DE LOTE'"
                                                dense
                                                v-model="loan_properties.land_lot_number"
                                              ></v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="1">
                                              <v-text-field
                                                :readonly="true"
                                                :label="'SUPERFICIE'"
                                                dense
                                                v-model="loan_properties.surface"
                                              ></v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="3">
                                              <v-select
                                                :readonly="true"
                                                dense
                                                :items="items_measurement"
                                                item-text="name"
                                                item-value="value"
                                                label="Unidad de medida"
                                                v-model="loan_properties.measurement"
                                              ></v-select>
                                            </v-col>
                                            <v-col cols="12" md="4">
                                              <v-text-field
                                                :readonly="true"
                                                :label="'CODIGO CATASTRAL'"
                                                dense
                                                v-model="loan_properties.cadastral_code"
                                              ></v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="4">
                                              <v-text-field
                                                :readonly="true"
                                                :label="'NRO MATRICULA'"
                                                dense
                                                v-model="loan_properties.registration_number"
                                              ></v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="4">
                                              <v-text-field
                                                :readonly="true"
                                                :label="'NRO FOLIO REAL'"
                                                dense
                                                v-model="loan_properties.real_folio_number"
                                              ></v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="4">
                                              <p><b>VNR: </b>{{ loan_properties.net_realizable_value}} </p>
                                            </v-col>
                                            <v-col cols="12" md="6">
                                              <v-text-field
                                                :readonly="true"
                                                :label="'VALOR COMERCIAL'"
                                                dense
                                                v-model="loan_properties.commercial_value"
                                              ></v-text-field>
                                            </v-col>
                                            <v-col cols="12" md="6">
                                              <v-text-field
                                                :readonly="true"
                                                :label="'VALOR DE RESCATE HIPOTECARIO'"
                                                dense
                                                v-model="loan_properties.rescue_value"
                                              ></v-text-field>
                                            </v-col>
                                          </v-row>
                                        </v-col>
                                        <ul style="list-style: none" class="pa-0 py-4" v-if="procedure_type.name == 'Préstamo Anticipo'">
                                          <p  style="color:teal" > <b>NO TIENE GARANTES</b></p>
                                        </ul>
                                      </div>
                                    </v-card-text>
                                  </v-col>
                                </v-card-text>
                              </v-card>
                            </v-tab-item>
                            <v-tab-item>
                              <v-card flat>
                                <v-card-text>
                                  <v-col cols="12" md="6" class="py-0" >
                                    <p style="color:teal"><b>DATOS DEL CONTRATO</b></p>
                                  </v-col>
                                  <v-progress-linear></v-progress-linear>
                                   <v-row>
                                    <v-col cols="12" md="6">
                                      <v-text-field
                                        dense
                                        v-model="loan.delivery_contract_date"
                                        label="FECHA ENTREGA DE CONTRATO"
                                        hint="Día/Mes/Año"
                                        type="date"
                                        :outlined="edit_delivery_date"
                                        :readonly="!edit_delivery_date"
                                      ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="6">
                                      <v-text-field
                                        dense
                                        v-model="loan.return_contract_date"
                                        label="FECHA RECEPCION DE CONTRATO"
                                        hint="Día/Mes/Año"
                                        type="date"
                                        :outlined="edit_return_date"
                                        :readonly="!edit_return_date"
                                      ></v-text-field>
                                    </v-col>
                                    </v-row>
                                    <v-col cols="12" class="pa-0">
                                      <v-progress-linear></v-progress-linear>
                                        <p style="color:teal"> <b>DATOS DE DESEMBOLSO</b></p>
                                      <v-progress-linear></v-progress-linear>
                                      <v-row>
                                        <v-col cols="12" md="4">
                                          <p><b>TIPO DE DESEMBOLSO:</b> {{loan.payment_type.name}}</p>
                                        </v-col>
                                        <v-col cols="12" md="3" v-show="loan.payment_type.name=='Depósito Bancario'">
                                          <p><b>ENTIDAD FINANCIERA:</b>{{' '+cuenta}}</p>
                                        </v-col>
                                        <v-col cols="12" md="3" v-show="loan.payment_type.name=='Depósito Bancario'">
                                          <p><b>NUMERO DE CUENTA:</b>{{' '+loan.lenders[0].account_number}}</p>
                                        </v-col>
                                        <v-col cols="12" md="3" v-show="loan.payment_type.name=='Depósito Bancario'">
                                          <p><b>CUENTA SIGEP:</b> {{' '+loan.lenders[0].sigep_status}}</p>
                                        </v-col>
                                        <v-col cols="12" md="4">
                                          <div class="py-0">
                                            <v-text-field
                                              dense
                                              :readonly="true"
                                              :label="'CERTIFICACIÓN PRESUPUESTARIA CONTABLE'"
                                              v-model="loan.num_accounting_voucher"
                                            ></v-text-field>
                                          </div>
                                        </v-col>
                                        <v-col cols="12" md="4">
                                          <v-text-field
                                            dense
                                            v-model=" loan.disbursement_date"
                                            label="FECHA DE DESEMBOLSO"
                                            hint="Día/Mes/Año"
                                            type="date"
                                            :readonly="true"
                                          ></v-text-field>
                                      </v-col>
                                    </v-row>
                                  </v-col>
                                  <v-col cols="12" md="6" class="pb-0" v-show="loan_refinancing.refinancing">
                                    <p style="color:teal"><b>DATOS DEL PRéSTAMO A REFINANCIAR{{' => '+ loan_refinancing.description}}</b></p>
                                  </v-col>
                                  <v-progress-linear v-show="loan_refinancing.refinancing"></v-progress-linear  >
                                  <v-row v-show="loan_refinancing.refinancing">
                                    <v-col cols="12" md="4" class="py-2">
                                      <p><b>Codigo de Prestamo a Padre:</b>{{' '+loan_refinancing.code}}</p>
                                    </v-col>
                                    <v-col cols="12" md="4" class="py-2" >
                                      <p><b>Monto de Préstamo Padre:</b> {{loan_refinancing.amount_approved_son | money}}</p>
                                    </v-col>
                                    <v-col cols="12" md="4" class="py-2">
                                      <p><b>Plazo de Préstamo Padre:</b>{{' '+loan_refinancing.loan_term}}</p>
                                    </v-col>
                                    <v-col cols="12" md="4" class="py-0">
                                      <p><b>Cuota de Préstamo Padre:</b> {{loan_refinancing.estimated_quota | money}}</p>
                                    </v-col>
                                    <v-col cols="12" md="4" class="py-0"  v-show="!cobranzas_edit_sismu" v-if="loan_refinancing.type_sismu==true">
                                      <p><b>Fecha de Corte :</b> {{loan_refinancing.date_cut_refinancing ? loan_refinancing.date_cut_refinancing : 'Sin registar'}}</p>
                                    </v-col>
                                    <v-col cols="12" md="4"  v-show="cobranzas_edit_sismu "  class="py-0">
                                      <v-text-field
                                        dense
                                        v-model="loan_refinancing.date_cut_refinancing"
                                        label="Fecha de Corte"
                                        hint="Día/Mes/Año"
                                        type="date"
                                        :outlined="true"
                                    ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="4" class="py-0" v-show="!cobranzas_edit_sismu">
                                      <p><b>Saldo de Prestamo a Refinanciar:</b> {{loan_refinancing.balance | money}}</p>
                                    </v-col>
                                    <v-col cols="12" md="4" v-show="cobranzas_edit_sismu " class="py-0" >
                                      <v-text-field
                                        dense
                                        label="Saldo de Prestamo a Refinanciar"
                                        v-model="loan_refinancing.balance"
                                      :outlined="true"
                                      ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="4" class="py-0" >
                                      <p class="success--text"><b>Monto Solicitado del Prestamo Nuevo:</b> {{loan_refinancing.amount_approved | money}}</p>
                                    </v-col>
                                    <v-col cols="12" md="4" class="py-0" >
                                      <p class="success--text"><b>Saldo Anterior de la Deuda:</b> {{loan_refinancing.balance_parent_loan_refinancing | money}}</p>
                                    </v-col>
                                    <v-col cols="12" md="4" class="py-0" >
                                      <p class="success--text"><b>Monto del Refinanciamiento:</b> {{loan_refinancing.refinancing_balance | money}}</p>
                                    </v-col>
                                  </v-row>
                                </v-card-text>
                              </v-card>
                            </v-tab-item>
                            <v-tab-item>
                              <v-card flat>
                                <v-card-text>
                                      <p style="color:teal" v-if="loan.personal_references.length>0"><b>PERSONA DE REFERENCIA </b></p>
                                        <v-progress-linear v-if="loan.personal_references.length>0"></v-progress-linear><br>
                                        <v-data-table
                                          dense
                                          v-if="loan.personal_references.length>0"
                                          :headers="headers"
                                          :items="loan.personal_references"
                                          hide-default-footer
                                          >
                                        </v-data-table>
                                        <p v-if="loan.personal_references.length==0" style="color:teal"> <b>NO TIENE PERSONA DE REFERENCIA</b></p>
                                                      
                                </v-card-text>
                              </v-card>
                            </v-tab-item>
                                <v-tab-item>
                              <v-card flat>
                                <v-card-text>
                                  <p style="color:teal" v-if="loan.personal_references.length>0"><b>CODEUDOR NO AFILIADO </b></p>
                                <v-card flat tile>
                              <v-card-text>
                              <p v-if="loan.cosigners.length>0"><b>CODEUDOR NO AFILIADO</b></p>
                              <v-progress-linear v-if="loan.cosigners.length>0"></v-progress-linear><br>
                              <v-data-table
                                v-if="loan.cosigners.length>0"
                                :headers="headers"
                                :items="loan.cosigners"
                                dense
                                >
                              </v-data-table>
                             <p v-if="loan.cosigners.length==0" > <b>NO TIENE CODEUDORES</b></p>
                            </v-card-text>
                              </v-card>
                              </v-card-text>
                            </v-card>
                          </v-tab-item>
                            <v-tab-item>
                            <v-card flat>
                              <v-card-text>
                                <DocumentsFlow>
                                </DocumentsFlow>
                              </v-card-text>
                            </v-card>
                          </v-tab-item>
                            <v-tab-item>
                            <v-card flat>
                              <v-card-text>
                                <ObserverFlow>
                                </ObserverFlow>
                              </v-card-text>
                            </v-card>
                          </v-tab-item>
                          <v-tab-item>
                            <v-card flat>
                              <v-card-text>
                                <HistoryFlow>
                                </HistoryFlow>
                              </v-card-text>
                            </v-card>
                          </v-tab-item>
                        </v-tabs>
                    </v-card-text>
                  </v-card>
                 </v-col>
                </v-row>
              </v-container>
            </v-col>
          </v-row>
        <!--/v-card-->
      </v-form>
    </ValidationObserver>
  </v-container>
</template>
<script>

import DocumentsFlow from "@/components/tracing/DocumentsFlow"
import ObserverFlow from "@/components/tracing/ObserverFlow"
import HistoryFlow from "@/components/tracing/HistoryFlow"
export default {
  components: {
   DocumentsFlow,
   ObserverFlow,
   HistoryFlow
  },
  name: "specific-data-loan",
  props: {
    loan_refinancing: {
      type: Object,
      required: true
    },
    loan: {
      type: Object,
      required: true
    },
    loan_properties: {
      type: Object,
      required: true
    },
    procedure_types: {
      type: Object,
      required: true
    },
    /*validate:{
      type: Object,
      required: false
    }*/
  },
   data: () => ({
     civil_statuses: [
      { name: "Soltero", value: "S" },
      { name: "Casado", value: "C" },
      { name: "Viudo", value: "V" },
      { name: "Divorciado", value: "D" }
    ],
    items_measurement: [
      { name: "Metros cuadrados", value: "METROS CUADRADOS" },
      { name: "Hectáreas", value: "HECTÁREAS" }
    ],
    genders: [
      {
        name: "Femenino",
        value: "F"
      },
      {
        name: "Masculino",
        value: "M"
      }
    ],
      dialog: false,
      dialog1: false,
      calificacion_edit:false,
      cobranzas_edit:false,
      cobranzas_edit_sismu:false,
      edit_return_date : false,
      edit_delivery_date : false,
      editedIndex: -1,
      editedItem: {},
      defaultItem: {},
      editedItem1: {},
      defaultItem1: {},
      headers: [
        {
          text: 'PRIMER NOMBRE',
          align: 'start',
          sortable: false,
          value: 'first_name',
        },
        { text: 'SEGUNDO NOMBRE',  value: 'second_name' },
        { text: 'PRIMER APELLIDO ', value: 'last_name' },
        { text: 'SEGUNDO APELLIDO ', value: 'mothers_last_name' },
        { text: 'TELÉFONO', value: 'phone_number' },
        { text: 'CELULAR', value: 'cell_phone_number' },
        { text: 'DIRECCION ', value: 'address' },
      ],
    editable1: false,
    editable: false,
    reload: false,
    payment_types:[],
    city: [],
    entity: [],
    entities:null,
  }),
  beforeMount(){
    this.getCity()
    this.getEntity()
  },
  computed: {
      //Metodo para obtener Permisos por rol
      permissionSimpleSelected () {
        return this.$store.getters.permissionSimpleSelected
      },
      cuenta() {
       for (this.i = 0; this.i< this.entity.length; this.i++) {
        if(this.loan.lenders[0].financial_entity_id==this.entity[this.i].id)
        {
          this.entities= this.entity[this.i].name
        }
      }
      return this.entities
    }
  },
  methods:{

    async getEntity() {
      try {
        this.loading = true
        let res = await axios.get(`financial_entity`)
        this.entity = res.data
        console.log("ciudad "+ this.entity)
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    },
    async getCity() {
      try {
        this.loading = true
        let res = await axios.get(`city`)
        this.city = res.data
        console.log("ciudad "+ this.city)
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    },
    identityCardExt(id){
      let ext
      if(id != null){
        for(let i=0; i<this.city.length;i++){
          if(this.city[i].id == id){
            ext = this.city[i].first_shortened
          }  
        }
      return ext
      }else{
        return ''
      }
    },
  }
}
</script>