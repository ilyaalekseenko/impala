<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-10">
                <b-modal id="modal-xl" size="lg" hide-footer hide-header>
                    <div class="col-12 mod_borders_top mod_new_cargo row header_grade_mod_bot">
                        <div class="col-8">Новый грузоотправитель/грузополучатель:</div>
                        <div class="col-4 row d-flex justify-content-end">
                            <div class="col add_ts_button8 text-center" v-on:click="deleteTs()">Сохранить</div>
                            <div class="col add_ts_button8 text-center" v-on:click="deleteTs()">Отменить</div>
                        </div>
                    </div>
                    <div class="col-12 no_padding_right no_padding_left row mod_borders_bottom">
                        <!--                        начало левой колонки модалка-->
                        <div class="col-6">
                            <div class="container-fluid">
                                <div class="col-12 row">
                                    <div class="col-3 no_padding_left_form">
                                        <div class="col-12 create_orders_date_title_1 lit_marg_grade">Форма</div>
                                        <select @update="update_order()"
                                                class="col-12 create_orders_date_title_int_1 grade_marg_bot"

                                        >
                                            <option v-bind:value="0" class="sel_cust">Константин Константинович</option>
                                            <option v-bind:value="1" class="sel_cust">Иван Иванович</option>
                                            <option v-bind:value="2" class="sel_cust">Джек Воробей</option>
                                            <option v-bind:value="3" class="sel_cust">Путин В.В.</option>
                                        </select>
                                    </div>
                                    <div class="col-5 grade_naz">
                                        <div class="create_orders_date_title_1 lit_marg_grade col-12">Название</div>
                                        <input @blur="update_order()" class="border_input col-12"
                                               v-model="menedzer_zakazchik"/>
                                    </div>
                                    <div class="col-3 date_width_col no_padding_left_form grade_marg_bot">
                                        <div class="create_orders_date_title_1 lit_marg_grade col-12 no_wrap_text">Дата регистрации</div>
                                        <input @blur="update_order()" class="border_input date_width col-12"
                                               v-model="menedzer_zakazchik"/>
                                    </div>
                                </div>
                            </div>
                            <div class="container-fluid ">
                                <div class="col-12 row">
                                    <div class="col-3 inn_width no_padding_left_form inn_mar_r grade_marg_bot">
                                        <div class="col-12 create_orders_date_title_1 lit_marg_grade ">ИНН</div>
                                        <input @blur="update_order()" class="col-12 border_input inn_width"
                                               v-model="menedzer_zakazchik"/>
                                    </div>
                                    <div class="col-2 inn_width no_padding_left_form inn_mar_r grade_marg_bot">
                                        <div class="col-12 create_orders_date_title_1 lit_marg_grade no_wrap_text ">ОГРН(если есть)</div>
                                        <input @blur="update_order()" class="col-12 border_input inn_width"
                                               v-model="menedzer_zakazchik"/>
                                    </div>
                                    <div class="col-2 inn_width no_padding_left_form inn_mar_r grade_marg_bot">
                                        <div class="col-12 create_orders_date_title_1 lit_marg_grade">Телефон</div>
                                        <input @blur="update_order()" class="col-12 border_input inn_width"
                                               v-model="menedzer_zakazchik"/>
                                    </div>
                                    <div class="col-2 inn_width no_padding_left_form grade_marg_bot">
                                        <div class="col-12 create_orders_date_title_1 lit_marg_grade">email</div>
                                        <input @blur="update_order()" class="col-12 border_input inn_width"
                                               v-model="menedzer_zakazchik"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 row">
                                <div class="col-6">
                                    <div class="col-12 create_orders_date_title_1 lit_marg_grade">Генеральный
                                        директор
                                    </div>
                                    <input @blur="update_order()" class="col-12 border_input "
                                           v-model="menedzer_zakazchik"/>
                                </div>
                                <div class="offset-1 col-4">
                                    <div class="col-12 create_orders_date_title_1 lit_marg_grade">Телефон</div>
                                    <input @blur="update_order()" class="col-12 border_input"
                                           v-model="menedzer_zakazchik"/>
                                </div>
                            </div>
                            <div class="col-12 grade_title_lit cont_header">Контакты:</div>
                            <div class="container-fluid ">
                                <div class="col-12 row">
                                    <div class="col-3 inn_width no_padding_left_form inn_mar_r grade_marg_bot">
                                        <div class="col-12 create_orders_date_title_1 lit_marg_grade">Должность</div>
                                        <input @blur="update_order()" class="col-12 border_input inn_width"
                                               v-model="menedzer_zakazchik"/>
                                    </div>
                                    <div class="col-3 inn_width no_padding_left_form inn_mar_r grade_marg_bot">
                                        <div class="col-12 create_orders_date_title_1 lit_marg_grade">ФИО</div>
                                        <input @blur="update_order()" class="col-12 border_input inn_width"
                                               v-model="menedzer_zakazchik"/>
                                    </div>
                                    <div class="col-2 inn_width no_padding_left_form inn_mar_r grade_marg_bot">
                                        <div class="col-12 create_orders_date_title_1 lit_marg_grade">Телефон</div>
                                        <input @blur="update_order()" class="col-12 border_input inn_width"
                                               v-model="menedzer_zakazchik"/>
                                    </div>
                                    <div class="col-2 inn_width no_padding_left_form inn_mar_r grade_marg_bot">
                                        <div class="col-12 create_orders_date_title_1 lit_marg_grade">email</div>
                                        <input @blur="update_order()" class="col-12 border_input inn_width"
                                               v-model="menedzer_zakazchik"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 add_cont_grade">Добавить контакт</div>
                        </div>
                        <!--                        конец левой колонки модалка-->
                        <!--                        начало правой колонки модалка-->
                        <div class="col-6">
                            <div class="col-12 grade_marg_bot">
                                <div class="col create_orders_date_title_1 lit_marg_grade ">Юридический адрес:</div>
                                <input @blur="update_order()" class="col-12 border_input"
                                       v-model="menedzer_zakazchik"/>
                            </div>
                            <div class="col-12 grade_marg_bot">
                                <div class="col create_orders_date_title_1 lit_marg_grade">Почтовый адрес:</div>
                                <input @blur="update_order()" class="col-12 border_input"
                                       v-model="menedzer_zakazchik"/>
                            </div>
                            <div class="col-12 grade_title_lit cont_header">Банковские реквизиты:</div>
                            <div class="col-12 row">
                                <div class="col-6 grade_marg_bot">
                                    <div class="col create_orders_date_title_1 lit_marg_grade">БИК банка:</div>
                                    <input @blur="update_order()" class="col-12 border_input"
                                           v-model="menedzer_zakazchik"/>
                                </div>
                                <div class="col-6 bank_grade_marg grade_marg_bot">
                                    <span class="bank_grade ">Банк:</span>
                                    <span class="bank_grade_1 ">ПАО "Сбербанк"</span>
                                </div>
                            </div>
                            <div class="col-12 row">
                                <div class="col-6 grade_marg_bot">
                                    <div class="col create_orders_date_title_1 lit_marg_grade">Расчётный счёт:</div>
                                    <input @blur="update_order()" class="col-12 border_input grade_marg_bot"
                                           v-model="menedzer_zakazchik"/>
                                    <div class="col create_orders_date_title_1 lit_marg_grade">Корсчёт:</div>
                                    <input @blur="update_order()" class="col-12 border_input"
                                           v-model="menedzer_zakazchik"/>
                                </div>
                                <div class="col-6 grade_marg_bot">
                                    <span class="bank_grade ">Комментарий:</span>
                                    <textarea class="comm_settings" @blur="update_order()" v-model="komment_1"
                                              rows="6" name="text"></textarea>
                                </div>
                            </div>
                            <div class="col-12 add_cont_grade">Добавить счёт</div>
                            <div class="col-12 grade_title_lit cont_header_1">Файлы:</div>
                            <div class="col-12 row">
                                <div class="col-2 lit_marg_grade">Документ 1</div>
                                <div class="col-10 lit_marg_grade add_button_grade no_wrap_text">Документ1.jpg
                                    <iconify-icon icon="ci:off-close" style="color: #c4c4c4;" width="20"
                                                  height="20"></iconify-icon>
                                </div>
                            </div>
                            <div class="col-12 row">
                                <div class="col-6">
                                    <input @blur="update_order()" class="col-12 lit_marg_grade border_input"
                                           v-model="menedzer_zakazchik"/>
                                </div>
                                <div class="col-6">
                                    <span class="choose_file_grade ">Выбрать файл</span>
                                    <span class="choose_file_grade ">Загрузить файл</span>
                                </div>
                            </div>
                            <div class="col-12 add_cont_grade">Добавить документ</div>
                        </div>
                    </div>
                </b-modal>

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        data() {
            return {
                menedzer_zakazchik:'',
                komment_1:'',
            }}
    }
</script>
