<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div  class="col-6 orders_create_title">
                    Заявки: Создание заявки
                </div>
            </div>
            <div class="col-12 second_header_cr_order">
                <div class="col-10 create_orders_second_title row">
                    <div class="col-3">
                        <span  class="create_orders_date_title">Дата внесения:</span>
                        <span class="create_orders_date_title_int">{{ data_vneseniya }}</span>
<!--                        <span class="create_orders_date_title_int">{{data_vneseniya| formatDate }}</span>-->
                       <span @click="openEndDatePicker">
                        <span class="iconify edit_icon" data-icon="akar-icons:edit" style="color: #a6a6a6;" data-width="20" data-height="20"></span>
                       </span>
                        <div class="datePickerDiv">
                        <datepicker :format="customFormatter"  v-model="data_vneseniya" ref="startDatePicker" >
                        </datepicker>
                        </div>
                    </div>
                    <div class="col-4 ">
                        <span  class="create_orders_date_title">Логист:</span>
                        <select @change="handleChange" v-if="logist_list" @update="update_order()" class="create_orders_date_title_int cr_ord_inp_n_1" v-model="logist">
                            <option v-bind:value="0" class="sel_cust">Константин Константинович</option>
                            <option v-bind:value="1" class="sel_cust">Иван Иванович</option>
                            <option v-bind:value="2" class="sel_cust">Джек Воробей</option>
                            <option v-bind:value="3" class="sel_cust">Путин В.В.</option>
                        </select>
                        <span v-if="!logist_list" class="create_orders_date_title_int">{{ logist_name }}</span>
                        <span @click="logist_show">
                        <span class="iconify edit_icon" data-icon="akar-icons:edit" style="color: #a6a6a6;" data-width="20" data-height="20"></span>
                       </span>
                    </div>
                    <div class="col-3">
                        <span  class="create_orders_date_title">Рассчитать до:</span>
                        <span class="create_orders_date_title_int">{{ rasschitat_do }}</span>
