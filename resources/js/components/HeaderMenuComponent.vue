<template>
    <div class="container">
        <div class="row justify-content-center header_settings">
            <a href="/" class="col-1">
                <img class="logo_settings" :src="'/images/logo.png'" alt="">
            </a>

            <div class="col-5 row">

<!--                <div class="menu_block top_menu_words">-->
<!--                    Заявки-->
<!--                </div>-->
                <div class="col top_menu_words align-self-center d-flex top_menu_1">
                    Справочник компаний
                </div>
                <div class="col top_menu_words  align-self-center d-flex">
                    Меню-4
                </div>
                <div class="col top_menu_words  align-self-center d-flex">
                    Меню-5
                </div>
                <div class="col top_menu_words  align-self-center d-flex">
                    Меню-6
                </div>
                <div class="col top_menu_words  align-self-center d-flex">
                    Меню-7
                </div>

            </div>
            <div class="col-5 row">
                <div class="col align-self-center row top_menu_right_words top_menu_2">
                    <input class="col inp_search " type="text" placeholder="Поиск" name="search">
                    <i class="col align-self-center fa fa-search search_set" style="font-size: 20px;"></i>
                </div>
                <div class="col align-self-center top_menu_3">
                    <img :src="'/images/ava.jpg'" class="col  new_head_user_icon ">
                </div>
                <div class="col align-self-center  top_menu_user_name no_wrap  ">Константин Константинопольский</div>
            </div>
            <div class="col align-self-center">
           <span class="gear_settings" v-on:click="go_to_docs_setting()"> <span class="iconify" data-icon="ci:settings-filled" style="color: #020e49;" data-width="32" data-height="32" ></span></span>
            <span class="iconify top_menu_icon_right" data-icon="majesticons:door-exit" style="color: #020e49;" data-width="32" data-height="32"></span>
            </div>

<!--            <div class="col-5">-->
<!--                <div class="menu_block right_menu_block_height"></div>-->
<!--                <div class="menu_block top_menu_right_words">-->
<!--                    <input class="inp_search " type="text" placeholder="Поиск" name="search">-->
<!--                    <i class="fa fa-search search_set" style="font-size: 20px;"></i>-->
<!--                </div>-->
<!--                <img :src="'/images/ava.jpg'" class="menu_block top_menu_right_words new_head_user_icon ">-->
<!--                <div class="menu_block  top_menu_right_words top_menu_user_name ">Константин Константинопольский</div>-->
<!--            </div>-->
<!--            <div class="col-1">-->
<!--                <div class="menu_block right_menu_block_height"></div>-->
<!--                <span class="iconify" data-icon="ci:settings-filled" style="color: #020e49;" data-width="32" data-height="32"></span>-->
<!--                <span class="iconify top_menu_icon_right" data-icon="majesticons:door-exit" style="color: #020e49;" data-width="32" data-height="32"></span>-->
<!--            </div>-->




        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        methods: {
            go_to_docs_setting()
            {
                window.location.href =('/docs_setting')
            }
        }
    }
</script>
