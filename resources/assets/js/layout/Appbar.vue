<template>
  <v-app-bar
    app
    fixed
    dense
    flat
    clipped-left
    dark
    :color="bar.color"
  >
    <v-app-bar-nav-icon @click.stop="$emit('update:expanded', !expanded)"></v-app-bar-nav-icon>
    <v-toolbar-title>{{ bar.text }}</v-toolbar-title>
    <v-spacer></v-spacer>
    <div width="300px">
      <v-select
        outlined
        hide-details
        v-model="rolePermissionSelected"
        :items="rolesPermissionsItems"
        label="Rol actual"
        class="py-0 my-0"
        item-text="display_name"
        return-object
        dense
      ></v-select>
    </div>
    <!-- <span>{{ roles }}aaa</span> -->
    <LoggedUser/>
  </v-app-bar>
</template>

<script>
import LoggedUser from '@/layout/LoggedUser'

export default {
  name: 'app-mainbar',
  components: {
    LoggedUser
  },
  props: {
    expanded: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      rolesPermissionsItems: [],
      rolePermissionSelected: null,
    }
  },
  async created() {
    await this.getRolePermissions()
    this.setDefaultValues()
  },
  methods: {
    async getRolePermissions() {
      try {
        let res = await axios.get(`user_role/${6}/permission`)
        let aux_rolesPermissionsItems = res.data
        aux_rolesPermissionsItems.forEach(item => {
          //delete item.id
          delete item.module_id
          delete item.action
          delete item.created_at
          delete item.updated_at
          delete item.correlative
          delete item.name
          delete item.sequence_number
          item.permissions = item.permissions.map(item => ({display_name: item.display_name, name: item.name }))
        })
        this.rolesPermissionsItems =  aux_rolesPermissionsItems
        console.log(this.rolesPermissionsItems)
      } catch (e) {
        console.log(e)
      }
    },
    setDefaultValues() {
      if(this.rolesPermissionsItems.length > 0) {
        this.rolePermissionSelected = this.rolesPermissionsItems[0]
      }
    },
  },
  computed: {
    bar() {
      if (process.env.NODE_ENV != 'production') {
        return {
          color: `error`,
          text: `PLATAFORMA VIRTUAL DE TRÁMITES (VERSIÓN DE PRUEBA)`
        }
      } else {
        return {
          color: `primary`,
          text: `PLATAFORMA VIRTUAL DE TRÁMITES`
        }
      }
    },
    // roles() {
    //   return this.$store.getters.roles
    // },
  },
  watch: {
    'rolePermissionSelected.display_name'(val) {
      this.$store.commit('setRolePermissionSelected', this.rolePermissionSelected)
      //volver a la ruta de inicio
      if(this.$route.name != 'dashboardIndex'){
        this.$router.push("dashboardIndex")
      }else{
        console.log("Esta en dashboardIndex")
      }
    }
  }
}
</script>
