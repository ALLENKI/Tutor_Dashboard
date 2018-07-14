<template>
    <div>

    <form v-on:submit.prevent="search">

        <el-row>

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
            <div class="col-md-3" v-for="tutor in tutors">
                <tutor-card :tutor="tutor"></tutor-card>
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
import TutorCard from "./TutorCard";
import tutor from "../../../services/tutor.js";

export default {
  components: {
    TutorCard
  },
  data() {
    return {
      searchForm: {
        type: "all",
        input: ""
      },
      locations: [],
      tutors: [],
      pagination: false
    };
  },
  mounted() {
    store.dispatch("setHeading", "Tutors");
    this.search();
  },
  methods: {
    search(page) {

      page = page || 1;
      let payload = this.searchForm;
      payload["page"] = page;

      tutor.index(payload).then((response) => {

          if (page > 1) {
            this.tutors = _.union(this.tutors, response.data.data);
          } else {
            this.tutors = response.data.data;
          }

          this.pagination = response.data.meta.pagination;
          store.dispatch(
            "setHeading",
            "Browse Tutors: Total " + this.pagination.total
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
