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
                                <v-col cols="6" class="ma-0 py-2">
                                  <label><b>Pago a Capital:</b></label>
                                  {{payment.capital_payment | moneyString}}
                                </v-col>
                                 <v-col cols="6" class="ma-0 py-2">
                                  <label><b>Fecha de Calculo:</b></label>
                                  {{payment.estimated_date | date}}
                                </v-col>
                                   <v-col cols="6" class="ma-0 py-2">
                                  <label><b>Interes Corriente de Días:</b></label>
                                  {{payment.estimated_days.current+' Total'}}
                                  {{payment.estimated_days.current_generated | moneyString}}
                                </v-col>
                                 <v-col cols="6" class="ma-0 py-2">
                                  <label><b>Fecha de Transaccion:</b></label>
                                   {{ payment.now_date | date}}

                                </v-col>
                                <v-col cols="6" class="ma-0 py-2">
                                  <label><b>Interes Penal de Días:</b></label>
                                  {{payment.estimated_days.penal +' Total'}}
                                  {{payment.estimated_days.penal_generated | moneyString}}
                                </v-col>
                                 <v-col cols="6" class="ma-0 py-2">
                                  <label><b>Saldo Anterior:</b></label>
                                  {{payment.balance | moneyString }}
                                </v-col>
                                <v-col cols="6" class="ma-0 py-2">
                                  <label><b>Interes Corriente Pendiente:</b></label>
                                  {{payment.interest_remaining | moneyString}}
                                </v-col>
                                <v-col cols="6" class="ma-0 py-2">
                                  <label><b style="color:teal">Saldo Actual:</b></label>
                                  <b style="color:teal">{{payment.next_balance | moneyString }}</b>
                                </v-col>
                                <v-col cols="12" class="ma-0 py-2">
                                  <label><b>Interes Penal Pendiente:</b></label>
                                  {{payment.penal_remaining | moneyString}}
                                </v-col>
                                 <v-col cols="12" class="ma-0 py-2">
                                  <label><b>Total Pagado:</b></label>
                                  {{payment.estimated_quota | moneyString}}
                                </v-col>
                                 <v-progress-linear></v-progress-linear>
                                <v-col cols="6" class="ma-0 py-2">
                                  <label><b>Intereses Corrientes Pendientes:</b></label>
                                  {{payment.interest_accumulated | moneyString}}
                                </v-col>
                                <v-col cols="6" class="ma-0 py-2">
                                  <label><b>Interes Penales Pendientes:</b></label>
                                  {{payment.penal_accumulated | moneyString}}
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
    this.$forceUpdate()
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