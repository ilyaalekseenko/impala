<template>
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
            <!--стрелки-->
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
            <!--конец стрелок-->
        </ul>
    </nav>
</template>

<script>
//При вызове компонента значение countAllTypes отслеживает общее количество записей
//Обновить компонент уставновив пагинацию на первую страничку можно методом
//this.$refs.PaginationComponent.setToDefaultState() не забудь прописать ref="PaginationComponent" в компоненте
//возвращает результат работы пагинации (значение offset) в методе dataFromPagination в родителе
//countLimit следит за изменением количества одновременно выводимых записей, по умолчанию 20
//в родителе ставить limit 0, затем в mounted то значение которое надо
//все строки обязательны
//<pagination-component
//:countAllTypes="countAllTypes"
//:dataFromPagination="dataFromPagination"
//ref="PaginationComponent"
//></pagination-component>
    export default {
        props: ['countAllTypes','dataFromPagination','countLimit'],
        data(){
            return {
                //текущая страничка
                current_page: 1,
                //массив с кнопками пагинации 1 2 3 и т.д.
                pagination_numb:[],
                //последняя ли страничка
                last_pagination_number:false,
                //количество всех записей
                countAllTypesLocal:0,
                //по сколько записей выводить за один раз
                limit:20
            }
        },
        watch: {
            // отслеживаем изменения значений счётчика общего количества записей в родителе
            countAllTypes: function () {
                this.pagination_numb=[]
                this.countAllTypesLocal=this.countAllTypes
                // console.log(this.countAllTypesLocal)
                this.paginationCounter()
            },
            // отслеживаем изменения значений количества выводимых записей
            countLimit: function () {
                 console.log('this.countLimit')
                 console.log(this.countLimit)
                this.limit=this.countLimit
                this.countAllTypesLocal=this.countAllTypes
                this.new_page(1)
            },
        },
        created() {
        this.paginationCounter();
            },
        methods: {

            //перерисовка самой пагинации
            paginationCounter()
            {
                // console.log('countAllTypesLocal')
                // console.log(this.countAllTypesLocal)
                //если всех записей не ноль
                if(this.countAllTypesLocal!=0) {

                    //получаем количество всех страниц ( округляем в большую сторону)
                    let g = Math.ceil(this.countAllTypesLocal / this.limit);
                    // console.log('g')
                    // console.log(g)
                    // console.log('limit')
                    // console.log(this.limit)
                    //проверка на последнюю страничку
                            if(this.current_page==g)
                            {
                                this.last_pagination_number=false
                            }
                            else
                            {
                                this.last_pagination_number=true
                            }
                    //если текущая страница первая
                    if (this.current_page == 1) {
                        if (g > 1) {
                            for (let i = 1; i <= 2; i++) {
                                this.pagination_numb.push({'id': i})
                            }
                            this.pagination_numb.push({'id': '...'})
                            this.pagination_numb.push({'id': g})
                        }
                        // else {
                        //     this.pagination_numb.push({'id': g})
                        // }
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
                    //если текущая страница больше второй
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
                // this.last_pagination_number=!this.last_pagination_number;
                let g = Math.ceil(this.countAllTypesLocal / this.limit);

                if(this.current_page!=g)
                {
                    this.current_page=this.current_page+1;
                    this.new_page(this.current_page)
                }
            },
            new_page(page_id)
            {
                this.pagination_numb=[]
                this.current_page=page_id
                //перерисуем пагинацию
                this.paginationCounter()
                //вызовем метод в родительском компоненте
                //посчитаем сколько отступа передавать в родитель
                let offsetToparent=(this.current_page-1)*this.limit
                this.dataFromPagination(offsetToparent)
            },
            //установим пагинацию в начальное состояние
            //вызов из родителя
            //this.$refs.PaginationComponent.setToDefaultState() не забудь прописать ref="PaginationComponent" в компоненте
                setToDefaultState()
            {
                //перерисуем пагинацию
                this.new_page(1)
            }


        }
    }
</script>
