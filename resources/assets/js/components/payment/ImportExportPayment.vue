<template>
  <v-container fluid>
    <ValidationObserver>
      <v-form>
        <v-card flat>
          <v-card-title class="pa-0 pb-3">
            <v-toolbar dense color="tertiary" class="font-weight-regular pa-3">
              <v-col cols="12" md="12">
                <v-row>
                  <v-col cols="12" md="8" class="py-0">
                    <v-toolbar-title>GENERACIÓN DE PERIODOS </v-toolbar-title>
                  </v-col>
                  <v-col cols="12" md="2" class="py-0">
                    <v-select
                      dense
                      :items="year"
                      item-text="year"
                      item-value="year"
                      :loading="loading"
                      label="Gestion"
                      v-model="period_year"
                      @change="Onchange()"
                    ></v-select>
                  </v-col>
                  <v-col cols="12" md="2" class="py-0">
                    <v-tooltip top>
                      <template v-slot:activator="{ on }">
                        <v-btn
                          fab
                          dark
                          x-small
                          :color="'success'"
                          bottom
                          right
                          v-on="on"
                          @click.stop="savePeriod()"
                        >
                          <v-icon>mdi-plus</v-icon>
                        </v-btn>
                      </template>
                      <div>
                        <span>Añadir Periodo</span>
                      </div>
                    </v-tooltip>
                  </v-col>
                </v-row>
              </v-col>
            </v-toolbar>
          </v-card-title>
          <template>
            <v-container fluid class="px-2 pt-0">
              <v-row justify="center" class="py-0">
                <v-col cols="12" md="4" class="py-0"
                  v-for="(item, i) in month"
                  :key="i">
                  <v-card color="#454545" class="headline font-weight-bold"  max-width="90%" max-height="500" >
                    <v-card-text >
                      <v-row>
                        <v-col cols="4" md="12" class="py-0">
                          <center><b><h1 style="color:white">
                            {{meses[item.month - 1]}}
                          </h1></b></center>
                        </v-col>
                        <v-progress-linear color="white"></v-progress-linear>
                        <br>
                        <v-col cols="4" md="2" class="py-0">
                           <v-tooltip top>
                            <template v-slot:activator="{ on }">
                              <v-btn
                                fab
                                dark
                                small
                                :color="'info'"
                                bottom
                                right
                                v-on="on"
                                @click.stop="importacionComando(item.month, item.id)"
                               >
                                <v-icon style="color:white">mdi-warehouse</v-icon>
                              </v-btn>
                            </template>
                            <div>
                              <span>Importación Comando</span>
                            </div>
                          </v-tooltip>
                        </v-col>
                        <v-col cols="4" md="2" class="py-0">
                           <v-tooltip top>
                            <template v-slot:activator="{ on}">
                              <v-btn
                                fab
                                dark
                                small
                                :color="'info'"
                                right
                                v-on="on"
                                 @click.stop="importacionSenasir(item.month, item.id)"
                              >
                                <v-icon  style="color:white" >mdi-home-analytics</v-icon>
                              </v-btn>
                            </template>
                            <div>
                              <span>Importación Senasir</span>
                            </div>
                          </v-tooltip>
                        </v-col>
                        <v-col cols="4" md="6" class="py-0">
                          <b style="color:white">Importaciones Comando/Senasir</b>
                        </v-col>
                      </v-row>
                      </v-card-text>
                      <v-progress-linear color="white"></v-progress-linear>
                      <v-card-actions>
                        <v-col cols="4" md="8" class="py-0">
                          <b style="color:white" class="caption">Reportes Comando/Senasir</b>
                        </v-col>
                        <v-tooltip top>
                          <template v-slot:activator="{ on }">
                            <v-btn
                              fab
                              dark
                              small
                              :color="'#454545'"
                              bottom
                              right
                              v-on="on"
                              :loading="reporte_comando_loading"
                              @click.stop="reporteComandoSenasir(item.id,'C')"
                            >
                              <v-icon>mdi-warehouse</v-icon>
                            </v-btn>
                          </template>
                          <div>
                            <span>Reporte Pago Comando</span>
                          </div>
                        </v-tooltip>
                        <v-tooltip top>
                          <template v-slot:activator="{ on }">
                            <v-btn
                              fab
                              dark
                              small
                              :color="'#454545'"
                              bottom
                              right
                              v-on="on"
                              :loading="reporte_senasir_loading"
                              @click.stop="reporteComandoSenasir(item.id,'S')"
                            >
                              <v-icon >mdi-home-analytics</v-icon>
                            </v-btn>
                          </template>
                          <div>
                            <span>Reporte Pago Senasir</span>
                          </div>
                        </v-tooltip>
                    </v-card-actions>
                  </v-card>
                  <br>
                </v-col>
              </v-row>
            </v-container>
            <v-dialog
              v-model="dialog"
              fullscreen
              hide-overlay
              transition="dialog-bottom-transition"
            >
              <v-card>
                <v-toolbar dark color="primary" >
                  <v-btn icon dark @click="dialog_confirm=true" >
                    <v-icon>mdi-close</v-icon>
                  </v-btn>
                  <v-toolbar-title>IMPORTACION {{title}}</v-toolbar-title>
                  <v-spacer></v-spacer>
                  <v-toolbar-items>
                    <v-btn dark text v-show="importacion" @click="dialog_confirm_import=true" >
                      Ejecutar la Importación
                    </v-btn>
                  </v-toolbar-items>
                </v-toolbar>
                <v-col cols="12" >
                  <v-row>
                    <v-col cols="3"  md="3" >
                    </v-col>
                    <v-col cols="3"  md="3" >
                      <v-col cols="12" >
                        <v-toolbar-title>
                          <center><b>Información para descuento </b></center>
                        </v-toolbar-title>
                        <v-progress-linear></v-progress-linear>
                      </v-col>
                      <v-col cols="6" md="10" >
                       <v-select
                          dense
                          :items="state_affiliate"
                          item-text="name"
                          item-value="value"
                          label="Estado del afiliado"
                          disabled
                          readonly
                          v-model="import_export.state_affiliate"
                        ></v-select>
                      </v-col>
                      <v-col cols="6"  md="10">
                        <v-file-input
                          counter
                          show-size
                          truncate-length="30"
                          outlined
                          small-chips
                          dense
                          label="Importar información"
                          v-model="import_export.file"
                        ></v-file-input>
                      </v-col>
                      <v-col cols="6" >
                        <v-btn
                          color="success"
                          @click.stop="uploadFilePayment()"
                          >Subir Archivo
                        </v-btn>
                      </v-col>
                         <v-col cols="6" v-show="validar_datos" >
                        <v-btn
                          color="info"
                          @click.stop="validateFilePayment()"
                          >Validar Datos
                        </v-btn>
                      </v-col>
                    </v-col>
                    <v-col cols="6"  md="6" class="py-0" >
                      <v-col cols="12" >
                        <v-card color="warning" class="headline font-weight-bold"  max-width="53%" max-height="500"  >
                      <v-card-text >
                        <b style="color:white" >
                          *DATOS A TOMAR EN CUENTA PARA LA IMPORTACIÓN
                        </b>
                        <v-progress-linear style="color:primary"></v-progress-linear><br/>
                        <v-icon style="color:white">mdi-check</v-icon>
                        <b style="color:white" >
                         Archivo CSV
                        </b>
                        <br/>
                        <v-icon style="color:white">mdi-check</v-icon>
                        <b style="color:white" >
                         Campos del Archivo CSV
                        </b>
                        <v-card color="primary" class="headline font-weight-bold"  max-width="100%" max-height="500"  >
                          <v-card-text >
                             <v-icon style="color:white">mdi-arrow-right-thick</v-icon>
                            <b style="color:white" >
                             CI / MATRICULA: CI del afiliado cuando sea una importacion por COMANDO.
                             Matricula del afiliado cuando sea una importacion por SENASIR.
                            </b>
                            <br/>
                            <v-icon style="color:white">mdi-arrow-right-thick</v-icon>
                            <b style="color:white" >
                             MONTO : Monto de la importación
                            </b>
                            <br/>
                          </v-card-text>
                        </v-card>
                        <br/>
                        <v-icon style="color:white">mdi-check</v-icon>
                        <b style="color:white" >
                          Ejemplo de introduccion de datos en el archivo .csv
                        </b>
                        <v-card color="info" class="headline font-weight-bold"  max-width="100%" max-height="500"  >
                          <v-card-text >
                            <v-icon style="color:white">*</v-icon>
                            <b style="color:white" >
                             CI
                            </b>
                            <v-icon style="color:white">*</v-icon>
                            <b style="color:white" >
                             MONTO
                            </b>
                            <br/>
                            <b style="color:white" >
                              82716152:1256.56
                            </b>
                          </v-card-text>
                        </v-card>
                      </v-card-text>
                    </v-card >
                  </v-col>
                </v-col>
              </v-row>
            </v-col>
          </v-card>
        </v-dialog>
         <v-dialog
            v-model="dialog_confirm"
            max-width="500"
          >
            <v-card>
              <v-card-title>
                Esta seguro de salir?
                <br> Al salir se borraran todos los datos ingresados
              </v-card-title>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                  color="red darken-1"
                  text
                  @click="dialog_confirm=false"
                >
                  Cancelar
                </v-btn>
                <v-btn
                  color="green darken-1"
                  text
                  @click="closePayment()"
                >
                  Aceptar
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
           <v-dialog
            v-model="dialog_confirm_import"
            max-width="500"
          >
            <v-card>
              <v-card-title>
                Esta seguro de realizar la importación?
              </v-card-title>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                  color="red darken-1"
                  text
                  @click="dialog_confirm_import=false"
                >
                  Cancelar
                </v-btn>
                <v-btn
                  color="green darken-1"
                  text
                  :loading="loading_importacion"
                  @click="importPayment()"
                >
                  Aceptar
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
      </template>
    </v-card>
  </v-form>
