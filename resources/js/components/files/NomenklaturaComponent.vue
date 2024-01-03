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
                    })
                    .catch(error => {
                        let errorMessage=error.response.data.message
                        let regex = /Workbook already contains a worksheet named/;
                        let regex2 = /Rename the external sheet first/g;
                        let modifiedMessage = errorMessage
                            .replace(regex, 'Ошибка, дублирование названия страницы')
                            .replace(regex2, 'Переименуйте лист в любом из документов');
                        alert(modifiedMessage);
                    });
            },
        }
    }
</script>
