<template>
  <v-container fluid>
    <v-card>
      <v-row justify="center">
        <v-col cols="12">
          <ValidationObserver ref="observerDestiny">
            <v-form>
              <v-container class="py-0">
                <v-row>
                  <v-col cols="12" md="2" class="py-0">
                    <label>Tipo de Depósitos:</label>
                  </v-col>
                  <v-col cols="12" md="3" class="py-0">
                    <ValidationProvider v-slot="{ errors }" name="Tipo Desembolso" rules="required">
                      <v-select
                        class="py-0"
                        :error-messages="errors"
                        dense
                        v-model="loanTypeSelected"
                        :onchange="Onchange()"
                        :items="payment_types"
                        item-text="name"
                        item-value="id"
                        outlined
                      ></v-select>
                    </ValidationProvider>
                  </v-col>
                  <!--<v-col cols="12" md="3" class="py-0" v-show="visible">
                      <v-text-field
                        label="Entidad Financiera"
                        class="py-0"
                        dense
                        v-model="entity"
                        readonly
                      ></v-text-field>
                  </v-col>-->

                  <v-col cols="12" md="3" class="py-0" v-show="visible">
                    <ValidationProvider
                      v-slot="{ errors }"
                      vid="financial_entity_id"
                      name="Entidad Financiera"
                      :rules="loanTypeSelected == 1 ? 'required': ''"
                    >
                      <v-select
                        :error-messages="errors"
                        dense
                        :items="entity"
                        item-text="name"
                        item-value="id"
                        label="Entidad Financiera"
                        v-model="affiliate.financial_entity_id"
                        :readonly="!editable || !permission.secondary"
                        :outlined="editable && permission.secondary"
                        :disabled="editable && !permission.secondary"
                      ></v-select>
                    </ValidationProvider>
                  </v-col>
                  <v-col cols="12" md="3" class="py-0" v-show="visible">
                    <ValidationProvider
                      v-slot="{ errors }"
                      vid="account_number"
                      name="Cuenta SIGEP Activa"
                      :rules="loanTypeSelected == 1 ? 'required': ''"
                    >
                      <v-text-field
                        :error-messages="errors"
                        label="Cuenta SIGEP Activa"
                        class="py-0"
                        dense
                        v-model="affiliate.account_number"
                        :readonly="!editable || !permission.secondary"
                        :outlined="editable && permission.secondary"
                        :disabled="editable && !permission.secondary"
                      ></v-text-field>
                    </ValidationProvider>
                  </v-col>
                  <v-col cols="12" md="1" class="ma-0 pa-0" v-show="visible">
                    <v-tooltip top>
                      <template v-slot:activator="{ on }">
                        <v-btn
                          fab
                          dark
                          x-small
                          :color="'error'"
                          bottom
                          right
                          v-on="on"
                          style="margin-right: 45px;"
                          @click.stop="resetForm()"
                          v-show="editable"
                        >
                          <v-icon>mdi-close</v-icon>
                        </v-btn>
                      </template>
                      <div>
                        <span>Cancelar</span>
                      </div>
                    </v-tooltip>
                    <v-tooltip top>
                      <template v-slot:activator="{ on }">
                        <v-btn
                          fab
                          dark
                          x-small
                          :color="'light-blue accent-4'"
                          bottom
                          right
                          v-on="on"
                          style="margin-right: 45px;margin-top:5px;"
                          @click.stop="clear()"
                          v-show="editable"
                        >
                          <v-icon>mdi-eraser</v-icon>
                        </v-btn>
                      </template>
                      <div>
                        <span>Borrar</span>
                      </div>
                    </v-tooltip>
                    <v-tooltip top>
                      <template v-slot:activator="{ on }">
                        <v-btn
                          fab
                          dark
                          x-small
                          :color="editable ? 'danger' : 'success'"
                          bottom
                          right
                          v-on="on"
                          style="margin-right: -9px; margin-top:5px;"
                          @click.stop="saveAffiliate()"
                        >
                          <v-icon v-if="editable">mdi-check</v-icon>
                          <v-icon v-else>mdi-pencil</v-icon>
                        </v-btn>
                      </template>
                      <div>
                        <span v-if="editable">Guardar</span>
                        <span v-else>Editar</span>
                      </div>
                    </v-tooltip>
                  </v-col>
                  <v-col cols="12" md="6" v-show="espacio"></v-col>
                  <v-col cols="12" md="2" class="py-1">
                    <label>Destino del Préstamo:</label>
                  </v-col>
                  <v-col cols="12" md="6">
                    <ValidationProvider v-slot="{ errors }" name="destino" rules="required">
                      <v-select
                        class="py-0"
                        :error-messages="errors"
                        v-model="loanTypeSelected2"
                        dense
                        :items="destino"
                        item-text="name"
                        item-value="id"
                        outlined
                      ></v-select>
                    </ValidationProvider>
                  </v-col>
                </v-row>
              </v-container>
            </v-form>
          </ValidationObserver>

          <!--Referencia personal-->
          <ValidationObserver ref="observerPerRef">
            <v-form>
              <v-container class="py-0" v-show="modalidad_personal_reference">
                <v-row>
                  <v-col cols="12" md="12">
                    <v-toolbar-title>REFERENCIA PERSONAL</v-toolbar-title>
                  </v-col>
                  <v-col cols="12" md="3">
                    <ValidationProvider
                      v-slot="{ errors }"
                      vid="first_name"
                      name="Primer Nombre"
                      rules="required|alpha_spaces|min:3|max:20"
                    >
                      <v-text-field
                        :error-messages="errors"
                        v-model="personal_reference.first_name"
                        dense
                        label="Primer Nombre"
                      ></v-text-field>
                    </ValidationProvider>
                  </v-col>
                  <v-col cols="12" md="3">
                    <ValidationProvider
                      v-slot="{ errors }"
                      vid="second_name"
                      name="Primer Nombre"
                      rules="alpha_spaces|min:3|max:20"
                    >
                      <v-text-field
                        :error-messages="errors"
                        v-model="personal_reference.second_name"
                        dense
                        label="Segundo Nombre"
                      ></v-text-field>
                    </ValidationProvider>
                  </v-col>
                  <v-col cols="12" md="3">
                    <ValidationProvider
                      v-slot="{ errors }"
                      name="Primer Apellido"
                      rules="alpha_spaces|min:3|max:20"
                    >
                      <v-text-field
                        :error-messages="errors"
                        v-model="personal_reference.last_name"
                        dense
                        label="Primer Apellido"
                      ></v-text-field>
                    </ValidationProvider>
                  </v-col>
                  <v-col cols="12" md="3">
                    <ValidationProvider
                      v-slot="{ errors }"
                      name="Segundo Apellido"
                      :rules="(personal_reference.last_name == null || personal_reference.last_name == '')? 'required' : ''+'alpha_spaces|min:3|max:20'"
                    >
                      <v-text-field
                        :error-messages="errors"
                        v-model="personal_reference.mothers_last_name"
                        dense
                        label="Segundo Apellido"
                      ></v-text-field>
                    </ValidationProvider>
                  </v-col>
                  <!--<v-col cols="12" md="3">
                    <ValidationProvider v-slot="{ errors }" name="CI" rules="required|min:3">
                      <v-text-field
                        :error-messages="errors"
                        v-model="personal_reference.identity_card"
                        dense
                        label="Cédula de Identidad"
                      ></v-text-field>
                    </ValidationProvider>
                  </v-col>
                  <v-col cols="12" md="3">
                    <ValidationProvider
                      v-slot="{ errors }"
                      name="Ciudad de Expedición"
                      rules="required"
                    >
                      <v-select
                        :error-messages="errors"
                        v-model="personal_reference.city_identity_card_id"
                        dense
                        :items="cities"
                        item-text="name"
                        item-value="id"
                        label="Ciudad de Expedición"
                      ></v-select>
                    </ValidationProvider>
                  </v-col>-->
                  <v-col cols="12" md="3">
                    <ValidationProvider
                      v-slot="{ errors }"
                      name="Teléfono"
                      rules="integer|min:7|max:12"
                    >
                      <v-text-field
                        :error-messages="errors"
                        v-model="personal_reference.phone_number"
                        dense
                        label="Telefono"
                      ></v-text-field>
                    </ValidationProvider>
                  </v-col>
                  <v-col cols="12" md="3">
                    <ValidationProvider
                      v-slot="{ errors }"
                      name="Celular"
                      rules="integer|min:7|max:12"
                    >
                      <v-text-field
                        :error-messages="errors"
                        v-model="personal_reference.cell_phone_number"
                        dense
                        label="Celular"
                      ></v-text-field>
                    </ValidationProvider>
                  </v-col>
                  <v-col cols="12" md="6">
                    <ValidationProvider v-slot="{ errors }" name="Direccion" rules="required">
                      <v-text-field
                        :error-messages="errors"
                        dense
                        v-model="personal_reference.address"
                        label="Dirección"
                        ></v-text-field>
                      </ValidationProvider>
                  </v-col>
                </v-row>
              </v-container>
            </v-form>
          </ValidationObserver>
        </v-col>
          <CoDebtor
            v-show="this.modalidad_max_cosigner > 0"
            :personal_codebtor="personal_codebtor"
            :modalidad_max_cosigner.sync="modalidad_max_cosigner"
          />
      </v-row>
    </v-card>
    <v-container class="py-0">
          <v-row>
            <v-spacer></v-spacer><v-spacer></v-spacer><v-spacer></v-spacer>
              <v-col class="py-0 pt-2">
                <v-btn text
                @click="beforeStepBus(5)">Atras</v-btn>
                <v-btn
                color="primary"
                @click="validateStepsFive()">
                Siguiente
                </v-btn>
              </v-col>
            </v-row>
    </v-container>
  </v-container>
