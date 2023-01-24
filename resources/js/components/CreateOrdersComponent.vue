<template>
    <div class="container" id="scroll_block">
        <div class="row">
            <div class="col-12 main_head_marg">
                <modal-pogruzka-component :get_gruzootpravitel_list='get_gruzootpravitel_list' :select_gruzootpravitel='select_gruzootpravitel'></modal-pogruzka-component>
                <div  class="col-6 orders_create_title">
                     {{ order_header_text }} {{ data_vneseniya }}
                </div>
            </div>
            <div class="col-12 second_header_cr_order">
                <div class="col-10 create_orders_second_title row">
                    <div class="col-3">
                        <span  class="create_orders_date_title">Дата внесения:</span>
<!--                        <date-picker  v-model="data_vneseniya" valueType="format" format="DD.MM.YYYY" :open="openDP"></date-picker>-->
                        <span class="create_orders_date_title_int">{{ data_vneseniya }}</span>
                        <span @click="openDB0">
<!--                       <span @click="openEndDatePicker">-->
                        <span class="iconify edit_icon" data-icon="akar-icons:edit" style="color: #a6a6a6;" data-width="20" data-height="20"></span>
                       </span>
                        <date-picker ref="datepicker0"  v-model="data_vneseniya" valueType="format" format="DD.MM.YYYY" :open.sync="openDP" @change="handleChange0"></date-picker>
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
                        <span @click="openDB1">
                        <span class="iconify edit_icon" data-icon="akar-icons:edit" style="color: #a6a6a6;" data-width="20" data-height="20"></span>
                       </span>
                        <date-picker ref="datepicker1"  v-model="rasschitat_do" valueType="format" format="DD.MM.YYYY"  :open.sync="openDP1" @change="handleChange1"></date-picker>
                    </div>
                    <div class="col-2 justify-content-end" v-on:click="go_to_grade()">
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
                                    <option v-for="(perevozka) in perevozka_arr" v-bind:value=perevozka.id  class="sel_cust">{{ perevozka.perevozka_name }}</option>
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
                            <span class=" cr_ord_inp_n_2 cena_kont_marg">
                                <div class="little_title_create_orders no_wrap_text">
                                    Цена контракта
                                </div>
                                <input @blur="update_order()" class="cr_ord_inp_n_2 border_input" v-model="cena_kontrakta"  />
                            </span>
                            <span class=" cr_ord_inp_n_2">
                                <div class="little_title_create_orders no_wrap_text">
                                    Дата контракта
                                </div>
                                <input @click="openDB4" class="cr_ord_inp_n_2 border_input" v-model="data_kontrakta"  />
                                <date-picker ref="datepicker4"  v-model="data_kontrakta" valueType="format"
                                             format="DD.MM.YYYY" :open.sync="openDP4" @change="handleChange0"></date-picker>
                            </span>
                        </div>
                        <div v-for="(oplata,key) in oplata_arr" class="col-12 row">
                            <span class=" cr_ord_inp_n_2 cena_kont_marg">
                                <div class="little_title_create_orders">
                                    Оплата
                                </div>
                                <input @blur="update_order()" class="cr_ord_inp_n_2 border_input" v-model="oplata_arr[key].oplata"  />
                           </span>
                            <span class=" cr_ord_inp_n_2 cena_kont_marg">
                                <div class="little_title_create_orders">
                                    Сумма
                                </div>
                                <input @blur="update_order()" class="cr_ord_inp_n_2 border_input" v-model="oplata_arr[key].summa"  />
                                </span>
                        </div>
                        <div class="col">
                            <div class="add_button_plus" v-on:click="dobavit_oplatu()">
                                +Добавить оплату
                            </div>
                        </div>
                    </div>
                    <div class="col fit_height row middle_block_cr_order ">
                        <div class="col cr_ord_mid_col">
                            <div class="little_title_create_orders">
                                Номенклатура
                            </div>
                            <div class="create_orders_bottom">
