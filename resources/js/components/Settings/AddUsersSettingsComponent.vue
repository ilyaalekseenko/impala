<template>
    <div class="container">
        <div class="col-12 mail_header">Добавление нового пользователя</div>
        <div class="justify-content-center">
            <div v-if="alert" class="alert alert-danger" role="alert">
                <ul id="error_list">
                    <li v-for="item in alert_arr" >
                        {{ item }}
                    </li>
                </ul>
            </div>
            <div v-if="success" class="alert alert-success" role="alert">
                Пользователь успешно добавлен
            </div>
            <div class="col-6">Имя</div>
            <input class="col-6" v-model="first_name"  />
            <div class="col-6">Фамилия</div>
            <input class="col-6" v-model="last_name"  />
            <div class="col-6">Отчество</div>
            <input class="col-6" v-model="patronymic"  />
            <div class="col-6">Должность</div>
            <input class="col-6" v-model="dolznost"  />
            <div class="col-6">Почта</div>
            <input class="col-6" type="email" v-model="email"  />
            <div class="col-6">Номер телефона</div>

            <phone-component  :initialPhoneNumber="telefon" :setPhoneNumber="setPhoneNumber"></phone-component>

            <div class="col-6">Роль</div>
            <select class="cr_ord_inp_n_1" v-model="role">
                <option v-for="(elem,key) in roles_list" v-bind:value=elem.id  class="sel_cust">{{ elem.name }}</option>
            </select>
            <div class="col-6">Дата рождения</div>
            <input class="col-6" v-model="data_rozdenia" @click="openDB0"/>
            <date-picker ref="datepicker0" v-model:value="data_rozdenia" valueType="format" format="DD.MM.YYYY" v-model:open="openDP" ></date-picker>

            <div class="col-3">
            <button type="button" class=" btn btn-primary add_new_user_button" v-on:click="add_new_user()">Добавить нового пользователя</button>
            </div>
            </div>
    </div>
</template>

<script>
import moment from 'moment'
import DatePicker from 'vue-datepicker-next';
import 'vue-datepicker-next/index.css';
import 'vue-datepicker-next/locale/ru';

    export default {
        components: {
            DatePicker
        },
        mounted() {
            this.get_roles(this.roles_list);
        },
        data() {
            return {
                name:'user',
                email:'',
                alert_arr:[],
                alert:false,
                success:'',
                first_name:'',
                last_name:'',
                patronymic:'',
                dolznost:'',
                telefon:'',
                data_rozdenia:'',
                openDP:false,
                role:3,
                roles_list:[],
                lastCursorPosition: 0,

            }
        },
      methods: {
            setPhoneNumber(newPhoneNumber)
            {
                this.telefon=newPhoneNumber
            },
            add_new_user()
            {
                this.alert_arr=[];
                this.alert=false;
                this.success=false;
                if(this.name=="")
                {
                    this.alert=true;
                    this.alert_arr.push('Заполните поле "Никнейм"');
                }
                if(this.email=="")
                {
                    this.alert=true;
                    this.alert_arr.push('Заполните поле "Почта"');
                }
                if(this.alert==false)
                {
                    axios.post( '/add_user',
                        {
                            name:this.name,
                            email:this.email,
                            first_name:this.first_name,
                            last_name:this.last_name,
                            patronymic:this.patronymic,
                            dolznost:this.dolznost,
                            telefon:this.telefon,
                            data_rozdenia:this.data_rozdenia,
                            role:this.role,
                                                    }
                    )
                        .then(response => {
                            if (response.data.status === 'success') {
                                    this.success=true,
                                    this.email='',
                                    this.first_name='',
                                    this.last_name='',
                                    this.patronymic='',
                                    this.dolznost='',
                                    this.telefon='',
                                    this.data_rozdenia='',
                                    this.role=3,
                                    this.openDP=false

                            }
                        })
                        .catch((error) => {
                            this.add_to_errors(error.response.data.errors);
                        })
                }
            },
            add_to_errors(inp_errors)
            {
                this.alert=true;
                let temp_arr=[];
                for (const [key, value] of Object.entries(inp_errors)) {
                    value.forEach(function(number) {
                        temp_arr.push(number)
                    });
                }
                this.alert_arr=temp_arr;
            },
            openDB0()
            {
                this.openDP=true
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
        },


    }
</script>
