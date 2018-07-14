 <template>
  <div>
   <input type="file" @change="onFileChange">
   <button @click="onUpload">upload</button>
   </form>
  </div>
  </div>

</template>
<script>
export default{
  
  data(){
  	return{
  		selectedfiles:null
  	}
  },


	methods:{
		onFileChange(event){
			this.selectedfiles=event.target.files[0]
   //console.log(selectedfiles.name);
		},
		onUpload()
		{
        const fd=new FormData();
        fd.append('image',this.selectedfiles)
        axios.post('http://localhost:8000/ahamapi/tutor/classes/upload-file/'+this.$route.params.ahamclassid, fd)
        .then(res=>{
        	console.log(res)
        })


		}

	}
}
</script>
<style>
</style> 


