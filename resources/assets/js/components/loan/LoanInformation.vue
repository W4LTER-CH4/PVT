<template>
  <v-container fluid >
    <v-card>
    <v-row justify="center">
      <v-col cols="12"  >
        <v-container class="py-0">
          <ValidationObserver ref="observer">
          <v-form>
          <v-row>
            <v-col cols="12" md="4" class="py-0">
              Modalidad Prestamo
            </v-col>
            <v-col cols="12" md="4" class="py-0">
              Monto Solicitado
            </v-col>
            <v-col cols="12" md="4" class="py-0">
              Plazo Meses
            </v-col>
            <v-col cols="12" md="4" class="py-0">
              <ValidationProvider v-slot="{ errors }" name="Modalidad" rules="required">
              <v-select
                :error-messages="errors"
                dense
                :onchange="Onchange()"
                :items="modalities"
                v-model="loanTypeSelected"
                item-text="name"
                item-value="id"
              ></v-select>
              </ValidationProvider>
            </v-col>
            <v-col cols="12" md="4" class="py-0">
              <ValidationProvider v-slot="{ errors }" name="monto" rules="min:1|max:20" mode="aggressive">
              <v-text-field
                :error-messages="errors"
                dense
                readonly
                v-model="monto"
              ></v-text-field>
              </ValidationProvider>
            </v-col>
            <v-col cols="12" md="4" class="py-0" >
              <ValidationProvider v-slot="{ errors }" name="plazo" rules="min:1|max:20" mode="aggressive">
              <v-text-field
                :error-messages="errors"
                dense
                readonly
                v-model="plazo"
              ></v-text-field>
              </ValidationProvider>
            </v-col>
          </v-row>
          </v-form>
          </ValidationObserver>
        </v-container>
      </v-col>
    </v-row>
    </v-card>
  </v-container>
</template>
<script>
import Ballots from '@/components/loan/Ballots'
  export default {
  name: "loan-information",
  data: () => ({
    monto:null,
    plazo:null,
    interval:[],
    loanTypeSelected:null

  }),
  props: {
    modalities: {
      type: Array,
      required: true
    },
    datos: {
      type: Array,
      required: true
    },
  },
  beforeMount() {
    this.getLoanIntervals()
  },

  methods: {
    Onchange(){
      for (this.i = 0; this.i< this.interval.length; this.i++) {
        if(this.loanTypeSelected==this.interval[this.i].procedure_type_id)
        {
          this.monto= this.interval[this.i].maximum_amount,
          this.plazo= this.interval[this.i].maximum_term
        }
      }
          console.log(this.datos+'este son los datos')
         this.datos[0]=this.loanTypeSelected,
          this.datos[1]=this.monto,
          this.datos[2]=this.plazo
    },
    async getLoanIntervals() {
      try {
        let res = await axios.get(`loan_interval`)
        this.interval = res.data
       } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    },
  }
}
</script>
