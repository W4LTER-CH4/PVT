<template>
  <v-flex xs12 class="px-3">
    <div class="text-center">BOLETAS DE PAGO DEL CODEUDOR AFILIADO</div>
    <v-form>
      <v-row justify="center">
        <v-col cols="12">
          <v-container class="py-0">
            <v-row>
              <v-col class="text-center" cols="12" md="8" v-show="exist_codebtor" v-if="ver">
                <h2 class="success--text">ES AFILIADO DE LA MUSERPOL</h2>
              </v-col>
              <v-col class="text-center" cols="12" md="8" v-show="!exist_codebtor" v-if="ver">
                <h2 class="error--text">NO ES AFILIADO DE LA MUSERPOL</h2>
              </v-col>
              <v-col class="text-center" cols="12" md="8" v-if="!ver"></v-col>
              <v-col cols="12" md="3">
                <v-text-field
                  dense
                  label="C.I. CODEUDOR"
                  v-model="affiliate_codebtor_ci"
                  class="py-0"
                  single-line
                  hide-details
                  clearable
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="1">
                <v-tooltip>
                  <template v-slot:activator="{ on }">
                    <v-btn fab dark x-small v-on="on" color="info" @click.stop="searchCodebtor()">
                      <v-icon>mdi-magnify</v-icon>
                    </v-btn>
                  </template>
                </v-tooltip>
               
              </v-col>
              <v-col>
                <!--{{contrib_codebtor}}-->
                <v-data-table
                  dense
                  :headers="headers1"
                  :items="contrib_codebtor"
                  sort-by
                  class="elevation-1 ma-0 pa-3 pb-6"
                  hide-default-footer
                >
                  <template v-slot:top>
                    <v-dialog v-model="dialog" max-width="80%">
                      <v-card>
                        <v-card-title class="pa-0">
                          <v-toolbar dense flat color="">
                            <v-toolbar-title>{{ formTitle }}</v-toolbar-title>
                            <v-spacer></v-spacer>
                          </v-toolbar>
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-card-text class="py-0">
                          <v-container>
                            <v-row>
                              <v-col cols="12" sm="12" md="12" style="color: teal">
                                <h3>CI: {{editedItem.identity_card}}</h3>
                                <h3>NOMBRE: {{editedItem.full_name}} </h3>
                              </v-col>
                            </v-row>
                            <v-row class="py-0 my-0">              
                              <v-col cols="12" md="2" class="py-0 my-0">
                                <v-text-field
                                  dense
                                  v-model="number_diff_month"
                                  label="Número de meses"                    
                                  color="info"
                                  append-icon="mdi-plus-box"
                                  prepend-icon="mdi-minus-box"
                                  @click:append="appendIconCallback"
                                  @click:prepend="prependIconCallback"
                                  readonly
                                ></v-text-field>
                              </v-col>           
                            </v-row>
                            <!--boleta 1--->

                            <v-row v-for="(contrib,i) in editedItem.contribution" :key="i" class="py-0 my-0">
                            
                              <v-col cols="12" md="7" class="py-0 my-0">
                                <v-row>
                                  <v-col cols="12" md="12" class="py-0 my-0 pb-1 uppercase"> BOLETAS DE PAGO <b>{{editedItem.contribution[i].month}}</b></v-col>
                                  <v-col cols="12" md="3" class="py-0 my-0" v-if="lender_contribution.state_affiliate != 'Comisión'">
                                    <ValidationProvider
                                      v-slot="{ errors }"
                                      name="Boleta de pago"
                                      :rules="'required|min_value:' + global_parameters.livelihood_amount"
                                      mode="aggressive"
                                    >
                                      <b style="text-align: center"></b>
                                      <v-text-field
                                        :error-messages="errors"
                                        dense
                                        v-model="editedItem.contribution[i].payable_liquid"
                                        label="Liquido pagable"
                                        :disabled="!enabled"
                                        :outlined="editar"
                                      ></v-text-field>
                                    </ValidationProvider>
                                  </v-col>
                                  <!--<v-col cols="12" md="1" class="py-0">
                                    <span>
                                      <v-tooltip top>
                                        <template v-slot:activator="{ on }">
                                          <v-btn
                                            icon
                                            dark
                                            small
                                            color="success"
                                            bottom
                                            right
                                            v-on="on"
                                            @click="saveAdjustment(i)"
                                          >
                                            <v-icon>mdi-calculator</v-icon>
                                          </v-btn>
                                        </template>
                                        <span>Ajuste</span>
                                      </v-tooltip>
                                    </span>                        
                                  </v-col>-->
                                  <v-col cols="12" class="py-0 my-0"  :md="lender_contribution.state_affiliate == 'Comisión' ? 4 : 2">
                                    <ValidationProvider
                                      v-slot="{ errors }"
                                      name="Monto ajuste"
                                      :rules="''"
                                      mode="aggressive"
                                    >
                                      <b style="text-align: center"></b>
                                      <v-text-field
                                        :error-messages="errors"
                                        dense
                                        v-model="editedItem.contribution[i].adjustment_amount"
                                        :label= "lender_contribution.state_affiliate == 'Comisión' ? 'Liquido pagable' :  'Monto ajuste'"
                                        outlined
                                      ></v-text-field>
                                    </ValidationProvider>
                                  </v-col>
                                  <template v-if="lender_contribution.state_affiliate != 'Comisión'">
                                    <v-col cols="12" md="2" class="py-0 my-0" >
                                      <b style="text-align: center">= {{(parseFloat(editedItem.contribution[i].adjustment_amount) + parseFloat(editedItem.contribution[i].payable_liquid)) | money}}</b>
                                    </v-col>
                                    <v-col cols="12" md="5" class="py-0 my-0">
                                      <ValidationProvider
                                        v-slot="{ errors }"
                                        name="Descripción"
                                        :rules="''"
                                        mode="aggressive"
                                      >
                                        <b style="text-align: center"></b>
                                        <v-textarea
                                          :error-messages="errors"
                                          dense
                                          v-model="editedItem.contribution[i].adjustment_description"
                                          label="Descripción ajuste"
                                          outlined
                                          rows="1"                              
                                        ></v-textarea>
                                      </ValidationProvider>
                                    </v-col>
                                  </template>
                                </v-row>
                              </v-col>

                              <v-col cols="12" md="5" class="py-0 my-0">
                                <v-row class="py-0 my-0">
                                  <v-col cols="12" md="12" class="py-0 my-0" v-if="lender_contribution.state_affiliate != 'Comisión'"> BONOS </v-col>
                                  <template v-if="lender_contribution.state_affiliate == 'Activo'">
                                    <v-col cols="12" md="3" class="py-0 my-0">
                                      <ValidationProvider
                                        v-slot="{ errors }"
                                        name="Bono Frontera"
                                        :rules="''"
                                        mode="aggressive"
                                      >
                                        <v-text-field
                                          :error-messages="errors"
                                          dense
                                          v-model="editedItem.contribution[i].border_bonus"
                                          label="Bono Frontera"
                                          :disabled="!enabled"
                                          :outlined="editar"
                                        ></v-text-field>
                                      </ValidationProvider>
                                    </v-col>
                                    <v-col cols="12" md="3" class="py-0 my-0">
                                      <ValidationProvider
                                        v-slot="{ errors }"
                                        name="Bono Oriente"
                                         :rules="''"
                                        mode="aggressive"
                                      >
                                        <v-text-field
                                          :error-messages="errors"
                                          dense
                                          v-model="editedItem.contribution[i].east_bonus"
                                          label="Bono Oriente"
                                          :disabled="!enabled"
                                          :outlined="editar"
                                        ></v-text-field>
                                      </ValidationProvider>
                                    </v-col>
                                    <v-col cols="12" md="3" class="py-0 my-0">
                                      <ValidationProvider
                                        v-slot="{ errors }"
                                        name="Bono Cargo"
                                        :rules="''"
                                        mode="aggressive"
                                      >
                                        <v-text-field
                                          :error-messages="errors"
                                          dense
                                          v-model="editedItem.contribution[i].position_bonus"
                                          label="Bono Cargo"
                                          :disabled="!enabled"
                                          :outlined="editar"
                                        ></v-text-field>
                                      </ValidationProvider>
                                    </v-col>
                                    <v-col cols="12" md="3" class="py-0 my-0">
                                      <ValidationProvider
                                        v-slot="{ errors }"
                                        name="Bono Seguridad Ciudadana"
                                         :rules="''"
                                        mode="aggressive"
                                      >
                                        <v-text-field
                                          :error-messages="errors"
                                          dense
                                          v-model="editedItem.contribution[i].public_security_bonus"
                                          label="Bono Seguridad Ciudadana"
                                          :disabled="!enabled"
                                          :outlined="editar"
                                        ></v-text-field>
                                      </ValidationProvider>
                                    </v-col>
                                 </template> 
                                  <v-col cols="12" :md="lender_contribution.state_affiliate == 'Pasivo' ? 4 : 3" class="py-0 my-0" v-if="lender_contribution.state_affiliate == 'Pasivo'">
                                    <ValidationProvider
                                      v-slot="{ errors }"
                                      name="Renta dignidad"
                                      :rules="''"
                                      mode="aggressive"
                                    >
                                      <v-text-field
                                        :error-messages="errors"
                                        dense
                                        v-model="editedItem.contribution[i].dignity_rent"
                                        label="Renta dignidad"
                                        :disabled="!enabled"
                                        :outlined="editar"
                                      ></v-text-field>
                                    </ValidationProvider>
                                  </v-col>
                                </v-row>
                              </v-col>
                            </v-row>
                          </v-container>
                        </v-card-text>
                        <v-divider></v-divider>
                        <v-card-actions class="ma-0 pb-0">
                          <v-spacer></v-spacer>
                            <v-btn color="error" dense text @click.stop="close()">Cerrar</v-btn>
                            <v-btn color="success" dense text @click="save">Guardar</v-btn>
                        </v-card-actions>
                      </v-card>
                    </v-dialog>
                  </template>

                  <template v-slot:[`item.p_payable_liquid`]="{ item }">
                    {{ item.p_payable_liquid | money }}
                  </template>
                  <template v-slot:[`item.p_border_bonus`]="{ item }">
                    {{ item.p_border_bonus | money }}
                  </template>
                  <template v-slot:[`item.p_east_bonus`]="{ item }">
                    {{ item.p_east_bonus | money }}
                  </template>
                  <template v-slot:[`item.p_position_bonus`]="{ item }">
                    {{ item.p_position_bonus | money }}
                  </template>
                  <template v-slot:[`item.p_public_security_bonus`]="{ item }">
                    {{ item.p_public_security_bonus | money }}
                  </template>

                  <template v-slot:[`item.actions`]="{ item }">
                    <v-icon small class="mr-2" color="blue" @click="editItem(item)">mdi-pencil</v-icon>
                    <v-icon small color="error" @click="deleteItem(item)">X</v-icon>
                  </template>
                  <template v-slot:no-data>
                    <!--<v-btn color="primary" @click=" initialize">Reset</v-btn>-->
                  </template>
                </v-data-table>
              </v-col>
            </v-row>
          </v-container>
        </v-col>
      </v-row>
    </v-form>
  </v-flex>