<!--                                <input class="cr_ord_inp_n_3 border_input" v-model="nomenklatura" placeholder="Добавьте файл.xlsx" />-->
                                <div class="cr_ord_div_nomenklatura">{{ nomenklatura }}</div>
                                <input hidden="true" type="file" id="files" ref="files"  v-on:change="handleFilesUpload()"/>
                                <span class="excel_set" v-on:click="addFiles()">
                                <span class="iconify" data-icon="file-icons:microsoft-excel" style="color: #4d4d4d;" data-width="24" data-height="24"></span>
                                </span>

                                <span class="excel_set" v-if="nomenklatura" v-on:click="DownloadFiles()">
                                <span class="iconify" data-icon="material-symbols:sim-card-download-outline-rounded" style="color: #4d4d4d;" data-width="24" data-height="24"></span>
                                </span>

                            </div>

                            <div class="col ad_pogr_marg">
                                <div class="little_title_create_orders row">
                                   <div class="col no_wrap">Адрес погрузки</div>
                                   <div class="col add_button" v-b-modal.modal-xl variant="primary" v-on:click="select_temp_var('pogruzka',0)">Добавить</div>
                                </div>
                                <select @blur="update_order()" class="cr_ord_inp_n_1" v-model="adres_pogruzke">
                                    <option v-for="(gruzootpravitel) in gruzootpravitel_arr" v-bind:value=gruzootpravitel.id  class="sel_cust">{{ gruzootpravitel.nazvanie }}</option>
                                </select>
                            </div>

                        </div>
                        <div class="col cr_ord_right_col">
                            <div class="col-12 row no_padding_right">
                                <div class="col-5 data_pog_dost no_padding_right">
                                    <div class="little_title_create_orders ">
                                        Дата погрузки
                                    </div>
                                    <div class="data_pog_dost_height no_padding_right">
                                        <input @click="openDB2" class="cr_ord_inp_n_2 border_input" v-model="data_pogruzki"  />
                                        <date-picker ref="datepicker2"  v-model="data_pogruzki" valueType="format" format="DD.MM.YYYY" :open.sync="openDP2" @change="handleChange0"></date-picker>
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
                            <div class="col ad_pogr_marg">
                                <div class="little_title_create_orders row">
                                    <div class="col no_wrap">Адрес выгрузки 1</div>
                                    <div class="col add_button" v-b-modal.modal-xl variant="primary" v-on:click="select_temp_var('vygruzka',0)">Добавить</div>
                                </div>
                                <select @blur="update_order()" class="cr_ord_inp_n_1" v-model="adres_vygruski">
                                    <option v-for="(gruzootpravitel) in gruzootpravitel_arr" v-bind:value=gruzootpravitel.id  class="sel_cust">{{ gruzootpravitel.nazvanie }}</option>
                                </select>
                            </div>
                            <div class="col-12 row no_padding_right">
                                <div class="col-5  data_pog_dost  no_padding_right">
                                    <div class="little_title_create_orders">
                                        Дата доставки
                                    </div>
                                    <div class="data_pog_dost_height no_padding_right">
                                        <input @click="openDB3" class="cr_ord_inp_n_2 border_input" v-model="data_dostavki"  />
                                        <date-picker ref="datepicker3"  v-model="data_dostavki" valueType="format" format="DD.MM.YYYY" :open.sync="openDP3" @change="handleChange0"></date-picker>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="big_comment">
                            <div class="little_title_create_orders">
                                Комментарий
                            </div>
                            <textarea class="comm_settings_1" @blur="update_order()" v-model="komment_1" rows="6"  name="text"></textarea>
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
                            <div class="col add_ts_button" v-on:click="add_ts_func()" id="box">Добавить ТС</div>
                        </div>
                    </div>
                    <div class="col-12 row create_ord_underline"></div>

                    <!--                        список старых ТС-->
                    <span v-for="(elem,key) in spisokTSarr">
                        <span v-if="add_ts!==key">
                        <div class="col row" >
                                    <div class="col-1 no_padding_right create_order_right_main_text ts_counter">{{key + 1}}</div>
                                    <div class="col-2 no_padding_right ">
                                        <div class="col-12 no_padding_left d-flex justify-content-left no_padding_right create_ord_right_lit_text mt_ts_text ">Вид ТС</div>
                                        <div class="col-12 no_padding_left d-flex justify-content-left no_padding_right">
                                                <div v-for="(one_ts,key1) in ts_list_names" v-if="one_ts['ts_list_id']==elem.vid_TS" class="col-12 create_order_right_main_text text_out_block no_padding_left">{{ one_ts.ts_name }}</div>
                                        </div>
                                    </div>
                                    <div class="col-2 no_padding_right">
                                        <div class="col-12 no_padding_left d-flex justify-content-left no_padding_right create_ord_right_lit_text mt_ts_text">Расстояние,км</div>
                                        <div class="col-12 no_padding_left d-flex justify-content-left no_padding_right create_order_right_main_text text_out_block">{{ elem.rasstojanie_TS }}</div>
                                    </div>
                                    <div class="col-2 no_padding_right  ">
                                        <div class="col-12 no_padding_left d-flex justify-content-left no_padding_right create_ord_right_lit_text mt_ts_text">Кол.грузомест</div>
                                        <div class="col-12 no_padding_left d-flex justify-content-left no_padding_right create_order_right_main_text text_out_block">{{ elem.kol_gruz_TS }}</div>
                                    </div>
                                    <div class="col-2 no_padding_right  ">
                                        <div class="col-12 no_padding_left d-flex justify-content-left no_padding_right create_ord_right_lit_text mt_ts_text">Кол.ТС</div>
                                        <div class="col-12 no_padding_left d-flex justify-content-left no_padding_right create_order_right_main_text text_out_block">{{ elem.kol_TS_TS }}</div>
                                    </div>
                                    <div class="col-2  no_padding_right">
                                        <div class="col-12 no_padding_left d-flex justify-content-left no_padding_right create_ord_right_lit_text mt_ts_text">Общий вес,кг</div>
                                        <div class="col-12 no_padding_left d-flex justify-content-left no_padding_right create_order_right_main_text text_out_block">{{ elem.ob_ves_TS }}</div>
                                    </div>

                                    <div class="offset-1 col-2  no_padding_right">
                                        <div class="col-12 no_padding_left d-flex justify-content-left no_padding_right create_ord_right_lit_text text_line mt_ts_text">Общий объём,м3</div>
                                        <div class="col-12 no_padding_left d-flex justify-content-left no_padding_right create_order_right_main_text text_out_block">{{ elem.ob_ob_TS }}</div>
                                    </div>
                                    <div class="col-2  no_padding_right">
                                        <div class="col-12 no_padding_left d-flex justify-content-left no_padding_right create_ord_right_lit_text mt_ts_text">Ставка</div>
                                        <div class="col-12 no_padding_left d-flex justify-content-left no_padding_right create_order_right_main_text text_out_block">{{ elem.stavka_TS }}<span v-if="elem.stavka_TS">р.</span></div>
                                    </div>
                                    <div class="col-2  no_padding_right">
                                        <div class="col-12 no_padding_left d-flex justify-content-left no_padding_right create_ord_right_lit_text mt_ts_text">Ставка КП</div>
                                        <div class="col-12 no_padding_left d-flex justify-content-left no_padding_right create_order_right_main_text text_out_block">{{ elem.stavka_kp_TS }}<span v-if="elem.stavka_kp_TS">р.</span></div>
                                    </div>
                                    <div class="col-2  no_padding_right">
                                        <div class="col-12 no_padding_left d-flex justify-content-left no_padding_right create_ord_right_lit_text mt_ts_text">Маржа</div>
                                        <div class="col-12 no_padding_left d-flex justify-content-left no_padding_right create_order_right_main_text text_out_block">{{ elem.marja_TS }} <span v-if="elem.marja_TS">р.</span> </div>
                                    </div>
                                </div>

                                <div class="col-12 second_right_create_orders_text">
                                    <div class="col right_top_text_1_0" v-on:click="editTs(key)">
                                        <span class="iconify edit_icon_right_menu" data-icon="akar-icons:edit" style="color: #a6a6a6;" data-width="20" data-height="20"></span>
                                    </div>
                                    <div class="col-12 row">
                                        <div class="col-6">
                                        <div class="col-12" v-for="(elem1,key1) in elem.adres_pogruzki_TS">
                                            <div class="create_ord_right_lit_text mt_ts_text">Адрес Погрузки {{ key1 +1 }}</div>
                                            <div class="cr_ord_inp_n_7" v-for="(gruz,key1) in gruzootpravitel_arr" v-if="gruz['id']==elem1['adres_pogruzki']">{{ gruz.nazvanie }}</div>
                                        </div>
                                             </div>
                                        <div class="col-6">
                                        <div class="col-12" v-for="(elem1,key1) in elem.adres_vygr_TS">
                                            <div class="create_ord_right_lit_text mt_ts_text">Адрес Выгрузки {{ key1 +1 }}</div>
                                            <div class="cr_ord_inp_n_7" v-for="(gruz,key1) in gruzootpravitel_arr" v-if="gruz['id']==elem1['adres_pogruzki']">{{ gruz.nazvanie }}</div>
                                        </div>
                                        </div>
                                   </div>
                                </div>
                                <div class="col-12 row right_top_text_1_2">
                                    <div class="col">
                                        <div class="create_ord_right_lit_text mt_ts_text">Комментарий</div>
                                        <div v-if="elem.kommentari_TS==''" class="create_order_right_main_text">Нет комментария</div>
                                        <div v-else class="create_order_right_main_text">{{ elem.kommentari_TS }}</div>
                                    </div>
                                </div>
                                                    <div class="col-12 row create_ord_underline"></div>
                        </span>
                    <!--             конец список старых ТС-->


                    <!--  Добавление нового ТС или редактирование старого-->
                    <div v-if="add_ts===key" class="row add_ts_class">
                        <div class="col spisok_ts_second_title_left">
                            <div class="little_title_create_orders2">
                                Вид ТС
                            </div>
                            <div class="create_orders_bottom">
                                <select  class="sel_cust select_width" v-model="vid_TS">
                                    <option v-for="(ts_list_one) in ts_list_names" v-bind:value=ts_list_one.id  class="sel_cust">{{ ts_list_one.ts_name }}</option>
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
                                        <div class="cr_ord_inp_n_6">{{ stavka_TS_za_km }} р.</div>
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
                                        <div>{{ marja_TS }} р.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col row">
                            <div class="col ">
                                <div class="col row no_padding_right no_padding_left">
                                    <div class="col-12 row no_padding_right no_padding_left">
                                        <div class="col cr_ord_lit_left no_padding_right no_padding_left">
                                            <div class="little_title_create_orders2 no_wrap">
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

                                    <div class="col-12 row">
                                        <div class="col-6 cr_ord_inp_n_11 ob_ves">
                                            <div class="little_title_create_orders2">
                                                Общий вес,кг
                                            </div>
                                            <div class="create_orders_bottom row">
                                                <input class="cr_ord_inp_n_13 border_input" v-model="ob_ves_TS"  />
                                            </div>
                                        </div>
                                        <div class="col-6 cr_ord_inp_n_11 ob_ves">
                                            <div class="little_title_create_orders2 ob_ob_width">
                                                Общий объём,м3
                                            </div>
                                            <div class="create_orders_bottom row">
                                                <input class="cr_ord_inp_n_13 border_input" v-model="ob_ob_TS"  />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 ad_pogr_marg1" v-for="(elem,key) in ad_pogruzki_arr_temp">
                                        <div class="little_title_create_orders2 ob_ob_width">
                                            Адрес погрузки
                                            <span class="add_button" v-b-modal.modal-xl variant="primary" v-on:click="select_temp_var('TS_pogruzka',key)">Добавить</span>
                                        </div>
                                        <select @blur="update_order()" class="cr_ord_inp_n_1" v-model="ad_pogruzki_arr_temp[key]['adres_pogruzki']">
                                            <option v-for="(gruzootpravitel) in gruzootpravitel_arr" v-bind:value=gruzootpravitel.id  class="sel_cust">{{ gruzootpravitel.nazvanie }}</option>
                                        </select>
                                        <button type="button" class="btn btn-success" v-on:click="add_empty_adres_pogr()">+</button>
