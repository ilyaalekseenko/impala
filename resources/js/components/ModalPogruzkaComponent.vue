<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-10">
                <b-modal ref="my-modal" id="modal-xl" size="lg" hide-footer hide-header>
                    <div class="col-12 mod_borders_top mod_new_cargo row header_grade_mod_bot">
                        <div class="col-8">Новый грузоотправитель/грузополучатель:</div>
                        <div class="col-4 row d-flex justify-content-end">
                            <div class="col add_ts_button8 text-center" v-on:click="save_gruzootpravitel()">Сохранить</div>
                            <div class="col add_ts_button8 text-center" v-on:click="cancel_modal()">Отменить</div>
                        </div>
                    </div>
                    <div v-if="alert" class="alert alert-danger" role="alert">
                        <ul id="error_list">
                            <li v-for="item in alert_arr" >
                                {{ item }}
                            </li>
                        </ul>
                    </div>
                    <div class="col-12 no_padding_right no_padding_left row mod_borders_bottom">
                        <!--                        начало левой колонки модалка-->
                        <div class="col-6">
                            <div class="container-fluid">
                                <div class="col-12 row">
                                    <div class="col-3 no_padding_left_form">
                                        <div class="col-12 create_orders_date_title_1 lit_marg_grade">Форма</div>
                                        <select v-model="forma"
                                                class="col-12 create_orders_date_title_int_1 grade_marg_bot">
                                            <option v-bind:value="0" class="sel_cust">Константин Константинович</option>
                                            <option v-bind:value="1" class="sel_cust">Иван Иванович</option>
                                            <option v-bind:value="2" class="sel_cust">Джек Воробей</option>
                                            <option v-bind:value="3" class="sel_cust">Путин В.В.</option>
                                        </select>
                                    </div>
                                    <div class="col-5 grade_naz">
                                        <div class="create_orders_date_title_1 lit_marg_grade col-12">Название</div>
                                        <input class="border_input col-12"
                                               v-model="nazvanie"/>
                                    </div>
                                    <div class="col-3 date_width_col no_padding_left_form grade_marg_bot">
                                        <div class="create_orders_date_title_1 lit_marg_grade col-12 no_wrap_text">Дата регистрации</div>
                                        <input class="border_input date_width col-12" @click="openDB0"
                                               v-model="data_registracii"/>
                                        <date-picker ref="datepicker0"  v-model="data_registracii" valueType="format" format="DD.MM.YYYY" :open.sync="openDP" ></date-picker>

                                    </div>
                                </div>
                            </div>
                            <div class="container-fluid ">
                                <div class="col-12 row">
                                    <div class="col-3 inn_width no_padding_left_form inn_mar_r grade_marg_bot">
                                        <div class="col-12 create_orders_date_title_1 lit_marg_grade ">ИНН</div>
                                        <input  class="col-12 border_input inn_width"
                                               v-model="INN"/>
                                    </div>
                                    <div class="col-2 inn_width no_padding_left_form inn_mar_r grade_marg_bot">
                                        <div class="col-12 create_orders_date_title_1 lit_marg_grade no_wrap_text ">ОГРН(если есть)</div>
                                        <input class="col-12 border_input inn_width"
                                               v-model="OGRN"/>
                                    </div>
                                    <div class="col-2 inn_width no_padding_left_form inn_mar_r grade_marg_bot">
                                        <div class="col-12 create_orders_date_title_1 lit_marg_grade">Телефон</div>
                                        <input class="col-12 border_input inn_width"
                                               v-model="telefon"/>
                                    </div>
                                    <div class="col-2 inn_width no_padding_left_form grade_marg_bot">
                                        <div class="col-12 create_orders_date_title_1 lit_marg_grade">email</div>
                                        <input  class="col-12 border_input inn_width"
                                               v-model="email"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 row">
                                <div class="col-6">
                                    <div class="col-12 create_orders_date_title_1 lit_marg_grade">Генеральный
                                        директор
                                    </div>
                                    <input  class="col-12 border_input "
                                           v-model="generalnii_direktor"/>
                                </div>
                                <div class="offset-1 col-4">
                                    <div class="col-12 create_orders_date_title_1 lit_marg_grade">Телефон</div>
                                    <input  class="col-12 border_input"
                                           v-model="telefon_gen_dir"/>
                                </div>
                            </div>
                            <div class="col-12 grade_title_lit cont_header">Контакты:</div>
                            <div class="container-fluid ">
                                <div class="col-12 row" v-for="(oplata,key) in kontakty">
                                    <div class="col-3 inn_width no_padding_left_form inn_mar_r grade_marg_bot">
                                        <div class="col-12 create_orders_date_title_1 lit_marg_grade">Должность</div>
                                        <input  class="col-12 border_input inn_width"
                                               v-model="kontakty[key].dolznost"/>
                                    </div>
                                    <div class="col-3 inn_width no_padding_left_form inn_mar_r grade_marg_bot">
                                        <div class="col-12 create_orders_date_title_1 lit_marg_grade">ФИО</div>
                                        <input  class="col-12 border_input inn_width"
                                               v-model="kontakty[key].FIO"/>
                                    </div>
                                    <div class="col-2 inn_width no_padding_left_form inn_mar_r grade_marg_bot">
                                        <div class="col-12 create_orders_date_title_1 lit_marg_grade">Телефон</div>
                                        <input  class="col-12 border_input inn_width"
                                               v-model="kontakty[key].telefon"/>
                                    </div>
                                    <div class="col-2 inn_width no_padding_left_form inn_mar_r grade_marg_bot">
                                        <div class="col-12 create_orders_date_title_1 lit_marg_grade">email</div>
                                        <input  class="col-12 border_input inn_width"
                                               v-model="kontakty[key].email"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 add_cont_grade" v-on:click="dobavit_kontank()">Добавить контакт</div>
                        </div>
                        <!--                        конец левой колонки модалка-->
                        <!--                        начало правой колонки модалка-->
                        <div class="col-6">
                            <div class="col-12 grade_marg_bot">
                                <div class="col create_orders_date_title_1 lit_marg_grade ">Юридический адрес:</div>
                                <input  class="col-12 border_input"
                                       v-model="yridicheskii_adres"/>
                            </div>
                            <div class="col-12 grade_marg_bot">
                                <div class="col create_orders_date_title_1 lit_marg_grade">Почтовый адрес:</div>
                                <input  class="col-12 border_input"
                                       v-model="pochtovyi_adres"/>
                            </div>
                            <div class="col-12 grade_title_lit cont_header">Банковские реквизиты:</div>
                            <span v-for="(bank,key) in bank_arr">
                            <div class="col-12 row">
                                <div class="col-6 grade_marg_bot">
                                    <div class="col create_orders_date_title_1 lit_marg_grade">БИК банка:</div>
                                    <input  class="col-12 border_input"
                                           v-model="bank_arr[key].BIK"/>
                                </div>
                                <div class="col-6 bank_grade_marg grade_marg_bot">
                                    <span class="bank_grade ">Банк:</span>
                                    <span class="bank_grade_1 ">{{ bank_arr[key].bank }}</span>
                                </div>
                            </div>
                            <div class="col-12 row">
                                <div class="col-6 grade_marg_bot">
                                    <div class="col create_orders_date_title_1 lit_marg_grade">Расчётный счёт:</div>
                                    <input  class="col-12 border_input grade_marg_bot"
                                           v-model="bank_arr[key].raschetnyi"/>
                                    <div class="col create_orders_date_title_1 lit_marg_grade">Корсчёт:</div>
                                    <input  class="col-12 border_input"
                                           v-model="bank_arr[key].korschet"/>
                                </div>
                                <div class="col-6 grade_marg_bot">
                                    <span class="bank_grade ">Комментарий:</span>
                                    <textarea class="comm_settings"  v-model="bank_arr[key].kommentarii"
                                              rows="6" name="text"></textarea>
                                </div>
                            </div>
                                </span>
                            <div class="col-12 add_cont_grade" v-on:click="dobavit_bank()">Добавить счёт</div>

                            <input hidden="true" type="file" id="files" ref="files" v-on:change="handleFilesUpload()"/>

                            <span v-for="(loc_file,key) in doc_files">
                            <div class="col-12 grade_title_lit cont_header_1">Файлы:</div>
                            <div class="col-12 row">
                                <div class="col-2 lit_marg_grade">Документ {{ key+1 }}</div>
                                <div class="col-10 lit_marg_grade add_button_grade no_wrap_text">{{ loc_file.file_name }}.{{ loc_file.ext }}
                                    <iconify-icon icon="ci:off-close" style="color: #c4c4c4;" width="20"
                                                  v-if="loc_file.file_name"    height="20" v-on:click="delete_one_file_modal(key)"></iconify-icon>
                                </div>
                            </div>
                            <div class="col-12 row">
                                <div class="col-6">
                                    <input class="col-12 lit_marg_grade border_input"
                                           v-if="loc_file.file_name" v-model="loc_file.file_name"/>
                                </div>
                                <div class="col-6" >
                                    <span class="choose_file_grade " v-on:click="addFiles(key)">Загрузить файл</span>
                                    <span class="choose_file_grade " v-if="loc_file.file_name" v-on:click="download_modal_file(key)">Скачать файл</span>
                                </div>
                            </div>
                            </span>
                            <div class="col-12 add_cont_grade" v-on:click="dobavit_doc()">Добавить документ</div>
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
            // this.get_gruzootpravitel_modal();
            // this.dobavit_kontank();
            // this.dobavit_bank();
        },
        data() {
            return {
                forma:'',
                nazvanie:'',
                data_registracii:'',
                INN:'',
                OGRN:'',
                telefon:'',
                email:'',
                generalnii_direktor:'',
                telefon_gen_dir:'',
                yridicheskii_adres:'',
                pochtovyi_adres:'',
                menedzer_zakazchik:'',
                kontakty:[],
                openDP:false,
                bank_arr:[],
                files:[],
                doc_files:[],
                alert_arr:[],
                alert:false,
                //совпадает с ключём в массиве,аккуратно
                temp_file_id:''

            }},
        methods: {
            hideModal() {
                this.clear_data_modal()
                this.$refs['my-modal'].hide()
            },
            cancel_modal()
            {
                this.delete_files_modal()
                this.hideModal()
            },
            clear_data_modal()
            {
                this.forma='',
                this.nazvanie='',
                this.data_registracii='',
                this.INN='',
                this.OGRN='',
                this.telefon='',
                this.email='',
                this.generalnii_direktor='',
                this.telefon_gen_dir='',
                this.yridicheskii_adres='',
                this.pochtovyi_adres='',
                this.menedzer_zakazchik='',
                this.kontakty=[],
                this.openDP=false,
                this.bank_arr=[],
                this.files=[],
                this.doc_files=[],
                this.alert_arr=[],
                this.alert=false,
                this.temp_file_id=''
            },
            delete_one_file_modal(key)
            {
                axios
                    .post('/delete_one_file_modal',{
                        doc:this.doc_files[key]
                    })
                this.doc_files.splice(key, 1);

            },
            delete_files_modal()
            {
                axios
                    .post('/delete_files_modal',{
                        doc_files:this.doc_files
                    })
            },
            get_gruzootpravitel_modal() {
                axios
                    .post('/get_gruzootpravitel_modal',{
                    })
                    // .then(({ data }) => (
                    //         data.terminal.forEach(function(entry) {
                    //             inp.push({
                    //                 id:entry.id,
                    //                 name:entry.ts_name
                    //             });
                    //         })
                    //     )
                    // );
            },
            openDB0()
            {
                this.openDP=true
            },
            dobavit_kontank()
            {
                let objToPush= {};
                objToPush['dolznost'] = '';
                objToPush['FIO'] = '';
                objToPush['telefon'] = '';
                objToPush['email'] = '';
                this.kontakty.push(objToPush);
            },
            dobavit_bank()
            {
                let objToPush= {};
                objToPush['BIK'] = '';
                objToPush['raschetnyi'] = '';
                objToPush['korschet'] = '';
                objToPush['kommentarii'] = '';
                objToPush['bank'] = '';
                this.bank_arr.push(objToPush);
            },
            dobavit_doc()
            {

                let objToPush= {};
                objToPush['doc_name'] = '';
                objToPush['doc_path'] = '';
                objToPush['file_name'] = '';
                objToPush['ext'] = '';
                if(this.doc_files.length==0)
                {
                    objToPush['doc_id'] = 0;
                }
                else{
                    objToPush['doc_id'] =Number( this.doc_files[this.doc_files.length-1]['doc_id'])+Number(1)
                    }
                this.doc_files.push(objToPush);

            },
            download_modal_file(key)
            {
                window.location.href = '/download_modal_file/'+this.doc_files[key]['doc_path'];
            },
            addFiles(key){
                this.temp_file_id=key
                this.$refs.files.click();
            },
            handleFilesUpload()
            {
                let uploadedFiles = this.$refs.files.files;
                let full_name=(uploadedFiles[0].name.match(/([A-Za-zа-яА-Я0-9Ёё@ : , ' '' ; - _ = < > % # ~ `& !\W]+)/))
                let file_name=(uploadedFiles[0].name.match(/([A-Za-zа-яА-Я0-9Ёё@ : , ' '' ; - _ = < > % # ~ `& !\W]+)\./))
                let extension=(uploadedFiles[0].name.slice((Math.max(0, (uploadedFiles[0].name.lastIndexOf(".")) || Infinity) + 1)));
                let formData = new FormData();
                let file = uploadedFiles;
                formData.append('file', file[0]);
                formData.append('file_name',file_name[1]);
                formData.append('full_name',full_name[0]);
                formData.append('extension',extension);
                axios.post( '/store_modal_file_temp',
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                )
                    .then(response => {
                        if (response.data.status === 'success') {
                            this.doc_files[this.temp_file_id].doc_path=response.data.path
                            this.doc_files[this.temp_file_id].doc_name=full_name[0]
                            this.doc_files[this.temp_file_id].file_name=file_name[1]
                            this.doc_files[this.temp_file_id].ext=extension
                        }
                        else
                        {
                            alert('файл не сохранён')
                        }
                    })
                // }
                // else
                // {
                //     alert('Не верный формат файла')
                // }
            },
            save_gruzootpravitel()
            {
                this.alert_arr=[];
                this.alert=false;
                if(this.forma=="")
                {
                    this.alert=true;
                    this.alert_arr.push('Выберите форму');
                }
                if(this.alert==false)
                {
                axios
                    .post('/save_gruzootpravitel',{
                        forma:this.forma,
                        nazvanie:this.nazvanie,
                        data_registracii:this.data_registracii,
                        INN:this.INN,
                        OGRN:this.OGRN,
                        telefon:this.telefon,
                        email:this.email,
                        generalnii_direktor:this.generalnii_direktor,
                        telefon_gen_dir:this.telefon_gen_dir,
                        yridicheskii_adres:this.yridicheskii_adres,
                        pochtovyi_adres:this.pochtovyi_adres,
                        menedzer_zakazchik:this.menedzer_zakazchik,
                        kontakty:this.kontakty,
                        bank_arr:this.bank_arr,
                        doc_files:this.doc_files
                    })
                    this.hideModal()
                }
            }
        }
    }
</script>
