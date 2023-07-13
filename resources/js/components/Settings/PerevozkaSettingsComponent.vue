<template>
    <div class="container">
        <div>Виды перевозок</div>
        <div class="row justify-content-start">
            <div class="col-12 ad_pogr_marg1" v-for="(perevozka,key) in perevozka_arr">
                <input class="cr_ord_inp_n_7 border_input" @blur="update_perevozka_settings(key)" v-model="perevozka_arr[key]['perevozka_name']"  />
                <button type="button" class="btn btn-danger btn_del_in_ord" v-on:click="delete_perevozka_settings(key)">-</button>
            </div>
            <div class="col-2 add_settings_doc_button">
            <button type="button" class=" btn btn-success" v-on:click="add_perevozka_settings()">+</button>
            </div>
            </div>
    </div>
</template>

<script>
    export default {
        props: ['title'],
        mounted() {
           this.get_perevozka_list(this.perevozka_arr)
        },
        data() {
            return {
                perevozka_arr:[]
            }
        },
        methods: {
            get_perevozka_list(inp)
            {
                axios
                    .post('/getVidPerevozki',{
                    })
                    .then(({ data }) => (
                            data.perevozka_list.forEach(function(entry) {
                                inp.push({
                                    id:entry.id,
                                    perevozka_name:entry.perevozka_name
                                });
                            })
                        )
                    );
            },
            update_perevozka_settings(key)
            {
                if(this.perevozka_arr[key]['perevozka_name']!=='')
                {
                axios
                    .post('/updateVidPerevozka',{
                        perevozka:this.perevozka_arr[key],
                    })
                }
            },
            delete_perevozka_settings(key)
            {
                axios
                    .post('/deleteVidPerevozka',{
                        perevozka:this.perevozka_arr[key],
                    })
                this.perevozka_arr.splice(key,1)

            },
            add_perevozka_settings(key)
            {
                let objToPush= {};
                objToPush['id'] ='';
                objToPush['perevozka_name'] ='';
                this.perevozka_arr.push(objToPush);
                axios
                    .post('/addVidPerevozka',{
                    })
                    .then(({ data }) => (
                        this.perevozka_arr[this.perevozka_arr.length-1].id=data.result.id
                        )
                    );

            },
        }

    }
</script>