<!--                                        <span v-on:click="add_empty_adres_pogr()">+</span>-->
                                        <button type="button" class="btn btn-danger btn_del_in_ord" v-on:click="delete_adres_pogr(key)">-</button>
<!--                                        <span v-on:click="delete_adres_pogr(key)">-</span>-->
                                    </div>

                                </div>

                            <div class="col no_padding_right">
                                <div class="col-12 ad_vygr_right_ord" v-for="(elem,key) in ad_vygruz_arr_temp">
                                    <div class="little_title_create_orders2 ob_ob_width">
                                        Адрес выгрузки
                                        <span class="add_button" v-b-modal.modal-xl variant="primary" v-on:click="select_temp_var('TS_vygruzka',key)">Добавить</span>
                                    </div>
                                    <div class="create_orders_bottom">
<!--                                        <input class="border_input cr_ord_inp_n_7" v-model="ad_vygruz_arr_temp[key]['adres_pogruzki']"  />-->
                                        <select @blur="update_order()" class="cr_ord_inp_n_1" v-model="ad_vygruz_arr_temp[key]['adres_pogruzki']">
                                            <option v-for="(gruzootpravitel) in gruzootpravitel_arr" v-bind:value=gruzootpravitel.id  class="sel_cust">{{ gruzootpravitel.nazvanie }}</option>
                                        </select>
                                        <button type="button" class="btn btn-success" v-on:click="add_empty_adres_vygruz()">+</button>
                                        <button type="button" class="btn btn-danger btn_del_in_ord" v-on:click="delete_adres_vygruz(key)">-</button>

                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>


                        <div class="col-12">
                            <div class="little_title_create_orders2">
                                Комментарий
                            </div>
                            <textarea class="col-12" v-model="kommentari_TS" rows="4" name="text"></textarea>
                        </div>
                        <div class="col-12 right_comments">

                            <div class="col-6 row checkbox2_row">
                                <input class="col-2 checkbox_create_orders2 border_input checkbox_create_orders2_height" type="checkbox" id="checkbox1" v-model="checked2">
                                <div class="col terminal_ts">На терминале</div>
                            </div>
                            <div v-if="checked2" class="col-6 terminal_cr_ord">
                                <div class="little_title_create_orders">
                                    Терминал
                                    <span class="add_button"  v-on:click="add_empty_terminal()">Добавить</span>
                                </div>
                                <div class="create_orders_bottom row">
                                    <select @blur="update_order()" class="cr_ord_inp_n_1" v-model="terminal_TS">
                                        <option v-for="(elem,key) in termList" v-bind:value=elem.id  class="sel_cust">{{ elem.name }}</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="col-11 save_buttons justify-content-end row ">
                            <div class="col offset-1 add_ts_button2 text-center" v-on:click="save_TS()">Сохранить</div>
                            <div class="col add_ts_button3 text-center" v-on:click="deleteTs()">Удалить</div>
                        </div>
                    </div>
                    <div v-if="add_ts===key" class="col-12 row create_ord_underline"></div>

                        <!--                end_ts-->
 </span>
                    <div v-if="add_new_ts" class="row add_ts_class">
                        <div class="col spisok_ts_second_title_left">
                            <div class="little_title_create_orders2">
                                Вид ТС
                            </div>
                            <div class="create_orders_bottom">

                                <select  class="sel_cust select_width" v-model="vid_TS">
                                    <option v-for="(ts_list_one) in ts_list_names" v-bind:value=ts_list_one.id  class="sel_cust">{{ ts_list_one.ts_name }}</option>
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
                                        <div class="cr_ord_inp_n_6">{{ stavka_TS_za_km }} р.</div>
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
                            <div class="col ">
                                <div class="col row no_padding_right no_padding_left">
                                    <div class="col-12 row no_padding_right no_padding_left">
                                        <div class="col cr_ord_lit_left no_padding_right no_padding_left">
                                            <div class="little_title_create_orders2 no_wrap">
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

                                    <div class="col-12 row">
                                        <div class="col-6 cr_ord_inp_n_11 ob_ves">
                                            <div class="little_title_create_orders2">
                                                Общий вес,кг
                                            </div>
                                            <div class="create_orders_bottom row">
                                                <input class="cr_ord_inp_n_13 border_input" v-model="ob_ves_TS"  />
                                            </div>
                                        </div>
                                        <div class="col-6 cr_ord_inp_n_11 ob_ves">
                                            <div class="little_title_create_orders2 ob_ob_width">
                                                Общий объём,м3
                                            </div>
                                            <div class="create_orders_bottom row">
                                                <input class="cr_ord_inp_n_13 border_input" v-model="ob_ob_TS"  />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 ad_pogr_marg1" v-for="(elem,key) in ad_pogruzki_arr_temp">
                                        <div class="little_title_create_orders2 ob_ob_width">
                                            Адрес погрузки
                                            <span class="add_button" v-b-modal.modal-xl variant="primary" v-on:click="select_temp_var('TS_pogruzka_new',key)">Добавить</span>
                                        </div>
