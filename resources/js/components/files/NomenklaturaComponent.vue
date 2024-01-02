<template>
    <span class="container nomenklaturaFull" v-on:click="downloadNomenklaturaFull()">
        <span class="iconify" data-icon="fa6-solid:download" style="color: #4d4d4d;" data-width="24" data-height="24" ></span>
    </span>
</template>

<script>
    export default {
        props: {
            order_id: {
                type: String, // Тип данных, которые вы ожидаете
            },
        },
        mounted() {
        },
        methods: {
            downloadNomenklaturaFull()
            {
                axios
                    .post('/downloadNomenklaturaFull',{
                        id:this.order_id,
                    })
                    .then(response => {
                        window.location.assign('/downloadFileByName/'+this.order_id+'.xlsx') ;
                        axios
                            .post('/delFile',{
                                pathToDel:'/united/'+this.order_id+'.xlsx',
                            })
                    })
            },
        }
    }
</script>
