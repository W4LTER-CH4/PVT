<template>
  <v-card flat>
    <v-card-text>
      <v-container class="py-0">
        <v-card color="grey lighten-1" class="ma-0 pa-3">
          <v-row>
            <!--v-col cols="12" md="4">
              <v-card>
                <v-container class="py-0">
                  <v-row>
                    <v-col cols="12" md="4"></v-col>
                    <v-col cols="12" md="6"> Afiliado </v-col>
                    <v-col cols="12" md="2"></v-col>
                    <v-col cols="12" md="1"></v-col>
                    <v-col cols="12" md="8">
                      <v-text-field
                        dense
                        label="CI ó Matrícula"
                        v-model="affiliate_ci"
                        class="py-0"
                        single-line
                        hide-details
                        clearable
                        :loading="loading"
                        @keyup.enter="getCalculator()"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" md="2">
                      <v-tooltip top>
                        <template v-slot:activator="{ on }">
                          <v-btn
                            fab
                            dark
                            x-small
                            v-on="on"
                            color="info"
                            @click.stop="getCalculator()"
                          >
                            <v-icon>mdi-magnify</v-icon>
                          </v-btn>
                        </template>
                        <span>Buscar afiliado</span>
                      </v-tooltip>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card>
            </!--v-col>
            <v-col-- cols="12" md="8" v-show="ver">
              <v-card>
                <v-card-text>
                  <v-row>
                    <v-col   class="text-center" cols="12" md="12" >
                      <h3 class="info--text aling-text--center " >
                      DATOS DEL AFILIADO  {{loans1}}
                      </h3>
                    </v-col>
                     <v-progress-linear></v-progress-linear>
                    <v-col cols="12" md="6" >
                        Nombre: {{loans1.affiliate}}
                    </v-col>
                    <v-col cols="12" md="6" >
                        C.I.: {{loans1.affiliate_identity_card}}
                    </v-col>
                    <v-col cols="12" md="6" >
                     Estado: {{loans1.state_affiliate}}
                    </v-col>
                  </v-row>
                </v-card-text>
              </v-card>
            </v-col-->
            <v-col  cols="12" md="12" v-show="evaluacion">
              <v-row>
                <v-col cols="12" md="4">
                  <v-card>
                    <v-card-text>
                      <v-row>
                        <v-col   class="text-center" cols="12" md="12">
                          <h3 class="aling-text--center" style="color:teal">
                          {{anticipo.name }}
                          </h3>
                        </v-col>
                        <v-progress-linear></v-progress-linear>
                        <v-col cols="12" md="8" >
                            Liquido para Calificacion:
                        </v-col>
                        <v-col cols="12" md="4" >
                            {{ anticipo.liquid_calification +" Bs."}}
                        </v-col>
                        <v-col cols="12" md="8" >
                            Monto Maximo:
                        </v-col>
                        <v-col cols="12" md="4" >
                            {{ anticipo.amount_max +" Bs."}}
                        </v-col>
                        <v-col cols="12" md="8" >
                            Plazo Maximo:
                        </v-col>
                        <v-col cols="12" md="4" >
                            {{ anticipo.maximum_term +" meses"}}
                        </v-col>
                        <v-col cols="12" md="8" >                            
                            Cuota Calculada:
                        </v-col>
                        <v-col cols="12" md="4" >
                            {{ anticipo.quota_calculated +" Bs."}}
                        </v-col>
                        <v-col cols="12" md="8" >
                            Interes Mensual:
                        </v-col>
                        <v-col cols="12" md="4" >
                            {{ (anticipo.annual_interest/12).toFixed(2) +" %"}}
                        </v-col>
                      </v-row>
                    </v-card-text>
                  </v-card>
                </v-col>
                <v-col cols="12" md="4">
                  <v-card>
                    <v-card-text>
                      <v-row>
                      <v-col   class="text-center" cols="12" md="12">
                        <h3 class=" aling-text--center" style="color:teal">
                        {{corto_plazo.name}}
                        </h3>
                      </v-col>
                      <v-progress-linear></v-progress-linear>
                        <v-col cols="12" md="8" >
                            Liquido para Calificacion:
                        </v-col>
                        <v-col cols="12" md="4" >
                            {{ corto_plazo.liquid_calification +" Bs."}}
                        </v-col>
                        <v-col cols="12" md="8" >
                            Monto Maximo:
                        </v-col>
                        <v-col cols="12" md="4" >
                            {{ corto_plazo.amount_max +" Bs."}}
                        </v-col>
                        <v-col cols="12" md="8" >
                            Plazo Maximo:
                        </v-col>
                        <v-col cols="12" md="4" >
                            {{ corto_plazo.maximum_term +" meses"}}
                        </v-col>
                        <v-col cols="12" md="8" >                            
                            Cuota Calculada:
                        </v-col>
                        <v-col cols="12" md="4" >
                            {{ corto_plazo.quota_calculated +" Bs."}}
                        </v-col>
                        <v-col cols="12" md="8" >
                            Interes Mensual:
                        </v-col>
                        <v-col cols="12" md="4" >
                            {{ (corto_plazo.annual_interest/12).toFixed(2)  +" %"}}
                        </v-col>
                      </v-row>
                    </v-card-text>
                  </v-card>
                </v-col>
                <v-col cols="12" md="4">
                  <v-card>
                    <v-card-text>
                    <v-row>
                      <v-col   class="text-center" cols="12" md="12">
                        <h3 class=" aling-text--center" style="color:teal">
                        {{largo_plazo.name}}
                        </h3>
                      </v-col>
                      <v-progress-linear></v-progress-linear>
                        <v-col cols="12" md="8" >
                            Liquido para Calificacion:
                        </v-col>
                        <v-col cols="12" md="4" >
                            {{ largo_plazo.liquid_calification +" Bs."}}
                        </v-col>
                        <v-col cols="12" md="8" >
                            Monto Máximo:
                        </v-col>
                        <v-col cols="12" md="4" >
                            {{ largo_plazo.amount_max +" Bs."}}
                        </v-col>
                        <v-col cols="12" md="8" >
                            Plazo Máximo:
                        </v-col>
                        <v-col cols="12" md="4" >
                            {{ largo_plazo.maximum_term +" meses"}}
                        </v-col>
                        <v-col cols="12" md="8" >                            
                            Cuota Calculada:
                        </v-col>
                        <v-col cols="12" md="4" >
                            {{ largo_plazo.quota_calculated +" Bs."}}
                        </v-col>
                        <v-col cols="12" md="8" >
                            Interes Mensual:
                        </v-col>
                        <v-col cols="12" md="4" >
                            {{(largo_plazo.annual_interest/12).toFixed(2)  +" %"}}
                        </v-col>
                    </v-row>
                    </v-card-text>
                  </v-card>
                </v-col>
              </v-row>
            </v-col>
            <v-col v-show="ver">
               <v-col cols="12" md="12" v-show="!evaluacion">
                <v-card>
                  <v-card-text>
                  <v-row>
                    <v-col   class="text-center" cols="12" md="12">
                      <h3 class=" aling-text--center" style="color:teal">
                      {{loans1.message.accomplished}}
                      </h3>
                    </v-col>
                  </v-row>
                  </v-card-text>
                </v-card>
              </v-col>
            </v-col>
          </v-row>
        </v-card>
      </v-container>
    </v-card-text>
  </v-card>
