<template>
    <div class="container">
        <modal-perevozchiki-component
            ref="modalComponentforAction"
            vid="grade"
            :gradeAddPerevozchik='gradeAddPerevozchik'
        ></modal-perevozchiki-component>
        <modal-voditel-component
            ref="modalComponentforActionVoditel"
            vid="grade"
            :gradeAddVoditel='gradeAddVoditel'
        ></modal-voditel-component>
        <modal-t-s-component
            ref="modalComponentforActionTS"
            vid="grade"
            :gradeAddTSModal='gradeAddTSModal'
        ></modal-t-s-component>
        <modal-p-p-component
            ref="modalComponentforActionPP"
            vid="grade"
            :gradeAddPPModal='gradeAddPPModal'
        ></modal-p-p-component>
        <modal-author-component   ref="modalComponentforActionAuthor"
                                  edit_flag=true
                                  :chahgeFrontNames='chahgeFrontNames'
                                  vid="GruzzotpravitelComponent"
        ></modal-author-component>

        <div class="row main_grade_div">
            <div class="col-12 row grade_head_row " >
                <input hidden="true" type="file" id="files" ref="files"  v-on:change="handleFilesUpload()"/>
                <input hidden="true" type="file" ref="filesmult" multiple v-on:change="handleMultFilesUpload()" />

                <div  class="col-6 orders_create_title">
                    Заявка: В работе > №{{ nomer_zayavki }}
                </div>
                <div  class="col-6 row justify-content-end trio_but">
                    <div class="col add_ts_button2 text-center" v-on:click="save_TS()">Сохранить</div>
                    <div class="col add_ts_button2 text-center" v-on:click="toProverka()">На проверку</div>
                    <div class="col add_ts_button3 text-center" v-on:click="deleteTs()">Отменить</div>
                </div>
            </div>
            <div class="col-12 row grade_underline"></div>
            <div class="col-12 row">
<!--               маленькая левая колонка-->
                <div class="col-2 grade_left_column">
                    <div class="offset-1 col-11 row grade_left_column_text_main">
                        <div class="col-6 grade_left_column_text_first">Номер запроса:</div>
                        <div class="col-6 grade_left_column_text_sec linkStyle" v-on:click="gotoCreateOrder()">{{ nomer_zayavki }}</div>
                    </div>
                    <div class="offset-1 col-11 row grade_left_underline"></div>
                    <div class="offset-1 col-11 row grade_left_column_text_main">
                        <div class="col-6 grade_left_column_text_first">Дата внесения:</div>
                        <div class="col-6 grade_left_column_text_sec">{{ data_vneseniya }}</div>
                    </div>
                    <div class="offset-1 col-11 row grade_left_underline"></div>
                    <div class="offset-1 col-11 row grade_left_column_text_main">
                        <div class="col-12 grade_left_column_text_first">Адрес погрузки 1:</div>
                        <div class="col-12 grade_left_column_text_sec">{{ adres_pogruzke }}</div>
                    </div>
                    <div class="offset-1 col-11 row grade_left_underline"></div>
                    <div class="offset-1 col-11 row grade_left_column_text_main">
                        <div class="col-12 grade_left_column_text_first">Адрес выгрузки 1:</div>
                        <div class="col-12 grade_left_column_text_sec">{{ adres_vygruski }}</div>
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
                        <div class="col-8 grade_left_column_text_sec">{{ elem.vid_TSNazvanie }},{{elem.kol_TS_TS}}ТС,{{ elem.stavka_TS }} руб</div>
                            <span class="col-2 hide">
                                <div class="hide">
                                <iconify-icon  class="" icon="ant-design:arrow-right-outlined" style="color: #c4c4c4;" width="20" height="20"></iconify-icon>
                                </div>
                            </span>
                        </div>
                        <div class="offset-1 col-11 row grade_left_underline"></div>
                        </span>
                    </div>
                    <div class="offset-1 col-11 row grade_left_underline"></div>
                    <div class="offset-1 col-11 row grade_left_column_text_main" v-show="checkRolePermission([1])">
                        <div class="col-12 grade_left_column_text_first">Заказчик:</div>
                        <div class="col-12 grade_left_column_text_sec">{{ kompaniya_zakazchik }} </div>
                    </div>
                    <div class="offset-1 col-11 row grade_left_underline"></div>
                    <div class="offset-1 col-11 row grade_left_column_text_main">
                        <div class="col-12 grade_left_column_text_first">Общий бюджет:</div>
                        <div class="col-12 grade_left_column_text_sec">{{ ob_budzet_left }} р.</div>
                    </div>
                    <div class="offset-1 col-11 row grade_left_underline"></div>
                    <div class="offset-1 col-11 row grade_left_column_text_main" v-show="checkRolePermission([1])">
                        <div class="col-12 grade_left_column_text_first">Цена контракта:</div>
                        <div class="col-12 grade_left_column_text_sec">{{ cena_kontrakta }} р.</div>
                    </div>
                </div>
<!--конец маленькой левой колонки-->


                <div class="col-10">
<!--                    модалочка-->
                    <modal-pogruzka-component
                        :get_gruzootpravitel_list='get_gruzootpravitel_list'
                        :select_gruzootpravitel='select_gruzootpravitel'
                        allNew=true
                        vid="GradeComponent"
                    ></modal-pogruzka-component>
<!--                    конец модалочки-->



<!--                    начало правой строки основного вида-->
                    <div class="col-12 row">
                        <div  class="col-6 grade_create_title">
                            Список ТС:
                        </div>
                        <div v-if="right_current_TS!==''" class="col-6 row justify-content-end trio_but">
                            <div class="col add_ts_button5 text-center" v-on:click="get_finall_doc_pdf('1')">ТН</div>
                            <input hidden="true" type="file" id="files_doc" ref="files_doc"  v-on:change="handleFilesUploadDoc()"/>

                            <div class="col add_ts_button5 add_ts_button5_1 text-center justify-content-center" v-on:click="get_finall_doc_pdf('2')">Доверенность</div>
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
                        <div class="container  no_padding_left no_padding_right " v-on:click="click_on_right_col(elem,key)" v-bind:class="{ ts_podsvet: elem.id_ts===right_current_TS }">
                             <div class="col-12 row no_padding_left no_padding_right">
                            <div class="col-2 grade_header_col">
                                <iconify-icon class="truck_grade no_padding_right no_padding_left" icon="carbon:delivery-truck" width="20" height="20"></iconify-icon>
                                <span class=" grade_right_col_text">{{ elem.kol_gruz_TS }} мест</span>
                            </div>

                            <div class="col-2 grade_header_col">
                                {{ elem.perevozchik_TSNazvanie }}
                            </div>

                            <div class="col-2 grade_header_col">
                               <span v-if="elem.adres_pogruzki_TS && elem.adres_pogruzki_TS[0] && elem.adres_pogruzki_TS[0].date_ts">{{ elem.adres_pogruzki_TS[0].date_ts }}</span>
                            </div>
                            <div class="col-2 grade_header_col">
                               <span v-if="elem.adres_vygr_TS && elem.adres_vygr_TS[0] && elem.adres_vygr_TS[0].date_ts">{{ elem.adres_vygr_TS[0].date_ts }}</span>
                            </div>
                            <div class="col-2 grade_header_col">
                                {{elem.stavka_summa}}р .
                                <button type="button" class="btn btn-danger btn_del_in_grade" v-on:click="deleteTSFromRightColumn(elem,key)">-</button>
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
                                           <auto-input-global-component class="select_width_grade"
                                                                        :order_id="order_id"
                                                                        :vidTsFromParent="elem1.vid_TSNazvanie"
                                                                        :elem1="elem1"
                                                                        @childReturnMethod="parentMethodFromAutoinputVidts"
                                                                        ref="AutoSelectComponent_vid_TS"
                                           ></auto-input-global-component>

                                    <div  class="create_orders_date_title_1 lit_marg_grade_auto">Перевозчик:
                            <span class="col add_button_grade no_wrap_text" v-on:click="newPerevozchik()">Добавить</span>
                                        <span
                                            v-if="elem1.perevozchik_TSNazvanie!==''"
                                            v-on:click="show_mod_edit(elem1.perevozchik)">
                                            <span
                                                class="iconify edit_icon"
                                                data-icon="akar-icons:edit"
                                                style="color: #a6a6a6;"
                                                data-width="20" data-height="20"
                                            ></span>
                                        </span>
                                    </div>
                            <div class="cr_ord_inp_n_1" v-if="!pogruzkaShowInp" v-on:click="pogruzkaShowInpChange()">{{ elem1.perevozchik_TSNazvanie }}</div>
                            <div class="cr_ord_inp_n_1 add_button_grade_perevozka" v-if="!pogruzkaShowInp&&elem1.perevozchik_TSNazvanie==''" v-on:click="pogruzkaShowInpChange()">Выбрать перевозчика</div>

                                    <auto-input-perevozka-component v-if="pogruzkaShowInp" class="select_width_grade"
                                                                 :order_id="order_id"
                                                                 :vidTsFromParent="elem1.perevozchik_TSNazvanie"
                                                                 :elem1="elem1"
                                                                 @childReturnMethod="parentMethodFromAutoinputPerevozka"
                                                                 @childCloseAutoInput="closeParentAutoInput"
                                                                 ref="AutoSelectComponent_vid_TS"
                                    ></auto-input-perevozka-component>

                                    <div  class="create_orders_date_title_1 lit_marg_grade_auto">Водитель:
                                    <span class="col add_button_grade no_wrap_text" v-on:click="newVoditel()">Добавить</span>
                                      <span
                                          v-if="elem1.voditel_TSNazvanie!==''"
                                          v-on:click="editVoditel(elem1.voditel)">
                                            <span
                                                class="iconify edit_icon"
                                                data-icon="akar-icons:edit"
                                                style="color: #a6a6a6;"
                                                data-width="20" data-height="20"
                                            ></span>
                                        </span>
                                    </div>
                            <div class="cr_ord_inp_n_1" v-if="!voditelShowInp" v-on:click="voditelShowInpChange()">{{ elem1.voditel_TSNazvanie }}</div>
                            <div class="cr_ord_inp_n_1 add_button_grade_perevozka" v-if="!voditelShowInp&&elem1.voditel_TSNazvanie==''" v-on:click="voditelShowInpChange()">Выбрать водителя</div>
                                    <auto-input-voditel-component v-if="voditelShowInp" class="select_width_grade"
                                                                    :order_id="order_id"
                                                                    :vidTsFromParent="elem1.voditel_TSNazvanie"
                                                                    :elem1="elem1"
                                                                    :kompaniya="elem1.perevozchik"
                                                                    @childReturnMethod="parentMethodFromAutoinputVoditel"
                                                                    @childCloseAutoInput="closeParentAutoInput"
                                                                  ref="AutoSelectComponent_vid_TS"
                                    ></auto-input-voditel-component>

                                    <div class="col-6 min_ts">
                            <div  class="create_orders_date_title_1 lit_marg_grade_auto">Номер ТС:
                            <span class="col add_button_grade no_wrap_text" v-on:click="newTSModal()">Добавить</span>
                              <span
                                  v-if="elem1.TS_TSNazvanie!==''"
                                  v-on:click="editTS(elem1.nomer_TS)">
                                            <span
                                                class="iconify edit_icon"
                                                data-icon="akar-icons:edit"
                                                style="color: #a6a6a6;"
                                                data-width="20" data-height="20"
                                            ></span>
                              </span>
                            </div>
                            <div class="cr_ord_inp_n_1" v-if="!TSShowInp" v-on:click="TSShowInpChange()">{{ elem1.TS_TSNazvanie }}</div>
                            <div class="cr_ord_inp_n_1 add_button_grade_perevozka" v-if="!TSShowInp&&elem1.TS_TSNazvanie==''" v-on:click="TSShowInpChange()">Выбрать номер ТС</div>
                                    <auto-input-t-s-component
                                                                  v-if="TSShowInp" class="select_width_grade"
                                                                  :order_id="order_id"
                                                                  :vidTsFromParent="elem1.TS_TSNazvanie"
                                                                  :elem1="elem1"
                                                                  :kompaniya="elem1.perevozchik"
                                                                  @childReturnMethod="parentMethodFromAutoinputTSModal"
                                                                  @childCloseAutoInput="closeParentAutoInput"
                                                                  ref="AutoSelectComponent_vid_TS"
                                    ></auto-input-t-s-component>
                                         </div>
                                    <div class="col-6 min_ts">
                                        <div  class="create_orders_date_title_1 lit_marg_grade_auto">Номер ПП:
                                         <span class="col add_button_grade no_wrap_text" v-on:click="newPPModal()">Добавить</span>
                                            <span
                                                v-if="elem1.PP_Nazvanie!==''"
                                                v-on:click="editPP(elem1.nomer_PP)">
                                            <span
                                                class="iconify edit_icon"
                                                data-icon="akar-icons:edit"
                                                style="color: #a6a6a6;"
                                                data-width="20" data-height="20"
                                            ></span>
                                        </span>
                                        </div>
                            <div class="cr_ord_inp_n_1" v-if="!PPShowInp" v-on:click="PPShowInpChange()">{{ elem1.PP_Nazvanie }}</div>
                            <div class="cr_ord_inp_n_1 add_button_grade_perevozka" v-if="!PPShowInp&&elem1.PP_Nazvanie==''" v-on:click="PPShowInpChange()">Выбрать номер ПП</div>
                                    <auto-input-p-p-component v-if="PPShowInp" class="select_width_grade"
                                                              :order_id="order_id"
                                                              :vidTsFromParent="elem1.PP_Nazvanie"
                                                              :elem1="elem1"
                                                              :kompaniya="elem1.perevozchik"
                                                              @childReturnMethod="parentMethodFromAutoinputPP"
                                                              @childCloseAutoInput="closeParentAutoInput"
                                                              ref="AutoSelectComponent_vid_TS"
                                    ></auto-input-p-p-component>
                                    </div>

                                        <div class="col-12 grade_bold_dark grade_marg_bot">Информация о грузе</div>
                                        <div class="col-6 little_title_grade_margin">
                                            <div class="little_title_grade ">
                                                Кол-во грузомест
                                            </div>
                                            <input @blur="update_one_data_number(elem1,'kol_gruz_TS',elem1.kol_gruz_TS)" class="border_input" v-model="elem1.kol_gruz_TS"  />
                                        </div>
                                        <div class="col-6">
                                            <div class="little_title_grade">
                                                Расстояние, км
                                            </div>
                                            <input @blur="update_one_data_number(elem1,'rasstojanie_TS',elem1.rasstojanie_TS)" class="border_input" v-model="elem1.rasstojanie_TS"  />
                                        </div>
                                </div>