</template>
<script>
export default {
  name: "ballots_hipotecary",
  props: {
    modalidad: {
      type: Object,
      required: true
    },
    contrib_codebtor: {
      type: Array,
      required: true
    },
    affiliate: {
      type: Object,
      required: true
    },
    global_parameters:{
      type: Object,
      required:true
    },
  },
  data: () => ({
    affiliate_codebtor_ci: null,
    editar: true,
    bonos: {},
    payable_liquid: {},
    exist_codebtor: true,
    ver: false,
    dialog: false,
    headers1: [
      {
        text: "id",
        class: ["normal", "white--text"],
        sortable: false,
        value: "id_affiliate"
      },
      {
        text: "CI",
        class: ["normal", "white--text"],
        sortable: false,
        value: "identity_card"
      },
      {
        text: "Nombre Afiliado",
        class: ["normal", "white--text"],
        sortable: false,
        value: "full_name"
      },
      {
        text: "Liquido Pagable Promedio",
        class: ["normal", "white--text"],
        value: "p_payable_liquid"
      },
      {
        text: "Bono Frontera Promedio",
        class: ["normal", "white--text"],
        value: "p_border_bonus"
      },
      {
        text: "Bono Oriente Promedio",
        class: ["normal", "white--text"],
        value: "p_east_bonus"
      },
      {
        text: "Bono Cargo Promedio",
        class: ["normal", "white--text"],
        value: "p_position_bonus"
      },
      {
        text: "Bono Seguridad Ciudadana Promedio",
        class: ["normal", "white--text"],
        value: "p_public_security_bonus"
      },
      {
        text: "Actions",
        class: ["normal", "white--text"],
        value: "actions",
        sortable: false
      }
    ],
    editedIndex: -1,
    editedItem: {
      id_affiliate: null,
      identity_card: null,
      first_name:null,
      second_name:null,
      last_name: null,
      mothers_last_name: null,
      full_name:null,
      contribution: [],
      p_payable_liquid: 0,
      p_border_bonus: 0,
      p_east_bonus: 0,
      p_position_bonus: 0,
      p_public_security_bonus: 0
    },
    defaultItem: {
      id_affiliate: "por defecto",
      identity_card: null,
      first_name:null,
      second_name:null,
      last_name: null,
      mothers_last_name: null,
      full_name:null,
      contribution: [],
      p_payable_liquid: 0,
      p_border_bonus: 0,
      p_east_bonus: 0,
      p_position_bonus: 0,
      p_public_security_bonus: 0
    },
        choose_diff_month: false,
    number_diff_month: 1,
    lender_contribution: {},
        enabled: false,
    editar:true,
    affiliate_contribution: {},
    data_ballots: [],
    miData: []
  }),

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "Nueva Boleta" : "Editar Boleta"
    }
  },
  watch: {
    dialog(val) {
      val || this.close()
    }
  },
  beforeMount() {
    //this.getBallots(this.$route.query.affiliate_id)
    //alert(this.$route.query.affiliate_id)
  },
  methods: {
    //Metodo para sacar boleta de un afiliado
    async getBallots(id) {
    try {
        let data_ballots = []
        let res = await axios.get(`affiliate/${id}/contribution`, {
         params:{
           city_id: this.$store.getters.cityId,
           choose_diff_month: this.choose_diff_month,
           number_diff_month: this.number_diff_month,
           sortBy: ['month_year'],
           sortDesc: [1],
           per_page: this.modalidad.quantity_ballots,
           page: 1,
         }
        })
        this.lender_contribution = res.data
        this.affiliate_contribution.valid = this.lender_contribution.valid
        this.affiliate_contribution.state_affiliate = this.lender_contribution.state_affiliate
        this.affiliate_contribution.name_table_contribution = this.lender_contribution.name_table_contribution
        //console.log(res)
        this.data_ballots = res.data.data
        if (res.data.valid) {
          this.editar = false
          this.editedItem.id_affiliate = this.data_ballots[0].affiliate_id
          for (let i = 0; i < this.modalidad.quantity_ballots; i++) {

          if(this.lender_contribution.valid && this.lender_contribution.state_affiliate =='Activo'){
            this.enabled = false
            this.editar=false

            this.editedItem.contribution[i].contributionable_id = this.data_ballots[i].id
            this.editedItem.contribution[i].payable_liquid = this.data_ballots[i].payable_liquid != null ? this.data_ballots[i].payable_liquid : 0
            this.editedItem.contribution[i].border_bonus = this.data_ballots[i].border_bonus != null ? this.data_ballots[i].border_bonus : 0
            this.editedItem.contribution[i].east_bonus = this.data_ballots[i].east_bonus != null ? this.data_ballots[i].east_bonus : 0
            this.editedItem.contribution[i].position_bonus = this.data_ballots[i].position_bonus != null ? this.data_ballots[i].position_bonus : 0
            this.editedItem.contribution[i].public_security_bonus = this.data_ballots[i].public_security_bonus != null ? this.data_ballots[i].public_security_bonus : 0
            this.editedItem.contribution[i].period = this.$moment(this.data_ballots[i].month_year).format('YYYY-MM-DD')
            this.editedItem.contribution[i].month = this.$moment(this.data_ballots[i].month_year).format('MMMM')
            //Promedios
            this.editedItem.p_payable_liquid = this.editedItem.p_payable_liquid + parseFloat(this.data_ballots[i].payable_liquid != null ? this.data_ballots[i].payable_liquid : 0)
            this.editedItem.p_border_bonus = this.editedItem.p_border_bonus + parseFloat(this.data_ballots[i].border_bonus != null ? this.data_ballots[i].border_bonus : 0)
            this.editedItem.p_east_bonus = this.editedItem.p_east_bonus +  parseFloat(this.data_ballots[i].east_bonus != null ? this.data_ballots[i].east_bonus : 0)
            this.editedItem.p_position_bonus = this.editedItem.p_position_bonus + parseFloat(this.data_ballots[i].position_bonus != null ? this.data_ballots[i].position_bonus : 0)
            this.editedItem.p_public_security_bonus = this.editedItem.p_public_security_bonus +  parseFloat(this.data_ballots[i].public_security_bonus != null ? this.data_ballots[i].public_security_bonus : 0)
        
        } else if(!this.lender_contribution.valid && this.lender_contribution.state_affiliate =='Activo'){
            this.enabled = false
            this.editar=false

            this.editedItem.contribution[i].contributionable_id = 0
            this.editedItem.contribution[i].payable_liquid = 0
            this.editedItem.contribution[i].border_bonus = 0
            this.editedItem.contribution[i].east_bonus = 0
            this.editedItem.contribution[i].position_bonus = 0
            this.editedItem.contribution[i].public_security_bonus = 0
            this.editedItem.contribution[i].period = this.$moment(this.lender_contribution.current_tiket).subtract(this.modalidad.quantity_ballots-1-i,'months').format('YYYY-MM-DD')
            this.editedItem.contribution[i].month = this.$moment(this.lender_contribution.current_tiket).subtract(this.modalidad.quantity_ballots-1-i,'months').format('MMMM')
         
        } else{
          this.toastr.error("el afiliado no es activo")
        }   

        }
        this.editedItem.p_payable_liquid = (this.editedItem.p_payable_liquid / this.modalidad.quantity_ballots)
        this.editedItem.p_border_bonus = (this.editedItem.p_border_bonus / this.modalidad.quantity_ballots)
        this.editedItem.p_east_bonus = (this.editedItem.p_east_bonus / this.modalidad.quantity_ballots)
        this.editedItem.p_position_bonus = (this.editedItem.p_position_bonus / this.modalidad.quantity_ballots)
        this.editedItem.p_public_security_bonus = (this.editedItem.p_public_security_bonus / this.modalidad.quantity_ballots)
      
      }
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    },

    editItem(item) {
      this.editedIndex = this.contrib_codebtor.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialog = true
    },

    deleteItem(item) {
      const index = this.contrib_codebtor.indexOf(item)
      confirm("Esta seguro que?") && this.contrib_codebtor.splice(index, 1)
    },

    close() {
      this.dialog = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      })
    },

    async save() {
      console.log(this.contrib_codebtor)
      await this.saveAdjustment()      
      if (this.editedIndex > -1) {
        Object.assign(this.contrib_codebtor[this.editedIndex], this.editedItem)
      } else {
        this.contrib_codebtor.push(this.editedItem)
      }         
      this.close()      
    },

    async searchCodebtor() {
      try {
        if(this.affiliate_codebtor_ci != this.affiliate.identity_card) {
          if(this.contrib_codebtor.length < this.modalidad.max_lenders-1){
            let resp = await axios.get(`affiliate_existence`, {
              params: {
                identity_card: this.affiliate_codebtor_ci
              }
            })
            this.affiliate_codebtor = resp.data
            this.exist_codebtor = this.affiliate_codebtor.state
            let codebtor_information = this.affiliate_codebtor.information
            let state_affiliate = this.affiliate_codebtor.affiliate.affiliate_state.name
            console.log(state_affiliate)
            this.ver = true
            if (this.exist_codebtor){
              if(state_affiliate =='Servicio' || state_affiliate =='Disponibilidad' ){
                if(codebtor_information){
                  this.choose_diff_month = false
                  this.number_diff_month = 1
                  this.getBallots(this.affiliate_codebtor.affiliate.id)
                  this.generateContributions()
                  console.log("ID DEL AFILIADO " + this.affiliate_codebtor)
                  this.getAffiliate(this.affiliate_codebtor.affiliate.id)
                  this.dialog = true
                }else{
                  this.toastr.error("No se tiene la información actualizada del Codeudor. Por favor actualice sus datos.")
                }
              }else{
                this.toastr.error("El afiliados debe tener el estado de Disponibilidad ó Servicio.")
              }
            }
          }else{
            this.toastr.error("La cantidad máxima de codeudores afiliados es de " + (this.modalidad.max_lenders -1) +".")
          }
        }
        else{
          this.toastr.error("El Codeudor afiliado no puede tener el mismo carnet que el titular.")
        } 
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    },
    async getAffiliate(id){
      try {
        let res= await axios.get(`affiliate/${id}`)
        let data_codebtor = res.data
        this.editedItem.identity_card = data_codebtor.identity_card
        this.editedItem.first_name = data_codebtor.first_name
        this.editedItem.second_name = data_codebtor.second_name
        this.editedItem.last_name = data_codebtor.last_name
        this.editedItem.mothers_last_name = data_codebtor.mothers_last_name
        this.editedItem.full_name = this.$options.filters.fullName(data_codebtor, true)

        console.log(data_codebtor)
      } catch (e) {
        console.log(e)
      }
    },
    generateContributions () {
    this.editedItem.contribution = []
    for (let i = 0; i < this.modalidad.quantity_ballots; i++) {
      this.editedItem.contribution.push({
        contributionable_id: 0,
        payable_liquid: 0,
        position_bonus: 0,
        border_bonus: 0,
        public_security_bonus: 0,
        east_bonus: 0,
        dignity_rent: 0,
        period: null,
        adjustment_amount: 0,
        adjustment_description: null,
        loan_contributions_adjust_id: 0,
      })
    }
   
  },
 getContributions() {
    return this.editedItem.contribution
  },

      ///ajuste
  async saveAdjustment(){
    this.editedItem.contributionable_ids = []
    this.editedItem.loan_contributions_adjust_ids = []
 
    try {

      let loan_contributions_adjust_ids = []
      let loan_contributions_adjust_id = 0
      
      this.editedItem.contribution.forEach(async (item, i) => { //introducir su contribución        
        if(this.affiliate_contribution.state_affiliate == 'Activo') {
            if (this.editedItem.contributionable_ids.indexOf(this.editedItem.contribution[i].contributionable_id) === -1) {
                this.editedItem.contributionable_ids.push(this.editedItem.contribution[i].contributionable_id)
          }
          this.editedItem.contributionable_type = 'contributions'
        } 
        //Para el ajuste
        if(this.editedItem.contribution[i].adjustment_amount > 0){ //aqui se debe colocar la edicion del ajuste, hacer condicional
          //guardar el ajuste
 
          let res = await axios.post(`loan_contribution_adjust/updateOrCreate`, {
            affiliate_id: this.affiliate_codebtor.affiliate.id,
            adjustable_id: this.editedItem.contribution[i].contributionable_id,
            adjustable_type: this.affiliate_contribution.name_table_contribution,
            type_affiliate: 'cosigner',
            amount: this.editedItem.contribution[i].adjustment_amount,
            type_adjust: 'adjust',
            period_date: this.$moment(this.fecha).format('YYYY-MM-DD'),
            description: this.editedItem.contribution[i].adjustment_description
          })

          loan_contributions_adjust_id  = res.data.id
         
          if ( loan_contributions_adjust_ids.indexOf( loan_contributions_adjust_id) === -1) {
              loan_contributions_adjust_ids.push( loan_contributions_adjust_id)
          }

        }else{
          console.log('no tiene ajuste')
        }    
      })
        this.editedItem.loan_contributions_adjust_ids = loan_contributions_adjust_ids   
     //debugger
      //alert(this.editedItem.loan_contributions_adjust_ids)
    }  catch (e) {
      console.log(e)
    }
  },
    appendIconCallback () {
      if(this.number_diff_month < this.global_parameters.max_months_go_back){
      this.number_diff_month++
      this.choose_diff_month = true
      this.getBallots(this.affiliate_codebtor.affiliate.id)
    }
  },
  prependIconCallback () {

      if(this.number_diff_month > 1){
      this.number_diff_month--
      this.choose_diff_month = true
      this.getBallots(this.affiliate_codebtor.affiliate.id)
    }
  },

  }
}

</script>