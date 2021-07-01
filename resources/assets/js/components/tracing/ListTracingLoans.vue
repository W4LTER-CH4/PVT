<template>
  <v-container fluid>
    <ValidationObserver>
      <v-form>
        <v-card flat>
        <v-card-title class="pa-0 pb-3">
            <v-toolbar dense color="tertiary" class="font-weight-regular">
              <v-toolbar-title>Seguimiento de Préstamos</v-toolbar-title>
            </v-toolbar>
          </v-card-title>
          <!--<v-tooltip top>
            <template v-slot:activator="{ on }">
              <v-btn
                fab
                @click="download_loans()"
                color="success"
                class="mb-2"
                v-on="on"
                small
              >
                <v-icon> mdi-file-excel </v-icon>
              </v-btn>
            </template>
            <span class="caption">Descargar reporte</span>
          </v-tooltip>-->
          <v-tooltip top>
            <template v-slot:activator="{ on }">
              <v-btn
                fab
                @click="clearAll()"
                color="info"
                v-on="on"
                x-small
                absolute
                right
                style="margin-top: -53px; margin-right:10px"
              >
                <v-icon> mdi-broom </v-icon>
              </v-btn>
            </template>
            <span class="caption">Limpiar todos los filtros</span>
          </v-tooltip>
          <v-data-table
            dense
            :headers="headers"
            :items="loans"
            :options.sync="options"
            :server-items-length="totalLoans"
            :item-class="itemRowBackground"
            :footer-props="{ itemsPerPageOptions: [8, 15, 50,100] }"
          >
            <template v-slot:[`header.citi_loan`]="{ header }">
              {{ header.text }}<br />
              <v-menu offset-y :close-on-content-click="false">
                <template v-slot:activator="{ on, attrs }">
                  <v-btn icon v-bind="attrs" v-on="on">
                    <v-icon
                      small
                      :color="searching.citi_loan != '' ? 'red' : 'black'"
                    >
                      mdi-filter
                    </v-icon>
                  </v-btn>
                </template>
                <div>
                  <v-text-field
                    dense
                    v-model="searching.citi_loan"
                    type="text"
                    :label="'Buscar ' + header.text"
                    @keydown.enter="search_loans()"
                    hide-details
                    single-line
                  ></v-text-field>
                </div>
              </v-menu>
            </template>

            <template v-slot:[`header.name_role_loan`]="{ header }">
              {{ header.text }}<br />
              <v-menu offset-y :close-on-content-click="false">
                <template v-slot:activator="{ on, attrs }">
                  <v-btn icon v-bind="attrs" v-on="on">
                    <v-icon
                      small
                      :color="searching.name_role_loan != '' ? 'red' : 'black'"
                    >
                      mdi-filter
                    </v-icon>
                  </v-btn>
                </template>
                <div>
                  <v-text-field
                    dense
                    v-model="searching.name_role_loan"
                    type="text"
                    :label="'Buscar ' + header.text"
                    @keydown.enter="search_loans()"
                    hide-details
                    single-line
                  ></v-text-field>
                </div>
              </v-menu>
            </template>

            <template v-slot:[`header.user_loan`]="{ header }">
              {{ header.text }}<br />
              <v-menu offset-y :close-on-content-click="false">
                <template v-slot:activator="{ on, attrs }">
                  <v-btn icon v-bind="attrs" v-on="on">
                    <v-icon
                      small
                      :color="searching.user_loan != '' ? 'red' : 'black'"
                    >
                      mdi-filter
                    </v-icon>
                  </v-btn>
                </template>
                <div>
                  <v-text-field
                    dense
                    v-model="searching.user_loan"
                    type="text"
                    :label="'Buscar ' + header.text"
                    @keydown.enter="search_loans()"
                    hide-details
                    single-line
                  ></v-text-field>
                </div>
              </v-menu>
            </template>

            <template v-slot:[`header.code_loan`]="{ header }">
              {{ header.text }}<br />
              <v-menu offset-y :close-on-content-click="false">
                <template v-slot:activator="{ on, attrs }">
                  <v-btn icon v-bind="attrs" v-on="on">
                    <v-icon
                      small
                      :color="searching.code_loan != '' ? 'red' : 'black'"
                    >
                      mdi-filter
                    </v-icon>
                  </v-btn>
                </template>
                <div>
                  <v-text-field
                    dense
                    v-model="searching.code_loan"
                    type="text"
                    :label="'Buscar ' + header.text"
                    @keydown.enter="search_loans()"
                    hide-details
                    single-line
                  ></v-text-field>
                </div>
              </v-menu>
            </template>

            <template v-slot:[`header.identity_card_affiliate`]="{ header }">
              {{ header.text }}<br />
              <v-menu offset-y :close-on-content-click="false">
                <template v-slot:activator="{ on, attrs }">
                  <v-btn icon v-bind="attrs" v-on="on">
                    <v-icon
                      small
                      :color="searching.identity_card_affiliate != '' ? 'red' : 'black'"
                    >
                      mdi-filter
                    </v-icon>
                  </v-btn>
                </template>
                <div>
                  <v-text-field
                    dense
                    v-model="searching.identity_card_affiliate"
                    type="text"
                    :label="'Buscar ' + header.text"
                    @keydown.enter="search_loans()"
                    hide-details
                    single-line
                  ></v-text-field>
                </div>
              </v-menu>
            </template>

            <template v-slot:[`header.first_name_affiliate`]="{ header }">
              {{ header.text }}<br />
              <v-menu offset-y :close-on-content-click="false">
                <template v-slot:activator="{ on, attrs }">
                  <v-btn icon v-bind="attrs" v-on="on">
                    <v-icon
                      small
                      :color="
                        searching.first_name_affiliate != '' ? 'red' : 'black'"
                    >
                      mdi-filter
                    </v-icon>
                  </v-btn>
                </template>
                <div>
                  <v-text-field
                    dense
                    v-model="searching.first_name_affiliate"
                    type="text"
                    :label="'Buscar ' + header.text"
                    @keydown.enter="search_loans()"
                    hide-details
                    single-line
                  ></v-text-field>
                </div>
              </v-menu>
            </template>

            <template v-slot:[`header.second_name_affiliate`]="{ header }">
              {{ header.text }}<br />
              <v-menu offset-y :close-on-content-click="false">
                <template v-slot:activator="{ on, attrs }">
                  <v-btn icon v-bind="attrs" v-on="on">
                    <v-icon
                      small
                      :color="
                        searching.second_name_affiliate != '' ? 'red' : 'black'"
                    >
                      mdi-filter
                    </v-icon>
                  </v-btn>
                </template>
                <div>
                  <v-text-field
                    dense
                    v-model="searching.second_name_affiliate"
                    type="text"
                    :label="'Buscar ' + header.text"
                    @keydown.enter="search_loans()"
                    hide-details
                    single-line
                  ></v-text-field>
                </div>
              </v-menu>
            </template>

            <template v-slot:[`header.last_name_affiliate`]="{ header }">
              {{ header.text }}<br />
              <v-menu offset-y :close-on-content-click="false">
                <template v-slot:activator="{ on, attrs }">
                  <v-btn icon v-bind="attrs" v-on="on">
                    <v-icon
                      small
                      :color="
                        searching.last_name_affiliate != '' ? 'red' : 'black'"
                    >
                      mdi-filter
                    </v-icon>
                  </v-btn>
                </template>
                <div>
                  <v-text-field
                    dense
                    v-model="searching.last_name_affiliate"
                    type="text"
                    :label="'Buscar ' + header.text"
                    @keydown.enter="search_loans()"
                    hide-details
                    single-line
                  ></v-text-field>
                </div>
              </v-menu>
            </template>

            <template
              v-slot:[`header.mothers_last_name_affiliate`]="{ header }"
            >
              {{ header.text }}<br />
              <v-menu offset-y :close-on-content-click="false">
                <template v-slot:activator="{ on, attrs }">
                  <v-btn icon v-bind="attrs" v-on="on">
                    <v-icon
                      small
                      :color="
                        searching.mothers_last_name_affiliate != '' ? 'red' : 'black'"
                    >
                      mdi-filter
                    </v-icon>
                  </v-btn>
                </template>
                <div>
                  <v-text-field
                    dense
                    v-model="searching.mothers_last_name_affiliate"
                    type="text"
                    :label="'Buscar ' + header.text"
                    @keydown.enter="search_loans()"
                    hide-details
                    single-line
                  ></v-text-field>
                </div>
              </v-menu>
            </template>

            <template v-slot:[`header.shortened_sub_modality_loan`]="{ header }">
              {{ header.text }}<br />
              <v-menu offset-y :close-on-content-click="false">
                <template v-slot:activator="{ on, attrs }">
                  <v-btn icon v-bind="attrs" v-on="on">
                    <v-icon
                      small
                      :color="
                        searching.shortened_sub_modality_loan != '' ? 'red' : 'black'"
                    >
                      mdi-filter
                    </v-icon>
                  </v-btn>
                </template>
                <div>
                  <v-text-field
                    dense
                    v-model="searching.shortened_sub_modality_loan"
                    type="text"
                    :label="'Buscar ' + header.text"
                    @keydown.enter="search_loans()"
                    hide-details
                    single-line
                  ></v-text-field>
                </div>
              </v-menu>
            </template>

            <template v-slot:[`header.state_loan`]="{ header }">
              {{ header.text }}<br />
              <v-menu offset-y :close-on-content-click="false">
                <template v-slot:activator="{ on, attrs }">
                  <v-btn icon v-bind="attrs" v-on="on">
                    <v-icon
                      small
                      :color="
                        searching.state_loan != '' ? 'red' : 'black'"
                    >
                      mdi-filter
                    </v-icon>
                  </v-btn>
                </template>
                <div>
                  <v-text-field
                    dense
                    v-model="searching.state_loan"
                    type="text"
                    :label="'Buscar ' + header.text"
                    @keydown.enter="search_loans()"
                    hide-details
                    single-line
                  ></v-text-field>
                </div>
              </v-menu>
            </template>

            <template v-slot:[`item.shortened_sub_modality_loan`]="{ item }">
              <v-tooltip top>
                <template v-slot:activator="{ on }">
                  <span v-on="on">{{ item.shortened_sub_modality_loan }}</span>
                </template>
                <span>{{ item.sub_modality_loan }}</span>
              </v-tooltip>
            </template>
            <template v-slot:[`item.guarantor_loan_affiliate`]="{ item }">
              {{ item.guarantor_loan_affiliate ? "SI" : "NO" }}
            </template>
            <template v-slot:[`item.amount_approved_loan`]="{ item }">
              {{ item.amount_approved_loan | money }}
            </template>
            <template v-slot:[`item.balance_loan`]="{ item }">
              {{ item.balance_loan | money }}
            </template>
            <template v-slot:[`item.quota_loan`]="{ item }">
              {{ item.quota_loan | moneyString }}
            </template>
            <template v-slot:[`item.request_date_loan`]="{ item }">
              {{ item.request_date_loan | date }}
            </template>

            <template v-slot:[`item.actions`]="{ item }">
              <v-tooltip bottom>
                <template v-slot:activator="{ on }">
                  <v-btn
                    icon
                    small
                    v-on="on"
                    color="black"
                    :to="{ name: 'tracingAdd', params: { id: item.id_loan } }"
                    ><v-icon>mdi-eye</v-icon>
                  </v-btn>
                </template>
                <span>Ver información del trámite</span>
              </v-tooltip>
              <v-tooltip bottom >
                <template v-slot:activator="{ on }">
                  <v-btn
                    icon
                    small
                    v-on="on"
                    color="info"
                    @click="imprimir(2, item.id_loan)"
                    ><v-icon>mdi-file-document</v-icon>
                  </v-btn>
                </template>
                <span>Imprimir Solictud</span>
              </v-tooltip>
              <v-tooltip bottom >
                <template v-slot:activator="{ on }">
                  <v-btn
                    icon
                    small
                    v-on="on"
                    color="info"
                    @click="imprimir(1, item.id_loan)"
                    ><v-icon>mdi-file</v-icon>
                  </v-btn>
                </template>
                <span>Imprimir Contrato</span>
              </v-tooltip>
              <v-tooltip bottom v-if="item.state_loan == 'Vigente'">
                <template v-slot:activator="{ on }">
                  <v-btn
                    icon
                    small
                    v-on="on"
                    color="info"
                    @click="imprimir(3, item.id_loan)"
                    ><v-icon>mdi-cash</v-icon>
                  </v-btn>
                </template>
                <span>Imprimir Plan de pagos</span>
              </v-tooltip>
              <v-tooltip bottom v-if=" item.state_loan == 'Vigente'">
                <template v-slot:activator="{ on }">
                  <v-btn
                    icon
                    small
                    v-on="on"
                    color="info"
                    @click="imprimir(4, item.id_loan)"
                    ><v-icon>mdi-view-list</v-icon>
                  </v-btn>
                </template>
                <span>Imprimir Kardex</span>
              </v-tooltip>

            </template>
          </v-data-table>
        </v-card>
      </v-form>
    </ValidationObserver>
  </v-container>
