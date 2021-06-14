<template>
  <v-card flat>
    <v-card-title >
      <v-toolbar  dense color="tertiary" style="z-index: 1;">
        <v-toolbar-title>
          <Breadcrumbs/>
        </v-toolbar-title>
        <v-spacer></v-spacer>
      </v-toolbar>
    </v-card-title>
    <template>
      <v-container>
        <div>
          <v-row>
            <v-col  cols="6">
              <span>
                <v-tooltip
                left
                >
                <template v-slot:activator="{ on }">
                  <v-btn
                    icon
                    dark
                    small
                    color="success"
                    bottom
                    right
                    v-on="on"
                    :to="{ name: 'affiliateAdd', params: { id: $route.query.affiliate_id }}"
                  >
                  <v-icon>mdi-arrow-left-bold-outline</v-icon>
                  </v-btn>
                </template>
                <span>Regresar</span>
                </v-tooltip>
              </span>
              {{"TITULAR: "+this.degree_name}} {{this.$options.filters.fullName(this.affiliate, true)}}
            </v-col>
            <v-col  cols="6">
              {{'CATEGORÍA:'+this.category_name}}
            </v-col>
          </v-row>
          <Steps
            :affiliate.sync="affiliate"
            :affiliate_data.sync="affiliate_data"
            :addresses.sync="addresses"/>
        </div>
      </v-container>
    </template>
  </v-card>
</template>

<script>
import Steps from '@/components/loan/Steps'
import Ballots from '@/components/loan/Ballots'
import Breadcrumbs from '@/components/shared/Breadcrumbs'

export default {
  name: "loan-add",
  components: {
    Steps,
    Ballots,
    Breadcrumbs
  },
  data: () => ({
    addresses:[],
    affiliate:{
      phone_number:null,
      cell_phone_number:null
    },
    degree_name: null,
    category_name: null,
    affiliate_data:{}
  }),
  computed: {
    isNew() {
      return this.$route.params.hash == 'new'
    }
  },
  beforeMount() {
    this.$store.commit('setBreadcrumbs', [
      {
        text: 'Préstamos',
        to: { name: 'flowIndex' }
      }
    ])
  },
  mounted() {
    this.getAffiliate(this.$route.query.affiliate_id)
    this.getAddress(this.$route.query.affiliate_id)
  },
  methods:{
    async getAffiliate(id) {
      try {
        this.loading = true
        let res = await axios.get(`affiliate/${id}`)
        this.affiliate = res.data
        this.affiliate_data.cpop_affiliate=this.affiliate.cpop
        this.getCategory_name(res.data.category_id)
        this.getDegree_name(res.data.degree_id)
        this.setBreadcrumbs()
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    },
  setBreadcrumbs() {
    let breadcrumbs = [
      {
        text: 'Préstamos',
        to: { name: 'flowIndex' }
      }
    ]
    if (this.isNew) {
      breadcrumbs.push({
        text: 'Nuevo Préstamo',
        to: { name: 'flowIndex', params: { id: 'new' } }
      })
      } else {
      breadcrumbs.push({
        text: this.$options.filters.fullName(this.affiliate, true),
        to: { name: 'flowIndex', params: { id: this.affiliate.id } }
      })
    }
    this.$store.commit('setBreadcrumbs', breadcrumbs)
  },
  async getAddress(id) {
      try {
        this.loading = true
        let res = await axios.get(`affiliate/${id}/address`)
        this.addresses = res.data
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
  },
    async getDegree_name(id) {
      try {
        this.loading = true;
        let res = await axios.get(`degree/${id}`)
        this.degree_name = res.data.shortened
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    },
    async getCategory_name(id) {
      try {
        this.loading = true;
        let res = await axios.get(`category/${id}`);
        this.category_name = res.data.name;
      } catch (e) {
        console.log(e);
      } finally {
        this.loading = false;
      }
    }
  }
}
</script>