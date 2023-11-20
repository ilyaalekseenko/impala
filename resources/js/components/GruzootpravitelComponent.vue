<template>
    <div class="container orders_main">
        <div class="row orders_title_div">
            <modal-pogruzka-component ref="modalComponentforAction"
                                      edit_flag=true
                                      :change_one_gruzzotpravitel='change_one_gruzzotpravitel'
                                      :addRowGruzoot='addRowGruzoot'
                                      vid="GruzzotpravitelComponent"
            ></modal-pogruzka-component>
            <div class="col-12 row">
                <div class="col-6 orders_title">
                    Грузоотправитель/грузополучатель
                </div>


                <div class="col-12 coloring_row row">

                    <div class="col-12 under_col_title_main">
                        <input type="checkbox" id="checkbox" v-on:click="check_all()" v-model="checked_all">
                        <span class="under_colored_title head_font" v-on:click="delete_gruzootpravitel()">Удалить</span>
                        <span class="under_colored_title head_font">Переместить</span>
                        <span class="under_colored_title head_font" v-on:click="mix_mark_as_important(gruzootpravitel_arr,'Gruzootpravitel')" >Важное</span>
                        <span class="col add_button_perevozchiki" v-b-modal.modal-xl variant="primary" v-on:click="newModal()">Добавить</span>
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
                                <div class="col-10" v-b-modal.modal-xl variant="primary">{{ gruzootpravitel.forma_id }} {{ gruzootpravitel.nazvanie }}</div>
                            </div>
                            <div class="col-2 orders_title_table" v-b-modal.modal-xl variant="primary">{{ gruzootpravitel.YR_adres }}</div>
                            <div class="col-8 orders_title_table t1" v-b-modal.modal-xl variant="primary">
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
                            <!-- Pagination -->
                            <!--            пагинация          -->
                            <nav class="col d-flex justify-content-end"  aria-label=" Page navigation example">
                                <ul class="pagination">
                                    <li v-if="pag.id!='...'" v-for="pag in pagination_numb" class="page-item pagination_button"
                                        v-bind:class="{ active: pag.id==current_page }"
                                        v-on:click="new_page(pag.id)">
                                        <span class="page-link page_link_int" >{{ pag.id }}</span>
                                    </li>
                                    <li v-else class="page-item">
                                        <span class="page-link" >{{ pag.id }}</span>
                                    </li>
<!--                                    стрелки-->
                                    <li class="page-item pagination_button prev_pag_button page_link_int" v-on:click="prev_page()">
                                        <div v-if="current_page!=1">
                                        <span class="iconify" data-icon="eva:arrow-ios-downward-fill" style="color: #0066fa;" data-width="48" data-height="48" data-rotate="90deg">
                                        </span>
                                        </div>
                                    </li>
                                    <li class="page-item pagination_button next_pag_button page_link_int" v-on:click="next_page()">
                                        <div v-if="last_pagination_number">
                                        <span class="iconify" data-icon="eva:arrow-ios-downward-fill" style="color: #0066fa;" data-width="48" data-height="48" data-rotate="270deg"></span>
                                        </div>
                                    </li>