</template>

<script>
export default {
  name: "data-loan-affiliate",
   props: {
    loans1: {
      type: Object,
      required: true
    },
    largo_plazo: {
      type: Object,
      required: true
    },
    corto_plazo: {
      type: Object,
      required: true
    },
    anticipo: {
      type: Object,
      required: true
    },
     ver: {
      type: Boolean,
      required: true
    },
     evaluacion: {
      type: Boolean,
      required: true
    },
  },
  data: () => ({
    //affiliate_ci:null,
    //loading: false,
    //largo_plazo:{},
    //corto_plazo:{},
    //anticipo:{},
    
    //loans1: {
    //  message:{
    //    accomplished:null
    //  },
    //  state_affiliate:{
    //    name:null,
  
    //  }
   
    //},
    //ver: false,
    //evaluacion:false
  }),

  methods: {
   /* async getCalculator() {
      try {
        this.loading = false;
         this.ver= true
          let res = await axios.post(`search_loan`, {
            identity_card : this.affiliate_ci });
            this.loans1=res.data
            this.cont=this.loans1.modalities.length
            for (let i = 0; i < this.loans1.modalities.length; i++)
             {
               if(this.loans1.modalities[i].modality_affiliate.procedure_type_id==9)
               {
                  this.anticipo.name=this.loans1.modalities[i].name_procedure_modality
                  this.anticipo.amount_max=this.loans1.modalities[i].amount_max
                  this.anticipo.maximum_term=this.loans1.modalities[i].modality_affiliate.procedure_type.interval.maximum_term
                  this.anticipo.liquid_calification=this.loans1.modalities[i].liquid_calification
                  this.anticipo.annual_interest=this.loans1.modalities[i].interest.annual_interest
               }
               if(this.loans1.modalities[i].modality_affiliate.procedure_type_id==10)
               {
                  this.corto_plazo.name=this.loans1.modalities[i].name_procedure_modality
                  this.corto_plazo.amount_max=this.loans1.modalities[i].amount_max
                  this.corto_plazo.maximum_term=this.loans1.modalities[i].modality_affiliate.procedure_type.interval.maximum_term
                  this.corto_plazo.liquid_calification=this.loans1.modalities[i].liquid_calification
                  this.corto_plazo.annual_interest=this.loans1.modalities[i].interest.annual_interest
               }
               if(this.loans1.modalities[i].modality_affiliate.procedure_type_id==11)
               {
                  this.largo_plazo.name=this.loans1.modalities[i].name_procedure_modality
                  this.largo_plazo.amount_max=this.loans1.modalities[i].amount_max
                  this.largo_plazo.maximum_term=this.loans1.modalities[i].modality_affiliate.procedure_type.interval.maximum_term
                  this.largo_plazo.liquid_calification=this.loans1.modalities[i].liquid_calification
                  this.largo_plazo.annual_interest=this.loans1.modalities[i].interest.annual_interest
               }

             }
            
            this.evaluacion=res.data.evaluate
              this.$forceUpdate()
      } catch (e) {
        console.log(e);
      } finally {
        this.loading = false;
      }
    },*/

  },
};
</script>
