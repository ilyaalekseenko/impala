<template>
    <div class="container orders_main">
        <div class="row orders_title_div">
            <modal-perevozchiki-component
                ref="modalComponentforAction"
                edit_flag=true
                :addOnePerevozchik='addOnePerevozchik'
                :change_one_gruzzotpravitel='change_one_gruzzotpravitel'>
            </modal-perevozchiki-component>
            <div class="col-12 row">
                <div class="col-6 orders_title">
                   Перевозчики
                </div>
                <div class="col-12 coloring_row row">
                    <div class="col-12 under_col_title_main">
                        <input type="checkbox" id="checkbox" v-on:click="check_all()" v-model="checked_all">
                        <span class="under_colored_title head_font" v-on:click="delete_gruzootpravitel()">Удалить</span>
                        <span class="under_colored_title head_font">Переместить</span>
                        <span class="under_colored_title head_font" v-on:click="mix_mark_as_important(gruzootpravitel_arr,'Gruzootpravitel')" >Важное</span>
                        <span class="col add_button_perevozchiki" v-b-modal.perevozkaMod variant="primary" v-on:click="newPerevozchik()">Добавить</span>
                    </div>
                    <div class="col-12 row  table_orders_column_settings">
                        <div class="col-12 row no_padding_right border_in_orders">
                            <div class="col-2 orders_title_table_main text-start row">
                                <div class="col-2"></div>
                                <div class="col-10 head_font"> Название</div>
                            </div>
                            <div class="col-2 orders_title_table_main head_font">Адрес</div>
                            <div class="col-8 orders_title_table_main head_font">Контактное лицо</div>
                        </div>
                        <div v-on:click="show_mod_edit(gruzootpravitel.id)"  v-for="(gruzootpravitel,key) in gruzootpravitel_arr" class="col-12 row no_padding_right border_in_orders" v-bind:class="{ important_back: gruzootpravitel.important==1 }">
                        <div class="col-2 orders_title_table text-start row">
                            <input class="col-2 checkbox_orders" type="checkbox" id="checkbox1" v-model="gruzootpravitel.checked_order">
                            <div class="col-10" v-b-modal.perevozkaMod variant="primary">{{ gruzootpravitel.nazvanie }}</div>
                        </div>
                        <div class="col-2 orders_title_table" v-b-modal.perevozkaMod variant="primary">{{ gruzootpravitel.YR_adres }}</div>
                        <div class="col-8 orders_title_table t1" v-b-modal.perevozkaMod variant="primary">
                            <div v-for="(kont_lico,key) in gruzootpravitel.kontaktnoe_lico">
                            <span>{{ kont_lico.FIO }} </span>
                            <span>{{ kont_lico.dolznost }} </span>
                            <span>{{ kont_lico.telefon }} </span>
                            <span>{{ kont_lico.email }} </span>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 row">
                    <div class="col-6 pagination_orders_left">Показывать:
                         <span :class="{ blue_ref: offset!=20 }" v-on:click="show_by(20)">по 20</span>
                        / <span :class="{ blue_ref: offset!=50 }" v-on:click="show_by(50)">по 50</span>
                        / <span :class="{ blue_ref: offset!=100 }" v-on:click="show_by(100)">по 100</span>
                    </div>
                    <div class="col-5 pagination_orders_right row">
                        <!--            пагинация          -->
                        <nav class="col d-flex justify-content-end"  aria-label=" Page navigation example">
                            <pagination-component
                                :countAllTypes="countAllTypes"
                                :countLimit="limit"
                                :dataFromPagination="dataFromPagination"
                                ref="PaginationComponent"
                            ></pagination-component>
                        </nav>
                    </div>



                </div>
            </div>
        </div>


    </div>
</template>