</ValidationObserver>
</v-container>
</template>



<script>
export default {
  name: "payment-ImportExport",
  data: () => ({

  bus: new Vue(),
  importacion:false,
  loading_importacion:false,
  reporte_comando_loading:false,
  reporte_senasir_loading:false,
  validar_datos:false,
  dialog: false,
  dialog_confirm : false,
  dialog_confirm_import:false,
  aux_period:null,
  title: null,
  dialog1: false,
  tab: null,
  import_export: {
    file: null,
    state_affiliate: null,
    cutoff_date: null,
  },
  meses: [ 'ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE'
  ],
  actions: [
    { nameTab: "Exportación", value: "export" },
    { nameTab: "Importación", value: "import" },
  ],
  state_affiliate: [
    { name: "Activo - Comando", value: 'C' },
    { name: "Pasivo - Senasir", value: 'S' },
  ],
  paymentsBatch: [],
  datos: [],
  import_payments: {
    automatic: 0,
    no_automatic: 0,
  },
  period_year:null,
  mes:null,
  year:[],
  month:[],
  visible: false,
  loading_rpb: false,
  loading_ipb: false,
  aux:null

  }),
  beforeMount() {
    this.getYear();
  },
  methods: {
    clearInputs() {
      this.import_export.file = null
      this.import_export.state_affiliate = null
    },

    async uploadFilePayment() {
      const formData = new FormData();
      formData.append("file", this.import_export.file);
      formData.append("state", this.import_export.state_affiliate);
      this.loading_ipb = true
      await axios({
        url: "/loan_payment/upload_file_payment",
        method: "POST",
        headers: { Accept: "application/vnd.ms-excel" },
        data: formData,
      })
        .then((response) => {
          console.log(response.data);
           this.validar_datos=response.data.validate
           if(this.validar_datos){
            this.toastr.success('Datos efectivizados '+ response.data.message[0].count)
           }
           else{
            this.toastr.error(response.data.message)
           }
            this.import_export.file=null
        })
        .catch((e) => {
          console.log(e);
        });
    },

    async getYear() {
      try {
        this.loading = true;
        let res = await axios.get(`get_list_year`);
        this.year = res.data;
      } catch (e) {
        this.dialog = false;
        console.log(e);
      } finally {
        this.loading = false;
      }
    },
      Onchange(){
      if(this.period_year!=null)
      {
         this.getMonthYear()
      }
    },
    async getMonthYear() {
      try {
         let res = await axios.get(`get_list_month`,{
          params:{
            year:this.period_year
          }
        });
        this.month = res.data
      } catch (e) {
        this.loading = false;
        console.log(e);
      } finally {
        this.loading = false;
      }
    },
    async savePeriod() {
      try {
         let res = await axios.post(`periods`);
         if(res.data.message){
            this.toastr.error(res.data.message)
         }else{
            this.month.push(res.data.month)
            this.period_year=res.data.year

            this.mes= res.data.id
            this.getMonthYear()
            this.$forceUpdate();
         }
      } catch (e) {
        this.loading = false;
        console.log(e);
      } finally {
        this.loading = false;
      }
    },
    async importacionComando(month, id){
      try {
        let res = await axios.get(`periods/${id}`)
        if(res.data.import_command){
          this.toastr.error('Este periodo ya fue Importado')
        }else{
          this.aux_period= month
          this.mes=id
          this.dialog=true
          this.import_export.state_affiliate = 'C'
          this.title= 'COMANDO'
        }
      } catch (e) {
        this.loading = false;
        console.log(e);
      } finally {
        this.loading = false;
      }
    },
    async importacionSenasir(month, id){
        try {
        let res = await axios.get(`periods/${id}`)
        if(res.data.import_senasir){
          this.toastr.error('Este periodo ya fue Importado')
        }else{
          this.aux_period= month
          this.mes=id
          this.dialog=true
          this.import_export.state_affiliate = 'S'
          this.title= 'SENASIR'
        }
      } catch (e) {
        this.loading = false;
        console.log(e);
      } finally {
        this.loading = false;
      }
    },
    async validateFilePayment(){
    try {

      let res = await axios.get(`agruped_payments`,{
        params:{
        origin: this.import_export.state_affiliate,
        period: this.mes
        }
      })
       if(res.data.validated_agroup){
        this.importacion =res.data.validated_agroup
        this.toastr.success(res.data.message +' '+'cantidad de afiliados '+res.data.count_affilites)
      }
      else{
         this.importacion =res.data.validated_agroup

          const formData = new FormData();

         await axios({
          url: '/upload_fail_validated_group',
          method: "GET",
          responseType: "blob", // important
          headers: { Accept: "application/vnd.ms-excel" },
          //headers: { Accept: "text/plain" },
          data: formData,
          params: {
            origin: this.import_export.state_affiliate,
            period: this.mes
          }
        })
          .then((response) => {
            console.log(response.data);
             const url = window.URL.createObjectURL(new Blob([response.data]));
           const link = document.createElement("a");
           link.href = url;
           link.setAttribute("download", "ReporteAfiliadosNoImportados.xls");
           document.body.appendChild(link);
           link.click();
         })
           this.toastr.error(res.data.message)
      }

      } catch (e) {
        this.loading = false;
        console.log(e);
      } finally {
        this.loading = false;
      }
    },
    async importPayment(){
    try {
      this.loading_importacion = true
      if( this.loading_importacion == true)
      {
        if(this.import_export.state_affiliate=='C'){
          let res = await axios.get(`create_payments_command`,{
            params:{
              period: this.mes
          }
        })
        if(res.data.importation_validated){
          if(res.status==201 || res.status == 200){
            this.dialog=false
            this.dialog_confirm_import=false
            this.toastr.success('Importado Correctamente: '+res.data.paid_by_lenders+ ' titulares y '+ res.data.paid_by_guarantors+' garantes' )
          }
        }else{
          this.dialog_confirm_import=false
          this.toastr.error(res.data.message)
        }
      }else{
        let res = await axios.get(`importation_payments_senasir`,{
        params:{
          period: this.mes
        }
      })
      if(res.data.importation_validated){
        if(res.status==201 || res.status == 200){
          this.dialog=false
          this.dialog_confirm_import=false
          this.toastr.success('Importado Correctamente: '+res.data.paid_by_lenders+ ' titulares y '+ res.data.paid_by_guarantors+' garantes' )
        }
      }
      else{
        this.toastr.error(res.data.message)
      }
     }
      }
        this.importacion=false
        this.validar_datos=false
       } catch (e) {
        this.loading = false;
        console.log(e);
      } finally {
        this.loading = false;
      }
    },
    async closePayment(){
        let res = await axios.get(`rollback_copy_groups_payments`,{
        params:{
          origin: this.import_export.state_affiliate,
          period: this.mes
        }
      })
      if(res.data.validated_rollback){
          this.toastr.success(res.data.message)
      }
      else{
        this.toastr.error(res.data.message)
      }
      this.clearInputs()
      this.dialog=false
      this.dialog_confirm=false
      this.validar_datos=false
    },
    async reporteComandoSenasir(id, tipo){
    try {
          if(tipo=='C')
          {
            this.reporte_comando_loading = true
          }
          else{
            this.reporte_senasir_loading = true
          }
          if(this.reporte_comando_loading==true || this.reporte_senasir_loading ){

          const formData = new FormData();

         await axios({
          url: '/report_amortization_importation_payments',
          method: "GET",
          responseType: "blob", // important
          headers: { Accept: "application/vnd.ms-excel" },
          //headers: { Accept: "text/plain" },
          data: formData,
          params: {
            origin: tipo,
            period: id
          }
        })
          .then((response) => {
           const url = window.URL.createObjectURL(new Blob([response.data]));
           const link = document.createElement("a");
           link.href = url;
           link.setAttribute("download", "ReporteImportacion.xls");
           document.body.appendChild(link);
           link.click();
         if(response.status==201 || response.status == 200){
              this.reporte_comando_loading=false
              this.reporte_senasir_loading=false
            }
        })

          }
      } catch (e) {
        this.loading = false;
        console.log(e);
      } finally {
        this.loading = false;
      }
    },
  },
};
</script>
