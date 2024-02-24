<template>
    <div class="container" id="scroll_block">
        <div class="row">
            <modal-pogruzka-component
                :get_gruzootpravitel_list='get_gruzootpravitel_list'
                :select_gruzootpravitel='select_gruzootpravitel'
                :select_Manager='select_Manager'
                allNew=true
                edit_flag=true
                vid="CreateOrdersComponent"
                ref="modalComponentforAction"
            ></modal-pogruzka-component>
            <modal-author-component   ref="modalComponentforActionAuthor"
                                      edit_flag=true
                                      :chahgeFrontNames='chahgeFrontNames'
                                      vid="GruzzotpravitelComponent"
            ></modal-author-component>
            <modal-perevozchiki-component
                ref="modalComponentforActionPerevozchik"
                vid="CreateOrdersComponent"
                :gradeAddPerevozchik='gradeAddPerevozchik'
            ></modal-perevozchiki-component>
            <div class="col-12 main_head_marg row">
                <div  class="col-6 orders_create_title">
                     {{ order_header_text }} {{ data_vneseniya }}
                </div>
                <div class="col-4 orders_create_title create_orders_status">{{ status }}</div>
            </div>
            <div class="col-12 second_header_cr_order">
                <div class="col-10 create_orders_second_title row">
                    <div class="col-3">
                        <span  class="create_orders_date_title">Дата внесения:</span>
                        <span class="create_orders_date_title_int">{{ data_vneseniya }}</span>
                        <span @click="openDB0">
                        <span class="iconify edit_icon" data-icon="akar-icons:edit" style="color: #a6a6a6;" data-width="20" data-height="20"></span>
                       </span>
                        <date-picker ref="datepicker0" v-model="data_vneseniya" type="datetime" valueType="format"  format="DD.MM.YYYY H:mm" :open.sync="openDP" @change="updateOrderLoc('data_vneseniya',data_vneseniya)"></date-picker>
                    </div>
                    <div class="col-4" v-show="checkRolePermission([1])">
                        <span  class="create_orders_date_title">Логист:</span>
                        <select @change="handleChange" v-if="logist_list"  class="create_orders_date_title_int cr_ord_inp_n_1" v-model="logist">
                            <option  v-bind:value=0  class="sel_cust">Логист не выбран</option>
                            <option v-for="(logist) in logist_list_full" v-bind:value=logist.id  class="sel_cust">{{ logist.full_logist_name }}</option>
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
                        <date-picker ref="datepicker1"  v-model="rasschitat_do" type="datetime" valueType="format" format="DD.MM.YYYY H:mm"  :open.sync="openDP1" @change="updateOrderLoc('rasschitat_do',rasschitat_do)"></date-picker>
                    </div>
                    <div class="col-2 justify-content-end" v-if="ocenka_show_button" v-on:click="setColumn('ocenka')">
                        <div class="col add_ts_button4 text-center">Оценка</div>
                    </div>
                    <div class="col-2 justify-content-end" v-if="utverzdenie_show_button" v-on:click="setColumn('naznachenie_stavki')">
                        <div class="col add_ts_button4 text-center">Утверждение</div>
                    </div>
                    <div class="col-2 justify-content-end" v-if="v_rabote_show_button" v-on:click="setColumn('v_rabote')">
                        <div class="col add_ts_button4 text-center">В работе</div>
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
                                <select @blur="updateOrderLoc('vid_perevozki',vid_perevozki)" class="cr_ord_inp_n_1" v-model="vid_perevozki">
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
                                Номер запроса
                            </div>
                            <input @blur="updateOrderNomerZaprosa()" class="cr_ord_inp_n_1 border_input" v-model="new_nomer_zayavki"  />
                        </div>
                        <div class="col" v-show="checkRolePermission([1])">
                            <div class="little_title_create_orders">
                                Компания заказчик
                                <span class="add_button n1" v-b-modal.modal-xl variant="primary" v-on:click="select_temp_var('zakazchik',0)">Добавить</span>
                            </div>
                            <div class="cr_ord_inp_n_1 add_button_grade_perevozka" v-if="zakazchikShowInp&&kompaniya_zakazchik_name==''" v-on:click="zakazchikShowInpChange()">Выбрать заказчика</div>
                            <div class="cr_ord_inp_n_1" v-if="zakazchikShowInp" v-on:click="zakazchikShowInpChange()">{{ kompaniya_zakazchik_name }}</div>
                            <auto-input-zakazchik-component v-if="!zakazchikShowInp" class="select_width_grade"
                                                            :order_id="order_id"
                                                            :vidTsFromParent="kompaniya_zakazchik_name"
                                                            @childReturnMethod="parentMethodFromAutoinputZakazchik"
                                                            @childCloseAutoInput="closeParentAutoInput"
                                                            ref="AutoSelectComponent_vid_TS"
                            ></auto-input-zakazchik-component>
                        </div>
                        <div class="col" v-show="checkRolePermission([1])">
                            <div class="little_title_create_orders">
                                Менеджер заказчика
                                <span class="add_button n2" v-if="kompaniya_zakazchik_id" v-b-modal.modal-xl v-on:click="select_temp_var('manager',0)">Добавить</span>
                            </div>
                            <div class="cr_ord_inp_n_1 add_button_grade_perevozka" v-if="managerZakazchikShowInp&&managerZakazchik_name==''" v-on:click="managerShowInpChange()">Выбрать менеджера</div>
                            <div class="cr_ord_inp_n_1" v-if="managerZakazchikShowInp" v-on:click="managerShowInpChange()">{{ managerZakazchik_name }}</div>
                            <auto-input-manager-component v-if="!managerZakazchikShowInp" class="select_width_grade"
                                                            :order_id="order_id"
                                                            :vidTsFromParent="managerZakazchik_name"
                                                            :zakazchikId="kompaniya_zakazchik_id"
                                                            @childReturnMethod="parentMethodFromAutoinputManager"
                                                            @childCloseAutoInput="closeParentAutoInputManager"
                                                            ref="AutoSelectComponent_vid_TS"
                            ></auto-input-manager-component>
                        </div>

                        <div class="col-12 row" v-show="checkRolePermission([1])">
                            <span class=" cr_ord_inp_n_2 cena_kont_marg">
                                <div class="little_title_create_orders no_wrap_text" >
                                    Бюджет контракта
                                </div>
                                <input @blur="updateOrderLocNumber('cena_kontrakta',cena_kontrakta)" class="cr_ord_inp_n_2 border_input" v-model="cena_kontrakta"  />
                            </span>

                        </div>
                        <div v-for="(oplata,key) in oplata_arr" class="col-12 row">
                            <span class=" cr_ord_inp_n_2 cena_kont_marg">
                                <div class="little_title_create_orders">
                                    Оплата
                                </div>
                                <input @blur="update_order_oplata('oplata',oplata.id,oplata_arr[key].oplata)" class="cr_ord_inp_n_2 border_input" v-model="oplata_arr[key].oplata"  />
                           </span>
                            <span class=" cr_ord_inp_n_2 cena_kont_marg">
                                <div class="little_title_create_orders">
                                    Сумма
                                </div>
                                <input @blur="update_order_oplata('summa',oplata.id,oplata_arr[key].summa)" class="cr_ord_inp_n_2 border_input" v-model="oplata_arr[key].summa"  />
                                </span>
                            <span class="col-12 oplata_del" ><span v-on:click="delete_oplata_summa(oplata.id,key)" class="del_summa_text">удалить</span></span>
                        </div>
                    </div>
                    <div class="col-7 fit_height row middle_block_cr_order ">
                        <div class="col cr_ord_mid_col">
                            <div class="little_title_create_orders">
                                Номенклатура
                            </div>
                            <div class="create_orders_bottom">
                                <div class="cr_ord_div_nomenklatura">{{ nomenklatura }}</div>
                                <input hidden="true" type="file" id="files" ref="files"  v-on:change="handleFilesUpload('nom')"/>
                                <span class="excel_set" v-on:click="addFiles('nom')">
                                <span class="iconify" data-icon="file-icons:microsoft-excel" style="color: #4d4d4d;" data-width="24" data-height="24"></span>
                                </span>
                                <span class="excel_set" v-if="nomenklatura" v-on:click="DownloadFiles('nom')">
                                <span class="iconify" data-icon="material-symbols:sim-card-download-outline-rounded" style="color: #4d4d4d;" data-width="24" data-height="24"></span>
                                </span>
                                <nomenklatura-component
                                    :order_id="order_id"
                                    v-if="nomenklatura"
                                ></nomenklatura-component>
                            </div>

                            <div class="little_title_create_orders">
                                Готовый расчёт
                            </div>
                            <div class="create_orders_bottom">
                                <div class="cr_ord_div_nomenklatura">{{ gotovyi_raschet }}</div>
                                <input hidden="true" type="file" id="files_ready" ref="files_ready"  v-on:change="handleFilesUpload('ready')"/>
                                <span class="excel_set" v-on:click="addFiles('ready')">
                                <span class="iconify" data-icon="file-icons:microsoft-excel" style="color: #4d4d4d;" data-width="24" data-height="24"></span>
                                </span>
                                <span class="excel_set" v-if="gotovyi_raschet" v-on:click="DownloadFiles('ready')">
                                <span class="iconify" data-icon="material-symbols:sim-card-download-outline-rounded" style="color: #4d4d4d;" data-width="24" data-height="24"></span>
                                </span>
                            </div>

                            <div class="col ad_pogr_marg">
                                <div class="little_title_create_orders row">
                                   <div class="col no_wrap">Адрес погрузки</div>
                                </div>
                            <div class="cr_ord_inp_n_1" v-show="adresPogruzkiShowSpan&&((adres_pogruzke=='')||(adres_pogruzke==null))" v-on:click="inputShow('adresPogruzkiShowSpan')">Выбрать адрес</div>
                            <div class="cr_ord_inp_n_1" v-show="adresPogruzkiShowSpan" v-on:click="inputShow('adresPogruzkiShowSpan')">{{ adres_pogruzke }}</div>
                                <textarea class="cr_ord_inp_n_1" v-show="!adresPogruzkiShowSpan" @blur="updateOrderLoc('adres_pogruzke',adres_pogruzke)" v-model="adres_pogruzke" rows="3"  name="adres_pogruzke"></textarea>
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
                                        <date-picker ref="datepicker2" type="date" valueType="format" v-model="data_pogruzki" format="DD.MM.YYYY" :open.sync="openDP2" @change="handleChange0"></date-picker>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 row no_padding_right">
                                <div class="col-6 no_padding_left no_padding_right">
                                    <div class="little_title_create_orders">
                                        Кол-во грузомест
                                    </div>
                                    <div class="create_orders_bottom right_menu_nom row">
                                        <input @blur="updateOrderLocNumber('gruzomesta_big',gruzomesta_big)" class="cr_ord_inp_n_4 border_input" v-model="gruzomesta_big"  />
                                        <input @blur="updateOrderLocNumber('gruzomesta_small',gruzomesta_small)" class="cr_ord_inp_n_5 nom_margin border_input" v-model="gruzomesta_small"  readonly />
                                    </div>
                                </div>
                                <div class=" col-6 no_padding_right no_padding_left">
                                    <div class="little_title_create_orders no_padding_right">
                                        Расстояние, км
                                    </div>
                                    <div class="create_orders_bottom">
                                        <input @blur="updateOrderLocNumber('rasstojanie',rasstojanie)" class="cr_ord_inp_n_2 border_input" v-model="rasstojanie"  />
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 row no_padding_right">
                                <div class="col-5 no_padding_right no_padding_left">
                                    <div class="little_title_create_orders">
                                        Общий вес, кг
                                    </div>
                                    <div class="create_orders_bottom">
                                        <input @blur="updateOrderLocNumber('ob_ves',ob_ves)" class="cr_ord_inp_n_2 border_input" v-model="ob_ves"  />
                                    </div>
                                </div>
                                <div class="offset-1 col-6  no_padding_left no_padding_right">
                                    <div class="little_title_create_orders">
                                        Общий объём, м3
                                    </div>
                                    <div class="create_orders_bottom">
                                        <input @blur="updateOrderLocNumber('ob_ob',ob_ob)" class="cr_ord_inp_n_2 border_input" v-model="ob_ob"  />
                                    </div>
                                </div>
                            </div>
                            <div class="col ad_pogr_marg">
                                <div class="little_title_create_orders row">
                                    <div class="col no_wrap">Адрес выгрузки</div>
                                </div>
                                <div class="cr_ord_inp_n_1" v-show="adresVygruzkiShowSpan&&((adres_vygruski=='')||(adres_vygruski==null))" v-on:click="inputShow('adresVygruzkiShowSpan')">Выбрать адрес</div>
                                <div class="cr_ord_inp_n_1" v-show="adresVygruzkiShowSpan" v-on:click="inputShow('adresVygruzkiShowSpan')">{{ adres_vygruski }}</div>
                                <textarea class="cr_ord_inp_n_1" v-show="!adresVygruzkiShowSpan" @blur="updateOrderLoc('adres_vygruski',adres_vygruski)" v-model="adres_vygruski" rows="3"  name="adres_vygruski"></textarea>

                            </div>

                        </div>

                        <div class="big_comment">
                            <div class="little_title_create_orders">
                                Комментарий
                            </div>
                            <textarea class="comm_settings_1" @blur="updateOrderLoc('komment_1',komment_1)" v-model="komment_1" rows="6"  name="text"></textarea>
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
                                        <div class="col-12 no_padding_left d-flex justify-content-left no_padding_right create_ord_right_lit_text mt_ts_text ">Тип ТС</div>
                                        <div class="col-12 no_padding_left d-flex justify-content-left no_padding_right">
                                                <div v-for="(one_ts,key1) in ts_list_names" v-if="one_ts['id']==elem.vid_TS" class="col-12 create_order_right_main_text text_out_block no_padding_left">{{ one_ts.ts_name }}</div>
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
                                        <div class="col-12 no_padding_left d-flex justify-content-left no_padding_right create_ord_right_lit_text mt_ts_text">Ставка</div>
                                        <div class="col-12 no_padding_left d-flex justify-content-left no_padding_right create_order_right_main_text text_out_block">{{ elem.stavka_TS }}<span v-if="elem.stavka_TS">р.</span></div>
                                    </div>
                                    <div class="col-2  no_padding_right">
                                        <div class="col-12 no_padding_left d-flex justify-content-left no_padding_right create_ord_right_lit_text mt_ts_text">Ставка без НДС</div>
                                        <div class="col-12 no_padding_left d-flex justify-content-left no_padding_right create_order_right_main_text text_out_block">{{ elem.stavka_TS_bez_NDS }}<span v-if="elem.stavka_TS">р.</span></div>
                                    </div>
                                    <div class="col-2  no_padding_right">
                                        <div class="col-12 no_padding_left d-flex justify-content-left no_padding_right create_ord_right_lit_text mt_ts_text">Ставка за км</div>
                                        <div class="col-12 no_padding_left d-flex justify-content-left no_padding_right create_order_right_main_text text_out_block">{{ elem.stavka_TS_za_km }}<span v-if="elem.stavka_TS">р.</span></div>
                                    </div>
                                    <div class="col-2  no_padding_right" v-show="checkRolePermission([1])">
                                        <div class="col-12 no_padding_left d-flex justify-content-left no_padding_right create_ord_right_lit_text mt_ts_text">Ставка КП</div>
                                        <div class="col-12 no_padding_left d-flex justify-content-left no_padding_right create_order_right_main_text text_out_block">{{ elem.stavka_kp_TS }}<span v-if="elem.stavka_kp_TS">р.</span></div>
                                    </div>
                                    <div class="col-2  no_padding_right" v-show="checkRolePermission([1])">
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
                                            <div class="cr_ord_inp_n_7" v-for="(gruz,key1) in gruzootpravitel_arr" v-if="gruz['id']==elem1['adres_pogruzki']">{{ elem1.adres_pogruzke_show }}</div>
                                        </div>
                                             </div>
                                        <div class="col-6">
                                        <div class="col-12" v-for="(elem1,key1) in elem.adres_vygr_TS">
                                            <div class="create_ord_right_lit_text mt_ts_text">Адрес Выгрузки {{ key1 +1 }}</div>
                                            <div class="cr_ord_inp_n_7" v-for="(gruz,key1) in gruzootpravitel_arr" v-if="gruz['id']==elem1['adres_pogruzki']">{{ elem1.adres_vygruzki_show }}</div>
                                        </div>
                                        </div>
                                   </div>
                                </div>