<!--                                    конец стрелок-->
                                </ul>
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
                offset:20,
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
                order_by:'0'
            }
        },
        created()
        {
            // this.get_type_per_list(this.type_per_list);
        },
        methods: {

            change_one_gruzzotpravitel(id,nazvanie,yr_adres,kontakty,forma_id)
            {

                for( let i = 0; i < this.gruzootpravitel_arr.length; i++ )
                {
                    if(this.gruzootpravitel_arr[i].id==id)
                    {
                        this.gruzootpravitel_arr[i].nazvanie=nazvanie
                        this.gruzootpravitel_arr[i].YR_adres=yr_adres
                        this.gruzootpravitel_arr[i].kontaktnoe_lico=kontakty
                        this.gruzootpravitel_arr[i].forma_id=forma_id
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
                this.$refs.modalComponentforAction.setNewModalStatusFalse()
                this.$refs.modalComponentforAction.get_modal_edit_data(id)
            },
            check_all()
            {
                for( let i = 0; i < this.gruzootpravitel_arr.length; i++ )
                {
                   this.gruzootpravitel_arr[i].checked_order=!this.gruzootpravitel_arr[i].checked_order
                }
            },

            delete_gruzootpravitel()
            {
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
                   .post('/delete_gruzootpravitel',{
                       gruzootpravitels_id:this.delete_arr,
                   })
                this.delete_arr=[]
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
                let offset_from_start=(this.current_page-1)*this.offset

                let inp_temp =[];
                axios
                    .post('/get_gruzootpravitel_list_front',{
                        offset:offset_from_start,
                        limit:this.limit,
                    })
                    .then(({ data }) => (
                            this.pagination_all=data.tipes_count,
                                data.gruzootpravitel.forEach(function(entry) {
                                inp_temp.push({
                                    id:entry.id,
                                    nazvanie:entry.nazvanie,
                                    YR_adres:entry.YR_adres,
                                    kontaktnoe_lico:entry.kontaktnoe_lico,
                                    checked_order:false,
                                    important:entry.important,
                                    forma_id:entry.forma_id
                                });
                            }),
                         this.pagination_counter()
                        ),
                        this.gruzootpravitel_arr=inp_temp

                    );
            },
            newModal()
            {
                this.$refs.modalComponentforAction.newModal()
            },
            addRowGruzoot(id,nazvanie,yr_adres,kontakty,forma_id)
            {
                //метод вызываемый из дочернего компонента
                let objToPush= {};
                objToPush['id'] = id;
                objToPush['nazvanie'] = nazvanie;
                objToPush['YR_adres'] = yr_adres;
                objToPush['kontaktnoe_lico'] = kontakty;
                objToPush['forma_id'] = forma_id;
                this.gruzootpravitel_arr.push(objToPush);

            },
            prev_page()
            {
                if(this.current_page!=1)
                {
                    this.current_page=this.current_page-1;
                    this.new_page(this.current_page)
                }

            },
            next_page()
            {
                this.last_pagination_number=!this.last_pagination_number;
                let g=Math.ceil(this.pagination_all/this.offset);

                if(this.current_page!=g)
                {
                    this.current_page=this.current_page+1;
                    this.new_page(this.current_page)
                }
            },
            new_page(page_id)
            {
                this.pagination_numb=[]
                this.gruzootpravitel_arr=[]
                this.current_page=page_id
                this.get_gruzootpravitel_list();
            },
            pagination_counter()
            {
                if(this.pagination_all!=0) {

                    //формирование отрисовки пагинации
                    let g = Math.ceil(this.pagination_all / this.offset);
                    //проверка на последнюю страничку
                    if(this.current_page==g)
                    {
                        this.last_pagination_number=false
                    }
                    else
                    {
                        this.last_pagination_number=true
                    }
                    //текущая страница первая
                    if (this.current_page == 1) {
                        if (g > 1) {
                            for (let i = 1; i <= 2; i++) {
                                this.pagination_numb.push({'id': i})
                            }
                            this.pagination_numb.push({'id': '...'})
                            this.pagination_numb.push({'id': g})
                        } else {
                            this.pagination_numb.push({'id': g})
                        }
                    }
                    //текущая страница вторая
                    if (this.current_page == 2) {
                        if (g > 2) {
                            for (let i = 1; i <= 3; i++) {
                                this.pagination_numb.push({'id': i})
                            }
                            this.pagination_numb.push({'id': '...'})
                            this.pagination_numb.push({'id': g})
                        } else {
                            for (let i = 1; i <= 2; i++) {
                                this.pagination_numb.push({'id': i})
                            }
                        }
                    }
                    if (this.current_page > 2) {

                        //минусовая
                        if ((this.current_page - 3) == 1) {
                            let page = this.current_page - 3
                            this.pagination_numb.push({'id': '1'})
                        } else {
                            if ((this.current_page - 2) > 1) {
                                this.pagination_numb.push({'id': '1'})
                                this.pagination_numb.push({'id': '...'})
                            }
                        }
                        let page = this.current_page - 2
                        this.pagination_numb.push({'id': page})
                        page = this.current_page - 1
                        this.pagination_numb.push({'id': page})
                        this.pagination_numb.push({'id': this.current_page})
                        //плюсовая
                        if ((this.current_page + 1) <= g) {
                            page = this.current_page + 1
                            this.pagination_numb.push({'id': page})
                        }
                        if ((this.current_page + 2) <= g) {
                            page = this.current_page + 2
                            this.pagination_numb.push({'id': page})
                        }
                        if ((this.current_page + 3) == g) {
                            page = this.current_page + 3
                            this.pagination_numb.push({'id': page})
                        } else {
                            if ((g - this.current_page) > 2) {
                                this.pagination_numb.push({'id': '...'})
                                this.pagination_numb.push({'id': g})
                            }
                        }
                    }
                }
            },
        }
    }
</script>