<!--                                вторая колонка-->
                                <div class="col-3">
<!--                                        {{ adres_pogr }}-->
                                    <div class="col-12 grade_bold_dark grade_marg_bot grade_marg_bot">Адрес и дата погрузки</div>
                                    <span v-on:click="add_new_adres_pogruzka(key,1)" class="col add_button_grade no_wrap_text">Новый</span>
                                    <span v-for="(adres_pogr,key1) in elem1.adres_pogruzki_TS" :key="adres_pogr.id_pogruzka">
                                    <div class="col-12 row">
                                        <div class="col-8 little_title_grade pogr_marg_grade">
                                            Адрес погрузки {{ key1 + 1 }}
                                        </div>
                                        <div class="col-4 row">
                                            <span class="col add_button_grade no_wrap_text" v-on:click="select_temp_var(key,'pogruzka',key1,elem1,adres_pogr.id_pogruzka,1,adres_pogr.adres_pogruzki,'adres_pogruzki')">Добавить</span>
                                        </div>
                                    </div>
                              <auto-input-author-grade-component  class="cr_ord_inp_n_1"
                                                            inp_type='adres_pogruzke'
                                                            :adres_pogruzke_show="flag_pogruz"
                                                            :adres_pogruzke_show_edit="adres_pogr.adres_pogruzki_show"
                                                            :order_id="order_id"
                                                            :id_ts="elem1['id_ts']"
                                                            :firstClick="localFirstClick"
                                                            v-bind:gruzootpravitel_arr="gruzootpravitel_arr"
                                                            :key_in_arr="key1"
                                                            :changePogrVygrAllShow="changePogrVygrAllShow"
                                                            @add_pogruzka_new="update_one_gruzzootpravitel_from_select"
                                                            ref="AutoSelectComponent_grade_pogruzka"
                              ></auto-input-author-grade-component>
                                                                            <div class="col-12 row">
                                                                                <div class="col-6 date_width">
                                                                                    <div class="little_title_grade">Дата</div>

                                        <!--                        ДАТА ЗАГРУЗКИ-->
                        <input @click="openDPpogr(adres_pogr.id_pogruzka,1,1)" class="cr_ord_inp_n_2 border_input" v-model="adres_pogr.date_ts"  />
                        <date-picker v-model:value="adres_pogr.date_ts" valueType="format" type="date"
                        format="DD.MM.YYYY" v-model:open=adres_pogr.show_DP_date @change="update_one_data_pogruzka(elem1,adres_pogr.id_pogruzka,1,adres_pogr.date_ts,'date_ts')"></date-picker>

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
                                    <input hidden="true" type="file" :ref="files" v-on:change="handleFilesUpload()" />
<!--                                    <div v-if="!adres_pogr.doc_name" class="col add_ts_button6 text-center" v-on:click="addFiles(adres_pogr.id_pogruzka,1)">Добавить файл</div>-->

<!--                                    кнопки загрузки документов-->

                                <div class="col-12">
                                   <div class="col-12 row">
                                   <div class="col-6">
                                        <div class="little_title_grade col-12">Документы</div>
                                             <div v-for="(docInfo,keyCreatedDocsInfo) in adres_pogr.doc_name_DOC" class="add_button_grade  col-12">
                                                    {{ docInfo.name_doc }}
                                                <span v-if="docInfo.name_doc!=''" v-on:click="deleteFileInMultGrade(adres_pogr.id_pogruzka,8,keyCreatedDocsInfo,docInfo.id)">
                                                    <iconify-icon  icon="ci:off-close" style="color: #c4c4c4;" width="20" height="20"></iconify-icon>
                                                </span>
                                             </div>
                                        <div class="col add_ts_button6 text-center" v-on:click="addFiles(adres_pogr.id_pogruzka,8)">Загрузить</div>
                                   </div>

                                    <div class="col-6">
                                        <div class="little_title_grade">Фото груза</div>
                                             <div v-for="(docInfo,keyCreatedDocsInfo) in adres_pogr.doc_name_FOTO" class="add_button_grade">
                                                    {{ docInfo.name_doc }}
                                                <span v-if="docInfo.name_doc!=''" v-on:click="deleteFileInMultGrade(adres_pogr.id_pogruzka,9,keyCreatedDocsInfo,docInfo.id)"> <iconify-icon  icon="ci:off-close" style="color: #c4c4c4;" width="20" height="20"></iconify-icon></span>
                                             </div>
                                        <div  class="col add_ts_button6 text-center" v-on:click="addFiles(adres_pogr.id_pogruzka,9)">Загрузить</div>
                                   </div>

                                    </div>
                                    <div class="col-12 row">
                                        <div class="col-6">
                                            <div class="little_title_grade">Файлы</div>
                                            <div class="col add_ts_button9_grade text-center" v-on:click="download_pogruzka_vygr_files('pogr',adres_pogr.id_pogruzka)">Скачать всё</div>
                                        </div>
                                    </div>
                                </div>
                                                                                <button type="button" class="btn btn-danger btn_del_in_grade grade_columns" v-on:click="deletePogVygInTS(adres_pogr.id_pogruzka,1,1,key)">-</button>
 </span>

<!--                                    конец кнопок загрузки документов-->

                                </div>

<!--                                третья колонка-->
                                <div class="col-3">
                                    <div class="col-12 grade_bold_dark grade_marg_bot grade_marg_bot">Адрес и дата выгрузки</div>
                                    <span v-on:click="add_new_adres_pogruzka(key,2)" class="col add_button_grade no_wrap_text">Новый</span>
                                    <span v-for="(adres_vygr,key2) in elem1.adres_vygr_TS" :key="adres_vygr.id_pogruzka">
                                        <div class="col-12 row">
                                        <div class="col-8 little_title_grade pogr_marg_grade">
                                            Адрес выгрузки {{ key2 + 1 }}
                                        </div>
                                        <div class="col-4 row">
                                            <span class="col add_button_grade no_wrap_text" v-on:click="select_temp_var(key,'vygruzka',key2,elem1,adres_vygr.id_pogruzka,2,adres_vygr.adres_pogruzki,'adres_pogruzki')">Добавить</span>
                                        </div>
                                    </div>
                                    <auto-input-author-grade-component  class="cr_ord_inp_n_1"
                                                                        inp_type='adres_vygruski'
                                                                        :adres_pogruzke_show="flag_pogruz"
                                                                        :adres_pogruzke_show_edit="adres_vygr.adres_vygruzki_show"
                                                                        :order_id="order_id"
                                                                        :id_ts="elem1['id_ts']"
                                                                        :firstClick="localFirstClick"
                                                                        v-bind:gruzootpravitel_arr="gruzootpravitel_arr"
                                                                        :key_in_arr="key2"
                                                                        :changePogrVygrAllShow="changePogrVygrAllShow"
                                                                        @add_pogruzka_new="update_one_gruzzootpravitel_from_select"
                                                                        ref="AutoSelectComponent_grade_vygruzka"
                                    ></auto-input-author-grade-component>
