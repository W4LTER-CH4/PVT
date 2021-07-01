<template>
  <v-card flat>
    <v-card-title class="pb-0">
      <v-toolbar dense color='tertiary'>
        <v-toolbar-title>
          <Breadcrumbs />
        </v-toolbar-title>
        <v-spacer></v-spacer>
        <template>
          <v-tooltip bottom>
                <template v-slot:activator="{ on }">
                  <v-btn
                    icon
                    small
                    v-on="on"
                    color="primary"
                    :to="{ name: 'tracingAdd', params: { id: item.id_loan } }"
                    ><v-icon>mdi-eye</v-icon>
                  </v-btn>
                </template>
                <span>Ver información del trámite</span>
              </v-tooltip>
              <v-tooltip bottom >
                <template v-slot:activator="{ on }">
                  <v-btn
                    fab
                    x-small
                    outlined
                    v-on="on"
                    color="primary"
                    class="ml-1"
                    @click="imprimir(2, item.id_loan)"
                    ><v-icon>mdi-file-document</v-icon>
                  </v-btn>
                </template>
                <span>Imprimir Solictud</span>
              </v-tooltip>
              <v-tooltip bottom >
                <template v-slot:activator="{ on }">
                  <v-btn
                    fab
                    x-small
                    outlined
                    v-on="on"
                    color="primary"
                    class="ml-1"
                    @click="imprimir(1, item.id_loan)"
                    ><v-icon>mdi-file</v-icon>
                  </v-btn>
                </template>
                <span>Imprimir Contrato</span>
              </v-tooltip>
              <v-tooltip bottom v-if="loan.state.name == 'Vigente'">
                <template v-slot:activator="{ on }">
                  <v-btn
                    fab
                    x-small
                    outlined
                    v-on="on"
                    color="primary"
                    class="ml-1"
                    @click="imprimir(3, item.id_loan)"
                    ><v-icon>mdi-cash</v-icon>
                  </v-btn>
                </template>
                <span>Imprimir Plan de pagos</span>
              </v-tooltip>
              <v-tooltip bottom v-if=" loan.state.name == 'Vigente'">
                <template v-slot:activator="{ on }">
                  <v-btn
                    fab
                    x-small
                    outlined
                    v-on="on"
                    color="primary"
                    class="ml-1"
                    @click="imprimir(4, item.id_loan)"
                    ><v-icon>mdi-view-list</v-icon>
                  </v-btn>
                </template>
                <span>Imprimir Kardex</span>
              </v-tooltip>

          <v-divider vertical class="mx-4"></v-divider>
          <h6 class="caption">
          <strong>Ubicación trámite:</strong> <br />
          <v-icon x-small color="orange">mdi-folder-information</v-icon>{{role_name}} <br>
          <v-icon x-small color="blue" v-if="user_name != null">mdi-file-account</v-icon> {{user_name}}</h6>
        </template>
      </v-toolbar>
    </v-card-title>
    <v-card-text>
      <Dashboard :affiliate.sync="affiliate" :loan.sync="loan" :spouse.sync="spouse" />
      <FormTracing
          :loan.sync="loan"
          :loan_refinancing.sync="loan_refinancing"
          :loan_properties="loan_properties"
          :procedure_types="procedure_types"
      >
      </FormTracing>
    </v-card-text>
  </v-card>
</template>
<script>
import Breadcrumbs from "@/components/shared/Breadcrumbs"
import FormTracing from "@/components/tracing/FormTracing"
import Dashboard from "@/components/tracing/Dashboard"

