<template>
    <div class="container no_padding_right no_padding_left_imp">
            <modal-perevozchiki-component ref="modalComponentforAction" edit_flag=true :addOnePerevozchik='addOnePerevozchik' :change_one_gruzzotpravitel='change_one_gruzzotpravitel'></modal-perevozchiki-component>
            <div class="col-12 no_padding_right">
                <div class="col-6 orders_title">
                   Ставки
                </div>
                <div class="col-12 coloring_row">
                    <div class="col-12 under_col_title_main">
                        <input type="checkbox" id="checkbox" v-on:click="check_all()" v-model="checked_all">
                        <span class="under_colored_title head_font" v-on:click="delete_gruzootpravitel()">Удалить</span>
                        <span class="under_colored_title head_font">Переместить</span>
                        <span class="under_colored_title head_font" v-on:click="mix_mark_as_important(gruzootpravitel_arr,'Gruzootpravitel')" >Важное</span>
                    </div>
                    <div class="col-12 row  table_orders_column_settings">
                        <div class="col-12 row no_padding_right border_in_orders">
                            <div class="col-3 orders_title_table_main text-start row">
                                <div class="col-2"></div>
                                <div class="col-8 head_font no_padding_right_imp">Тип ТС
                                    <auto-input-stavki-component class=""
                                                                 @childReturnMethod="parentMethodStavki"
                                                                 tip="tipTS"
                                                                 ref="AutoPogruzka"
                                                                 :vidTsFromParent="tipTSSearch"
                                                                 modelSearch="VidTS"
                                                                 fieldToSearch="ts_name"
                                                                 fieldToSearchFinalGrade="vid_TS"
                                    ></auto-input-stavki-component>
                                </div>
                                <div v-if="tipTSSearch" class="col-2 cross_stavki" v-on:click="clearInput('tipTS')"><iconify-icon icon="akar-icons:cross" width="24" height="24"></iconify-icon></div>
                            </div>
                            <div class="col-2 orders_title_table_main head_font">Перевозчик
                                <div class="row">
                                    <auto-input-stavki-component class="col-10 no_padding_right_imp"
                                                          @childReturnMethod="parentMethodStavki"
                                                          tip="perevozchik"
                                                          ref="AutoPogruzka"
                                                          :vidTsFromParent="perevozchikSearch"
                                                          modelSearch="Perevozka"
                                                          fieldToSearch="nazvanie"
                                                          fieldToSearchFinalGrade="perevozchik"
                                    ></auto-input-stavki-component>
                                    <div v-if="perevozchikSearch" class="col-2 cross_stavki_perevozka" v-on:click="clearInput('perevozchik')"><iconify-icon icon="akar-icons:cross" width="24" height="24"></iconify-icon></div>
                                </div>
                            </div>
                            <div class="col-2 orders_title_table_main head_font">Ставки</div>
                            <div class="col-2 orders_title_table_main head_font">Откуда
                                <div class="row">
                                    <auto-input-stavki-component class="col-10 no_padding_right_imp"
                                                                 @childReturnMethod="parentMethodStavki"
                                                                 tip="otkuda"
                                                                 ref="AutoPogruzka"
                                                                 :vidTsFromParent="otkudaTSSearch"
                                                                 modelSearch="GruzootpravitelAdresa"
                                                                 fieldToSearch="adres"
                                                                 fieldToSearchFinalGrade="vid_TS"
                                                                 pogrVygrInp="1"
                                    ></auto-input-stavki-component>
                                    <div v-if="otkudaTSSearch" class="col-2 cross_stavki_perevozka" v-on:click="clearInput('otkuda')"><iconify-icon icon="akar-icons:cross" width="24" height="24"></iconify-icon></div>
                                </div>
                            </div>
                            <div class="col-2 orders_title_table_main head_font">Куда
                                <div class="row">
                                    <auto-input-stavki-component class="col-10 no_padding_right_imp"
                                                                 @childReturnMethod="parentMethodStavki"
                                                                 tip="kuda"
                                                                 ref="AutoPogruzka"
                                                                 :vidTsFromParent="kudaSearch"
                                                                 modelSearch="GruzootpravitelAdresa"
                                                                 fieldToSearch="adres"
                                                                 fieldToSearchFinalGrade="vid_TS"
                                                                 pogrVygrInp="2"
                                    ></auto-input-stavki-component>
                                    <div v-if="kudaSearch" class="col-2 cross_stavki_perevozka" v-on:click="clearInput('kuda')"><iconify-icon icon="akar-icons:cross" width="24" height="24"></iconify-icon></div>
                                </div>
                            </div>
                            <div class="col-1 orders_title_table_main head_font">Расстояние</div>
                        </div>

                        <div v-if="!paginationGif"  v-for="(gruzootpravitel,key) in gruzootpravitel_arr" class="col-12 row no_padding_right border_in_orders" v-bind:class="{ important_back: gruzootpravitel.important==1 }">
                        <div class="col-3 orders_title_table text-start row">
                            <input class="col-2 checkbox_orders" type="checkbox" id="checkbox1" v-model="gruzootpravitel.checked_order">
                            <div class="col-10" >{{ gruzootpravitel.vid_TSNazvanie }}</div>
                        </div>
                        <div class="col-2 orders_title_table" v-on:click="show_mod_edit(gruzootpravitel.perevozchik,key)" v-b-modal.perevozkaMod variant="primary">{{ gruzootpravitel.perevozchik_TSNazvanie }}</div>
                        <div class="col-2 orders_title_table" >{{ gruzootpravitel.stavka_summa }}р. {{ gruzootpravitel.NDS_check }}</div>
                            <!--откуда-->
                        <div class="col-2 orders_title_table">
                            <div v-for="(adres) in gruzootpravitel.adres_pogruzki_TS">
                                <span>{{ adres.adres_pogruzki_show }} </span>
                            </div>
                        </div>
                            <!--куда-->
                        <div class="col-2 orders_title_table">
                            <div v-for="(adres) in gruzootpravitel.adres_vygr_TS">
                                <span>{{ adres.adres_vygruzki_show }} </span>
                            </div>
                        </div>
                        <div class="col-1 orders_title_table" >{{ gruzootpravitel.rasstojanie_TS }}</div>
                        </div>
                        <div class="col-12" v-if="paginationGif">
                            <img class="imgLoading" src="/loading.gif">
                        </div>
                    </div>
                </div>
                <div class="col-12 pagination_stavki row">
                    <div class="col-6 pagination_orders_left pagination_orders_stavki">Показывать:
                         <span :class="{ blue_ref: limit!=20 }" v-on:click="show_by(20)">по 20</span>
                        / <span :class="{ blue_ref: limit!=50 }" v-on:click="show_by(50)">по 50</span>
                        / <span :class="{ blue_ref: limit!=100 }" v-on:click="show_by(100)">по 100</span>
                    </div>
                    <div class="col-5 row">
                        <!--            пагинация          -->
                        <nav class="col d-flex justify-content-end"  aria-label=" Page navigation example">
                            <pagination-component
                                :countAllTypes="countAllTypes"
                                :countLimit="limit"
                                :dataFromPagination="dataFromPagination"
                                ref="PaginationComponent"
                            ></pagination-component>
                        </nav>
                    </div>
                </div>

        </div>


    </div>
