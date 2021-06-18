<template>
  <v-container fluid>
    <ValidationObserver>
      <v-form>
        <v-card flat>
          <v-card-title class="pa-0 ma-0">
            <v-toolbar dense color="tertiary" class="font-weight-regular">
              <v-toolbar-title>FONDO ROTATORIO</v-toolbar-title>
            </v-toolbar>
          </v-card-title>
          <v-data-table
            :headers="headers"
            :items="fund_rotatory_list"
            :loading="loading"
            :footer-props="{ itemsPerPageOptions: [50,100] }"
            multi-sort
            single-expand
            :key="refreshFoundRotatoryTable"
          >
            <!--Modal-->
            <template v-slot:top>
              <v-divider class="mx-4" inset vertical></v-divider>
              <v-spacer></v-spacer>
              <v-tooltip top>
                <template v-slot:activator="{ on }">
                  <v-btn
                    fab
                    x-small
                    color="warning"
                    dark
                    v-on="on"
                    right
                    absolute
                    style="margin-top: -60px; margin-right:40px"
                    @click="getFundRotary()"
                  >
                    <v-icon>mdi-refresh</v-icon>
                  </v-btn>
                </template>
                <span>Recargar información</span>
              </v-tooltip>

              <v-dialog v-model="dialog" max-width="500px">
                <template v-slot:activator="{ on }">
                  <v-btn
                    fab
                    x-small
                    color="success"
                    dark
                    v-on="on"
                    right
                    absolute
                    style="margin-top: -60px"
                  >
                    <v-icon>mdi-plus</v-icon>
                  </v-btn>
                </template>

                <v-card>
                  <v-card-title>
                    <span class="text-h5">Registro</span>
                  </v-card-title>

                  <v-card-text>
                    <v-container>
                      <v-row>
                        <v-col cols="12" sm="6" md="6">
                          <v-text-field
                            v-model="fund_rotatory_item.check_number"
                            label="Nro Cheque"
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="6" md="6">
                          <v-text-field
                            v-model="fund_rotatory_item.date_check_delivery"
                            label="Fecha de entrega cheque"
                            type="date"
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="6" md="12">
                          <v-text-field
                            v-model="fund_rotatory_item.amount"
                            label="Monto"
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="6" md="12">
                          <v-text-field
                            v-model="fund_rotatory_item.description"
                            label="Descripción"
                          ></v-text-field>
                        </v-col>
                      </v-row>
                    </v-container>
                  </v-card-text>

                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="error" text @click="close()">
                      CANCELAR
                    </v-btn>
                    <v-btn color="success" text @click="saveFundRotary()">
                      GUARDAR
                    </v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>
              <!--<v-dialog v-model="dialogDelete" max-width="500px">
                <v-card>
                  <v-card-title class="text-h5"
                    >Are you sure you want to delete this item?</v-card-title
                  >
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="closeDelete"
                      >Cancel</v-btn
                    >
                    <v-btn color="blue darken-1" text @click="deleteItemConfirm"
                      >OK</v-btn
                    >
                    <v-spacer></v-spacer>
                  </v-card-actions>
                </v-card>
              </v-dialog>-->
            </template>
            <!--Encabezados y registros-->
            <template v-slot:item="props">
              <tr :class="props.isExpanded ? 'info white--text' : ''">
                <td @click.stop="props.expand(!props.isExpanded)">
                  {{ props.item.code_entry | uppercase }}
                </td>
                <td @click.stop="props.expand(!props.isExpanded)">
                  {{ props.item.check_number }}
                </td>
                <td @click.stop="props.expand(!props.isExpanded)">
                  {{ props.item.date_check_delivery | date }}
                </td>
                <td @click.stop="props.expand(!props.isExpanded)">
                  {{ props.item.amount | money }}
                </td>
                <td @click.stop="props.expand(!props.isExpanded)">
                  {{ props.item.balance_previous | money }}
                </td>
                <td @click.stop="props.expand(!props.isExpanded)" :class="props.isExpanded ? 'warning black--text font-weight-bold' : ''">
                  {{ parseFloat(props.item.amount) + parseFloat(props.item.balance_previous) - parseFloat(props.item.balance) | money }}
                </td>
                <td @click.stop="props.expand(!props.isExpanded)">
                  {{ props.item.balance | money }}
                </td>

                <!--Actiones-->
                <td>
                  <v-tooltip bottom v-if="props.item.fund_rotatory_outputs.length ==0 || props.item.fund_rotatory_outputs.length == 0">
                    <template v-slot:activator="{ on }">
                      <v-btn
                        v-if="last(props.item)"
                        icon
                        small
                        v-on="on"
                        color="warning"
                        @click="editItem(props.item)"
                      >
                        <v-icon>mdi-pencil</v-icon>
                      </v-btn>
                    </template>
                    <span>Editar registro </span>
                  </v-tooltip>

                  <!--<v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                      <v-btn icon small v-on="on" color="error" @click.stop="">
                        <v-icon>mdi-mdi-delete</v-icon>
                      </v-btn>
                    </template>
                    <span>Anular registro</span>
                  </v-tooltip>-->
                </td>
              </tr>
            </template>
            <!--Expanded-->
            <template v-slot:expanded-item="{ headers, item }">
              <tr >
                <td :colspan="headers.length" class="pa-0 pl-10 pb-1 pr-1" style=" background-color:#0288D1" >
                  <v-data-table
                    :headers="headersOutput"
                    :items="item.fund_rotatory_outputs"
                    :loading="loading"
                    dense
                    hide-default-footer
                  >

                  <template v-slot:[`item.affiliate`]="{ item }">
                    {{$options.filters.fullName(item.loan.affiliate, true) }}
                  </template>
                  <template v-slot:[`item.loan.disbursement_date`]="{ item }">
                    {{ item.loan.disbursement_date | datetimeshorted }}
                  </template>
                  <template v-slot:[`item.loan.amount_requested`]="{ item }">
                    {{ item.loan.amount_requested | money }}
                  </template>
                  <template v-slot:[`item.loan.modality`]="{ item }">
                    {{ item.loan.modality.name }}
                  </template>

                  <template v-slot:[`item.actions`]="{ item }">
                    <v-tooltip bottom>
                      <template v-slot:activator="{ on }">
                        <v-btn
                          icon
                          x-small
                          v-on="on"
                          color="primary"
                          @click="imprimirRecive(item.loan.id)"
                        ><v-icon>mdi-printer</v-icon>
                        </v-btn>
                      </template>
                      <span>{{tray != 'all'? 'Revisar trámite' : 'Ver trámite'}}</span>
                    </v-tooltip>
                  </template>
                  </v-data-table>
                </td>
              </tr>
            </template>
          </v-data-table>
          <RemoveItem :bus="bus" />
        </v-card>
      </v-form>
    </ValidationObserver>
  </v-container>