export default {
  name: "flow-index",
  components: {
    Breadcrumbs,
    FormTracing,
    Dashboard,
  },
  data: () => ({
    bus: new Vue(),
    search: "",
    bus1: new Vue(), //Creamos la instancia de bus1
    addresses: [],
    affiliate: {
      first_name: null,
      second_name: null,
      last_name: null,
      mothers_last_name: null,
      identity_card: null,
      birth_date: null,
      date_death: null,
      reason_death: null,
      phone_number: null,
      cell_phone_number: null,
      city_identity_card_id: null,
      date_entry: null,
      service_years: null,
      service_months: null,
      date_derelict: null,
      unit_name: null,
      registration: null
    },
    dialog:false,
    bonos: [0, 0, 0, 0],
    payable_liquid: [0, 0, 0],
    modalidad: {},
    loan: {
      intereses: {},
      state: {},
      user:{}
    },
    loan_refinancing:{},
    datos: {},
    formulario: [],
    observations: [],
    spouse: {},
    loan_properties: {},
    procedure_types: {},
    intervalos: {},
    modalidad: {},
    icons: true,
    vertical: true,
    editable: false,
    reload: false,
    tab: "tab-1",
    validate: {
      valid_disbursement: false,
      valid_date_contract : false,
      valid_date_contract_return: false,
      valid_certificate : false

    },
    role_name: null,
    user_name: null,
    id_street: 0,
  }),
   mounted() {
    // si existe el query de redireccion de tab, se setea el valor
    if(this.$route.query.redirectTab) {
      this.tab = 'tab-'+this.$route.query.redirectTab
    }
    /*if(this.$route.params.workTray){
      this.workTray = 'received'
    }*/
    this.getloan(this.$route.params.id)
    this.getSpouse(this.$route.params.id)
    this.getObservation(this.$route.params.id)
    this.getProceduretype(this.$route.params.id)
    
    this.bus1.$on("emitGetObservation", id => {
      //escuchamos la emision de ObserverFlow
      this.getObservation(id) //y monstramos la lista de observaciones segun el id del prestamo
    })
  },
  methods: {
    resetForm() {
      this.getAddress(this.affiliate.id)
      this.editable = false
      this.reload = true
      this.$nextTick(() => {
        this.reload = false
      })
    },
      setBreadcrumbs() {
      let breadcrumbs = [
        {
          text: "Seguimiento",
          to: { name: "listTracing" }
        }
      ]
      breadcrumbs.push({
        text: this.loan.code,
        to: { name: "tracingAdd", params: { id: this.loan.id } }
      })
      this.$store.commit("setBreadcrumbs", breadcrumbs)
    },
    async getloan(id) {
      try {
        this.loading = true
        let res = await axios.get(`loan/${id}`)
        this.loan = res.data
        this.loan.amount_approved_before= res.data.amount_approved
        this.loan.loan_term_before= res.data.loan_term

        this.loan.amount_approved_aux = this.loan.amount_approved
        this.loan.payable_liquid_calculated_aux = this.loan.lenders[0].pivot.payable_liquid_calculated
        this.loan.liquid_qualification_calculated_aux = this.loan.liquid_qualification_calculated
        this.loan.loan_term_aux = this.loan.loan_term
        this.loan.bonus_calculated_aux = this.loan.lenders[0].pivot.bonus_calculated
        this.loan.indebtedness_calculated_aux = this.loan.indebtedness_calculated
        this.loan.estimated_quota_aux = this.loan.estimated_quota


        this.loan.disbursement_date=this.$moment(res.data.disbursement_date).format('YYYY-MM-DD')
        this.loan.delivery_contract_date=this.$moment(res.data.delivery_contract_date).format('YYYY-MM-DD')
        this.loan.return_contract_date=this.$moment(res.data.return_contract_date).format('YYYY-MM-DD')

        if(this.loan.parent_reason=='REFINANCIAMIENTO')
        {
          this.loan_refinancing.refinancing = true
          if(this.loan.parent_loan_id){

            this.loan_refinancing.code = this.loan.parent_loan.code
            this.loan_refinancing.amount_approved_son  = this.loan.parent_loan.amount_approved
            this.loan_refinancing.loan_term  = this.loan.parent_loan.loan_term
            this.loan_refinancing.balance  = this.loan.parent_loan.balance
            this.loan_refinancing.estimated_quota = this.loan.parent_loan.estimated_quota
            this.loan_refinancing.type_sismu = false
            this.loan_refinancing.description= 'PRESTAMO DEL PVT'
          }else{
            this.loan_refinancing.code = this.loan.data_loan.code
            this.loan_refinancing.amount_approved_son  = this.loan.data_loan.amount_approved
            this.loan_refinancing.loan_term  = this.loan.data_loan.loan_term
            this.loan_refinancing.balance  = this.loan.data_loan.balance
            this.loan_refinancing.type_sismu = true
            this.loan_refinancing.estimated_quota = this.loan.data_loan.estimated_quota
            this.loan_refinancing.description= 'PRESTAMO DEL SISMU'
           }
        }else{
           this.loan_refinancing.refinancing = false
        }
            this.loan_refinancing.date_cut_refinancing = this.loan.date_cut_refinancing
            this.loan_refinancing.balance_parent_loan_refinancing = this.loan.balance_parent_loan_refinancing
            this.loan_refinancing.amount_approved = this.loan.amount_approved
            this.loan_refinancing.refinancing_balance = this.loan.refinancing_balance



        console.log("este es el loan" + this.loan)
        let res1 = await axios.get(`affiliate/${this.loan.lenders[0].id}`)
        this.affiliate = res1.data
        if (this.loan.property_id != null) {
          this.getLoanproperty(this.loan.property_id)
        }
        this.getProceduretype(this.loan.procedure_modality_id)
        if (this.loan.disbursable_type == "spouses") {
          this.getSpouse(this.affiliate.id)
        }
        this.setBreadcrumbs()
        this.getAddress(this.affiliate.id)

        this.role(this.loan.role_id)
        this.user(this.loan.user_id)
        console.log(this.loan)
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    },
    async getSpouse(id) {
      try {
        this.loading = true
        let res = await axios.get(`affiliate/${id}/spouse`)
        this.spouse = res.data
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    },

    async getLoanproperty(id) {
      try {
        this.loading = true
        let res = await axios.get(`loan_property/${id}`)
        this.loan_properties = res.data
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    },

    async getProceduretype(id) {
      try {
        this.loading = true
        let res = await axios.get(`procedure_modality/${id}`)
        this.procedure_types = res.data
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    },

    async getObservation(id) {
      try {
        this.loading = true
        let res = await axios.get(`loan/${id}/observation`)
        this.observations = res.data
        
        for (this.i = 0; this.i < this.observations.length; this.i++) {
           console.log("ww"+this.observations[this.i].user_id)
          let res1 = await axios.get(`user/${this.observations[this.i].user_id}`
          )
          this.observations[this.i].user_name = res1.data.username
        }
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    },
    async getAddress(id) {
      try {
        this.loading = true
        let res = await axios.get(`affiliate/${id}/address`)
        this.addresses = res.data
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    },
    async role(role_id){
      try {
        let res = await axios.get(`role/${role_id}`)
        this.role_name = res.data.display_name
        console.log(this.role_name)
      } catch (e) {
        console.log(e)
      }
    },

    async user(user_id){
      try {
        let res = await axios.get(`user/${user_id}`)
        this.user_name = res.data.username
        console.log(this.user_name)
      } catch (e) {
        console.log(e)
      }
    },
  },
      async getAddress(id) {
      try {
        this.loading = true
        let res = await axios.get(`affiliate/${id}/address`)
        this.addresses = res.data
        // Seteando el valor del address
        let address = this.addresses.find(item => item.pivot.validated)
        this.id_street = address.id
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    },

    async imprimir(id, item) {
      try {
        let res;
        if (id == 1) {
          res = await axios.get(`loan/${item}/print/contract`);
        } else if (id == 2) {
          res = await axios.get(`loan/${item}/print/form`);
        } else if (id == 3) {
          res = await axios.get(`loan/${item}/print/plan`);
        } else {
          res = await axios.get(`loan/${item}/print/kardex`);
        }
        printJS({
          printable: res.data.content,
          type: res.data.type,
          documentTitle: res.data.file_name,
          base64: true,
        });
      } catch (e) {
        this.toastr.error("Ocurrió un error en la impresión.");
        console.log(e);
      }
    },
  }

</script>