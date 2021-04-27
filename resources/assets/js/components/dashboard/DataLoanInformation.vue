<template>
  <v-card flat>
    <v-card-text>
      <v-container class="py-0">
        <v-card color="grey lighten-1" class="ma-0 pa-3">

       
          <!--TITULAR--->
          <template v-if="Object.entries(loans_lender).length !== 0">
            <template v-if="ver && loans_lender.loans.length > 0">
              <h3 class="pa-1 text-center">PRÉSTAMOS SOLICITADOS POR EL TITULAR</h3>
              <v-row>
                <v-col cols="12" md="12" class="py-0">
                  <v-card>
                    <v-data-table
                      class="text-uppercase"
                      dense
                      :headers="headers_loans"
                      :items="loans_lender.loans"
                      :loading="loading"
                      :items-per-page="10"
                      :footer-props="{ itemsPerPageOptions: [10, 15, 30] }"
                    >
                      <template v-slot:[`item.shortened`]="{ item }">
                        <v-tooltip top>
                          <template v-slot:activator="{ on }">
                            <span v-on="on">{{ item.shortened }}</span>
                          </template>
                          <span> {{ item.modality }}</span>
                        </v-tooltip>
                      </template>

                      <template v-slot:[`item.request_date`]="{ item }">
                        {{ item.request_date | date }}
                      </template>
                      <template v-slot:[`item.disbursement_date`]="{ item }">
                        {{ item.disbursement_date | date }}
                      </template>
                      <template v-slot:[`item.amount`]="{ item }">
                        {{ item.amount | moneyString }}
                      </template>
                      <template v-slot:[`item.estimated_quota`]="{ item }">
                        {{ item.estimated_quota | moneyString }}
                      </template>
                      <template v-slot:[`item.balance`]="{ item }">
                        {{ item.balance | moneyString }}
                      </template>

                      <template v-slot:[`item.actions`]="{ item }">
                        <v-tooltip top>
                          <template v-slot:activator="{ on }">
                            <v-btn
                              v-if="item.origin == 'PVT'"
                              icon
                              small
                              v-on="on"
                              color="warning"
                              :to="{
                                name: 'flowAdd',
                                params: { id: item.id },
                              }"
                              target="_blank"
                              ><v-icon>mdi-eye</v-icon>
                            </v-btn>
                            <v-btn
                              v-else
                              icon
                              small
                              v-on="on"
                              color="warning"
                              @click.stop="routeSismu(item.id)"
                              ><v-icon>mdi-eye</v-icon>
                            </v-btn>
                          </template>
                          <span>Ver información</span>
                        </v-tooltip>
                      </template>
                    </v-data-table>
                  </v-card>
                </v-col>
              </v-row>
            </template>
            <template v-if="ver && loans_lender.guarantees.length > 0">
              <h3 class="pa-1 text-center">PRESTAMOS GARANTIZADOS POR EL TITULAR</h3>
              <v-row>
                <v-col cols="12" md="12" class="py-0">
                  <v-card>
                    <v-data-table
                      class="text-uppercase"
                      dense
                      :headers="headers_loans"
                      :items="loans_lender.guarantees"
                      :loading="loading"
                      :items-per-page="10"
                      :footer-props="{ itemsPerPageOptions: [10, 15, 30] }"
                    >
                      <template v-slot:[`item.shortened`]="{ item }">
                        <v-tooltip top>
                          <template v-slot:activator="{ on }">
                            <span v-on="on">{{ item.shortened }}</span>
                          </template>
                          <span> {{ item.modality }}</span>
                        </v-tooltip>
                      </template>

                      <template v-slot:[`item.request_date`]="{ item }">
                        {{ item.request_date | date }}
                      </template>
                      <template v-slot:[`item.disbursement_date`]="{ item }">
                        {{ item.disbursement_date | date }}
                      </template>
                      <template v-slot:[`item.amount`]="{ item }">
                        {{ item.amount | moneyString }}
                      </template>
                      <template v-slot:[`item.estimated_quota`]="{ item }">
                        {{ item.estimated_quota | moneyString }}
                      </template>
                      <template v-slot:[`item.balance`]="{ item }">
                        {{ item.balance | moneyString }}
                      </template>

                      <template v-slot:[`item.actions`]="{ item }">
                        <v-tooltip top>
                          <template v-slot:activator="{ on }">
                            <v-btn
                              v-if="item.origin == 'PVT'"
                              icon
                              small
                              v-on="on"
                              color="warning"
                              :to="{
                                name: 'flowAdd',
                                params: { id: item.id },
                              }"
                              ><v-icon>mdi-eye</v-icon>
                            </v-btn>
                            <v-btn
                              v-else
                              icon
                              small
                              v-on="on"
                              color="warning"
                              @click.stop="routeSismu(item.id)"
                              ><v-icon>mdi-eye</v-icon>
                            </v-btn>
                          </template>
                          <span>Ver información</span>
                        </v-tooltip>
                      </template>
                    </v-data-table>
                  </v-card>
                </v-col>
              </v-row>
            </template>
          </template>
            <!--CONYUGUE-->
            <template v-if="Object.entries(loans_spouse).length !== 0">
            <template v-if="loans_spouse.loans.length > 0 && ver">
              <h3 class="pa-1 text-center ">PRÉSTAMOS SOLICITADOS POR CONYUGUE</h3>
              <v-row>
                <v-col cols="12" md="12" class="py-0">
                  <v-card>
                    <v-data-table
                      class="text-uppercase"
                      dense
                      :headers="headers_loans"
                      :items="loans_spouse.loans"
                      :loading="loading"
                      :items-per-page="10"
                      :footer-props="{ itemsPerPageOptions: [10, 15, 30] }"
                    >
                      <template v-slot:[`item.shortened`]="{ item }">
                        <v-tooltip top>
                          <template v-slot:activator="{ on }">
                            <span v-on="on">{{ item.shortened }}</span>
                          </template>
                          <span> {{ item.modality }}</span>
                        </v-tooltip>
                      </template>

                      <template v-slot:[`item.request_date`]="{ item }">
                        {{ item.request_date | date }}
                      </template>
                      <template v-slot:[`item.disbursement_date`]="{ item }">
                        {{ item.disbursement_date | date }}
                      </template>
                      <template v-slot:[`item.amount`]="{ item }">
                        {{ item.amount | moneyString }}
                      </template>
                      <template v-slot:[`item.estimated_quota`]="{ item }">
                        {{ item.estimated_quota | moneyString }}
                      </template>
                      <template v-slot:[`item.balance`]="{ item }">
                        {{ item.balance | moneyString }}
                      </template>

                      <template v-slot:[`item.actions`]="{ item }">
                        <v-tooltip top>
                          <template v-slot:activator="{ on }">
                            <v-btn
                              v-if="item.origin == 'PVT'"
                              icon
                              small
                              v-on="on"
                              color="warning"
                              :to="{
                                name: 'flowAdd',
                                params: { id: item.id },
                              }"
                              target="_blank"
                              ><v-icon>mdi-eye</v-icon>
                            </v-btn>
                            <v-btn
                              v-else
                              icon
                              small
                              v-on="on"
                              color="warning"
                              @click.stop="routeSismu(item.id)"
                              ><v-icon>mdi-eye</v-icon>
                            </v-btn>
                          </template>
                          <span>Ver información</span>
                        </v-tooltip>
                      </template>
                    </v-data-table>
                  </v-card>
                </v-col>
              </v-row>
            </template>
            
            <template v-if="loans_spouse.guarantees.length > 0 && ver ">
              <h3 class="pa-1 text-center">PRESTAMOS GARANTIZADOS POR CONYUGUE</h3>
              <v-row>
                <v-col cols="12" md="12" class="py-0">
                  <v-card>
                    <v-data-table
                      class="text-uppercase"
                      dense
                      :headers="headers_loans"
                      :items="loans_spouse.guarantees"
                      :loading="loading"
                      :items-per-page="10"
                      :footer-props="{ itemsPerPageOptions: [10, 15, 30] }"
                    >
                      <template v-slot:[`item.shortened`]="{ item }">
                        <v-tooltip top>
                          <template v-slot:activator="{ on }">
                            <span v-on="on">{{ item.shortened }}</span>
                          </template>
                          <span> {{ item.modality }}</span>
                        </v-tooltip>
                      </template>

                      <template v-slot:[`item.request_date`]="{ item }">
                        {{ item.request_date | date }}
                      </template>
                      <template v-slot:[`item.disbursement_date`]="{ item }">
                        {{ item.disbursement_date | date }}
                      </template>
                      <template v-slot:[`item.amount`]="{ item }">
                        {{ item.amount | moneyString }}
                      </template>
                      <template v-slot:[`item.estimated_quota`]="{ item }">
                        {{ item.estimated_quota | moneyString }}
                      </template>
                      <template v-slot:[`item.balance`]="{ item }">
                        {{ item.balance | moneyString }}
                      </template>

                      <template v-slot:[`item.actions`]="{ item }">
                        <v-tooltip top>
                          <template v-slot:activator="{ on }">
                            <v-btn
                              v-if="item.origin == 'PVT'"
                              icon
                              small
                              v-on="on"
                              color="warning"
                              :to="{
                                name: 'flowAdd',
                                params: { id: item.id },
                              }"
                              ><v-icon>mdi-eye</v-icon>
                            </v-btn>
                            <v-btn
                              v-else
                              icon
                              small
                              v-on="on"
                              color="warning"
                              @click.stop="routeSismu(item.id)"
                              ><v-icon>mdi-eye</v-icon>
                            </v-btn>
                          </template>
                          <span>Ver información</span>
                        </v-tooltip>
                      </template>
                    </v-data-table>
                  </v-card>
                </v-col>
              </v-row>
            </template>

            </template> 
          
        </v-card>
      </v-container>
    </v-card-text>
  </v-card>