<!--                        <span class="create_orders_date_title_int">{{rasschitat_do| formatDate }}</span>-->
                        <span @click="openEndDatePicker1">
                        <span class="iconify edit_icon" data-icon="akar-icons:edit" style="color: #a6a6a6;" data-width="20" data-height="20"></span>
                       </span>
                        <div class="datePickerDiv">
                            <datepicker :format="customFormatter1" v-model="rasschitat_do" ref="startDatePicker1">
                            </datepicker>
                        </div>
                    </div>
                    <div class="col-2 justify-content-end">
                        <div class="col add_ts_button4 text-center">Утверждение</div>
                    </div>
                </div>
            </div>


            <div class="container row">
                <div class="container row">
                <div class="col-6 row ">
                    <div class="col cr_ord_left_col">
                        <div class="col">
                            <div class="little_title_create_orders">
                                Вид перевозки
                            </div>
                            <div class="create_orders_bottom">
                                <select @blur="update_order()" class="cr_ord_inp_n_1" v-model="vid_perevozki">
                                    <option v-bind:value="0" class="sel_cust">Автоперевозка</option>
                                    <option v-bind:value="1" class="sel_cust">Самолётом</option>
                                    <option v-bind:value="2" class="sel_cust">Кораблём</option>
                                    <option v-bind:value="3" class="sel_cust">На верблюде</option>
                                </select>
                            </div>
                        </div>

                        <div class="col">
                            <div class="bold_title_create_orders title_cr_ord_bold_first">
                                Информация о клиенте:
                            </div>
                        </div>
                        <div class="col">
                            <div class="little_title_create_orders1">
                                Номер заявки
                            </div>
                            <input @blur="update_order()" class="cr_ord_inp_n_1 border_input" v-model="nomer_zayavki"  />
                        </div>
                        <div class="col">
                            <div class="little_title_create_orders">
                                Компания заказчик
                                <span class="add_button n1">Добавить</span>
                            </div>
                            <input @blur="update_order()" class="cr_ord_inp_n_1 border_input" v-model="kompaniya_zakazchik"  />
                        </div>
                        <div class="col">
                            <div class="little_title_create_orders">
                                Менеджер заказчика
                                <span class="add_button n2">Добавить</span>
                            </div>
                            <input @blur="update_order()" class="cr_ord_inp_n_1 border_input" v-model="menedzer_zakazchik"  />
                        </div>
                        <div class="col">
                            <div class="little_title_create_orders">
                                Проект ИСД (номер и название)
                            </div>
                            <input @blur="update_order()" class="cr_ord_inp_n_1 border_input" v-model="ISD"  />
                        </div>
                        <div class="col-12 row">
                            <div class="col-6">
                                <div class="little_title_create_orders">
                                    Цена контракта
                                </div>
                                <input @blur="update_order()" class="cr_ord_inp_n_2 border_input" v-model="cena_kontrakta"  />
                            </div>
                            <div class="col-6">
                                <div class="little_title_create_orders">
                                    Дата контракта
                                </div>
                                <input @blur="update_order()" class="cr_ord_inp_n_2 border_input" v-model="data_kontrakta"  />
                            </div>
                        </div>
                        <div v-for="(oplata,key) in oplata_arr" class="col-12 row">
                            <div class="col-6">
                                <div class="little_title_create_orders">
                                    Оплата
                                </div>
                                <input @blur="update_order()" class="cr_ord_inp_n_2 border_input" v-model="oplata_arr[key].oplata"  />
                            </div>
                            <div class="col-6">
                                <div class="little_title_create_orders">
                                    Сумма
                                </div>
                                <input @blur="update_order()" class="cr_ord_inp_n_2 border_input" v-model="oplata_arr[key].summa"  />
                            </div>
                        </div>
                        <div class="col">
                            <div class="add_button_plus" v-on:click="dobavit_oplatu()">
                                +Добавить оплату
                            </div>
                        </div>
                    </div>
                    <div class="col fit_height row ">
                        <div class="col cr_ord_mid_col">
                            <div class="little_title_create_orders">
                                Номенклатура
                            </div>
                            <div class="create_orders_bottom">
                                <input class="cr_ord_inp_n_3 border_input" v-model="nomenklatura" placeholder="Добавьте файл.xlsx" />
                                <input hidden="true" type="file" id="files" ref="files"  v-on:change="handleFilesUpload()"/>
                                <span class="excel_set" v-on:click="addFiles()">
                                        <span class="iconify" data-icon="file-icons:microsoft-excel" style="color: #4d4d4d;" data-width="24" data-height="24"></span>
                                       </span>
                            </div>

                            <div class="col">
                                <div class="little_title_create_orders">
                                    Адрес погрузки 1
                                    <span class="add_button n3">Добавить</span>
                                </div>
                                <input @blur="update_order()" class="cr_ord_inp_n_1 border_input" v-model="adres_pogruzke"  />
                            </div>

                        </div>
                        <div class="col cr_ord_right_col">
                            <div class="col-12 row no_padding_right">
                                <div class="col-5 data_pog_dost no_padding_right">
                                    <div class="little_title_create_orders ">
                                        Дата погрузки
                                    </div>
                                    <div class="create_orders_bottom no_padding_right">
                                        <input @blur="update_order()" class="cr_ord_inp_n_2 border_input" v-model="data_pogruzki"  />
                                    </div>
                                </div>
                                <div class=" offset-1 col-6  data_pog_dost  no_padding_right">
                                    <div class="little_title_create_orders no_padding_right">
                                        Дата доставки
                                    </div>
                                    <div class="create_orders_bottom no_padding_right">
                                        <input @blur="update_order()" class="cr_ord_inp_n_2 border_input" v-model="data_dostavki"  />
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 row no_padding_right">
                                <div class="col-6 no_padding_left no_padding_right">
                                    <div class="little_title_create_orders">
                                        Кол-во грузомест
                                    </div>
                                    <div class="create_orders_bottom right_menu_nom row">
                                        <input @blur="update_order()" class="cr_ord_inp_n_4 border_input" v-model="gruzomesta_big"  />
                                        <input @blur="update_order()" class="cr_ord_inp_n_5 nom_margin border_input" v-model="gruzomesta_small"  readonly />
                                    </div>
                                </div>
                                <div class=" col-6 no_padding_right no_padding_left">
                                    <div class="little_title_create_orders no_padding_right">
                                        Расстояние, км
                                    </div>
                                    <div class="create_orders_bottom">
                                        <input @blur="update_order()" class="cr_ord_inp_n_2 border_input" v-model="rasstojanie"  />
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 row no_padding_right">
                                <div class="col-5 no_padding_right no_padding_left">
                                    <div class="little_title_create_orders">
                                        Общий вес, кг
                                    </div>
                                    <div class="create_orders_bottom">
                                        <input @blur="update_order()" class="cr_ord_inp_n_2 border_input" v-model="ob_ves"  />
                                    </div>
                                </div>
                                <div class="offset-1 col-6  no_padding_left no_padding_right">
                                    <div class="little_title_create_orders">
                                        Общий объём, м3
                                    </div>
                                    <div class="create_orders_bottom">
                                        <input @blur="update_order()" class="cr_ord_inp_n_2 border_input" v-model="ob_ob"  />
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 row">
                                <div class="little_title_create_orders no_padding_right">
                                    Адрес выгрузки 1
                                    <span class="add_button">Добавить</span>
                                </div>
                                <input @blur="update_order()" class="cr_ord_inp_n_1 border_input" v-model="adres_vygruski"  />
                            </div>
                        </div>
                        <div class="big_comment">
                            <div class="little_title_create_orders">
                                Комментарий
                            </div>
                            <textarea class="comm_settings" @blur="update_order()" v-model="komment_1" rows="6"  name="text"></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-6 fit_height right_white_block">
                    <div class="col-12 row ">
                        <div class="col-3 create_order_right_title ">
                            Список ТС
                        </div>
                        <div class="col-9 text-right row justify-content-end ">
                            <div class="col create_order_right_main_text text_in_header text-end">Общий бюджет:{{ ob_budjet }} р</div>
                            <div class="col add_ts_button" v-on:click="add_ts_func()">Добавить ТС</div>
                        </div>
                    </div>
                    <div class="col-12 row create_ord_underline"></div>

                    <span v-for="(elem,key) in spisokTSarr">
                                <div class="col row" >
                                    <div class="col-1 no_padding_right create_order_right_main_text">{{key + 1}}</div>
                                    <div class="col-2 no_padding_right ">
                                        <div class="col-12 no_padding_left d-flex justify-content-center no_padding_right create_ord_right_lit_text  ">Вид ТС</div>
                                        <div class="col-12 no_padding_left d-flex justify-content-center no_padding_right">
                                            <div class="create_order_right_main_text">
                                                <div v-for="(one_ts,key1) in ts_list_names" v-if="one_ts['ts_list_id']==elem.vid_TS" class="col-9 no_padding_left">{{ one_ts.ts_name }}</div>
                                                <div else class="col-9 no_padding_left"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-2 no_padding_right">
                                        <div class="col-12 no_padding_left d-flex justify-content-center no_padding_right create_ord_right_lit_text">Расстояние,км</div>
                                        <div class="col-12 no_padding_left d-flex justify-content-center no_padding_right create_order_right_main_text">{{ elem.rasstojanie_TS }}</div>
                                    </div>
                                    <div class="col-2 no_padding_right  ">
                                        <div class="col-12 no_padding_left d-flex justify-content-center no_padding_right create_ord_right_lit_text">Кол.грузомест</div>
                                        <div class="col-12 no_padding_left d-flex justify-content-center no_padding_right create_order_right_main_text">{{ elem.kol_gruz_TS }}</div>
                                    </div>
                                    <div class="col-2 no_padding_right  ">
                                        <div class="col-12 no_padding_left d-flex justify-content-center no_padding_right create_ord_right_lit_text">Кол.ТС</div>
                                        <div class="col-12 no_padding_left d-flex justify-content-center no_padding_right create_order_right_main_text">{{ elem.kol_TS_TS }}</div>
                                    </div>
                                    <div class="col-2  no_padding_right">
                                        <div class="col-12 no_padding_left d-flex justify-content-center no_padding_right create_ord_right_lit_text">Общий вес,кг</div>
                                        <div class="col-12 no_padding_left d-flex justify-content-center no_padding_right create_order_right_main_text">{{ elem.ob_ves_TS }}</div>
                                    </div>

                                    <div class="offset-1 col-2  no_padding_right">
                                        <div class="col-12 no_padding_left d-flex justify-content-center no_padding_right create_ord_right_lit_text text_line">Общий объём,м3</div>
                                        <div class="col-12 no_padding_left d-flex justify-content-center no_padding_right create_order_right_main_text">{{ elem.ob_ob_TS }}</div>
                                    </div>
                                    <div class="col-2  no_padding_right">
                                        <div class="col-12 no_padding_left d-flex justify-content-center no_padding_right create_ord_right_lit_text">Ставка</div>
                                        <div class="col-12 no_padding_left d-flex justify-content-center no_padding_right create_order_right_main_text">{{ elem.stavka_TS }}р.</div>
                                    </div>
                                    <div class="col-2  no_padding_right">
                                        <div class="col-12 no_padding_left d-flex justify-content-center no_padding_right create_ord_right_lit_text">Ставка КП</div>
                                        <div class="col-12 no_padding_left d-flex justify-content-center no_padding_right create_order_right_main_text">{{ elem.stavka_kp_TS }}р.</div>
                                    </div>
                                    <div class="col-2  no_padding_right">
                                        <div class="col-12 no_padding_left d-flex justify-content-center no_padding_right create_ord_right_lit_text">Маржа</div>
                                        <div class="col-12 no_padding_left d-flex justify-content-center no_padding_right create_order_right_main_text">{{ elem.marja_TS }}р.</div>
                                    </div>
                                </div>

                                <div class="col-12 second_right_create_orders_text row">
                                    <div class="col right_top_text_1_0" v-on:click="editTs(key)">
                                        <span class="iconify edit_icon_right_menu" data-icon="akar-icons:edit" style="color: #a6a6a6;" data-width="20" data-height="20"></span>
                                    </div>
                                    <div class="col right_top_text_1_1">
                                        <div class="create_ord_right_lit_text">Адрес Погрузки</div>
                                        <div class="create_order_right_main_text">{{ elem.adres_pogruzki_TS }}</div>
                                    </div>
                                    <div class="col">
                                        <div class="create_ord_right_lit_text">Адрес Выгрузки</div>
                                        <div class="create_order_right_main_text">{{ elem.adres_vygr_TS }}</div>
                                    </div>
                                </div>
                                <div class="col-12 row right_top_text_1_2">
                                    <div class="col">
                                        <div class="create_ord_right_lit_text">Комментарий</div>
                                        <div v-if="elem.kommentari_TS==''" class="create_order_right_main_text">Нет комментария</div>
                                        <div v-else class="create_order_right_main_text">{{ elem.kommentari_TS }}</div>
                                    </div>
                                </div>
                                                    <div class="col-12 row create_ord_underline"></div>
                                </span>



                    <!--                start_ts-->
                    <div v-if="add_ts" class="row">
                        <div class="col spisok_ts_second_title_left">
                            <div class="little_title_create_orders2">
                                Вид ТС
                            </div>
                            <div class="create_orders_bottom">
                                <select class="sel_cust" v-model="vid_TS">
                                    <option v-bind:value="0" class="sel_cust">Автоперевозка</option>
                                    <option v-bind:value="1" class="sel_cust">Тент</option>
                                    <option v-bind:value="2" class="sel_cust">Палатка</option>
                                </select>
                            </div>
                            <div class="col-12 row">
                                <div class="col-6">
                                    <div class="little_title_create_orders2">
                                        Ставка
                                    </div>
                                    <div class="create_orders_bottom">
                                        <input class="cr_ord_inp_n_6 border_input" v-model="stavka_TS"  />
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="little_title_create_orders2">
                                        Ставка за км
                                    </div>
                                    <div class="create_orders_bottom">
                                        <input class="cr_ord_inp_n_6 border_input" v-model="stavka_TS_za_km" readonly />
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 row">
                                <div class="col-6">
                                    <div class="little_title_create_orders2">
                                        Ставка КП
                                    </div>
                                    <div class="create_orders_bottom">
                                        <input class="cr_ord_inp_n_6 border_input" v-model="stavka_kp_TS"  />
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="little_title_create_orders2">
                                        Маржа
                                    </div>
                                    <div class="create_orders_bottom">
                                        <input class="cr_ord_inp_n_6 border_input" v-model="marja_TS"  readonly />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col row">
                            <div class="col spisok_ts_second_title_center ">
                                <div class="col row no_padding_right no_padding_left">
                                    <div class="col-12 row no_padding_right no_padding_left">
                                        <div class="col cr_ord_lit_left no_padding_right no_padding_left">
                                            <div class="little_title_create_orders2">
                                                Кол-во грузомест
                                            </div>
                                            <div class="create_orders_bottom">
                                                <input class="cr_ord_inp_n_8 border_input" v-model="kol_gruz_TS"  />
                                            </div>
                                        </div>
                                        <div class="col cr_ord_lit_center no_padding_right">
                                            <div class="little_title_create_orders2">
                                                Кол-во ТС
                                            </div>
                                            <div class="create_orders_bottom">
                                                <input class="cr_ord_inp_n_9 border_input" v-model="kol_TS_TS"  />
                                            </div>
                                        </div>
                                        <div class="col cr_ord_lit_right no_padding_left">
                                            <div class="little_title_create_orders2">
                                                Расстояние, км
                                            </div>
                                            <div class="create_orders_bottom">
                                                <input class="cr_ord_inp_n_10 border_input" v-model="rasstojanie_TS"  />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col no_padding_left">
                                        <div class="little_title_create_orders2">
                                            Адрес погрузки1
                                            <span class="add_button">Добавить</span>
                                        </div>
                                        <div class="create_orders_bottom row">
                                            <input class="cr_ord_inp_n_7 border_input" v-model="adres_pogruzki_TS"  />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col no_padding_right">
                                <div class="col-12 row">
                                    <div class="col-6 cr_ord_inp_n_11 ob_ves">
                                        <div class="little_title_create_orders2">
                                            Общий вес,кг
                                        </div>
                                        <div class="create_orders_bottom row">
                                            <input class=" border_input" v-model="ob_ves_TS"  />
                                        </div>
                                    </div>
                                    <div class="col-6 cr_ord_inp_n_11 ob_ves">
                                        <div class="little_title_create_orders2 ob_ob_width">
                                            Общий объём,м3
                                        </div>
                                        <div class="create_orders_bottom row">
                                            <input class=" border_input" v-model="ob_ob_TS"  />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="little_title_create_orders2 ob_ob_width">
                                        Адрес выгрузки1
                                        <span class="add_button">Добавить</span>
                                    </div>
                                    <div class="create_orders_bottom row">
                                        <input class="border_input ob_ob_width" v-model="adres_vygr_TS"  />
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col">
                            <div class="little_title_create_orders2">
                                Комментарий
                            </div>
                            <textarea v-model="kommentari_TS" rows="4" cols="60" name="text"></textarea>
                        </div>
                        <div class="col right_comments">
                            <div class="col-6 row">
                                <input class="col-2 checkbox_create_orders2 border_input" type="checkbox" id="checkbox1" v-model="checked2">
                                <div class="col">На терминале</div>
                            </div>
                            <div class="col">
                                <div class="little_title_create_orders">
                                    Терминал
                                    <span class="add_button">Добавить</span>
                                </div>
                                <div class="create_orders_bottom row">
                                    <input class="cr_ord_inp_n_12 border_input" v-model="terminal_TS"  />
                                </div>
                            </div>
                        </div>
                        <div class="col-11 save_buttons justify-content-end row">
                            <div class="col add_ts_button2 text-center" v-on:click="save_TS()">Сохранить</div>
                            <div class="col add_ts_button3 text-center" v-on:click="deleteTs()">Удалить</div>
                        </div>
                    </div>
                    <!--                end_ts-->

                </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    import datepicker from 'vuejs-datepicker';
    import moment from 'moment'
    Vue.filter('formatDate', function(value) {
        if (value) {
            return moment(String(value)).format('DD.MM.YYYY')
        }
    });
    export default {
        components: {
            datepicker
        },
        data() {
            return {
                order_id:'',
                vid_perevozki:'',
                nomenklatura:'',
                files:[],
                checked1:'',
                alert_message:[],
                data_vneseniya:'',
                rasschitat_do:'',
                logist:'',
                nomer_zayavki:'',
                kompaniya_zakazchik:'',
                menedzer_zakazchik:'',
                ISD:'',
                cena_kontrakta:'',
                data_kontrakta:'',
                dob_oplatu:false,
                oplata_arr:[],
                adres_pogruzke:'',
                data_pogruzki:'',
                data_dostavki:'',
                gruzomesta_big:'',
                // gruzomesta_small:'',
                rasstojanie:'',
                ob_ves:'',
                ob_ob:'',
                adres_vygruski:'',
                komment_1:'',
                // ob_budjet:0,
                add_ts:false,
                vid_TS:'',
                stavka_TS:'',
                // stavka_TS_za_km:'',
                stavka_kp_TS:'',
                // marja_TS:'',
                kol_gruz_TS:'',
                kol_TS_TS:'',
                rasstojanie_TS:'',
                adres_pogruzki_TS:'',
                ob_ves_TS:'',
                ob_ob_TS:'',
                adres_vygr_TS:'',
                kommentari_TS:'',
                checked2:'',
                terminal_TS:'',
                spisokTSarr:[],
                edit_flag:false,
                //НОМЕР В МАССИВЕ, ЭТО НЕ id редактируемого объекта
                edit_number:'',
                id_ts:'',
                ts_list_names:[],
                logist_list:false,
                logist_name:'Логист не выбран'

            }
        },
        mounted()
        {
            this.get_ts_list(this.ts_list_names);
            let adress=window.location.href;
            adress=(adress.split("/")[4])
            //если новая заявка
            if(adress==null)
            {
                this.data_vneseniya= new Date().toLocaleDateString();
                this.start_new_order();
            }
            //если редактируем заявку
            else
            {
                this.order_id=adress;
                this.start_get_old_order(adress,this.oplata_arr,this.spisokTSarr);
            }
        },

        computed: {
            stavka_TS_za_km: function () {
                if(this.stavka_TS==''||this.rasstojanie_TS=='')
                {
                    return 0
                }
                else
                {
                    return this.stavka_TS/this.rasstojanie_TS
                }
            },
            marja_TS: function () {
                if(this.stavka_kp_TS==''||this.stavka_TS=='')
                {
                    return 0
                }
                else
                {
                    return this.stavka_kp_TS-this.stavka_TS
                }
            },
            gruzomesta_small: function () {
                let gruzomesta = 0;
                for(var i = 0; i < this.spisokTSarr.length; i++)
                {
                    gruzomesta=(Number(gruzomesta)+Number(this.spisokTSarr[i]['kol_gruz_TS']));
                }
                return this.gruzomesta_big - gruzomesta;
            },
            ob_budjet:function () {
                let budjet = 0;
                for(var i = 0; i < this.spisokTSarr.length; i++)
                {
                    budjet=(Number(budjet)+Number(this.spisokTSarr[i]['stavka_TS']));
                }
                return budjet;
            },
        },
        methods: {
                handleChange(e) {
                var name = e.target.options[e.target.options.selectedIndex].text;
                this.logist_name=name ;
                this.logist_show();
                this.update_order();
             },
            logist_show()
            {
                this.logist_list=!this.logist_list
            },
            get_ts_list(inp)
            {
                axios
                    .post('/get_ts_list',{
                    })
                    .then(({ data }) => (
                            data.ts.forEach(function(entry) {
                                inp.push({
                                    id:entry.id,
                                    ts_list_id:entry.ts_list_id,
                                    ts_name:entry.ts_name
                                });
                            })
                        )
                    );
            },
            update_order()
            {
                axios
                    .post('/update_order',{
                        id:this.order_id,
                        data_vneseniya:this.data_vneseniya,
                        rasschitat_do:this.rasschitat_do,
                        nomenklatura:this.nomenklatura,
                        nomer_zayavki:this.nomer_zayavki,
                        kompaniya_zakazchik:this.kompaniya_zakazchik,
                        menedzer_zakazchik:this.menedzer_zakazchik,
                        ISD:this.ISD,
                        cena_kontrakta:this.cena_kontrakta,
                        data_kontrakta:this.data_kontrakta,
                        adres_pogruzke:this.adres_pogruzke,
                        data_pogruzki:this.data_pogruzki,
                        data_dostavki:this.data_dostavki,
                        adres_vygruski:this.adres_vygruski,
                        komment_1:this.komment_1,
                        logist:this.logist,
                        gruzomesta_big:this.gruzomesta_big,
                        gruzomesta_small:this.gruzomesta_small,
                        rasstojanie:this.rasstojanie,
                        ob_ves:this.ob_ves,
                        ob_ob:this.ob_ob,
                        vid_perevozki:this.vid_perevozki,
                        oplata_arr:this.oplata_arr,
                        start_flag:false
                    })
            },
            start_get_old_order(adress,inp,TSinp)
            {
                axios
                    .post('/start_get_old_order',{
                        id:adress
                    })
                    .then(({ data }) => (
                            this.data_vneseniya=data.data[0]['data_vneseniya'],
                            this.rasschitat_do=data.data[0]['rasschitat_do'],
                            this.nomenklatura=data.data[0]['nomenklatura'],
                            this.nomer_zayavki=data.data[0]['nomer_zayavki'],
                            this.kompaniya_zakazchik=data.data[0]['kompaniya_zakazchik'],
                            this.menedzer_zakazchik=data.data[0]['menedzer_zakazchik'],
                            this.ISD=data.data[0]['ISD'],
                            this.cena_kontrakta=data.data[0]['cena_kontrakta'],
                            this.data_kontrakta=data.data[0]['data_kontrakta'],
                            this.adres_pogruzke=data.data[0]['adres_pogruzke'],
                            this.data_pogruzki=data.data[0]['data_pogruzki'],
                            this.data_dostavki=data.data[0]['data_dostavki'],
                            this.adres_vygruski=data.data[0]['adres_vygruski'],
                            this.komment_1=data.data[0]['komment_1'],
                            this.logist=data.data[0]['logist'],
                            this.logist_name=data.data[0]['logist_name'],
                            this.gruzomesta_big=data.data[0]['gruzomesta_big'],
                            this.rasstojanie=data.data[0]['rasstojanie'],
                            this.ob_ves=data.data[0]['ob_ves'],
                            this.ob_ob=data.data[0]['ob_ob'],
                            this.vid_perevozki=data.data[0]['vid_perevozki'],
                                data.data[0].oplata.forEach(function(entry) {
                                    inp.push(entry);
                                }),
                                data.TS_list.forEach(function(entry) {
                                    TSinp.push({
                                        id_ts:entry.id_ts,
                                        order_id:entry.order_id,
                                        vid_TS : entry.vid_TS,
                                        stavka_TS : entry.stavka_TS,
                                        stavka_TS_za_km : entry.stavka_TS_za_km,
                                        stavka_kp_TS : entry.stavka_kp_TS,
                                        marja_TS : entry.marja_TS,
                                        kol_gruz_TS : entry.kol_gruz_TS,
                                        kol_TS_TS : entry.kol_TS_TS,
                                        rasstojanie_TS : entry.rasstojanie_TS,
                                        adres_pogruzki_TS : entry.adres_pogruzki_TS,
                                        ob_ves_TS : entry.ob_ves_TS,
                                        ob_ob_TS : entry.ob_ob_TS,
                                        adres_vygr_TS : entry.adres_vygr_TS,
                                        kommentari_TS : entry.kommentari_TS,
                                        checked2 : entry.checked2,
                                        terminal_TS : entry.terminal_TS
                                    });
                                })
                        )
                    )

            },
            //если новая заявка
            start_new_order()
            {
                axios
                    .post('/start_new_order',{
                        data_vneseniya:new Date().toLocaleDateString()
                    })
                    .then(({ data }) => (
                            this.order_id=data.data['id']
                        )
                    )
            },
            save_TS()
            {
                if(this.edit_flag==false)
                {
                    //проверка, были ли вообще хоть одно ТС
                    if(this.spisokTSarr.length==0)
                    {
                        this.id_ts = 0;
                    }
                    else
                    {
                        let last_id_ts=this.spisokTSarr.slice(-1);
                        this.id_ts=Number(last_id_ts[0]['id_ts'])+Number(1);
                        console.log('last id ts');
                        console.log(last_id_ts[0]['id_ts']);
                    }
                let objToPush= {};
                objToPush['id_ts'] = this.id_ts;
                objToPush['vid_TS'] = this.vid_TS;
                objToPush['stavka_TS'] = this.stavka_TS;
                objToPush['stavka_TS_za_km'] = this.stavka_TS_za_km;
                objToPush['stavka_kp_TS'] = this.stavka_kp_TS;
                objToPush['marja_TS'] = this.marja_TS;
                objToPush['kol_gruz_TS'] = this.kol_gruz_TS;
                objToPush['kol_TS_TS'] = this.kol_TS_TS;
                objToPush['rasstojanie_TS'] = this.rasstojanie_TS;
                objToPush['adres_pogruzki_TS'] = this.adres_pogruzki_TS;
                objToPush['ob_ves_TS'] = this.ob_ves_TS;
                objToPush['ob_ob_TS'] = this.ob_ob_TS;
                objToPush['adres_vygr_TS'] = this.adres_vygr_TS;
                objToPush['kommentari_TS'] = this.kommentari_TS;
                objToPush['checked2'] = this.checked2;
                objToPush['terminal_TS'] = this.terminal_TS;
                this.spisokTSarr.push(objToPush);


//сохраняем новое тс
                    axios
                        .post('/save_ts',{
                            id_ts:this.id_ts,
                            order_id:this.order_id,
                            vid_TS : this.vid_TS,
                            stavka_TS : this.stavka_TS,
                            stavka_TS_za_km : this.stavka_TS_za_km,
                            stavka_kp_TS : this.stavka_kp_TS,
                            marja_TS : this.marja_TS,
                            kol_gruz_TS : this.kol_gruz_TS,
                            kol_TS_TS : this.kol_TS_TS,
                            rasstojanie_TS : this.rasstojanie_TS,
                            adres_pogruzki_TS : this.adres_pogruzki_TS,
                            ob_ves_TS : this.ob_ves_TS,
                            ob_ob_TS : this.ob_ob_TS,
                            adres_vygr_TS : this.adres_vygr_TS,
                            kommentari_TS : this.kommentari_TS,
                            checked2 : this.checked2,
                            terminal_TS : this.terminal_TS
                        })

                this.vid_TS='';
                this.id_ts='';
                this.stavka_TS='';
                this.stavka_kp_TS='';
                this.kol_gruz_TS='';
                this.kol_TS_TS='';
                this.rasstojanie_TS='';
                this.adres_pogruzki_TS='';
                this.ob_ves_TS='';
                this.ob_ob_TS='';
                this.adres_vygr_TS='';
                this.kommentari_TS='';
                this.checked2='';
                this.terminal_TS='';
                this.add_ts=false;
                }
                //редактируем существующее ТС
                else
                {
                    this.spisokTSarr[this.edit_number]['vid_TS']=this.vid_TS;
                    this.spisokTSarr[this.edit_number]['stavka_TS']=this.stavka_TS;
                    this.spisokTSarr[this.edit_number]['stavka_kp_TS']=this.stavka_kp_TS;
                    this.spisokTSarr[this.edit_number]['kol_gruz_TS']=this.kol_gruz_TS;
                    this.spisokTSarr[this.edit_number]['kol_TS_TS']=this.kol_TS_TS;
                    this.spisokTSarr[this.edit_number]['rasstojanie_TS']=this.rasstojanie_TS;
                    this.spisokTSarr[this.edit_number]['adres_pogruzki_TS']=this.adres_pogruzki_TS;
                    this.spisokTSarr[this.edit_number]['ob_ves_TS']=this.ob_ves_TS;
                    this.spisokTSarr[this.edit_number]['ob_ob_TS']=this.ob_ob_TS;
                    this.spisokTSarr[this.edit_number]['adres_vygr_TS']=this.adres_vygr_TS;
                    this.spisokTSarr[this.edit_number]['kommentari_TS']=this.kommentari_TS;
                    this.spisokTSarr[this.edit_number]['checked2']=this.checked2;
                    this.spisokTSarr[this.edit_number]['terminal_TS']=this.terminal_TS;
                    axios
                        .post('/save_ts',{
                            id_ts:this.id_ts,
                            order_id:this.order_id,
                            vid_TS : this.vid_TS,
                            stavka_TS : this.stavka_TS,
                            stavka_TS_za_km : this.stavka_TS_za_km,
                            stavka_kp_TS : this.stavka_kp_TS,
                            marja_TS : this.marja_TS,
                            kol_gruz_TS : this.kol_gruz_TS,
                            kol_TS_TS : this.kol_TS_TS,
                            rasstojanie_TS : this.rasstojanie_TS,
                            adres_pogruzki_TS : this.adres_pogruzki_TS,
                            ob_ves_TS : this.ob_ves_TS,
                            ob_ob_TS : this.ob_ob_TS,
                            adres_vygr_TS : this.adres_vygr_TS,
                            kommentari_TS : this.kommentari_TS,
                            checked2 : this.checked2,
                            terminal_TS : this.terminal_TS
                        })
                    this.id_ts='';
                    this.vid_TS='';
                    this.stavka_TS='';
                    this.stavka_kp_TS='';
                    this.kol_gruz_TS='';
                    this.kol_TS_TS='';
                    this.rasstojanie_TS='';
                    this.adres_pogruzki_TS='';
                    this.ob_ves_TS='';
                    this.ob_ob_TS='';
                    this.adres_vygr_TS='';
                    this.kommentari_TS='';
                    this.checked2='';
                    this.terminal_TS='';
                    this.edit_number='';
                    this.edit_flag=false;
                    this.add_ts=false;
                }
            },
            editTs(key)
            {
                this.edit_number=key;
                this.edit_flag=true;
                this.add_ts=true;
                this.id_ts=this.spisokTSarr[key]['id_ts'];;
                this.vid_TS=this.spisokTSarr[key]['vid_TS'];
                this.stavka_TS=this.spisokTSarr[key]['stavka_TS'];
                this.stavka_kp_TS=this.spisokTSarr[key]['stavka_kp_TS'];
                this.kol_gruz_TS=this.spisokTSarr[key]['kol_gruz_TS'];
                this.kol_TS_TS=this.spisokTSarr[key]['kol_TS_TS'];
                this.rasstojanie_TS=this.spisokTSarr[key]['rasstojanie_TS'];
                this.adres_pogruzki_TS=this.spisokTSarr[key]['adres_pogruzki_TS'];
                this.ob_ves_TS=this.spisokTSarr[key]['ob_ves_TS'];
                this.ob_ob_TS=this.spisokTSarr[key]['ob_ob_TS'];
                this.adres_vygr_TS=this.spisokTSarr[key]['adres_vygr_TS'];
                this.kommentari_TS=this.spisokTSarr[key]['kommentari_TS'];
                this.checked2=this.spisokTSarr[key]['checked2'];
                this.terminal_TS=this.spisokTSarr[key]['terminal_TS'];
            },
            deleteTs()
            {
                if(this.edit_flag=false)
                {
                this.id_ts='';
                this.vid_TS='';
                this.stavka_TS='';
                this.stavka_kp_TS='';
                this.kol_gruz_TS='';
                this.kol_TS_TS='';
                this.rasstojanie_TS='';
                this.adres_pogruzki_TS='';
                this.ob_ves_TS='';
                this.ob_ob_TS='';
                this.adres_vygr_TS='';
                this.kommentari_TS='';
                this.checked2='';
                this.terminal_TS='';
                this.edit_number='';
                this.edit_flag=false;
                this.add_ts=false;
                }
                else
                {
                    axios
                        .post('/delete_TS',{
                            id_ts:this.id_ts,
                            order_id:this.order_id,
                        })
                    this.spisokTSarr.splice(this.edit_number, 1);
                    this.id_ts='';
                    this.vid_TS='';
                    this.stavka_TS='';
                    this.stavka_kp_TS='';
                    this.kol_gruz_TS='';
                    this.kol_TS_TS='';
                    this.rasstojanie_TS='';
                    this.adres_pogruzki_TS='';
                    this.ob_ves_TS='';
                    this.ob_ob_TS='';
                    this.adres_vygr_TS='';
                    this.kommentari_TS='';
                    this.checked2='';
                    this.terminal_TS='';
                    this.edit_number='';
                    this.edit_flag=false;
                    this.add_ts=false;

                }
            },
            add_ts_func()
            {
                this.add_ts=!this.add_ts;
            },
            dobavit_oplatu()
            {
                let objToPush= {};
                objToPush['oplata'] = '';
                objToPush['summa'] = '';
                this.oplata_arr.push(objToPush);
            },
            openEndDatePicker: function() {

            this.$refs.startDatePicker.showCalendar();
            // if(document.getElementsByClassName('vdp-datepicker__calendar')[3].style.display !== 'none')
            // {
            //      this.$refs.startDatePicker1.showCalendar();
            // }
            // document.getElementsByClassName('vdp-datepicker__calendar')[3].style.display = 'none';

            },
            openEndDatePicker1: function() {
            if(document.getElementsByClassName('vdp-datepicker__calendar')[0].style.display !== 'none')
            {
                 this.$refs.startDatePicker.showCalendar();
            }
                document.getElementsByClassName('vdp-datepicker__calendar')[0].style.display = 'none';
                this.$refs.startDatePicker1.showCalendar();
            },
            customFormatter1(date) {
                //первичная загрузка
                if(!this.start_flag)
                {
                    this.start_flag=true
                }
                //все последующие загрузки и изменения
                else
                {
                    this.rasschitat_do= new Date(this.rasschitat_do).toLocaleDateString();
                 //   this.update_order()
                }
              //  return moment(date);
            },
            customFormatter(date) {
                //первичная загрузка
                if(!this.start_flag)
                {
                    this.start_flag=true
                }
                //все последующие загрузки и изменения
                else
                {
                    this.data_vneseniya= new Date(this.data_vneseniya).toLocaleDateString();
                  //  this.update_order()
                }
                // console.log(this.data_vneseniya);
                // console.log(moment(date).format('D MM YYYY'));
               // return moment(date);
            },
            addFiles(){
                this.$refs.files.click();
            },
            handleFilesUpload(){
                let flag = 0;
                let uploadedFiles = this.$refs.files.files;
                        if ( /\.(xlsx?)$/i.test( uploadedFiles[0].name ) ) {
                            let reg ='';
                           reg=(uploadedFiles[0].name.match(/([A-Za-zа-яА-Я0-9Ёё\W]+)\.(xlsx?)/))
                            this.nomenklatura=reg[1];
                            this.nomenklatura=reg[0];
                            let formData = new FormData();
                                let file = uploadedFiles;
                                formData.append('file_xlsx', file[0]);
                                formData.append('file_name',this.nomenklatura);
                                formData.append('order_id',this.order_id);
                                formData.append('full_name',reg[0]);
                            axios.post( '/store_xlsx',
                                formData,
                                {
                                    headers: {
                                        'Content-Type': 'multipart/form-data'
                                    }
                                }
                            )
                        }
                        else
                        {
                            alert('Не верный формат файла')
                        }
            },

        }
    }
</script>

