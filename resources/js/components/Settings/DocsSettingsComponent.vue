<template>
            <div class="row justify-content-center" >
                <div  class="col-10 row justify-content-end trio_but">
                        <div class="col add_ts_button5 text-center doc_set_button" v-on:click="store_doc('TH')">Загрузить шаблон ТН</div>
                        <div class="col add_ts_button5 text-center doc_set_button" v-on:click="show_vars_func('TH')">Настройка шаблона ТН</div>
                        <div class="col add_ts_button5 text-center doc_set_button" v-on:click="download_current_doc('TH')">Скачать шаблон ТН</div>
                    <div class="doc_set_marg">{{ TH_name }}</div>
                        <div class="col add_ts_button5 text-center doc_set_button" v-on:click="store_doc('DOV')">Загрузить шаблон Доверенность</div>
                        <div class="col add_ts_button5 text-center doc_set_button" v-on:click="show_vars_func('DOV')">Настройка шаблона Доверенность</div>
                        <div class="col add_ts_button5 text-center doc_set_button" v-on:click="download_current_doc('DOV')">Скачать шаблон Доверенность</div>
                    <div class="doc_set_marg">Excel:{{ DOV_name }}/Doc:{{ DOV_DOC_name }}</div>
                        <div class="col add_ts_button5 text-center doc_set_button" v-on:click="store_doc('ZAI')">Загрузить шаблон Запроса</div>
                        <div class="col add_ts_button5 text-center doc_set_button" v-on:click="show_vars_func('ZAI')">Настройка шаблона Запроса</div>
                        <div class="col add_ts_button5 text-center doc_set_button" v-on:click="download_current_doc('ZAI')">Скачать шаблон Запроса</div>
                    <div class="doc_set_marg">{{ ZAI_name }}</div>

                    <div class="col add_ts_button5 text-center doc_set_button" v-on:click="store_doc_NOM()">Загрузить шаблон Номенклатуры</div>
                    <div class="doc_set_marg">{{ NOM_name }}</div>

                    <input hidden="true" type="file" id="files_doc" ref="files_doc"  v-on:change="handleFilesUploadDoc()"/>
                    <input hidden="true" type="file" id="files_doc" ref="files_docNom"  v-on:change="handleFilesUploadDocNom()"/>
                </div>
                <div>
                    В формате xlsx переменную указывать в виде {переменная} без пробелов в скобках. Формат xlsx обязателен, xls устаревший и приводит к не корректной работе. Перевеодить прямо в excel редакторе, без посторонних сайтов.
                </div>
                <div>
                    В формате docx переменную указывать в виде { переменная } .
                </div>
                <div class="col-12" v-if="show_TH_vars">
                    <button type="button" class="btn btn-secondary" v-on:click="mergeListExcel()">TEST</button>

                    <h2>Выберите страницу:<button type="button" class="btn btn-success" v-on:click="addList(1)">+</button></h2>
                    <div v-for="(item, index1) in TNListArr" :key="index1">
                        <input type="radio" :value="item.list_id" v-model="selectedItem" />
                        <input @blur="updateDocsInputList(item.list_id,item.list_name)"  v-model="item.list_name"  />
                        <button type="button" class="btn btn-danger btn_del_in_grade" v-on:click="deleteList(item.id,item.list_id)">-</button>
                    </div>
                    <p v-if="selectedItem">Выбрано: {{ selectedItem }}</p>

                    <div>Список ячеек
                       <button type="button" class="btn btn-success" v-on:click="addCell(1)">+</button>
                    </div>

                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Номер клетки</th>
                            <th scope="col">Ширина клетки</th>
                            <th scope="col">Название переменной</th>
                            <th scope="col">Размер шрифта</th>
                            <th scope="col">Удаление</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(item, index) in TNvars" v-if="item.list_id==selectedItem">
                            <th scope="row"><input @blur="updateDocsInputVarsByName(item.id,item.cell_number,'cell_number')"  v-model="item.cell_number"  /></th>
                            <td><input @blur="updateDocsInputVarsByName(item.id,item.width_cell,'width_cell')"  v-model="item.width_cell"  /></td>
                            <td><input @blur="updateDocsInputVarsByName(item.id,item.var_name,'var_name')"  v-model="item.var_name"  /></td>
                            <td><input @blur="updateDocsInputVarsByName(item.id,item.font_size,'font_size')"  v-model="item.font_size"  /></td>
                            <td><button type="button" class="btn btn-danger btn_del_in_grade" v-on:click="deleteCell(item.id)">-</button></td>
                        </tr>
                        </tbody>
                    </table>

                    <button type="button" class="btn btn-secondary" v-on:click="showBotVars()">Показать переменные</button>
                    <table class="table" v-if="show_bot_vars">
                        <thead>
                        <tr>
                            <th scope="col">№</th>
                            <th scope="col">Переменная</th>
                            <th scope="col">Что означает</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(group, index) in arr_TH_vars">
                            <th scope="row">{{ index+1 }}</th>
                            <td>{{ group.var_name }}</td>
                            <td>{{ group.description }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-12" v-if="show_DOV_vars">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">№</th>
                            <th scope="col">Переменная</th>
                            <th scope="col">Что означает</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(group, index) in arr_DOV_vars">
                            <th scope="row">{{ index+1 }}</th>
                            <td>{{ group.var_name }}</td>
                            <td>{{ group.description }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-12" v-if="show_ZAI_vars">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">№</th>
                            <th scope="col">Переменная</th>
                            <th scope="col">Что означает</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(group, index) in arr_ZAI_vars">
                            <th scope="row">{{ index+1 }}</th>
                            <td>{{ group.var_name }}</td>
                            <td>{{ group.description }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>


</template>

<script>

export default {
    props: ['transfer'],
created() {

},
    mounted() {
            this.get_templ_names()
            this.get_template_vars(this.arr_TH_vars,this.arr_DOV_vars,this.arr_ZAI_vars)
            this.TNvars=this.transfer.TNvars,
            this.TNListArr=this.transfer.TNListArr,
            this.setDefaultList()
    },
        data() {
            return {
                doc_type:'',
                TH_name:'',
                DOV_name:'',
                ZAI_name:'',
                DOV_DOC_name:'',
                NOM_name:'',
                arr_TH_vars:new Array(),
                arr_DOV_vars:new Array(),
                arr_ZAI_vars:new Array(),
                show_TH_vars:false,
                show_DOV_vars:false,
                show_ZAI_vars:false,
                nav_menu_0:false,
                nav_menu_1:false,
                nav_menu_2:false,
                nav_menu_3:false,
                nav_menu_show_var:'',
                TNvars:{},
                TNListArr: {},
                selectedItem: null,
                show_bot_vars:false,
                showNomSettings:false

            }
        },
        methods: {
            mergeListExcel()
            {
                axios
                    .post('/mergeListExcel',{
                    })
            },
            showBotVars()
            {
              this.show_bot_vars=!this.show_bot_vars
            },
        setDefaultList()
        {
            if((this.transfer.TNListArr[0]!=null)&&(this.transfer.TNListArr[0]!=='undefined'))
            {
                this.selectedItem = this.transfer.TNListArr[0].list_id
            }
        },
          async deleteCell(id)
            {
                const result = await this.confirmMethodMixin();
                if (result) {
                    let keyInArr=this.getKeyById(id,this.TNvars)
                    this.TNvars.splice(keyInArr,1)
                    axios
                        .post('/deleteCell',{
                            id:id,
                        })
                }
            },
           async deleteList(id,listId)
            {
                const result = await this.confirmMethodMixin();
                if (result) {
                    if(listId==this.selectedItem)
                    {
                        this.selectedItem=''
                    }
                    let keyInArr=this.getKeyById(id,this.TNListArr)
                    this.TNListArr.splice(keyInArr,1)
                    axios
                       .post('/deleteList',{
                            id:listId,
                       })
                }
            },
            getKeyById(id,objCust)
            {
                for(var i = 0; i < objCust.length; i++)
                {
                    if(objCust[i]['id']==id)
                   {
                       return i
                    }
                }
            },
            updateDocsInputList(id,list_name)
            {
                axios
                    .post('/updateDocsInputList',{
                        id:id,
                        list_name:list_name,
                    })
            },
            updateDocsInputVarsByName(id,cellVar,cellName)
            {
                axios
                    .post('/updateDocsInputVarsByName',{
                        id:id,
                        cellVar:cellVar,
                        cellName:cellName
                    })
            },
            addCell(id)
            {
                axios
                    .post('/addEmptyCell',{
                        docId:id,
                        listId:this.selectedItem,

                    })
                    .then(response => {
                        let objToPush1= {};
                        objToPush1['id'] = response.data.dataCell.id;
                        objToPush1['list_id'] = this.selectedItem;
                        objToPush1['cell_number'] = '';
                        objToPush1['doc_type'] = '';
                        objToPush1['width_cell'] = '';
                        objToPush1['var_name'] = '';
                        objToPush1['font_size'] = '';
                        this.TNvars.push(objToPush1);
                    })
            },
            addList(id)
            {
                axios
                    .post('/addEmptyList',{
                        docId:id
                    })
                    .then(response => {
                        let objToPush= {};
                        objToPush['id'] = '';
                        objToPush['list_id'] = response.data.data.id;
                        objToPush['list_name'] = '';
                        objToPush['doc_type'] = this.current_doc;
                        this.TNListArr.push(objToPush);

                        let objToPush1= {};
                        objToPush1['id'] = response.data.dataCell.id;
                        objToPush1['list_id'] = response.data.data.id;
                        objToPush1['cell_number'] = '';
                        objToPush1['doc_type'] = '';
                        objToPush['width_cell'] = '';
                        objToPush['var_name'] = '';
                        objToPush['font_size'] = '';
                        this.TNvars.push(objToPush1);
                        console.log('TNvars')
                        console.log(this.TNvars)
                    })
            },
           async download_current_doc(doc_type)
            {
                if(doc_type=='DOV')
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
                        doc_type='DOV_DOC';
                    } else if (result.dismiss === this.$swal.DismissReason.cancel) {
                        doc_type='DOV';
                    } else {
                        doc_type=''
                    }
                }
                this.current_doc=doc_type
                axios
                    .post('/download_current_doc',{
                        doc_type:this.current_doc

                    })
                    .then(response => {

                        window.location.assign('/get_finall_doc_pdf_file/templates/'+response.data.file) ;

                    })
            },
            nav_menu_show(numb)
            {
               this.nav_menu_show_var=numb

            },
            all_nav_false()
            {
                this.nav_menu_0=false,
                this.nav_menu_1=false,
                this.nav_menu_2=false,
                this.nav_menu_3=false
            },
            show_vars_func(type)
            {
                if(type=='TH')
                {
                    this.show_TH_vars=!this.show_TH_vars
                    this.show_DOV_vars=false
                    this.show_ZAI_vars=false
                }
                if(type=='DOV')
                {
                    this.show_DOV_vars=!this.show_DOV_vars
                    this.show_TH_vars=false
                    this.show_ZAI_vars=false
                }
                if(type=='ZAI')
                {
                    this.show_ZAI_vars=!this.show_ZAI_vars
                    this.show_DOV_vars=false
                    this.show_TH_vars=false
                }
            },
            get_template_vars(arr_TH_vars,arr_DOV_vars,arr_ZAI_vars)
            {
                axios
                    .post('/get_template_vars',{

                    })
                    .then(({ data }) => {
                        data.templ_vars1.forEach(function(entry) {
                            arr_TH_vars.push({
                                id:entry.id,
                                var_name:entry.var_name,
                                description:entry.description,
                                doc_type:entry.doc_type,
                            });
                        })
                        data.templ_vars2.forEach(function(entry) {
                            arr_DOV_vars.push({
                                id:entry.id,
                                var_name:entry.var_name,
                                description:entry.description,
                                doc_type:entry.doc_type,
                            });
                        })
                        data.templ_vars3.forEach(function(entry) {
                            arr_ZAI_vars.push({
                                id:entry.id,
                                var_name:entry.var_name,
                                description:entry.description,
                                doc_type:entry.doc_type,
                            });
                        })
                    })
            },
            get_templ_names()
            {
                axios.post( '/get_templ_names',

                )
                    .then(response => {
                    this.TH_name=response.data.TH
                    this.DOV_name=response.data.DOV
                    this.ZAI_name=response.data.ZAI
                    this.DOV_DOC_name=response.data.DOV_DOC
                    this.NOM_name=response.data.NOM
                    })
            },
            store_doc_NOM()
            {
                this.$refs.files_docNom.click();
            },
          async store_doc(type)
            {
                if(type=='DOV')
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
                        type='DOV_DOC';
                    } else if (result.dismiss === this.$swal.DismissReason.cancel) {
                        type='DOV';
                    } else {
                        type=''
                    }
                }
                if(type!='')
                {
                    this.doc_type=type
                    this.$refs.files_doc.click();
                }

            },

            handleFilesUploadDocNom()
            {
                this.doc_type='NOM';
                let full_name = '';
                let ext = '';
                let uploadedFiles = this.$refs.files_docNom.files;
                let flag = 0;
                ext = (uploadedFiles[0].name.match(/\.[0-9a-z]+$/i))
                full_name = (uploadedFiles[0].name.match(/([A-Za-zа-яА-Я0-9Ёё\W]+)/))
                if (ext[0] == '.xlsx') {
                    flag = 1;
                }
                if (flag == 1)
                {
                    let formData = new FormData();
                    let file = uploadedFiles;
                    formData.append('file_xlsx', file[0]);
                    formData.append('doc_type', this.doc_type);
                    formData.append('full_name', full_name[0]);
                    axios.post('/store_doc_templ_nom',
                        formData,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }
                    )
                        .then(response => {
                            if(response.data.status=='success')
                            {
                                if(this.doc_type== 'NOM')
                                {
                                    this.NOM_name=full_name[0]
                                }
                            }
                        })
                }
                else
                {
                    alert('Формат файла должен быть xlsx')
                }
            },

            handleFilesUploadDoc() {
                let full_name = '';
                let ext = '';
                let uploadedFiles = this.$refs.files_doc.files;
                let flag = 0;
                ext = (uploadedFiles[0].name.match(/\.[0-9a-z]+$/i))
                if (this.doc_type == 'ZAI') {
                    if (ext[0] == '.doc' || ext[0] == '.docx' || ext[0] == '.txt') {
                        flag = 1;
                    }
                }
                if ((this.doc_type == 'DOV') || (this.doc_type == 'TH')) {
                    if (ext[0] == '.xls' || ext[0] == '.xlsx' || ext[0] == '.xlsm') {
                        flag = 1;
                    }

                }
                if (this.doc_type == 'DOV_DOC') {
                    if (ext[0] == '.doc' || ext[0] == '.docx' || ext[0] == '.txt') {
                        flag = 1;
                    }

                }
                if (flag == 1)
                {
                    full_name = (uploadedFiles[0].name.match(/([A-Za-zа-яА-Я0-9Ёё\W]+)/))

                    if(this.doc_type == 'TH')
                    {
                        if((full_name[0]==this.DOV_name)||(full_name[0]==this.ZAI_name)||(full_name[0]==this.DOV_DOC_name))
                        {
                            flag=0;
                        }
                    }
                    if(this.doc_type == 'DOV')
                    {
                        if((full_name[0]==this.TH_name)||(full_name[0]==this.ZAI_name)||(full_name[0]==this.DOV_DOC_name))
                        {
                            flag=0;
                        }
                    }
                    if(this.doc_type == 'ZAI')
                    {
                        if((full_name[0]==this.DOV_name)||(full_name[0]==this.TH_name)||(full_name[0]==this.DOV_DOC_name))
                        {
                            flag=0;
                        }
                    }
                    if(this.doc_type == 'DOV_DOC')
                    {
                        if((full_name[0]==this.DOV_name)||(full_name[0]==this.TH_name)||(full_name[0]==this.ZAI_name))
                        {
                            flag=0;
                        }
                    }
                    if (flag == 1)
                    {
                    let formData = new FormData();
                let file = uploadedFiles;
                formData.append('file_xlsx', file[0]);
                formData.append('doc_type', this.doc_type);

                formData.append('full_name', full_name[0]);
                axios.post('/store_doc_templ',
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                )
                    .then(response => {
                        if(response.data.status=='success')
                        {
                            if(this.doc_type== 'TH')
                            {
                                this.TH_name=full_name[0]
                            }
                            if(this.doc_type== 'DOV')
                            {
                                this.DOV_name=full_name[0]
                            }
                            if(this.doc_type== 'ZAI')
                            {
                                this.ZAI_name=full_name[0]
                            }
                            if(this.doc_type== 'DOV_DOC')
                            {
                                this.DOV_DOC_name=full_name[0]
                            }
                        }
                    })
                    }
                    else {
                        alert('Такое имя файла уже существует')
                    }
                 }
                else
                {
                    alert('не верный формат файла')
                }
            },
        }

    }
</script>
