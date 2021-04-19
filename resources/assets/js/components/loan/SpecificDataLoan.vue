<template>
  <v-container fluid class="py-0 px-0">
    <ValidationObserver ref="observer">
      <v-form>
        <!--v-card-->
        <div v-if="$route.params.workTray == 'received' || $route.params.workTray == 'my_received' || $route.params.workTray == 'validated'">
          <v-tooltip top>
            <template v-slot:activator="{ on }">
              <v-btn
                fab
                dark
                x-small
                :color="'error'"
                top
                right
                absolute
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
          <v-tooltip top v-if="$store.getters.permissions.includes('disbursement-loan')">
            <template v-slot:activator="{ on }">
              <v-btn
                fab
                dark
                x-small
                :color="editable ? 'danger' : 'success'"
                top
                right
                absolute
                v-on="on"
                style="margin-right: -9px;"
                @click.stop="editLoan()"
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
        </div>
        <v-row justify="center" >
            <v-col cols="12" class="py-0 px-0">
              <v-container fluid class="py-0 px-6  ">
                <v-row class="py-0">
                  <v-col cols="12" class="py-0">
                    <v-tabs dark active-class="secondary">
                      <v-tab>DATOS DEL PRESTAMO</v-tab>
                        <v-tab-item>
                          <v-card flat tile class="py-0">
                            <v-card-text class="py-0">
                              <v-col cols="12" md="12" color="orange">
                                <v-row>
                                  <v-col cols="12" md="12">
                                    <p style="color:teal"><b>TITULAR</b></p>
                                  </v-col>
                                  <v-col cols="12" md="4">
                                    <p><b>PLAZO EN MESES:</b>{{' '+loan.loan_term}}</p>
                                  </v-col>
                                  <v-col cols="12" md="4">
                                    <p><b>MONTO SOLICITADO:</b>{{' '+loan.amount_approved}} Bs.</p>
                                  </v-col>
                                  <v-col cols="12" md="4">
                                    <p><b>PROMEDIO LIQUIDO PAGABLE</b>{{' '+loan.lenders[0].pivot.payable_liquid_calculated}} Bs.</p>
                                  </v-col>
                                  <v-col cols="12" md="4">
                                    <p><b>TOTAL BONOS:</b>{{' '+loan.lenders[0].pivot.bonus_calculated}}</p>
                                  </v-col>
                                  <v-col cols="12" md="4">
                                    <p><b>LIQUIDO PARA CALIFICACION:</b>{{' '+loan.liquid_qualification_calculated}} Bs.</p>
                                  </v-col>
                                   <v-col cols="12" md="4">
                                    <p><b>INDICE DE ENDEUDAMIENTO:</b>{{' '+loan.indebtedness_calculated}} </p>
                                  </v-col>
                                  <v-col cols="12" md="4">
                                    <p><b>CALCULO DE CUOTA:</b>{{' '+loan.estimated_quota}} Bs.</p>
                                  </v-col>
                                  <v-col cols="12" md="12" >
                                    <div v-for="procedure_type in procedure_types" :key="procedure_type.id">
                                      <div v-if="procedure_type.name === 'Préstamo hipotecario'">
                                        <v-progress-linear></v-progress-linear><br>
                                          <p style="color:teal"><b>CODEUDOR</b></p>
                                          <div v-for="(lenders,i) in loan.lenders" :key="i">
                                            <div  v-if="(lenders,i)>0">
                                              <p><b>PROMEDIO LIQUIDO PAGABLE:</b>{{' '+lenders.pivot.payable_liquid_calculated}}</p>
                                              <p><b>TOTAL BONOS:</b>{{' '+lenders.pivot.bonus_calculated}}</p>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </v-col>
                              </v-row>
                            </v-col>
                          </v-card-text>
                        </v-card>
                      </v-tab-item>
                    <v-tab>GARANTIA</v-tab>
                      <v-tab-item >
                        <v-card flat tile>
                          <v-card-text class="pa-0 py-0">
                            <v-col cols="12" class="mb-0 py-0">
                              <v-col cols="12" md="12" class="mb-0 py-0">
                                <v-card-text class="pa-0 mb-0">
                                  <div v-for="procedure_type in procedure_types" :key="procedure_type.id" class="pa-0 py-0" >
                                    <ul style="list-style: none" class="pa-0" v-if="procedure_type.name == 'Préstamo a largo plazo' || procedure_type.name == 'Préstamo a corto plazo'">
                                      <li v-for="guarantor in loan.guarantors" :key="guarantor.id">
                                        <v-col cols="12" md="12" class="pa-0">
                                          <v-row class="pa-0">
                                            <v-progress-linear></v-progress-linear><br>
                                            <v-col cols="12" md="12" class="py-0">
                                              <p style="color:teal"><b>GARANTE </b></p>
                                            </v-col>
                                            <v-col cols="12" md="3">
                                              <p><b>NOMBRE:</b> {{$options.filters.fullName(guarantor, true)}}</p>
                                            </v-col>
                                            <v-col cols="12" md="3">
                                              <p><b>CÉDULA DE IDENTIDAD:</b> {{guarantor.identity_card +" "+ identityCardExt(guarantor.city_identity_card_id) }}</p>
                                            </v-col>
                                            <v-col cols="12" md="3">
                                              <p><b>TELEFONO:</b> {{guarantor.cell_phone_number}}</p>
                                            </v-col>
                                            <v-col cols="12" md="3">
                                              <p><b>PORCENTAJE DE PAGO:</b> {{guarantor.pivot.payment_percentage}} %</p>
                                            </v-col>
                                             <v-col cols="12" md="3">
                                              <p><b>LIQUIDO PARA CALIFICACION:</b> {{guarantor.pivot.payable_liquid_calculated}}</p>
                                            </v-col>
                                            <v-col cols="12" md="3">
                                              <p><b>PROMEDIO DE BONOS:</b> {{guarantor.pivot.bonus_calculated }}</p>
                                            </v-col>
                                            <v-col cols="12" md="3">
                                              <p><b>LIQUIDO PARA CALIFICACION CALCULADO:</b> {{guarantor.pivot.liquid_qualification_calculated}}</p>
                                            </v-col>
                                            <v-col cols="12" md="3">
                                              <p><b>INDICE DE ENDEUDAMIENTO CALCULADO:</b> {{guarantor.pivot.indebtedness_calculated}} %</p>
                                            </v-col>
                                          </v-row>
                                        </v-col>
                                      </li>
                                      <br>
                                      <p v-if="loan.guarantors.length==0" style="color:teal"><b> NO TIENE GARANTES </b></p>
                                    </ul>
                                    <v-col cols="12" md="12" v-if="procedure_type.name == 'Préstamo hipotecario'">
                                      <p style="color:teal"><b>GARANTIA HIPOTECARIA </b></p>
                                      <v-tooltip >
                                        <template v-slot:activator="{ on }">
                                          <v-btn
                                            fab
                                            dark
                                            x-small
                                            :color="'error'"
                                            top
                                            right
                                            absolute
                                            v-on="on"
                                            style="margin-right: 45px; "
                                            @click.stop="resetForm()"
                                            v-show="editable1"
                                          >
                                            <v-icon>mdi-close</v-icon>
                                          </v-btn>
                                        </template>
                                        <div>
                                          <span>Cancelar</span>
                                        </div>
                                      </v-tooltip>
                                      <v-tooltip top  v-if="$store.getters.permissions.includes('update-loan')">
                                        <template v-slot:activator="{ on }">
                                          <v-btn
                                            fab
                                            dark
                                            x-small
                                            :color="editable1 ? 'danger' : 'success'"
                                            top
                                            right
                                            absolute
                                            v-on="on"
                                            style="margin-right: -9px;"
                                            @click.stop="editLoanPrueba()"
                                          >
                                            <v-icon v-if="editable1">mdi-check</v-icon>
                                            <v-icon v-else>mdi-pencil</v-icon>
                                          </v-btn>
                                        </template>
                                        <div>
                                          <span v-if="editable1">Guardar</span>
                                          <span v-else>Editar</span>
                                        </div>
                                      </v-tooltip>
                                      <v-row>
                                        <v-progress-linear></v-progress-linear><br>
                                        <v-col cols="12" md="4">
                                        <v-select
                                          dense
                                          :outlined="editable1"
                                          :readonly="!editable1"
                                          :items="city"
                                          item-text="name"
                                          item-value="id"
                                          label="CIUDAD"
                                          v-model="loan_properties.real_city_id"
                                        ></v-select>
                                      </v-col>
                                      <v-col cols="12" md="4">
                                        <v-text-field
                                          :outlined="editable1"
                                          :readonly="!editable1"
                                          :label="'UBICACION'"
                                          dense
                                          v-model="loan_properties.location"
                                        ></v-text-field>
                                      </v-col>
                                      <v-col cols="12" md="4">
                                        <v-text-field
                                          :outlined="editable1"
                                          :readonly="!editable1"
                                          :label="'NUMERO DE LOTE'"
                                          dense
                                          v-model="loan_properties.land_lot_number"
                                        ></v-text-field>
                                      </v-col>
                                      <v-col cols="12" md="1">
                                        <v-text-field
                                          :outlined="editable1"
                                          :readonly="!editable1"
                                          :label="'SUPERFICIE'"
                                          dense
                                          v-model="loan_properties.surface"
                                        ></v-text-field>
                                      </v-col>
                                      <v-col cols="12" md="3">
                                      <v-select
                                        :outlined="editable1"
                                        :readonly="!editable1"
                                        dense
                                        :items="items_measurement"
                                        item-text="name"
                                        item-value="value"
                                        label="Unidad de medida"
                                        v-model="loan_properties.measurement"
                                      ></v-select>
                                      </v-col>
                                      <v-col cols="12" md="4">
                                        <v-text-field
                                          :outlined="editable1"
                                          :readonly="!editable1"
                                          :label="'CODIGO CATASTRAL'"
                                          dense
                                          v-model="loan_properties.cadastral_code"
                                        ></v-text-field>
                                      </v-col>
                                      <v-col cols="12" md="4">
                                        <v-text-field
                                          :outlined="editable1"
                                          :readonly="!editable1"
                                          :label="'NRO MATRICULA'"
                                          dense
                                          v-model="loan_properties.registration_number"
                                        ></v-text-field>
                                      </v-col>
                                      <v-col cols="12" md="4">
                                        <v-text-field
                                          :outlined="editable1"
                                          :readonly="!editable1"
                                          :label="'NRO FOLIO REAL'"
                                          dense
                                          v-model="loan_properties.real_folio_number"
                                        ></v-text-field>
                                      </v-col>
                                      <v-col cols="12" md="4">
                                        <p><b>VNR: </b>{{ loan_properties.net_realizable_value}} </p>
                                      </v-col>
                                    </v-row>
                                  </v-col>
                                  <ul style="list-style: none" class="pa-0 py-4" v-if="procedure_type.name == 'Préstamo Anticipo'">
                                    <p style="color:teal"> <b>NO TIENE GARANTES</b></p>
                                  </ul>
                                </div>
                              </v-card-text>
                            </v-col>
                          </v-col>
                          </v-card-text>
                        </v-card>
                      </v-tab-item>
                      <v-tab>DATOS PERSONA DE REFERENCIA</v-tab>
                        <v-tab-item >
                          <v-card flat tile>
                            <v-card-text>
                              <p style="color:teal" v-if="loan.personal_references.length>0"><b>PERSONA DE REFERENCIA </b></p>
                              <v-progress-linear v-if="loan.personal_references.length>0"></v-progress-linear><br>
                              <v-data-table
                                v-if="loan.personal_references.length>0"
                                :headers="headers"
                                :items="loan.personal_references"
                                >
                                <template v-slot:top>
                                  <v-dialog v-model="dialog" max-width="500px" >
                                    <v-card>
                                      <v-card-title>
                                        <span style="color:teal" class="headline">EDITAR PERSONA DE REFERENCIA</span>
                                      </v-card-title>
                                        <v-progress-linear></v-progress-linear>
                                        <v-card-text class="py-0">
                                          <v-container class="py-0">
                                            <v-row>
                                              <v-col cols="12" sm="6" md="4">
                                                <v-text-field
                                                  dense
                                                  v-model="editedItem.first_name"
                                                  label="Primer Nombre"
                                                ></v-text-field>
                                              </v-col>
                                              <v-col cols="12" sm="6" md="4" >
                                                <v-text-field
                                                  dense
                                                  v-model="editedItem.second_name"
                                                  label="Segundo Nombre"
                                                ></v-text-field>
                                              </v-col>
                                              <v-col cols="12" sm="6" md="4" >
                                                <v-text-field
                                                  dense
                                                  v-model="editedItem.last_name"
                                                  label="Primer Apellido"
                                                ></v-text-field>
                                              </v-col>
                                              <v-col cols="12" sm="6" md="4">
                                                <v-text-field
                                                  dense
                                                  v-model="editedItem.mothers_last_name"
                                                  label="Segundo Apellido"
                                                ></v-text-field>
                                              </v-col>
                                              <v-col cols="12" sm="6" md="4" >
                                                <v-text-field
                                                  dense
                                                  v-model="editedItem.phone_number"
                                                  label="Telefono"
                                                ></v-text-field>
                                              </v-col>
                                              <v-col cols="12" sm="6" md="4" >
                                                <v-text-field
                                                  dense
                                                  v-model="editedItem.cell_phone_number"
                                                  label="Celular"
                                                ></v-text-field>
                                              </v-col>
                                              <v-col cols="12" sm="6" md="12">
                                                <v-text-field
                                                  dense
                                                  v-model="editedItem.address"
                                                  label="Direccion"
                                                ></v-text-field>
                                              </v-col>
                                            </v-row>
                                          </v-container>
                                        </v-card-text>
                                        <v-card-actions class="py-0">
                                          <v-spacer></v-spacer>
                                          <v-btn
                                            color="red"
                                            text
                                            @click="close"
                                          >
                                            Cancelar
                                          </v-btn>
                                          <v-btn
                                            color="success"
                                            text
                                            @click="guardar()"
                                          >
                                            Guardar
                                          </v-btn>
                                        </v-card-actions>
                                      </v-card>
                                    </v-dialog>
                                </template>
                                <template v-slot:item.actions="{ item }" v-if="$store.getters.permissions.includes('update-loan')">
                                  <v-icon
                                    small
                                    class="mr-2"
                                    @click="editItem(item)"
                                  >
                                    mdi-pencil
                                  </v-icon>
                                </template>
                              </v-data-table>
                              <p v-if="loan.personal_references.length==0" style="color:teal"> <b>NO TIENE PERSONA DE REFERENCIA</b></p>
                            </v-card-text>
                          </v-card>
                        </v-tab-item>
                        <v-tab>DATOS CODEUDOR</v-tab>
                          <v-tab-item >
                            <v-card flat tile>
                              <v-card-text>
                              <p style="color:teal" v-if="loan.cosigners.length>0"><b>CODEUDOR NO AFILIADO</b></p>
                              <v-progress-linear v-if="loan.cosigners.length>0"></v-progress-linear><br>
                              <v-data-table
                                v-if="loan.cosigners.length>0"
                                :headers="headers"
                                :items="loan.cosigners"
                                >
                                <template v-slot:top>
                                  <v-dialog v-model="dialog1" max-width="500px" >
                                    <v-card>
                                      <v-card-title>
                                        <span style="color:teal" class="headline">EDITAR CODEUDOR</span>
                                      </v-card-title>
                                       <v-progress-linear></v-progress-linear>
                                        <v-card-text class="py-0">
                                          <v-container class="py-0">
                                            <v-row >
                                              <v-col cols="12" sm="6" md="4">
                                                <v-text-field
                                                  dense
                                                  v-model="editedItem1.first_name"
                                                  label="Primer Nombre"
                                                ></v-text-field>
                                              </v-col>
                                              <v-col cols="12" sm="6" md="4" >
                                                <v-text-field
                                                  dense
                                                  v-model="editedItem1.second_name"
                                                  label="Segundo Nombre"
                                                ></v-text-field>
                                              </v-col>
                                              <v-col cols="12" sm="6" md="4" >
                                                <v-text-field
                                                  dense
                                                  v-model="editedItem1.last_name"
                                                  label="Primer Apellido"
                                                ></v-text-field>
                                              </v-col>
                                              <v-col cols="12" sm="6" md="4">
                                                <v-text-field
                                                  dense
                                                  v-model="editedItem1.mothers_last_name"
                                                  label="Segundo Apellido"
                                                ></v-text-field>
                                              </v-col>
                                              <v-col cols="12" sm="6" md="4" >
                                                <v-select
                                                  v-model="editedItem1.city_identity_card_id"
                                                  dense
                                                  :items="city"
                                                  item-text="name"
                                                  item-value="id"
                                                  label="Ciudad de Expedición"
                                                ></v-select>
                                              </v-col>
                                              <v-col cols="12" sm="6" md="4" >
                                                <v-select
                                                  dense
                                                  :items="genders"
                                                  item-text="name"
                                                  item-value="value"
                                                  v-model="editedItem1.gender"
                                                  label="Género"
                                                ></v-select>
                                              </v-col>
                                               <v-col cols="12" sm="6" md="4" >
                                                  <v-select
                                                    dense
                                                    :items="civil_statuses"
                                                    item-text="name"
                                                    item-value="value"
                                                    label="Estado civil"
                                                    v-model="editedItem1.civil_status"
                                                  ></v-select>
                                              </v-col>
                                              <v-col cols="12" sm="6" md="4" >
                                                <v-select
                                                  v-model="editedItem1.city_birth_id"
                                                  dense
                                                  :items="city"
                                                  item-text="name"
                                                  item-value="id"
                                                  label="Ciudad de Nacimiento"
                                                ></v-select>
                                              </v-col>
                                              <v-col cols="12" sm="6" md="4" >
                                                <v-text-field
                                                  dense
                                                  v-model="editedItem1.phone_number"
                                                  label="Telefono"
                                                ></v-text-field>
                                              </v-col>
                                              <v-col cols="12" sm="6" md="4" >
                                                <v-text-field
                                                  dense
                                                  v-model="editedItem1.cell_phone_number"
                                                  label="Celular"
                                                ></v-text-field>
                                              </v-col>
                                              <v-col cols="12" sm="6" md="8">
                                                <v-text-field
                                                  dense
                                                  v-model="editedItem1.address"
                                                  label="Direccion"
                                                ></v-text-field>
                                              </v-col>
                                            </v-row>
                                          </v-container>
                                        </v-card-text>
                                        <v-card-actions>
                                          <v-spacer></v-spacer>
                                          <v-btn
                                            color="red"
                                            text
                                            @click="closeCodeptor"
                                          >
                                            Cancelar
                                          </v-btn>
                                          <v-btn
                                            color="success"
                                            text
                                            @click="guardarCodeptor()"
                                          >
                                            Guardar
                                          </v-btn>
                                        </v-card-actions>
                                      </v-card>
                                    </v-dialog>
                                </template>
                                <template v-slot:item.actions="{ item }" v-if="$store.getters.permissions.includes('update-loan')">
                                  <v-icon
                                    small
                                    class="mr-2"
                                    @click="editItem1(item)"
                                  >
                                    mdi-pencil
                                  </v-icon>
                                </template>
                              </v-data-table>
                             <p v-if="loan.cosigners.length==0" style="color:teal"> <b>NO TIENE CODEUDORES</b></p>
                            </v-card-text>
                            </v-card>
                          </v-tab-item>
                          <v-tab>DESEMBOLSO</v-tab>
                            <v-tab-item >
                              <v-card flat tile>
                                <v-card-text>
                                  <v-col cols="12" class="mb-0">
                                    <p style="color:teal"> <b>DATOS DE DESEMBOLSO</b></p>
                                    <v-row>
                                      <v-progress-linear></v-progress-linear><br>
                                      <v-col cols="12" md="4">
                                        <p><b>ENTIDAD FINANCIERA:</b>{{' '+cuenta}}</p>
                                      </v-col>
                                      <v-col cols="12" md="4">
                                        <p><b>NUMERO DE CUENTA:</b>{{' '+loan.lenders[0].account_number}}</p>
                                      </v-col>
                                      <v-col cols="12" md="4">
                                        <p><b>CUENTA SIGEP:</b> {{' '+loan.lenders[0].sigep_status}}</p>
                                      </v-col>
                                      <v-col cols="12" md="4">
                                        <v-text-field
                                          dense
                                          v-model="loan.disbursement_date"
                                          label="FECHA DE DESEMBOLSO"
                                          hint="Día/Mes/Año"
                                          type="date"
                                          :outlined="editable"
                                          :readonly="!editable"
                                        ></v-text-field>
                                      </v-col>
                                      <v-col cols="12" md="4">
                                        <v-select
                                          dense
                                          :outlined="editable"
                                          :readonly="!editable"
                                          :items="payment_types"
                                          item-text="name"
                                          item-value="id"
                                          label="TIPO"
                                          v-model="loan.payment_type_id"
                                        ></v-select>
                                      </v-col>
                                      <v-col cols="12" md="4">
                                        <div v-if="loan.payment_type_id=='1'"  class="py-0">
                                          <v-text-field
                                            dense
                                            :outlined="editable"
                                            :readonly="!editable"
                                            :label="'NRO DE DEPOSITO'"
                                            @click="desembolso()"
                                            v-model="loan.number_payment_type"
                                          ></v-text-field>
                                        </div>
                                        <div v-if="loan.payment_type_id!='1'">
                                          <v-text-field
                                            dense
                                            :outlined="editable"
                                            :readonly="!editable"
                                            @click="desembolso()"
                                            :label="loan.payment_type_id=='2'? 'NRO DE CHEQUE':loan.payment_type_id=='3'?'NRO DE RECIBO':'OTRO'"
                                            v-model="loan.number_payment_type"
                                          ></v-text-field>
                                        </div>
                                      </v-col>
                                      <v-col cols="12" md="4">
                                        <div class="py-0">
                                          <v-text-field
                                            dense
                                            :outlined="editable"
                                            :readonly="!editable"
                                            :label="'CÓDIGO DE CERTIFICACIÓN PRESUPUESTARIA'"
                                             v-model="loan.num_budget_certification"
                                          ></v-text-field>
                                        </div>
                                      </v-col>
                                       <v-col cols="12" md="4">
                                        <div class="py-0">
                                          <v-text-field
                                            dense
                                            :outlined="editable"
                                            :readonly="!editable"
                                            :label="'CÓDIGO DE COMPROBANTE CONTABLE'"
                                             v-model="loan.num_accounting_voucher"
                                          ></v-text-field>
                                        </div>
                                      </v-col>
                                    </v-row>
                                  </v-col>
                                </v-card-text>
                              </v-card>
                            </v-tab-item>
                          </v-tabs>
                        </v-col>
                </v-row>
              </v-container>
            </v-col>
          </v-row>
        <!--/v-card-->
      </v-form>
    </ValidationObserver>
  </v-container>
