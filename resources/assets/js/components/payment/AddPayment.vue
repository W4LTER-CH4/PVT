<template>
  <v-container >
    <ValidationObserver ref="observer">
      <v-form>
        <v-card>
        <v-card-text class="ma-0 pb-0">
          <v-container fluid class="ma-0 pb-0">
            <v-row justify="center" class="ma-0 pb-0">
              <v-col cols="12" >
                <v-layout row wrap>
                  <v-flex xs12 class="px-2">
                    <fieldset class="pa-3">
                      <center>
                        <v-toolbar-title>DATOS DE LA AMORTIZACION</v-toolbar-title>
                      </center>
                      <v-progress-linear></v-progress-linear>
                      <v-container >
                        <ValidationObserver ref="observer">
                          <v-form>
                            <template>
                              <v-row>
                                <v-col cols="3" class="ma-0 py-0">
                                  <label><b>Primer Nombre:</b></label>
                                    {{loan.lenders[0].first_name}}
                                </v-col>
                                <v-col cols="3" class="ma-0 py-0">
                                  <label><b>Pago a Capital:</b></label>
                                </v-col>
                                <v-col cols="2" class="ma-0 py-0">
                                  {{payment.capital_payment}}
                                </v-col>
                                <v-col cols="3" class="ma-0 py-0">
                                  <label><b>Interes Pendiente:</b></label>
                                </v-col>
                                <v-col cols="1" class="ma-0 py-0">
                                  {{payment.interest_remaining}}
                                </v-col>
                                <v-col cols="3" class="ma-0 py-0">
                                  <label><b>Paterno:</b></label>
                                  {{loan.lenders[0].last_name}}
                                </v-col>
                                <v-col cols="3" class="ma-0 py-0">
                                  <label><b>Interes de Días:</b></label>
                                </v-col>
                                <v-col cols="2" class="ma-0 py-0">
                                  {{payment.estimated_days.current+' Total'}}
                                </v-col>
                                <v-col cols="3" class="ma-0 py-0">
                                  <label><b>Interes Penal Pendiente:</b></label>
                                </v-col>
                                <v-col cols="1" class="ma-0 py-0">
                                  {{payment.penal_remaining}}
                                </v-col>
                                <v-col cols="3" class="ma-0 py-0">
                                  <label><b>Materno:</b></label>
                                  {{loan.lenders[0].mothers_last_name}}
                                </v-col>
                                <v-col cols="3" class="ma-0 py-0">
                                  <label><b>Penales de Días:</b></label>
                                </v-col>
                                <v-col cols="2" class="ma-0 py-0">
                                  {{payment.estimated_days.penal +' Total'}}
                                </v-col>
                                <v-col cols="2" class="ma-0 py-0">
                                  <label><b>Nro Prestamos:</b></label>
                                </v-col>
                                <v-col cols="2" class="ma-0 py-0">
                                  {{loan.code}}
                                </v-col>
                                <v-col cols="3" class="ma-0 py-0">
                                  <label><b>Numero de Pago:</b></label>
                                  {{ payment.quota_number}}
                                </v-col>
                                <v-col cols="3" class="ma-0 py-0">
                                  <label><b>Interes Corrientes Pendientes:</b></label>
                                </v-col>
                                <v-col cols="2" class="ma-0 py-0">
                                  {{payment.interest_payment}}
                                </v-col>
                                <v-col cols="2" class="ma-0 py-0">
                                  <label><b>Cuota Fija:</b></label>
                                </v-col>
                                <v-col cols="2" class="ma-0 py-0">
                                  {{payment.estimated_quota+ ' Bs'}}
                                </v-col>
                                <v-col cols="3" class="ma-0 py-0">
                                  <label><b>C.I.:</b></label>
                                  {{ loan.lenders[0].identity_card}}
                                </v-col>
                                <v-col cols="3" class="ma-0 py-0">
                                  <label><b>Interes Penales Pendientes:</b></label>
                                </v-col>
                                <v-col cols="2" class="ma-0 py-0">
                                  {{payment.penal_payment}}
                                </v-col>
                                <v-col cols="2" class="ma-0 py-0">
                                  <label><b>Saldo Anterior:</b></label>
                                </v-col>
                                <v-col cols="2" class="ma-0 py-0">
                                  {{payment.balance}}
                                </v-col>
                                <v-col cols="3" class="ma-0 py-0">
                                  <label><b>Fecha de Pago:</b></label>
                                  {{ payment.estimated_date}}
                                </v-col>
                                <v-col cols="3" class="ma-0 py-0">
                                  <label><b>Total Pagado:</b></label>
                                </v-col>
                                <v-col cols="2" class="ma-0 py-0">
                                  {{payment.estimated_quota}}
                                </v-col>
                                <v-col cols="2" class="ma-0 py-0">
                                  <label><b>Saldo Actual:</b></label>
                                </v-col>
                                <v-col cols="2" class="ma-0 py-0">
                                  {{payment.next_balance}}
                                </v-col>
                                 <v-progress-linear></v-progress-linear>
                                <v-col cols="3" class="ma-0 py-0">
                                  <label><b>Interes Restante Acumulado:</b></label>
                                </v-col>
                                <v-col cols="3" class="ma-0 py-0">
                                  {{payment.interest_accumulated}}
                                </v-col>
                                <v-col cols="3" class="ma-0 py-0">
                                  <label><b>Interes Penal Restante Acumulado:</b></label>
                                </v-col>
                                <v-col cols="3" class="ma-0 py-0">
                                  {{payment.penal_accumulated}}
                                </v-col>
                              </v-row>
                            </template>
                          </v-form>
                        </ValidationObserver>
                      </v-container>
                    </fieldset>
                  </v-flex>
                </v-layout>
            </v-col>
          </v-row>
        </v-container>
      </v-card-text>
        </v-card>
      </v-form>
    </ValidationObserver>
  </v-container>
</template>
<script>
export default {
  name: "add-payment",
  props: {
    payment: {
      type: Object,
      required: true
    },
    data_payment: {
      type: Object,
      required: true
    }
  },
  data: () => ({
    dialog: false,
    observation: {},
    tipo_de_pago: [
      {name:"Efectivo",
      id:1
      },
      {name:"Dep. en Cuenta",
      id:2
      },
      {name:"Cheque",
      id:3
      },
      {name:"Garante",
      id:4
      }
    ],
    tipo_de_amortizacion: [
      {name:"Regular",
      id:1
      },
      {name:"Total",
      id:2
      },
    ],
    loan:{
      lenders:[]},
     payment_types:[],
    dates: {
      disbursementDate:{
            formatted: null,
            picker: false
          }
      },
    observation_type: [],
    flow: {},
    valArea: [],
    areas: []
  }),
  computed: {
    isNew() {
      return  this.$route.params.hash == 'new'
    },
  },
 beforeMount(){
    this.getPaymentTypes()
     if(this.isNew)
    {
      this.getLoanData(this.$route.query.loan_id)
    }
  },
  methods: {
    async getLoan() {
      try {
        this.loading = true
        let res = await axios.get(`payment_type`)
        this.payment_types = res.data
        console.log(this.payment_types+'este es el tipo de desembolso');
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    },
    async getLoanData(id) {
      try {
        this.loading = true
        let res = await axios.get(`loan/${id}`)
        this.loan=res.data
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    },

        async getPaymentTypes() {
      try {
        this.loading = true
        let res = await axios.get(`payment_type`)
        this.payment_types = res.data
        console.log(this.payment_types+'este es el tipo de desembolso');
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    },
  }
}
</script>