</template>

<script>
import moment from 'moment'
Vue.filter('formatDate', function(value) {
    if (value) {
        return moment(String(value)).format('DD.MM.YYYY')
    }
});
    export default {
        props: ['auth_user'],
        mounted() {
            this.get_gruzootpravitel_list(this.gruzootpravitel_arr)

        },
        data() {
            return {
                // pagination_all:0,
                // current_page:1,
                // pagination_numb:[],

                //пагинация
                offset_from_start:0,
                limit:20,
                // offset:0,
                countAllTypes:0,
                //общие переменные
                delete_arr:[],
                gruzootpravitel_arr:[],
                currentRow:'',
                checked_all: '',
                paginationGif:true,
                //переменные поиска
                tipTSSearch:'',
                perevozchikSearch:'',
                otkudaTSSearch:'',
                kudaSearch:''
            }
        },
        created()
        {

        },
        methods: {
            //метод очистки инпутов
            clearInput(type)
            {
                //очищаем инпут
                this.setTip(type,'')
                //устанавливаем пагинацию в ноль
                this.setPaginationToNull()
                //если хоть одно не пустое тогда ищем на бэке
                if(this.callSeacrh())
                {
                    this.searchOnBack()
                }
                //если всё пустое тогда вызываем общий метод поиска
                else
                {
                    this.get_gruzootpravitel_list();
                }

            },
            //метод вызываемый из дочернего компонента новый перевозчик
            addOnePerevozchik(newPerevId,nazvanie,yridicheskii_adres,kontakty)
            {
                this.gruzootpravitel_arr[this.currentRow].perevozchik=newPerevId
                this.gruzootpravitel_arr[this.currentRow].perevozchik_TSNazvanie=nazvanie
                this.addPerevozchikToStavka()
            },
            change_one_gruzzotpravitel(id,nazvanie,yr_adres,kontakty)
            {
                console.log(this.gruzootpravitel_arr[this.currentRow])
                this.gruzootpravitel_arr[this.currentRow].perevozchik=id
                this.gruzootpravitel_arr[this.currentRow].perevozchik_TSNazvanie=nazvanie
            },
            addPerevozchikToStavka()
            {
                axios
                    .post('/addPerevozchikToStavka',{
                        perevozchikId:this.gruzootpravitel_arr[this.currentRow].perevozchik,
                        finalGradeId:this.gruzootpravitel_arr[this.currentRow].id
                    })
            },
            show_mod_edit(id,key)
            {
                this.currentRow=key
                if(id==null)
                {
                    //вызов метода дочернего компонента( модального окна новый перевозчик )
                  this.$refs.modalComponentforAction.newPerevozchik()
                }
                else
                {
                   //вызов метода дочернего компонента( модального окна редактирования )
                   this.$refs.modalComponentforAction.get_modal_edit_data(id)
                }



            },

            check_all()
            {
                for( let i = 0; i < this.gruzootpravitel_arr.length; i++ )
                {
                   this.gruzootpravitel_arr[i].checked_order=!this.gruzootpravitel_arr[i].checked_order
                }
            },

            delete_gruzootpravitel()
            {
                let temp_arr=[];
                for( let i = 0; i < this.gruzootpravitel_arr.length; i++ )
                {
                        if(this.gruzootpravitel_arr[i].checked_order==true)
                        {
                            this.delete_arr.push(this.gruzootpravitel_arr[i].id)
                        }
                        else
                        {
                            temp_arr.push(this.gruzootpravitel_arr[i])
                        }
                }
                this.gruzootpravitel_arr=temp_arr
               axios
                   .post('/deleteStavka',{
                       ids:this.delete_arr,
                   })
                this.delete_arr=[]
            },
            //показывать по
            show_by(int)
            {
               // this.offset=int
                this.limit=int
                //устанавливаем пагинацию в ноль
                this.setPaginationToNull()
                //если хоть одно не пустое тогда ищем на бэке
                if(this.callSeacrh())
                {
                    this.searchOnBack()
                }
                //если всё пустое тогда вызываем общий метод поиска
                else
                {

                    this.get_gruzootpravitel_list();
                }
            },
            create_order() {
                window.location.href =('/create_orders')
            },
            //метод получения ставок
            get_gruzootpravitel_list()
            {
                this.paginationGif=true
                let inp_temp =[];
                axios
                    .post('/getStavkiList',{
                        offset:this.offset_from_start,
                        limit:this.limit,
                    })
                    .then(({ data }) => (
                            this.countAllTypes=data.tipesCount,
                                data.stavkiList.forEach(function(entry) {
                                inp_temp.push({
                                    id:entry.id,
                                    perevozchik:entry.perevozchik,
                                    vid_TSNazvanie:entry.vid_TSNazvanie,
                                    perevozchik_TSNazvanie:entry.perevozchik_TSNazvanie,
                                    stavka_summa:entry.stavka_summa,
                                    NDS_check:entry.NDS_check,
                                    adres_pogruzki_TS:entry.adres_pogruzki_TS,
                                    adres_vygr_TS:entry.adres_vygr_TS,
                                    rasstojanie_TS:entry.rasstojanie_TS
                                });
                            }),
                         this.paginationGif=false
                        ),
                        this.gruzootpravitel_arr=inp_temp

                    );
            },
            dataFromPagination(offset)
            {
                this.offset_from_start=offset
                this.gruzootpravitel_arr=[];
                if(this.callSeacrh())
                {
                    this.searchOnBack()
                }
                else
                {
                    this.get_gruzootpravitel_list()
                }

            },
            //главный метод поиска вызываемый из дочерних инпутов
            parentMethodStavki(data)
            {
                //устанавливаем значения пришедшие из поисковых инпутов
                this.setTip(data.tip,data.ts_name)
                //устанавливаем пагинацию в ноль
                this.setPaginationToNull()
                //если хоть одно не пустое тогда ищем на бэке
                if(this.callSeacrh())
                {
                    this.searchOnBack()
                }
                //если всё пустое тогда вызываем общий метод поиска
                else
                {
                    this.get_gruzootpravitel_list();
                }

            },
            setPaginationToNull()
            {
                this.$refs.PaginationComponent.setToDefaultState()
                this.offset_from_start=0;
                this.countAllTypes=0;
            },
            //метод поиска на бэке
            searchOnBack()
            {
                this.paginationGif=true
                let inp_temp=[];
                    axios
                        .post('/searchBackStavki',{
                            tipTS:this.tipTSSearch,
                            perevozchik:this.perevozchikSearch,
                            otkuda:this.otkudaTSSearch,
                            kuda:this.kudaSearch,
                            offset:this.offset_from_start,
                            limit:this.limit,
                        })
                        .then(({ data }) => (
                                    this.countAllTypes=data.tipesCount,
                                    data.stavkiList.forEach(function(entry) {
                                        inp_temp.push({
                                            id:entry.id,
                                            perevozchik:entry.perevozchik,
                                            vid_TSNazvanie:entry.vid_TSNazvanie,
                                            perevozchik_TSNazvanie:entry.perevozchik_TSNazvanie,
                                            stavka_summa:entry.stavka_summa,
                                            NDS_check:entry.NDS_check,
                                            adres_pogruzki_TS:entry.adres_pogruzki_TS,
                                            adres_vygr_TS:entry.adres_vygr_TS,
                                            rasstojanie_TS:entry.rasstojanie_TS
                                        });
                                    }),
                            this.paginationGif=false
                            ),
                            this.gruzootpravitel_arr=inp_temp

                        );
            },
            setTip(searchRow,inpDataSearch)
            {
                switch (searchRow) {
                    case 'tipTS':
                        this.tipTSSearch=inpDataSearch
                        break;
                    case 'perevozchik':
                        this.perevozchikSearch=inpDataSearch
                        break;
                    case 'otkuda':
                        this.otkudaTSSearch=inpDataSearch
                        break;
                    case 'kuda':
                        this.kudaSearch=inpDataSearch
                        break;
                }
            },
            //метод проверки не пустые ли все значения
            callSeacrh()
            {
                if((this.tipTSSearch=='')&&(this.perevozchikSearch=='')&&(this.otkudaTSSearch=='')&&(this.kudaSearch==''))
                {
                    return false
                }
                else
                {
                    return true
                }
            }


        }
    }
</script>
