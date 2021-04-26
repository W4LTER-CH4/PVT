import moment from 'moment'
import jwt from 'jsonwebtoken'
import VuexPersistence from 'vuex-persist'
import router from '@/plugins/router'
import Role from '@/services/RoleService'
import Module from '@/services/ModuleService'
import ProcedureType from '@/services/ProcedureTypeService'
import ModalityLoan from '@/services/ModalityLoanService'
import AmortizationLoan from '@/services/AmortizationLoanService'

const vuexLocal = new VuexPersistence({
  key: 'pvt',
  storage: window.localStorage
})

export default {
  state: {
    id: null,
    user: null,
    username: null,
    cityId: null,
    roles: [],
    module: {},
    procedureTypes: [],
    modalityLoan: [],
    amortizationLoan: [],
    userRoles: [],
    permissions: [],
    ldapAuth: JSON.parse(process.env.MIX_LDAP_AUTHENTICATION),
    dateNow: moment().format('Y-MM-DD'),
    tokenType: localStorage.getItem('token_type') || null,
    accessToken: localStorage.getItem('access_token') || null,
    tokenExpiration: localStorage.getItem('token_expiration') || null,
    breadcrumbs: [],
    rolePermissionSelected: null,
  },
  getters: {
    ldapAuth(state) {
      return state.ldapAuth
    },
    id(state) {
      return state.id
    },
    user(state) {
      return state.user
    },
    username(state) {
      return state.username
    },
    cityId(state) {
      return state.cityId
    },
    roles(state) {
      return state.roles
    },
    module(state) {
      return state.module
    },
    procedureTypes(state) {
      return state.procedureTypes
    },
    modalityLoan(state) {
      return state.modalityLoan
    },
    amortizationLoan(state) {
      return state.amortizationLoan
    },
    userRoles(state) {
      return state.userRoles
    },
    permissions(state) {
      return state.permissions
    },
    dateNow(state) {
      return state.dateNow
    },
    tokenType(state) {
      return state.tokenType
    },
    accessToken(state) {
      return state.accessToken
    },
    tokenExpiration(state) {
      return state.tokenExpiration
    },
    tokenExpired(state) {
      if (state.tokenExpiration) {
        return moment().isAfter(state.tokenExpiration)
      }
    },
    breadcrumbs(state) {
      return state.breadcrumbs
    },
    // devuelve todo el objeto seleccionado rol con sus permisos
    rolePermissionSelected(state) {
      return state.rolePermissionSelected
    },
    // devuelve todo el array de objetos permisos
    permissionSelected(state) {
      if(state.rolePermissionSelected && state.rolePermissionSelected.permissions && state.rolePermissionSelected.permissions.length > 0) {
        return state.rolePermissionSelected.permissions
      } else {
        return []
      }
    },
    // devuelve un array de names de los permisos
    permissionSimpleSelected(state) {
      if(state.rolePermissionSelected && state.rolePermissionSelected.permissions && state.rolePermissionSelected.permissions.length > 0) {
        return state.rolePermissionSelected.permissions.map(item => item.name)
      } else {
        return []
      }
    }
  },
  mutations: {
    logout(state) {
      state.id = null
      state.user = null
      state.username = null
      state.cityId = null
      state.userRoles = []
      state.permissions = []
      state.tokenType = null
      state.accessToken = null
      state.tokenExpiration = null
      state.module = {}
      state.procedureTypes = [],
      state.modalityLoan = [],
      state.amortizationLoan = []
    },
    login(state, data) {
      state.id = data.id
      state.user = data.user
      state.username = data.username
      state.cityId = data.city_id
      state.userRoles = data.roles
      state.permissions = data.permissions
      state.accessToken = data.access_token
      state.tokenType = data.token_type
      state.tokenExpiration = data.exp
      axios.defaults.headers.common['Authorization'] = `${data.token_type} ${data.access_token}`
    },
    setRoles(state, newValue) {
      state.roles = newValue
    },
    setDate(state, newValue) {
      state.dateNow = newValue
    },
    refreshToken(state, data) {
      state.accessToken = data.access_token
      state.tokenType = data.token_type
      state.tokenExpiration = moment.unix(jwt.decode(data.access_token).exp).format()
      axios.defaults.headers.common['Authorization'] = `${data.token_type} ${data.access_token}`
    },
    setBreadcrumbs(state, data) {
      state.breadcrumbs = data
    },
    addBreadcrumb(state, index, data) {
      state.breadcrumbs[index] = data
    },
    setModule(state, data) {
      state.module = data
    },
    setProcedureTypes(state, data) {
      state.procedureTypes = data
    },
    setModalityLoan(state, data) {
      state.modalityLoan = data
    },
    setAmortizationLoan(state, data) {
      state.amortizationLoan = data
    },
    setRolePermissionSelected(state, data) {
      state.rolePermissionSelected = data
    },
  },
  actions: {
    selectModule({ commit }, name) {
      const module = new Module()
      const procedureType = new ProcedureType()
      return module.get(null, {
        name: name,
        page: 1,
        per_page: 1
      }).then(res => {
        commit('setModule', res.data[0])
        return procedureType.get(null, {
          module_id: res.data[0].id,
          page: 1,
          per_page: 100
        })
      }).then(res => {
        commit('setProcedureTypes', res.data)
      }).catch(e => {
        console.log(e)
      })
    },
    selectModuleLoan({ commit }, name) {
      const module = new Module()
      const modalityLoan = new ModalityLoan()
      return module.get(null, {
        name: name,
        page: 1,
        per_page: 1
      }).then(res => {
        commit('setModule', res.data[0])
        return modalityLoan.get(null, {
          module_id: res.data[0].id,
          page: 1,
          per_page: 100
        })
      }).then(res => {
        commit('setModalityLoan', res.data)
      }).catch(e => {
        console.log(e)
      })
    },
    selectModuleAmortization({ commit }, name) {
      const module = new Module()
      const amortizationLoan = new AmortizationLoan()
      return module.get(null, {
        name: name,
        page: 1,
        per_page: 1
      }).then(res => {
        commit('setModule', res.data[0])
        return amortizationLoan.get(null, {
          module_id: res.data[0].id,
          page: 1,
          per_page: 100
        })
      }).then(res => {
        commit('setAmortizationLoan', res.data)
      }).catch(e => {
        console.log(e)
      })
    },
    logout({ commit }) {
      commit('logout')
      router.go('login')
    },
    login({ commit }, data) {
      const payload = jwt.decode(data.access_token)
      commit('login', Object.assign({ ...data, ...payload }, { exp: moment.unix(payload.exp).format() }))
      const role = new Role()
      role.get().then((data) => {
        commit('setRoles', data)
        router.go('dashboard')
      }).catch((e) => {
        commit('logout')
      })
    },
    async refresh({ commit, state }) {
      try {
        const expiration = moment(state.tokenExpiration)
        const now = moment()
        if (now.isAfter(expiration.clone().subtract(JSON.parse(process.env.MIX_JWT_TTL)/10, 'minutes')) && expiration.isAfter(now)) {
          const res = await axios.patch(`auth/${state.id}`)
          commit('refreshToken', res.data)
        }
      } catch (e) {
        console.log(e)
      }
    }
  },
  plugins: [vuexLocal.plugin]
}