<!--                            перевозчики-->
                               <div class="col-12 second_right_create_orders_text">

                                    <div class="col-12 row">
                                        <div class="col-12 row" v-for="(perevozchik,perevozchikKey) in elem.perevozchikiList">
                                            <div class="cr_ord_inp_n_7 col-4">
                                                <div class="text-center">
                                                   <b-popover :target="'myDiv-' + perevozchikKey + perevozchik['perevozchik_id'] + key" triggers="hover" placement="top" custom-class="wide-popover">
                                                <div v-for="(onePopUpPerevozchik,key1Perevozchik) in perevozchik['contacts']" :key="key1Perevozchik">
                                                   {{ onePopUpPerevozchik['FIO'] }} {{ onePopUpPerevozchik['dolznost'] }} {{ onePopUpPerevozchik['telefon'] }} {{ onePopUpPerevozchik['email'] }}
                                                </div>
                                                   </b-popover>
                                                </div>
                                                <span class="create_ord_right_lit_text" >Перевозчик:</span>
                                                <span :id="'myDiv-' + perevozchikKey + perevozchik['perevozchik_id'] + key">{{ perevozchik.perevozka.forma_id }} {{ perevozchik.perevozka.nazvanie }}</span>
                                            </div>
                                            <div class="col-4">
                                                <span class="create_ord_right_lit_text">Код АТИ:</span>
                                                {{ perevozchik.perevozka.kod_ATI }}
                                            </div>
                                            <div class="col-4">
                                                <span class="create_ord_right_lit_text">ИНН:</span>
                                                {{ perevozchik.perevozka.INN }}
                                            </div>
                                            <div class="col-4">
                                                <span class="create_ord_right_lit_text">Ставка с НДС:</span>
                                                {{ perevozchik.stavka_NDS }}
                                            </div>
                                            <div class="col-4">
                                                <span class="create_ord_right_lit_text">Ставка без НДС:</span>
                                                {{ perevozchik.stavka_bez_NDS }}
                                            </div>
                                            <div class="col-4">
                                                <span class="create_ord_right_lit_text">Ставка за км:</span>
                                                {{ perevozchik.stavka_za_km }}
                                            </div>

                                        </div>
                                   </div>
                                </div>
<!--                            конец перевозчики-->
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


                    <!--  Редактирование ТС-->
                    <div v-if="add_ts===key" class="row add_ts_class">
                        <div class="col spisok_ts_second_title_left">
                            <div class="little_title_create_orders2">
                                Тип ТС
                            </div>
                            <div class="create_orders_bottom">
                                         <auto-input-global-component class="select_width"
                                                               :vidTsFromParent="vidTsNazavanie"
                                                               :order_id="order_id"
                                                               :key_in_arr="key"
                                                               @childReturnMethod="parentMethodFromAutoinput"
                                                               ref="AutoSelectComponent_vid_TS"
                                         ></auto-input-global-component>
                            </div>
                            <div class="col-12 row">
                                <div class="col-6">
                                    <div class="little_title_create_orders2">
                                        Ставка
                                    </div>
                                    <div class="create_orders_bottom">
                                        <input class="cr_ord_inp_n_6 border_input" v-model="stavka_TS" @input="updateStavkaBezNDS" />
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="little_title_create_orders2">
                                        Ставка без НДС
                                    </div>
                                    <div class="create_orders_bottom">
                                        <input class="cr_ord_inp_n_6 border_input" v-model="stavka_TS_bez_NDS" @input="updateStavka"/>
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
                            <div class="col-12 row" v-show="checkRolePermission([1])">
                                <div class="col-6">
                                    <div class="little_title_create_orders2">
                                        Ставка КП
                                    </div>
                                    <div class="create_orders_bottom">
                                        <input class="cr_ord_inp_n_6 border_input" v-model="stavka_kp_TS" @blur="updateNumberToFormat('stavka_kp_TS',stavka_kp_TS)" />
                                    </div>
                                </div>
                                <div class="col-6" v-show="checkRolePermission([1])">
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
                                                <input class="cr_ord_inp_n_8 border_input" v-model="kol_gruz_TS" @blur="updateNumberToFormat('kol_gruz_TS',kol_gruz_TS)" />
                                            </div>
                                        </div>
                                        <div class="col cr_ord_lit_center no_padding_right">
                                            <div class="little_title_create_orders2">
                                                Кол-во ТС
                                            </div>
                                            <div class="create_orders_bottom">
                                                <input class="cr_ord_inp_n_9 border_input" v-model="kol_TS_TS" @blur="updateNumberToFormat('kol_TS_TS',kol_TS_TS)" />
                                            </div>
                                        </div>
                                        <div class="col cr_ord_lit_right no_padding_left">
                                            <div class="little_title_create_orders2">
                                                Расстояние, км
                                            </div>
                                            <div class="create_orders_bottom">
                                                <input class="cr_ord_inp_n_10 border_input" v-model="rasstojanie_TS" @input="updateStavkaPerevozchikRasstojanie('rasstojanie_TS',rasstojanie_TS)" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 ad_pogr_marg1">
                                        <input type="checkbox" id="checkbox" v-model="checkboxPogrVygr">
                                        <span class="head_font_grade">Адреса погрузки выгрузки</span>
                                    </div>
                                    <div v-if="checkboxPogrVygr" class="col-12 ad_pogr_marg1" v-for="(elem,key) in ad_pogruzki_arr_temp">
                                        <div class="little_title_create_orders2 ob_ob_width">
                                            Адрес погрузки
                                            <span class="add_button" v-b-modal.modal-xl variant="primary" v-on:click="select_temp_var('TS_pogruzka',key)">Добавить</span>
                                        </div>
                                        <auto-input-author-component class="cr_ord_inp_n_1"
                                                              inp_type='add_pogruzka_edit'
                                                              :adres_pogruzke_show="flag_pogruz"
                                                              :adres_pogruzke_show_edit="ad_pogruzki_arr_temp[key]['adres_pogruzke_show']"
                                                              :order_id="order_id"
                                                              v-bind:gruzootpravitel_arr="gruzootpravitel_arr"
                                                              :key_in_arr="key"
                                                              @add_pogruzka_new="add_new_pogruzka_in_ts"
                                                              ref="AutoSelectComponent_pogruzka_edit"
                                                                     :changePogrVygrAllShow="changePogrVygrAllShow"
                                        ></auto-input-author-component>
                                        <button type="button" class="btn btn-success" v-on:click="add_empty_adres_pogr()">+</button>
                                        <button type="button" class="btn btn-danger btn_del_in_ord" v-on:click="delete_adres(ad_pogruzki_arr_temp,key,'AutoSelectComponent_pogruzka_edit','adres_pogruzke_show')">-</button>
                                    </div>

                                </div>

                            <div v-if="checkboxPogrVygr" class="col no_padding_right">
                                <div class="col-12 ad_vygr_right_ord" v-for="(elem,key) in ad_vygruz_arr_temp">
                                    <div class="little_title_create_orders2 ob_ob_width">
                                        Адрес выгрузки
                                        <span class="add_button" v-b-modal.modal-xl variant="primary" v-on:click="select_temp_var('TS_vygruzka',key)">Добавить</span>
                                    </div>
                                    <div class="create_orders_bottom">
                                        <auto-input-author-component class="cr_ord_inp_n_1"
                                                              inp_type='add_vygruzka_edit'
                                                              :adres_pogruzke_show="flag_pogruz"
                                                              :adres_pogruzke_show_edit="ad_vygruz_arr_temp[key]['adres_vygruzki_show']"
                                                              :order_id="order_id"
                                                              v-bind:gruzootpravitel_arr="gruzootpravitel_arr"
                                                              :key_in_arr="key"
                                                              @add_pogruzka_new="add_new_pogruzka_in_ts"
                                                              ref="AutoSelectComponent_vygruzka_edit"
                                                                     :changePogrVygrAllShow="changePogrVygrAllShow"
                                        ></auto-input-author-component>
                                        <button type="button" class="btn btn-success" v-on:click="add_empty_adres_vygruz()">+</button>
                                        <button type="button" class="btn btn-danger btn_del_in_ord" v-on:click="delete_adres(ad_vygruz_arr_temp,key,'AutoSelectComponent_vygruzka_edit','adres_vygruzki_show')">-</button>

                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>

