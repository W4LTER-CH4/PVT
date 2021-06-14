<template>
  <v-container fluid class="py-0" >
   <v-form>
             <v-row  class="py-0">
                  <v-toolbar-title style="color:teal"> DOCUMENTOS REQUERIDOS</v-toolbar-title>
                  <v-col v-for="(req,i) in docsRequired" :key="req.id" cols="12" class="py-1">
                      <v-row>
                        <v-col cols="12" class="py-0">
                          <v-list dense class="py-0">
                            <v-list-item class="py-0">
                              <v-col cols="2" class="py-0">
                                <v-list-item-content class="align-end font-weight-light">
                                  <div>
                                    <h1>{{i+1}}</h1>
                                  </div>
                                </v-list-item-content>
                              </v-col>
                              <v-col cols="10" class="py-0 ml-n8">
                              {{ req.name }}
                              </v-col>
                            </v-list-item>
                          </v-list>
                        </v-col>
                      </v-row>
                  </v-col>
                </v-row>
              <!--</template>
            </v-data-iterator>
          </v-col>
        </v-row>-->
        <v-row>
          <v-col cols="12" v-if="docsOptional.length >0" >
           <!--<v-data-iterator :items="docsOptional" hide-default-footer>-->
              <template>
                <v-toolbar-title style="color:teal" >ADICIONALES</v-toolbar-title>
                 <v-progress-linear></v-progress-linear>
                <v-row  class="py-3">
                  <v-col v-for="(opt,i) in docsOptional" :key="opt.id" cols="12" class="py-1">
                    <v-card dense>
                      <v-row>
                        <v-col cols="12" class="py-0">
                          <v-list dense class="py-0">
                            <v-list-item class="py-0">
                              <v-col cols="1" class="py-0">
                                <v-list-item-content class="align-end font-weight-light">
                                  <div>
                                    <h1>{{i+1}}</h1>
                                  </div>
                                </v-list-item-content>
                              </v-col>
                              <v-col cols="8" class="py-0 ml-n8">
                                {{ opt.name }}
                              </v-col>
                            </v-list-item>
                          </v-list>
                        </v-col>
                      </v-row>
                    </v-card>
                  </v-col>
                </v-row>
              </template>
              <template>
                <v-toolbar-title class="align-end font-weight-black text-left ma-0 pa-4 pl-8">
                  <h5 v-if="notes.length >0" style="color:teal">Otros Documentos</h5>
                </v-toolbar-title>
                <v-row v-show="!editar">
                  <v-col cols="12" class="ma-0 px-10">
                    <div
                      class="align-end font-weight-light ma-0 pa-0 pl-2"
                      v-for="(note, index) of notes"
                      :key="index"
                    >
                      {{index+1 +". "}} {{note.message}}
                      <v-divider></v-divider>
                    </div>
                  </v-col>
                </v-row>
              </template>
            <!--</v-data-iterator>-->
          </v-col>
        </v-row>
      <!--</v-card>-->
    </v-form>
  </v-container>
</template>
<script>
export default {
  name: "documents-flow",
  data: () => ({

 dialog: false,
      dialogDelete: false,
      headers: [
        {
          text: 'Descripcion',
          align: 'start',
          sortable: false,
          value: 'message',
        },
        { text: 'Actions', value: 'actions', sortable: false },
      ],
      desserts: [],
      editedIndex: -1,
      editedItem: {},
      defaultItem: {},
    editar:false,
    docsRequired: [],
    docsOptional: [],
    notes: [],
    editable: false,
    reload: false,
    documents:[]
  }),

  beforeMount() {
    this.getDocumentsSubmitted(this.$route.params.id)
    this.getNotes(this.$route.params.id)
  },

  methods: {
    async getDocumentsSubmitted(id) {
      try {
        this.loading = true
        let res = await axios.get(`loan/${id}/document`)
        this.docsRequired = res.data.required
        this.docsOptional = res.data.optional
        console.log(this.docsRequired + " " + this.docsOptional)
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    },
    async getNotes(id) {
      try {
        this.loading = true
        let res = await axios.get(`loan/${id}/note`)
        this.notes = res.data
        console.log("NOTES  " + this.notes)
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    },
  }
}
</script>