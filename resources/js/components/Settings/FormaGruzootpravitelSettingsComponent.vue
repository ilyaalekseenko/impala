<template>
    <div class="container">
        <div>Перевозки</div>
        <div class="row justify-content-start">
            <div>Тут добавлю грузоотправителя</div>
<!--            <div class="col-12 ad_pogr_marg1" v-for="(forma,key) in forma_arr">-->
<!--                <input class="cr_ord_inp_n_7 border_input" @blur="update_perevozka_settings(key)" v-model="forma_arr[key]['forma_name']"  />-->
<!--                <button type="button" class="btn btn-danger btn_del_in_ord" v-on:click="delete_perevozka_settings(key)">-</button>-->
<!--            </div>-->
<!--            <div class="col-2 add_settings_doc_button">-->
<!--            <button type="button" class=" btn btn-success" v-on:click="add_perevozka_settings()">+</button>-->
<!--            </div>-->
            </div>
    </div>
</template>

<script>
    export default {
        mounted() {
           this.get_perevozka_list(this.forma_arr)
        },
        data() {
            return {
                forma_arr:[]
            }
        },
        methods: {
            get_perevozka_list(inp)
            {
                axios
                    .post('/get_forma_gruzootpravitel_list',{
                    })
                    .then(({ data }) => (
                            data.forma_list.forEach(function(entry) {
                                inp.push({
                                    id:entry.id,
                                    forma_name:entry.forma_name,
                                });
                            })
                        )
                    );
            },
            update_perevozka_settings(key)
            {
                if(this.forma_arr[key]['forma_name']!=='')
                {
                axios
                    .post('/update_forma_settings',{
                        forma:this.forma_arr[key],
                    })
                }
            },
            delete_perevozka_settings(key)
            {
                axios
                    .post('/delete_forma_settings',{
                        forma:this.forma_arr[key],
                    })
                this.forma_arr.splice(key,1)

            },
            add_perevozka_settings(key)
            {
                // this.perevozka_arr[this.perevozka_arr.length-1]
                let objToPush= {};
                objToPush['id'] ='';

                objToPush['forma_name'] ='';
                this.forma_arr.push(objToPush);
                axios
                    .post('/add_forma_settings',{
                    })
                    .then(({ data }) => (
                        this.forma_arr[this.forma_arr.length-1].id=data.result.id
                        )
                    );

            },
        }

    }
</script>
