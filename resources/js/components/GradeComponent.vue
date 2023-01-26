<template>
    <div class="container">
        <div class="row main_grade_div">
            <div class="col-12 row grade_head_row " >
                <input hidden="true" type="file" id="files" ref="files"  v-on:change="handleFilesUpload()"/>

                <div  class="col-6 orders_create_title">
                    Заявки: Оценка > №{{ order_id }}
                </div>
                <div  class="col-6 row justify-content-end trio_but">
                    <div class="col add_ts_button2 text-center" v-on:click="save_TS()">Сохранить</div>
                    <div class="col add_ts_button2 text-center" v-on:click="save_TS()">На проверку</div>
                    <div class="col add_ts_button3 text-center" v-on:click="deleteTs()">Отменить</div>
                </div>
            </div>
            <div class="col-12 row grade_underline"></div>
            <div class="col-12 row">
<!--               маленькая левая колонка-->
                <div class="col-2 grade_left_column">
                    <div class="offset-1 col-11 row grade_left_column_text_main">
                        <div class="col-6 grade_left_column_text_first">Номер заявки:</div>
                        <div class="col-6 grade_left_column_text_sec">{{ order_id }}</div>
                    </div>
                    <div class="offset-1 col-11 row grade_left_underline"></div>
                    <div class="offset-1 col-11 row grade_left_column_text_main">
                        <div class="col-6 grade_left_column_text_first">Дата внесения:</div>
                        <div class="col-6 grade_left_column_text_sec">{{ data_vneseniya }}</div>
                    </div>
                    <div class="offset-1 col-11 row grade_left_underline"></div>
                    <div class="offset-1 col-11 row grade_left_column_text_main">
                        <div class="col-12 grade_left_column_text_first">Адрес погрузки 1:</div>
                        <div class="col-12 grade_left_column_text_sec" v-for="(gruz,key1) in gruzootpravitel_arr"  v-if="gruz['id']==adres_pogruzke">{{ gruz.nazvanie }}</div>
                    </div>
                    <div class="offset-1 col-11 row grade_left_underline"></div>
                    <div class="offset-1 col-11 row grade_left_column_text_main">
                        <div class="col-12 grade_left_column_text_first">Адрес выгрузки 1:</div>
                        <div class="col-12 grade_left_column_text_sec" v-for="(gruz,key1) in gruzootpravitel_arr" v-if="gruz['id']==adres_vygruski">{{ gruz.nazvanie }}</div>
                    </div>
                    <div class="offset-1 col-11 row grade_left_underline"></div>
                    <div class="offset-1 col-11 row grade_left_column_text_main">
                        <div class="col-12 grade_left_column_text_first">Количество грузомест:</div>
                        <div class="col-12 grade_left_column_text_sec"><span>Вывезли {{ gruzomesta_big }}</span><span class="grade_red_text">Осталось {{ ostalos }}</span></div>
                    </div>
                    <div class="offset-1 col-11 row grade_left_underline"></div>
                    <div class="offset-1 col-11 row grade_left_column_text_main">
                        <div class="col-12 grade_left_column_text_first">Расстояние, км:</div>
                        <div class="col-12 grade_left_column_text_sec">{{ rasstojanie }} км</div>
                    </div>
                    <div class="offset-1 col-11 row ">
                    <hr noshade class="col-10  hr_line d-flex justify-content-center align-items-center">
                    <iconify-icon class="col-2 arrow_gray_grade d-flex justify-content-center align-items-center" icon="ant-design:arrow-right-outlined" style="color: #c4c4c4;" width="20" height="20"></iconify-icon>
                    </div>

                    <div class="offset-1 col-11 row grade_left_column_text_main no_margin_top" >
                        <div class="col-12 grade_left_column_text_first">Список ТС:</div>

                       <span v-for="(elem,key) in spisokTSarr" class="show_arrow_flag" v-on:click="add_to_right_column(key)">
                        <div  class="col-12 row spisok_ts_grade_left myDIV">
                            <div class="col-1 no_padding_right no_padding_left" >
                                <iconify-icon  class="truck_grade no_padding_right no_padding_left" icon="carbon:delivery-truck" width="20" height="20"></iconify-icon>
                            </div>
                        <div class="col-8 grade_left_column_text_sec">{{ elem.kol_gruz_TS }} мест,{{ elem.stavka_TS }} руб</div>
                            <span class="col-2 hide">
                                <div class="hide">
                                <iconify-icon  class="" icon="ant-design:arrow-right-outlined" style="color: #c4c4c4;" width="20" height="20"></iconify-icon>
                                </div>
                            </span>
                        </div>
                        <div class="offset-1 col-11 row grade_left_underline"></div>
                        </span>
                    </div>

                    <div class="offset-1 col-11 row grade_left_column_text_main" v-for="(elem,key) in na_terminale_arr" >
                        <div class="col-6 grade_left_column_text_first no_padding_right">На терминале:</div>
                        <div class="col-4 grade_left_column_text_sec  grade_left_column_text_sec_1 no_padding_left">{{ elem.kol_gruz_TS}} мест</div>
                        <iconify-icon class="col-2 hide d-flex justify-content-center align-items-center" icon="ant-design:arrow-right-outlined" style="color: #c4c4c4;" width="20" height="20"></iconify-icon>
                        <div class="col-12 grade_left_column_text_sec">{{ elem.terminal_name }}</div>
                    </div>
                    <div class="offset-1 col-11 row grade_left_underline"></div>
                    <div class="offset-1 col-11 row grade_left_column_text_main">
                        <div class="col-12 grade_left_column_text_first">Общий бюджет:</div>
                        <div class="col-12 grade_left_column_text_sec">{{ ob_budzet_left }} р.</div>
                    </div>
                    <div class="offset-1 col-11 row grade_left_underline"></div>
                    <div class="offset-1 col-11 row grade_left_column_text_main">
                        <div class="col-12 grade_left_column_text_first">Цена контракта:</div>
                        <div class="col-12 grade_left_column_text_sec">{{ cena_kontrakta }} р.</div>
                    </div>
                </div>
<!--конец маленькой левой колонки-->


                <div class="col-10">
<!--                    модалочка-->
                    <modal-pogruzka-component :get_gruzootpravitel_list='get_gruzootpravitel_list' :select_gruzootpravitel='select_gruzootpravitel'></modal-pogruzka-component>
<!--                    конец модалочки-->



<!--                    начало правой строки основного вида-->
                    <div class="col-12 row">
                        <div  class="col-6 grade_create_title">
                            Список ТС:
                        </div>
                        <div  class="col-6 row justify-content-end trio_but">
                            <div class="col add_ts_button5 text-center" v-on:click="get_finall_doc_pdf('1')">ТН</div>
                            <input hidden="true" type="file" id="files_doc" ref="files_doc"  v-on:change="handleFilesUploadDoc()"/>

                            <div class="col add_ts_button5 add_ts_button5_1 text-center justify-content-center" v-on:click="get_finall_doc_pdf('2')">Доверенность</div>
                            <div class="col add_ts_button5 text-center" v-on:click="get_finall_doc_pdf('3')">Заявка</div>
                        </div>
                    </div>

                    <div class="col-12 back_grade_right">
                        <div class="container no_padding_left no_padding_right">
                    <div class="col-12 row">
                        <div class="col-2 grade_header_col">
                            Вид ТС
                        </div>
                        <div class="col-2 grade_header_col">
                            Перевозчик
                        </div>
                        <div class="col-2 grade_header_col">
                            Маршрут
                        </div>
                        <div class="col-2 grade_header_col">
                            Дата и время погрузки
                        </div>
                        <div class="col-2 grade_header_col">
                            Дата и время выгрузки
                        </div>
                        <div class="col-2 grade_header_col">
                            Ставка
                        </div>
                    </div>
                    </div>