<script>
import moment from 'moment'
Vue.filter('formatDate', function(value) {
    if (value) {
        return moment(String(value)).format('DD.MM.YYYY')
    }
});
    export default {
        props: ['auth_user'],
        mounted() {
            this.get_gruzootpravitel_list(this.gruzootpravitel_arr)

        },
        data() {
            return {
                checked_all: '',
                orders_list:[],
                //пагинация
                pagination_all:0,
                current_page:1,
                pagination_numb:[],
                offset:0,
                limit:20,
                last_pagination_number:true,
                //конец пагинации
                ocenka:0,
                delete_arr:[],
                type_per_list:[],
                gruzootpravitel_arr: [],
                zurnal_zaiavok:0,
                ocenka_counter:0,
                header_underscore_list_class:[true,false,false,false,false,false],
                order_by:'0',
                offset_from_start:0,
                countAllTypes:0
            }
        },
        created()
        {
            // this.get_type_per_list(this.type_per_list);
        },
        methods: {
            //метод вызываемый из дочернего компонента
            addOnePerevozchik(newPerevId,nazvanie,yridicheskii_adres,kontakty)
            {
                console.log(newPerevId)
                let objToPush= {};
                objToPush['id'] = newPerevId;
                objToPush['nazvanie'] = nazvanie;
                objToPush['YR_adres'] = yridicheskii_adres;
                objToPush['kontaktnoe_lico'] = kontakty;
                objToPush['checked_order'] = false;
                this.gruzootpravitel_arr.push(objToPush);
            },
            change_one_gruzzotpravitel(id,nazvanie,yr_adres,kontakty)
            {

                for( let i = 0; i < this.gruzootpravitel_arr.length; i++ )
                {
                    if(this.gruzootpravitel_arr[i].id==id)
                    {
                        this.gruzootpravitel_arr[i].nazvanie=nazvanie
                        this.gruzootpravitel_arr[i].YR_adres=yr_adres
                        this.gruzootpravitel_arr[i].kontaktnoe_lico=kontakty
                    }

                }
            },
            go_to_order(id)
            {
                axios
                    .post('/check_if_order_isset',{
                        id:id,
                    })
                    .then(response => {
                        if(response.data.data=='isset')
                        {
                            window.location.href =('/create_orders/'+id)
                        }
                        else
                        {
                            alert('Запрос удалён')
                        }

                    })
              //  window.location.href =('/create_orders/'+id)
            },
            show_mod_edit(id)
            {
                //вызов метода дочернего компонента( модального окна )
                this.$refs.modalComponentforAction.get_modal_edit_data(id)
            },
            newPerevozchik(id)
            {
                //вызов метода дочернего компонента( модального окна )
                this.$refs.modalComponentforAction.newPerevozchik()
            },
            check_all()
            {
                for( let i = 0; i < this.gruzootpravitel_arr.length; i++ )
                {
                   this.gruzootpravitel_arr[i].checked_order=!this.gruzootpravitel_arr[i].checked_order
                }
            },

           async delete_gruzootpravitel()
            {
                const result = await this.confirmMethodMixin();

                if (result) {
                let temp_arr=[];
                for( let i = 0; i < this.gruzootpravitel_arr.length; i++ )
                {
                        if(this.gruzootpravitel_arr[i].checked_order==true)
                        {
                            this.delete_arr.push(this.gruzootpravitel_arr[i].id)
                        }
                        else
                        {
                            temp_arr.push(this.gruzootpravitel_arr[i])
                        }
                }
                this.gruzootpravitel_arr=temp_arr
               axios
                   .post('/deletePerevozka',{
                       ids:this.delete_arr,
                   })
                this.delete_arr=[]
            }
            },
            show_by(int)
            {
                this.offset=int
                this.limit=int
                this.pagination_all=0
                this.current_page=1,
                this.pagination_numb=[],
                this.gruzootpravitel_arr=[]
                this.get_gruzootpravitel_list(this.gruzootpravitel_arr)
            },
            create_order() {
                window.location.href =('/create_orders')
            },
            get_gruzootpravitel_list()
            {
                let inp_temp =[];
                axios
                    .post('/get_perevozka_list_front',{
                        offset:this.offset_from_start,
                        limit:this.limit,
                    })
                    .then(({ data }) => (
                            this.countAllTypes=data.tipesCount,
                                console.log('this.countAllTypes!!!'),
                                console.log(this.countAllTypes),
                                data.perevozka.forEach(function(entry) {
                                inp_temp.push({
                                    id:entry.id,
                                    nazvanie:entry.nazvanie,
                                    YR_adres:entry.YR_adres,
                                    kontaktnoe_lico:entry.kontaktnoe_lico,
                                    checked_order:false,
                                    important:entry.important
                                });
                            })
                        ),
                        this.gruzootpravitel_arr=inp_temp

                    );
            },
            dataFromPagination(offset)
            {
                this.offset_from_start=offset
                this.gruzootpravitel_arr=[];
                this.get_gruzootpravitel_list()
            }


        }
    }
</script>