</template>

<script>
import RemoveItem from "@/components/shared/RemoveItem";
export default {
  name: "fund_rotatory_entry-list",
  components: {
    RemoveItem
  },
  data: () => ({
    bus: new Vue(),
    loading: true,
    search: "",
    options: {
      page: 1,
      //itemsPerPage: 8,
      sortBy: ["code_entry"],
      sortDesc: [false],
    },
    fund_rotatory_list: [],
    totalFundRotatoryEntry: 0,
    headers: [

      {
        text: "Código de Ingreso",
        value: "code_entry",
        class: ["normal", "white--text"],
        width: "15%",
        sortable: true,
      },
      {
        text: "Número de Cheque",
        value: "check_number",
        class: ["normal", "white--text"],
        width: "15%",
        sortable: false,
      },
      {
        text: "Fecha Entrega Cheque",
        value: "date_check_delivery",
        class: ["normal", "white--text"],
        width: "15%",
        sortable: false,
      },
      {
        text: "Monto Ingreso (+)[Bs]",
        value: "amount",
        class: ["normal", "white--text"],
        width: "10%",
        sortable: false,
      },
      {
        text: "Saldo Anterior (+)[Bs]",
        value: "balance_previous",
        class: ["normal", "white--text"],
        width: "10%",
        sortable: false,
      },
      {
        text: "Egreso (-)[Bs]",
        value: "salidas",
        class: ["normal", "white--text"],
        width: "10%",
        sortable: false,
      },
      {
        text: "Saldo Actual [Bs]",
        value: "balance",
        class: ["normal", "white--text"],
        width: "10%",
        sortable: false,
      },

      {
        text: "Acción",
        value: "actions",
        class: ["normal", "white--text"],
        sortable: false,
        width: "15%",
        sortable: false,
      },
    ],
    headersOutput: [

      {
        text: "Cód. Recibo",
        value: "code",
        class: ["white", "black--text"],
        width: "5%",
        sortable: false,
      },
      {
        text: "Nro Contrato",
        value: "loan.code",
        class: ["white", "black--text"],
        width: "20%",
        sortable: false,
      },
      {
        text: "Fecha Desembolso",
        value: "loan.disbursement_date",
        class: ["white", "black--text"],
        width: "15%",
        sortable: false,
      },
      {
        text: "Afiliado",
        value: "affiliate",
        class: ["white", "black--text"],
        width: "20%",
        sortable: false,
      },
      {
        text: "Monto [Bs]",
        value: "loan.amount_requested",
        class: ["white", "black--text"],
        width: "10%",
        sortable: false,
      },

      {
        text: "Concepto",
        value: "loan.modality",
        class: ["white", "black--text"],
        width: "20%",
        sortable: false,
      },
      {
        text: "Acción",
        value: "actions",
        class: ["white", "black--text"],
        sortable: false,
        width: "10%",
        sortable: false,
      },
    ],
    fund_rotatory_item: {},
    editedIndexPerRef: -1,
    dialog: false,
    //editedItem: {},
    defaultItem: {},
    refreshFoundRotatoryTable: 0,
  }),
  computed: {
    //Metodo para obtener Permisos por rol
    permissionSimpleSelected() {
      return this.$store.getters.permissionSimpleSelected;
    },
  },
  watch: {
    options: function (newVal, oldVal) {
      if (
        newVal.page != oldVal.page ||
        newVal.itemsPerPage != oldVal.itemsPerPage ||
        newVal.sortBy != oldVal.sortBy ||
        newVal.sortDesc != oldVal.sortDesc
      ) {
        this.getFundRotary();
      }
    },
  },
  mounted() {
    this.bus.$on("removed", (val) => {
      this.getFundRotary();
    });
    this.getFundRotary();
  },
  methods: {
    async getFundRotary(params) {
      try {
        this.loading = true;
        let res = await axios.get(`fund_rotatory_entry_output` /*, {
          params: {
            page: this.options.page,
            per_page: this.options.itemsPerPage,
            //sortBy: this.options.sortBy,
            sortDesc: this.options.sortDesc,
            //search: this.search
          },
        }
        */
        );

        this.fund_rotatory_list = res.data.data;
        console.log(this.fund_rotatory_list);
        /*this.totalFundRotatoryEntry = res.data.total;
        delete res.data["data"];
        this.options.page = res.data.current_page;
        this.options.itemsPerPage = parseInt(res.data.per_page);
        this.options.totalItems = res.data.total;*/
        this.refreshFoundRotatoryTable++
      } catch (e) {
        console.log(e);
      } finally {
        this.loading = false;
      }
    },

    async saveFundRotary() {
      try {
        if (this.fund_rotatory_item.id) {
          let res = await axios.patch(`fund_rotatory_entry/${this.fund_rotatory_item.id}`,
            {
              check_number: this.fund_rotatory_item.check_number,
              amount: this.fund_rotatory_item.amount,
              balance: parseFloat(this.fund_rotatory_item.amount) + parseFloat(this.fund_rotatory_item.balance_previous),
              date_check_delivery: this.$moment(this.fund_rotatory_item.date_check_delivery).format("YYYY-MM-DD"),
              description: this.fund_rotatory_item.description,
              role_id: this.$store.getters.rolePermissionSelected.id,
            }
          );
        } else {
          let res = await axios.post(`fund_rotatory_entry`, {
            check_number: this.fund_rotatory_item.check_number,
            amount: this.fund_rotatory_item.amount,
            date_check_delivery: this.fund_rotatory_item.date_check_delivery,
            description: this.fund_rotatory_item.description,
            role_id: this.$store.getters.rolePermissionSelected.id,
          });
        }

        this.dialog = false;
        this.getFundRotary();
      } catch (e) {
        console.log(e);
      } finally {
        this.loading = false;
      }
    },
    editItem(item) {
      this.fund_rotatory_item = item;
      this.fund_rotatory_item.date_check_delivery= this.$moment(this.fund_rotatory_item.date_check_delivery).format('YYYY-MM-DD')
      console.log("edit");
      console.log(this.fund_rotatory_item);
      this.dialog = true;
    },

    deleteItem(item) {
      const index = this.contrib_codebtor.indexOf(item);
      confirm("Esta seguro que?") && this.contrib_codebtor.splice(index, 1);
    },

    close() {
      this.dialog = false;
      this.$nextTick(() => {
        this.fund_rotatory_item = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },
        async imprimirRecive(item) {
      try {
          let res = await axios.get(`print_fund_rotary_output/${item}`)
            printJS({
              printable: res.data.content,
              type: res.data.type,
              file_name: res.data.file_name,
              base64: true
            })
      } catch (e) {
        this.toastr.error("Ocurrió un error en la impresión.")
        console.log(e)
      }
    },
    last(item){
      if(item.id == this.fund_rotatory_list[this.fund_rotatory_list.length -1].id ){
        return true
      }else{
        return false
      }
    },

  },
};
</script>

