<template>
  <v-container fluid >
    <v-row justify="center"  v-show="modalidad.procedure_type_name!= 'Préstamo hipotecario'">
         <v-col cols="12" class="py-0" v-if="modalidad_guarantors == 0">
          <v-card>
            <v-container class="py-0">
              <v-col class="text-center">
                <h2 class="success--text" >ESTA MODALIDAD NO NECESITA GARANTE </h2>
              </v-col>
            </v-container>
          </v-card>
         </v-col>
      <v-col cols="4" class="py-0" v-if="modalidad_guarantors > 0" >
        <v-card>
          <v-container class="py-0">
            <v-row>
              <v-col cols="12" md="4"></v-col>
              <v-col cols="12" md="6" >
                Afiliado
              </v-col>
              <v-col cols="12" md="2"></v-col>
              <v-col cols="12" md="1"></v-col>
              <v-col cols="12" md="8" >
                <v-text-field
                  label="C.I."
                  v-model="guarantor_ci"
                  class="py-0"
                  single-line
                  hide-details
                  clearable
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="2">
                <v-tooltip>
                  <template v-slot:activator="{ on }">
                    <v-btn
                      fab
                      dark
                      x-small
                      v-on="on"
                      color="info"
                      @click.stop="activar()">
                      <v-icon>mdi-magnify</v-icon>
                    </v-btn>
                  </template>
                </v-tooltip>
              </v-col>
            </v-row>
          </v-container>
        </v-card>
        <br>
        <v-card v-show="show_calculated">
          <v-container class="py-0">
            <v-row>
              <v-col cols="12" md="12" class="pb-0">
                <v-layout row wrap>
                  <v-flex xs12 class="px-2">
                    <fieldset class="pa-3">
                      <v-row>
                        <v-col cols="12" md="5" class="pb-0">
                          <v-text-field
                            :outlined="editar"
                            :readonly="!editar"
                            dense
                            class="py-0"
                            label="Boleta de Pago"
                            v-model="payable_liquid[0]"
                          ></v-text-field>
                        </v-col>
                        <!--v-col cols="12" md="2"  v-show="retroceder_meses">
                          <v-tooltip>
                            <template v-slot:activator="{ on }">
                              <v-btn
                               
                                dark
                                x-small
                                v-on="on"
                                style="width:15px; height:15px"
                                color="info"
                                @click.stop="retrocederContribusiones()">
                                <v-icon x-small> mdi-minus</v-icon>
                              </v-btn>
                            </template>
                          </v-tooltip>
                        </-v-col>
                        <v-col cols="12" md="2" class="pb-0"  v-show="retroceder_meses">
                          <v-text-field
                            v-model="cantidad_boletas"
                            class="mt-0 pt-0"
                            type="number"
                            style="width: 60px"
                            :readonly="true"
                          ></v-text-field>
                        </v-col>
                         <v-col-- cols="12" md="1"  v-show="retroceder_meses">
                          <v-tooltip>
                            <template v-slot:activator="{ on }">
                              <v-btn
                                fab
                                dark
                                x-small
                                v-on="on"
                                style="width:25px; height:25px"
                                color="info"
                                @click.stop="retrocederContribusiones()">
                                <v-icon>mdi-plus</v-icon>
                              </v-btn>
                            </template>
                          </v-tooltip>
                        </v-col-->
                                       <v-col cols="12" md="3" v-show="show_ajuste" >
               <label><small  class=" caption" > Meses atras</small> </label>
                  </v-col>
                           <v-col cols="12" md="3" v-show="show_ajuste" >
                 <v-row class="py-0">
                    <v-text-field
                      dense
                      v-model="number_diff_month"
                      color="info"
                      append-icon="mdi-plus-box"
                      prepend-icon="mdi-minus-box"
                      @click:append="appendIconCallback"
                      @click:prepend="prependIconCallback"
                      readonly
                    ></v-text-field>
                 </v-row>
                  </v-col>
                        <v-col cols="12" md="4" v-show="show_ajuste">
                          <v-text-field
                            dense
                            v-model="monto_ajustable"
                            :outlined="show_ajuste"
                            class="mt-0 pt-0"
                            label="Monto Ajustable"
                            type="text"
                            style="width: 90px"
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="8" v-show="show_ajuste">
                         <v-text-field
                            dense
                            v-model="monto_ajustable_descripcion"
                            :outlined="show_ajuste"
                            class="mt-0 pt-0"
                            label="Descripcion Monto Ajustable"
                            type="text"
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="6" class="pb-0" v-show="comision">
                          <v-text-field
                            :outlined="editarComision"
                            :readonly="!editarComision"
                            dense
                            class="py-0"
                            v-model="periodo"
                            label="Periodo"
                          ></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row >
                        <v-col cols="12" md="6" class="pb-0" v-show="contribusion">
                          <v-text-field
                            :outlined="editar"
                            :readonly="!editar"
                            dense
                            class="py-0"
                            v-model="bonos[0]"
                            label="Bono Frontera"
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="6" class="pb-0" v-show="contribusion" >
                          <v-text-field
                            :outlined="editar"
                            :readonly="!editar"
                            dense
                            class="py-0"
                            v-model="bonos[1]"
                            label="Bono Oriente"
                          ></v-text-field>
                        </v-col>
                      <v-col cols="12" md="6" class="pb-0" v-show="contribusion" >
                        <v-text-field
                          :outlined="editar"
                          :readonly="!editar"
                          dense
                          class="py-0"
                          v-model="bonos[2]"
                          label="Bono Cargo"
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12" md="6" class="pb-0" v-show="contribusion">
                        <v-text-field
                          :outlined="editar"
                          :readonly="!editar"
                          dense
                          class="py-0"
                          v-model="bonos[3]"
                          label="Bono Seguridad"
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12" md="6" class="pb-0" v-show="pasivo">
                        <v-text-field
                          :outlined="editarPasivo"
                          :readonly="!editarPasivo"
                          dense
                          class="py-0"
                          v-model="bonos[0]"
                          label="Bono Renta Dignidad"
                        ></v-text-field>
                      </v-col>
                    </v-row>
                      </fieldset>
                    </v-flex>
                  </v-layout>
                </v-col>
                <v-col cols="12" md="7" class="py-0" ></v-col>
                <v-col cols="12" md="4" class="py-0">
                  <v-btn
                  class="py-0"
                    color="info"
                    small
                       rounded
                    @click="calculator()">Calcular
                  </v-btn>
                </v-col>
              </v-row>
            </v-container>
          </v-card>
        </v-col>
        <v-col cols="8" class="py-0" v-if="modalidad_guarantors>0">
          <v-card v-show="show_garante">
            <v-container v-if="modalidad_guarantors>0">
              <v-row>
                <v-col class="text-center">
                    <h4 class="error--text" > CANTIDAD DE GARANTES QUE NECESITA ESTA MODALIDAD:{{modalidad_guarantors}}<br>
                  EL GARANTE DEBE ESTAR ENTRE UNA CATEGORIA DE {{loan_detail.min_guarantor_category}} A {{loan_detail.max_guarantor_category}} </h4>
                </v-col>
              </v-row>
            </v-container>
          </v-card>
          <v-card v-show="!show_garante" >
            <v-container class="py-0">
              <v-row>
                <v-col cols="12" md="4"></v-col>
                <v-col cols="12" md="6"  class="py-0" >
                  <h3 class="red--text" v-show="!validated">NO PUEDE SER GARANTE</h3>
                  <h3 class="success--text" v-show="validated1"> PUEDE SER GARANTE</h3>
                  <!--{{affiliate_garantor.guarantor_information}}-->
                </v-col>
                <v-col cols="12" md="12" class="py-0" v-show="affiliate_garantor.affiliate.cpop">
                  <h5 class="success--text text-center">AFILIADO CPOP</h5>
                </v-col>
                <v-col cols="12" md="6" class="ma-0 pb-0 font-weight-light caption">
                  AFILIADO :{{affiliate_garantor.affiliate.full_name}}
                </v-col>
                <v-col cols="12" md="3" class="ma-0 pb-0 font-weight-light caption" >
                  C.I :{{affiliate_garantor.affiliate.identity_card_ext}}
                </v-col>
                <v-col cols="12" md="3" class="ma-0 pb-0 font-weight-light caption" >
                  CATEGORIA:   {{affiliate_garantor.affiliate.category.name}}
                </v-col>
                <v-col cols="12" md="6" class="py-0 font-weight-light caption" >
                  MATRICULA:{{affiliate_garantor.affiliate.registration}}
                </v-col>
                <v-col cols="12" md="6" class="text-uppercase py-0 font-weight-light caption">
                  ESTADO:{{affiliate_garantor.affiliate.affiliate_state.name}}
                </v-col>
                <v-col cols="12" md="8" class="font-weight-black caption py-0" >
                  PRESTAMOS QUE ESTA GARANTIZANDO:
                </v-col>
                <v-col cols="12" md="2" class="font-weight-black caption py-0" >
                  {{affiliate_garantor.active_guarantees_quantity}}
                </v-col>
                <v-col cols="12" md="8" class="font-weight-black caption py-1">
                  PRESTAMOS VIGENTES QUE TIENE EL AFILIADO:
                </v-col>
                 <v-col cols="12" md="2" class="font-weight-black caption py-1">
                 {{loan.length}}
                </v-col>
                <v-col cols="12" class="py-0" v-show="loan.length>0">
                  <v-data-table
                    dense
                    :headers="headers"
                    :items="loan"
                    :items-per-page="4"
                    hide-default-footer
                  >
                  </v-data-table>
                </v-col>
              </v-row>
            </v-container>
          </v-card>
          <br>
          <v-card v-show="show_result">
            <v-container >
              <v-row>
                <v-col cols="12" md="12" >
                  <v-card-text class="py-0">
                    <v-layout row wrap>
                      <v-flex xs6 class="px-2">
                        <fieldset class="pa-3" >
                          <h1 class="caption" >{{'Cantidad de garantes faltantes a añadir: '}}{{modalidad_guarantors-garantes_detalle.length}}</h1>
                          <v-progress-linear></v-progress-linear>
                          <h1 class="caption" v-show="cont=0">{{'Calcular el porcentaje de pago del garante'}}</h1>
                          <p class="py-0 mb-0 caption" v-show="show_data">Liquido Total:{{evaluate_garantor.payable_liquid_calculated +' '}}<br>
                           Total de Bonos:{{evaluate_garantor.bonus_calculated +' '}}<br>
                           Liquido para la Calificacion:{{evaluate_garantor.liquid_qualification_calculated }}</p>
                          <p class="py-0 mb-0 caption" v-show="show_data">Indice de Endeudamiento: {{evaluate_garantor.indebtnes_calculated+'% '}}<br>{{'Monto Ajustable:'+ monto_ajustable}}<br> <b>{{evaluate_garantor.is_valid?'Cubre la Cuota ':'No Cubre la Cuota'}}</b></p>
                          <div class="text-right"  v-show="evaluate_garantor.is_valid">
                            <v-btn
                              v-if="show_data"
                              v-show="garantes_detalle.length < modalidad_guarantors"
                              x-small
                              class="py-0"
                              color="info"
                              rounded
                              @click="añadir()">Añadir Garante
                            </v-btn>
                          </div>
                        </fieldset>
                      </v-flex>
                      <v-flex xs6 class="px-2" >
                        <fieldset class="pa-3 caption">
                          {{'Cantidad de garantes a añadir: '+modalidad_guarantors}}
                              <v-progress-linear></v-progress-linear>
                          <div
                            class="align-end font-weight-light ma-0 pa-0"
                            v-for="(otherDoc, index) of garantes_detalle"
                            :key="index"
                          >
                            {{index+1 +". "}} {{otherDoc}}
                            <v-btn text icon color="error" @click.stop="deleteOtherDocument(index)">X</v-btn>
                              <v-divider></v-divider>
                          </div>
                          <div class="text-right"  v-if="modalidad_guarantors==garantes_detalle.length">
                            <v-btn
                              class="py-0"
                              color="info"
                              rounded
                               x-small
                              @click="simulador()">Calculo de Cuota
                            </v-btn>
                          </div>
                        </fieldset>
                      </v-flex>
                    </v-layout>
                  </v-card-text>
                </v-col>
                <v-col cols="12" md="12" v-show="show_simulador">
                  <v-card-text class="py-0">
                    <v-layout row wrap>
                      <v-flex xs12 class="px-2">
                        <fieldset class="pa-3">
                          <p class="py-0 mb-0 caption">Monto del Prestamo: {{loan_detail.amount_requested +' '}}<b>|</b> Plazo del Prestamo:{{loan_detail.months_term+' '}}<b>|</b> Cuota del Titular:{{simulator_guarantors.quota_calculated_estimated_total  }}</p>
                            <ul style="list-style: none" class="pa-0">
                              <li v-for="(afiliados,i) in simulator_guarantors.affiliates" :key="i" >
                                <v-progress-linear></v-progress-linear>
                                <p class="py-0 mb-0 caption">Nombre del Afiliado: {{ garantes_detalle[i]}}</p>
                                <p class="py-0 mb-0 caption">Cuota: {{afiliados.quota_calculated+"  "}}{{"  "+"Indice de Endeudamiento:"+afiliados.indebtedness_calculated}}{{" "}}Porcentaje de Pago: {{" "+afiliados.payment_percentage}}%</p>
                              </li>
                            </ul>
                        </fieldset>
                      </v-flex>
                    </v-layout>
                  </v-card-text>
                </v-col>
              </v-row>
            </v-container>
          </v-card>
        </v-col>
      </v-row>
      <v-card>
      </v-card>
    </v-container>