<!--                                        <input class="cr_ord_inp_n_7 border_input" v-model="ad_pogruzki_arr_temp[key]['adres_pogruzki']"  />-->
                                        <select @blur="update_order()" class="cr_ord_inp_n_1" v-model="ad_pogruzki_arr_temp[key]['adres_pogruzki']">
                                            <option v-for="(gruzootpravitel) in gruzootpravitel_arr" v-bind:value=gruzootpravitel.id  class="sel_cust">{{ gruzootpravitel.nazvanie }}</option>
                                        </select>
                                        <button type="button" class="btn btn-success" v-on:click="add_empty_adres_pogr()">+</button>
                                        <button type="button" class="btn btn-danger btn_del_in_ord" v-on:click="delete_adres_pogr(key)">-</button>

                                    </div>

                                </div>

                                <div class="col no_padding_right">
                                    <div class="col-12 ad_vygr_right_ord" v-for="(elem,key) in ad_vygruz_arr_temp">
                                        <div class="little_title_create_orders2 ob_ob_width">
                                            Адрес выгрузки
                                            <span class="add_button" v-b-modal.modal-xl variant="primary" v-on:click="select_temp_var('TS_vygruzka_new',key)">Добавить</span>
                                        </div>
                                        <div class="create_orders_bottom">
