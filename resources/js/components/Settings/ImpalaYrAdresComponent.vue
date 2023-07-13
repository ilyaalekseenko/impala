<template>
    <div class="container">
        <div>Организация</div>
        <div class="row col-10 justify-content-start">
                <input class="textar_settings border_input" @blur="updateAdres()" v-model="adres"  />
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
           this.getimpalaAdres()
        },
        data() {
            return {
                adres:''
            }
        },
        methods: {
            getimpalaAdres()
            {
                axios
                    .post('/getImpalaAdres',{
                    })
                    .then(response => {
                        if (response.data.status === 'success') {
                            this.adres=response.data.adres
                        }
                    })
            },
            updateAdres()
            {
                axios
                    .post('/updateAdres',{
                        adres:this.adres
                    })
            },

        }

    }
</script>
