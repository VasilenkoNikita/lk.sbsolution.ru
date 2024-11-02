<template>

</template>
<script>
import VueDragscroll from 'vue-dragscroll'
Vue.use(VueDragscroll)
export default {
    components: {
        VueDragscroll
    },
    props: ['table'],
    data() {
        return {
            table: this.table,
            csrf: this.token
        }
    },
    methods: {
        scrollToElement: function() {
            // const el = this.$el.getElementsByClassName('index-50')[0];
            if (this.scroll){
                //  this.$el.getAttribute('data-id')
                //  el.scrollIntoView({behavior: 'smooth'});
                console.log(this.$refs)
            }
        }
    }
}
</script>
