<template>

    <div>

        <form v-on:submit.prevent="search">

          <el-row>

            <el-col :span="4">
              <el-select v-model="searchForm.type" placeholder="Select">
                  <el-option
                  v-for="item in search_options"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value">
                  </el-option>
              </el-select>
            </el-col>

            <el-col :span="16">
              <el-input placeholder="Please input" clearable v-model="searchForm.input" v-on:keyup.enter.native="search()"></el-input>
            </el-col>

            <el-col :span="4">
              <el-button type="info" plain icon="el-icon-search" @click="search()">Search</el-button>
            </el-col>

          </el-row>

        </form>

            <hr>

        <div class="row">
          
            <div class="col-md-3" v-for="course in courses">
              <course-catalog-course-card :course="course"></course-catalog-course-card>
            </div>
            
            <div class="clearfix"></div>

            <el-button type="info" plain icon="el-icon-search" @click="search(pagination.current_page+1)"  v-if="pagination && pagination.current_page != pagination.total_pages">
              Load More From Page {{ pagination.current_page+1 }}
            </el-button>
          
        </div>

    </div>
    
</template>

<script>
import store from "../../store";
import CourseCatalogCourseCard from "./CourseCatalogCourseCard";

export default {
  props:['fetchCourses'],
  components: {
    CourseCatalogCourseCard
  },
  data() {
    return {
      search_options: [
        {
          value: "all",
          label: "All"
        },
        {
          value: "collection_of_topics",
          label: "Collection of Topics"
        },
        {
          value: "collection_of_courses",
          label: "Collection of Courses"
        }
      ],
      searchForm: {
        type: "all",
        input: ""
      },
      courses: [],
      pagination: false
    };
  },
  mounted() {
    store.dispatch("setHeading", "Browse Catalog");
    this.search();
  },
  methods: {

    search(page) {
      page = page || 1;
      let payload = this.searchForm;
      payload["page"] = page;

      this.fetchCourses(payload)
      .then(response => {

          if (page > 1) {
            this.courses = _.union(this.courses, response.data.data);
          } else {
            this.courses = response.data.data;
          }

          this.pagination = response.data.meta.pagination;
          store.dispatch(
            "setHeading",
            "Browse Catalog: Total " + this.pagination.total
          );
          
      })
      .catch(error => {
        console.log(error);
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