<!--                        перевозчики в редактировании списка ТС-->
                         <div class="col-12 second_right_create_orders_text">

                                    <div class="col-12 row">
                                        <div class="col-12 row per_status"  v-for="(onePerevozchik,keyPerevozchik) in perevozchikiList" :key="keyPerevozchik">
                                            <div class="col-8 row">
                                                <span class="create_ord_right_lit_text col-2">Перевозчик:</span>
                                                    <auto-input-perevozka-component
                                                        class="select_width_grade col-7 per_bot"
                                                        :order_id="order_id"
                                                        :vidTsFromParent="onePerevozchik.perevozka.nazvanie"
                                                        :elem1="keyPerevozchik"
                                                        @childReturnMethod="parentMethodFromAutoinputPerevozka"
                                                        @childCloseAutoInput="closeParentAutoInputPogruzka"
                                                        ref="AutoSelectComponent_vid_TS"
                                                    ></auto-input-perevozka-component>
                                                <span class="col-3 row add_button_grade no_wrap_text" v-b-modal.perevozkaMod variant="primary" v-on:click="newPerevozchik(keyPerevozchik)">
                                                    <span class="col-8">Добавить</span>
                                                        <span
                                                            class="col-4"
                                                            v-if="onePerevozchik.perevozchik_id!==''"
                                                            v-on:click="show_mod_edit_perevozchik(onePerevozchik.perevozchik_id,keyPerevozchik)"
                                                            v-b-modal.perevozkaMod variant="primary">
                                                                <span
                                                                    class="iconify edit_icon"
                                                                    data-icon="akar-icons:edit"
                                                                    style="color: #a6a6a6;"
                                                                    data-width="20" data-height="20"
                                                                ></span>
                                                        </span>
                                                    </span>
                                            </div>
                                            <div class="col-4">
                                                <span class="create_ord_right_lit_text">Код АТИ:</span>
                                                {{ onePerevozchik.perevozka.kod_ATI }}

                                            </div>
                                            <div class="col-4">
                                                <span class="create_ord_right_lit_text">ИНН:</span>
                                                {{ onePerevozchik.perevozka.INN }}
                                            </div>
                                            <div class="col-4">
                                                <span class="create_ord_right_lit_text">Ставка с НДС:</span>
                                         <input class="cr_ord_inp_n_st border_input" v-model="onePerevozchik.stavka_NDS" @input="updateStavkaPerevozchik('stavka_NDS',keyPerevozchik)"/>

                                            </div>
                                            <div class="col-4">
                                                <span class="create_ord_right_lit_text">Ставка без НДС:</span>
                                         <input class="cr_ord_inp_n_st border_input" v-model="onePerevozchik.stavka_bez_NDS" @input="updateStavkaPerevozchik('stavka_bez_NDS',keyPerevozchik)"/>
                                            </div>
                                              <div class="col-4">
                                                <span class="create_ord_right_lit_text">Ставка за км:</span>
                                                {{ onePerevozchik.stavka_za_km }}
                                            </div>
                                            <div class="col-4">
                                                <span v-on:click="deletePerevozchikFromOrder(onePerevozchik.id,keyPerevozchik)">Удалить</span>
                                            </div>
                                        </div>
                                   </div>
                         </div>


                        <div class="col-12 btn_add_per">
                            <button type="button" class="btn btn-success " v-on:click="addEmptyPerevozchik()">+Перевозчик</button>
                        </div>
<!--                        конец первозчиков в редактировании списка ТС-->



                        <div class="col-12">
                            <div class="little_title_create_orders2">
                                Комментарий
                            </div>
                            <textarea class="col-12" v-model="kommentari_TS" rows="4" name="text"></textarea>
                        </div>
                        <div class="col-11 save_buttons justify-content-end row ">
                            <div class="col offset-1 add_ts_button2 text-center" v-on:click="save_TS()">Сохранить</div>
                            <div class="col add_ts_button3 text-center" v-on:click="deleteTs()">Удалить</div>
                        </div>
                    </div>
                    <div v-if="add_ts===key" class="col-12 row create_ord_underline"></div>

                        <!--Конец редактирования ТС-->
 </span>
<!--                    Добавление нового ТС-->
                    <div v-if="add_new_ts" class="row add_ts_class">
                        <div class="col spisok_ts_second_title_left">
                            <div class="little_title_create_orders2">
                                Тип ТС
                            </div>
                            <div class="create_orders_bottom">
                                <auto-input-global-component class="select_width"

                                                             @childReturnMethod="parentMethodFromAutoinputNewVidts"
                                                             ref="AutoSelectComponent_vid_TS"
                                ></auto-input-global-component>
