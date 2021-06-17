<template>
  <v-container fluid class="ma-0 pa-0">
    <v-row>
      <v-col cols="12" class="text-center">
        <v-row>
          <v-col cols="12" class="text-center py-0" style="margin-bottom:10px">
            <v-card class="py-0" color="#151515" dark max-width="100%" max-height="500">
              <v-card-text class="headline font-weight-bold">
                <v-row>
                  <v-col cols="5" class="py-0">
                    <h6><v-icon large left style="font-size: 25px;">
                      mdi-shield-account
                    </v-icon>
                    <strong><b style="color:white">PRESTATARIO: </b></strong>
                      {{ $options.filters.fullName(affiliate, true) }}</h6>
                    <h6><strong><b style="color:white">CI: </b></strong>
                      {{ affiliate.identity_card }}</h6>
                    <div v-if="loan.disbursable_type == 'spouses'">
                      <h6><v-icon large left style="font-size: 25px;">
                        mdi-account-heart
                        </v-icon>
                      <strong><b style="color:white">CONYUGUE:</b></strong> {{ $options.filters.fullName(spouse, true) }}
                      <b style="color:white">C.I: </b> {{ spouse.identity_card }}</h6>
                    </div>
                    <div v-for="(lenders,i) in loan.lenders" :key="i" v-show="loan.lenders.length > 1">
                      <h6><strong><b style="color:white">CODEUDOR: </b>{{  $options.filters.fullName(lenders,true)}}</strong>
                      <b style="color:white">C.I: </b>{{lenders.identity_card}} </h6>
                    </div>
                  </v-col>
                  <v-col cols="7" class="py-0 ">
                    <h6><v-icon large left style="font-size: 25px;">
                    mdi-bank
                    </v-icon>
                    <strong><b style="color:white">MODALIDAD:</b></strong>
                      {{ procedure_modality_name | uppercase }}</h6>
                    <h6><strong><b style="color:white">MONTO SOLICITADO:</b></strong>
                      {{ loan.amount_approved | money}}  Bs
                    <strong><b style="color:white">MESES PLAZO:</b></strong>
                      {{ loan.loan_term }}</h6>
                  </v-col>
                </v-row>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import common from "@/plugins/common"
export default {
  name: "flow-dashboard",
  props: {
    affiliate: {
      type: Object,
      required: true
    },
    loan: {
      type: Object,
      required: true
    },
    spouse: {
      type: Object,
      required: true
    }
  },
  data: () => ({
    loading: false,
    degree_name: null,
    unit_name: null,
    last_name: null,
    mothers_last_name: null,
    last_name:null,
    first_name:null,
    //identity_card: null,
    procedure_modality_name: ""
  }),
  computed: {
    isNew() {
      return this.$route.params.id == "new"
    },

    /*spouseNombre: function() {
      return (
        this.spouse.mothers_last_name +
        " " +
        this.spouse.last_name +
        " " +
        this.spouse.first_name
      );
    }*/
  },
  watch: {
    affiliate(newVal, oldVal) {
      if (oldVal != newVal) {
        if (newVal.hasOwnProperty("degree_id"))
          this.getDegree_name(newVal.degree_id);
        if (newVal.hasOwnProperty("unit_id")) this.getUnit_name(newVal.unit_id)
      }
    },
    loan(newVal, oldVal) {
      if (oldVal != newVal) {
        if (newVal.hasOwnProperty("procedure_modality_id"))
          this.getProcedureModalityName(newVal.procedure_modality_id);
      }
    }
  },
  methods: {
    async getDegree_name(id) {
      try {
        this.loading = true
        let res = await axios.get(`degree/${id}`)
        this.degree_name = res.data.name;
      } catch (e) {
        console.log(e);
      } finally {
        this.loading = false;
      }
    },
    async getUnit_name(id) {
      try {
        this.loading = true;
        let res = await axios.get(`unit/${id}`)
        this.unit_name = res.data.name;
      } catch (e) {
        console.log(e);
      } finally {
        this.loading = false;
      }
    },
    async getProcedureModalityName(id) {
      try {
        this.loading = true;
        let res = await axios.get(`procedure_modality/${id}`)
        this.procedure_modality_name = res.data.name
        console.log(this.procedure_modality_name)
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    }
  }
};
</script>