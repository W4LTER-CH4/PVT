<template>
  <v-container fluid class="pl-0 pt-0">
    <v-row justify="center" class="py-0">
      <v-col cols="12" class="py-0">
        <v-row justify="center" class="py-0">
          <v-col cols="12" class="py-0">
              <p style="color:teal"> <b>HISTORIAL DEL TRAMITE</b></p>
              <v-card flat tile>
                <v-card-text>
                  <v-col cols="12" class="mb-0">
                    <v-data-table
                      :headers="headersHist"
                      :items="record"
                      :items-per-page="6"
                      class="elevation-1"
                    >
                      <template v-slot:item="items">
                        <tr>
                          <td>{{items.item.created_at|datetime}}</td>
                          <td>{{items.item.updated_at|datetime}}</td>
                              <td>{{items.item.action}}</td>
                          </tr>
                      </template>
                    </v-data-table>
                  </v-col>
                </v-card-text>
              </v-card>
          </v-col>
        </v-row>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>


export default {
  name: "history-flow",
  data: () => ({
    //valor: false,
    observation_type: [],
    bus: new Vue(),
    headersHist: [
      {
        text: "Fecha creación",
        class: ["normal", "white--text"],
        align: "left",
        value: "created_at"
      },
      {
        text: "Fecha actualización",
        class: ["normal", "white--text"],
        align: "left",
        value: "update_at"
      },
      {
        text: "Acciones realizadas",
        class: ["normal", "white--text"],
        align: "left",
        value: "accion"
      }
    ],
      headersHist2: [
      {
        text: "Fecha creación",
        class: ["normal", "white--text"],
        align: "left",
        value: "created_at"
      },
      {
        text: "Fecha actualización",
        class: ["normal", "white--text"],
        align: "left",
        value: "update_at"
      },
      {
        text: "Acciones realizadas",
        class: ["normal", "white--text"],
        align: "left",
        value: "accion"
      }
    ],
    headersObs: [
      {
        text: "Usuario",
        class: ["normal", "white--text"],
        align: "left",
        value: "user_id"
      },
      {
        text: "Observación",
        class: ["normal", "white--text"],
        align: "left",
        value: "observation_type_id"
      },
      {
        text: "Mensaje",
        class: ["normal", "white--text"],
        align: "left",
        value: "message"
      },
      {
        text: "Fecha",
        class: ["normal", "white--text"],
        align: "left",
        value: "date"
      },
    
    ],
    record: [],
    //record_payment: []
  }),
  props: {
    affiliate: {
      type: Object,
      required: true,
    },
    loan: {
      type: Object,
      required: true
    },
    observations: {
      type: Array,
      required: true
    },
    bus1: {
      type: Object,
      required: true
    },
  },

  mounted() {
    this.getObservationType()
    this.getRecords(this.loan.id)
  },
  methods: {
  
    async getObservationType() {
      try {
        this.loading = true
        let res = await axios.get(
          `module/${this.$store.getters.module.id}/observation_type`
        )
        this.observation_type = res.data
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    },
    async getRecords(id) {
      try {
        this.loading = true
        let res = await axios.get(`record`, {
          params: {
            loan_id: id
          }
        })
        this.record = res.data.data
        console.log(this.record)
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    },
  }
}
</script>