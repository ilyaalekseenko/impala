<template>
    <div class="container">
        <div class="row justify-content-center">

            <table class="table">
                <thead>
                <tr>
                    <th scope="col" v-on:click="sort_by('id')">id</th>
                    <th scope="col" v-on:click="sort_by('email')">Почта</th>
                    <th scope="col" v-on:click="sort_by('last_name')">Фамилия</th>
                    <th scope="col" v-on:click="sort_by('first_name')">Имя</th>
                    <th scope="col" v-on:click="sort_by('patronymic')">Отчество</th>
                    <th scope="col" v-on:click="sort_by('dolznost')">Должность</th>
                    <th scope="col" v-on:click="sort_by('telefon')">Телефон</th>
                    <th scope="col" v-on:click="sort_by('data_rozdenia')">Дата рождения</th>
                    <th scope="col" v-on:click="sort_by('status')">Статус</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(user,index) in users_list">
                    <th scope="row">{{ user.id }}</th>
                    <td>{{ user.email }}</td>
                    <td>{{ user.last_name }}</td>
                    <td>{{ user.first_name }}</td>
                    <td>{{ user.patronymic }}</td>
                    <td>{{ user.dolznost }}</td>
                    <td>{{ user.telefon }}</td>
                    <td>{{ user.data_rozdenia }}</td>
                    <td>
                        <select @blur="change_permission(user.id,user.perm)" class="cr_ord_inp_n_1" v-model="user.perm">
                            <option v-for="(elem,key) in roles_list" v-bind:value=elem.slug  class="sel_cust">{{ elem.name }}</option>
                        </select>
                    </td>
                </tr>
                </tbody>
            </table>

<!--            пагинация          -->
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item pagination_button" v-on:click="prev_page()"><span class="page-link" >Previous</span>
                    </li>
                    <li v-if="pag.id!='...'" v-for="pag in pagination_numb" class="page-item pagination_button"
                        v-bind:class="{ active: pag.id==current_page }"
                        v-on:click="new_page(pag.id)">
                        <span class="page-link" >{{ pag.id }}</span>
                    </li>
                    <li v-else class="page-item">
                        <span class="page-link" >{{ pag.id }}</span>
                    </li>
                    <li class="page-item pagination_button" v-on:click="next_page()"><span class="page-link" href="javascript:;">Next</span>
                    </li>
                </ul>
            </nav>
        </div>

    </div>
</template>

<script>
import { VueTelInput } from 'vue-tel-input'

export default {
    data() {
        return {
            users_list:[],
            //текущая страница
            current_page:1,
            //по сколько записей на странице выбирать результат
            offset:12,
            // ниже сколько всего записей в БД с такими параметрами
            pagination_all:0,
            //ниже массив в который добавляем страницы пагинации 1 2 3 и т.д.
            pagination_numb:[],
            isModalVisible: false,
            roles_list:[],
            sort_by_item:'',
            asc_desc:''
        };
    },
    mounted() {
        this.get_users_list(this.users_list);
        this.get_roles(this.roles_list);
    },
    methods: {
        change_permission(user_id,user_perm)
        {
            axios
                .post('/change_permission',{
                    user_id:user_id,
                    user_perm:user_perm,
                })
        },
        sort_by(sort_item)
        {
            this.current_page=1
            this.users_list=[];
            this.pagination_all=0;
            this.pagination_numb=[]
            if(this.asc_desc=='')
            {
                this.asc_desc='desc'
            }
            else
            {
                if(this.asc_desc=='desc')
                {
                    this.asc_desc='asc'
                }
                else
                {
                    this.asc_desc='desc'
                }
                if(this.sort_by_item!==sort_item)
                {
                    this.asc_desc='asc'
                }
            }

            this.sort_by_item=sort_item;
            this.get_users_list(this.users_list)
        },
        get_users_list(inp)
        {
            let offset_from_start=(this.current_page-1)*this.offset
            axios
                .post('/get_users_list',{
                    offset:offset_from_start,
                    sort_by_item:this.sort_by_item,
                    asc_desc:this.asc_desc,
                })
                .then(({ data }) => (
                        this.pagination_all=data.tipes_count,
                        data.list_users.forEach(function(entry) {
                            inp.push({
                                id:entry.id,
                                name:entry.name,
                                email:entry.email,
                                perm:entry.perm,
                                first_name:entry.first_name,
                                last_name:entry.last_name,
                                patronymic:entry.patronymic,
                                dolznost:entry.dolznost,
                                telefon:entry.telefon,
                                data_rozdenia:entry.data_rozdenia,
                            });
                        }),
                        this.pagination_counter()
                    )
                );
        },
        get_roles(inp)
        {
            axios
                .post('/get_roles',{
                })
                .then(({ data }) => (
                        data.roles_list.forEach(function(entry) {
                            inp.push({
                                id:entry.id,
                                name:entry.name,
                                slug:entry.slug
                            });
                        })
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
            this.users_list=[]
            this.current_page=page_id
            this.get_users_list(this.users_list);
        },
        pagination_counter()
        {
            if(this.pagination_all!=0) {
                //формирование отрисовки пагинации
                let g = Math.ceil(this.pagination_all / this.offset);

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