</template>

<script>
export default {
  name: "list-loans-generate",

  data() {
    return {
      searching: {
        citi_loan:"",
        name_role_loan:"",
        user_loan:"",
        code_loan: "",
        identity_card_affiliate: "",
        last_name_affiliate: "",
        mothers_last_name_affiliate: "",
        first_name_affiliate: "",
        second_name_affiliate: "",
        shortened_sub_modality_loan: "",
        request_date_loan: "",
        amount_approved_loan: "",
        loan_term: "",
        quota_loan: "",
        state_loan: "",
      },
      headers: [
        { text: 'Dpto', value: 'citi_loan',input:'' , menu:false,type:"text",class: ['normal', 'white--text','text-md-center'],width: '5%', sortable: false},
        { text: 'Área', value: 'name_role_loan',input:'' , menu:false,type:"text",class: ['normal', 'white--text','text-md-center'],width: '5%',sortable: false},
        { text: 'Usuario',value:'user_loan',input:'', menu:false,type:"text",class: ['normal', 'white--text','text-md-center'],width: '5%',sortable: false},
        { text: 'Cód. Préstamo', value: 'code_loan',input:'' , menu:false,type:"text",class: ['normal', 'white--text','text-md-center'],width: '5%',sortable: true},
        { text: 'CI', value: 'identity_card_affiliate',input:'' , menu:false,type:"text",class: ['normal', 'white--text','text-md-center'],width: '5%',sortable: false},
        { text: '1er Nombre', value: 'first_name_affiliate',input:'' , menu:false,type:"text",class: ['normal', 'white--text','text-md-center'],width: '5%', sortable: false},
        { text: '2do Nombre', value: 'second_name_affiliate',input:'' , menu:false,type:"text",class: ['normal', 'white--text','text-md-center'],width: '5%',sortable: false},
        { text: 'Ap. Paterno', value: 'last_name_affiliate',input:'', menu:false,type:"text",class: ['normal', 'white--text','text-md-center'],width: '5%',sortable: false},
        { text: 'Ap. Materno',value:'mothers_last_name_affiliate',input:'', menu:false,type:"text",class: ['normal', 'white--text','text-md-center'],width: '5%', sortable: false},
        { text: 'Modalidad',value:'shortened_sub_modality_loan',input:'', menu:false,type:"text",class: ['normal', 'white--text','text-md-center'],width: '5%',sortable: false},
        { text: 'Fecha Solicitud',value:'request_date_loan',input:'', menu:false,type:"text",class: ['normal', 'white--text','text-md-center'],width: '5%',sortable: false},
        { text: 'Monto aprobado', value: 'amount_approved_loan' ,input:'', menu:false,type:"text",class: ['normal', 'white--text','text-md-center'],width: '5%',sortable: false},
        { text: 'Plazo', value: 'loan_term ',input:'', menu:false,type:"text",class: ['normal', 'white--text','text-md-center'],width: '5%',sortable: false},
        { text: 'Cuota',value:'quota_loan',input:'', menu:false,type:"text",class: ['normal', 'white--text','text-md-center'],width: '5%',sortable: false},
        { text: 'Estado',value:'state_loan',input:'', menu:false,type:"text",class: ['normal', 'white--text','text-md-center'],width: '5%',sortable: false},
        { text: 'Acción',value:'actions',input:'', menu:false,type:"text",class: ['normal', 'white--text','text-md-center'], sortable: false,width: '10%'},
      ],
      loans: [],
      printDocs: [],
      options: {
        page: 1,
        itemsPerPage: 8,
        sortBy: ["code_loan"],
        sortDesc: [false],
      },
      totalLoans: 0,
    };
  },
  computed: {
    //permisos del selector global por rol
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
        this.search_loans();
      }
    },
  },
  mounted() {
    this.search_loans();
    this.docsLoans();
  },
  methods: {
    async search_loans() {
      try {
        let res = await axios.get(`loan_tracking`, {
          params: {
            citi_loan:this.searching.citi_loan,
            name_role_loan:this.searching.name_role_loan,
            user_loan:this.searching.user_loan,
            code_loan: this.searching.code_loan,
            identity_card_affiliate: this.searching.identity_card_affiliate,
            last_name_affiliate: this.searching.last_name_affiliate,
            mothers_last_name_affiliate: this.searching.mothers_last_name_affiliate,
            first_name_affiliate: this.searching.first_name_affiliate,
            second_name_affiliate: this.searching.second_name_affiliate,
            shortened_sub_modality_loan: this.searching.shortened_sub_modality_loan,
            state_loan: this.searching.state_loan,
            excel: false,
            page: this.options.page,
            per_page: this.options.itemsPerPage,
            sortBy: this.options.sortBy,
            sortDesc: this.options.sortDesc,
          },
        });
        this.loans = res.data.data;
        this.totalLoans = res.data.total;
        delete res.data["data"];
        this.options.page = res.data.current_page;
        this.options.itemsPerPage = parseInt(res.data.per_page);
        this.options.totalItems = res.data.total;
      } catch (e) {
        console.log(e);
      }
    },

    async download_loans() {
      await axios({
        url: "/loan_tracking",
        method: "GET",
        responseType: "blob", // important
        headers: { Accept: "application/vnd.ms-excel" },
        data: this.datos,
        params: {
          code_loan: this.searching.code_loan,
          identity_card_affiliate: this.searching.identity_card_affiliate,
          registration_affiliate: this.searching.registration_affiliate,
          registration_spouse: this.searching.registration_spouse,
          last_name_affiliate: this.searching.last_name_affiliate,
          mothers_last_name_affiliate: this.searching.mothers_last_name_affiliate,
          first_name_affiliate: this.searching.first_name_affiliate,
          second_name_affiliate: this.searching.second_name_affiliate,
          surname_husband_affiliate: this.searching.surname_husband_affiliate,
          shortened_sub_modality_loan: this.searching.shortened_sub_modality_loan,
          request_date_loan: this.searching.request_date_loan,
          amount_approved_loan: this.searching.amount_approved_loan,
          state_type_affiliate: this.searching.state_type_affiliate,
          quota_loan: this.searching.quota_loan,
          state_loan: this.state_loan,
          guarantor_loan_affiliate: false,
          excel: true,
        },
      })
        .then((response) => {
          console.log(response);
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement("a");
          link.href = url;
          link.setAttribute("download", "ReportePrestamo.xls");
          document.body.appendChild(link);
          link.click();
        })
        .catch((error) => {
          console.log(error);
        });
    },

    clearAll() {
      this.searching.citi_loan = "",
      this.searching.user_loan = "",
      this.searching.name_role_loan = "",
      this.searching.code_loan = "",
      this.searching.identity_card_affiliate = "",
      this.searching.registration_affiliate = "",
      this.searching.registration_spouse= "",
      this.searching.last_name_affiliate = "",
      this.searching.mothers_last_name_affiliate = "",
      this.searching.first_name_affiliate = "",
      this.searching.second_name_affiliate = "",
      this.searching.surname_husband_affiliate = "",
      this.searching.shortened_sub_modality_loan = "",
      this.searching.request_date_loan = "",
      this.searching.amount_approved_loan = "",
      this.searching.state_type_affiliate = "",
      this.searching.quota_loan = "",
      this.searching.state_loan = "",
      this.searching.guarantor_loan_affiliate = "",
      this.search_loans();
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
    docsLoans() {
      let docs = [];
      if (this.permissionSimpleSelected.includes("print-contract-loan")) {
        docs.push(
          { id: 1, title: "Contrato", icon: "mdi-file-document" },
          { id: 2, title: "Solicitud", icon: "mdi-file" }
        );
      }
      if (this.permissionSimpleSelected.includes("print-payment-plan")) {
        docs.push({ id: 3, title: "Plan de pagos", icon: "mdi-cash" });
      }
      if (this.permissionSimpleSelected.includes("print-payment-kardex-loan")) {
        docs.push({ id: 4, title: "Kardex", icon: "mdi-view-list" });
      } else {
        console.log("Se ha producido un error durante la generación de la impresión");
      }
      this.printDocs = docs;
      console.log(this.printDocs);
    },
    itemRowBackground: function (item) {
      if(item.validated_loan === true && item.user_loan != null){
        return 'style-1'
      }else if(item.validated_loan === false && item.user_loan != null){
        return 'style-2'
      }else{
        return 'style-3'
      }
    },
  },
};
</script>
<style scoped>
.v-text-field {
  background-color: white;
  width: 200px;
  padding: 5px;
  margin: 0px;
  font-size: 0.8em;
  border-color: teal;
}
th.text-start {
  background-color: #757575;
}
.style-1 {
  background-color: #8BC34A
}
.style-2 {
  background-color: yellow
}
</style>
