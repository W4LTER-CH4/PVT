<template>
  <v-card flat>
    <v-card-title>
      <v-toolbar dense color="tertiary">
        <v-toolbar-title>Perfil</v-toolbar-title>
      </v-toolbar>
    </v-card-title>
    <v-card-text>
      <v-card>
        <v-card-text>
          <v-container fluid>
            <v-row
              align="start"
              justify="space-around"
            >
              <v-col>
                <v-row
                  align="start"
                  justify="center"
                >
                  <v-col xl="2" class="font-weight-black text-right">
                    Usuario:
                  </v-col>
                  <v-col xl="10">
                    {{ $store.getters.user }}
                  </v-col>
                </v-row>
                <v-row
                  align="start"
                  justify="center"
                  v-if="position"
                >
                  <v-col xl="2" class="font-weight-black text-right">
                    Cargo:
                  </v-col>
                  <v-col xl="10">
                    {{ position }}
                  </v-col>
                </v-row>
              </v-col>
              <v-col>
                <v-row
                  align="start"
                  justify="center"
                >
                  <v-col xl="12" class="font-weight-black text-center">
                    Roles:
                  </v-col>
                </v-row>
                <v-row
                  align="start"
                  justify="center"
                >
                  <v-col xl="12">
                    <v-treeview
                      :items="modules"
                      item-children="roles"
                      item-key="id"
                      item-text="display_name"
                      expand-icon=""
                      open-on-click
                      open-all
                      dense
                    >
                      <template v-slot:prepend="{ item, open }">
                        <v-icon v-if="item.hasOwnProperty('sequence_number')">
                          mdi-square-small
                        </v-icon>
                        <v-icon v-else>
                          {{ open ? 'mdi-folder-open' : 'mdi-folder' }}
                        </v-icon>
                      </template>
                    </v-treeview>
                      <ul style="list-style: none" class="pa-0">
                      <li v-for="(permission,i) in rolePermissionSelected.permissions" :key="i">
                        <strong>{{i+1}} {{permission.display_name}}</strong> - {{permission.name}} 
                     </li>
                    </ul>
                  </v-col>
                </v-row>
              </v-col>
              <v-col v-if="!$store.getters.ldapAuth || $store.getters.username == 'admin'">
                <ChangePassword/>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
      </v-card>
    </v-card-text>
  </v-card>
</template>

<script>
import ChangePassword from '@/components/login/ChangePassword'
import Loading from '@/components/shared/Loading'
import Module from '@/services/ModuleService'

export default {
  name: "Profile",
  data() {
    return {
      loading: true,
      modules: [],
      position: null
    }
  },
  components: {
    ChangePassword,
    Loading
  },
  beforeMount() {
    this.getUser()
    this.getModules()
  },
  computed: {
    //permisos del selector global por rol
    permissionSimpleSelected () {
      return this.$store.getters.permissionSimpleSelected
    },
    rolePermissionSelected () {
      return this.$store.getters.rolePermissionSelected
    }
  },
  methods: {
    async getUser() {
      try {
        let res = await axios.get(`user/${this.$store.getters.id}`)
        this.position = res.data.position
      } catch (e) {
        console.log(e)
      }
    },
    getModules() {
      this.loading = true
      let roles = this.$store.getters.roles.filter(o => this.$store.getters.userRoles.includes(o.name))
      const module = new Module()
      module.get(null, {
        page: 1,
        per_page: 100
      }).then(res => {
        res.data.forEach(module => {
          let moduleRoles = roles.filter(o => o.module_id == module.id)
          if (moduleRoles.length) {
            module.roles = moduleRoles
            this.modules.push(module)
          }
        })
      }).catch(e => {
        console.log(e)
      }).finally(() => {
        this.loading = false
      })
    }
  }
}
</script>
