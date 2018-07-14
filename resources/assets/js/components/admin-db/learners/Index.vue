<template>
    <div>

    <form v-on:submit.prevent="search">

        <el-row>

            <el-col :span="8">
              <el-select v-model="searchForm.preferred_location" clearable placeholder="Select">
                  <el-option
                  v-for="item in locations"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id">
                  </el-option>
              </el-select>
            </el-col>

            <el-col :span="12">
                <el-input placeholder="Search by name or email.." clearable v-model="searchForm.input" v-on:keyup.enter.native="search()"></el-input>
            </el-col>

            <el-col :span="4">
                <el-button type="info" plain icon="el-icon-search" @click="search()">Search</el-button>
            </el-col>

        </el-row>

    </form>

    <hr>

        <div class="row">
            <div class="col-md-3" v-for="learner in learners">
                <learner-card :learner="learner"></learner-card>
            </div>

            <div class="clearfix"></div>

            <el-button type="info" plain icon="el-icon-search" @click="search(pagination.current_page+1)"  v-if="pagination && pagination.current_page != pagination.total_pages">
                Load More From Page {{ pagination.current_page+1 }}
            </el-button>

        </div>
        
    </div>
</template>

<script>
import store from "../../../store";
import LearnerCard from "./LearnerCard";
import learner from "../../../services/learner.js";

export default {
  components: {
    LearnerCard
  },
  data() {
    return {
      searchForm: {
        type: "all",
        input: "",
        preferred_location: ""
      },
      locations: [],
      learners: [],
      pagination: false
    };
  },
  mounted() {
    store.dispatch("setHeading", "Learners");

    axios.get('/hub/available-locations')
      .then((response) => {
            this.locations = response.data.locations;
      })
      .catch((error) => {
        console.log(error);
      });

    this.search();
  },
  methods: {
    search(page) {

      page = page || 1;
      let payload = this.searchForm;
      payload["page"] = page;

      learner.index(payload).then((response) => {

          if (page > 1) {
            this.learners = _.union(this.learners, response.data.data);
          } else {
            this.learners = response.data.data;
          }

          this.pagination = response.data.meta.pagination;
          store.dispatch(
            "setHeading",
            "Browse Learners: Total " + this.pagination.total
          );

      });
      
    }
  }
};
</script>

<style scoped>
.el-select,
.el-button {
  width: 100%;
}
</style>
