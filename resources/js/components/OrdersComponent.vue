<template>
    <div class="container orders_main">
        <div class="row orders_title_div">
            <div class="col-12 row">
                <div class="col-6 orders_title">
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

                        <div class="col-2  color_title_1 coloring_np">
                            <span class="col-12 row">
                            <div class="col-6 coloring_row_text coloring_np head_font">Журнал заявок</div >
                            <div class="col-6 row text-end coloring_np">
                                <div class="col-12 coloring_np">
                                    <span class="coloring_integer">{{ orders_total_numb }}</span>
                                </div>
                            </div>
                            </span>
                        </div>

                        <div class="col-2  color_title_2 coloring_np">
                            <span class="col-12 row">
                            <div class="col-4 coloring_row_text coloring_np head_font">Оценка</div >
                            <div class="col row text-end coloring_np">
                                <div class="col coloring_np">
                                    <span class="coloring_integer head_font">100000</span>
                                    <span class="coloring_integer_green">+1</span>
                                </div>
                            </div>
                            </span>
                        </div>

                    <div class="col-2 color_title_3 coloring_np">
                        <span class="col-12 row">
                        <div class="col-7 coloring_row_text coloring_np head_font">Назначение ставки</div >
                        <div class="col-5 row text-end coloring_np">
                            <div class="col-12 coloring_np ">
                            <span class="coloring_integer head_font">1000</span>
                            </div>
                        </div>
                            </span>
                    </div>
                    <div class="col-2 color_title_4 coloring_np">
                        <span class="col-12 row">
                        <div class="col-4 coloring_row_text coloring_np head_font">В работе</div >
                        <div class="col-8 row text-end coloring_np">
                            <div class="col-12 coloring_np coloring_np">
                                <span class="coloring_integer head_font">100000</span>
                            </div>
                        </div>
                            </span>
                    </div>
                    <div class="col-2 color_title_5 coloring_np">
                        <span class="col-12 row">
                        <div class="col-4 coloring_row_text coloring_np head_font">Контроль</div >
                        <div class="col-8 row text-end coloring_np">
                            <div class="col-12 coloring_np">
                                <span class="coloring_integer head_font">100000</span>
                            </div>
                        </div>
                            </span>
                    </div>
                    <div class="col-2 color_title_6 coloring_np">
                        <span class="col-12 row">
                        <div class="col-4 coloring_row_text coloring_np head_font">Завершён</div >
                        <div class="col-8 row text-end coloring_np">
                            <div class="col-12 coloring_np">
                                <span class="coloring_integer head_font">100000</span>
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
                            <div class="col-2 orders_title_table_main text-start row">
                                <div class="col-2"></div>
                                <div class="col-10 head_font">Номер заявки</div>
                            </div>
                            <div class="col-2 orders_title_table_main head_font">Дата внесения</div>
                            <div class="col-2 orders_title_table_main head_font">Тип перевозки</div>
                            <div class="col-2 orders_title_table_main head_font">Откуда</div>
                            <div class="col-2 orders_title_table_main head_font">Куда</div>
                            <div class="col-2 orders_title_table_main head_font">Заказчик</div>
                        </div>
                        <div v-on:dblclick="go_to_order(order.id)" v-for="(order,key) in orders_list" class="col-12 row no_padding_right border_in_orders" v-bind:class="{ important_back: order.important==1 }">
                        <div class="col-2 orders_title_table text-start row">
                            <input class="col-2 checkbox_orders" type="checkbox" id="checkbox1" v-model="order.checked_order">
                            <div class="col-10">№ {{ order.id }}</div>
                        </div>
                        <div class="col-2 orders_title_table">{{ order.data_vneseniya }}</div>
                        <div class="col-2 orders_title_table t1" v-for="(one_ts,key1) in type_per_list" v-if="one_ts['id']==order.vid_perevozki">{{ one_ts.ts_name }}</div>
                        <div class="col-2 orders_title_table t2" v-if="order.vid_perevozki==null">{{ order.vid_perevozki }}</div>
                        <div class="col-2 orders_title_table">{{ order.adres_pogruzke }}</div>
                        <div class="col-2 orders_title_table">{{ order.adres_vygruski }}</div>
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
        mounted() {
            console.log('Component mounted.')
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
                orders_total_numb:0,
                delete_arr:[],
                type_per_list:[]
            }
        },
        created()
        {
            this.get_orders_list(this.orders_list)
            this.get_type_per_list(this.type_per_list);

        },
        methods: {
            go_to_order(id)
            {
                window.location.href =('/create_orders/'+id)
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
                console.log(this.orders_list)
                let temp_arr=[];
                for( let i = 0; i < this.orders_list.length; i++ )
                {
                        if(this.orders_list[i].checked_order==true)
                        {
                            this.delete_arr.push(this.orders_list[i].id)
                            // this.orders_list.splice(i, 1);
                            // console.log(this.delete_arr)
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
               this.delete_arr=[]
            },
            show_by(int)
            {
                this.offset=int
                this.limit=int
                this.pagination_all=0
                this.current_page=1,
                this.pagination_numb=[],
                this.orders_list=[]
                this.get_orders_list(this.orders_list)
            },
            create_order() {
                window.location.href =('/create_orders')
            },
            get_orders_list(inp)
            {
                let offset_from_start=(this.current_page-1)*this.offset
                // console.log(this.offset)
                axios
                    .post('/get_orders_list_new',{
                        offset:offset_from_start,
                        limit:this.limit
                    })
                    .then(({ data }) => (
                        console.log(data),
                            this.pagination_all=data.tipes_count,
                                this.orders_total_numb=data.tipes_count,
                                data.list_colored.forEach(function(entry) {
                                    inp.push({
                                        id:entry.id,
                                        data_vneseniya:entry.data_vneseniya,
                                        vid_perevozki:entry.vid_perevozki,
                                        adres_pogruzke:entry.adres_pogruzke,
                                        adres_vygruski:entry.adres_vygruski,
                                        kompaniya_zakazchik:entry.kompaniya_zakazchik,
                                        checked_order:false,
                                        important:entry.important
                                    });
                                }),
                                this.pagination_counter()
                        )
                    );
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
                console.log(this.current_page)

                this.pagination_numb=[]
                this.orders_list=[]
                this.current_page=page_id
                this.get_orders_list(this.orders_list);
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
