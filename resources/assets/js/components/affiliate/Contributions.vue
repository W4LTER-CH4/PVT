<template>
  <div>
    <v-row>
      <v-col cols="12">
        <v-toolbar-title>CONTRIBUCIONES DEL AFILIADO</v-toolbar-title>
      </v-col>
    </v-row>

    <v-data-table
      :headers="headers"
      :items="affiliates"
      :loading="loading"
      :options.sync="options"
      :server-items-length="totalAffiliates"
      :footer-props="{ itemsPerPageOptions: [8, 15, 30] }"
    >
      <template v-slot:[`item.month_year`]="{ item }">
        {{ $moment(item.month_year).format("MM-YYYY") }}
      </template>
      <template v-slot:[`item.degree_id`]="{ item }">
        {{ searchDegree(item.degree_id) }}
      </template>
      <template v-slot:[`item.category_id`]="{ item }">
        {{ searchCategory(item.category_id) }}
      </template>
      <template v-slot:[`item.breakdown`]="{ item }">
        {{ item.breakdown.name }}
      </template>
      <template v-slot:[`item.unit_id`]="{ item }">
        {{ searchUnit(item.unit_id) }}
      </template>
      <template v-slot:[`item.border_bonus`]="{ item }">
        {{ item.border_bonus | money }}
      </template>
      <template v-slot:[`item.east_bonus`]="{ item }">
        {{ item.east_bonus | money }}
      </template>
      <template v-slot:[`item.position_bonus`]="{ item }">
        {{ item.position_bonus | money }}
      </template>
      <template v-slot:[`item.public_security_bonus`]="{ item }">
        {{ item.public_security_bonus | money }}
      </template>
      <template v-slot:[`item.payable_liquid`]="{ item }">
        {{ item.payable_liquid | money }}
      </template>
    </v-data-table>
  </div>
</template>



<script>
export default {
  name: "contributions-list",

  props: ["bus"],
  data: () => ({
    loading: true,
    search: "",
    options: {
      page: 1,
      itemsPerPage: 8,
      sortBy: ["month_year"],
      sortDesc: [true],
    },
    affiliates: [],
    totalAffiliates: 0,
    headers: [
      {
        text: "Período",
        value: "month_year",
        class: ["normal", "white--text"],
        sortable: false,
        width: "10%",
      },
      {
        text: "Grado",
        value: "degree_id",
        class: ["normal", "white--text"],
        sortable: false,
      },
      {
        text: "Categoría",
        value: "category_id",
        class: ["normal", "white--text"],
        sortable: false,
      },
      {
        text: "Desglose",
        value: "breakdown",
        class: ["normal", "white--text"],
        sortable: false,
      },
      {
        text: "Unidad",
        value: "unit_id",
        class: ["normal", "white--text"],
        sortable: false,
      },
      {
        text: "Bono Frontera",
        value: "border_bonus",
        class: ["normal", "white--text"],
        sortable: false,
        width: "8%",
      },
      {
        text: "Bono Oriente",
        value: "east_bonus",
        class: ["normal", "white--text"],
        sortable: false,
        width: "8%",
      },
      {
        text: "Bono Cargo",
        value: "position_bonus",
        class: ["normal", "white--text"],
        sortable: false,
        width: "8%",
      },
      {
        text: "Bono Seguridad Ciudadana",
        value: "public_security_bonus",
        class: ["normal", "white--text"],
        sortable: false,
        width: "8%",
      },
      {
        text: "Liquido pagable",
        value: "payable_liquid",
        class: ["normal", "white--text"],
        sortable: false,
        width: "8%",
      },
    ],
    state: [],
    category: [],
    degree: [],
    unit: [],
  }),
  watch: {
    options: function (newVal, oldVal) {
      if (
        newVal.page != oldVal.page ||
        newVal.itemsPerPage != oldVal.itemsPerPage ||
        newVal.sortBy != oldVal.sortBy ||
        newVal.sortDesc != oldVal.sortDesc
      ) {
        this.getContributions();
      }
    },
    search: function (newVal, oldVal) {
      if (newVal != oldVal) {
        this.options.page = 1;
        this.getContributions();
      }
    },
  },
  mounted() {
    this.getContributions();
    this.getCategory();
    this.getDegree();
    this.getUnit();
    //this.getAffiliateState()
  },
  methods: {
    async getContributions(params) {
      try {
        this.loading = true;
        let res = await axios.get(
          `affiliate/${this.$route.params.id}/contributions_affiliate`,
          {
            params: {
              //city_id: this.$store.getters.cityId,
              page: this.options.page,
              per_page: this.options.itemsPerPage,
              sortBy: this.options.sortBy,
              sortDesc: this.options.sortDesc,
              search: this.search,
            },
          }
        );
        this.affiliates = res.data.data;
        this.totalAffiliates = res.data.total;
        delete res.data["data"];
        this.options.page = res.data.current_page;
        this.options.itemsPerPage = parseInt(res.data.per_page);
        this.options.totalItems = res.data.total;
      } catch (e) {
        console.log(e);
      } finally {
        this.loading = false;
      }
    },
    async getCategory(id) {
      try {
        this.loading = true;
        let res = await axios.get(`category`);
        this.category = res.data;
      } catch (e) {
        console.log(e);
      } finally {
        this.loading = false;
      }
    },
    searchCategory(item) {
      let procedureCategory = this.category.find((o) => o.id == item);
      if (procedureCategory) {
        return procedureCategory.name;
      } else {
        return null;
      }
    },
    async getDegree(id) {
      try {
        this.loading = true;
        let res = await axios.get(`degree`);
        this.degree = res.data;
      } catch (e) {
        console.log(e);
      } finally {
        this.loading = false;
      }
    },
    searchDegree(item) {
      let procedureDegree = this.degree.find((o) => o.id == item);
      if (procedureDegree) {
        return procedureDegree.name;
      } else {
        return null;
      }
    },

    async getUnit(id) {
      try {
        this.loading = true;
        let res = await axios.get(`unit`);
        this.unit = res.data;
      } catch (e) {
        console.log(e);
      } finally {
        this.loading = false;
      }
    },
    searchUnit(item) {
      let procedureUnit = this.unit.find((o) => o.id == item);
      if (procedureUnit) {
        return procedureUnit.name;
      } else {
        return null;
      }
    },

  },
};
</script>