</template>
<script>
import CoDebtor from "@/components/loan/CoDebtor";
export default {
  name: "loan-information",
  components: {
    CoDebtor
  },
  props: {
    loan_detail: {
      type: Object,
      required: true
    },
    modalidad_personal_reference: {
      type: Boolean,
      required: true,
      default: false
    },
    intervalos: {
      type: Object,
      required: true
    },
    bus: {
      type: Object,
      required: true
    },
    modalidad_id: {
      type: Number,
      required: true,
      default: 0
    },
    affiliate: {
      type: Object,
      required: true
    },
    personal_codebtor: {
      type: Array,
      required: true
    },
    modalidad_max_cosigner: {
      type: Number,
      required: true,
      default:0
    }
  },
  data: () => ({  
    cuenta: null,
    destino:[],
    visible: false,
    espacio: true,
    loanTypeSelected: null,
    loanTypeSelected2: null,
    payment_types: [],
    cities: [],
    entity: null,
    editedIndexPerRef: -1,
    personal_reference:{},
    val_destiny: false,
    val_per_ref: false,
    reference: [],
    cosigners:[],
    editable: false,
  }),
  watch: {
    modalidad_id(newVal, oldVal){
      this.getLoanDestiny()
    },
    modalidad_personal_reference() {
      if (this.modalidad_personal_reference == false) {
        this.personal_reference = {};
      }
    }
  },
  beforeMount() {
    this.getCities();
    this.getPaymentTypes();
  },
    computed: {
    permission() {
      return {
        primary: this.primaryPermission,
        secondary: this.secondaryPermission
      }
    },
    secondaryPermission() {
      if (this.affiliate.id) {
        return this.$store.getters.permissions.includes(
          "update-affiliate-secondary"
        )
      } else {
        return this.$store.getters.permissions.includes("create-affiliate")
      }
    },
    primaryPermission() {
      if (this.affiliate.id) {
        return this.$store.getters.permissions.includes(
          "update-affiliate-primary"
        )
      } else {
        return this.$store.getters.permissions.includes("create-affiliate")
      }
    },
  },
  methods: {
    beforeStepBus(val) {
      this.bus.$emit("beforeStepBus", val)
    },
    nextStepBus(val) {
      this.bus.$emit("nextStepBus", val)
    },
    Onchange() {
      for (let i = 0; i < this.payment_types.length; i++) {
        if (this.loanTypeSelected == this.payment_types[i].id) {
          if (this.payment_types[i].name == "Depósito Bancario") {
            this.visible = true
            this.espacio = false
            this.getEntity()
          } else {
            this.visible = false
            this.espacio = true            
          }
        }
      }
      if(this.visible){
        this.loan_detail.payment_type_id = this.loanTypeSelected
        this.loan_detail.financial_entity_id = this.affiliate.financial_entity_id
        this.loan_detail.number_payment_type = this.affiliate.account_number
        this.loan_detail.destiny_id = this.loanTypeSelected2
      }else{
        this.loan_detail.payment_type_id = this.loanTypeSelected
        this.loan_detail.financial_entity_id = null
        this.loan_detail.number_payment_type = null
        this.loan_detail.destiny_id = this.loanTypeSelected2
      }
    },
    async getPaymentTypes() {
      try {
        this.loading = true;
        let res = await axios.get(`payment_type`);
        this.payment_types = res.data;
        console.log(this.payment_types + "este es el tipo de desembolso");
      } catch (e) {
        console.log(e);
      } finally {
        this.loading = false;
      }
    },
    async getCities() {
      try {
        this.loading = true;
        let res = await axios.get(`city`);
        this.cities = res.data;
      } catch (e) {
        this.dialog = false;
        console.log(e);
      } finally {
        this.loading = false;
      }
    },
    async getLoanDestiny() {
      try {
        this.loading = true
        let res = await axios.get(`procedure_type/${this.intervalos.procedure_type_id}/loan_destiny`)
        this.destino = res.data
        console.log(this.destino+'estos son los destinos');
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    },
    /*async getEntityAffiliate() {
      try {
        this.loading = true
        let res = await axios.get(`financial_entity/${this.affiliate.financial_entity_id}`)
        this.entity = res.data.name
      } catch (e) {
        console.log(e)
      }finally {
          this.loading = false
        }
    },*/
    async getEntity() {
      try {
        this.loading = true;
        let res = await axios.get(`financial_entity`);
        this.entity = res.data;
      } catch (e) {
        this.dialog = false;
        console.log(e);
      } finally {
        this.loading = false;
      }
    },
    async savePersonalReference()
    {
      try{
        if (this.modalidad_personal_reference) {
          this.reference = []        
          if (this.editedIndexPerRef == -1){
            let res = await axios.post(`personal_reference`, {
              city_identity_card_id:this.personal_reference.city_identity_card_id,
              identity_card:this.personal_reference.identity_card,
              last_name:this.personal_reference.last_name,
              mothers_last_name:this.personal_reference.mothers_last_name,
              first_name:this.personal_reference.first_name,
              second_name:this.personal_reference.second_name,
              phone_number:this.personal_reference.phone_number,
              cell_phone_number:this.personal_reference.cell_phone_number,
              address:this.personal_reference.address
            })         
            this.editedIndexPerRef = res.data.id
            this.reference.push(res.data.id)
          }else{
            let res = await axios.patch(`personal_reference/${this.editedIndexPerRef}`, 
            {
              city_identity_card_id:this.personal_reference.city_identity_card_id,
              identity_card:this.personal_reference.identity_card,
              last_name:this.personal_reference.last_name,
              mothers_last_name:this.personal_reference.mothers_last_name,
              first_name:this.personal_reference.first_name,
              second_name:this.personal_reference.second_name,
              phone_number:this.personal_reference.phone_number,
              cell_phone_number:this.personal_reference.cell_phone_number,
              address:this.personal_reference.address
            })   
            this.reference.push(res.data.id)     
          }
          this.loan_detail.reference = this.reference
        }
      } catch (e) {
        console.log(e)
         this.$refs.observer.setErrors(e)
      } finally {
        this.loading = false
      }
    },
    async savePCosigner() {
      try {
        let ids_codebtor=[]
        for (let i = 0; i < this.personal_codebtor.length; i++) {
          let res = await axios.post(`personal_reference`, {
            city_identity_card_id: this.personal_codebtor[i].city_identity_card_id,
            identity_card: this.personal_codebtor[i].identity_card,
            last_name: this.personal_codebtor[i].last_name,
            mothers_last_name: this.personal_codebtor[i].mothers_last_name,
            first_name: this.personal_codebtor[i].first_name,
            second_name: this.personal_codebtor[i].second_name,
            phone_number: this.personal_codebtor[i].phone_number,
            cell_phone_number: this.personal_codebtor[i].cell_phone_number,
            address: this.personal_codebtor[i].address,
            civil_status: this.personal_codebtor[i].civil_status,
            gender: this.personal_codebtor[i].gender,
            cosigner: true,
            city_birth_id: this.personal_codebtor[i].city_birth_id
          })
          ids_codebtor.push(res.data.id)
          console.log(this.personal_codebtor.length)
          console.log(ids_codebtor)
        }
        this.loan_detail.cosigners = ids_codebtor
        console.log(this.loan_detail.cosigners)
      } catch (e) {
        this.dialog = false
        console.log(e)
      } finally {
        this.loading = false
      }
    },
    async saveAffiliate() {
      try {
        if (!this.editable) {
          this.editable = true
        } else {
          await axios.patch(`affiliate/${this.affiliate.id}`, {
            financial_entity_id: this.affiliate.financial_entity_id,
            account_number: this.affiliate.account_number,
            sigep_status: (this.affiliate.financial_entity_id != null && this.affiliate.account_number != null) ? 'ACTIVO' : null
          })
          this.toastr.success("Registro guardado correctamente")
          this.editable = false
        }
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    },
    resetForm() {
      this.editable = false
    },
    clear(){
      this.affiliate.financial_entity_id = null
      this.affiliate.account_number= null
    },
     async validateStepsFive(){

      try {
        this.val_destiny = await this.$refs.observerDestiny.validate();
        if (this.val_destiny ) {
          if(this.modalidad_personal_reference){
            this.val_per_ref = await this.$refs.observerPerRef.validate();
            if(this.val_per_ref){
                this.savePersonalReference()
                this.savePCosigner() 
                this.nextStepBus(5)
            }else{
                console.log("no pasa")
            }
          }else{
            this.savePersonalReference()
            this.savePCosigner() 
            this.nextStepBus(5)
          }
        }else{
          console.log("no pasa")
        }
    
      }catch (e) {
        this.$refs.observerDestiny.setErrors(e);
        if(this.modalidad_personal_reference){
        this.$refs.observerPerRef.setErrors(e)
        }
      }
     }
  }
};
</script>