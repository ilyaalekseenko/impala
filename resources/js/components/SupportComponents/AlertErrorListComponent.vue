<template>
    <div v-if="show_alert" class="alert alert-danger">
        <ul>
            <li v-for="error in alert_list_local" :key="error"  role="alert" class="alert_text">{{ error }}</li>
        </ul>
    </div>
</template>

<script>
    export default {
        props: ['alert_list'],
        data(){
            return {
                show_alert:false,
                alert_list_local:[]
            }
        },
        watch: {
            alert_list: function () {
                    this.alert_list_local=this.alert_list
            },
        },
        methods: {
            hide_alert_list()
            {
                this.show_alert=false
            },
            show_alert_list()
            {
                this.show_alert=true
            },
            clear_alert_list()
            {
                this.alert_list_local=[]
            }
        }
    }
</script>
