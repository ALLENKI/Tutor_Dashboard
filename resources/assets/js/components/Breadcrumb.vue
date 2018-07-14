<template>
<ul class="m-subheader__breadcrumbs m-nav m-nav--inline" v-if="breadcrumb">
    <li class="m-nav__item m-nav__item--home" v-for="(link,index) in breadcrumb.links">
        <a :href="link.link" class="m-nav__link m-nav__link--icon">
            {{ link.title }} 
            <span class="m-nav__separator" v-if="(index+1) != breadcrumb.links.length">&nbsp; - &nbsp;</span>
        </a>
        
    </li>
</ul>
</template>

<script>
import store from "../store";
export default {
    computed:{
      breadcrumb () {
        return this.$store.state.breadcrumb;
      }
    }
};
</script>