</template>
<script>
export default {
  name: "specific-data-loan",
  props: {
    loan: {
      type: Object,
      required: true
    },
    loan_properties: {
      type: Object,
      required: true
    },
    procedure_types: {
      type: Object,
      required: true
    },
    /*validate:{
      type: Object,
      required: false
    }*/
  },
   data: () => ({
     civil_statuses: [
      { name: "Soltero", value: "S" },
      { name: "Casado", value: "C" },
      { name: "Viudo", value: "V" },
      { name: "Divorciado", value: "D" }
    ],
    items_measurement: [
      { name: "Metros cuadrados", value: "METROS CUADRADOS" },
      { name: "Hectáreas", value: "HECTÁREAS" }
    ],
    genders: [
      {
        name: "Femenino",
        value: "F"
      },
      {
        name: "Masculino",
        value: "M"
      }
    ],
      dialog: false,
      dialog1: false,
      editedIndex: -1,
      editedItem: {},
      defaultItem: {},
      editedItem1: {},
      defaultItem1: {},
      headers: [
        {
          text: 'PRIMER NOMBRE',
          align: 'start',
          sortable: false,
          value: 'first_name',
        },
        { text: 'SEGUNDO NOMBRE',  value: 'second_name' },
        { text: 'PRIMER APELLIDO ', value: 'last_name' },
        { text: 'SEGUNDO APELLIDO ', value: 'mothers_last_name' },
        { text: 'TELEFONO', value: 'phone_number' },
        { text: 'CELULAR', value: 'cell_phone_number' },
        { text: 'DIRECCION ', value: 'address' },
        {
      text: "Actions",
      value: "actions",
      sortable: false
    }
      ],

    editable1: false,
    editable: false,
    reload: false,
    payment_types:[],
    city: [],
    entity: [],
    entities:null,
  }),
  beforeMount(){
    this.getPaymentTypes()
    this.getCity()
    this.getEntity()
  },
  computed: {
      cuenta() {
       for (this.i = 0; this.i< this.entity.length; this.i++) {
        if(this.loan.lenders[0].financial_entity_id==this.entity[this.i].id)
        {
          this.entities= this.entity[this.i].name
        }
      }
      return this.entities
    }
  },
  watch: {
      dialog (val) {
        val || this.close()
      },
    },
  methods:{
    async guardar(){
      let res = await axios.patch(`personal_reference/${this.editedItem.id}`, {
        first_name:this.editedItem.first_name,
        second_name:this.editedItem.second_name,
        last_name:this.editedItem.last_name,
        mothers_last_name:this.editedItem.mothers_last_name,
        phone_number:this.editedItem.phone_number,
        cell_phone_number:this.editedItem.cell_phone_number,
        address:this.editedItem.address})
        this.toastr.success('Se registró correctamente.')
    this.close()
    this.$forceUpdate()
},
  async guardarCodeptor(){
      let res = await axios.patch(`personal_reference/${this.editedItem1.id}`, {
        first_name:this.editedItem1.first_name,
        second_name:this.editedItem1.second_name,
        last_name:this.editedItem1.last_name,
        mothers_last_name:this.editedItem1.mothers_last_name,

        city_identity_card_id:this.editedItem1.city_identity_card_id,
        gender:this.editedItem1.gender,
        civil_status:this.editedItem1.civil_status,
        city_birth_id:this.editedItem1.city_birth_id,

        phone_number:this.editedItem1.phone_number,
        cell_phone_number:this.editedItem1.cell_phone_number,
        cell_phone_number:this.editedItem1.cell_phone_number,
        address:this.editedItem1.address})
        this.toastr.success('Se registró correctamente.')
    this.closeCodeptor()
    this.$forceUpdate()
},

      editItem1 (item) {
        //this.editedIndex = this.loan.personal_references.indexOf(item)
        this.editedItem1 =  item
        console.log('edit')
        console.log(this.editedItem1)
        this.dialog1 = true
      },
      editItem (item) {
        //this.editedIndex = this.loan.personal_references.indexOf(item)
        this.editedItem =  item
        console.log('edit')
        console.log(this.editedItem)
        this.dialog = true
      },
      closeCodeptor() {
        this.dialog1 = false
        this.$nextTick(() => {
          this.editedItem1 = Object.assign({}, this.defaultItem1)
          this.editedIndex = -1
        })
      },
      close () {
        this.dialog = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
      },
    desembolso () {
      if(this.loan.payment_type_id=='1'){
      this.loan.number_payment_type = this.loan.lenders[0].account_number;
      }else{
      this.loan.number_payment_type = null
    }
    },
    async getEntity() {
      try {
        this.loading = true
        let res = await axios.get(`financial_entity`)
        this.entity = res.data
        console.log("ciudad "+ this.entity)

      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    },
    async getCity() {
      try {
        this.loading = true
        let res = await axios.get(`city`)
        this.city = res.data
        console.log("ciudad "+ this.city)

      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    },
    identityCardExt(id){
      let ext
      if(id != null){
        for(let i=0; i<this.city.length;i++){
          if(this.city[i].id == id){
            ext = this.city[i].first_shortened
          }  
        }
      return ext
      }else{
        return ''
      }
    },
    /*identityCardExt(id){
      let ext
      if(id != null){
      ext = this.city.find(o => o.id == id).first_shortened
      console.log( ext)
      return ext
      }else{
        return ''
      }
    },*/
    resetForm() {
      this.editable1 = false
      this.editable = false
      this.reload = true
      this.$nextTick(() => {
      this.reload = false
      })
    },
    async editLoan(){
      try {
        if (!this.editable) {
          this.editable = true
          console.log('entro al grabar por verdadero :)')
        } else {
          console.log('entro al grabar por falso :)')
          //Edit desembolso
            let res = await axios.patch(`loan/${this.loan.id}`, {
            disbursement_date:this.loan.disbursement_date,
            payment_type_id: this.loan.payment_type_id,
            number_payment_type: this.loan.number_payment_type,
            num_budget_certification: this.loan.num_budget_certification,
            num_accounting_voucher: this.loan.num_accounting_voucher
          })
            this.toastr.success('Se registró correctamente.')
            this.editable = false
            /* if((this.loan.disbursement_date != '' && this.loan.number_payment_type != '') && (this.loan.disbursement_date != null && this.loan.number_payment_type != null)){
               this.validate.valid_disbursement = true
             }else{
               this.validate.valid_disbursement = false
             }*/
        }
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    },
      async editLoanPrueba(){
      try {
        if (!this.editable1) {
          this.editable1 = true
          console.log('entro al grabar por verdadero :)')
        } else {
          console.log('entro al grabar por falso :)')
        
          //Edit desembolso
          let res = await axios.patch(`loan_property/${this.loan_properties.id}`, {
            location:this.loan_properties.location,
            land_lot_number:this.loan_properties.land_lot_number,
            real_city_id:this.loan_properties.real_city_id,
            surface:this.loan_properties.surface,
            measurement:this.loan_properties.measurement,
            cadastral_code:this.loan_properties.cadastral_code,
            registration_number:this.loan_properties.registration_number,
            real_folio_number:this.loan_properties.real_folio_number,

          })
           /* let res = await axios.patch(`personal_reference/${this.personal_references.id}`, {
            first_name:this.personal_references.first_name,
            second_name:this.personal_references.second_name,
            last_name:this.personal_references.last_name,
            mothers_last_name:this.personal_references.mothers_last_name,
            phone_number:this.personal_references.phone_number,
            cell_phone_number:this.personal_references.cell_phone_number,
            address:this.personal_references.address
          })*/
            this.toastr.success('Se registró correctamente.')
            this.editable1 = false
            /* if((this.loan.disbursement_date != '' && this.loan.number_payment_type != '') && (this.loan.disbursement_date != null && this.loan.number_payment_type != null)){
               this.validate.valid_disbursement = true
             }else{
               this.validate.valid_disbursement = false
             }*/
        }
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    },
     async getPaymentTypes() {
      try {
        this.loading = true
        let res = await axios.get(`payment_type`)
        this.payment_types = res.data
        console.log(this.payment_types+'este es el tipo de desembolso');
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    },
  }
}
</script>