<!--                        перебор в шапке-->
                        <div class="col-12 row create_ord_underline_1"></div>
                        <span v-for="(elem,key) in spisokTShead" class="ts_grade_margin">
                        <div class="container  no_padding_left no_padding_right " v-on:click="click_on_right_col(elem)" v-bind:class="{ ts_podsvet: elem.id_ts===right_current_TS }">
                             <div class="col-12 row no_padding_left no_padding_right">
                            <div class="col-2 grade_header_col">
                                <iconify-icon class="truck_grade no_padding_right no_padding_left" icon="carbon:delivery-truck" width="20" height="20"></iconify-icon>
                                <span class=" grade_right_col_text">{{ elem.kol_gruz_TS }} мест</span>
                            </div>

                            <div class="col-2 grade_header_col">
                                {{ elem.perevozchik }}
                            </div>
                            <div class="col-2 grade_header_col">
                                Прямой
                            </div>
                            <div class="col-2 grade_header_col">
                                {{ data_pogruzki }}
                            </div>
                            <div class="col-2 grade_header_col">
                                {{ data_dostavki }}
                            </div>
                            <div class="col-2 grade_header_col">
                                {{elem.ob_summa}}р .
                            </div>
                        </div>
                       </div>
                        <div class="col-12 row create_ord_underline_1"></div>


                        <!--                        правая колонка низ начало-->

                        <div v-for="(elem1,key) in spisokTShead" v-if="(right_col_down_show)&&(elem.id_ts==right_current_TS)" class="no_margin_left no_margin_right no_padding_left no_padding_right ">
                            <div class="col-12 row"    v-if="elem1.id_ts==right_current_TS">
                                <div class="col-3">
                                    <div class="col-12 grade_bold_dark grade_marg_bot grade_marg_bot">Данные о перевозчике</div>
                                    <div  class="create_orders_date_title_1 lit_marg_grade">Тип ТС:</div>
                                    <select  @blur="update_one_data(elem1,'vid_TS')" class="create_orders_date_title_int_1 grade_marg_bot" v-model="elem.vid_TS">
                                        <option v-bind:value="0" class="sel_cust">Автоперевозка</option>
                                        <option v-bind:value="1" class="sel_cust">Самолётом</option>
                                        <option v-bind:value="2" class="sel_cust">Кораблём</option>
                                        <option v-bind:value="3" class="sel_cust">На верблюде</option>
                                    </select>
                                    <div  class="create_orders_date_title_1 lit_marg_grade">Перевозчик:</div>
                                    <input @blur="update_one_data(elem1,'perevozchik')" class="border_input grade_marg_bot" v-model="elem1.perevozchik"  />
                                    <div  class="create_orders_date_title_1 lit_marg_grade">Водитель:</div>
                                    <input @blur="update_one_data(elem1,'voditel')" class="border_input grade_marg_bot" v-model="elem1.voditel"  />

                                    <div class="col-12 row grade_marg_bot">
                                    <div class="col-6 min_ts min_ts_marg ">
                                        <div  class="create_orders_date_title_1 lit_marg_grade">Номер ТС:</div>
                                        <select  @blur="update_one_data(elem1,'nomer_TS')" class="create_orders_date_title_int_1 inp_max_width" v-model="elem1.nomer_TS">
                                            <option v-bind:value="0" class="sel_cust">Константин Константинович</option>
                                            <option v-bind:value="1" class="sel_cust">Иван Иванович</option>
                                            <option v-bind:value="2" class="sel_cust">Джек Воробей</option>
                                            <option v-bind:value="3" class="sel_cust">Путин В.В.</option>
                                        </select>
                                        <span class="add_button_grade ">Добавить</span>
                                    </div>
                                    <div class="col-6 min_ts">
                                        <div  class="create_orders_date_title_1 lit_marg_grade">Номер ПП:</div>
                                        <select  @blur="update_one_data(elem1,'nomer_PP')" class="create_orders_date_title_int_1 inp_max_width" v-model="elem1.nomer_PP">
                                            <option v-bind:value="0" class="sel_cust">Константин Константинович</option>
                                            <option v-bind:value="1" class="sel_cust">Иван Иванович</option>
                                            <option v-bind:value="2" class="sel_cust">Джек Воробей</option>
                                            <option v-bind:value="3" class="sel_cust">Путин В.В.</option>
                                        </select>
                                        <span class="add_button_grade ">Добавить</span>
                                    </div>
                                    </div>
                                        <div class="col-12 grade_bold_dark grade_marg_bot">Информация о грузе</div>
                                        <div class="col-6 little_title_grade_margin">
                                            <div class="little_title_grade ">
                                                Кол-во грузомест
                                            </div>
                                            <input @blur="update_one_data(elem1,'kol_gruz_TS')" class="border_input" v-model="elem1.kol_gruz_TS"  />
                                        </div>
                                        <div class="col-6">
                                            <div class="little_title_grade">
                                                Расстояние, км
                                            </div>
                                            <input @blur="update_one_data(elem1,'rasstojanie_TS')" class="border_input" v-model="elem1.rasstojanie_TS"  />
                                        </div>
                                </div>

<!--                                вторая колонка-->
                                <div class="col-3">
<!--                                        {{ adres_pogr }}-->
                                    <div class="col-12 grade_bold_dark grade_marg_bot grade_marg_bot">Адрес и дата погрузки</div>
                                    <span v-for="(adres_pogr,key1) in elem1.adres_pogruzki_TS">
                                    <div class="col-12 row">
                                        <div class="col-6 little_title_grade pogr_marg_grade">
                                            Адрес погрузки {{ key1 + 1 }}
                                        </div>
                                        <div class="col-6 row">
                                            <span v-on:click="add_new_adres_pogruzka(key,1)" class="col add_button_grade no_wrap_text">Новый</span>
                                            <span class="col add_button_grade no_wrap_text" v-b-modal.modal-xl variant="primary" v-on:click="select_temp_var(key,'pogruzka',key1,elem1,adres_pogr.id_pogruzka,1,adres_pogr.adres_pogruzki,'adres_pogruzki')">Добавить</span>
                                        </div>
                                    </div>
                                        <select @blur="update_one_data_pogruzka(elem1,adres_pogr.id_pogruzka,1,adres_pogr.adres_pogruzki,'adres_pogruzki')" class="cr_ord_inp_n_0" v-model="adres_pogr.adres_pogruzki">
                                            <option v-for="(gruzootpravitel) in gruzootpravitel_arr" v-bind:value=gruzootpravitel.id  class="sel_cust">{{ gruzootpravitel.nazvanie }}</option>
                                        </select>
                                    <div class="col-12 row">
                                        <div class="col-6 date_width">
                                            <div class="little_title_grade">Дата</div>

<!--                        ДАТА ЗАГРУЗКИ-->
                        <input @click="openDPpogr(adres_pogr.id_pogruzka,1,1)" class="cr_ord_inp_n_2 border_input" v-model="adres_pogr.date_ts"  />
                        <date-picker   v-model="adres_pogr.date_ts" valueType="format" type="date"
                        format="DD.MM.YYYY" :open.sync=adres_pogr.show_DP_date @change="update_one_data_pogruzka(elem1,adres_pogr.id_pogruzka,1,adres_pogr.date_ts,'date_ts')"></date-picker>

                                        </div>
                                        <div class="col-6">
                                            <div class="little_title_grade">Время</div>

<!--                                                    <input @blur="update_one_data_pogruzka(elem,adres_pogr.id_pogruzka,1,adres_pogr.time_ts,'time_ts')"-->
<!--                                                        class="border_input inp_time" v-model="adres_pogr.time_ts"  />-->
<!--                        ВРЕМЯ ПОГРУЗКИ-->
                        <input @click="openDPpogr(adres_pogr.id_pogruzka,2,1)" class="cr_ord_inp_n_2 border_input" v-model="adres_pogr.time_ts"  />
                        <date-picker   v-model="adres_pogr.time_ts" valueType="format" type="time"
                        format=" H:m" :open.sync=adres_pogr.show_DP_time @change="update_one_data_pogruzka(elem1,adres_pogr.id_pogruzka,1,adres_pogr.time_ts,'time_ts')"></date-picker>

                                        </div>
                                    </div>
                                    <div class="col-12 row doc_add_row_1">
                                        <div class="col-6 add_button_grade no_wrap_text">{{ adres_pogr.doc_name }}
                                            <input hidden="true" v-model="adres_pogr.doc_name" >
                                            <span v-if="adres_pogr.doc_name" v-on:click="delete_file_grade(adres_pogr.id_pogruzka,1)">
                                            <iconify-icon   icon="ci:off-close" style="color: #c4c4c4;" width="20" height="20"></iconify-icon>
                                            </span>
                                        </div>
                                    </div>
