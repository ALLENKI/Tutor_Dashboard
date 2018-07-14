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

            <el-col :span="4">
              <el-select v-model="searchForm.status" placeholder="Select">
                  <el-option
                    v-for="item in status"
                    :key="item.value"
                    :label="item.label"
                    :value="item.value">
                  </el-option>
              </el-select>
            </el-col>

            <el-col :span="12">
              <el-input placeholder="Please input" clearable v-model="searchForm.input" v-on:keyup.enter.native="search()"></el-input>
            </el-col>

            <el-col :span="4">
              <el-button type="info" plain icon="el-icon-search" @click="search()">Search</el-button>
            </el-col>

          </el-row>

				</form>

				<hr>

				<div class="row">

					<div class="col-md-3" v-for="topic in topics">
						<course-catalog-card :topic="topic"></course-catalog-card>
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
import CourseCatalogCard from "./CourseCatalogCard";

export default {
  props:['fetchTopics'],
  components: {
    CourseCatalogCard
  },
  data() {
    return {
      search_options: [
        {
          value: "all",
          label: "All"
        },
        {
          value: "category",
          label: "Category"
        },
        {
          value: "subject",
          label: "Subject"
        },
        {
          value: "sub-category",
          label: "Sub Category"
        },
        {
          value: "topic",
          label: "Topic"
        }
      ],
      status: [
        {
          value: "all",
          label: "All"
        },
        {
          value: "active",
          label: "Active"
        },
        {
          value: "in_progress",
          label: "In Progress"
        },
        {
          value: "in_future",
          label: "In Future"
        },
        {
          value: "obsolete",
          label: "Obsolete"
        }
      ],
      searchForm: {
        type: "all",
        input: "",
        status: "all",
      },
      topics: [],
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

      this.fetchTopics(payload)
      .then(response => {
          if (page > 1) {
            this.topics = _.union(this.topics, response.data.data);
          } else {
            this.topics = response.data.data;
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