<!--                                <select  class="sel_cust select_width" v-model="vid_TS">-->
<!--                                    <option v-for="(ts_list_one) in ts_list_names" v-bind:value=ts_list_one.id  class="sel_cust">{{ ts_list_one.ts_name }}</option>-->
<!--                                </select>-->
                            </div>
                            <div class="col-12 row">
                                <div class="col-6">
                                    <div class="little_title_create_orders2">
                                        Ставка
                                    </div>
                                    <div class="create_orders_bottom">
                                        <input class="cr_ord_inp_n_6 border_input" v-model="stavka_TS" @input="updateStavkaBezNDS" />
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="little_title_create_orders2">
                                        Ставка без НДС
                                    </div>
                                    <div class="create_orders_bottom">
                                        <input class="cr_ord_inp_n_6 border_input" v-model="stavka_TS_bez_NDS" @input="updateStavka"/>
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
                            <div class="col-12 row" v-show="checkRolePermission([1])">
                                <div class="col-6">
                                    <div class="little_title_create_orders2">
                                        Ставка КП
                                    </div>
                                    <div class="create_orders_bottom">
                                        <input class="cr_ord_inp_n_6 border_input" v-model="stavka_kp_TS" @blur="updateNumberToFormat('stavka_kp_TS',stavka_kp_TS)" />
                                    </div>
                                </div>
                                <div class="col-6" v-show="checkRolePermission([1])">
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
                                                <input class="cr_ord_inp_n_8 border_input" v-model="kol_gruz_TS" @blur="updateNumberToFormat('kol_gruz_TS',kol_gruz_TS)" />
                                            </div>
                                        </div>
                                        <div class="col cr_ord_lit_center no_padding_right">
                                            <div class="little_title_create_orders2">
                                                Кол-во ТС
                                            </div>
                                            <div class="create_orders_bottom">
                                                <input class="cr_ord_inp_n_9 border_input" v-model="kol_TS_TS" @blur="updateNumberToFormat('kol_TS_TS',kol_TS_TS)" />
                                            </div>
                                        </div>
                                        <div class="col cr_ord_lit_right no_padding_left">
                                            <div class="little_title_create_orders2">
                                                Расстояние, км
                                            </div>
                                            <div class="create_orders_bottom">
                                                <input class="cr_ord_inp_n_10 border_input" v-model="rasstojanie_TS" @input="updateStavkaPerevozchikRasstojanie('rasstojanie_TS',rasstojanie_TS)" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 ad_pogr_marg1">
                                        <input type="checkbox" id="checkbox" v-model="checkboxPogrVygr">
                                        <span class="head_font_grade">Адреса погрузки выгрузки</span>
                                    </div>
                                    <div v-if="checkboxPogrVygr" class="col-12 ad_pogr_marg1" v-for="(elem,key) in ad_pogruzki_arr_temp">
                                        <div class="little_title_create_orders2 ob_ob_width">
                                            Адрес погрузки
                                            <span class="add_button" v-b-modal.modal-xl variant="primary" v-on:click="select_temp_var('TS_pogruzka_new',key)">Добавить</span>
                                        </div>
                                        <auto-input-author-component class="cr_ord_inp_n_1"
                                                              inp_type='add_pogruzka_empty'
                                                              adres_pogruzke_show=''
                                                              :order_id="order_id"
                                                              v-bind:gruzootpravitel_arr="gruzootpravitel_arr"
                                                              :key_in_arr="key"
                                                              @add_pogruzka_new="add_new_pogruzka_in_ts"
                                                              ref="AutoSelectComponent_pogruzka_empty"
                                                                     :changePogrVygrAllShow="changePogrVygrAllShow"
                                        ></auto-input-author-component>

                                        <button type="button" class="btn btn-success" v-on:click="add_empty_adres_pogr()">+</button>
                                        <button type="button" class="btn btn-danger btn_del_in_ord" v-on:click="delete_adres(ad_pogruzki_arr_temp,key,'AutoSelectComponent_pogruzka_empty','adres_pogruzke_show')">-</button>

                                    </div>

                                </div>

                                <div v-if="checkboxPogrVygr" class="col no_padding_right">
                                    <div class="col-12 ad_vygr_right_ord" v-for="(elem,key) in ad_vygruz_arr_temp">
                                        <div class="little_title_create_orders2 ob_ob_width">
                                            Адрес выгрузки
                                            <span class="add_button" v-b-modal.modal-xl variant="primary" v-on:click="select_temp_var('TS_vygruzka_new',key)">Добавить</span>
                                        </div>
                                        <div class="create_orders_bottom">
                                            <auto-input-author-component class="cr_ord_inp_n_1"
                                                                  inp_type='add_vygruzka_empty'
                                                                  adres_pogruzke_show=''
                                                                  :order_id="order_id"
                                                                  v-bind:gruzootpravitel_arr="gruzootpravitel_arr"
                                                                  :key_in_arr="key"
                                                                  @add_pogruzka_new="add_new_pogruzka_in_ts"
                                                                  ref="AutoSelectComponent_vygruzka_empty"
                                                                         :changePogrVygrAllShow="changePogrVygrAllShow"
                                            ></auto-input-author-component>
                                            <button type="button" class="btn btn-success" v-on:click="add_empty_adres_vygruz()">+</button>
                                            <button type="button" class="btn btn-danger btn_del_in_ord" v-on:click="delete_adres(ad_vygruz_arr_temp,key,'AutoSelectComponent_vygruzka_empty','adres_vygruzki_show')">-</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!--                        перевозчики в создании списка ТС-->
                        <span class="col-12">
                              <div class="col-12 second_right_create_orders_text">
                                    <div class="col-12 row">
                                        <div class="col-12 row per_status" v-for="(onePerevozchik,keyPerevozchik) in perevozchikiList">
                                            <div class="col-6 row">
                                                <span class="create_ord_right_lit_text col-2">Перевозчик:</span>
                                                 <auto-input-perevozka-component
                                                     class="select_width_grade col-7 per_bot"
                                                     :order_id="order_id"
                                                     :vidTsFromParent="onePerevozchik.perevozka.nazvanie"
                                                     :elem1="keyPerevozchik"
                                                     @childReturnMethod="parentMethodFromAutoinputPerevozka"
                                                     @childCloseAutoInput="closeParentAutoInputPogruzka"
                                                     ref="AutoSelectComponent_vid_TS"
                                                 ></auto-input-perevozka-component>
                                                    <span class="col-3 row add_button_grade no_wrap_text" v-b-modal.perevozkaMod variant="primary" v-on:click="newPerevozchik(keyPerevozchik)">
                                                    <span class="col-8">Добавить</span>
                                                        <span
                                                            v-if="onePerevozchik.perevozchik_id!==''"
                                                            class="col-4"
                                                            v-on:click="show_mod_edit_perevozchik(onePerevozchik.perevozchik_id,keyPerevozchik)"
                                                            v-b-modal.perevozkaMod variant="primary">
                                                                <span
                                                                    class="iconify edit_icon"
                                                                    data-icon="akar-icons:edit"
                                                                    style="color: #a6a6a6;"
                                                                    data-width="20" data-height="20"
                                                                ></span>
                                                        </span>
                                                    </span>

                                            </div>
                                            <div class="col-4">
                                                <span class="create_ord_right_lit_text">Код АТИ:</span>
                                                {{ onePerevozchik.perevozka.kod_ATI }}
                                            </div>
                                            <div class="col-4">
                                                <span class="create_ord_right_lit_text">ИНН:</span>
                                                {{ onePerevozchik.perevozka.INN }}
                                            </div>
                                            <div class="col-4">
                                                <span class="create_ord_right_lit_text">Ставка с НДС:</span>
                                         <input class="cr_ord_inp_n_st border_input" v-model="onePerevozchik.stavka_NDS" @input="updateStavkaPerevozchik('stavka_NDS',keyPerevozchik)"/>

                                            </div>
                                            <div class="col-4">
                                                <span class="create_ord_right_lit_text">Ставка без НДС:</span>
                                         <input class="cr_ord_inp_n_st border_input" v-model="onePerevozchik.stavka_bez_NDS" @input="updateStavkaPerevozchik('stavka_bez_NDS',keyPerevozchik)"/>
                                            </div>
                                            <div class="col-4">
                                                <span class="create_ord_right_lit_text">Ставка за км:</span>
                                                {{ onePerevozchik.stavka_za_km }}
                                            </div>
                                        </div>
                                   </div>
                         </div>


                        <div class="col-12 btn_add_per">
                            <button type="button" class="btn btn-success " v-on:click="addEmptyPerevozchik()">+Перевозчик</button>
                        </div>
                            </span>
                        <!--                        конец первозчиков в редактировании списка ТС-->



                        <div class="col-12">
                            <div class="little_title_create_orders2">
                                Комментарий
                            </div>
                            <textarea class="col-12" v-model="kommentari_TS" rows="4" name="text"></textarea>
                        </div>
                        <div class="col-11 save_buttons justify-content-end row">
                            <div class="col offset-1 add_ts_button2 text-center" v-on:click="save_TS()">Сохранить</div>
                            <div class="col add_ts_button3 text-center" v-on:click="deleteTs()">Удалить</div>
                        </div>
                    </div>
