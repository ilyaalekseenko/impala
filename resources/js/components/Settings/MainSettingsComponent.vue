<template>
    <div class="container">
        <div class="row">
       <div class="col-2">
        <aside>
            <ul class="nav nav--vertical">
                <li v-if="is_admin_var" class="dropmenu"><a href="#">Админка</a>
                    <ul class="level1">
<!--                        <li v-on:click="nav_menu_show(1)"><a href="#">Шаблоны документов</a>-->
                                                    <li class="dropmenu"><a href="#">Пользователи</a>
                                                        <ul class="level2">
                                                            <li v-on:click="nav_menu_show(4)"><a href="#">Добавление</a></li>
                                                            <li v-on:click="nav_menu_show(5)"><a href="#">Роли</a></li>
                                                        </ul>
                                                    </li>
<!--                                                    <li><a href="#">ipsum</a></li>-->
<!--                                                    <li><a href="#">dolor</a></li>-->
<!--                                                    <li><a href="#">sit</a>-->
<!--                        </li>-->
                    </ul>
                </li>
                <li class="dropmenu"><a href="#">Документы</a>
                    <ul class="level1">
                        <li v-on:click="nav_menu_show(1)"><a href="#">Шаблоны документов</a>
<!--                        <li class="dropmenu"><a href="#">Trades</a>-->
<!--                            <ul class="level2">-->
<!--                                <li><a href="#">lorem iklor</a></li>-->
<!--                                <li><a href="#">ipsum</a></li>-->
<!--                                <li><a href="#">dolor</a></li>-->
<!--                                <li><a href="#">sit amit</a></li>-->
<!--                            </ul>-->
<!--                        </li>-->
<!--                        <li><a href="#">ipsum</a></li>-->
<!--                        <li><a href="#">dolor</a></li>-->
<!--                        <li><a href="#">sit</a>-->
                        </li>
                    </ul>
                </li>
                <li class="dropmenu"><a href="#">Перевозки</a>
                    <ul class="level1">
                        <li v-on:click="nav_menu_show(2)"><a href="#">Настройки</a>
<!--                        <li class="dropmenu"><a href="#">Trades</a>-->
<!--                            <ul class="level2">-->
<!--                                <li><a href="#">lorem iklor</a></li>-->
<!--                                <li><a href="#">ipsum</a></li>-->
<!--                                <li><a href="#">dolor</a></li>-->
<!--                                <li><a href="#">sit amit</a></li>-->
<!--                            </ul>-->
<!--                        </li>-->
<!--                        <li><a href="#">ipsum</a>-->
                    </li>
<!--                        <li><a href="#">dolor</a></li>-->
<!--                        <li><a href="#">sit</a></li>-->
                    </ul>
                </li>
                <li class="dropmenu"><a href="#">Грузоотправитель</a>
                    <ul class="level1">
                        <li v-on:click="nav_menu_show(3)"><a href="#">Форма</a>
<!--                        <li class="dropmenu"><a href="#">Trades</a>-->
<!--                            <ul class="level2">-->
<!--                                <li><a href="#">lorem iklor</a></li>-->
<!--                                <li><a href="#">ipsum</a></li>-->
<!--                                <li><a href="#">dolor</a></li>-->
<!--                                <li><a href="#">sit amit</a></li>-->
<!--                            </ul>-->
<!--                        </li>-->
<!--                        <li><a href="#">ipsum</a></li>-->
<!--                        <li><a href="#">dolor</a></li>-->
<!--                        <li><a href="#">sit</a>-->
                        </li>
                    </ul>
                </li>
            </ul>
        </aside>
       </div>
        <div class="col-10" >
        <docs-settings-component v-if="nav_menu_show_var==1"></docs-settings-component>
        <perevozka-settings-component v-if="nav_menu_show_var==2"></perevozka-settings-component>
        <forma-gruzootpravitel-settings-component v-if="nav_menu_show_var==3"></forma-gruzootpravitel-settings-component>
        <add-users-settings-component v-if="nav_menu_show_var==4"></add-users-settings-component>
        <users-list-component v-if="nav_menu_show_var==5"></users-list-component>
        </div>

        </div>
    </div>
</template>

<script>

export default {
        mounted() {
            this.is_admin_settings()
        },
        data() {
            return {

                nav_menu_show_var:'',
                is_admin_var:''
            }
        },
        methods: {

            nav_menu_show(numb)
            {
               this.nav_menu_show_var=numb

            },
            is_admin_settings()
            {
                axios
                    .post('/is_admin_settings',{
                    })
                    .then(response => {
                        if (response.data.status === 'success') {
                            this.is_admin_var=true
                        }
                        else
                        {
                            this.is_admin_var=false
                        }
                    })
            }
        }

    }
</script>