<!--                                                        <form @submit="formSubmit" enctype="multipart/form-data">-->
<!--                    <input type="file" class="form-control" v-on:change="imgPreview" name="avatar">-->
<!--                </form>-->
<!--                                    <input hidden="true" type="file" id="files" ref="files"/>-->
                                    <input hidden="true" type="file" :ref="files" v-on:change="handleFilesUpload()" />
                                    <div v-if="!adres_pogr.doc_name" class="col add_ts_button6 text-center" v-on:click="addFiles(adres_pogr.id_pogruzka,1)">Добавить файл</div>

                              </span>
                                </div>

<!--                                третья колонка-->
                                <div class="col-3">
                                    <div class="col-12 grade_bold_dark grade_marg_bot grade_marg_bot">Адрес и дата выгрузки</div>
                                    <span v-for="(adres_vygr,key2) in elem1.adres_vygr_TS">
                                        <div class="col-12 row">
                                        <div class="col-6 little_title_grade pogr_marg_grade">
                                            Адрес выгрузки {{ key2 + 1 }}
                                        </div>
                                        <div class="col-6 row">
                                            <span v-on:click="add_new_adres_pogruzka(key,2)" class="col add_button_grade no_wrap_text">Новый</span>
                                            <span class="col add_button_grade no_wrap_text" v-b-modal.modal-xl variant="primary" v-on:click="select_temp_var(key,'vygruzka',key2,elem1,adres_vygr.id_pogruzka,2,adres_vygr.adres_pogruzki,'adres_pogruzki')">Добавить</span>
                                        </div>
                                    </div>
                                         <select @blur="update_one_data_pogruzka(elem1,adres_vygr.id_pogruzka,2,adres_vygr.adres_pogruzki,'adres_pogruzki')" class="cr_ord_inp_n_0" v-model="adres_vygr.adres_pogruzki">
                                            <option v-for="(gruzootpravitel) in gruzootpravitel_arr" v-bind:value=gruzootpravitel.id  class="sel_cust">{{ gruzootpravitel.nazvanie }}</option>
                                        </select>
                                        <div class="col-12 row">
                                        <div class="col-6 date_width">
                                            <div class="little_title_grade">Дата</div>


<!--                                            <input @blur="update_one_data_pogruzka(elem,adres_vygr.id_pogruzka,2,adres_vygr.date_ts,'date_ts')"-->
<!--                                                   class="border_input inp_date" v-model="adres_vygr.date_ts"  />-->

                                            <input @click="openDPpogr(adres_vygr.id_pogruzka,1,2)" class="cr_ord_inp_n_2 border_input" v-model="adres_vygr.date_ts"  />
                        <date-picker   v-model="adres_vygr.date_ts" valueType="format" type="date"
                                     format="DD.MM.YYYY" :open.sync=adres_vygr.show_DP_date @change="update_one_data_pogruzka(elem1,adres_vygr.id_pogruzka,2,adres_vygr.date_ts,'date_ts')"></date-picker>


                                        </div>
                                        <div class="col-6">
                                            <div class="little_title_grade">Время</div>

<!--                                            <input @blur="update_one_data_pogruzka(elem,adres_vygr.id_pogruzka,2,adres_vygr.time_ts,'time_ts')"-->
<!--                                                   class="border_input inp_time" v-model="adres_vygr.time_ts"  />-->

                                            <input @click="openDPpogr(adres_vygr.id_pogruzka,2,2)" class="cr_ord_inp_n_2 border_input" v-model="adres_vygr.time_ts"  />
                        <date-picker   v-model="adres_vygr.time_ts" valueType="format" type="time"
                                     format=" H:m" :open.sync=adres_vygr.show_DP_time @change="update_one_data_pogruzka(elem,adres_vygr.id_pogruzka,2,adres_vygr.time_ts,'time_ts')"></date-picker>


                                        </div>
                                    </div>
                                    <div class="col-12 row doc_add_row_1">
                                        <div class="col-3 add_button_grade no_wrap_text">{{ adres_vygr.doc_name }}
                                    <span v-if="adres_vygr.doc_name" v-on:click="delete_file_grade(adres_vygr.id_pogruzka,2)">
                                            <iconify-icon   icon="ci:off-close" style="color: #c4c4c4;" width="20" height="20"></iconify-icon>
                                            </span>                                        </div>
                                    </div>
                                     <input hidden="true" type="file" :ref="files" v-on:change="handleFilesUpload()" />
                                    <div v-if="!adres_vygr.doc_name" class="col add_ts_button6 text-center" v-on:click="addFiles(adres_vygr.id_pogruzka,2)">Добавить файл</div>
                                    </span>

                                    <div class="col-12 row grade_underline"></div>
                                    <input type="checkbox" id="checkbox" @blur="update_one_data(elem1,'checked2')" v-model="elem1.checked2">
                                    <span class="head_font_grade">На терминале</span>
                                    <div v-if="elem1.checked2" class="col-12 row">
                                        <div class="col-12 little_title_grade">
                                            Терминал
                                        </div>
                                        <div class="col-12 row">
                                            <span class="col add_button_grade no_wrap_text">Новый</span>
                                            <span class="col add_button_grade no_wrap_text">Добавить</span>
                                        </div>
                                    </div>
                                    <select v-if="elem1.checked2" @blur="update_one_data(elem1,'terminal_TS')" class="col border_input grade_adr" v-model="elem1.terminal_TS">
                                        <option v-for="(elem2) in termList" v-bind:value=elem2.id  class="sel_cust">{{ elem2.name }}</option>
                                    </select>

                                </div>
<!--                                четвёртая колонка-->
                                <div class="col-3">
                                    <div class="col-12 grade_bold_dark grade_marg_bot grade_marg_bot">Ставка</div>
                                     <div class="col-12 row">
                                    <div class="col-8">
                                        <div class="little_title_grade">Сумма</div>
