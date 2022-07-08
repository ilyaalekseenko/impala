<template>
    <div class="container">
        <div class="row justify-content-center header_settings">
            <div class="col-6">
                <a href="/" class="menu_block">
                    <img class="logo_settings" :src="'/images/logo.png'" alt="">
                </a>
                <div class="menu_block top_menu_words">
                    Заявки
                </div>
                <div class="menu_block top_menu_words">
                    Справочник компаний
                </div>
                <div class="menu_block top_menu_words">
                    Меню-4
                </div>
                <div class="menu_block top_menu_words">
                    Меню-5
                </div>
                <div class="menu_block top_menu_words">
                    Меню-6
                </div>
                <div class="menu_block top_menu_words">
                    Меню-7
                </div>

            </div>
            <div class="col-5">
                <div class="menu_block right_menu_block_height"></div>
                <div class="menu_block top_menu_right_words">
                    <input class="inp_search " type="text" placeholder="Поиск" name="search">
                    <i class="fa fa-search search_set" style="font-size: 20px;"></i>
                </div>
                <img :src="'/images/ava.jpg'" class="menu_block top_menu_right_words new_head_user_icon ">
                <div class="menu_block  top_menu_right_words top_menu_user_name">Константин Константинопольский</div>
            </div>
            <div class="col-1">
                <div class="menu_block right_menu_block_height"></div>
            <span class="iconify" data-icon="ci:settings-filled" style="color: #020e49;" data-width="32" data-height="32"></span>
            <span class="iconify top_menu_icon_right" data-icon="majesticons:door-exit" style="color: #020e49;" data-width="32" data-height="32"></span>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