</template>
<script>

  export default {
  name: "loan-guarantor",
   props: {
    loan_detail: {
      type: Object,
      required: true
    },
    guarantors: {
      type: Array,
      required: true
    },
     affiliate: {
      type: Object,
      required: true
    },
    modalidad_guarantors: {
      type: Number,
      required: true,
      default: 0
    },
    modalidad_id: {
      type: Number,
      required: true,
      default: 0
    },
    modalidad: {
      type: Object,
      required: true
    }
  },
  data: () => ({
    guarantor_ci:null,
    affiliate_garantor:{
      affiliate:{
        category:{},
        affiliate_state:{}
      },
    },
    valido:true,
    monto_ajustable_descripcion:null,
    number_diff_month:1,
    show_ajuste:true,
    contribusion:true,
    comision:false,
    periodo:null,
    pasivo:false,
    editarComision:false,
    retroceder_meses:false,
    prueba:false,
    editar:true,
    editarPasivo:true,
    contributionable:[],
    data_ballots_state_affiliate:null,
    loan_contributions_adjust_ids:[],
    periodo:null,


    cantidad_boletas:1,
    monto_ajustable:0,
    ex4:true,

    data_ballots_name:null,

    show_data:true,
    show_simulador:false,
    garante_boletas:{},
    data_ballots:[],
    show_garante:true,
    show_calculated:false,
    show_result:false,
    simulator_guarantors:{},
    loan:[],
    index: [],
    guarantor_objeto:{},
    garantes_detalle:[],
    garantes_simulador:[],
    validated:true,
    validated1:false,
    payable_liquid:[0],
    bonos:[0,0,0,0],
    evaluate_garantor:{},
    headers: [
      {
        text: "Codigo",
        class: ["normal", "white--text"],
        align: "left",
        value: "code"
      },
      {
        text: "Monto Aprovado",
        class: ["normal", "white--text"],
        align: "left",
        value: "amount_approved"
      },
      {
        text: "Plazo",
        class: ["normal", "white--text"],
        align: "left",
        value: "loan_term"
      },
       {
        text: "Cuota",
        class: ["normal", "white--text"],
        align: "left",
        value: "estimated_quota"
      }
    ],
  }),
 watch:{
ver()
{
  añadir()
}
 },
  methods: {
    async clear()
    {
      this.editar=true
      this.payable_liquid[0]=0,
      this.bonos[0]=0
      this.bonos[1]=0
      this.bonos[2]=0
      this.bonos[3]=0
      this.monto_ajustable=0
      this.monto_ajustable_descripcion=null
      this.number_diff_month=1
    },
    deleteOtherDocument(i) {
      this.show_simulador=false
      this.garantes_detalle.splice(i, 1)
      this.garantes_simulador.splice(i, 1)
      this.guarantors.splice(i, 1)
      this.contributionable.splice(i, 1)
      this.loan_contributions_adjust_ids.splice(i, 1)

    },
     async calculator() {
      try {
        if(this.affiliate_garantor.guarantor_information==true)
        {
          if(this.monto_ajustable>0){
            if(this.monto_ajustable_descripcion)
            {
              this.show_result=true
              this.show_data=true
              // this.payable_liquid[0]=parseFloat(this.payable_liquid[0]) + parseFloat(this.monto_ajustable)
              let res = await axios.post(`evaluate_garantor`, {
                procedure_modality_id:this.modalidad_id,
                affiliate_id:this.affiliate_garantor.affiliate.id,
                quota_calculated_total_lender:this.loan_detail.quota_calculated_total_lender,
                contributions: [
                {
                  payable_liquid: parseFloat(this.payable_liquid[0]) + parseFloat(this.monto_ajustable),
                  position_bonus:  this.bonos[2],
                  border_bonus: this.bonos[0],
                  public_security_bonus: this.bonos[3],
                  east_bonus:this.bonos[1]
                }
                ]
              })
              this.evaluate_garantor= res.data
              this.loan_detail.simulador=false
            }else{
              this.toastr.error("Tiene que ingresar la descripcion del ajuste.")
            }
          }else{
            this.show_result=true
            this.show_data=true
            // this.payable_liquid[0]=parseFloat(this.payable_liquid[0]) + parseFloat(this.monto_ajustable)
            let res = await axios.post(`evaluate_garantor`, {
              procedure_modality_id:this.modalidad_id,
              affiliate_id:this.affiliate_garantor.affiliate.id,
              quota_calculated_total_lender:this.loan_detail.quota_calculated_total_lender,
              contributions: [
              {
                payable_liquid: parseFloat(this.payable_liquid[0]) + parseFloat(this.monto_ajustable),
                position_bonus:  this.bonos[2],
                border_bonus: this.bonos[0],
                public_security_bonus: this.bonos[3],
                east_bonus:this.bonos[1]
              }
              ]
            })
            this.evaluate_garantor= res.data
            this.loan_detail.simulador=false
          }
        }else{
          this.toastr.error("Tiene que actualizar los datos personales del Garante.")
        }
      }catch (e) {
        console.log(e)
      }finally {
        this.loading = false
      }
    },
    async simulador() {
      try {
        this.show_simulador=true
        let res = await axios.post(`simulator`, {
          procedure_modality_id: this.modalidad_id,
          amount_requested: this.loan_detail.amount_requested,
          months_term: this.loan_detail.months_term,
          guarantor: true,
          liquid_qualification_calculated_lender: this.loan_detail.liquid_qualification_calculated + this.monto_ajustable,
          liquid_calculated:this.garantes_simulador
        })
        this.simulator_guarantors = res.data
        for (this.j = 0; this.j< this.guarantors.length; this.j++) {
          this.guarantors[this.j].payment_percentage=this.simulator_guarantors.affiliates[this.j].payment_percentage
          this.guarantors[this.j].indebtedness_calculated=this.simulator_guarantors.affiliates[this.j].indebtedness_calculated
          this.guarantors[this.j].liquid_qualification_calculated=this.simulator_guarantors.affiliates[this.j].liquid_qualification_calculated
        }
      this.loan_detail.simulador=true
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    },

    async activar()
    {
      this.clear()
      try {
        if(this.guarantor_ci==this.affiliate.identity_card)
        {
          this.toastr.error("El garante no puede tener el mismo carnet que el titular.")
        }
        else{
          let resp = await axios.post(`affiliate_guarantor`,{
            identity_card: this.guarantor_ci,
            procedure_modality_id:this.modalidad_id,
          })
          this.affiliate_garantor=resp.data
          let res = await axios.get(`affiliate/${this.affiliate_garantor.affiliate.id}/contribution`, {
          params:{
            city_id: this.$store.getters.cityId,
            choose_diff_month: 1,
            number_diff_month:1,
            sortBy: ['month_year'],
            sortDesc: [1],
            per_page: 1,
            page: 1,
            }
          })
          this.data_ballots=res.data.data
          this.data_ballots_name=res.data.name_table_contribution
          this.data_ballots_state_affiliate=res.data.state_affiliate
          this.periodo=this.$moment(res.data.current_tiket).format('YYYY-MM-DD')
          this.valido=res.data.valid
          if(res.data.name_table_contribution=='contributions')
          {
           // this.toastr.error("afiliado que pertenece a contribution")
            if(res.data.valid)
            {
              this.editar=false
              this.contribusion=true
              this.comision=false
              this.pasivo=false
              this.retroceder_meses=true
              this.show_ajuste=true
              this.payable_liquid[0] = this.data_ballots[0].payable_liquid,
              this.bonos[0] = this.data_ballots[0].border_bonus,
              this.bonos[1] = this.data_ballots[0].east_bonus,
              this.bonos[2] = this.data_ballots[0].position_bonus,
              this.bonos[3] = this.data_ballots[0].public_security_bonus
            } else{
              this.editar=false
              this.contribusion=true
              this.comision=false
              this.show_ajuste=true
              this.pasivo=false
              this.retroceder_meses=true
              this.payable_liquid[0] = this.payable_liquid[0]
              this.bonos[0] = this.bonos[0]
              this.bonos[1] =this.bonos[1]
              this.bonos[2] = this.bonos[2]
              this.bonos[3] =this.bonos[3]
            }
          }else{
            if(res.data.name_table_contribution=='aid_contributions')
            {
              this.pasivo= true
              this.contribusion=false
              this.retroceder_meses=false
              this.comision=false
              this.show_ajuste=true

            if(res.data.valid)
            {
              if(this.data_ballots[0].rent==0 && this.data_ballots[0].dignity_rent==0){
                this.editar=true
                this.editarPasivo= true
              }else{
                if(this.data_ballots[0].dignity_rent>0 && this.data_ballots[0].rent>0){
                  this.editar=false
                  this.editarPasivo= false
                  this.payable_liquid[0] = this.data_ballots[0].rent
                  this.bonos[0] = this.data_ballots[0].dignity_rent
                }else{
                  if(this.data_ballots[0].dignity_rent==0){
                    this.editarPasivo= true
                    this.editar=false
                    this.payable_liquid[0] = this.data_ballots[0].rent
                  }else{
                    if(this.data_ballots[0].rent==0){
                      this.editar=true
                      this.editarPasivo= false
                      this.bonos[0] = this.data_ballots[0].dignity_rent
                    }
                  }
                }
              }
            } else{
              this.editar=true
              this.show_ajuste=true
              this.payable_liquid[0] = this.data_ballots[0].rent
              this.bonos[0] = this.data_ballots[0].dignity_rent
            }

            
            //this.payable_liquid[0] = this.data_ballots[0].rent,
            //this.bonos[0] = this.data_ballots[0].dignity_rent,

            //this.toastr.error("afiliado que pertenece a aid contribution")
            }
            else{
              if(res.data.name_table_contribution==null)
              {
                this.comision=true
                this.contribusion=false
                this.pasivo=false
                this.show_ajuste=false
                //this.periodo=res.data.current_date
                //this.periodo=this.periodo.getMonth()
                this.retroceder_meses=false
               // this.toastr.error("afiliado que esta de comision")
              }

            }
          }
          this.validated=this.affiliate_garantor.guarantor
          this.validated1=this.affiliate_garantor.guarantor
          this.show_calculated=this.affiliate_garantor.guarantor
          this.loan=this.affiliate_garantor.affiliate.loans
          this.show_garante=false
        }
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    },

    async añadir()
    {
      this.guarantor_ci=null
      this.show_data=false
      this.garante_boletas.affiliate_id=this.affiliate_garantor.affiliate.id
      this.garante_boletas.liquid_qualification_calculated=this.evaluate_garantor.liquid_qualification_calculated
      this.guarantor_objeto.affiliate_id=this.affiliate_garantor.affiliate.id
      this.guarantor_objeto.bonus_calculated=this.evaluate_garantor.bonus_calculated
      this.guarantor_objeto.payable_liquid_calculated=this.evaluate_garantor.payable_liquid_calculated

      if(this.guarantors.length>0){
        if(this.garante_boletas.affiliate_id == this.guarantors[0].affiliate_id){
          this.toastr.error("Este afiliado ya se encuentra registrado.")
        }else{
          if(this.data_ballots_name==null)
      {
          let res = await axios.post(`loan_contribution_adjust`, {
            affiliate_id: this.affiliate_garantor.affiliate.id,
            adjustable_id:this.affiliate_garantor.affiliate.id,
            adjustable_type: 'afilliates',
            type_affiliate:'guarantor',
            amount: this.payable_liquid[0],
            type_adjust: 'liquid',
            period_date: this.periodo,
            description: 'Liquido Pagable Comision',
          })
        this.guarantor_objeto.contributionable_ids=this.contributionable
        this.guarantor_objeto.contributionable_type='loan_contribution_adjusts'
        this.loan_contributions_adjust_ids.push(res.data.id)
        this.guarantor_objeto.loan_contributions_adjust_ids=this.loan_contributions_adjust_ids
      }else{
        if(this.data_ballots_state_affiliate=='Pasivo'){
          let res_aid_contribution = await axios.post(`aid_contribution/updateOrCreate`, {
              affiliate_id: this.affiliate_garantor.affiliate.id,
              month_year: this.periodo,
              rent: this.payable_liquid[0],
              dignity_rent: this.bonos[0],
          })
          this.contributionable.push(res_aid_contribution.data.id)
          this.guarantor_objeto.contributionable_ids=this.contributionable
          this.guarantor_objeto.contributionable_type=this.data_ballots_name

          if(this.monto_ajustable>0){
            let res_adjust_pasivo= await axios.post(`loan_contribution_adjust`, {
              affiliate_id: this.affiliate_garantor.affiliate.id,
              adjustable_id:res_aid_contribution.data.id,
              adjustable_type: this.data_ballots_name,
              type_affiliate:'guarantor',
              amount: this.monto_ajustable,
              type_adjust: 'adjust',
              period_date: this.periodo,
              description: this.monto_ajustable_descripcion,
            })
            this.loan_contributions_adjust_ids.push(res_adjust_pasivo.data.id)
            this.guarantor_objeto.loan_contributions_adjust_ids=this.loan_contributions_adjust_ids
          }else{
            this.guarantor_objeto.loan_contributions_adjust_ids=this.loan_contributions_adjust_ids
          }
        }else{

          this.contributionable.push(this.data_ballots[0].id)
          this.guarantor_objeto.contributionable_ids=this.contributionable
          this.guarantor_objeto.contributionable_type=this.data_ballots_name

          if(this.monto_ajustable>0){
            let res_adjust_activo = await axios.post(`loan_contribution_adjust`, {
              affiliate_id: this.affiliate_garantor.affiliate.id,
              adjustable_id:this.data_ballots[0].id,
              adjustable_type: this.data_ballots_name,
              type_affiliate:'guarantor',
              amount: this.monto_ajustable,
              type_adjust: 'adjust',
              period_date: this.periodo,
              description: this.monto_ajustable_descripcion,
            })
            this.loan_contributions_adjust_ids.push(res_adjust_activo.data.id)
            this.guarantor_objeto.loan_contributions_adjust_ids=this.loan_contributions_adjust_ids
          }else{
            this.guarantor_objeto.loan_contributions_adjust_ids=this.loan_contributions_adjust_ids
          }
        }
      }
        this.garantes_detalle.push(this.affiliate_garantor.affiliate.full_name);
        this.garantes_simulador.push(this.garante_boletas);
        this.guarantors.push(this.guarantor_objeto);

        }
      }else{
        if(this.data_ballots_name==null)
      {
          let res = await axios.post(`loan_contribution_adjust`, {
            affiliate_id: this.affiliate_garantor.affiliate.id,
            adjustable_id:this.affiliate_garantor.affiliate.id,
            adjustable_type: 'afilliates',
            type_affiliate:'guarantor',
            amount: this.payable_liquid[0],
            type_adjust: 'liquid',
            period_date: this.periodo,
            description: 'Liquido Pagable Comision',
          })
        this.guarantor_objeto.contributionable_ids=this.contributionable
        this.guarantor_objeto.contributionable_type='loan_contribution_adjusts'
        this.loan_contributions_adjust_ids.push(res.data.id)
        this.guarantor_objeto.loan_contributions_adjust_ids=this.loan_contributions_adjust_ids
      }else{
        if(this.data_ballots_state_affiliate=='Pasivo'){
          let res_aid_contribution = await axios.post(`aid_contribution/updateOrCreate`, {
              affiliate_id: this.affiliate_garantor.affiliate.id,
              month_year: this.periodo,
              rent: this.payable_liquid[0],
              dignity_rent: this.bonos[0],
          })
          this.contributionable.push(res_aid_contribution.data.id)
          this.guarantor_objeto.contributionable_ids=this.contributionable
          this.guarantor_objeto.contributionable_type=this.data_ballots_name

          if(this.monto_ajustable>0){
            let res_adjust_pasivo= await axios.post(`loan_contribution_adjust`, {
              affiliate_id: this.affiliate_garantor.affiliate.id,
              adjustable_id:res_aid_contribution.data.id,
              adjustable_type: this.data_ballots_name,
              type_affiliate:'guarantor',
              amount: this.monto_ajustable,
              type_adjust: 'adjust',
              period_date: this.periodo,
              description: this.monto_ajustable_descripcion,
            })
            this.loan_contributions_adjust_ids.push(res_adjust_pasivo.data.id)
            this.guarantor_objeto.loan_contributions_adjust_ids=this.loan_contributions_adjust_ids
          }else{
            this.guarantor_objeto.loan_contributions_adjust_ids=this.loan_contributions_adjust_ids
          }
        }else{

          this.contributionable.push(this.data_ballots[0].id)
          this.guarantor_objeto.contributionable_ids=this.contributionable
          this.guarantor_objeto.contributionable_type=this.data_ballots_name

          if(this.monto_ajustable>0){
            let res_adjust_activo = await axios.post(`loan_contribution_adjust`, {
              affiliate_id: this.affiliate_garantor.affiliate.id,
              adjustable_id:this.data_ballots[0].id,
              adjustable_type: this.data_ballots_name,
              type_affiliate:'guarantor',
              amount: this.monto_ajustable,
              type_adjust: 'adjust',
              period_date: this.periodo,
              description: this.monto_ajustable_descripcion,
            })
            this.loan_contributions_adjust_ids.push(res_adjust_activo.data.id)
            this.guarantor_objeto.loan_contributions_adjust_ids=this.loan_contributions_adjust_ids
          }else{
            this.guarantor_objeto.loan_contributions_adjust_ids=this.loan_contributions_adjust_ids
          }
        }
      }
        this.garantes_detalle.push(this.affiliate_garantor.affiliate.full_name);
        this.garantes_simulador.push(this.garante_boletas);
        this.guarantors.push(this.guarantor_objeto);
      }
      






        this.garante_boletas={}
        this.guarantor_objeto={}
        this.loan_contributions_adjust_ids=[]
        this.contributionable=[]
        this.clear()
        console.log(this.guarantors)
    },
    async retrocederContribusiones(){
      //  this.toastr.error("se retroceden boletas.")
          let res = await axios.get(`affiliate/${this.affiliate_garantor.affiliate.id}/contribution`, {
          params:{
            city_id: this.$store.getters.cityId,
            choose_diff_month: 1,
            number_diff_month:this.number_diff_month,
            sortBy: ['month_year'],
            sortDesc: [1],
            per_page: 1,
            page: 1,
          }
          })
          this.data_ballots=res.data.data
  if(res.data.valid)
            {
              this.editar=false
              this.payable_liquid[0] = this.data_ballots[0].payable_liquid,
              this.bonos[0] = this.data_ballots[0].border_bonus,
              this.bonos[1] = this.data_ballots[0].east_bonus,
              this.bonos[2] = this.data_ballots[0].position_bonus,
              this.bonos[3] = this.data_ballots[0].public_security_bonus
            } else{
              this.editar=false
              this.retroceder_meses=true
              this.payable_liquid[0] = 0
              this.bonos[0] = this.bonos[0]
              this.bonos[1] =this.bonos[1]
              this.bonos[2] = this.bonos[2]
              this.bonos[3] =this.bonos[3]
            }

             this.$forceUpdate()

    },
     appendIconCallback () {
      if(this.number_diff_month < 8){
      this.number_diff_month++
      this.choose_diff_month = 1
      this.retrocederContribusiones()
    }
  },
  prependIconCallback () {

      if(this.number_diff_month > 1){
      this.number_diff_month--
      this.choose_diff_month = 1
      this.retrocederContribusiones()
    }
  },
  }
  }
</script>