<!--                                        <input class="border_input inp_date" v-model="elem1.ob_summa"/>-->
                                        <input @blur="update_one_data(elem1,'stavka_summa')" class="border_input inp_date" v-model="elem1.stavka_summa"/>
                                        <span class="no_wrap"><input type="checkbox" id="checkbox" @blur="update_one_data(elem1,'NDS_check')" v-model="elem1.NDS_check">
                                           НДС
                                        </span>
                                    </div>
                                    <div class="col-4">
                                        <div class="little_title_grade">Предоплата,%</div>
                                        <input @blur="update_one_data(elem1,'predoplata')" class="border_input inp_time" v-model="elem1.predoplata"  />
                                    </div>
                                     </div>
                                    <div class="col-12 not_paid_marg">
                                        <span class="not_paid">Не оплачено:</span>
                                        <span class="grade_bold_dark_1">{{ elem1.ne_oplacheno }}р.</span>
                                    </div>
                                    <div class="col-12 little_title_grade">Оплата:</div>

                                    <div class="col-12 row" v-for="(sum,key2) in elem1.summa_list">
                                    <div class="col little_title_grade_1">
                                        Сумма {{ key2 +1 }}
                                    </div>
                                        <div class="col add_button_grade no_wrap_text" v-on:click="add_summ(key)">Добавить сумму</div>
                                     <div class="col-12">
                                        <input @blur="update_one_data_summa(elem1,sum.id_summa,'summa',sum.summa)"
                                               class=" border_input add_summ_grade_inp" v-model="sum.summa"  />
                                            <input @click="openDPsumma(key2)"
                                                   class=" border_input add_summ_grade_inp_1" v-model="sum.data"  />
                                         <date-picker   v-model="sum.data" valueType="format" type="time"
                                                        format=" H:m" :open.sync=sum.show_DP_date @change="update_one_data_summa(elem1,sum.id_summa,'data',sum.data)">
                                         </date-picker>
                                     </div>
                                    </div>

                                    <div class="col-12 grade_bold_dark grade_marg_bot grade_marg_bot">Договор-заявка:</div>
                                   <div class="col-12 row">
                                    <div class="col-6">
                                    <div class="little_title_grade">Подписанная</div>
                                        <input hidden="true" type="file" :ref="files" v-on:change="handleFilesUpload()" />
                                        <span v-if="elem1.podpisannaya_doc_name" class="add_button_grade no_wrap_text" v-on:click="delete_file_grade(0,3)">{{ elem1.podpisannaya_doc_name }}
                                            <iconify-icon  icon="ci:off-close" style="color: #c4c4c4;" width="20" height="20"></iconify-icon>
                                        </span>
                                        <div v-if="!elem1.podpisannaya_doc_name" class="col add_ts_button6 text-center" v-on:click="addFiles(0,3)">Загрузить</div>
                                    </div>
                                    <div class="col-6">
                                        <input hidden="true" type="file" :ref="files" v-on:change="handleFilesUpload()" />
                                        <div class="little_title_grade">Счёт</div>
                                        <div class="col-12 add_button_grade">
                                            <input hidden="true" type="file" :ref="files" v-on:change="handleFilesUpload()" />
                                            <span v-if="elem1.schet_doc_name" class="add_button_grade no_wrap_text" v-on:click="delete_file_grade(0,4)">{{ elem1.schet_doc_name }}
                                            <iconify-icon  icon="ci:off-close" style="color: #c4c4c4;" width="20" height="20"></iconify-icon>
                                        </span>
                                            <div v-else class="col add_ts_button6 text-center" v-on:click="addFiles(0,4)">Загрузить</div>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="col-12 row">
                                        <div class="col-6">
                                            <div class="little_title_grade">Счёт фактура или УПД</div>
                                            <input hidden="true" type="file" :ref="files" v-on:change="handleFilesUpload()" />
                                            <span v-if="elem1.faktura_doc_name" class="add_button_grade no_wrap_text" v-on:click="delete_file_grade(0,5)">{{ elem1.faktura_doc_name }}
                                            <iconify-icon  icon="ci:off-close" style="color: #c4c4c4;" width="20" height="20"></iconify-icon>
                                        </span>
                                            <div v-else class="col add_ts_button6 text-center" v-on:click="addFiles(0,5)">Загрузить</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="little_title_grade">Файлы</div>
                                            <div class="col add_ts_button9 text-center" v-on:click="download_all_doc_grade()">Скачать всё</div>
                                        </div>
                                    </div>
                                    <div class="col-12 row">
                                        <div class="col-12">
                                            <div class="little_title_grade col-12">Оригиналы</div>
                                                <input type="checkbox" id="checkbox" @blur="update_one_data(elem1,'predoplata')" v-model="elem1.TN_check">
                                                <span class="TH_grade">ТН</span>
                                            <span v-if="elem1.TN_doc_name" class="add_button_grade no_wrap_text" v-on:click="delete_file_grade(0,6)">{{ elem1.TN_doc_name }}
                                            <iconify-icon  icon="ci:off-close" style="color: #c4c4c4;" width="20" height="20"></iconify-icon>
                                            </span>
                                            <input  hidden="true" type="file" :ref="files" v-on:change="handleFilesUpload()" />

                                            <div v-if="!elem1.TN_doc_name" class="col add_ts_button6 text-center" v-on:click="addFiles(0,6)">Загрузить</div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!--                                конец четвёртой колонки -->
                        </div>
                        <!--                    правая колонка низ конец-->
                        </span>
                    </div>
                </div>
                <!--конец правой колонки-->



                </div>

            <!--                    нижняя строка-->

            <!--                    конец нижней строки-->

        </div>
        <div class="offset-1 col-10 row down_streak_grade">
            <div class="down_grade_text col-5">
                Общий бюджет: {{ ob_budzet_down }} рубля
            </div>
            <div class="down_grade_text col-4">
                Оплачено: {{ oplacheno }} рублей
            </div>
            <div class="down_grade_text col-3">
                Долг: {{ dolg }} рублей
            </div>
        </div>
        <iframe style="display:none;" name="hiddenIframe" id="hiddenIframe"></iframe>
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
        props: ['auth_user'],
        components: {
            datepicker,
            DatePicker
        },
        data() {
            return {
                order_id:'',
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
                na_terminale_arr:[],
                termList:[],
                ob_budzet_left:0,
                ob_budzet_down:0,
                show_arr_TS:[],
                //это общий список со всей информацией а не только для шапки
                spisokTShead:[],

                // правая колонка, переменные должны отличаться названием и будут иметь приставку right_
                right_col_down_show:false,
                //текущее ТС в работе
                right_current_TS:'',

                right_vid_TS:'',
                right_perevozchik:'',
                right_voditel:'',
                right_nomer_TS:'',
                right_nomer_PP:'',
                right_kol_gruz_TS:'',
                right_rasstojanie_TS:'',
                //закончил на второй колонке, узнать про адреса погрузки
                right_adres_pogruzki_TS:'',
                files:[],
                current_id_pogruzka:'',
                current_id_doc_type:'',
                openDP4:false,
                oplacheno:0,
                current_doc:'',
                gruzootpravitel_arr:[],

                add_gruzoot_key:'',
                add_gruzoot_pogr_vygr:'',
                add_gruzoot_key1:'',
                //временные параметры для динамического сохранения данных о погрузке
                p1:'',
                p2:'',
                p3:'',
                p4:'',
                p5:''


            }
        },
        mounted()
        {
            let adress=window.location.href;
            adress=(adress.split("/")[4])
            this.order_id=adress;
            this.get_terminal_list(this.termList);
            this.get_final_grades_data(this.spisokTShead);
            this.get_ob_budzet_down();
            this.get_gruzootpravitel_list(this.gruzootpravitel_arr)
        },

        computed: {

            dolg: function () {
                    return this.ob_budzet_down-this.oplacheno
                },

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
            spisokTSheadFlag()
            {
                //указываешь что должно поменяться
                for (let i = 0; i < this.spisokTShead.length; i++) {

                    if(this.right_current_TS==this.spisokTShead[i].id_ts)
                    {
                        return this.spisokTShead[i].summa_list;
                    }
                }
            },
            oplachenoFlag()
            {
                //указываешь что должно поменяться
                for (let i = 0; i < this.spisokTShead.length; i++) {

                    if(this.right_current_TS==this.spisokTShead[i].id_ts)
                    {
                        return this.spisokTShead[i].summa_list;
                    }
                }
            },
            ostalos: function () {
                let gruzomesta = 0;
                for(var i = 0; i < this.spisokTShead.length; i++)
                {
                    gruzomesta=(Number(gruzomesta)+Number(this.spisokTShead[i]['kol_gruz_TS']));
                    // console.log(gruzomesta)
                }
                return this.gruzomesta_big-gruzomesta;
            },
            neOplachenoFlagList()
            {
                //указываешь что должно поменяться
                for (let i = 0; i < this.spisokTShead.length; i++) {

                    if(this.right_current_TS==this.spisokTShead[i].id_ts)
                    {
                        return this.spisokTShead[i].summa_list;
                    }
                }
            },
            neOplachenoFlag()
            {
                //указываешь что должно поменяться
                for (let i = 0; i < this.spisokTShead.length; i++) {

                    if(this.right_current_TS==this.spisokTShead[i].id_ts)
                    {
                        return this.spisokTShead[i].stavka_summa;
                    }
                }
            }
        },
        watch:{

            neOplachenoFlagList:{
                deep: true,
                handler(){
                    for (let i = 0; i < this.spisokTShead.length; i++) {
                        // if(this.right_current_TS==this.spisokTShead[i].id_ts)
                        // {
                        this.spisokTShead[i].ne_oplacheno=0;
                        this.spisokTShead[i].ob_summa=0;
                        for (let j = 0; j < this.spisokTShead[i].summa_list.length; j++) {
                            let sum=Number(this.spisokTShead[i].summa_list[j].summa);
                            this.spisokTShead[i].ob_summa=this.spisokTShead[i].ob_summa+sum;
                        }
                        this.spisokTShead[i].ne_oplacheno=(this.spisokTShead[i].stavka_summa-this.spisokTShead[i].ob_summa)*-1
                        // }
                    }
                }
            },
            neOplachenoFlag:{
                deep: true,
                handler(){
                    for (let i = 0; i < this.spisokTShead.length; i++) {
                        // if(this.right_current_TS==this.spisokTShead[i].id_ts)
                        // {
                        this.spisokTShead[i].ne_oplacheno=0;
                        this.spisokTShead[i].ob_summa=0;
                        for (let j = 0; j < this.spisokTShead[i].summa_list.length; j++) {
                            let sum=Number(this.spisokTShead[i].summa_list[j].summa);
                            this.spisokTShead[i].ob_summa=this.spisokTShead[i].ob_summa+sum;
                        }
                        this.spisokTShead[i].ne_oplacheno=(this.spisokTShead[i].stavka_summa-this.spisokTShead[i].ob_summa)*-1
                        // }
                    }
                }
            },
            spisokTSheadFlag: {
                 deep: true,
                handler(){
                    for (let i = 0; i < this.spisokTShead.length; i++) {
                        // if(this.right_current_TS==this.spisokTShead[i].id_ts)
                        // {
                            this.spisokTShead[i].ob_summa=0;
                            for (let j = 0; j < this.spisokTShead[i].summa_list.length; j++) {
                                let sum=Number(this.spisokTShead[i].summa_list[j].summa);
                                this.spisokTShead[i].ob_summa=this.spisokTShead[i].ob_summa+sum;
                            }
                        // }
                    }
                }
            },

            oplachenoFlag: {
                deep: true,
                handler(){
                this.oplacheno=0;
                for (let i = 0; i < this.spisokTShead.length; i++) {
                    if(!isNaN(this.spisokTShead[i].ob_summa ))
                    {
                        this.oplacheno=Number(this.oplacheno)+Number(this.spisokTShead[i].ob_summa);
                    }

                }

                    },
            },


        },

        methods: {
            deleteTs()
            {
             console.log(this.spisokTShead)
            },
            select_temp_var(key,pogr_vygr,key1,p1,p2,p3,p4,p5)
            {
                //key - номер в массиве spisokTShead
                //pogr_vygr - погрузка или выгрузка в массиве spisokTShead
                //key1 - номер в массиве погрузки или выгрузки
              //  console.log('pogr_or_vygr')
              //  console.log(pogr_or_vygr)
                this.add_gruzoot_key=key
                this.add_gruzoot_pogr_vygr=pogr_vygr
                this.add_gruzoot_key1=key1
                this.p1=p1
                this.p2=p2
                this.p3=p3
                this.p4=p4
                this.p5=p5
            },
            select_gruzootpravitel()
            {
                if(this.add_gruzoot_pogr_vygr=='pogruzka')
                {
                    axios
                        .post('/select_gruzootpravitel',{
                        })
                        .then(({ data }) => (
                            this.spisokTShead[this.add_gruzoot_key]['adres_pogruzki_TS'][this.add_gruzoot_key1]['adres_pogruzki']=data.gruzootpravitel.id,
                            this.update_one_data_pogruzka(this.p1,this.p2,this.p3,data.gruzootpravitel.id,this.p5)
                            )

                        );
                }

                if(this.add_gruzoot_pogr_vygr=='vygruzka')
                {
                    axios
                        .post('/select_gruzootpravitel',{
                        })
                        .then(({ data }) => (
                                this.spisokTShead[this.add_gruzoot_key]['adres_vygr_TS'][this.add_gruzoot_key1]['adres_pogruzki']=data.gruzootpravitel.id,
                                this.update_one_data_pogruzka(this.p1,this.p2,this.p3,data.gruzootpravitel.id,this.p5)
                            )

                        );
                }
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
            get_finall_doc_pdf(doc_type)
            {
                this.current_doc=doc_type
                // window.location.href = '/get_finall_doc_pdf/'+doc_type;
                axios
                    .post('/get_finall_doc_pdf',{
                         doc_type:this.current_doc,
                         id:this.order_id,

                    })
                    .then(response => {
                        // console.log(response.data.file)
                        window.location.assign('/get_finall_doc_pdf_file/templates/'+response.data.file) ;

                    })

            },
            set_doc()
            {
                axios
                    .post('/set_doc',{
                    })
            },
            store_doc()
            {
                this.$refs.files_doc.click();
            },
            handleFilesUploadDoc(){
                let flag = 0;
                let uploadedFiles = this.$refs.files_doc.files;
                // if ( /\.(xlsx?)$/i.test( uploadedFiles[0].name ) ) {
                    let reg ='';
                    reg=(uploadedFiles[0].name.match(/([A-Za-zа-яА-Я0-9Ёё\W]+)\.(txt?)/))
                    this.nomenklatura=reg[1];
                    this.nomenklatura=reg[0];
                    let formData = new FormData();
                    let file = uploadedFiles;
                    formData.append('file_xlsx', file[0]);
                    formData.append('file_name',this.nomenklatura);
                    formData.append('order_id',this.order_id);
                    formData.append('full_name',reg[0]);
                    axios.post( '/store_doc',
                        formData,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }
                    )
                // }
                // else
                // {
                //     alert('Не верный формат файла')
                // }
            },

            get_ob_budzet_down()
            {
                axios
                    .post('/get_ob_budzet_down',{
                        order_id:this.order_id,
                    })
                    .then(({ data }) => (
                          this.ob_budzet_down=data.count
                        )
                    );
            },
            openDPpogr(id_pogruzka,date_or_time,pogr_or_vygr)
            {
                if(pogr_or_vygr==1)
                {
                    if(date_or_time==1)
                    {
                        this.spisokTShead[this.right_current_TS].adres_pogruzki_TS[id_pogruzka].show_DP_date=true;
                    }
                    else
                    {
                        this.spisokTShead[this.right_current_TS].adres_pogruzki_TS[id_pogruzka].show_DP_time=true;
                    }
                }
                else
                {
                    if(date_or_time==1)
                    {
                        this.spisokTShead[this.right_current_TS].adres_vygr_TS[id_pogruzka].show_DP_date=true;
                    }
                    else
                    {
                        this.spisokTShead[this.right_current_TS].adres_vygr_TS[id_pogruzka].show_DP_time=true;
                    }
                }
            },
            openDPsumma(summa_numb_in_arr)
            {
                    this.spisokTShead[this.right_current_TS].summa_list[summa_numb_in_arr].show_DP_date=true
            },
            handleChange0()
            {
                console.log(this.data_kontrakta)
            },
            delete_file_grade(id_pogruzka, id_doc_type)
            {
                this.current_id_pogruzka=id_pogruzka;
                this.current_id_doc_type=id_doc_type;

                // for (let i = 0; i < this.spisokTShead[this.right_current_TS].adres_pogruzki_TS.length; i++) {
                //     if(this.current_id_pogruzka== this.spisokTShead[this.right_current_TS].adres_pogruzki_TS[i].id_pogruzka)
                //     {
                //         let temp=''
                //         this.spisokTShead[this.right_current_TS].adres_pogruzki_TS[i].doc_name=''
                //         if(this.right_col_down_show==true)
                //         {
                //             this.right_col_down_show=false;
                //         }
                //         this.right_col_down_show=true;
                //     }
                // }


                 axios.post( '/delete_file_grade',
                     {
                         id_ts:this.right_current_TS,
                         grade_id:this.order_id,
                         id_pogruzka:this.current_id_pogruzka,
                         id_doc_type:this.current_id_doc_type,

                     }
                 )
                     .then(response => {

                         if(response.data.id_doc_type=='1')
                         {

                             for (let i = 0; i < this.spisokTShead[this.right_current_TS].adres_pogruzki_TS.length; i++) {
                                 if(this.current_id_pogruzka== this.spisokTShead[this.right_current_TS].adres_pogruzki_TS[i].id_pogruzka)
                                 {

                                              this.spisokTShead[this.right_current_TS].adres_pogruzki_TS[i].doc_name=''
                                              if(this.right_col_down_show==true)
                                              {
                                                  this.right_col_down_show=false;
                                              }
                                              this.right_col_down_show=true;
                                 }
                             }
                         }

                         if(response.data.id_doc_type=='2')
                         {
                             for (let i = 0; i < this.spisokTShead[this.right_current_TS].adres_vygr_TS.length; i++) {
                                 if(this.current_id_pogruzka== this.spisokTShead[this.right_current_TS].adres_vygr_TS[i].id_pogruzka)
                                 {
                                     this.spisokTShead[this.right_current_TS].adres_vygr_TS[i].doc_name =''
                                     if (this.right_col_down_show == true) {
                                         this.right_col_down_show = false;
                                     }
                                     this.right_col_down_show = true;
                                 }
                             }
                         }

                         if(response.data.id_doc_type=='3')
                         {
                             this.spisokTShead[this.right_current_TS].podpisannaya_doc_name=''
                             if (this.right_col_down_show == true) {
                                 this.right_col_down_show = false;
                             }
                             this.right_col_down_show = true;
                         }
                         if(response.data.id_doc_type=='4')
                         {
                             this.spisokTShead[this.right_current_TS].schet_doc_name=''
                             if (this.right_col_down_show == true) {
                                 this.right_col_down_show = false;
                             }
                             this.right_col_down_show = true;
                         }
                         if(response.data.id_doc_type=='5')
                         {
                             this.spisokTShead[this.right_current_TS].faktura_doc_name=''
                             if (this.right_col_down_show == true) {
                                 this.right_col_down_show = false;
                             }
                             this.right_col_down_show = true;
                         }
                         if(response.data.id_doc_type=='6')
                         {
                             this.spisokTShead[this.right_current_TS].TN_doc_name=''
                             if (this.right_col_down_show == true) {
                                 this.right_col_down_show = false;
                             }
                             this.right_col_down_show = true;
                         }
                     })

            },
            addFiles(id_pogruzka, id_doc_type){
                 //console.log(this.$refs.files);
                // console.log(id_pogruzka);
                this.current_id_pogruzka=id_pogruzka;
                this.current_id_doc_type=id_doc_type;
                this.$refs.files.click();
            },
            handleFilesUpload(){

                console.log('Правый список')
                console.log(this.spisokTShead)


                let flag = 0;
                let uploadedFiles = this.$refs.files.files;

                    let reg ='';
               let full_name=(uploadedFiles[0].name.match(/([A-Za-zа-яА-Я0-9Ёё@ : , ' '' ; - _ = < > % # ~ `& !\W]+)/))
               let file_name=(uploadedFiles[0].name.match(/([A-Za-zа-яА-Я0-9Ёё@ : , ' '' ; - _ = < > % # ~ `& !\W]+)\./))
               let extension=(uploadedFiles[0].name.slice((Math.max(0, (uploadedFiles[0].name.lastIndexOf(".")) || Infinity) + 1)));
                    let formData = new FormData();
                    let file = uploadedFiles;
                    formData.append('file', file[0]);
                    formData.append('file_name',file_name[1]);
                    formData.append('grade_id',this.order_id);
                    formData.append('id_ts',this.right_current_TS);
                    formData.append('id_pogruzka',this.current_id_pogruzka);
                    formData.append('id_doc_type',this.current_id_doc_type);
                    formData.append('full_name',full_name[0]);
                    formData.append('extension',extension);

                // console.log(reg)
                //     console.log(file[0])
                //     console.log(filename[1])
                //     console.log(full_name[0])

                    axios.post( '/store_grade_file',
                        formData,

                        {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }
                    )
                        .then(response => {
                            if(response.data.id_doc_type==='1')
                            {

                                for (let i = 0; i < this.spisokTShead[this.right_current_TS].adres_pogruzki_TS.length; i++) {
                                    if(this.current_id_pogruzka== this.spisokTShead[this.right_current_TS].adres_pogruzki_TS[i].id_pogruzka)
                                    {
                                        console.log('current_pogruzka')
                                        console.log(i)

                                let temp=''
                                temp=this.spisokTShead[this.right_current_TS].adres_pogruzki_TS[i].adres_pogruzki
                                this.spisokTShead[this.right_current_TS].adres_pogruzki_TS[i].adres_pogruzki=''
                                 this.spisokTShead[this.right_current_TS].adres_pogruzki_TS[i].adres_pogruzki=temp
                                this.spisokTShead[this.right_current_TS].adres_pogruzki_TS[i].doc_name=response.data.name_doc
                                        if(this.right_col_down_show==true)
                                        {
                                            this.right_col_down_show=false;
                                        }
                                        this.right_col_down_show=true;
                                    }
                                }
                            }

                            if(response.data.id_doc_type==='2')
                            {
                                for (let i = 0; i < this.spisokTShead[this.right_current_TS].adres_vygr_TS.length; i++) {
                                    if(this.current_id_pogruzka== this.spisokTShead[this.right_current_TS].adres_vygr_TS[i].id_pogruzka)
                                    {
                                    let temp = ''
                                    temp = this.spisokTShead[this.right_current_TS].adres_vygr_TS[i].adres_pogruzki
                                    this.spisokTShead[this.right_current_TS].adres_vygr_TS[i].adres_pogruzki = ''
                                    this.spisokTShead[this.right_current_TS].adres_vygr_TS[i].adres_pogruzki = temp
                                    this.spisokTShead[this.right_current_TS].adres_vygr_TS[i].doc_name = response.data.name_doc
                                    if (this.right_col_down_show == true) {
                                        this.right_col_down_show = false;
                                    }
                                    this.right_col_down_show = true;
                                }
                            }
                                 }

                            if(response.data.id_doc_type==='3')
                            {
                                this.spisokTShead[this.right_current_TS].podpisannaya_doc_name=response.data.name_doc
                                if (this.right_col_down_show == true) {
                                    this.right_col_down_show = false;
                                }
                                this.right_col_down_show = true;
                            }
                            if(response.data.id_doc_type==='4')
                            {
                                this.spisokTShead[this.right_current_TS].schet_doc_name=response.data.name_doc
                                if (this.right_col_down_show == true) {
                                    this.right_col_down_show = false;
                                }
                                this.right_col_down_show = true;
                            }
                            if(response.data.id_doc_type==='5')
                            {
                                this.spisokTShead[this.right_current_TS].faktura_doc_name=response.data.name_doc
                                if (this.right_col_down_show == true) {
                                    this.right_col_down_show = false;
                                }
                                this.right_col_down_show = true;
                            }
                            if(response.data.id_doc_type==='6')
                            {
                                this.spisokTShead[this.right_current_TS].TN_doc_name=response.data.name_doc
                                if (this.right_col_down_show == true) {
                                    this.right_col_down_show = false;
                                }
                                this.right_col_down_show = true;
                            }
                        })


            },
            update_one_data_summa(elem,id_summa,name,data_to_up)
            {
                axios
                    .post('/update_one_data_summa',{
                        elem:elem,
                        id_summa:id_summa,
                        data_to_up:data_to_up,
                        name:name,
                        grade_id:this.order_id
                    })
            },
            update_one_data_pogruzka(elem,id_pogr,pogr_or_vygr,data_to_up,name)
            {
                axios
                    .post('/update_one_data_pogruzka',{
                        elem:elem,
                        id_pogr:id_pogr,
                        pogr_or_vygr:pogr_or_vygr,
                        data_to_up:data_to_up,
                        name:name,
                        grade_id:this.order_id
                    })
            },
            update_one_data(elem,name)
            {

                axios
                    .post('/update_one_data',{
                        elem:elem,
                        name:name,
                        grade_id:this.order_id
                    })
            },
            get_final_grades_data(spisokTShead)
            {

                axios
                    .post('/get_final_grades_data',{
                        grade_id:this.order_id
                    })
                    .then(({ data }) => (
                                data.grade_list.forEach(function(entry) {
                                    spisokTShead.push({
                                        grade_id:entry.grade_id,
                                        id_ts : entry.id_ts,
                                        vid_TS : entry.vid_TS,
                                        perevozchik : entry.perevozchik,
                                        voditel : entry.voditel,
                                        nomer_TS : entry.nomer_TS,
                                        nomer_PP : entry.nomer_PP,
                                        kol_gruz_TS : entry.kol_gruz_TS,
                                        rasstojanie_TS : entry.rasstojanie_TS,
                                        NDS_check : entry.NDS_check,
                                        predoplata : entry.predoplata,
                                        TN_check : entry.TN_check,
                                        checked2 : entry.checked2,
                                        terminal_TS : entry.terminal_TS,
                                        summa_list:entry.summa_list,
                                        adres_pogruzki_TS:entry.adres_pogruzki_TS,
                                        adres_vygr_TS:entry.adres_vygr_TS,
                                        podpisannaya_doc_name:entry.podpisannaya_doc_name,
                                        schet_doc_name:entry.schet_doc_name,
                                        faktura_doc_name:entry.faktura_doc_name,
                                        TN_doc_name:entry.TN_doc_name,
                                        ne_oplacheno:0,
                                        stavka_summa:entry.stavka_summa,
                                    });
                                })
                        )
                    )

            },
            add_pogruzka_grade()
            {

                let arr_to_DB=[];
                for (let i = 0; i < this.spisokTShead[this.right_current_TS].adres_pogruzki_TS.length; i++) {
                    arr_to_DB.push(this.spisokTShead[this.right_current_TS].adres_pogruzki_TS[i])
                }
                for (let i = 0; i < this.spisokTShead[this.right_current_TS].adres_vygr_TS.length; i++) {
                    arr_to_DB.push(this.spisokTShead[this.right_current_TS].adres_vygr_TS[i])
                }
                console.log('сохранение погрузки')
                console.log(arr_to_DB)
                axios
                    .post('/add_pogruzka_grade',{
                        arr_to_DB:arr_to_DB,
                    })
            },
            update_final_grade()
            {
                let in_arr_numb='';
                for (let i = 0; i < this.spisokTShead.length; i++) {

                    if(this.right_current_TS==this.spisokTShead[i].id_ts)
                    {
                        in_arr_numb=i;
                    }
                }
                axios
                    .post('/update_final_grade',{
                        grade_id:this.order_id,
                        id_ts:this.spisokTShead[in_arr_numb].id_ts,
                        vid_TS:this.spisokTShead[in_arr_numb].vid_TS,
                        perevozchik:this.spisokTShead[in_arr_numb].perevozchik,
                        voditel:this.spisokTShead[in_arr_numb].voditel,
                        nomer_TS:this.spisokTShead[in_arr_numb].nomer_TS,
                        nomer_PP:this.spisokTShead[in_arr_numb].nomer_PP,
                        kol_gruz_TS:this.spisokTShead[in_arr_numb].kol_gruz_TS,
                        rasstojanie_TS:this.spisokTShead[in_arr_numb].rasstojanie_TS,
                        ob_summa:this.spisokTShead[in_arr_numb].ob_summa,
                        NDS_check:this.spisokTShead[in_arr_numb].NDS_check,
                        predoplata:this.spisokTShead[in_arr_numb].predoplata,
                        TN_check:this.spisokTShead[in_arr_numb].TN_check,
                        checked2:this.spisokTShead[in_arr_numb].checked2,
                        terminal_TS:this.spisokTShead[in_arr_numb].terminal_TS,
                    })
            },
            add_to_right_column(key)
            {
                this.right_current_TS=this.spisokTShead.length;

                    let objToPush= {};
                    objToPush['id_ts'] = this.right_current_TS;
                    objToPush['kol_gruz_TS'] = this.spisokTSarr[key].kol_gruz_TS;
                    objToPush['stavka_TS'] = this.spisokTSarr[key].stavka_TS;
                    objToPush['vid_TS'] = this.spisokTSarr[key].vid_TS;
                    objToPush['rasstojanie_TS'] = this.spisokTSarr[key].rasstojanie_TS;

                    // objToPush['adres_pogruzki_TS'] = this.spisokTSarr[key].adres_pogruzki_TS;
                    objToPush['adres_pogruzki_TS'] = [];
                    objToPush['adres_vygr_TS'] = [];

                    objToPush['checked2'] = this.spisokTSarr[key].checked2;
                    objToPush['terminal_TS'] = this.spisokTSarr[key].terminal_TS;

                    objToPush['perevozchik'] = '';
                    objToPush['voditel'] = '';
                    objToPush['nomer_TS'] = '';
                    objToPush['nomer_PP'] = '';
                    objToPush['predoplata'] = '';
                    objToPush['summa_list'] = [];
                    //общая сумма для данного ТС
                    objToPush['ob_summa'] = 0;
                    objToPush['ne_oplacheno'] = 0;
                    objToPush['stavka_summa'] = 0;
                    objToPush['NDS_check'] = 0;

                    //это общий список со всей информацией а не только для шапки
                    this.spisokTShead.push(objToPush);

                    let objToPush1= {};
                    objToPush1['summa'] = '';
                    objToPush1['data'] = '';
                    //добавить дату в сумму
                    objToPush1['show_DP_date'] =false;
                    objToPush1['id_summa'] = 0;

                    objToPush1['podpisannaya_doc_name'] = '';
                    objToPush1['schet_doc_name'] = '';
                    objToPush1['faktura_doc_name'] = '';
                    objToPush1['TN_doc_name'] = '';

                  //  this.spisokTShead[key].summa_list.push(objToPush1);

                    //высчитываем куда добавлять
                    let leng = this.spisokTShead.length-1;
                    this.spisokTShead[leng].summa_list.push(objToPush1);



                //Сохраняем ТС на фронте
                let arr_lengt_TS=0
                if(this.spisokTShead.length!=0)
                {
                    arr_lengt_TS=this.spisokTShead.length-1;
                }
                if(this.spisokTSarr[key].adres_pogruzki_TS.length==0)
                {
                    let objToPush2= {};
                    // console.log(this.spisokTSarr[key].adres_pogruzki_TS[i].adres_pogruzki)
                    objToPush2['adres_pogruzki'] =' ';
                    objToPush2['id_ts'] = this.right_current_TS;
                    objToPush2['grade_id'] = this.order_id;
                    objToPush2['pogruzka_or_vygruzka'] = 1;
                    objToPush2['time_ts'] = '';
                    objToPush2['date_ts'] = '';
                    objToPush2['id_pogruzka'] =0;
                    objToPush2['doc_name'] ='';
                    objToPush2['show_DP_date'] =false;
                    objToPush2['show_DP_time'] =false;
                    this.spisokTShead[leng].adres_pogruzki_TS.push(objToPush2);
                }
                else
                {
                for (let i = 0; i < this.spisokTSarr[key].adres_pogruzki_TS.length; i++) {
                    let objToPush2= {};
                    // console.log(this.spisokTSarr[key].adres_pogruzki_TS[i].adres_pogruzki)
                    objToPush2['adres_pogruzki'] = this.spisokTSarr[key].adres_pogruzki_TS[i].adres_pogruzki;
                    objToPush2['id_ts'] = this.right_current_TS;
                    objToPush2['grade_id'] = this.spisokTSarr[key].adres_pogruzki_TS[i].order_id;
                    objToPush2['pogruzka_or_vygruzka'] = this.spisokTSarr[key].adres_pogruzki_TS[i].pogruzka_or_vygruzka;
                    objToPush2['time_ts'] = '';
                    objToPush2['date_ts'] = '';
                    objToPush2['id_pogruzka'] = i;
                    objToPush2['doc_name'] ='';
                    objToPush2['show_DP_date'] =false;
                    objToPush2['show_DP_time'] =false;
                    this.spisokTShead[leng].adres_pogruzki_TS.push(objToPush2);
                }
                }
                if(this.spisokTSarr[key].adres_vygr_TS.length==0)
                {
                    let objToPush2= {};
                    // console.log(this.spisokTSarr[key].adres_pogruzki_TS[i].adres_pogruzki)
                    objToPush2['adres_pogruzki'] =' ';
                    objToPush2['id_ts'] = this.right_current_TS;
                    objToPush2['grade_id'] = this.order_id;
                    objToPush2['pogruzka_or_vygruzka'] = 2;
                    objToPush2['time_ts'] = '';
                    objToPush2['date_ts'] = '';
                    objToPush2['id_pogruzka'] =0;
                    objToPush2['doc_name'] ='';
                    objToPush2['show_DP_date'] =false;
                    objToPush2['show_DP_time'] =false;
                    this.spisokTShead[leng].adres_vygr_TS.push(objToPush2);
                }
                else{
                for (let i = 0; i < this.spisokTSarr[key].adres_vygr_TS.length; i++) {
                    let objToPush2= {};
                    // console.log(this.spisokTSarr[key].adres_pogruzki_TS[i].adres_pogruzki)
                    objToPush2['adres_pogruzki'] = this.spisokTSarr[key].adres_vygr_TS[i].adres_pogruzki;
                    objToPush2['id_ts'] = this.right_current_TS;
                    objToPush2['grade_id'] = this.spisokTSarr[key].adres_vygr_TS[i].order_id;
                    objToPush2['pogruzka_or_vygruzka'] = this.spisokTSarr[key].adres_vygr_TS[i].pogruzka_or_vygruzka;
                    objToPush2['time_ts'] = '';
                    objToPush2['date_ts'] = '';
                    objToPush2['id_pogruzka'] = i;
                    objToPush2['doc_name'] ='';
                    objToPush2['show_DP_date'] =false;
                    objToPush2['show_DP_time'] =false;
                    this.spisokTShead[leng].adres_vygr_TS.push(objToPush2);
                }
                }

                //общее сохранение ТС
                  this.update_final_grade();
                //сохранение погрузки
                  this.add_pogruzka_grade();
                  //сохранение суммы
                  this.save_start_summa();
                console.log('Левый список')
                console.log(this.spisokTSarr)
                console.log('Правый список')
                console.log(this.spisokTShead)


                if(this.right_col_down_show==true)
                {
                    this.right_col_down_show=false;
                }
                this.right_col_down_show=true;

            },
            click_on_right_col(elem)
            {
                if(this.right_current_TS===elem.id_ts)
                {
                    this.right_col_down_show=false;
                    this.right_current_TS=''
                }
                else
                {
                    this.right_current_TS=elem.id_ts
                    if(this.right_col_down_show==true)
                    {
                        this.right_col_down_show=false;
                    }
                    this.right_col_down_show=true;
                }

            },
            add_new_adres_pogruzka(key,pogruzka_or_vygruzka)
            {
                    let objToPush2= {}
                    objToPush2['adres_pogruzki'] = '',
                    objToPush2['id_ts'] = this.right_current_TS,
                    objToPush2['grade_id'] = this.order_id,
                    objToPush2['pogruzka_or_vygruzka'] = pogruzka_or_vygruzka,
                    objToPush2['time_ts'] = '',
                    objToPush2['date_ts'] = '',
                    objToPush2['id_pogruzka'] = ''
                    objToPush2['show_DP_date'] = false
                    objToPush2['show_DP_time'] = false
                        if(pogruzka_or_vygruzka==1)
                        {
                            this.spisokTShead[key].adres_pogruzki_TS.push(objToPush2)
                            axios
                                .post('/add_new_adres_pogruzka',{
                                    id_ts:this.right_current_TS,
                                    grade_id:this.order_id,
                                    pogruzka_or_vygruzka:pogruzka_or_vygruzka
                                })
                                .then(({ data }) => (
                                    this.spisokTShead[key].adres_pogruzki_TS[this.spisokTShead[key].adres_pogruzki_TS.length-1].id_pogruzka=data.id_pogruzka
                                ))
                        }
                        else
                        {
                            this.spisokTShead[key].adres_vygr_TS.push(objToPush2)
                            axios
                                .post('/add_new_adres_pogruzka',{
                                    id_ts:this.right_current_TS,
                                    grade_id:this.order_id,
                                    pogruzka_or_vygruzka:pogruzka_or_vygruzka
                                })
                                .then(({ data }) => (
                                    this.spisokTShead[key].adres_vygr_TS[this.spisokTShead[key].adres_vygr_TS.length-1].id_pogruzka=data.id_pogruzka
                                ))
                        }


            },
            add_summ (key)
            {
                let objToPush1= {};
                objToPush1['summa'] = '';
                objToPush1['data'] = '';
                objToPush1['show_DP_date'] =false;
                // console.log(this.spisokTShead[key].summa_list[this.spisokTShead[key].summa_list.length-1].id_summa)
                objToPush1['id_summa'] =Number(this.spisokTShead[key].summa_list[this.spisokTShead[key].summa_list.length-1].id_summa)+Number(1);
                this.spisokTShead[key].summa_list.push(objToPush1);
                axios
                    .post('/add_summ',{
                        id_ts:this.right_current_TS,
                        grade_id:this.order_id,
                        id_summa:objToPush1['id_summa']
                    })
            },
            save_start_summa()
            {
                axios
                    .post('/save_start_summa',{
                        id_ts:this.right_current_TS,
                        grade_id:this.order_id
                    })
            },
            download_all_doc_grade (){
                window.location.href = '/download_all_doc_grade/'+this.order_id+'/'+this.right_current_TS;
                // console.log(this.spisokTShead)


            },
            //получаем стартовые данные
            get_start_data_grade(inp,TSinp)
            {
                axios
                    .post('/get_start_data_grade',{
                        order_id:this.order_id
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
                            }),
                        this.na_term_count()
                        )
                    )


            },
            na_term_count()
            {
                console.log(this.spisokTSarr)

                //берём список старых терминалов
                for (let i = 0; i < this.spisokTSarr.length; i++) {
                    //если поставлена галочка на терминале
                    if(this.spisokTSarr[i].checked2==1)
                    {
                        this.ob_budzet_left=Number(this.ob_budzet_left)+Number(this.spisokTSarr[i].stavka_TS)

                        let flag = false;
                        //проверяем может был такой терминал уже
                        //перебираем новый массив
                        for(let j = 0; j < this.na_terminale_arr.length; j++)
                            {
                        if ( this.na_terminale_arr[j]['terminal_TS'] == this.spisokTSarr[i].terminal_TS) {
                            //значит есть такой терминал
                            flag = true;
                            this.na_terminale_arr[j]['kol_gruz_TS']=Number(this.na_terminale_arr[j]['kol_gruz_TS'])+Number(this.spisokTSarr[i].kol_gruz_TS)
                            //если уже был такой терминал
                            //получаем сколько было до этого мест и прибавляем новые
                        //    this.na_terminale_arr[this.spisokTSarr[i].terminal_TS][0].kol_gruz_TS=(Number(this.na_terminale_arr[this.spisokTSarr[i].terminal_TS][0].kol_gruz_TS)+Number(this.spisokTSarr[i].kol_gruz_TS))
                            }
                        }
                        //если терминал существует
                        if(!flag)
                        {
                            //если ещё не было такого терминала
                            let objToPush= {};
                           // this.na_terminale_arr[this.spisokTSarr[i].terminal_TS]=[];
                            objToPush['terminal_TS'] = this.spisokTSarr[i].terminal_TS;
                            objToPush['kol_gruz_TS'] = this.spisokTSarr[i].kol_gruz_TS;
                            this.na_terminale_arr.push(objToPush);
                         }
                        this.spisokTSarr[i]['show_arrow']=false
                    }
                };
                for (let i = 0; i < this.na_terminale_arr.length; i++) {
                    for (let j = 0; j < this.termList.length; j++) {
                        // console.log(this.termList[j])
                        if(this.na_terminale_arr[i]['terminal_TS']==this.termList[j]['id'])
                        {
                            this.na_terminale_arr[i]['terminal_name']=this.termList[j]['name']

                            // let objToPush= {};
                            // objToPush['id_ts'] = true;
                            // this.show_arr_TS.push(objToPush);
                        }
                    }
                }
                // console.log(this.na_terminale_arr)
                console.log(this.show_arr_TS)
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
                        ),
                this.get_start_data_grade(this.oplata_arr,this.spisokTSarr)
                    );
            },



        }
    }
</script>

