<template>
    <div class="container orders_main">
        <div class="row orders_title_div">
            <div class="col-12 row">
                <div class="col-6 orders_title" >
                    Заявки
                </div>
                <div class="col-6 d-flex justify-content-end">
                    <div class="create_orders_button" v-on:click="create_order()">
                        <span class="iconify button_menu_block" data-icon="akar-icons:circle-plus" style="color: white;" data-width="24" data-height="24"></span>
                        <span class="button_menu_block" >Создать заявку</span>
                    </div>
                </div>

                <div class="col-12 coloring_row row">
                    <div class="col-12 row coloring_row_1">

                        <div class="col-2  color_title_1 coloring_np" v-on:click="update_header_underscore(0,'zurnal_zaiavok')" :class="{ header_underscore_0: header_underscore_list_class[0] }">
                            <span class="col-12 row">
                            <div class="col-6 coloring_row_text coloring_np head_font">Журнал заявок</div >
                            <div class="col-6 row text-end coloring_np">
                                <div class="col-12 coloring_np">
                                    <span class="coloring_integer">{{ zurnal_zaiavok }}</span>
                                </div>
                            </div>
                            </span>
                        </div>

                        <div class="col-2  color_title_2 coloring_np" v-on:click="update_header_underscore(1,'ocenka')" :class="{ header_underscore_1: header_underscore_list_class[1] }">
                            <span class="col-12 row">
                            <div class="col-4 coloring_row_text coloring_np head_font">Оценка</div >
                            <div class="col row text-end coloring_np">
                                <div class="col coloring_np">
                                    <span class="coloring_integer head_font">{{ ocenka }}</span>
                                    <span class="coloring_integer_green" v-if="ocenka_counter!==0">+{{ ocenka_counter }}</span>
                                </div>
                            </div>
                            </span>
                        </div>

                    <div class="col-2 color_title_3 coloring_np" v-show="checkRolePermissionMixin([1])" v-on:click="update_header_underscore(2,'naznachenie_stavki')" :class="{ header_underscore_2: header_underscore_list_class[2] }">
                        <span class="col-12 row">
                        <div class="col-7 coloring_row_text coloring_np head_font">Назначение ставки</div >
                        <div class="col-5 row text-end coloring_np">
                            <div class="col-12 coloring_np ">
                            <span class="coloring_integer head_font">{{ naznachenie_stavki }}</span>
                                <span class="coloring_integer_green" v-if="naznachenie_stavki_unread_count!==0">+{{ naznachenie_stavki_unread_count }}</span>
                            </div>
                        </div>
                            </span>
                    </div>
                    <div class="col-2 color_title_4 coloring_np" v-on:click="update_header_underscore(3,'v_rabote')" :class="{ header_underscore_3: header_underscore_list_class[3] }">
                        <span class="col-12 row">
                        <div class="col-4 coloring_row_text coloring_np head_font">В работе</div >
                        <div class="col-8 row text-end coloring_np">
                            <div class="col-12 coloring_np coloring_np">
                                <span class="coloring_integer head_font">{{ v_rabote }}</span>
                                    <span class="coloring_integer_green" v-if="v_rabote_unread_count!==0">+{{ v_rabote_unread_count }}</span>
                            </div>
                        </div>
                            </span>
                    </div>
                    <div class="col-2 color_title_5 coloring_np" v-on:click="update_header_underscore(4)" :class="{ header_underscore_4: header_underscore_list_class[4] }">
                        <span class="col-12 row">
                        <div class="col-4 coloring_row_text coloring_np head_font">Контроль</div >
                        <div class="col-8 row text-end coloring_np">
                            <div class="col-12 coloring_np">
                                <span class="coloring_integer head_font">0</span>
                            </div>
                        </div>
                            </span>
                    </div>
                    <div class="col-2 color_title_6 coloring_np" v-on:click="update_header_underscore(5)" :class="{ header_underscore_5: header_underscore_list_class[5] }">
                        <span class="col-12 row">
                        <div class="col-4 coloring_row_text coloring_np head_font">Завершён</div >
                        <div class="col-8 row text-end coloring_np">
                            <div class="col-12 coloring_np">
                                <span class="coloring_integer head_font">0</span>
                            </div>
                        </div>
                            </span>
                    </div>

                    </div>

                    <div class="col-12 under_col_title_main">
                        <input type="checkbox" id="checkbox" v-on:click="check_all()" v-model="checked_all">
                        <span class="under_colored_title head_font">Объединить</span>
                        <span class="under_colored_title head_font" v-on:click="delete_orders()">Удалить</span>
                        <span class="under_colored_title head_font">Переместить</span>
                        <span class="under_colored_title head_font" v-on:click="mark_as_important()" >Важное</span>
                    </div>
                    <div class="col-12 row  table_orders_column_settings">
                        <div class="col-12 row no_padding_right border_in_orders">
                            <div class="col-1 orders_title_table_main text-start row">
                                <div class="col-2"></div>
                                <div class="col-10 head_font">Номер заявки</div>
                            </div>
                            <div class="col-1 orders_title_table_main head_font">Дата внесения</div>
                            <div class="col-1 orders_title_table_main head_font">Статус</div>
                            <div class="col-2 orders_title_table_main head_font">Тип перевозки</div>
                            <div class="col-2 orders_title_table_main head_font">Откуда</div>
                            <div class="col-2 orders_title_table_main head_font">Куда</div>
                            <div class="col-2 orders_title_table_main head_font">Заказчик</div>
                        </div>
                        <div v-on:dblclick="go_to_order(order.id)" v-for="(order,key) in orders_list" class="col-12 row no_padding_right border_in_orders" v-bind:class="{ important_back: order.important==1 }">
                        <div class="col-1 orders_title_table text-start row">
                            <input class="col-2 checkbox_orders" type="checkbox" id="checkbox1" v-model="order.checked_order">
                            <div class="col-10">{{ order.id }}</div>
                        </div>
                        <div class="col-1 orders_title_table">{{ order.data_vneseniya }}</div>
                            <div class="col-1 orders_title_table t2" >{{ order.status }}</div>
                            <div class="col-2 orders_title_table t1" v-for="(one_ts,key1) in type_per_list" v-if="one_ts['id']==order.vid_perevozki">{{ one_ts.ts_name }}</div>
                        <div class="col-2 orders_title_table t2" v-if="order.vid_perevozki==null">{{ order.vid_perevozki }}</div>
                            <div class="col-2 orders_title_table" v-for="(gruz,key1) in gruzootpravitel_arr" v-if="gruz['id']==order.adres_pogruzke">{{ gruz.nazvanie }}</div>
                        <div class="col-2 orders_title_table" v-for="(gruz,key1) in gruzootpravitel_arr" v-if="gruz['id']==order.adres_vygruski">{{ gruz.nazvanie }}</div>
                        <div class="col-2 orders_title_table">{{ order.kompaniya_zakazchik }}</div>
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
            this.header_counter_orders()
            this.role=this.auth_user['role_perm']['role']
            window.Echo.private('logist')
                .listen('UpdateMainLogistEvent',(e) => {
                    console.log(e.unreadHeaderArr.naznachenieStavki)
                    //если номер id пришедшего обновления совпадает с номером пользователя
                    if(e.logistId==this.auth_user.id)
                    {
                        //общая белая шапка
                        this.zurnal_zaiavok=e.mainHeaderArr.all
                        this.ocenka=e.mainHeaderArr.ocenka
                        this.naznachenie_stavki=e.mainHeaderArr.naznachenieStavki
                        this.v_rabote=e.mainHeaderArr.vRabote
                        //зелёная оповещения
                        this.ocenka_counter=e.unreadHeaderArr.ocenka
                        this.naznachenie_stavki_unread_count=e.unreadHeaderArr.naznachenieStavki
                        this.v_rabote_unread_count=e.unreadHeaderArr.vRabote

                    }
                }),
                window.Echo.private('delete-order-channel')
                    .listen('DeleteOrderEvent',(e) => {
                        let temp_arr=[];
                        for( let i = 0; i < this.orders_list.length; i++ )
                        {
                            let flag =false;
                            for( let j = 0; j < e.order_id.length; j++ )
                            {
                                if(this.orders_list[i].id==e.order_id[j])
                                {
                                   flag=true
                                }
                            }
                            if(flag==false)
                            {
                                temp_arr.push(this.orders_list[i])
                            }
                        }
                        this.orders_list=temp_arr
                        this.header_counter_orders()
                    }),
            this.permissions=this.auth_user['role_perm']['permissions']
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
                naznachenie_stavki:0,
                role:0,
                naznachenie_stavki_unread_count:0,
                v_rabote:0,
                v_rabote_unread_count:0,
                permissions:[],
                columnName:'zurnal_zaiavok',
                offset_from_start:0,
                countAllTypes:0
            }
        },
        created()
        {
            //проверяем логист ли это с закладки оценка, нажавший кнопку утверждение
            if(localStorage.getItem('logist_ut_flag')==1)
            {
                this.order_by=1
                localStorage.setItem('logist_ut_flag',0)
                this.header_underscore_list_class=[false,false,false,false,false,false]
                this.header_underscore_list_class[this.order_by]=true
            }
            this.getOrderlist(this.orders_list)
            this.get_type_per_list(this.type_per_list);
        },
        methods: {

            go_to_order(id)
            {
                axios
                    .post('/check_if_order_isset',{
                        id:id,
                    })
                    .then(response => {
                        if(response.data.data=='isset')
                        {
                            //если закладка в работе то редирект на вид grade
                            if(this.order_by==3)
                            {
                                window.location.href =('/grade/'+id)
                            }
                            else
                            {
                                window.location.href =('/create_orders/'+id)
                            }

                        }
                        else
                        {
                            alert('Заявка удалена')
                        }

                    })
              //  window.location.href =('/create_orders/'+id)
            },
            update_header_underscore(numb,columnName)
            {
                this.columnName=columnName
                this.order_by=numb
                this.header_underscore_list_class=[false,false,false,false,false,false]
                this.header_underscore_list_class[numb]=true
                this.orders_list=[];
                this.checked_all=false
                //установим пагинацию в нулевое состояние
                this.$refs.PaginationComponent.setToDefaultState()
            },
            get_gruzootpravitel_list()
            {
                let inp_temp =[];
                axios
                    .post('/get_gruzootpravitel_list',{
                    })
                    .then(({ data }) => (
                            data.gruzootpravitel.forEach(function(entry) {
                                inp_temp.push({
                                    id:entry.id,
                                    nazvanie:entry.nazvanie
                                });
                            })
                        ),
                        this.gruzootpravitel_arr=inp_temp

                    );
            },
            get_type_per_list(inp)
            {
                axios
                    .post('/get_type_per_list',{
                    })
                    .then(({ data }) => (
                            data.ts.forEach(function(entry) {
                                inp.push({
                                    id:entry.id,
                                    ts_name:entry.ts_name
                                });
                            })
                        )
                    );
            },
            check_all()
            {
                for( let i = 0; i < this.orders_list.length; i++ )
                {
                   this.orders_list[i].checked_order=!this.orders_list[i].checked_order
                }
            },
            mark_as_important()
            {
                this.checked_all=false
                for( let i = 0; i < this.orders_list.length; i++ )
                {
                    if(this.orders_list[i].checked_order==true)
                    {
                        this.orders_list[i].checked_order=false
                        if(this.orders_list[i].important==1)
                        {
                            this.orders_list[i].important=null
                        }
                        else
                        {
                            this.orders_list[i].important=1
                        }
                        this.delete_arr.push(this.orders_list[i].id)
                    }
                }
                //перерисуем массив
                let imp_arr=[];
                let not_imp_arr=[];
                for( let i = 0; i < this.orders_list.length; i++ )
                {
                   if(this.orders_list[i].important==1)
                   {
                       imp_arr.push(this.orders_list[i])
                   }
                   else
                   {
                       not_imp_arr.push(this.orders_list[i])
                   }
                }
                this.orders_list=[];
                for( let i = 0; i < imp_arr.length; i++ )
                {
                    this.orders_list.push(imp_arr[i])
                }
                for( let i = 0; i < not_imp_arr.length; i++ )
                {
                    this.orders_list.push(not_imp_arr[i])
                }

                axios
                    .post('/mark_as_important',{
                        orders_id:this.delete_arr,
                    })
                this.delete_arr=[]
            },

            delete_orders()
            {
                let temp_arr=[];
                for( let i = 0; i < this.orders_list.length; i++ )
                {
                        if(this.orders_list[i].checked_order==true)
                        {
                            this.delete_arr.push(this.orders_list[i].id)
                        }
                        else
                        {
                            temp_arr.push(this.orders_list[i])
                        }
                }
                this.orders_list=temp_arr
               axios
                   .post('/delete_orders',{
                       orders_id:this.delete_arr,
                   })
                   .then(({ data }) => (
                       this.delete_arr=[],
                       this.header_counter_orders()
                       )
                   );

            },
            show_by(int)
            {
                this.limit=int
            },
            create_order() {
             //  this.countAllTypes=Number(this.countAllTypes)+Number(20);
             window.location.href =('/create_orders')
            },
            header_counter_orders()
            {
                axios
                    .post('/header_counter_orders',{
                    })
                    .then(({ data }) => (
                        //общая белая шапка
                this.zurnal_zaiavok=data.mainHeaderArr.all,
                this.ocenka=data.mainHeaderArr.ocenka,
                this.naznachenie_stavki=data.mainHeaderArr.naznachenieStavki,
                this.v_rabote=data.mainHeaderArr.vRabote,
                //зелёная оповещения
                this.ocenka_counter=data.unreadHeaderArr.ocenka,
                this.naznachenie_stavki_unread_count=data.unreadHeaderArr.naznachenieStavki,
                this.v_rabote_unread_count=data.unreadHeaderArr.vRabote
                        )
                    );
            },
            getOrderlist(inp)
            {
                axios
                    .post('/getOrderlist',{
                         columnName:this.columnName,
                         offset:this.offset_from_start,
                         limit:this.limit,
                        // order_by:this.order_by
                    })
                    .then(({ data }) => (
                            this.countAllTypes=data.tipesCount,
                                data.list_colored.forEach(function(entry) {
                                    inp.push({
                                        id:entry.id,
                                        data_vneseniya:entry.data_vneseniya,
                                        status:entry.status,
                                        vid_perevozki:entry.vid_perevozki,
                                        adres_pogruzke:entry.adres_pogruzke,
                                        adres_vygruski:entry.adres_vygruski,
                                        kompaniya_zakazchik:entry.kompaniya_zakazchik,
                                        checked_order:false,
                                        important:entry.important
                                    });
                                })
                                // this.pagination_counter()
                        )
                    );
            },
            dataFromPagination(offset)
            {
                this.offset_from_start=offset
                this.orders_list=[];
                this.getOrderlist(this.orders_list)
            }

        }
    }
</script>