</template>

<script>
export default {
  name: "data-loan-information",
   props: {
    loans_lender: {
      type: Object,
      required: true
    },
    loans_spouse: {
      type: Object,
      required: true
    },
    loading: {
      type: Boolean,
      required: true
    },
    ver: {
      type: Boolean,
      required: true
    },
  },
  data: () => ({
    headers_loans: [
      {
        text: "Código",
        class: ["normal", "white--text"],
        align: "left",
        value: "code",
        width: "15%",
        sortable: true
      },
      {
        text: "Modalidad",
        class: ["normal", "white--text"],
        align: "left",
        value: "shortened",
        width: "5%",
        sortable: false
      },
      {
        text: "Fecha Solicitud",
        class: ["normal", "white--text"],
        align: "left",
        value: "request_date",
        width: "10%",
        sortable: true
      },
      {
        text: "Fecha Desembolso",
        class: ["normal", "white--text"],
        align: "left",
        value: "disbursement_date",
        width: "10%",
        sortable: true
      },
      {
        text: "Monto solicitado",
        class: ["normal", "white--text"],
        align: "left",
        value: "amount",
        width: "10%",
        sortable: false
      },

      {
        text: "Plazo",
        class: ["normal", "white--text"],
        align: "left",
        value: "loan_term",
        width: "10%",
        sortable: false
      },
      {
        text: "Cuota",
        class: ["normal", "white--text"],
        align: "left",
        value: "estimated_quota",
        width: "10%",
        sortable: false
      },
      {
        text: "Saldo",
        class: ["normal", "white--text"],
        align: "left",
        value: "balance",
        width: "10%",
        sortable: false
      },
      {
        text: "Tipo Trámite",
        class: ["normal", "white--text"],
        align: "left",
        value: "origin",
        width: "10%",
        sortable: false
      },
      {
        text: "Estado",
        class: ["normal", "white--text"],
        align: "left",
        value: "state",
        width: "15%",
        sortable: true
      },
      {
        text: "Acciones",
        value: "actions",
        class: ["normal", "white--text"],
        align: "center",
        sortable: false,
        width: "5%",
      },
    ],
    headers_observables: [
      {
        text: "CI",
        class: ["normal", "white--text"],
        align: "left",
        value: "PadCedulaIdentidad",
        width: "15%",
      },
      {
        text: "Código",
        class: ["normal", "white--text"],
        align: "left",
        value: "PresNumero",
        width: "15%",
        sortable: true
      },
      {
        text: "Modalidad",
        class: ["normal", "white--text"],
        align: "left",
        value: "PrdDsc",
        width: "20%",
      },
      {
        text: "Monto solicitado",
        class: ["normal", "white--text"],
        align: "left",
        value: "PresMntDesembolso",
        width: "10%",
      },
      {
        text: "Plazo",
        class: ["normal", "white--text"],
        align: "left",
        value: "PresMeses",
        width: "10%",
      },
      {
        text: "Cuota",
        class: ["normal", "white--text"],
        align: "left",
        value: "PresCuotaMensual",
        width: "10%",
      },
      {
        text: "Saldo",
        class: ["normal", "white--text"],
        align: "left",
        value: "PresSaldoAct",
        width: "10%",
      },
      {
        text: "Estado",
        class: ["normal", "white--text"],
        align: "left",
        value: "PresEstDsc",
        width: "15%",
        sortable: true
      },
      {
        text: "Acciones",
        value: "actions",
        class: ["normal", "white--text"],
        align: "center",
        sortable: false,
        width: "10%",
      },
    ],
    //loans: {},
    //affiliate_ci: null,
    //affiliate: {},
    //exist_affiliate: false,
    //ver: false,
    //loading: false,
    //degree_name: null,
    //category_name: null,
    //unit_name: null,
    //state_name_status: null,
  }),
  /*watch: {
    affiliate_ci() {
      this.ver = false;
    },
  },*/
  methods: {
    /*async getLoansHistory() {
      try {
        this.loading = true;
        let message = [];
        let res = await axios.get(`affiliate_record`, {
          params: {
            ci: this.affiliate_ci,
          },
        });
        this.loans = res.data;
        message = this.loans.message[0];
        if (message != "afiliado-inexistente") {
          this.exist_affiliate = true;
          this.ver = true;
          if (this.loans.tit_pvt) {
            this.getAffiliate(this.loans.id);
          }
        } else {
          this.exist_affiliate = false;
          this.ver = true;
          console.log("no coincide");
        }
      } catch (e) {
        console.log(e);
      } finally {
        this.loading = false;
      }
    },*/
    routeSismu(id) {
      window.open(
        "http://sismu.muserpol.gob.bo/musepol/akardex.aspx?" + id,
        "_blank"
      );
    },
    /*async getAffiliate(id) {
      try {
        this.loading = true;
        let res = await axios.get(`affiliate/${id}`);
        this.affiliate = res.data;
        this.getDegree_name(this.affiliate.degree_id);
        this.getCategory_name(this.affiliate.category_id);
        this.getUnit_name(this.affiliate.unit_id);
        this.getState_name(id);
      } catch (e) {
        console.log(e);
      } finally {
        this.loading = false;
      }
    },
    /*async getDegree_name(id) {
      try {
        this.loading = true;
        let res = await axios.get(`degree/${id}`);
        this.degree_name = res.data.name;
      } catch (e) {
        console.log(e);
      } finally {
        this.loading = false;
      }
    },*/
    /*async getCategory_name(id) {
      try {
        this.loading = true;
        let res = await axios.get(`category/${id}`);
        this.category_name = res.data.name;
      } catch (e) {
        console.log(e);
      } finally {
        this.loading = false;
      }
    },*/
    /*async getUnit_name(id) {
      try {
        this.loading = true;
        let res = await axios.get(`unit/${id}`);
        this.unit_name = res.data.name;
      } catch (e) {
        console.log(e);
      } finally {
        this.loading = false;
      }
    },*/
    /*async getState_name(id) {
      try {
        this.loading = true;
        let res = await axios.get(`affiliate/${id}/state`);
        this.state_name = res.data;
        this.state_name_type = this.state_name.affiliate_state_type.name;
        this.state_name_status = this.state_name.name;
      } catch (e) {
        console.log(e);
      } finally {
        this.loading = false;
      }
    },*/
    itemRowBackground: function (item) {
      return item.state === false ? "style-1" : "style-2";
    },
  },
};
</script>
<style>
.style-1 {
  background-color: rgb(82, 87, 43);
}
</style>