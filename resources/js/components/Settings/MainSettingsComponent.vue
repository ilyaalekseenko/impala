<template>
    <div class="container">
        <div class="row">
       <div class="col-2">
        <aside>
            <ul class="nav nav--vertical" v-click-outside="focus_out_from_li">
                <li v-if="is_admin_var" class="dropmenu" v-on:click="set_menu"><a href="#">Админка</a>
                    <ul class="level1" >
                                                    <li class="dropmenu"><a href="#">Пользователи</a>
                                                        <ul class="level2">
                                                            <li v-on:click="nav_menu_show(4)"><a href="#">Добавление</a></li>
                                                            <li v-on:click="nav_menu_show(5)"><a href="#">Список пользователей</a></li>
                                                        </ul>
                                                    </li>
                    </ul>
                </li>

                <li class="dropmenu" v-on:click="set_menu"><a href="#">Документы</a>
                    <ul class="level1">
                        <li v-on:click="nav_menu_show(1)"><a href="#">Шаблоны документов</a>
<!--                            пустой ul обязателен-->
                            <ul class="level2">
                            </ul>
                        </li>
                    </ul>
                </li>

<!--                <li class="dropmenu" v-on:click="set_menu"><a href="#">Перевозки</a>-->
<!--                    <ul class="level1">-->
<!--                        <li v-on:click="nav_menu_show(2)"><a href="#">Настройки</a>-->
<!--                        <li class="dropmenu"><a href="#">Trades</a>-->
<!--                            <ul class="level2">-->
<!--                                <li><a href="#">lorem iklor</a></li>-->
<!--                                <li><a href="#">ipsum</a></li>-->
<!--                                <li><a href="#">dolor</a></li>-->
<!--                                <li><a href="#">sit amit</a></li>-->
<!--                            </ul>-->
<!--                        </li>-->
<!--                        <li><a href="#">ipsum</a>-->
<!--                    </li>-->
<!--                        <li><a href="#">dolor</a></li>-->
<!--                        <li><a href="#">sit</a></li>-->
<!--                    </ul>-->
<!--                </li>-->
<!--                <li class="dropmenu" v-on:click="set_menu"><a href="#">Грузоотправитель</a>-->
<!--                    <ul class="level1">-->
<!--                        <li v-on:click="nav_menu_show(3)"><a href="#">Список</a>-->
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
<!--                        </li>-->
<!--                    </ul>-->
<!--                </li>-->
                <li class="dropmenu" v-on:click="set_menu"><a href="#">Справочники</a>
                    <ul class="level1">
                        <li v-on:click="nav_menu_show(6)"><a href="#">Виды ТС</a></li>
                        <li v-on:click="nav_menu_show(7)"><a href="#">Водители</a></li>
                        <li v-on:click="nav_menu_show(8)"><a href="#">Номера ТС</a></li>
                        <li v-on:click="nav_menu_show(9)"><a href="#">Номера ПП</a></li>
                    </ul>
                </li>
            </ul>
        </aside>
       </div>
            <div class="col-10 ">
        <docs-settings-component v-if="nav_menu_show_var==1"></docs-settings-component>
        <perevozka-settings-component v-if="nav_menu_show_var==2"></perevozka-settings-component>
        <forma-gruzootpravitel-settings-component v-if="nav_menu_show_var==3"></forma-gruzootpravitel-settings-component>
        <add-users-settings-component v-if="nav_menu_show_var==4"></add-users-settings-component>
        <users-list-component v-if="nav_menu_show_var==5"></users-list-component>
        <vid-t-s-component v-if="nav_menu_show_var==6"></vid-t-s-component>
        <voditeli-component v-if="nav_menu_show_var==7"></voditeli-component>
        <t-s-modal-component v-if="nav_menu_show_var==8"></t-s-modal-component>
        <p-p-component v-if="nav_menu_show_var==9"></p-p-component>
        </div>

        </div>
    </div>
</template>

<script>
import vClickOutside from 'v-click-outside'

