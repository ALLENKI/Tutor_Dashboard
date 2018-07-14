<template>
    <div>

      <div class="row">

        <div class="col-md-6">

          <div class="m-portlet m-portlet--mobile" v-if="course">

              <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                  <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                      course - {{ course.name }}
                    </h3>
                  </div>
                </div>

                <div class="m-portlet__head-tools">
                  <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                      
                      <router-link :to="{ name: 'edit-course', params: {course: course.id} }"  class="m-portlet__nav-link btn btn-success m-btn m-btn--pill m-btn--air">
                        Edit
                      </router-link>

                    </li>
                  </ul>
                </div>

              </div>

              <div class="m-portlet__body">

                <ul class="list-unstyled">

                  <li> <strong>Name:</strong> {{ course.name }}</li>

                  <li> <strong>Type:</strong> {{ course.type }}</li>

                  <li> <strong>Description:</strong>
                  
                    <read-more more-str="View" :text="course.description" link="#" less-str="Hide" :max-chars="0" v-if="course.description">
                    </read-more>

                  </li>

                </ul>

              </div>

          </div>

        </div>

        <div v-if="courseTree" class="col-md-6">
                <div class="m-section">
									<div class="m-section__content">
										<table class="table m-table m-table--head-separator-danger">
											<thead>
												<tr>
												
													<th v-if="course">
														
                            <span v-if="course.type == 'collection_of_topics'">
                          <strong>  Topics </strong>
                          </span> 
                          <span v-if="course.type == 'collection_of_courses'">
                           <strong> Courses </strong>
                          </span>

													</th>
												</tr>
											</thead>
											<tbody>
												<tr v-for="item in courseTree">
												
													<td>
														{{item.name}}
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
              </div>

        </div>

    </div>
</template>

<script>

export default {
  data() {
    return {
      course: null,
      courseTree: null,
    };
  },
  mounted() {
    this.getCourse();
    this.getTree();
  },
  methods: {

    getCourse() {
      axios
        .get("course_catalog/courses/" + this.$route.params.course + "/show")
        .then(response => {
          this.course = response.data;
        })
        .catch(error => {
          console.log(error);
        });
    },

    getTree() {
      axios
        .get("course_catalog/courses/" + this.$route.params.course + "/tree")
        .then(response => {
          this.courseTree = response.data;
        })
        .catch(error => {
          console.log(error);
        });
    },

  }
};
</script>
<style>
#readmore {
  margin-bottom: 10px;
  display: block;
}
</style>
