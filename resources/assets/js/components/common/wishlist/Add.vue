<template>
  
    <form>

        <el-row :gutter=20>

            <el-col :span="8" :offset="6">
            
                <div v-if="topics">

                    <el-form class="demo-createClassForm">

                        <el-form-item  prop="topic">
                            <el-select v-model="selectedTopicIds" multiple filterable clearable             placeholder="Select">
                                <el-option
                                    v-for="item in topics"
                                    :key="item.id"
                                    :label="item.name"
                                    :value="item.id">
                                </el-option>
                            </el-select>
                        </el-form-item>

                    </el-form>

                </div>

            </el-col>

            <el-col :span="4">
                        <el-button type="info" plain icon="el-icon-search" @click="passSelectedTopicsIds()"> add </el-button>
            </el-col>

        </el-row>

    </form>

</template>

<script>

export default {
    props: ['topics','add'],
    
    data() {
        return {
            selectedTopicIds: [],
        }
    },
    methods: {

        passSelectedTopicsIds() {
            this.$parent.add(this.selectedTopicIds);
            this.selectedTopicIds = [];
        },
        
    },
}
</script>

<style>

.el-cascader,.el-select,.el-input-number {
  width: 100%;
}

</style>