<!--                                         <auto-input-component class="cr_ord_inp_n_1"-->
<!--                                                               inp_type='grade_vygruzka'-->
<!--                                                               :adres_pogruzke_show="flag_pogruz"-->
<!--                                                               :adres_pogruzke_show_edit="adres_vygr.adres_vygruzki_show"-->
<!--                                                               :order_id="order_id"-->
<!--                                                               :id_ts="elem1['id_ts']"-->
<!--                                                               v-bind:gruzootpravitel_arr="gruzootpravitel_arr"-->
<!--                                                               :key_in_arr="key2"-->
<!--                                                               @add_pogruzka_new="update_one_gruzzootpravitel_from_select"-->
<!--                                                               ref="AutoSelectComponent_grade_vygruzka"-->
<!--                                         ></auto-input-component>-->


                                        <div class="col-12 row">
                                        <div class="col-6 date_width">
                                            <div class="little_title_grade">Дата</div>

                                            <input @click="openDPpogr(adres_vygr.id_pogruzka,1,2)" class="cr_ord_inp_n_2 border_input" v-model="adres_vygr.date_ts"  />
                        <date-picker v-model:value="adres_vygr.date_ts" valueType="format" type="date"
                                     format="DD.MM.YYYY" v-model:open=adres_vygr.show_DP_date @change="update_one_data_pogruzka(elem1,adres_vygr.id_pogruzka,2,adres_vygr.date_ts,'date_ts')"></date-picker>

                                        </div>
                                    </div>
                                    <div class="col-12 row doc_add_row_1">
                                        <div class="col-3 add_button_grade no_wrap_text">{{ adres_vygr.doc_name }}
                                    <span v-if="adres_vygr.doc_name" v-on:click="delete_file_grade(adres_vygr.id_pogruzka,2)">
                                            <iconify-icon   icon="ci:off-close" style="color: #c4c4c4;" width="20" height="20"></iconify-icon>
                                            </span>                                        </div>
                                    </div>
                                     <input hidden="true" type="file" :ref="files" v-on:change="handleFilesUpload()" />
