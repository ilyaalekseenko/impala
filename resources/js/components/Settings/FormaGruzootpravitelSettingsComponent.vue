<template>
    <div class="container">
        <div>Перевозки</div>
        <modal-pogruzka-component ref="modalComponentforAction" edit_flag=true  ></modal-pogruzka-component>
        <div class="row justify-content-start">
            <div >Список грузоотправителей</div>
            <div class="col-12 ad_pogr_marg1 row" v-for="(gruzootpravitel,key) in gruzootpravitel_arr">
                <div class="col-6">{{ gruzootpravitel.nazvanie }}</div>
                <button type="button" class="btn btn-info col-2 offset-1" v-on:click="show_mod_edit(gruzootpravitel.id)">Редактировать</button>
                <button type="button" class="btn btn-danger btn_del_in_ord col-2 offset-1" v-on:click="delete_gruzootpravitel(key)">-</button>
            </div>
            </div>
    </div>
</template>

<script>
    export default {

        mounted() {
           this.get_perevozka_list(this.gruzootpravitel_arr)
        },
        data() {
            return {
                gruzootpravitel_arr:[],
                show_mod:false
            }
        },
        methods: {
//мрдалка показ
            show_mod_edit(id)
            {
               // this.show_mod=true

                //вызов метода дочернего компонента( модального окна )
                this.$refs.modalComponentforAction.get_modal_edit_data(id)

            },
            get_perevozka_list(inp)
            {
                axios
                    .post('/get_forma_gruzootpravitel_list',{
                    })
                    .then(({ data }) => (
                            data.forma_list.forEach(function(entry) {
                                inp.push({
                                    id:entry.id,
                                    nazvanie:entry.nazvanie,
                                });
                            })
                        )
                    );
            },

          async delete_gruzootpravitel(key)
            {
                const result = await this.confirmMethodMixin();
                if (result) {
                    axios
                        .post('/delete_gruzootpravitel', {
                            id: this.gruzootpravitel_arr[key]['id'],
                        })
                    this.gruzootpravitel_arr.splice(key, 1)
                }
            },

        }

    }
</script>