export default {
        mounted() {
            this.is_admin_settings()
        },
        data() {
            return {

                nav_menu_show_var:'',
                is_admin_var:'',
                menu1:false,

                test_var:'1',
                postFontSize:0
            }
        },
        methods: {
            focus_out_from_li()
            {
                let all_li = document.getElementsByClassName('level1');
                //чистим от стиля выделения нулевой столбец
                for (let i = 0; i <= all_li.length - 1; i++) {
                    all_li[i].style.display = 'none';
                    all_li[i].parentElement.querySelector('a').style.backgroundColor = '#e5e5e5';
                }
            },
            set_menu(event)
            {
                let elem = event.target.parentElement.querySelector('ul');
                if(!elem)
                {
                    elem = event.target.parentElement;
                }
                if(elem)
                {
                    //получаю список классов элемента и сравниваю чтобы знать какой столбец
                let elem_class_list=elem.classList;
                let flag_have_class_1=false;
                let flag_have_class_2=false;
                for (let i = 0; i <= elem_class_list.length - 1; i++) {
                    if(elem_class_list[i]=='level1')
                    {
                        flag_have_class_1 = true
                    }
                    if(elem_class_list[i]=='level2')
                    {
                        flag_have_class_2 = true
                    }
                }

                    if(flag_have_class_1) {
                        if (elem.style.display !== 'block') {
                            let all_li = document.getElementsByClassName('level1');
                            //чистим от стиля выделения нулевой столбец
                            for (let i = 0; i <= all_li.length - 1; i++) {
                                all_li[i].style.display = 'none';
                                all_li[i].parentElement.querySelector('a').style.backgroundColor = '#e5e5e5';
                            }
                            //чистим от стиля выделения все дальнейшие столбцы
                            let all_a = elem.getElementsByTagName("a")
                            for (let j = 0; j <= all_a.length - 1; j++) {
                                all_a[j].style.backgroundColor = '#e5e5e5'
                            }
                            //закрываем последний столбец
                            let all_li1 = document.getElementsByClassName('level2');
                            for (let i = 0; i <= all_li1.length - 1; i++) {
                                all_li1[i].style.display = 'none';
                                all_li1[i].parentElement.querySelector('a').style.backgroundColor = '#e5e5e5';
                            }
                            //открываем столбец
                            elem.style.display='block';
                            //работа со стилем выделения
                            let elem_for_style = event.target.parentElement.querySelector('a');
                            elem_for_style.style.backgroundColor='#ccc';
                        }
                        else
                        {
                            let all_li = document.getElementsByClassName('level1');
                            //чистим от стиля выделения нулевой столбец
                            for (let i = 0; i <= all_li.length - 1; i++) {
                                all_li[i].style.display = 'none';
                                all_li[i].parentElement.querySelector('a').style.backgroundColor = '#e5e5e5';
                            }
                            //чистим от стиля выделения все дальнейшие столбцы
                            let all_a = elem.getElementsByTagName("a")
                            for (let j = 0; j <= all_a.length - 1; j++) {
                                all_a[j].style.backgroundColor = '#e5e5e5'
                            }
                            //закрываем последний столбец
                            let all_li1 = document.getElementsByClassName('level2');
                            for (let i = 0; i <= all_li1.length - 1; i++) {
                                all_li1[i].style.display = 'none';
                                all_li1[i].parentElement.querySelector('a').style.backgroundColor = '#e5e5e5';
                            }
                            //работа со стилем выделения
                            let elem_for_style = event.target.parentElement.querySelector('a');
                            elem_for_style.style.backgroundColor='#e5e5e5';
                        }
                 }
                    //второй втолбец
                if(flag_have_class_2) {
                    if (elem.style.display !== 'block') {
                        let all_li = document.getElementsByClassName('level2');
                        for (let i = 0; i <= all_li.length - 1; i++) {
                            all_li[i].style.display = 'none';
                            all_li[i].parentElement.querySelector('a').style.backgroundColor = '#e5e5e5';
                        }
                        //чистим от стиля выделения все дальнейшие столбцы
                        let all_a = elem.getElementsByTagName("a")
                        for (let j = 0; j <= all_a.length - 1; j++) {
                            all_a[j].style.backgroundColor = '#e5e5e5'
                        }
                        elem.style.display = 'block';
                        //работа со стилем выделения
                        let elem_for_style = event.target.parentElement.querySelector('a');
                        elem_for_style.style.backgroundColor='#ccc';
                    }
                    else
                    {
                        let all_li = document.getElementsByClassName('level2');
                        for (let i = 0; i <= all_li.length - 1; i++) {
                            all_li[i].style.display = 'none';
                            all_li[i].parentElement.querySelector('a').style.backgroundColor = '#e5e5e5';
                        }
                        //чистим от стиля выделения все дальнейшие столбцы
                        let all_a = elem.getElementsByTagName("a")
                        for (let j = 0; j <= all_a.length - 1; j++) {
                            all_a[j].style.backgroundColor = '#e5e5e5'
                        }
                        //работа со стилем выделения
                        let elem_for_style = event.target.parentElement.querySelector('a');
                        elem_for_style.style.backgroundColor='#e5e5e5';
                    }
                }

                //если самый последний столбец
                if((flag_have_class_2==false)&&(flag_have_class_1==false))
                {
                    // чистим стили
                    let all_li= document.getElementsByClassName('level2');

                    for (let k = 0; k <= all_li.length - 1; k++) {
                    let all_a=all_li[k].getElementsByTagName("a")
                    for (let j = 0; j <= all_a.length - 1; j++) {
                            all_a[j].style.backgroundColor='#e5e5e5'
                    }
                    }
                    elem.style.display='block';
                    let elem_for_style = event.target.parentElement.querySelector('a');
                   // console.log(elem_for_style.style.backgroundColor)
                    if (elem_for_style.style.backgroundColor=="rgb(229, 229, 229)") {
                        //работа со стилем выделения
                        let elem_for_style = event.target.parentElement.querySelector('a');
                        elem_for_style.style.backgroundColor = '#ccc';

                    }
                    else
                    {
                        //работа со стилем выделения
                        let elem_for_style = event.target.parentElement.querySelector('a');
                        elem_for_style.style.backgroundColor='#e5e5e5';

                    }
                }
                    //добавляю стили текущему блоку (открываем следующую колонку)
                    // elem.style.display='block';
                }


            },
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