<!--                                            <input class="border_input cr_ord_inp_n_7" v-model="ad_vygruz_arr_temp[key]['adres_pogruzki']"  />-->
                                            <select @blur="update_order()" class="cr_ord_inp_n_1" v-model="ad_vygruz_arr_temp[key]['adres_pogruzki']">
                                                <option v-for="(gruzootpravitel) in gruzootpravitel_arr" v-bind:value=gruzootpravitel.id  class="sel_cust">{{ gruzootpravitel.nazvanie }}</option>
                                            </select>
                                            <button type="button" class="btn btn-success" v-on:click="add_empty_adres_vygruz()">+</button>
                                            <button type="button" class="btn btn-danger btn_del_in_ord" v-on:click="delete_adres_vygruz(key)">-</button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-12">
                            <div class="little_title_create_orders2">
                                Комментарий
                            </div>
                            <textarea class="col-12" v-model="kommentari_TS" rows="4" name="text"></textarea>
                        </div>
                        <div class="col-12 right_comments">

                            <div class="col-6 row checkbox2_row">
                                <input class="col-2 checkbox_create_orders2 checkbox_create_orders2_height_d border_input" type="checkbox" id="checkbox1" v-model="checked2">
                                <div class="col terminal_ts">На терминале</div>
                            </div>
                            <div v-if="checked2" class="col-6 terminal_cr_ord">
                                <div class="little_title_create_orders">
                                    Терминал
                                    <span class="add_button"  v-on:click="add_empty_terminal()">Добавить</span>
                                </div>
                                <div class="create_orders_bottom row">
                                    <select @blur="update_order()" class="cr_ord_inp_n_1" v-model="terminal_TS">
                                        <option v-for="(elem,key) in termList" v-bind:value=elem.id  class="sel_cust">{{ elem.name }}</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="col-11 save_buttons justify-content-end row">
                            <div class="col offset-1 add_ts_button2 text-center" v-on:click="save_TS()">Сохранить</div>
                            <div class="col add_ts_button3 text-center" v-on:click="deleteTs()">Удалить</div>
                        </div>
                    </div>

                </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    import datepicker from 'vuejs-datepicker';
    import moment from 'moment'

    import DatePicker from 'vue2-datepicker';
    import 'vue2-datepicker/index.css';

    Vue.filter('formatDate', function(value) {
        if (value) {
            return moment(String(value)).format('DD.MM.YYYY')
        }
    });
    export default {
        components: {
            datepicker,
            DatePicker
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
                add_ts:'',
                add_new_ts:false,
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
                logist_name:'Логист не выбран',
                openDP:false,
                openDP1:false,
                openDP2:false,
                openDP3:false,
                openDP4:false,
                termList:[],
                terminal_act_list:[],
                ad_pogruzki_arr_temp:[],
                ad_vygruz_arr_temp:[],
                perevozka_arr:[],
                gruzootpravitel_arr: [],
                select_temp_pogr_or_vygr:'',
                select_temp_pogr_or_vygr_key:'',
                order_header_text:''
            }
        },
        mounted()
        {
            this.get_terminal_list(this.termList);
            this.get_perevozka_list(this.perevozka_arr)
            this.get_gruzootpravitel_list(this.gruzootpravitel_arr)
            for (let i = document.getElementsByClassName('mx-input-wrapper').length; i > 0 ; i--) {
                let m = i - 1
                document.getElementsByClassName('mx-input-wrapper')[m].remove();
            }
            this.get_ts_list(this.ts_list_names);
            let adress=window.location.href;
            adress=(adress.split("/")[4])
            //если новая заявка
            if(adress==null)
            {
                this.data_vneseniya= new Date().toLocaleDateString();
                this.start_new_order();
                this.order_header_text='Создание заявки'
            }
            //если редактируем заявку
            else
            {
                this.order_id=adress;
                this.order_header_text='Заявка номер: '+adress+' Дата внесения: '
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
                    // return this.stavka_TS/this.rasstojanie_TS
                    let st= this.stavka_TS/this.rasstojanie_TS
                    let fin =(parseInt(st * 100)) / 100
                    return fin
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
            get_perevozka_list(inp)
            {
                axios
                    .post('/get_perevozka_list',{
                    })
                    .then(({ data }) => (
                            data.perevozka_list.forEach(function(entry) {
                                inp.push({
                                    id:entry.id,
                                    perevozka_id:entry.perevozka_id,
                                    perevozka_name:entry.perevozka_name
                                });
                            })
                        )
                    );
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
            //метод выборки, адрес погрузки или адрес выгрузки поменяется динамически
            select_temp_var(pogr_or_vygr,key)
            {
                //погрузка 1 - pogruzka
                //выгрузка 1 - vygruzka
                //список ТС редактирование тс, погрузка - TS_pogruzka
                //список ТС редактирование тс, выгрузка - TS_vygruzka
                //список ТС новое тс, погрузка - TS_pogruzka_new
                //список ТС новое тс, выгрузка - TS_vygruzka_new
                this.select_temp_pogr_or_vygr=pogr_or_vygr
                this.select_temp_pogr_or_vygr_key=key
            },
            select_gruzootpravitel()
            {
                if(this.select_temp_pogr_or_vygr=='pogruzka')
                {
                    axios
                        .post('/select_gruzootpravitel',{
                        })
                        .then(({ data }) => (
                                this.adres_pogruzke=data.gruzootpravitel.id,
                                    this.update_order()
                            )

                        );
                }
                if(this.select_temp_pogr_or_vygr=='vygruzka')
                {
                    axios
                        .post('/select_gruzootpravitel',{
                        })
                        .then(({ data }) => (
                                this.adres_vygruski=data.gruzootpravitel.id,
                                    this.update_order()
                            )

                        );
                }
                if(this.select_temp_pogr_or_vygr=='TS_pogruzka')
                {
                    axios
                        .post('/select_gruzootpravitel',{
                        })
                        .then(({ data }) => (
                                this.ad_pogruzki_arr_temp[this.select_temp_pogr_or_vygr_key]['adres_pogruzki']=data.gruzootpravitel.id,
                                    this.update_order()
                            )

                        );
                }
                if(this.select_temp_pogr_or_vygr=='TS_vygruzka')
                {
                    axios
                        .post('/select_gruzootpravitel',{
                        })
                        .then(({ data }) => (
                                this.ad_vygruz_arr_temp[this.select_temp_pogr_or_vygr_key]['adres_pogruzki']=data.gruzootpravitel.id,
                                    this.update_order()
                            )

                        );
                }
                if(this.select_temp_pogr_or_vygr=='TS_pogruzka_new')
                {
                    axios
                        .post('/select_gruzootpravitel',{
                        })
                        .then(({ data }) => (
                                this.ad_pogruzki_arr_temp[this.select_temp_pogr_or_vygr_key]['adres_pogruzki']=data.gruzootpravitel.id,
                                    this.update_order()
                            )

                        );
                }
                if(this.select_temp_pogr_or_vygr=='TS_vygruzka_new')
                {
                    axios
                        .post('/select_gruzootpravitel',{
                        })
                        .then(({ data }) => (
                                this.ad_vygruz_arr_temp[this.select_temp_pogr_or_vygr_key]['adres_pogruzki']=data.gruzootpravitel.id,
                                    this.update_order()
                            )

                        );
                }

            },
            get_terminal_list(inp)
            {
                axios
                    .post('/get_terminal_list',{
                    })
                    .then(({ data }) => (
                            data.terminal.forEach(function(entry) {
                                inp.push({
                                    id:entry.id,
                                    name:entry.ts_name
                                });
                            })
                        )
                    );
            },
            handleChange0(){
                this.update_order()
            },
            handleChange1(){
                this.update_order()
            },
            openDB0()
            {
                console.log(this.$refs.files)
                this.openDP=true
            },
            openDB1()
            {
                this.openDP1=true;
            },
            openDB2()
            {
                this.openDP2=true
            },
            openDB3()
            {
                this.openDP3=true
            },
            openDB4()
            {
                this.openDP4=true
            },
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
                                        terminal_TS : entry.terminal_TS,

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
            go_to_grade()
            {
                window.location.href =('/grade/'+this.order_id)
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
                objToPush['adres_pogruzki_TS'] = this.ad_pogruzki_arr_temp;
                objToPush['ob_ves_TS'] = this.ob_ves_TS;
                objToPush['ob_ob_TS'] = this.ob_ob_TS;
                objToPush['adres_vygr_TS'] = this.ad_vygruz_arr_temp;
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
                            adres_pogruzki_TS : this.ad_pogruzki_arr_temp,
                            ob_ves_TS : this.ob_ves_TS,
                            ob_ob_TS : this.ob_ob_TS,
                            adres_vygr_TS : this.ad_vygruz_arr_temp,
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
                this.ad_pogruzki_arr_temp=[];
                this.ad_vygruz_arr_temp=[];
                this.ob_ves_TS='';
                this.ob_ob_TS='';
                this.adres_vygr_TS='';
                this.kommentari_TS='';
                this.checked2='';
                this.terminal_TS='';
                this.add_ts='';
                this.add_new_ts=false;
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
                    this.spisokTSarr[this.edit_number]['adres_pogruzki_TS']=this.ad_pogruzki_arr_temp;
                    this.spisokTSarr[this.edit_number]['ob_ves_TS']=this.ob_ves_TS;
                    this.spisokTSarr[this.edit_number]['ob_ob_TS']=this.ob_ob_TS;
                    this.spisokTSarr[this.edit_number]['adres_vygr_TS']=this.ad_vygruz_arr_temp;
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
                            adres_pogruzki_TS : this.ad_pogruzki_arr_temp,
                            ob_ves_TS : this.ob_ves_TS,
                            ob_ob_TS : this.ob_ob_TS,
                            adres_vygr_TS : this.ad_vygruz_arr_temp,
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
                    this.add_ts='';
                    this.ad_pogruzki_arr_temp=[];
                    this.ad_vygruz_arr_temp=[];
                }
            },
            editTs(key)
            {
                this.add_new_ts=false;
                this.edit_number=key;
                this.edit_flag=true;
                this.add_ts=key;
                this.id_ts=this.spisokTSarr[key]['id_ts'];
                this.vid_TS=this.spisokTSarr[key]['vid_TS'];
                this.stavka_TS=this.spisokTSarr[key]['stavka_TS'];
                this.stavka_kp_TS=this.spisokTSarr[key]['stavka_kp_TS'];
                this.kol_gruz_TS=this.spisokTSarr[key]['kol_gruz_TS'];
                this.kol_TS_TS=this.spisokTSarr[key]['kol_TS_TS'];
                this.rasstojanie_TS=this.spisokTSarr[key]['rasstojanie_TS'];

             //   this.adres_pogruzki_TS=this.spisokTSarr[key]['adres_pogruzki_TS'];
                this.ad_pogruzki_arr_temp=this.spisokTSarr[key]['adres_pogruzki_TS'];
                if(this.spisokTSarr[key]['adres_pogruzki_TS'].length==0)
                {
                    let objToPush= {};
                    objToPush['adres_pogruzki'] ='';
                    this.ad_pogruzki_arr_temp.push(objToPush);
                }
                this.ob_ves_TS=this.spisokTSarr[key]['ob_ves_TS'];
                this.ob_ob_TS=this.spisokTSarr[key]['ob_ob_TS'];
              //  this.adres_vygr_TS=this.spisokTSarr[key]['adres_vygr_TS'];
                this.ad_vygruz_arr_temp=this.spisokTSarr[key]['adres_vygr_TS'];
                if(this.spisokTSarr[key]['adres_vygr_TS'].length==0)
                {
                    let objToPush1= {};
                    objToPush1['adres_pogruzki'] ='';
                    this.ad_vygruz_arr_temp.push(objToPush1);
                }
                this.kommentari_TS=this.spisokTSarr[key]['kommentari_TS'];
                this.checked2=this.spisokTSarr[key]['checked2'];
                this.terminal_TS=this.spisokTSarr[key]['terminal_TS'];
            },
            deleteTs()
            {
                if(this.edit_flag==false)
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
                this.add_ts='';
                this.add_new_ts=false;
                    this.ad_vygruz_arr_temp=[];
                    this.ad_pogruzki_arr_temp=[];
                }
                else
                {
                    axios
                        .post('/delete_TS',{
                            id_ts:this.id_ts,
                            order_id:this.order_id,
                        })
                    this.spisokTSarr.splice(this.edit_number, 1);
                    this.ad_vygruz_arr_temp=[];
                    this.ad_pogruzki_arr_temp=[];
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
                    this.add_ts='';
                    this.add_new_ts=false;
                }
            },
            add_ts_func()
            {
                this.add_ts='';
                if(!this.add_new_ts)
                {
                    this.add_new_ts=!this.add_new_ts;
                }
                if(this.add_new_ts)
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
                    this.ad_pogruzki_arr_temp=[];
                    this.ad_vygruz_arr_temp=[];
                    let objToPush= {};
                    objToPush['adres_pogruzki'] ='';
                    this.ad_pogruzki_arr_temp.push(objToPush);
                    let objToPush1= {};
                    objToPush1['adres_pogruzki'] ='';
                    this.ad_vygruz_arr_temp.push(objToPush1);

                if(this.edit_flag==false) {
                    //проверка, были ли вообще хоть одно ТС
                    if (this.spisokTSarr.length == 0) {
                        this.id_ts = 0;
                    } else {
                        let last_id_ts = this.spisokTSarr.slice(-1);
                        this.id_ts = Number(last_id_ts[0]['id_ts']) + Number(1);
                    }
                }
                }
                setTimeout(() => {
                    this.scroll_to_end();
                }, 200);

            },
            scroll_to_end()
            {
                let block = document.getElementsByClassName("right_white_block");
                let offsetPosition = block[0].scrollHeight;
                window.scrollBy({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            },
            delete_adres_pogr(key)
            {
                this.ad_pogruzki_arr_temp.splice(key,1)
            },
            delete_adres_vygruz(key)
            {
                this.ad_vygruz_arr_temp.splice(key,1)
            },
            add_empty_adres_pogr_to_old(elem1, key)
            {
                console.log(key);
                let objToPush= {};
                objToPush['adres_pogruzki'] ='';
                console.log(this.spisokTSarr[key].adres_pogruzki_TS.length)
                this.spisokTSarr[key].adres_pogruzki_TS.push(objToPush);
              //  console.log(this.spisokTSarr)
            },
            add_empty_adres_pogr()
            {
                let objToPush= {};
                objToPush['adres_pogruzki'] ='';
                this.ad_pogruzki_arr_temp.push(objToPush);
            },
            add_empty_adres_vygruz()
            {
                let objToPush= {};
                objToPush['adres_pogruzki'] ='';
                this.ad_vygruz_arr_temp.push(objToPush);
            },
            add_empty_terminal()
            {

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
            if(document.getElementsByClassName('vdp-datepicker__calendar')[3].style.display !== 'none')
            {
                 this.$refs.startDatePicker1.showCalendar();
            }
            document.getElementsByClassName('vdp-datepicker__calendar')[3].style.display = 'none';

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
                return moment(date).format('D MM YYYY');
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
               return moment(date).format('D MM YYYY');
            },
            addFiles(){
                this.$refs.files.click();
            },
            DownloadFiles()
            {
                axios
                    .post('/download_xlsx_orders',{
                        id:this.order_id,
                    })
                    .then(response => {
                        window.location.assign('/get_xlsx_file/images/orders_xlsx/'+response.data.file) ;

                    })
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