<!--                                    <div v-if="!adres_vygr.doc_name" class="col add_ts_button6 text-center" v-on:click="addFiles(adres_vygr.id_pogruzka,2)">Добавить файл</div>-->



                                    <div class="col-12">
                                   <div class="col-12 row">
                                   <div class="col-6">
                                        <div class="little_title_grade col-12">Документы</div>
                                             <div v-for="(docInfo,keyCreatedDocsInfo) in adres_vygr.doc_name_DOC" class="add_button_grade col-12">
                                                    {{ docInfo.name_doc }}
                                                 <span v-if="docInfo.name_doc!=''" v-on:click="deleteFileInMultGrade(adres_vygr.id_pogruzka,10,keyCreatedDocsInfo,docInfo.id)">
                                                     <iconify-icon  icon="ci:off-close" style="color: #c4c4c4;" width="20" height="20"></iconify-icon>
                                                 </span>
                                             </div>
                                        <div  class="col add_ts_button6 text-center" v-on:click="addFiles(adres_vygr.id_pogruzka,10)">Загрузить</div>
                                   </div>

                                    <div class="col-6">
                                        <div class="little_title_grade">Фото груза</div>
                                             <div v-for="(docInfo,keyCreatedDocsInfo) in adres_vygr.doc_name_FOTO" class="add_button_grade">
                                                    {{ docInfo.name_doc }}
                                                 <iconify-icon  icon="ci:off-close" style="color: #c4c4c4;" width="20" height="20"></iconify-icon>
                                                 <span v-if="docInfo.name_doc!=''" v-on:click="deleteFileInMultGrade(adres_vygr.id_pogruzka,11,keyCreatedDocsInfo,docInfo.id)"> <iconify-icon  icon="ci:off-close" style="color: #c4c4c4;" width="20" height="20"></iconify-icon></span>

                                             </div>
                                        <div  class="col add_ts_button6 text-center" v-on:click="addFiles(adres_vygr.id_pogruzka,11)">Загрузить</div>
                                   </div>

                                   <div class="col-6">
                                        <div class="little_title_grade">Акт</div>
                                             <div v-for="(docInfo,keyCreatedDocsInfo) in adres_vygr.doc_name_ACT" class="add_button_grade">
                                                    {{ docInfo.name_doc }}
                                                 <span v-if="docInfo.name_doc!=''" v-on:click="deleteFileInMultGrade(adres_vygr.id_pogruzka,12,keyCreatedDocsInfo,docInfo.id)"> <iconify-icon  icon="ci:off-close" style="color: #c4c4c4;" width="20" height="20"></iconify-icon></span>
                                             </div>
                                        <div  class="col add_ts_button6 text-center" v-on:click="addFiles(adres_vygr.id_pogruzka,12)">Загрузить</div>
                                   </div>

                                    </div>
                                    <div class="col-12 row">
                                        <div class="col-6">
                                            <div class="little_title_grade">Файлы</div>
                                            <div class="col add_ts_button9_grade text-center" v-on:click="download_pogruzka_vygr_files('vygr',adres_vygr.id_pogruzka)">Скачать всё</div>
                                        </div>
                                    </div>
                                </div>
                                        <button type="button" class="btn btn-danger btn_del_in_grade grade_columns" v-on:click="deletePogVygInTS(adres_vygr.id_pogruzka,1,2,key)">-</button>
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
                                        <input @keypress="onlyNumber" @blur="update_one_data_number(elem1,'stavka_summa',elem1.stavka_summa)" class="border_input inp_date" v-model="elem1.stavka_summa"/>
                                        <span class="no_wrap"><input type="checkbox" id="checkbox" @blur="update_one_data(elem1,'NDS_check')" v-model="elem1.NDS_check">
                                           НДС
                                        </span>
                                    </div>
                                    <div class="col-4">
                                        <div class="little_title_grade">Предоплата,%</div>
                                        <input @blur="update_one_data_number(elem1,'predoplata',elem1.predoplata)" class="border_input inp_time" v-model="elem1.predoplata"  />
                                    </div>
                                     </div>
                                    <div class="col-12 not_paid_marg">
                                        <span class="not_paid">Не оплачено:</span>
                                        <span class="grade_bold_dark_1">{{ elem1.ne_oplacheno }}р.</span>
                                    </div>
                                    <div class="col-12 little_title_grade">Оплата:</div>
                                    <div class="col add_button_grade no_wrap_text" v-on:click="add_summ(key)">Добавить сумму</div>

                                    <div class="col-12 row" v-for="(sum,key2) in elem1.summa_list">
                                    <div class="col little_title_grade_1">Сумма {{ key2 +1 }}</div>
                                    <div class="col  no_wrap_text" ><span v-on:click="deleteSumm(sum.id,key,key2)" class="add_button_grade">Удалить сумму</span></div>
                                     <div class="col-12">
                                        <input @keypress="onlyNumber" @input="checkSumma(elem1,sum.id_summa,sum.summa,key2)" @blur="update_one_data_summa(elem1,sum.id_summa,'summa',sum.summa)"
                                               class="border_input add_summ_grade_inp" v-model="sum.summa"/>
                                            <input @click="openDPsumma(key2)"
                                                   class=" border_input add_summ_grade_inp_1" v-model="sum.data"  />
                                         <date-picker v-model:value="sum.data" valueType="format" type="date"
                                                        format="DD.MM.YYYY" v-model:open=sum.show_DP_date @change="update_one_data_summa(elem1,sum.id_summa,'data',sum.data)">
                                         </date-picker>
                                     </div>
                                    </div>

                                    <div class="col-12 grade_bold_dark grade_marg_bot grade_marg_bot">Договор-запрос:</div>
                                   <div class="col-12 row">
                                    <div class="col-6">
                                    <div class="little_title_grade">Подписанная</div>
                                        <input hidden="true" type="file" :ref="files" v-on:change="handleFilesUpload()" />
                                        <span v-if="elem1.podpisannaya_doc_name" class="add_button_grade " v-on:click="delete_file_grade(0,3)">{{ elem1.podpisannaya_doc_name }}
                                            <iconify-icon  icon="ci:off-close" style="color: #c4c4c4;" width="20" height="20"></iconify-icon>
                                        </span>
                                        <div v-if="!elem1.podpisannaya_doc_name" class="col add_ts_button6 text-center" v-on:click="addFiles(0,3)">Загрузить</div>
                                    </div>
                                    <div class="col-6">
                                        <input hidden="true" type="file" :ref="files" v-on:change="handleFilesUpload()" />
                                        <div class="little_title_grade">Счёт</div>
                                        <div class="col-12 add_button_grade">
                                            <input hidden="true" type="file" :ref="files" v-on:change="handleFilesUpload()" />
                                            <span v-if="elem1.schet_doc_name" class="add_button_grade " v-on:click="delete_file_grade(0,4)">{{ elem1.schet_doc_name }}
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
                                            <span v-if="elem1.faktura_doc_name" class="add_button_grade " v-on:click="delete_file_grade(0,5)">{{ elem1.faktura_doc_name }}
                                            <iconify-icon  icon="ci:off-close" style="color: #c4c4c4;" width="20" height="20"></iconify-icon>
                                        </span>
                                            <div v-else class="col add_ts_button6 text-center" v-on:click="addFiles(0,5)">Загрузить</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="little_title_grade">Файлы</div>
                                            <div class="col add_ts_button9_grade text-center" v-on:click="download_all_doc_grade()">Скачать всё</div>
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
<!--                Общий бюджет: {{ ob_budzet_down }} рубля-->
                Общий бюджет: {{ ob_budzet_left }} рубля
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
    import DatePicker from 'vue-datepicker-next';
    import 'vue-datepicker-next/index.css';
    import 'vue-datepicker-next/locale/ru';

    export default {
        props: ['auth_user'],
        components: {
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
                p5:'',
                flag_pogruz:'',
                role:'',
                deleteFlag:false,
                pogruzkaShowInp:false,
                voditelShowInp:false,
                TSShowInp:false,
                PPShowInp:false,
                showTsList:true,
                mainCount:100,
                valTest:0,
                right_currentTS_In_Arr:'',
                localFirstClick:false,


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
            this.update_unread_status_v_rabote()
        },

        created()
        {
            this.role=this.auth_user['role_perm']['role']
            this.permissions=this.auth_user['role_perm']['permissions']


        },

        computed: {

            dolg: function () {

                let summNeOpl=0;
                for (let i = 0; i < this.spisokTShead.length; i++) {
                    summNeOpl=summNeOpl+this.spisokTShead[i].ne_oplacheno;
                }
                return summNeOpl
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
            //используем вычисляемое свойство для определения где поменялось значение
            spisokTSheadSummaOne()
            {
                // console.log('this.right_current_TS!!!!!!!!!!!!!!!!!!!!!')
                // console.log(this.right_current_TS)
                //указываешь что должно поменяться
                if(this.spisokTShead.length!=0)
                {
                    for (let i = 0; i < this.spisokTShead.length; i++) {

                        if((this.right_current_TS==this.spisokTShead[i].id_ts)&&(this.right_current_TS!==''))
                        {
                            return this.spisokTShead[i].stavka_summa;
                        }
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
            // 'spisokTShead.1': {
            //     handler() {
            //         console.log('Свойство myNestedArray[1] изменилось');
            //     },
            //     deep: true,
            // },


                valTest() {

                    this.valTest = Math.max(1, 10);
                },
            neOplachenoFlagList:{
                deep: true,
                handler(){
                    for (let i = 0; i < this.spisokTShead.length; i++) {
                        this.spisokTShead[i].ne_oplacheno=0;
                        this.spisokTShead[i].ob_summa=0;
                        for (let j = 0; j < this.spisokTShead[i].summa_list.length; j++) {
                            let sum=Number(this.spisokTShead[i].summa_list[j].summa);
                            this.spisokTShead[i].ob_summa=this.spisokTShead[i].ob_summa+sum;
                        }
                        if((this.spisokTShead[i].stavka_summa-this.spisokTShead[i].ob_summa)<0)
                        {
                            this.spisokTShead[i].ne_oplacheno=0
                        }
                        else
                        {
                            this.spisokTShead[i].ne_oplacheno=(this.spisokTShead[i].stavka_summa-this.spisokTShead[i].ob_summa)
                        }
                    }
                }
            },
            spisokTSheadFlag: {
                 deep: true,
                handler(){
                    for (let i = 0; i < this.spisokTShead.length; i++) {
                            this.spisokTShead[i].ob_summa=0;
                            for (let j = 0; j < this.spisokTShead[i].summa_list.length; j++) {
                                let sum=Number(this.spisokTShead[i].summa_list[j].summa);
                                this.spisokTShead[i].ob_summa=this.spisokTShead[i].ob_summa+sum;
                            }
                    }
                }
            },
            spisokTSheadSummaOne: {
                deep: true,
                handler(){
                    for (let i = 0; i < this.spisokTShead.length; i++) {
                        this.spisokTShead[i].ne_oplacheno=0;
                        this.spisokTShead[i].ob_summa=0;
                        for (let j = 0; j < this.spisokTShead[i].summa_list.length; j++) {
                            let sum=Number(this.spisokTShead[i].summa_list[j].summa);
                            this.spisokTShead[i].ob_summa=this.spisokTShead[i].ob_summa+sum;
                        }
                        if((this.spisokTShead[i].stavka_summa-this.spisokTShead[i].ob_summa)<0)
                        {
                            this.spisokTShead[i].ne_oplacheno=0
                        }
                        else
                        {
                            this.spisokTShead[i].ne_oplacheno=(this.spisokTShead[i].stavka_summa-this.spisokTShead[i].ob_summa)
                        }
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
            //директива закрытия инпута если клик был в не элемента
            closeParentAutoInput(data)
            {
                if(data.varName=='TSShowInp')
                {
                    this.TSShowInp=false
                }
                if(data.varName=='voditelShowInp')
                {
                    this.voditelShowInp=false
                }
                if(data.varName=='PPShowInp')
                {
                    this.PPShowInp=false
                }
                if(data.varName=='pogruzkaShowInp')
                {
                    this.pogruzkaShowInp=false
                }
            },
            checkRolePermission(users_permissions_list)
            {
                let permission=2;

                //перебор юзеров
                let role=0;
                let flag=false;
                for(var j = 0; j < users_permissions_list.length; j++) {
                    role=users_permissions_list[j]
                    if((role==this.role)&&((this.permissions.includes(permission))||(this.permissions.includes(1)))) {
                        flag = true;
                    }
                }
                return flag

            },
            //метод для проверки показывать ли инпут или текст в погрузке выгрузке, если есть значение то текст, если нету то инпут
            adresPogruzkiVygruzkiShowFunc(key, pogr_vygr)
            {

            },
            //сохраняем перевозчика из модального окна
            gradeAddPerevozchik(id,nazvanie)
            {
                for (let i = 0; i < this.spisokTShead.length; i++) {
                    if(this.spisokTShead[i]['id_ts']==this.right_current_TS)
                    {
                        //если новый перевозчик
                        if(id!== undefined)
                        {
                            this.spisokTShead[i]['perevozchik']=id
                        }
                        this.spisokTShead[i]['perevozchik_TSNazvanie']=nazvanie
                        this.update_one_data(this.spisokTShead[i],'perevozchik')
                    }
                }

            },
            gradeAddVoditel(id,nazvanie)
            {
                for (let i = 0; i < this.spisokTShead.length; i++) {
                    if(this.spisokTShead[i]['id_ts']==this.right_current_TS)
                    {
                        this.spisokTShead[i]['voditel']=id
                        this.spisokTShead[i]['voditel_TSNazvanie']=nazvanie
                        this.update_one_data(this.spisokTShead[i],'voditel')
                    }
                }
            },
            gradeAddTSModal(id,nazvanie)
            {
                for (let i = 0; i < this.spisokTShead.length; i++) {
                    if(this.spisokTShead[i]['id_ts']==this.right_current_TS)
                    {
                        this.spisokTShead[i]['nomer_TS']=id
                        this.spisokTShead[i]['TS_TSNazvanie']=nazvanie
                        this.update_one_data(this.spisokTShead[i],'nomer_TS')
                    }
                }
            },
            gradeAddPPModal(id,nazvanie)
            {
                for (let i = 0; i < this.spisokTShead.length; i++) {
                    if(this.spisokTShead[i]['id_ts']==this.right_current_TS)
                    {
                        this.spisokTShead[i]['nomer_PP']=id
                        this.spisokTShead[i]['PP_Nazvanie']=nazvanie
                        this.update_one_data(this.spisokTShead[i],'nomer_PP')
                    }
                }
            },
            newPerevozchik()
            {
                //вызов метода дочернего компонента( модального окна )
                this.$refs.modalComponentforAction.newPerevozchik()
            },
            newVoditel()
            {
                //вызов метода дочернего компонента( модального окна )
                this.$refs.modalComponentforActionVoditel.newPerevozchik(true,this.spisokTShead[this.right_currentTS_In_Arr]['perevozchik'],this.spisokTShead[this.right_currentTS_In_Arr]['perevozchik_TSNazvanie'])
            },
            newTSModal()
            {
                this.$refs.modalComponentforActionTS.newPerevozchik(true,this.spisokTShead[this.right_currentTS_In_Arr]['perevozchik'],this.spisokTShead[this.right_currentTS_In_Arr]['perevozchik_TSNazvanie'])
            },
            newPPModal()
            {
                this.$refs.modalComponentforActionPP.newPerevozchik(true,this.spisokTShead[this.right_currentTS_In_Arr]['perevozchik'],this.spisokTShead[this.right_currentTS_In_Arr]['perevozchik_TSNazvanie'])
            },
            //метод удаления из массив корректный
            deleteFromArrTS(key,id_pogruzka,pogruzka_or_vygruzka)
            {

                if(pogruzka_or_vygruzka==1)
                {
                    this.spisokTShead[key].adres_pogruzki_TS = this.spisokTShead[key].adres_pogruzki_TS.filter(item => item.id_pogruzka !== id_pogruzka);
                }
                else
                {
                    this.spisokTShead[key].adres_vygr_TS = this.spisokTShead[key].adres_vygr_TS.filter(item => item.id_pogruzka !== id_pogruzka);
                }
            },
           async deletePogVygInTS(id_pogruzka, id_doc_type, pogruzka_or_vygruzka,key)
            {
                const result = await this.confirmMethodMixin();

                if (result) {
                this.deleteFromArrTS(key,id_pogruzka,pogruzka_or_vygruzka)

                // this.showTsList=false
                // console.log('key')
                // console.log(key)
                // console.log('key1')
                // console.log(key1)
                // console.log('pogruzka_or_vygruzka')
                // console.log(pogruzka_or_vyg ruzka)
                // console.log('список ДО')
                // console.log(this.spisokTShead[key].adres_pogruzki_TS)
                // this.spisokTShead[key].adres_pogruzki_TS.splice(key1,1)
                // // let tempArr=this.spisokTShead[key].adres_pogruzki_TS;
                // // this.spisokTShead[key].adres_pogruzki_TS=[];
                // // this.spisokTShead[key].adres_pogruzki_TS=tempArr;
                // console.log('список после')
                // console.log(this.spisokTShead[key].adres_pogruzki_TS)
                //  console.log('showTsList')
                // this.showTsList=true
                axios
                    .post('/deletePogVygInTS',{
                        id_ts:this.right_current_TS,
                        grade_id:this.order_id,
                        id_pogruzka:id_pogruzka,
                        id_doc_type:id_doc_type,
                        pogruzka_or_vygruzka:pogruzka_or_vygruzka,

                    })
                    .then(response => {
                        // console.log(response.data.file)
                       // window.location.assign('/get_finall_doc_pdf_file/templates/'+response.data.file) ;
                        if(pogruzka_or_vygruzka==1)
                        {
                            //получим id данной перевозки ИМЕННО ID
                            // this.deleteFromArrTS(key,response.data.id)
                            // this.spisokTShead[key].adres_pogruzki_TS.splice(key1,1)

                        }
                        // else
                        // {
                        //     this.spisokTShead[key].adres_vygr_TS[key1].splice(id_pogruzka,1)
                        //
                        // }
                    })
                }
            },
            //обновление из инпута поиска грузоотправителя
            update_one_gruzzootpravitel_from_select(data)
            {
                //номер транспортного средства по порядку среди всех ТС в данной grade(заявке)
                //ключ , это номер по порядку в данном ТС
                let newKey=0;
                if(data.pogr_or_vygr=='1')
                {
                    newKey= this.spisokTShead[this.right_currentTS_In_Arr].adres_pogruzki_TS[data.key].id_pogruzka
                }
                if(data.pogr_or_vygr=='2')
                {
                    newKey= this.spisokTShead[this.right_currentTS_In_Arr].adres_vygr_TS[data.key].id_pogruzka
                }
                //погрузка - 1 или выгрузка - 2
                // console.log(data.pogr_or_vygr)
                //id грузоотправителя
                // console.log(data.inp_pog_id)
                //то что в инпуте
                // console.log(data.nazvanie)
                //обновим в базе данных
                this.up_in_db_gruzootpravitel(this.order_id,data.id_ts,newKey,data.pogr_or_vygr,data.inp_pog_id)
                //обновим на фронте
                let pog_vyg='';
                let pog_vyg_show='';
                if(data.pogr_or_vygr=='1')
                {
                    pog_vyg='adres_pogruzki_TS'
                    pog_vyg_show='adres_pogruzki_show'
                }
                if(data.pogr_or_vygr=='2')
                {
                    pog_vyg='adres_vygr_TS'
                    pog_vyg_show='adres_vygruzki_show'
                }
                this.up_gruz_in_front_new(pog_vyg,data.key,pog_vyg_show,data.nazvanie)
            },

            //метод обновляющий данные грузоотправителя из инпута
            //this.order_id
            //номер транспортного средства по порядку среди всех ТС в данной grade(заявке)
            //data.id_ts
            //ключ , это номер по порядку в данном ТС
            //data.key
            //погрузка - 1 или выгрузка - 2
            //data.pogr_or_vygr
            //id грузоотправителя
            //data.inp_pog_id
            up_in_db_gruzootpravitel(order_id,id_ts,key,pogr_or_vygr,inp_pog_id)
            {
                axios
                    .post('/up_in_db_gruzootpravitel',{
                        grade_id:this.order_id,
                        id_ts:id_ts,
                        id_pogr:key,
                        pogr_or_vygr:pogr_or_vygr,
                        inp_pog_id:inp_pog_id,
                        name:name,
                    })
            },
            //обновим данные на фронте в списке грузоотправителей после работы с селектом НЕ РАБОТАЕТ ИЗ МОДАЛКИ ДОБАВЛЕНИЯ ГРУЗООТПРАВИТЕЛЯ
            up_gruz_in_front(id_ts,pog_vyg,key,pog_vyg_show,nazvanie)
            {
                this.spisokTShead[id_ts][pog_vyg][key][pog_vyg_show]=nazvanie
            },
            up_gruz_in_front_new(pog_vyg,key,pog_vyg_show,nazvanie)
            {
                // console.log('СПИСОК ОТСЮДА')
                // console.log('pog_vyg')
                // console.log(pog_vyg)
                // console.log('key')
                // console.log(key)
                // console.log('pog_vyg_show')
                // console.log(pog_vyg_show)
                // console.log('nazvanie')
                // console.log(nazvanie)
                this.spisokTShead[this.right_currentTS_In_Arr][pog_vyg][key][pog_vyg_show]=nazvanie
            },
            update_unread_status_v_rabote()
            {
                if(this.role==2)
                {
                    axios
                        .post('/update_unread_status_v_rabote',{
                            logist_id:this.auth_user['id'],
                            id:this.order_id,
                            column_name:'v_rabote',
                        })
                }
            },

              deleteTs()
            {
               // console.log('spisokTSarr')
               // console.log(this.spisokTSarr)
               console.log('this.spisokTShead')
               console.log(this.spisokTShead)
             //    console.log('spisokTSheadadres_pogruzki_TS')
             //    console.log(this.spisokTShead[0].adres_pogruzki_TS)


            },
            gotoCreateOrder()
            {
                window.location.href = ('/create_orders/'+this.order_id)

            },
         async   deleteTSFromRightColumn(elem,key)
            {
                const result = await this.confirmMethodMixin();
                if (result) {

                    this.deleteFlag=true;
                this.spisokTShead.splice(key,1)
                axios
                    .post('/deleteTSFromRightColumn',{
                        TS:elem,
                        grade_id:this.order_id
                    })
                    .then(response => {
                        console.log('удалено')
                    })
                }
            },
            select_temp_var(key,pogr_vygr,key1,p1,p2,p3,p4,p5)
            {
                //key - номер в массиве spisokTShead
                //pogr_vygr - погрузка или выгрузка в массиве spisokTShead
                //key1 - номер в массиве погрузки или выгрузки
                this.add_gruzoot_key=key
                this.add_gruzoot_pogr_vygr=pogr_vygr
                this.add_gruzoot_key1=key1
                this.p1=p1
                this.p2=p2
                this.p3=p3
                this.p4=p4
                this.p5=p5
            },
            //обновление из модального окна грузоотправителя
            select_gruzootpravitel()
            {

                    axios
                        .post('/select_gruzootpravitel',{
                        })
                        .then(response => {

                            //обновим на фронте
                            // this.p1.id_ts  - id ts номер транспортного средства по порядку среди всех ТС в данной grade(заявке)
                            // this.add_gruzoot_key - ключ , это номер по порядку в данном ТС
                            let pog_vyg='';
                            let pog_vyg_show='';
                            let ref_link='';
                            if(this.add_gruzoot_pogr_vygr=='pogruzka')
                            {

                                this.spisokTShead[this.add_gruzoot_key]['adres_pogruzki_TS'][this.add_gruzoot_key1]['adres_pogruzki']=response.data.gruzootpravitel.id,
                                this.update_one_data_pogruzka(this.p1,this.p2,this.p3,response.data.gruzootpravitel.id,this.p5)
                                pog_vyg='adres_pogruzki_TS'
                                pog_vyg_show='adres_pogruzki_show'
                                ref_link='AutoSelectComponent_grade_pogruzka'
                            }
                            if(this.add_gruzoot_pogr_vygr=='vygruzka')
                            {
                                this.spisokTShead[this.add_gruzoot_key]['adres_vygr_TS'][this.add_gruzoot_key1]['adres_pogruzki']=response.data.gruzootpravitel.id,
                                this.update_one_data_pogruzka(this.p1,this.p2,this.p3,response.data.gruzootpravitel.id,this.p5)
                                pog_vyg='adres_vygr_TS'
                                pog_vyg_show='adres_vygruzki_show'
                                ref_link='AutoSelectComponent_grade_vygruzka'

                            }

                           // console.log(this.p1)
                            console.log('this.add_gruzoot_key')
                            console.log(this.add_gruzoot_key)
                            this.up_gruz_in_front_new(pog_vyg,this.p2,pog_vyg_show,response.data.gruzootpravitel.nazvanie)
                            //this.up_gruz_in_front(this.p1.id_ts,pog_vyg,this.p2,pog_vyg_show,response.data.gruzootpravitel.nazvanie)
                            //обновим в input
                            //передаём в селект название и отображаем там

                            this.$refs[ref_link][this.p2].up_gruz_from_modal(response.data.gruzootpravitel.nazvanie)
                        })

            },
            editTS(id)
            {
                //вызов метода дочернего компонента( модального окна ) редактирования номера ТС
                this.$refs.modalComponentforActionTS.get_modal_edit_data(id)
            },
            editPP(id)
            {
                //вызов метода дочернего компонента( модального окна )
                this.$refs.modalComponentforActionPP.get_modal_edit_data(id)
            },
            pogruzkaShowInpChange()
            {
               this.pogruzkaShowInp=!this.pogruzkaShowInp
            },
            voditelShowInpChange()
            {
                this.voditelShowInp=!this.voditelShowInp
            },
            TSShowInpChange()
            {
                this.TSShowInp=!this.TSShowInp
            },
            PPShowInpChange()
            {
                this.PPShowInp=!this.PPShowInp
            },
            addPerevozchikShowId(id_ts)
            {
                this.pogruzkaShowArr.push(id_ts)
            },
            toProverka()
            {
                axios
                    .post('/orderChangeColumn',{
                        columnName:'kontrol',
                        id:this.order_id
                    })
                    .then(response => {
                         //    window.location.href = ('/')
                    })
            },
            get_gruzootpravitel_list()
            {
                let inp_temp =[];
                axios
                    .post('/getGruzWithName',{
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

            async get_finall_doc_pdf(doc_type)
            {
                this.current_doc=doc_type
                let dovType='';
                //если доверенность то выбираем между док и эксель
                if(doc_type==2)
                {
                    const result = await this.$swal.fire({
                        title: 'Выберите тип документа',
                        showCancelButton: true,
                        confirmButtonText: 'Doc',
                        cancelButtonText: 'Excel',
                        cancelButtonColor: '#d33',
                        showCloseButton: true,
                        focusConfirm: false,
                        preConfirm: (choice) => {
                            return choice;
                        },
                    });
                    if (result.isConfirmed) {
                        dovType='DOC';
                    } else if (result.dismiss === this.$swal.DismissReason.cancel) {
                        dovType='Excel';
                    } else {
                        dovType=''
                    }
                    if(dovType!='')
                    {
                        this.callGetDocPDF(dovType)
                    }
                }
                else
                {
                    this.callGetDocPDF(dovType)
                }



            },
            callGetDocPDF(dovType)
            {
                axios
                    .post('/get_finall_doc_pdf',{
                         doc_type:this.current_doc,
                         order_id:this.order_id,
                         id_ts:this.right_current_TS,
                         dovType:dovType,
                    })
                    .then(response => {
                        window.location.assign('/get_finall_doc_pdf_file/templates/'+response.data.file) ;
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
            //получим ключ id_pogruzka по значению поля
            getKeyInListByItem(whereSearch,columnNameWhere,whatSearch)
            {
                console.log('whereSearch')
                console.log(whereSearch)
                for (let i = 0; i < whereSearch.length; i++) {

                    if(whereSearch[i].id_pogruzka==whatSearch)
                    {
                        return i;
                        break;
                    }
                }
            },
            chahgeFrontNames()
            {

            },
            changePogrVygrAllShow(id)
            {
                this.$refs.modalComponentforActionAuthor.get_modal_edit_data(id)
            },
            openDPpogr(id_pogruzka,date_or_time,pogr_or_vygr)
            {
                if(pogr_or_vygr==1)
                {
                    let newKey=this.getKeyInListByItem(this.spisokTShead[this.right_currentTS_In_Arr].adres_pogruzki_TS,'id_pogruzka',id_pogruzka)
                    if(date_or_time==1)
                    {
                        this.spisokTShead[this.right_currentTS_In_Arr].adres_pogruzki_TS[newKey].show_DP_date=true;
                    }
                    else
                    {
                        this.spisokTShead[this.right_currentTS_In_Arr].adres_pogruzki_TS[newKey].show_DP_time=true;
                    }
                }
                else
                {
                    let newKey=this.getKeyInListByItem(this.spisokTShead[this.right_currentTS_In_Arr].adres_vygr_TS,'id_pogruzka',id_pogruzka)
                    if(date_or_time==1)
                    {
                        this.spisokTShead[this.right_currentTS_In_Arr].adres_vygr_TS[newKey].show_DP_date=true;
                    }
                    else
                    {
                        this.spisokTShead[this.right_currentTS_In_Arr].adres_vygr_TS[newKey].show_DP_time=true;
                    }
                }
            },
            openDPsumma(summa_numb_in_arr)
            {
                    this.spisokTShead[this.right_currentTS_In_Arr].summa_list[summa_numb_in_arr].show_DP_date=true
            },
            handleChange0()
            {
                console.log(this.data_kontrakta)
            },
            async deleteFileInMultGrade(id_pogruzka, id_doc_type, key, id)
            {
                const result = await this.confirmMethodMixin();

                if (result) {
                    this.current_id_pogruzka=id_pogruzka;
                    this.current_id_doc_type=id_doc_type;

                    axios.post( '/delete_file_grade_by_id',
                        {
                            id:id,
                        })
                            .then(response => {
                                    this.delelteNameFile(this.current_id_doc_type,key)
                            })
            }
            },

            delelteNameFile(type,key)
            {

                for (let i = 0; i < this.spisokTShead[this.right_currentTS_In_Arr].adres_pogruzki_TS.length; i++) {
                    if(this.current_id_pogruzka== this.spisokTShead[this.right_currentTS_In_Arr].adres_pogruzki_TS[i].id_pogruzka)
                    {
                        if(type=='8')
                        {
                            this.spisokTShead[this.right_currentTS_In_Arr].adres_pogruzki_TS[i].doc_name_DOC.splice(key,1)
                        }
                        if(type=='9')
                        {
                            this.spisokTShead[this.right_currentTS_In_Arr].adres_pogruzki_TS[i].doc_name_FOTO.splice(key,1)
                        }
                        if(type=='10')
                        {
                            this.spisokTShead[this.right_currentTS_In_Arr].adres_vygr_TS[i].doc_name_DOC.splice(key,1)
                        }
                        if(type=='11')
                        {
                            this.spisokTShead[this.right_currentTS_In_Arr].adres_vygr_TS[i].doc_name_FOTO.splice(key,1)
                        }
                        if(type=='12')
                        {
                            this.spisokTShead[this.right_currentTS_In_Arr].adres_vygr_TS[i].doc_name_ACT.splice(key,1)
                        }

                        if(this.right_col_down_show==true)
                        {
                            this.right_col_down_show=false;
                        }
                        this.right_col_down_show=true;
                    }
                }
            },

          async  delete_file_grade(id_pogruzka, id_doc_type)
            {
                const result = await this.confirmMethodMixin();

                if (result) {
                this.current_id_pogruzka=id_pogruzka;
                this.current_id_doc_type=id_doc_type;

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

                             for (let i = 0; i < this.spisokTShead[this.right_currentTS_In_Arr].adres_pogruzki_TS.length; i++) {
                                 if(this.current_id_pogruzka== this.spisokTShead[this.right_currentTS_In_Arr].adres_pogruzki_TS[i].id_pogruzka)
                                 {

                                              this.spisokTShead[this.right_currentTS_In_Arr].adres_pogruzki_TS[i].doc_name=''
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
                             for (let i = 0; i < this.spisokTShead[this.right_currentTS_In_Arr].adres_vygr_TS.length; i++) {
                                 if(this.current_id_pogruzka== this.spisokTShead[this.right_currentTS_In_Arr].adres_vygr_TS[i].id_pogruzka)
                                 {
                                     this.spisokTShead[this.right_currentTS_In_Arr].adres_vygr_TS[i].doc_name =''
                                     if (this.right_col_down_show == true) {
                                         this.right_col_down_show = false;
                                     }
                                     this.right_col_down_show = true;
                                 }
                             }
                         }

                         if(response.data.id_doc_type=='3')
                         {
                             this.spisokTShead[this.right_currentTS_In_Arr].podpisannaya_doc_name=''
                             if (this.right_col_down_show == true) {
                                 this.right_col_down_show = false;
                             }
                             this.right_col_down_show = true;
                         }
                         if(response.data.id_doc_type=='4')
                         {
                             this.spisokTShead[this.right_currentTS_In_Arr].schet_doc_name=''
                             if (this.right_col_down_show == true) {
                                 this.right_col_down_show = false;
                             }
                             this.right_col_down_show = true;
                         }
                         if(response.data.id_doc_type=='5')
                         {
                             this.spisokTShead[this.right_currentTS_In_Arr].faktura_doc_name=''
                             if (this.right_col_down_show == true) {
                                 this.right_col_down_show = false;
                             }
                             this.right_col_down_show = true;
                         }
                         if(response.data.id_doc_type=='6')
                         {
                             this.spisokTShead[this.right_currentTS_In_Arr].TN_doc_name=''
                             if (this.right_col_down_show == true) {
                                 this.right_col_down_show = false;
                             }
                             this.right_col_down_show = true;
                         }
                     })
                }
            },

            addFiles(id_pogruzka, id_doc_type){
                this.current_id_pogruzka=id_pogruzka;
                this.current_id_doc_type=id_doc_type;
                //8 9 файлы множественной загрузки
                if((id_doc_type=='8')||(id_doc_type=='9')||(id_doc_type=='10')||(id_doc_type=='11')||(id_doc_type=='12'))
                {
                    this.$refs.filesmult.click();
                }
                else
                {
                    this.$refs.files.click();
                }

            },
            handleMultFilesUpload()
            {
                console.log('Правый список мультизагрузка')

                let flag = 0;
                let uploadedFiles = this.$refs.filesmult.files;
                let filesData=[]
                let formData = new FormData();
                for(let i = 0; i < uploadedFiles.length; i++)
                  {
                let reg ='';
                let full_name=(uploadedFiles[i].name.match(/([A-Za-zа-яА-Я0-9Ёё@ : , ' '' ; - _ = < > % # ~ `& !\W]+)/))
                let file_name=(uploadedFiles[i].name.match(/([A-Za-zа-яА-Я0-9Ёё@ : , ' '' ; - _ = < > % # ~ `& !\W]+)\./))
                let extension=(uploadedFiles[i].name.slice((Math.max(0, (uploadedFiles[i].name.lastIndexOf(".")) || Infinity) + 1)));
                      // Создание объекта с информацией о файле
                      let fileInfo = {
                          full_name: full_name ? full_name[0] : '',
                          file_name: file_name ? file_name[1] : '',
                          extension: extension
                      };
                      // Добавление объекта в массив filesData
                      filesData.push(fileInfo);
                  }
console.log(filesData)
               // let file = uploadedFiles;

                for (let i = 0; i < uploadedFiles.length; i++) {
                    formData.append('files[]', uploadedFiles[i]);
                }

               // formData.append('files', file);
                formData.append('files_data',JSON.stringify(filesData));
                formData.append('grade_id',this.order_id);
                formData.append('id_ts',this.right_current_TS);
                formData.append('id_pogruzka',this.current_id_pogruzka);
                formData.append('id_doc_type',this.current_id_doc_type);


                axios.post( '/store_grade_file_mult',
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                )
                    .then(response => {
                        if(response.data.id_doc_type==='8')
                        {
                            this.changeNameDocuments(response.data.id_doc_type,response.data.createdDocsInfo)
                        }
                        if(response.data.id_doc_type==='9')
                        {
                            this.changeNameDocuments(response.data.id_doc_type,response.data.createdDocsInfo)
                        }
                        if(response.data.id_doc_type==='10')
                        {
                            this.changeNameDocuments(response.data.id_doc_type,response.data.createdDocsInfo)
                        }
                        if(response.data.id_doc_type==='11')
                        {
                            this.changeNameDocuments(response.data.id_doc_type,response.data.createdDocsInfo)
                        }
                        if(response.data.id_doc_type==='12')
                        {
                            this.changeNameDocuments(response.data.id_doc_type,response.data.createdDocsInfo)
                        }

                    })


            },
            changeNameDocuments(typeId,docInfo)
            {
                for (let i = 0; i < this.spisokTShead[this.right_currentTS_In_Arr].adres_pogruzki_TS.length; i++) {
                    if(this.current_id_pogruzka== this.spisokTShead[this.right_currentTS_In_Arr].adres_pogruzki_TS[i].id_pogruzka)
                    {
                        let temp=''
                        temp=this.spisokTShead[this.right_currentTS_In_Arr].adres_pogruzki_TS[i].adres_pogruzki
                        this.spisokTShead[this.right_currentTS_In_Arr].adres_pogruzki_TS[i].adres_pogruzki=''
                        this.spisokTShead[this.right_currentTS_In_Arr].adres_pogruzki_TS[i].adres_pogruzki=temp
                        if(typeId==='8')
                        {
                            this.spisokTShead[this.right_currentTS_In_Arr].adres_pogruzki_TS[i].doc_name_DOC=docInfo
                        }
                        if(typeId==='9')
                        {
                            this.spisokTShead[this.right_currentTS_In_Arr].adres_pogruzki_TS[i].doc_name_FOTO=docInfo
                        }
                        if(typeId==='10')
                        {
                            this.spisokTShead[this.right_currentTS_In_Arr].adres_vygr_TS[i].doc_name_DOC=docInfo
                        }
                        if(typeId==='11')
                        {
                            this.spisokTShead[this.right_currentTS_In_Arr].adres_vygr_TS[i].doc_name_FOTO=docInfo
                        }
                        if(typeId==='12')
                        {
                            this.spisokTShead[this.right_currentTS_In_Arr].adres_vygr_TS[i].doc_name_ACT=docInfo
                        }

                        if(this.right_col_down_show==true)
                        {
                            this.right_col_down_show=false;
                        }
                        this.right_col_down_show=true;
                    }
                }
            },
            handleFilesUpload(){

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

                                for (let i = 0; i < this.spisokTShead[this.right_currentTS_In_Arr].adres_pogruzki_TS.length; i++) {
                                    if(this.current_id_pogruzka== this.spisokTShead[this.right_currentTS_In_Arr].adres_pogruzki_TS[i].id_pogruzka)
                                    {
                                        console.log('current_pogruzka')
                                        console.log(i)

                                let temp=''
                                temp=this.spisokTShead[this.right_currentTS_In_Arr].adres_pogruzki_TS[i].adres_pogruzki
                                this.spisokTShead[this.right_currentTS_In_Arr].adres_pogruzki_TS[i].adres_pogruzki=''
                                 this.spisokTShead[this.right_currentTS_In_Arr].adres_pogruzki_TS[i].adres_pogruzki=temp
                                this.spisokTShead[this.right_currentTS_In_Arr].adres_pogruzki_TS[i].doc_name=response.data.name_doc
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
                                for (let i = 0; i < this.spisokTShead[this.right_currentTS_In_Arr].adres_vygr_TS.length; i++) {
                                    if(this.current_id_pogruzka== this.spisokTShead[this.right_currentTS_In_Arr].adres_vygr_TS[i].id_pogruzka)
                                    {
                                    let temp = ''
                                    temp = this.spisokTShead[this.right_currentTS_In_Arr].adres_vygr_TS[i].adres_pogruzki
                                    this.spisokTShead[this.right_currentTS_In_Arr].adres_vygr_TS[i].adres_pogruzki = ''
                                    this.spisokTShead[this.right_currentTS_In_Arr].adres_vygr_TS[i].adres_pogruzki = temp
                                    this.spisokTShead[this.right_currentTS_In_Arr].adres_vygr_TS[i].doc_name = response.data.name_doc
                                    if (this.right_col_down_show == true) {
                                        this.right_col_down_show = false;
                                    }
                                    this.right_col_down_show = true;
                                }
                            }
                                 }

                            if(response.data.id_doc_type==='3')
                            {
                                this.spisokTShead[this.right_currentTS_In_Arr].podpisannaya_doc_name=response.data.name_doc
                                if (this.right_col_down_show == true) {
                                    this.right_col_down_show = false;
                                }
                                this.right_col_down_show = true;
                            }
                            if(response.data.id_doc_type==='4')
                            {
                                this.spisokTShead[this.right_currentTS_In_Arr].schet_doc_name=response.data.name_doc
                                if (this.right_col_down_show == true) {
                                    this.right_col_down_show = false;
                                }
                                this.right_col_down_show = true;
                            }
                            if(response.data.id_doc_type==='5')
                            {
                                this.spisokTShead[this.right_currentTS_In_Arr].faktura_doc_name=response.data.name_doc
                                if (this.right_col_down_show == true) {
                                    this.right_col_down_show = false;
                                }
                                this.right_col_down_show = true;
                            }
                            if(response.data.id_doc_type==='6')
                            {
                                this.spisokTShead[this.right_currentTS_In_Arr].TN_doc_name=response.data.name_doc
                                if (this.right_col_down_show == true) {
                                    this.right_col_down_show = false;
                                }
                                this.right_col_down_show = true;
                            }
                        })


            },
            //важынй метод, получающий номер в массиве spisokTShead по id_ts
            getCurrentTSId()
            {
                for (let i = 0; i < this.spisokTShead.length; i++) {

                    if(this.right_current_TS==this.spisokTShead[i].id_ts)
                    {
                        return i;
                    }
                }
            },
            show_mod_edit(id)
            {
                //вызов метода дочернего компонента( модального окна )
                this.$refs.modalComponentforAction.get_modal_edit_data(id)
            },
            onlyNumber ($event) {



                //console.log($event.keyCode); //keyCodes value
                let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
                if ((keyCode < 48 || keyCode > 57)&& keyCode !== 46) { // 46 is dot
                    $event.preventDefault();
                }
                // if (tempSumma>this.spisokTShead[this.right_currentTS_In_Arr].stavka_summa) { // 46 is dot
                //     $event.preventDefault();
                // }
            },
            checkSumma(elem1,id_summa,summa,key)
            {
                this.spisokTShead[this.right_currentTS_In_Arr].summa_list[key].summa=this.setToNumberFormatData(summa)
                //сумма всех оплат
                let tempSumma=0;
                for (let i = 0; i < this.spisokTShead[this.right_currentTS_In_Arr].summa_list.length; i++) {
                    tempSumma=Number(tempSumma)+Number(this.spisokTShead[this.right_currentTS_In_Arr].summa_list[i].summa)
                }
                //если сумма всех оплат больше общей суммы то вырезаем один знак
                if(tempSumma>this.spisokTShead[this.right_currentTS_In_Arr].stavka_summa)
                {
                    let numberString = this.spisokTShead[this.right_currentTS_In_Arr].summa_list[key].summa.toString(); // Преобразование числа в строку
                    let trimmedNumberString = numberString.slice(0, -1); // Удаление последнего символа
                    if(trimmedNumberString.length=='')
                    {
                        trimmedNumberString=0
                    }
                    let trimmedNumber = parseInt(trimmedNumberString); // Преобразование строки обратно в число (если необходимо)
                    this.spisokTShead[this.right_currentTS_In_Arr].summa_list[key].summa=trimmedNumber
                }
            },
            update_one_data_summa(elem,id_summa,name,data_to_up)
            {
                if(data_to_up=='')
                {
                    data_to_up=0
                }
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
                if(name=='stavka_summa')
                {
                    if(elem.stavka_summa=='')
                    {
                        elem.stavka_summa=0
                    }
                }
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
                                        vid_TSNazvanie:entry.vid_TSNazvanie,
                                        perevozchik_TSNazvanie:entry.perevozchik_TSNazvanie,
                                        voditel_TSNazvanie:entry.voditel_TSNazvanie,
                                        TS_TSNazvanie:entry.TS_TSNazvanie,
                                        PP_Nazvanie:entry.PP_Nazvanie,
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
            editVoditel(idVoditel)
            {
                //вызов метода дочернего компонента( модального окна )
                this.$refs.modalComponentforActionVoditel.get_modal_edit_data(idVoditel)
            },
            add_pogruzka_grade()
            {
                let arr_to_DB=[];
                for (let i = 0; i < this.spisokTShead[this.right_currentTS_In_Arr].adres_pogruzki_TS.length; i++) {
                    arr_to_DB.push(this.spisokTShead[this.right_currentTS_In_Arr].adres_pogruzki_TS[i])
                }
                for (let i = 0; i < this.spisokTShead[this.right_currentTS_In_Arr].adres_vygr_TS.length; i++) {
                    arr_to_DB.push(this.spisokTShead[this.right_currentTS_In_Arr].adres_vygr_TS[i])
                }

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
                //определяем id ts
                if(this.spisokTShead.length==0)
                    {
                        this.right_current_TS=this.spisokTShead.length;
                    }
               else
                    {
                        this.right_current_TS=this.spisokTShead[this.spisokTShead.length-1].id_ts
                        this.right_current_TS=Number(this.right_current_TS)+Number(1)
                    }

                this.right_currentTS_In_Arr=this.spisokTShead.length;

                    let objToPush= {};
                    objToPush['id_ts'] = this.right_current_TS;
                    objToPush['kol_gruz_TS'] = this.spisokTSarr[key].kol_gruz_TS;
                    objToPush['stavka_TS'] = this.spisokTSarr[key].stavka_TS;
                    objToPush['vid_TS'] = this.spisokTSarr[key].vid_TS;
                    objToPush['vid_TSNazvanie'] = this.spisokTSarr[key].vid_TSNazvanie;
                    objToPush['perevozchik_TSNazvanie'] = this.spisokTSarr[key].perevozchik_TSNazvanie;
                    objToPush['voditel_TSNazvanie'] = this.spisokTSarr[key].voditel_TSNazvanie;
                    objToPush['TS_TSNazvanie'] = this.spisokTSarr[key].TS_TSNazvanie;
                    objToPush['PP_Nazvanie'] = this.spisokTSarr[key].PP_Nazvanie;
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
                    objToPush2['adres_pogruzki'] =' ';
                    objToPush2['adres_pogruzki_show'] ='';
                    objToPush2['id_ts'] = this.right_current_TS;
                    objToPush2['grade_id'] = this.order_id;
                    objToPush2['pogruzka_or_vygruzka'] = 1;
                    objToPush2['time_ts'] = '';
                    objToPush2['date_ts'] = '';
                    objToPush2['id_pogruzka'] =0;
                    objToPush2['doc_name'] ='';
                    objToPush2['show_DP_date'] =false;
                    objToPush2['show_DP_time'] =false;
                    objToPush2['doc_name_DOC'] =[];
                    objToPush2['doc_name_FOTO'] =[];

                    this.spisokTShead[leng].adres_pogruzki_TS.push(objToPush2);
                }
                else
                {
                for (let i = 0; i < this.spisokTSarr[key].adres_pogruzki_TS.length; i++) {
                    let objToPush2= {};
                    objToPush2['adres_pogruzki'] = this.spisokTSarr[key].adres_pogruzki_TS[i].adres_pogruzki;
                    objToPush2['adres_pogruzki_show'] =this.spisokTSarr[key].adres_pogruzki_TS[i].adres_pogruzki_show;
                    objToPush2['id_ts'] = this.right_current_TS;
                    objToPush2['grade_id'] = this.spisokTSarr[key].adres_pogruzki_TS[i].order_id;
                    objToPush2['pogruzka_or_vygruzka'] = this.spisokTSarr[key].adres_pogruzki_TS[i].pogruzka_or_vygruzka;
                    objToPush2['time_ts'] = '';
                    objToPush2['date_ts'] = '';
                    objToPush2['id_pogruzka'] = i;
                    objToPush2['doc_name'] ='';
                    objToPush2['show_DP_date'] =false;
                    objToPush2['show_DP_time'] =false;
                    objToPush2['doc_name_DOC'] =[];
                    objToPush2['doc_name_FOTO'] =[];
                    this.spisokTShead[leng].adres_pogruzki_TS.push(objToPush2);
                }
                }
                if(this.spisokTSarr[key].adres_vygr_TS.length==0)
                {
                    let objToPush2= {};
                    objToPush2['adres_pogruzki'] =' ';
                    objToPush2['adres_vygruzki_show'] ='';
                    objToPush2['id_ts'] = this.right_current_TS;
                    objToPush2['grade_id'] = this.order_id;
                    objToPush2['pogruzka_or_vygruzka'] = 2;
                    objToPush2['time_ts'] = '';
                    objToPush2['date_ts'] = '';
                    objToPush2['id_pogruzka'] =0;
                    objToPush2['doc_name'] ='';
                    objToPush2['show_DP_date'] =false;
                    objToPush2['show_DP_time'] =false;
                    objToPush2['doc_name_DOC'] =[];
                    objToPush2['doc_name_FOTO'] =[];
                    objToPush2['doc_name_ACT'] =[];
                    this.spisokTShead[leng].adres_vygr_TS.push(objToPush2);
                }
                else{
                for (let i = 0; i < this.spisokTSarr[key].adres_vygr_TS.length; i++) {
                    let objToPush2= {};
                    objToPush2['adres_pogruzki'] = this.spisokTSarr[key].adres_vygr_TS[i].adres_pogruzki;
                    objToPush2['adres_vygruzki_show'] =this.spisokTSarr[key].adres_vygr_TS[i].adres_vygruzki_show;
                    objToPush2['id_ts'] = this.right_current_TS;
                    objToPush2['grade_id'] = this.spisokTSarr[key].adres_vygr_TS[i].order_id;
                    objToPush2['pogruzka_or_vygruzka'] = this.spisokTSarr[key].adres_vygr_TS[i].pogruzka_or_vygruzka;
                    objToPush2['time_ts'] = '';
                    objToPush2['date_ts'] = '';
                    objToPush2['id_pogruzka'] = i;
                    objToPush2['doc_name'] ='';
                    objToPush2['show_DP_date'] =false;
                    objToPush2['show_DP_time'] =false;
                    objToPush2['doc_name_DOC'] =[];
                    objToPush2['doc_name_FOTO'] =[];
                    objToPush2['doc_name_ACT'] =[];
                    this.spisokTShead[leng].adres_vygr_TS.push(objToPush2);
                }
                }

                //общее сохранение ТС
                  this.update_final_grade();
                //сохранение погрузки
                  this.add_pogruzka_grade();
                  //сохранение суммы
                  this.save_start_summa();


                if(this.right_col_down_show==true)
                {
                    this.right_col_down_show=false;
                }
                this.right_col_down_show=true;

            },
            click_on_right_col(elem,key)
            {
                if(this.right_current_TS===elem.id_ts)
                {
                    this.right_col_down_show=false;
                    this.right_current_TS=''
                    this.right_currentTS_In_Arr=''
                }
                else
                {

                    if(!this.deleteFlag)
                    {
                        //переменная для id_ts текущего ТС в правой колонке
                        this.right_current_TS=elem.id_ts
                        //переменная обозначающая место текущего ТС в массиве правой колонки
                        this.right_currentTS_In_Arr=key
                        if(this.right_col_down_show==true)
                        {
                            this.right_col_down_show=false;
                        }
                        this.right_col_down_show=true;
                    }
                    else
                    {
                        this.deleteFlag=false
                    }
                }
                console.log('TS')
                console.log(this.right_current_TS)
                console.log('right_currentTS_In_Arr')
                console.log(this.right_currentTS_In_Arr)
            },
          add_new_adres_pogruzka(key, pogruzka_or_vygruzka) {
            let objToPush2 = {}
            objToPush2['adres_pogruzki'] = '',
                objToPush2['id_ts'] = this.right_current_TS,
                objToPush2['grade_id'] = this.order_id,
                objToPush2['pogruzka_or_vygruzka'] = pogruzka_or_vygruzka,
                objToPush2['time_ts'] = '',
                objToPush2['date_ts'] = '',
                objToPush2['id_pogruzka'] = ''
            objToPush2['show_DP_date'] = false
            objToPush2['show_DP_time'] = false
            objToPush2['doc_name_DOC'] = [];
            objToPush2['doc_name_FOTO'] = [];
            if (pogruzka_or_vygruzka == 2) {
              objToPush2['doc_name_ACT'] = [];
            }


            if (pogruzka_or_vygruzka == 1) {
              this.spisokTShead[key].adres_pogruzki_TS.push(objToPush2)
              axios
                  .post('/add_new_adres_pogruzka', {
                    id_ts: this.right_current_TS,
                    grade_id: this.order_id,
                    pogruzka_or_vygruzka: pogruzka_or_vygruzka
                  })
                  .then(({data}) => (
                      this.spisokTShead[key].adres_pogruzki_TS[this.spisokTShead[key].adres_pogruzki_TS.length - 1].id_pogruzka = data.id_pogruzka
                  ))
            } else {
              this.spisokTShead[key].adres_vygr_TS.push(objToPush2)
              axios
                  .post('/add_new_adres_pogruzka', {
                    id_ts: this.right_current_TS,
                    grade_id: this.order_id,
                    pogruzka_or_vygruzka: pogruzka_or_vygruzka
                  })
                  .then(({data}) => (
                      this.spisokTShead[key].adres_vygr_TS[this.spisokTShead[key].adres_vygr_TS.length - 1].id_pogruzka = data.id_pogruzka
                  ))
            }


          },
            add_summ (key)
            {
                let objToPush1= {};
                objToPush1['id'] = '';
                objToPush1['summa'] = '';
                objToPush1['data'] = '';
                objToPush1['show_DP_date'] =false;
                if(this.spisokTShead[key].summa_list.length==0)
                {
                    objToPush1['id_summa'] =0;
                }
                else
                {
                    objToPush1['id_summa'] =Number(this.spisokTShead[key].summa_list[this.spisokTShead[key].summa_list.length-1].id_summa)+Number(1);
                }
                this.spisokTShead[key].summa_list.push(objToPush1);
                axios
                    .post('/add_summ',{
                        id_ts:this.right_current_TS,
                        grade_id:this.order_id,
                        id_summa:objToPush1['id_summa']
                    })
                 .then(response => {
                     if(this.spisokTShead[key].summa_list.length==0)
                     {
                         this.spisokTShead[key].summa_list[0].id = response.data.id;
                     }
                     else
                     {
                         this.spisokTShead[key].summa_list[this.spisokTShead[key].summa_list.length-1].id = response.data.id;
                     }
                  })
            },
          async  deleteSumm(id,keyTS,keySumma)
            {
                const result = await this.confirmMethodMixin();

                if (result) {
                axios
                    .post('/deleteSumm',{
                        id:id
                    })
                   .then(response => {
                       this.spisokTShead[keyTS].summa_list.splice(keySumma,1)
                   })
            }
            },
            save_start_summa()
            {
                axios
                    .post('/save_start_summa',{
                        id_ts:this.right_current_TS,
                        grade_id:this.order_id
                    })
            },
            download_pogruzka_vygr_files1(pogr_vygr,id_pogruzka)
            {
                window.location.href = '/download_pogruzka_vygr_files/'+this.order_id+'/'+this.right_current_TS+'/'+id_pogruzka+'/'+pogr_vygr;
            },
            async download_pogruzka_vygr_files(pogr_vygr,id_pogruzka) {
                try {
                    const response = await axios.post(`/download_pogruzka_vygr_files/${this.order_id}/${this.right_current_TS}/${id_pogruzka}/${pogr_vygr}`, {
                        responseType: 'blob',
                    });

                    response.data.forEach(file => {
                        const blob = new Blob([file.content]);
                        const link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = file.name;
                        link.click();
                    });
                } catch (error) {
                    console.error('Ошибка при загрузке файлов:', error);
                }
            },
            download_all_doc_grade (){
                window.location.href = '/download_all_doc_grade/'+this.order_id+'/'+this.right_current_TS;
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
                                    vid_TSNazvanie : entry.vid_TSNazvanie,
                                    perevozchik_TSNazvanie : entry.perevozchik_TSNazvanie,
                                    voditel_TSNazvanie : entry.voditel_TSNazvanie,
                                    TS_TSNazvanie : entry.TS_TSNazvanie,
                                    PP_Nazvanie : entry.PP_Nazvanie,
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

                //берём список старых терминалов
                for (let i = 0; i < this.spisokTSarr.length; i++) {
                    //если поставлена галочка на терминале
                    // if(this.spisokTSarr[i].checked2==1)
                    // {
                        this.ob_budzet_left=Number(this.ob_budzet_left)+(Number(this.spisokTSarr[i].stavka_TS)*Number(this.spisokTSarr[i]['kol_TS_TS']))

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
                    // }
                };
                for (let i = 0; i < this.na_terminale_arr.length; i++) {
                    for (let j = 0; j < this.termList.length; j++) {
                        if(this.na_terminale_arr[i]['terminal_TS']==this.termList[j]['id'])
                        {
                            this.na_terminale_arr[i]['terminal_name']=this.termList[j]['name']

                            // let objToPush= {};
                            // objToPush['id_ts'] = true;
                            // this.show_arr_TS.push(objToPush);
                        }
                    }
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
                        ),
                this.get_start_data_grade(this.oplata_arr,this.spisokTSarr)
                    );
            },
            parentMethodFromAutoinputVidts(data)
            {
                //получить название и запихнуть в главный массив правой колонки в строку название
                //id тоже
                data.elem1.vid_TS=data.vid_TS
                this.update_one_data(data.elem1,'vid_TS')
                for (let i = 0; i < this.spisokTShead.length; i++) {
                        if(this.spisokTShead[i]['id_ts']==data.elem1['id_ts'])
                        {
                            this.spisokTShead[i]['vid_TS']=data.vid_TS
                            this.spisokTShead[i]['vid_TSNazvanie']=data.ts_name
                        }
                }

            },
            //сохранить то что пришло на фронте и записать на бэк
            parentMethodFromAutoinputPerevozka(data)
            {
                data.elem1.perevozchik=data.id
                this.update_one_data(data.elem1,'perevozchik')
                for (let i = 0; i < this.spisokTShead.length; i++) {
                    if(this.spisokTShead[i]['id_ts']==data.elem1['id_ts'])
                    {
                        // this.spisokTShead[i]['perevozchik']=data.vid_TS
                        this.spisokTShead[i]['perevozchik']=data.id
                        this.spisokTShead[i]['perevozchik_TSNazvanie']=data.forma_id+' '+data.ts_name
                    }
                }
            this.pogruzkaShowInp=false

            },
            //сохранить то что пришло на фронте Водитель и записать на бэк
            parentMethodFromAutoinputVoditel(data)
            {
                data.elem1.voditel=data.id
                this.update_one_data(data.elem1,'voditel')
                for (let i = 0; i < this.spisokTShead.length; i++) {
                    if(this.spisokTShead[i]['id_ts']==data.elem1['id_ts'])
                    {
                       // this.spisokTShead[i]['voditel']=data.vid_TS
                        this.spisokTShead[i]['voditel_TSNazvanie']=data.ts_name
                    }
                }
                this.voditelShowInp=false
            },
            //сохранить то что пришло на фронте Водитель и записать на бэк
            parentMethodFromAutoinputTSModal(data)
            {
                data.elem1.nomer_TS=data.id
                this.update_one_data(data.elem1,'nomer_TS')
                for (let i = 0; i < this.spisokTShead.length; i++) {
                    if(this.spisokTShead[i]['id_ts']==data.elem1['id_ts'])
                    {
                        // this.spisokTShead[i]['voditel']=data.vid_TS
                        this.spisokTShead[i]['TS_TSNazvanie']=data.ts_name
                    }
                }
                this.TSShowInp=false
            },
            //сохранить то что пришло на фронте Водитель и записать на бэк
            parentMethodFromAutoinputPP(data)
            {
                console.log(data.elem1)
                data.elem1.nomer_PP=data.id
                this.update_one_data(data.elem1,'nomer_PP')
                for (let i = 0; i < this.spisokTShead.length; i++) {
                    if(this.spisokTShead[i]['id_ts']==data.elem1['id_ts'])
                    {
                        // this.spisokTShead[i]['voditel']=data.vid_TS
                        this.spisokTShead[i]['PP_Nazvanie']=data.ts_name
                    }
                }
                this.PPShowInp=false
            },
            update_one_data_number(elem,name,data)
            {
                elem[name]=this.setToNumberFormatData(data)
                if(name=='stavka_summa')
                {
                    if(elem.stavka_summa=='')
                    {
                        elem.stavka_summa=0
                    }
                }
                axios
                    .post('/update_one_data',{
                        elem:elem,
                        name:name,
                        grade_id:this.order_id
                    })
            },

        }
    }
</script>