<!--конец добавления нового ТС-->
                </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    import datepicker from 'vuejs-datepicker';
    import moment from 'moment'
    import {logistData} from "../app.js";
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
                vid_perevozki:'',
                nomenklatura:'',
                gotovyi_raschet:'',
                files:[],
                checked1:'',
                alert_message:[],
                data_vneseniya:'',
                rasschitat_do:'',
                logist:'',
                nomer_zayavki:'',
                new_nomer_zayavki:'',
                kompaniya_zakazchik_name:'',
                kompaniya_zakazchik_id:'',
                managerZakazchik_name:'',
                managerZakazchik_id:'',
                menedzer_zakazchik:'',
                ISD:'',
                cena_kontrakta:'',
                data_kontrakta:'',
                dob_oplatu:false,
                oplata_arr:[],
                adres_pogruzke:'',
                adres_pogruzke_show:'',
                adres_vygruski:'',
                adres_vygruski_show:'',
                data_pogruzki:'',
                data_dostavki:'',
                gruzomesta_big:'',
                // gruzomesta_small:'',
                rasstojanie:'',
                ob_ves:'',
                ob_ob:'',
                komment_1:'',
                // ob_budjet:0,
                add_ts:'',
                add_new_ts:false,
                vid_TS:'',
                stavka_TS:'',
                stavka_TS_bez_NDS:'',
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
                order_header_text:'',
                role:'',
                permissions:[],
                logist_list_full:[],
                utverzdenie_show_button:false,
                v_rabote_show_button:false,
                ocenka_show_button:false,
                flag_pogruz:'',
                status:'Черновик',
                vidTsNazavanie:'',
                adresPogruzkiShowSpan:true,
                adresVygruzkiShowSpan:true,
                localFirstClick:false,
                headerTimeShow1:false,
                headerTimeShow2:false,
                headerTime1:'',
                headerTime2:'',
                zakazchikShowInp:true,
                managerZakazchikShowInp:true,
                checkboxPogrVygr:false,
                pogruzkaShowInp:false,
                perevozchikId:'',
                perevozchikName:'',
                perevozchikiList:[],
                editNumberKeyPerevozchik:'',
                tooltipTarget: "tooltip-target",
                tooltipText: "Текст, который отображается при наведении курсора на элемент",
                tooltipContent: "Содержимое всплывающего окна",
                isVisiblePogrList: true
            }
        },
        created()
        {
            this.role=this.auth_user['role_perm']['role']

            this.permissions=this.auth_user['role_perm']['permissions']

            this.get_terminal_list(this.termList);
            this.get_perevozka_list(this.perevozka_arr)
            this.get_gruzootpravitel_list(this.gruzootpravitel_arr)
            this.get_logist_list(this.logist_list_full)
            for (let i = document.getElementsByClassName('mx-input-wrapper').length; i > 0 ; i--) {
                let m = i - 1
                document.getElementsByClassName('mx-input-wrapper')[m].remove();
            }
            this.get_ts_list(this.ts_list_names);
            let adress=window.location.href;
            adress=(adress.split("/")[4])
            //если новый запрос
            if(adress==null)
            {
                this.data_vneseniya= new Date().toLocaleDateString('ru-RU');
                this.start_new_order();
                this.order_header_text='Создание запроса'
            }
            //если редактируем запрос
            else
            {
                this.order_id=adress;
                this.checkButtonsShow();
                this.start_get_old_order(adress,this.oplata_arr,this.spisokTSarr,this.perevozchikiList);
                this.update_unread_status()
                this.update_unread_status_v_rabote()
            }

        },

        computed: {
            stavka_TS_za_km: function () {
                if((this.stavka_TS=='')||(this.rasstojanie_TS=='')||(this.stavka_TS==null)||(this.rasstojanie_TS==null))
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
                    budjet=(Number(budjet)+(Number(this.spisokTSarr[i]['stavka_TS'])*Number(this.spisokTSarr[i]['kol_TS_TS']) ) );
                }
                return budjet;
            },
        },
        methods: {
            //проверка показывать ли этот блок текущему пользователю
            //role - id роли 1- admin 2- logist 3- manager и т.д
            //permission id прав, 1 - все права 2 - show и т.д.
            //на вход отдавать список id кто должен видеть блок

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
            test()
            {
              console.log(this.spisokTSarr)
            },
            pogruzkaShowInpChange(id)
            {
                this.perevozchikiList.forEach(obj => {
                    if (obj.id === id) {
                        obj.pogruzkaShowInp = !obj.pogruzkaShowInp; // Инвертируем значение
                    }
                });
                console.log(this.perevozchikiList)
               // this.pogruzkaShowInp=!this.pogruzkaShowInp
            },
            updatePerevozchikField(orderPerevozchikId,fieldName,fieldValue)
            {
                axios
                    .post('/updatePerevozchikField',{
                        id:orderPerevozchikId,
                        fieldName:fieldName,
                        fieldValue:fieldValue,
                    })

            },


    updateStavkaPerevozchikRasstojanie(field,data)
            {
                this.updateNumberToFormat(field,data)
                for(var i = 0; i < this.perevozchikiList.length; i++)
                {
                    if((this.perevozchikiList[i]['stavka_NDS']=='')||(this.rasstojanie_TS=='')||(this.perevozchikiList[i]['stavka_NDS']==null)||(this.rasstojanie_TS==null))
                    {
                        this.perevozchikiList[i]['stavka_za_km']=0
                    }
                    else
                    {
                        let tempSt=this.perevozchikiList[i]['stavka_NDS']/this.rasstojanie_TS
                        this.perevozchikiList[i]['stavka_za_km'] =(parseInt(tempSt * 100)) / 100
                    }
                }
            },
            updateStavkaPerevozchik(type,key)
            {
                if(type=='stavka_NDS')
                {
                    this.perevozchikiList[key]['stavka_NDS']=this.setToNumberFormatData(this.perevozchikiList[key]['stavka_NDS'])

                    if((this.perevozchikiList[key]['stavka_NDS']=='')||(this.perevozchikiList[key]['stavka_NDS']==0))
                    {
                        this.perevozchikiList[key]['stavka_bez_NDS']=0
                        this.perevozchikiList[key]['stavka_za_km']=0
                    }
                    else
                    {
                        this.perevozchikiList[key]['stavka_bez_NDS'] = (Number(this.perevozchikiList[key]['stavka_NDS'])/ 1.2).toFixed(2);
                        if((this.perevozchikiList[key]['stavka_NDS']=='')||(this.rasstojanie_TS=='')||(this.perevozchikiList[key]['stavka_NDS']==null)||(this.rasstojanie_TS==null))
                        {
                            this.perevozchikiList[key]['stavka_za_km']=0
                        }
                        else
                        {
                            let tempSt=this.perevozchikiList[key]['stavka_NDS']/this.rasstojanie_TS
                            this.perevozchikiList[key]['stavka_za_km'] =(parseInt(tempSt * 100)) / 100
                        }
                    }

                }
                if(type=='stavka_bez_NDS')
                {
                    this.perevozchikiList[key]['stavka_bez_NDS']=this.setToNumberFormatData(this.perevozchikiList[key]['stavka_bez_NDS'])
                    if((this.perevozchikiList[key]['stavka_bez_NDS']=='')||(this.perevozchikiList[key]['stavka_bez_NDS']==0))
                    {
                        this.perevozchikiList[key]['stavka_NDS'] =0
                        this.perevozchikiList[key]['stavka_za_km']=0
                    }
                    else
                    {
                        this.perevozchikiList[key]['stavka_NDS'] = (Number(this.perevozchikiList[key]['stavka_bez_NDS']) * 1.2).toFixed(2);

                        if((this.perevozchikiList[key]['stavka_NDS']=='')||(this.rasstojanie_TS=='')||(this.perevozchikiList[key]['stavka_NDS']==null)||(this.rasstojanie_TS==null))
                        {
                            this.perevozchikiList[key]['stavka_za_km']=0
                        }
                        else
                        {
                            let tempSt=this.perevozchikiList[key]['stavka_NDS']/this.rasstojanie_TS
                            this.perevozchikiList[key]['stavka_za_km'] =(parseInt(tempSt * 100)) / 100
                        }
                    }
                }
              //  this.updatePerevozchikField(this.perevozchikiList[key]['id'],'stavka_NDS',this.perevozchikiList[key]['stavka_NDS'])
              //  this.updatePerevozchikField(this.perevozchikiList[key]['id'],'stavka_bez_NDS',this.perevozchikiList[key]['stavka_bez_NDS'])

            },
            updateStavka() {
                this.stavka_TS=this.setToNumberFormat('stavka_TS_bez_NDS')
                if((this.stavka_TS_bez_NDS=='')||(this.stavka_TS_bez_NDS==0))
                {
                    this.stavka_TS =0
                }
                else
                {
                    this.stavka_TS = (Number(this.stavka_TS_bez_NDS) * 1.2).toFixed(2);
                }
            },
            updateStavkaBezNDS() {
                this.stavka_TS_bez_NDS=this.setToNumberFormat('stavka_TS')
                if((this.stavka_TS=='')||(this.stavka_TS==0))
                {
                    this.stavka_TS_bez_NDS =0
                }
                else
                {
                    this.stavka_TS_bez_NDS =(Number(this.stavka_TS) / 1.2).toFixed(2) ;
                }

            },
            zakazchikShowInpChange()
            {
                this.zakazchikShowInp=!this.zakazchikShowInp
            },
            managerShowInpChange()
            {
                this.managerZakazchikShowInp=!this.managerZakazchikShowInp
            },
            showHeaderTime(numb)
            {
                if(numb==1)
                {
                    this.headerTimeShow1=true
                }
                if(numb==2)
                {
                    this.headerTimeShow2=true
                }
            },
            updateOrderNomerZaprosa()
            {
                axios
                    .post('/updateOrderNomerZaprosa',{
                        id:this.order_id,
                        nomer_zayavki:this.new_nomer_zayavki
                    })
                    .then(response => {
                        //если такая заявка уже была
                        if(response.data.updated=='false')
                        {
                            alert('Номер запроса уже существует')
                            this.new_nomer_zayavki=this.nomer_zayavki
                        }
                        else
                        {
                            this.nomer_zayavki=this.new_nomer_zayavki
                            this.order_header_text='Запрос номер: '+this.nomer_zayavki+' Дата внесения: '
                        }

                    })
            },
            parentMethodFromAutoinputPerevozka(data)
            {
                if(data.id!==null)
                {
                    this.perevozchikiList[data.elem1].perevozchik_id=data.id
                    this.perevozchikiList[data.elem1].perevozka.nazvanie=data.forma_id+' '+data.ts_name
                    this.getPerevozchikData(data.id,data.elem1)
                }

            },
            closeParentAutoInputPogruzka(data)
            {
                this.perevozchikiList[data.elem1].pogruzkaShowInp=!this.perevozchikiList[data.elem1].pogruzkaShowInp
            },
            updateOrdersPerevozchik(perevozchik_id,key)
            {
                axios
                    .post('/updateOrdersPerevozchik',{
                        order_id:this.order_id,
                        key:key,
                        perevozchik_id:perevozchik_id,
                    })
                    .then(response => {
                        if((response.data.perevozchik[0].INN!='')&&(response.data.perevozchik[0].INN!=null))
                        {
                            //тут добавить строчку с нвоым параметром
                            this.perevozchikiList[key].INN=response.data.perevozchik[0].INN
                        }
                        if((response.data.perevozchik[0].kod_ATI!='')&&(response.data.perevozchik[0].kod_ATI!=null))
                        {
                            this.perevozchikiList[key].kod_ATI=response.data.perevozchik[0].kod_ATI
                        }
                        else
                        {
                            this.perevozchikiList[key].kod_ATI=''
                        }
                    })
            },

            inputShow(inpVar)
            {

                //если кликнули на адрес погрузки
                if(inpVar=='adresPogruzkiShowSpan')
                {
                    this.adresPogruzkiShowSpan=!this.adresPogruzkiShowSpan
                }
                //если кликнули на адрес выгрузки
                if(inpVar=='adresVygruzkiShowSpan')
                {
                    this.adresVygruzkiShowSpan=!this.adresVygruzkiShowSpan
                }
            },
            //метод показа или не показа инпута поиска в родителе
            showHideParent(inpData)
            {
                if(inpData.type=='adres_pogruzke')
                {
                    this.adresPogruzkiShowSpan=!this.adresPogruzkiShowSpan
                    this.adres_pogruzke_show=inpData.inputText
                }
                if(inpData.type=='adres_vygruski')
                {
                    this.adresVygruzkiShowSpan=!this.adresVygruzkiShowSpan
                    this.adres_vygruski_show=inpData.inputText
                }

            },
            addEmptyPerevozchik()
            {
           //     axios
            //        .post('/addEmptyPerevozchik',{
            //            order_id:this.order_id,
            //        })
                    //.then(response => {
                        let objToPush= {};
                        objToPush['TS_id'] = '';
                        objToPush['contacts'] = '';
                        objToPush['id'] = '';
                        objToPush['perevozchik_id'] = '';
                        objToPush['perevozka'] = {
                                    INN: '',
                                    OGRN: '',
                                    YR_adres: '',
                                    data_registracii: '',
                                    email: '',
                                    email_gen_dir: '',
                                    forma_id: '',
                                    generalnii_direktor: '',
                                    gorod_bazirovania: '',
                                    id:'',
                                    kod_ATI:'',
                                    kommentariy:'',
                                    nazvanie:'',
                                    perevozka_name:'',
                                    pochtovyi_adres:'',
                                    telefon:'',
                                    telefon_gen_dir:''
                                                 };
                        objToPush['stavka_NDS'] = '';
                        objToPush['stavka_bez_NDS'] = '';
                        objToPush['pogruzkaShowInp'] = false;
                        this.perevozchikiList.push(objToPush);
                   // })
            },
            //сохранение перевозчика из модального окна
            gradeAddPerevozchik(id,nazvanie)
            {
                    this.perevozchikiList[this.editNumberKeyPerevozchik].perevozchik_id=id
                    this.getPerevozchikDataEditOrNew(id,this.editNumberKeyPerevozchik)
            },
            getPerevozchikDataEditOrNew(id,key)
            {
                axios
                    .post('/getPerevozchikData',{
                        id:id,
                    })
                    .then(response => {
                         var elements = document.getElementsByClassName('perClassScript');

                        for(let i = 0; i < this.perevozchikiList.length; i++)
                        {
                            if(this.perevozchikiList[i].perevozchik_id==id)
                            {
                                this.perevozchikiList[i].contacts=response.data.perevozchik[0].contacts
                                this.perevozchikiList[i].perevozka.INN=response.data.perevozchik[0].INN
                                this.perevozchikiList[i].perevozka.kod_ATI=response.data.perevozchik[0].kod_ATI
                                this.perevozchikiList[i].perevozka.nazvanie=response.data.perevozchik[0].nazvanie
                                elements[i].value=response.data.perevozchik[0].nazvanie
                            }
                        }
                        for(let i = 0; i < this.spisokTSarr.length; i++)
                        {
                            for(let j = 0; j < this.spisokTSarr[i].perevozchikiList.length; j++)
                            {
                                if(this.spisokTSarr[i].perevozchikiList[j].perevozchik_id==id)
                                {
                                    this.spisokTSarr[i].perevozchikiList[j].contacts=response.data.perevozchik[0].contacts
                                    this.spisokTSarr[i].perevozchikiList[j].perevozka.INN=response.data.perevozchik[0].INN
                                    this.spisokTSarr[i].perevozchikiList[j].perevozka.kod_ATI=response.data.perevozchik[0].kod_ATI
                                    this.spisokTSarr[i].perevozchikiList[j].perevozka.nazvanie=response.data.perevozchik[0].nazvanie
                                }
                            }
                        }
                    })
            },
            getPerevozchikData(id,key)
            {
                axios
                    .post('/getPerevozchikData',{
                        id:id,
                    })
                    .then(response => {
                        for(let i = 0; i < this.perevozchikiList.length; i++)
                        {
                            if(this.perevozchikiList[i].perevozchik_id==id)
                            {
                                this.perevozchikiList[i].contacts=response.data.perevozchik[0].contacts
                                this.perevozchikiList[i].perevozka.INN=response.data.perevozchik[0].INN
                                this.perevozchikiList[i].perevozka.kod_ATI=response.data.perevozchik[0].kod_ATI
                                this.perevozchikiList[i].perevozka.nazvanie=response.data.perevozchik[0].nazvanie
                                this.perevozchikiList[i].perevozka.forma_id=response.data.perevozchik[0].forma_id
                            }
                        }


                        //  this.perevozchikiList[key].contacts=response.data.perevozchik[0].contacts
                    })
            },
            //метод изменяющий название погрузок или выгрузок на всей странице или удаляющий их и в левой колонке и в списке ТС
            chahgeFrontNames()
            {
                //получим все id нужных нам адресов ( адрес погрузки, выгрузки, и в списке ТС тоже самое ) и сформируем массив
                let findAdresa=[];
                findAdresa.push(this.adres_pogruzke);
                findAdresa.push(this.adres_vygruski);
                //делаем запрос
                //обновляем


                for(var i = 0; i < this.spisokTSarr.length; i++)
                {
                    for(var j = 0; j < this.spisokTSarr[i]['adres_pogruzki_TS'].length; j++)
                    {

                            findAdresa.push(this.spisokTSarr[i]['adres_pogruzki_TS'][j]['adres_pogruzki']);
                    }
                    for(var j = 0; j < this.spisokTSarr[i]['adres_vygr_TS'].length; j++)
                    {

                        findAdresa.push(this.spisokTSarr[i]['adres_vygr_TS'][j]['adres_pogruzki']);
                    }
                }

                axios
                    .post('/chahgeFrontNames',{
                        findAdresa:findAdresa
                    })
                    .then(response => {
                       this.changeFrontNames(response.data.gruzColect)
                    })
            },
            changeFrontNames(gruzColect)
            {

                //установим мордашку у адреса погрузки
                let nazv=this.issetFrontNames(gruzColect,this.adres_pogruzke)
                if(nazv)
                {
                    this.adres_pogruzke_show=nazv
                }
                else
                {
                    this.adres_pogruzke_show=''
                }
                //установим мордашку у адреса выгрузки
                 nazv=this.issetFrontNames(gruzColect,this.adres_vygruski)
                if(nazv)
                {
                    this.adres_vygruski_show=nazv
                }
                else
                {
                    this.adres_vygruski_show=''

                }

                for(var i = 0; i < this.spisokTSarr.length; i++)
                {
                    for(var j = 0; j < this.spisokTSarr[i]['adres_pogruzki_TS'].length; j++)
                    {
                        nazv=this.issetFrontNames(gruzColect,this.spisokTSarr[i]['adres_pogruzki_TS'][j]['adres_pogruzki'])
                        if(nazv)
                        {
                              this.spisokTSarr[i]['adres_pogruzki_TS'][j]['adres_pogruzke_show']=nazv
                        }
                        else
                        {
                            this.spisokTSarr[i]['adres_pogruzki_TS'][j]['adres_pogruzke_show']=''

                        }
                    }
                    for(var j = 0; j < this.spisokTSarr[i]['adres_vygr_TS'].length; j++)
                    {
                        nazv=this.issetFrontNames(gruzColect,this.spisokTSarr[i]['adres_vygr_TS'][j]['adres_pogruzki'])
                        if(nazv)
                        {
                            this.spisokTSarr[i]['adres_vygr_TS'][j]['adres_vygruzki_show']=nazv
                        }
                        else
                        {
                            this.spisokTSarr[i]['adres_vygr_TS'][j]['adres_vygruzki_show']=''

                        }
                    }
                }

            },
            issetFrontNames(gruzColect,targetId)
            {
                const foundObject = gruzColect.find(obj => obj.id == targetId);

                if (foundObject) {
                    return foundObject.nazvanie;;
                } else {
                    return false;
                }
            },
            changePogrVygrAllShow(id)
            {
                this.$refs.modalComponentforActionAuthor.get_modal_edit_data(id)
            },
            //добавление пустой погрузки или выгрузки
            add_new_pogruzka_in_ts(data)
            {
                //если погрузка
                if(data.pogr_or_vygr=='pogruzka')
                {
                    this.ad_pogruzki_arr_temp[data.key]['adres_pogruzki']=data.inp_pog_id;
                    this.ad_pogruzki_arr_temp[data.key]['adres_pogruzke_show']=data.nazvanie;
                }
                //если выгрузка
                if(data.pogr_or_vygr=='vygruzka')
                {
                    this.ad_vygruz_arr_temp[data.key]['adres_pogruzki']=data.inp_pog_id;
                    this.ad_vygruz_arr_temp[data.key]['adres_vygruzki_show']=data.nazvanie;
                }

            },
            //если редактируешь тс
            parentMethodFromAutoinput(data)
            {
                this.vid_TS=data.vid_TS
            },
            //если добавляешь новое тс
            parentMethodFromAutoinputNewVidts(data)
            {

                this.vid_TS=data.vid_TS
            },
            //проверка какие кнопки показывать пользователям
            checkButtonsShow(list)
            {
                axios
                    .post('/check_buttons_show',{
                        id:this.order_id,
                        user_id:this.auth_user['id'],
                        role:this.role
                    })
                    .then(({ data }) => (
                        this.ocenka_show_button=data.ocenkaShowButton,
                        this.utverzdenie_show_button=data.utverzdenieShowButton,
                        this.v_rabote_show_button=data.vRaboteShowButton
                        )

                    );
            },
            hideButton(buttonName)
            {
                if(buttonName=='ocenka')
                {
                    this.ocenka_show_button=false
                    if(this.spisokTSarr.length!=0)
                    {
                        this.utverzdenie_show_button=true
                        this.v_rabote_show_button=true
                    }

                }
                if(buttonName=='naznachenie_stavki')
                {
                    this.utverzdenie_show_button=false
                    this.ocenka_show_button=true
                    if(this.spisokTSarr.length!=0)
                    {
                        this.v_rabote_show_button = true
                    }
                }
                if(buttonName=='v_rabote')
                {
                    this.v_rabote_show_button=false
                    if(this.spisokTSarr.length!=0)
                    {
                        this.utverzdenie_show_button = true
                    }
                    this.ocenka_show_button=true
                }
            },
            update_order_oplata(summ_opl,id,data)
            {
                axios
                    .post('/update_order_oplata',{
                        id:id,
                        summ_opl:summ_opl,
                        data:data,
                    })

            },
            async deletePerevozchikFromOrder(id,key)
            {
                const result = await this.confirmMethodMixin();
                if (result) {
                    if(id!==null)
                    {
                        axios
                            .post('/deletePerevozchikFromOrder', {
                                id: id
                            })
                            .then(response => {
                                //забирать значения с классом и переприсваивать их после удаления
                                var elements = document.querySelectorAll('.perClassScript');
                                this.perevozchikiList.splice(key, 1)

                                var elementsNew = document.querySelectorAll('.perClassScript');
                                console.log(elements)
                                console.log(elementsNew)
                                for(let i = 0; i < elements.length; i++)
                                    {
                                        if(i !== key)
                                        {
                                            if(key >= i)
                                            {
                                                elementsNew[i].value=elements[i].value

                                            }
                                            else
                                            {
                                                elementsNew[i-1].value=elements[i].value

                                            }
                                        }
                                    }
                            })
                    }
                    else
                    {
                        this.perevozchikiList.splice(key, 1)
                    }

                }
            },

          async delete_oplata_summa(id,key)
            {
                const result = await this.confirmMethodMixin();
                if (result) {
                    axios
                        .post('/delete_oplata_summa', {
                            id: id
                        })
                        .then(response => {
                            this.oplata_arr.splice(key, 1)
                        })
                }
            },
            get_logist_list(inp)
            {
                axios
                    .post('/get_logist_list',{
                    })
                    .then(({ data }) => (
                            data.list_users.forEach(function(entry) {
                                inp.push({
                                    id:entry.id,
                                    full_logist_name:entry.last_name+' '+entry.first_name+' '+entry.patronymic,

                                });
                            })
                        )
                    );
            },
            get_perevozka_list(inp)
            {
                axios
                    .post('/getVidPerevozki',{
                    })
                    .then(({ data }) => (
                            data.perevozka_list.forEach(function(entry) {
                                inp.push({
                                    id:entry.id,
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
                        this.gruzootpravitel_arr=inp_temp,
                    );
            },
            show_mod_edit(id)
            {
                //вызов метода дочернего компонента( модального окна )
                this.$refs.modalComponentforAction.setNewModalStatusFalse()
                this.$refs.modalComponentforAction.get_modal_edit_data(id)
            },
            //работа из модального окна добавления грузоотправителя
            //метод выборки, адрес погрузки или адрес выгрузки поменяется динамически
            select_temp_var(pogr_or_vygr,key)
            {
                if(pogr_or_vygr=='manager')
                {
                    this.show_mod_edit(this.kompaniya_zakazchik_id)
                }
                else
                {
                    this.newModal()
                }

                //погрузка 1 - pogruzka
                //выгрузка 1 - vygruzka
                //список ТС редактирование тс, погрузка - TS_pogruzka
                //список ТС редактирование тс, выгрузка - TS_vygruzka
                //список ТС новое тс, погрузка - TS_pogruzka_new
                //список ТС новое тс, выгрузка - TS_vygruzka_new
                this.select_temp_pogr_or_vygr=pogr_or_vygr
                this.select_temp_pogr_or_vygr_key=key
            },
            select_Manager(kontakty)
            {
                if(this.select_temp_pogr_or_vygr=='manager')
                {
                    console.log(kontakty)
                }

            },
            newPerevozchik(key)
            {
                //в этой переменной храним ключ редактируемого перевозчика
                this.editNumberKeyPerevozchik=key
                //вызов метода дочернего компонента( модального окна )
                this.$refs.modalComponentforActionPerevozchik.newPerevozchik()
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
                                this.adres_pogruzke_show=data.gruzootpravitel.nazvanie,
                                this.$refs.AutoSelectComponent.up_gruzoot_from_select(this.adres_pogruzke,'adres_pogruzke')
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
                                this.adres_vygruski_show=data.gruzootpravitel.nazvanie,
                                this.$refs.AutoSelectComponent.up_gruzoot_from_select(this.adres_vygruski,'adres_vygruski')
                            )

                        );
                }

                //если редактируем погрузку
                if(this.select_temp_pogr_or_vygr=='TS_pogruzka')
                {
                    axios
                        .post('/select_gruzootpravitel',{
                        })
                        .then(({ data }) => (
                                //сохраняем во временный массив
                                this.ad_pogruzki_arr_temp[this.select_temp_pogr_or_vygr_key]['adres_pogruzki']=data.gruzootpravitel.id,
                                //сохраняем название во временный массив
                                this.ad_pogruzki_arr_temp[this.select_temp_pogr_or_vygr_key]['adres_pogruzke_show']=data.gruzootpravitel.nazvanie,
                                //передаём в селект название и отображаем там
                                this.$refs.AutoSelectComponent_pogruzka_edit[this.select_temp_pogr_or_vygr_key].up_gruz_from_modal(data.gruzootpravitel.nazvanie)
                            )

                        );
                }
                //если редактируем выгрузку
                if(this.select_temp_pogr_or_vygr=='TS_vygruzka')
                {
                    axios
                        .post('/select_gruzootpravitel',{
                        })
                        .then(({ data }) => (
                            //сохраняем во временный массив
                            this.ad_vygruz_arr_temp[this.select_temp_pogr_or_vygr_key]['adres_pogruzki']=data.gruzootpravitel.id,
                            //сохраняем название во временный массив
                            this.ad_vygruz_arr_temp[this.select_temp_pogr_or_vygr_key]['adres_vygruzki_show']=data.gruzootpravitel.nazvanie,
                            //передаём в селект название и отображаем там
                            this.$refs.AutoSelectComponent_vygruzka_edit[this.select_temp_pogr_or_vygr_key].up_gruz_from_modal(data.gruzootpravitel.nazvanie)
                            )

                        );
                }
                //если добавляем новую погрузку
                if(this.select_temp_pogr_or_vygr=='TS_pogruzka_new')
                {
                    axios
                        .post('/select_gruzootpravitel',{
                        })
                        .then(({ data }) => (
                                //сохраняем во временный массив
                                this.ad_pogruzki_arr_temp[this.select_temp_pogr_or_vygr_key]['adres_pogruzki']=data.gruzootpravitel.id,
                                //сохраняем название во временный массив
                                this.ad_pogruzki_arr_temp[this.select_temp_pogr_or_vygr_key]['adres_pogruzke_show']=data.gruzootpravitel.nazvanie,
                                //передаём в селект название и отображаем там
                                this.$refs.AutoSelectComponent_pogruzka_empty[this.select_temp_pogr_or_vygr_key].up_gruz_from_modal(data.gruzootpravitel.nazvanie)
                            )

                        );
                }
                //если добавляем новую выгрузку
                if(this.select_temp_pogr_or_vygr=='TS_vygruzka_new')
                {
                    axios
                        .post('/select_gruzootpravitel',{
                        })
                        .then(({ data }) => (
                            //сохраняем во временный массив
                            this.ad_vygruz_arr_temp[this.select_temp_pogr_or_vygr_key]['adres_pogruzki']=data.gruzootpravitel.id,
                            //сохраняем название во временный массив
                            this.ad_vygruz_arr_temp[this.select_temp_pogr_or_vygr_key]['adres_vygruzki_show']=data.gruzootpravitel.nazvanie,
                            //передаём в селект название и отображаем там
                            this.$refs.AutoSelectComponent_vygruzka_empty[this.select_temp_pogr_or_vygr_key].up_gruz_from_modal(data.gruzootpravitel.nazvanie)
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
                this.updateOrderLoc('data_pogruzki',this.data_pogruzki);
            },
            handleChange1(){
                this.update_order()
            },
            openDB0()
            {
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
                this.update_order_logist()
                    if((this.status=='Черновик')&&(name!='Логист не выбран'))
                    {
                        this.status='На оценке'
                    }
             },
            setStatusName(columnName)
            {
                if(columnName=='ocenka')
                {
                    this.status='На оценке'
                }
                if(columnName=='naznachenie_stavki')
                {
                    this.status='На утверждении'
                }
                if(columnName=='v_rabote')
                {
                    this.status='В работе'
                }
            },
            logist_show()
            {
                this.logist_list=!this.logist_list
            },
            show_mod_edit(id)
            {
                //вызов метода дочернего компонента( модального окна )
                this.$refs.modalComponentforAction.get_modal_edit_data(id)
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
            update_unread_status()
            {
                if(this.role==2)
                {
                axios
                    .post('/update_unread_status',{
                        logist_id:this.auth_user['id'],
                        id:this.order_id,
                        column_name:'ocenka',
                    })
                }
                if(this.role==1)
                {
                    axios
                        .post('/update_unread_status',{
                            logist_id:this.auth_user['id'],
                            id:this.order_id,
                            column_name:'naznachenie_stavki',
                        })
                }
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
            update_order_logist()
            {

                    axios
                        .post('/update_order_logist',{
                            logist:this.logist,
                            id:this.order_id
                        })
                        // .then(({ data }) => (
                        //     this.update_order()
                        // ))
            },
            setColumn(columnName)
            {
                axios
                    .post('/orderChangeColumn',{
                        columnName:columnName,
                        id:this.order_id
                    })
                    .then(response => {
                        if((this.role==2)&&(columnName=='naznachenie_stavki')) {
                            //редиректим логиста на главную перед этим говорим  отобразить оценку
                            localStorage.setItem('logist_ut_flag', '1')
                            window.location.href = ('/')
                        }
                        //если утвердил админ
                        if(this.role==1)
                        {
                           this.hideButton(columnName)
                           let message=this.setMessage(columnName)
                            alert(message)
                        }

                    })
                this.setStatusName(columnName)
            },
            setMessage(columnName)
            {
                let message=''
              if(columnName=='ocenka')
              {
                  message = 'Запрос отправлен на оценку'
              }
               if(columnName=='naznachenie_stavki')
              {
                  message = 'Запрос отправлен на назначение ставки'
              }
              if(columnName=='v_rabote')
              {
                  message = 'Запрос отправлен в работу'
              }
              return message;
            },
            newModal()
            {
                this.$refs.modalComponentforAction.newModal()
            },
            //метод применяемый для числовых значений с кореектировокой ввода
            updateOrderLocNumber(field,data)
            {
                data=this.setToNumberFormat(field)
                if((field=='adres_pogruzke')||(field=='adres_vygruski'))
                {
                    this.adresPogruzkiShowSpan=true
                    this.adresVygruzkiShowSpan=true
                }
                axios
                    .post('/updateOrderLoc',{
                        id:this.order_id,
                        field:field,
                        data:data
                    })
            },
            //метод для всех остальных значений кроме числовых
            updateOrderLoc(field,data)
            {
                if((field=='adres_pogruzke')||(field=='adres_vygruski'))
                {
                    this.adresPogruzkiShowSpan=true
                    this.adresVygruzkiShowSpan=true
                }
                axios
                    .post('/updateOrderLoc',{
                        id:this.order_id,
                        field:field,
                        data:data
                    })
            },
            update_order()
            {
                axios
                    .post('/update_order',{
                        id:this.order_id,
                        data_vneseniya:this.data_vneseniya,
                        rasschitat_do:this.rasschitat_do,
                        nomenklatura:this.nomenklatura,
                        kompaniya_zakazchik_name:this.kompaniya_zakazchik_name,
                        kompaniya_zakazchik_id:this.kompaniya_zakazchik,
                        managerZakazchik_id:this.menedzer_zakazchik,
                        ISD:this.ISD,
                        cena_kontrakta:this.cena_kontrakta,
                        data_kontrakta:this.data_kontrakta,
                       // adres_pogruzke:this.adres_pogruzke,
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
                        start_flag:false,
                        timeVneseniya:this.headerTime1,
                        timeRasscheta:this.headerTime2
                    })
            },
            start_get_old_order(adress,inp,TSinp,perevozchikiList)
            {
                axios
                    .post('/start_get_old_order',{
                        id:adress
                    })
                    .then(({ data }) => (
                            this.data_vneseniya=data.data[0]['data_vneseniya'],
                            this.headerTime1=data.data[0]['timeVneseniya'],
                            this.headerTime2=data.data[0]['timeRasscheta'],
                            this.rasschitat_do=data.data[0]['rasschitat_do'],
                            this.nomenklatura=data.data[0]['nomenklatura'],
                            this.gotovyi_raschet=data.data[0]['gotovyi_raschet'],
                            this.nomer_zayavki=data.data[0]['nomer_zayavki'],
                            this.new_nomer_zayavki=data.data[0]['nomer_zayavki'],
                            this.kompaniya_zakazchik_id=data.data[0]['kompaniya_zakazchik'],
                            this.kompaniya_zakazchik_name=data.data[0]['kompaniya_zakazchik_name'],
                            this.managerZakazchik_name=data.data[0]['menedzer_zakazchik_name'],
                            this.managerZakazchik_id=data.data[0]['menedzer_zakazchik'],
                            this.ISD=data.data[0]['ISD'],
                            this.cena_kontrakta=data.data[0]['cena_kontrakta'],
                            this.data_kontrakta=data.data[0]['data_kontrakta'],
                            this.adres_pogruzke=data.data[0]['adres_pogruzke'],
                            this.adres_vygruski=data.data[0]['adres_vygruski'],
                            this.data_pogruzki=data.data[0]['data_pogruzki'],
                            this.data_dostavki=data.data[0]['data_dostavki'],
                            this.komment_1=data.data[0]['komment_1'],
                            this.logist=data.data[0]['logist'],
                            this.logist_name=data.data[0]['logist_name'],
                            this.gruzomesta_big=data.data[0]['gruzomesta_big'],
                            this.rasstojanie=data.data[0]['rasstojanie'],
                            this.ob_ves=data.data[0]['ob_ves'],
                            this.ob_ob=data.data[0]['ob_ob'],
                            this.vid_perevozki=data.data[0]['vid_perevozki'],
                            this.status=data.data[0]['status'],
                            this.perevozchikId=data.data[0]['perevozchik'],
                            this.perevozchikName=data.data[0]['nazvanie'],
                                data.data[0].oplata.forEach(function(entry) {
                                    inp.push(entry);
                                }),
                                data.TS_list.forEach(function(entry) {
                                    TSinp.push({
                                        id_ts:entry.id_ts,
                                        order_id:entry.order_id,
                                        vid_TS : entry.vid_TS,
                                        stavka_TS : entry.stavka_TS,
                                        stavka_TS_bez_NDS : entry.stavka_TS_bez_NDS,
                                        stavka_TS_za_km : entry.stavka_TS_za_km,
                                        stavka_kp_TS : entry.stavka_kp_TS,
                                        marja_TS : entry.marja_TS,
                                        kol_gruz_TS : entry.kol_gruz_TS,
                                        kol_TS_TS : entry.kol_TS_TS,
                                        rasstojanie_TS : entry.rasstojanie_TS,
                                        ob_ves_TS : entry.ob_ves_TS,
                                        ob_ob_TS : entry.ob_ob_TS,
                                        adres_pogruzki_TS : entry.adres_pogruzki_TS,
                                        adres_vygr_TS : entry.adres_vygr_TS,
                                        kommentari_TS : entry.kommentari_TS,
                                        checked2 : entry.checked2,
                                        terminal_TS : entry.terminal_TS,
                                        perevozchikiList : entry.perevozchiki,
                                    });
                                }),
                                this.order_header_text='Запрос номер: '+this.nomer_zayavki+' Дата внесения: '
                        )
                    )

            },
            parentMethodFromAutoinputZakazchik(data)
            {
                if(this.kompaniya_zakazchik_id!=data.id)
                {
                    //обнуляем менеджера
                    this.managerZakazchik_name=''
                    this.managerZakazchik_id=''
                    this.updateOrderLoc('menedzer_zakazchik','')
                }

                this.kompaniya_zakazchik_name=data.ts_name
                this.kompaniya_zakazchik_id=data.id
                this.zakazchikShowInp=true
                this.updateOrderLoc('kompaniya_zakazchik',data.id)

            },
            parentMethodFromAutoinputManager(data)
            {
                this.managerZakazchik_name=data.ts_name
                this.managerZakazchik_id=data.id
                this.managerZakazchikShowInp=true
                this.updateOrderLoc('menedzer_zakazchik',data.id)

            },
            closeParentAutoInput(data)
            {
              this.zakazchikShowInp=false
            },
            closeParentAutoInputManager(data)
            {
                this.managerZakazchikShowInp=false
            },
            //если новая запрос
            start_new_order()
            {
                axios
                    .post('/start_new_order',{
                        data_vneseniya:new Date().toLocaleDateString('ru-RU')
                    })
                    .then(({ data }) => (
                            this.order_id=data.data['id'],
                            this.checkButtonsShow()
                        )
                    )
            },
            add_to_work()
            {
                this.v_rabote_show_button=false;
                axios
                    .post('/add_to_work',{
                        order_id:this.order_id
                    })
                    .then(response => {
                        alert('запрос добавлена в работу')
                    })
            },
            go_to_grade()
            {
                // window.location.href =('/grade/'+this.order_id)
                //отправляем запрос на вкладку назначение ставки
                this.utverzdenie_show_button=false;
                axios
                    .post('/add_to_naznachenie_stavki',{
                        order_id:this.order_id
                    })
                    .then(response => {
                        if(this.role==2) {
                            //редиректим логиста на главную перед этим говорим  отобразить оценку
                            localStorage.setItem('logist_ut_flag', '1')
                            window.location.href = ('/')
                        }
                        //если утвердил админ
                        if(this.role==1)
                        {
                            alert('запрос добавлен в назначение ставки')
                        }

                    })
            },
            showButtons()
            {
                 if(this.status!=='Назначение ставки')
                 {
                     this.utverzdenie_show_button=true
                 }
                if(this.status!=='На утверждении')
                {
                    this.utverzdenie_show_button=true
                }

              if(this.role==1)
              {
                  if(this.status!=='На оценке')
                  {
                      this.ocenka_show_button=true
                  }
                  if(this.status!=='В работе')
                  {
                      this.v_rabote_show_button=true
                  }

              }

            },
            show_mod_edit_perevozchik(id,key)
            {
                //в этой переменной храним ключ редактируемого перевозчика
                this.editNumberKeyPerevozchik=key
                //вызов метода дочернего компонента( модального окна )
                this.$refs.modalComponentforActionPerevozchik.get_modal_edit_data(id)
            },
            hideButtonsUsers()
            {
                this.v_rabote_show_button=false
                this.utverzdenie_show_button=false
            },
            save_TS()
            {
                this.checkboxPogrVygr=false
                //показываем кнопки
                this.showButtons()
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
                objToPush['stavka_TS_bez_NDS'] = this.stavka_TS_bez_NDS;
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
                objToPush['perevozchikiList'] = this.perevozchikiList;
                this.spisokTSarr.push(objToPush);

//сохраняем новое тс
                    axios
                        .post('/save_ts',{
                            id_ts:this.id_ts,
                            order_id:this.order_id,
                            vid_TS : this.vid_TS,
                            stavka_TS : this.stavka_TS,
                            stavka_TS_bez_NDS : this.stavka_TS_bez_NDS,
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
                            terminal_TS : this.terminal_TS,
                            perevozchikiList:this.perevozchikiList
                        })

                this.vid_TS='';
                this.id_ts='';
                this.stavka_TS='';
                this.stavka_TS_bez_NDS='';
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
                this.perevozchikiList=''
                }
                //редактируем существующее ТС
                else
                {
                    this.spisokTSarr[this.edit_number]['vid_TS']=this.vid_TS;
                    this.spisokTSarr[this.edit_number]['stavka_TS']=this.stavka_TS;
                    this.spisokTSarr[this.edit_number]['stavka_TS_bez_NDS']=this.stavka_TS_bez_NDS;
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
                    this.spisokTSarr[this.edit_number]['perevozchikiList']=this.perevozchikiList;
                    this.spisokTSarr[this.edit_number]['marja_TS']=this.marja_TS;
                    this.spisokTSarr[this.edit_number]['stavka_TS_za_km']=this.stavka_TS_za_km;

                    axios
                        .post('/save_ts',{
                            id_ts:this.id_ts,
                            order_id:this.order_id,
                            vid_TS : this.vid_TS,
                            stavka_TS : this.stavka_TS,
                            stavka_TS_bez_NDS : this.stavka_TS_bez_NDS,
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
                            terminal_TS : this.terminal_TS,
                            perevozchikiList:this.perevozchikiList
                        })
                    this.id_ts='';
                    this.vid_TS='';
                    this.stavka_TS='';
                    this.stavka_TS_bez_NDS='';
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
                    this.perevozchikiList=''
                }
            },
            //метод для получения названия видаТС по его id
            getTSNazvanie()
            {
                for(var i = 0; i < this.ts_list_names.length; i++)
               {
                    if(this.ts_list_names[i]['id']==this.vid_TS)
                    {
                        this.vidTsNazavanie=this.ts_list_names[i]['ts_name']
                    }
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
                this.stavka_TS_bez_NDS=this.spisokTSarr[key]['stavka_TS_bez_NDS'];
                this.stavka_kp_TS=this.spisokTSarr[key]['stavka_kp_TS'];
                this.kol_gruz_TS=this.spisokTSarr[key]['kol_gruz_TS'];
                this.kol_TS_TS=this.spisokTSarr[key]['kol_TS_TS'];
                this.rasstojanie_TS=this.spisokTSarr[key]['rasstojanie_TS'];
                this.perevozchikiList = JSON.parse(JSON.stringify(this.spisokTSarr[key]['perevozchikiList']));
                //получим название вида ТС при редактирвовании и отдадим его в окошко поиска
                this.getTSNazvanie();

                this.ad_pogruzki_arr_temp=this.spisokTSarr[key]['adres_pogruzki_TS'];
                if(this.spisokTSarr[key]['adres_pogruzki_TS'].length==0)
                {
                    let objToPush= {};
                    objToPush['adres_pogruzki'] ='';
                    this.ad_pogruzki_arr_temp.push(objToPush);
                }
                this.ob_ves_TS=this.spisokTSarr[key]['ob_ves_TS'];
                this.ob_ob_TS=this.spisokTSarr[key]['ob_ob_TS'];

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
           async deleteTs()
            {
                const result = await this.confirmMethodMixin();
                this.checkboxPogrVygr=false
                if (result) {
                if(this.edit_flag==false)
                {
                this.id_ts='';
                this.vid_TS='';
                this.stavka_TS='';
                this.stavka_TS_bez_NDS='';
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
                    this.stavka_TS_bez_NDS='';
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

                if(this.spisokTSarr.length==0)
                {

                    this.hideButtonsUsers()
                }
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
                    this.stavka_TS_bez_NDS='';
                    this.stavka_kp_TS='';
                    this.kol_gruz_TS='';
                    this.kol_TS_TS='';
                    this.rasstojanie_TS=this.rasstojanie;
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
                    this.perevozchikiList=[];
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
            //метод логики удаления из массива
           async delete_adres(arr,key,ref,pogr_vygr)
            {
                const result = await this.confirmMethodMixin();
                if (result) {
                    //удаляем из массива
                    this.delete_from_arr(arr, key)
                    //перерисовываем массив
                    this.render_new_arr(arr, ref, pogr_vygr)
                }
            },
            //arr массив откуда
            //key ключ где
            delete_from_arr(arr,key)
            {
                arr.splice(key,1)
            },
            //arr массив откуда
            //ref ссылка на элементы вида AutoSelectComponent_pogruzka_empty
            //pogr_vygr ссылка во временном массиве названия например adres_vygruzki_show
            render_new_arr(arr,ref,pogr_vygr)
            {
                for (let i = 0; i < arr.length; i++) {
                    this.$refs[ref][i].up_gruz_from_modal(arr[i][pogr_vygr])
                }
            },

            delete_adres_vygruz(key)
            {
                this.ad_vygruz_arr_temp.splice(key,1)
            },
            add_empty_adres_pogr_to_old(elem1, key)
            {
                let objToPush= {};
                objToPush['adres_pogruzki'] ='';
                this.spisokTSarr[key].adres_pogruzki_TS.push(objToPush);
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
                axios
                    .post('/add_oplata_orders',{
                        id:this.order_id,
                    })
                    .then(response => {
                        let objToPush= {};
                        objToPush['id'] = response.data.data.id;
                        objToPush['oplata'] = '';
                        objToPush['summa'] = '';
                        this.oplata_arr.push(objToPush);
                    })
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
                    this.rasschitat_do= new Date(this.rasschitat_do).toLocaleDateString('ru-RU');
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
                    this.data_vneseniya= new Date(this.data_vneseniya).toLocaleDateString('ru-RU');
                  //  this.update_order()
                }
               return moment(date).format('D MM YYYY');
            },
            addFiles(type){
                if(type=='nom')
                {
                    this.$refs.files.click();
                }
                if(type=='ready')
                {
                    this.$refs.files_ready.click();
                }
            },
            DownloadFiles(type)
            {
                axios
                    .post('/download_xlsx_orders',{
                        id:this.order_id,
                        docType:type,
                    })
                    .then(response => {
                        window.location.assign('/get_xlsx_file/images/orders_xlsx/'+response.data.file) ;
                    })
            },
            handleFilesUpload(type){
                let uploadedFiles='';
                if(type=='nom')
                {
                     uploadedFiles = this.$refs.files.files;
                }
                if(type=='ready')
                {
                     uploadedFiles = this.$refs.files_ready.files;
                }
                        if ( /\.(xlsx?)$/i.test( uploadedFiles[0].name ) ) {
                            let reg ='';
                           reg=(uploadedFiles[0].name.match(/([A-Za-zа-яА-Я0-9Ёё\W]+)\.(xlsx?)/))
                            if(type=='nom')
                            {
                                this.nomenklatura=reg[1];
                                this.nomenklatura=reg[0];
                            }
                            if(type=='ready')
                            {
                                this.gotovyi_raschet=reg[1];
                                this.gotovyi_raschet=reg[0];
                            }

                                let formData = new FormData();
                                let file = uploadedFiles;
                                formData.append('file_xlsx', file[0]);
                            if(type=='nom')
                            {
                                formData.append('file_name',this.nomenklatura);
                                formData.append('doc_type','nom');
                            }
                            if(type=='ready')
                            {
                                formData.append('file_name',this.gotovyi_raschet);
                                formData.append('doc_type','ready');
                            }
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

