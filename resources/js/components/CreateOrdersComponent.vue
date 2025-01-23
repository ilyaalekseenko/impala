<template>
  <modal-pogruzka-component
      :chahgeFrontNames="chahgeFrontNames"
      edit_flag=true
      vid="CreateOrdersComponent"
      ref="modalComponentforAction"
  ></modal-pogruzka-component>
  <modal-perevozchiki-component
      ref="modalComponentforActionPerevozchik"
      vid="CreateOrdersComponent"
      :gradeAddPerevozchik='gradeAddPerevozchik'
  ></modal-perevozchiki-component>

  <div class="impala-subheader">
    <div class="impala-page-title inline-flex gap-2"><span>Запрос номер:</span>
      <div>
        <div class="impala-add-link" v-if="nomerZaprosaShowInp" v-on:click="nomerZaprosaShowInpChange()"
             v-html="new_nomer_zayavki"></div>
        <input class="impala-input" v-else @change="updateOrderNomerZaprosa()"
               v-click-outside="nomerZaprosaShowInpChange" v-model="new_nomer_zayavki"/>
      </div>
    </div>

    <div class="impala-col-input">
      <div>
        <div class="impala-add-link" v-if="statusShowInp" v-on:click="statusShowInpChange()" v-html="status"></div>
        <VueSelect
            v-else
            :placeholder="'Статус запроса'"
            @option-selected="(option) => setColumn(option.value)"
            :options="[{ label: 'Оценка', value: 'ocenka', disabled: !ocenka_show_button },{ label: 'Утверждение', value: 'naznachenie_stavki', disabled: !utverzdenie_show_button },{ label: 'В работе', value: 'v_rabote', disabled: !v_rabote_show_button }]"
            :isClearable="false"
            :isSearchable="false"
            v-click-outside="statusShowInpChange"
        />
      </div>
    </div>

    <div class="justify-start items-center gap-8 inline-flex">
      <div class="impala-col-input">
        <div class="impala-label">Вид перевозки:</div>
        <div>
          <div class="impala-add-link" v-if="vidPerevozkiShowInp" v-on:click="vidPerevozkiShowInpChange()"
               v-html="vid_perevozki_name"></div>
          <VueSelect
              v-else
              :placeholder="'Вид перевозки'"
              @option-selected="(option) => updateOrderLoc('vid_perevozki', vid_perevozki, option.label)"
              v-model="vid_perevozki"
              :options="perevozka_arr"
              :isClearable="false"
              :isSearchable="false"
              v-click-outside="vidPerevozkiShowInpChange"
          />
        </div>
      </div>
      <div class="impala-col-input">
        <div class="impala-label">Дата внесения:</div>
        <div>
          <div class="impala-add-link" v-if="dataVneseniyaShowInp" v-on:click="dataVneseniyaShowInpChange()"
               v-html="(data_vneseniya === '' ||  data_vneseniya === null) ? 'Выбрать дату и время' : data_vneseniya"></div>
          <date-picker v-else ref="datepicker0" v-model:value="data_vneseniya" type="datetime" valueType="format"
                       format="DD.MM.YYYY H:mm" @change="updateOrderLoc('data_vneseniya',data_vneseniya)"
                       @close="dataVneseniyaShowInpChange()" :editable="false"></date-picker>
        </div>
      </div>
      <div class="impala-col-input" v-show="checkRolePermission([1])">
        <div class="impala-label">Логист:</div>
        <div>
          <div class="impala-add-link" v-if="logist_list" v-on:click="logist_show" v-html="logist_name"></div>
          <VueSelect
              v-else
              @option-selected="(option) => handleChange(option.label)"
              v-model="logist"
              :options="logist_list_full"
              :isClearable="false"
              :isSearchable="false"
              v-click-outside="logist_show"
          />
        </div>
      </div>
      <div class="impala-col-input">
        <div class="impala-label">Рассчитать до:</div>
        <div class="impala-add-link" v-if="rasschitatDoShowInp" v-on:click="rasschitatDoShowInpChange()"
             v-html="(rasschitat_do === '' ||  rasschitat_do === null) ? 'Выбрать дату и время' : rasschitat_do"></div>
        <date-picker v-else ref="datepicker1" v-model:value="rasschitat_do" type="datetime" valueType="format"
                     format="DD.MM.YYYY H:mm" @change="updateOrderLoc('rasschitat_do', rasschitat_do);"
                     @close="rasschitatDoShowInpChange()" :editable="false"></date-picker>
      </div>
    </div>
  </div>

  <div class="impala-request__wrap p-5 justify-between items-start gap-6 flex">
    <aside class="w-[475px] m-0 flex-col justify-start items-start gap-6 flex grow-0 shrink-0">
      <div class="flex-col justify-center items-start gap-4 flex w-full">
        <div class="impala-block-title">Основная информация</div>
        <div class="impala-box w-full p-4 flex-col justify-start items-start gap-6 flex">
          <div class="impala-row-input self-stretch">
            <div class="impala-label">
              Адрес погрузки
              <span class="impala-btn-none" v-on:click="adresPogruzkiShowSpanChange"><i
                  class="iconsax-edit-1"></i></span>
            </div>
            <template v-if="adresPogruzkiShowSpan">
              <div class="impala-add-link" v-if="(adres_pogruzke==='')||(adres_pogruzke==null)"
                   v-on:click="adresPogruzkiShowSpanChange" v-html="'Выбрать адрес'"></div>
              <div v-else v-html="adres_pogruzke" class="impala-add-link:none"></div>
            </template>
            <textarea v-else class="impala-input" @change="updateOrderLoc('adres_pogruzke', adres_pogruzke)"
                      v-model="adres_pogruzke" rows="3" name="adres_pogruzke"
                      v-click-outside="adresPogruzkiShowSpanChange"></textarea>
          </div>
          <div class="impala-row-input">
            <div class="impala-label">
              Дата погрузки
            </div>
            <div class="impala-add-link" v-if="dataPogruzkiShowInp" v-on:click="dataPogruzkiShowInpChange()"
                 v-html="(data_pogruzki === '' || data_pogruzki === null) ? 'Выбрать дату' : data_pogruzki"></div>
            <date-picker v-else ref="datepicker2" type="date" valueType="format" v-model:value="data_pogruzki"
                         format="DD.MM.YYYY" @change="updateOrderLoc('data_pogruzki', data_pogruzki)"
                         @close="dataPogruzkiShowInpChange()" :editable="false"></date-picker>
          </div>

          <div class="grid grid-cols-2 gap-6">
            <div class="impala-row-input">
              <div class="impala-label">
                Грузомест (Всего/Осталось)
              </div>
              <div class="grid grid-cols-2 gap-2.5">
                <input @change="updateOrderLocNumber('gruzomesta_big',gruzomesta_big)" class="impala-input"
                       v-model="gruzomesta_big"/>
                <input @change="updateOrderLocNumber('gruzomesta_small',gruzomesta_small)"
                       class="impala-input impala-input-red" v-model="gruzomesta_small" readonly/>
              </div>
            </div>
            <div class="impala-row-input">
              <div class="impala-label">
                Расстояние, км
              </div>
              <input @change="updateOrderLocNumber('rasstojanie',rasstojanie)" class="impala-input"
                     v-model="rasstojanie"/>
            </div>
            <div class="impala-row-input">
              <div class="impala-label">
                Общий вес, кг
              </div>
              <input @change="updateOrderLocNumber('ob_ves',ob_ves)" class="impala-input"
                     v-model="ob_ves"/>
            </div>
            <div class="impala-row-input">
              <div class="impala-label">
                Общий объём, м3
              </div>
              <input @change="updateOrderLocNumber('ob_ob',ob_ob)" class="impala-input"
                     v-model="ob_ob"/>
            </div>
          </div>

          <div class="impala-row-input self-stretch">
            <div class="impala-label">
              Адрес выгрузки
              <span class="impala-btn-none" v-on:click="adresVygruzkiShowSpanChange"><i
                  class="iconsax-edit-1"></i></span>
            </div>
            <template v-if="adresVygruzkiShowSpan">
              <div class="impala-add-link" v-if="(adres_vygruski==='')||(adres_vygruski==null)"
                   v-on:click="adresVygruzkiShowSpanChange" v-html="'Выбрать адрес'"></div>
              <div v-else v-html="adres_vygruski" class="impala-add-link:none"></div>
            </template>
            <textarea v-else class="impala-input" @change="updateOrderLoc('adres_vygruski', adres_vygruski)"
                      v-model="adres_vygruski" rows="3" name="adres_vygruski"
                      v-click-outside="adresVygruzkiShowSpanChange"></textarea>
          </div>
        </div>
      </div>
      <div class="flex-col justify-center items-start gap-4 flex w-full">
        <div class="impala-block-title">Информация о клиенте</div>
        <div class="impala-box w-full p-4 flex-col justify-start items-start gap-6 flex">
          <div class="impala-row-input self-stretch" v-show="checkRolePermission([1])">
            <div class="impala-label">
              Компания заказчик
              <span class="impala-btn-none" v-on:click="select_temp_var('zakazchik',0)"><i
                  class="iconsax-add-square"></i></span>
            </div>
            <div class="impala-add-link" v-if="zakazchikShowInp"
                 v-on:click="zakazchikShowInpChange()"
                 v-html="kompaniya_zakazchik_name==='' ? 'Выбрать заказчика' : kompaniya_zakazchik_name ">
            </div>
            <auto-input-zakazchik-component v-else
                                            :order_id="order_id"
                                            :vidTsFromParent="kompaniya_zakazchik_name"
                                            @childReturnMethod="parentMethodFromAutoinputZakazchik"
                                            @childCloseAutoInput="closeParentAutoInput"
                                            ref="AutoSelectComponent_vid_TS"
            ></auto-input-zakazchik-component>
          </div>
          <div class="impala-row-input self-stretch" v-show="checkRolePermission([1])">
            <div class="impala-label">
              Менеджер заказчика
              <span class="impala-btn-none" v-on:click="select_temp_var('manager',0)"><i
                  class="iconsax-add-square"></i></span>
            </div>
            <div class="impala-add-link"
                 v-if="managerZakazchikShowInp" v-on:click="managerShowInpChange()"
                 v-html="managerZakazchik_name==='' ? 'Выбрать менеджера' : managerZakazchik_name">
            </div>
            <auto-input-manager-component v-else
                                          :order_id="order_id"
                                          :vidTsFromParent="managerZakazchik_name"
                                          :zakazchikId="kompaniya_zakazchik_id"
                                          @childReturnMethod="parentMethodFromAutoinputManager"
                                          @childCloseAutoInput="closeParentAutoInputManager"
                                          ref="AutoSelectComponent_vid_TS"
            ></auto-input-manager-component>
          </div>
          <div class="impala-row-input" v-show="checkRolePermission([1])">
            <div class="impala-label">
              Бюджет контракта
            </div>
            <input @change="updateOrderLocNumber('cena_kontrakta',cena_kontrakta)"
                   class="impala-input" v-model="cena_kontrakta"/>
          </div>
        </div>
      </div>
      <div class="flex-col justify-center items-start gap-4 flex w-full">
        <div class="impala-block-title">Комментарий</div>
        <div class="impala-box w-full p-4 flex-col justify-start items-start gap-6 flex">
          <textarea class="impala-input" @change="updateOrderLoc('komment_1',komment_1)" v-model="komment_1" rows="3"
                    name="text"></textarea>
        </div>
      </div>
    </aside>

    <div class="flex-col justify-center items-start gap-4 flex grow">
      <div class="self-stretch justify-between items-center flex">
        <div class="impala-block-title">Список ТС</div>
        <div class="justify-start items-start gap-6 flex">
          <div class="impala-col-input">
            <div class="impala-label">Общий бюджет</div>
            <div class="impala-block-title">{{ ob_budjet }} ₽</div>
          </div>
        </div>
        <div class="justify-end items-start gap-6 flex">
          <div class="impala-btn impala-btn-small impala-btn-primary" v-on:click="addTS()"><i
              class="iconsax-add-circle"></i> Добавить ТС
          </div>
        </div>
      </div>

      <div v-for="(elem, key) in spisokTSarr"
           class="impala-box self-stretch flex-col justify-center items-start gap-0 flex"
           :class="add_ts===key && 'impala-outline'">
        <div class="self-stretch p-4 border-b border-[#e3eaea] justify-start items-center gap-4 inline-flex">
          <div
              class="w-8 h-8 p-2.5 bg-white rounded-[50px] border border-[#d3dddc] flex-col justify-center items-center gap-2.5 inline-flex">
            <div class=" text-[#708e8c] text-sm font-semibold">{{ key + 1 }}</div>
          </div>
          <div class="flex-col justify-start items-start gap-[5px] inline-flex">
            <div class="justify-center items-center gap-2.5 inline-flex">
              <template v-if="add_ts!==key" v-for="(one_ts,key1) in ts_list_names">
                <div v-if="!!one_ts && one_ts['id']===elem.vid_TS" class="impala-add-link:none">{{
                    one_ts.ts_name
                  }}
                </div>
              </template>
              <auto-input-global-component v-else class="select_width"
                                           :vidTsFromParent="vidTsNazavanie"
                                           :order_id="order_id"
                                           :key_in_arr="key"
                                           @childReturnMethod="parentMethodFromAutoinput"
                                           ref="AutoSelectComponent_vid_TS"
              ></auto-input-global-component>
            </div>
          </div>
          <div class="grow shrink basis-0 justify-end items-center gap-4 flex">
            <div v-if="add_ts!==key" v-on:click="editTs(key)" class="impala-btn impala-btn-small impala-btn-gray">
              <i class="iconsax-edit-1"></i>
              Редактировать
            </div>

            <template v-else>
              <div class="impala-btn impala-btn-small impala-btn-primary" v-on:click="save_TS()">Сохранить</div>
              <div class="impala-btn impala-btn-small impala-btn-red" v-on:click="deleteTs()">Удалить</div>
              <div class="impala-btn impala-btn-small impala-btn-gray" v-on:click="cancel_TS()">Отмена</div>
            </template>
          </div>
        </div>
        <div class="self-stretch p-4 justify-between items-start gap-4 inline-flex">
          <div class="grow shrink basis-0 flex-col justify-start items-start gap-6 inline-flex">
            <div class="self-stretch justify-between items-center inline-flex gap-4">
              <div class="impala-row-input">
                <div class="impala-label">Расстояние, км</div>
                <div v-if="add_ts!==key" class="impala-add-link:none"
                     v-html="(elem.rasstojanie_TS) ? elem.rasstojanie_TS : '---'"></div>
                <input v-else class="impala-input" v-model="rasstojanie_TS"
                       @input="updateStavkaPerevozchikRasstojanie('rasstojanie_TS', rasstojanie_TS)"/>
              </div>
              <div class="impala-row-input">
                <div class="impala-label">Грузомест</div>
                <div v-if="add_ts!==key" class="impala-add-link:none"
                     v-html="(elem.kol_gruz_TS) ? elem.kol_gruz_TS : '---'"></div>
                <input v-else class="impala-input" v-model="kol_gruz_TS"
                       @blur="updateNumberToFormat('kol_gruz_TS',kol_gruz_TS)"/>
              </div>
              <div class="impala-row-input">
                <div class="impala-label">Кол-во ТС</div>
                <div v-if="add_ts!==key" class="impala-add-link:none"
                     v-html="(elem.kol_TS_TS) ? elem.kol_TS_TS : '---'"></div>
                <input v-else class="impala-input" v-model="kol_TS_TS"
                       @blur="updateNumberToFormat('kol_TS_TS',kol_TS_TS)"/>
              </div>
              <div class="impala-row-input">
                <div class="impala-label">Ставка</div>
                <div v-if="add_ts!==key" class="impala-add-link:none"
                     v-html="(elem.stavka_TS) ? elem.stavka_TS + ' ₽' : '---'"></div>
                <input v-else class="impala-input" v-model="stavka_TS" @input="updateStavkaBezNDS"/>
              </div>
              <div class="impala-row-input">
                <div class="impala-label">Ставка без НДС</div>
                <div v-if="add_ts!==key" class="impala-add-link:none"
                     v-html="(elem.stavka_TS_bez_NDS) ? elem.stavka_TS_bez_NDS + ' ₽' : '---'"></div>
                <input v-else class="impala-input" v-model="stavka_TS_bez_NDS" @input="updateStavka"/>
              </div>
              <div class="impala-row-input">
                <div class="impala-label">Ставка за км</div>
                <div v-if="add_ts!==key" class="impala-add-link:none"
                     v-html="(elem.stavka_TS_za_km) ? elem.stavka_TS_za_km + ' ₽' : '---'"></div>
                <input v-else class="impala-input" v-model="elem.stavka_TS_za_km" disabled/>
              </div>
              <div class="impala-row-input" v-show="checkRolePermission([1])">
                <div class="impala-label">Ставка КП</div>
                <div v-if="add_ts!==key" class="impala-add-link:none"
                     v-html="(elem.stavka_kp_TS) ? elem.stavka_kp_TS + ' ₽' : '---'"></div>
                <input v-else class="impala-input" v-model="stavka_kp_TS"
                       @blur="updateNumberToFormat('stavka_kp_TS',stavka_kp_TS)"/>
              </div>
              <div class="impala-row-input" v-show="checkRolePermission([1])">
                <div class="impala-label">Маржа</div>
                <div v-if="add_ts!==key" class="impala-add-link:none"
                     v-html="(elem.marja_TS) ? elem.marja_TS + ' ₽' : '---'"></div>
                <input v-else class="impala-input" v-model="elem.marja_TS" disabled/>
              </div>
            </div>
            <div v-if="elem.adres_pogruzki_TS.length !== 0 || elem.adres_vygr_TS.length !== 0 || add_ts===key"
                 class="grid grid-cols-2 gap-6 w-full">
              <div class="self-stretch flex-col justify-start items-start gap-2.5 flex"
                   v-if="elem.adres_pogruzki_TS.length !== 0 || add_ts===key">
                <div v-if="add_ts!==key" v-for="(elem1, key1) in elem.adres_pogruzki_TS"
                     class="self-stretch impala-row-input pl-4 border-l-4 border-[#fff4de]">
                  <div class="impala-label">#{{ key1 + 1 }} Адрес погрузки</div>
                  <div class="impala-add-link:none">
                    {{ elem1.adres_pogruzke_show }}
                  </div>
                </div>
                <template v-else>
                  <div v-for="(elem, key) in ad_pogruzki_arr_temp"
                       class="self-stretch impala-row-input pl-4 border-l-4 border-[#fff4de]">
                    <div class="impala-label">
                      Адрес погрузки
                      <VMenu>
                        <button class="iconsax-menu-meatballs"></button>

                        <template #popper>
                          <div class="p-2 flex-col justify-center items-start gap-2.5 inline-flex">
                            <div class="impala-text-link " v-on:click="select_temp_var('TS_pogruzka',key)"><i
                                class="iconsax-add-square"></i> Добавить нового грузоотправителя/грузополучателя
                            </div>
                            <div class="impala-text-link impala-text-link:red"
                                 v-on:click="delete_adres(ad_pogruzki_arr_temp,key,'AutoSelectComponent_pogruzka_edit','adres_pogruzke_show')">
                              <i
                                  class="iconsax-trash"></i> Удалить
                            </div>
                          </div>
                        </template>
                      </VMenu>
                    </div>
                    <auto-input-author-component inp_type='add_pogruzka_edit'
                                                 :adres_pogruzke_show="flag_pogruz"
                                                 :adres_pogruzke_show_edit="ad_pogruzki_arr_temp[key]['adres_pogruzke_show']"
                                                 :order_id="order_id"
                                                 v-bind:gruzootpravitel_arr="gruzootpravitel_arr"
                                                 :key_in_arr="key"
                                                 @add_pogruzka_new="add_new_pogruzka_in_ts"
                                                 ref="AutoSelectComponent_pogruzka_edit"
                                                 :changePogrVygrAllShow="changePogrVygrAllShow"
                    ></auto-input-author-component>
                  </div>

                  <div class="px-2">
                    <div class="impala-add-link" v-on:click="add_empty_adres_pogr()">Добавить адрес погрузки</div>
                  </div>

                </template>
              </div>
              <div class="self-stretch flex-col justify-start items-start gap-2.5 flex"
                   v-if="elem.adres_vygr_TS.length !== 0 || add_ts===key">
                <div v-if="add_ts!==key" v-for="(elem1, key1) in elem.adres_vygr_TS"
                     class="self-stretch impala-row-input pl-4 border-l-4 border-[#c9f7f5]">
                  <div class="impala-label">#{{ key1 + 1 }} Адрес выгрузки</div>
                  <div class="impala-add-link:none">{{ elem1.adres_vygruzki_show }}</div>
                </div>
                <template v-else>
                  <div v-for="(elem,key) in ad_vygruz_arr_temp"
                       class="self-stretch impala-row-input pl-4 border-l-4 border-[#c9f7f5]">
                    <div class="impala-label">
                      Адрес выгрузки
                      <VMenu>
                        <button class="iconsax-menu-meatballs"></button>

                        <template #popper>
                          <div class="p-2 flex-col justify-center items-start gap-2.5 inline-flex">
                            <div class="impala-text-link " v-on:click="select_temp_var('TS_vygruzka',key)"><i
                                class="iconsax-add-square"></i> Добавить нового грузоотправителя/грузополучателя
                            </div>
                            <div class="impala-text-link impala-text-link:red"
                                 v-on:click="delete_adres(ad_vygruz_arr_temp,key,'AutoSelectComponent_vygruzka_edit','adres_vygruzki_show')">
                              <i
                                  class="iconsax-trash"></i> Удалить
                            </div>
                          </div>
                        </template>
                      </VMenu>
                    </div>
                    <auto-input-author-component inp_type='add_vygruzka_edit'
                                                 :adres_pogruzke_show="flag_pogruz"
                                                 :adres_pogruzke_show_edit="ad_vygruz_arr_temp[key]['adres_vygruzki_show']"
                                                 :order_id="order_id"
                                                 v-bind:gruzootpravitel_arr="gruzootpravitel_arr"
                                                 :key_in_arr="key"
                                                 @add_pogruzka_new="add_new_pogruzka_in_ts"
                                                 ref="AutoSelectComponent_vygruzka_edit"
                                                 :changePogrVygrAllShow="changePogrVygrAllShow"
                    ></auto-input-author-component>
                  </div>
                  <div class="px-2">
                    <div class="impala-add-link" v-on:click="add_empty_adres_vygruz()">Добавить адрес выгрузки</div>
                  </div>
                </template>
              </div>
            </div>
            <div v-if="elem.perevozchikiList.length !== 0 || add_ts===key"
                 class="self-stretch flex-col justify-start items-start gap-2.5 flex">
              <div v-if="add_ts!==key" v-for="(perevozchik, perevozchikKey) in elem.perevozchikiList"
                   class="impala-box:dashed self-stretch p-2 justify-start items-start gap-4 inline-flex">
                <div class="impala-row-input flex-1">
                  <div class="impala-label">Перевозчик:</div>
                  <div v-if="perevozchik['contacts'].length === 0" class="impala-add-link:none">
                    {{ perevozchik.perevozka.forma_id }} {{ perevozchik.perevozka.nazvanie }}
                  </div>
                  <VMenu v-else>
                    <button class="impala-add-link">{{ perevozchik.perevozka.forma_id }}
                      {{ perevozchik.perevozka.nazvanie }}
                    </button>

                    <template #popper v-if="!!perevozchik['contacts']">
                      <div class="p-2" v-for="(onePopUpPerevozchik, key1Perevozchik) in perevozchik['contacts']"
                           :key="key1Perevozchik">
                        {{ onePopUpPerevozchik['FIO'] }} {{ onePopUpPerevozchik['dolznost'] }}
                        {{ onePopUpPerevozchik['telefon'] }} {{ onePopUpPerevozchik['email'] }}
                      </div>
                    </template>
                  </VMenu>
                </div>
                <div class="impala-row-input flex-[0_1_fit-content]">
                  <div class="impala-label">ИНН</div>
                  <div class="impala-add-link:none"
                       v-html="perevozchik.perevozka.INN ? perevozchik.perevozka.INN : '---'"></div>
                </div>
                <div class="impala-row-input flex-[0_1_fit-content]">
                  <div class="impala-label">Код АТИ</div>
                  <div class="impala-add-link:none"
                       v-html="perevozchik.perevozka.kod_ATI ? perevozchik.perevozka.kod_ATI : '---'"></div>
                </div>
                <div class="impala-row-input flex-[0_1_fit-content]">
                  <div class="impala-label">Ставка с НДС</div>
                  <div class="impala-add-link:none"
                       v-html="perevozchik.stavka_NDS ? perevozchik.stavka_NDS : '---'"></div>
                </div>
                <div class="impala-row-input flex-[0_1_fit-content]">
                  <div class="impala-label">Ставка без НДС</div>
                  <div class="impala-add-link:none"
                       v-html="perevozchik.stavka_bez_NDS ? perevozchik.stavka_bez_NDS : '---'"></div>
                </div>
                <div class="impala-row-input flex-[0_1_fit-content]">
                  <div class="impala-label">Ставка за км</div>
                  <div class="impala-add-link:none"
                       v-html="perevozchik.stavka_za_km ? perevozchik.stavka_za_km : '---'"></div>
                </div>
              </div>
              <template v-else>
                <div v-for="(onePerevozchik, keyPerevozchik) in perevozchikiList"
                     class="impala-box:dashed self-stretch p-2 justify-start items-start gap-4 inline-flex"
                     :key="keyPerevozchik">
                  <div class="impala-row-input grow shrink basis-0 ">
                    <div class="impala-label">Перевозчик:
                      <VMenu>
                        <button class="iconsax-menu-meatballs"></button>

                        <template #popper>
                          <div class="p-2 flex-col justify-center items-start gap-2.5 inline-flex">
                            <div class="impala-text-link " v-on:click="newPerevozchik(keyPerevozchik)"><i
                                class="iconsax-add-square"></i> Добавить нового
                            </div>
                            <div class="impala-text-link " v-if="onePerevozchik.perevozchik_id"
                                 v-on:click="show_mod_edit_perevozchik(onePerevozchik.perevozchik_id,keyPerevozchik)"><i
                                class="iconsax-edit-1"></i> Редактировать
                            </div>
                            <div class="impala-text-link impala-text-link:red"
                                 v-on:click="deletePerevozchikFromOrder(onePerevozchik.id,keyPerevozchik)"><i
                                class="iconsax-trash"></i> Удалить
                            </div>
                          </div>
                        </template>
                      </VMenu>
                    </div>
                    <auto-input-perevozka-component
                        :order_id="order_id"
                        :vidTsFromParent="onePerevozchik.perevozka.nazvanie"
                        :elem1="keyPerevozchik"
                        @childReturnMethod="parentMethodFromAutoinputPerevozka"
                        @childCloseAutoInput="closeParentAutoInputPogruzka"
                        ref="AutoSelectComponent_vid_TS"
                    ></auto-input-perevozka-component>
                  </div>
                  <div class="impala-row-input w-min min-w-[125px]">
                    <div class="impala-label">ИНН:</div>
                    <input class="impala-input" v-model="onePerevozchik.perevozka.INN" disabled/>
                  </div>
                  <div class="impala-row-input w-min min-w-[125px]">
                    <div class="impala-label">Код АТИ:</div>
                    <input class="impala-input" v-model="onePerevozchik.perevozka.kod_ATI" disabled/>
                  </div>
                  <div class="impala-row-input w-min">
                    <div class="impala-label">Ставка с НДС:</div>
                    <input class="impala-input" v-model="onePerevozchik.stavka_NDS"
                           @input="updateStavkaPerevozchik('stavka_NDS',keyPerevozchik)"/>
                  </div>
                  <div class="impala-row-input w-min">
                    <div class="impala-label">Ставка без НДС:</div>
                    <input class="impala-input" v-model="onePerevozchik.stavka_bez_NDS"
                           @input="updateStavkaPerevozchik('stavka_bez_NDS',keyPerevozchik)"/>
                  </div>
                  <div class="impala-row-input w-min">
                    <div class="impala-label">Ставка за км:</div>
                    <input class="impala-input" v-model="onePerevozchik.stavka_za_km" disabled/>
                  </div>
                </div>
                <div class="px-2">
                  <div class="impala-add-link" v-on:click="addEmptyPerevozchik()">Добавить перевозчика</div>
                </div>
              </template>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import moment from 'moment'
import DatePicker from 'vue-datepicker-next';
import 'vue-datepicker-next/index.css';
import 'vue-datepicker-next/locale/ru';

import 'floating-vue/dist/style.css';
import {useFio} from "../composables/fio";

export default {
  props: ['auth_user'],
  components: {
    DatePicker
  },
  data() {
    return {
      show: false,
      order_id: '',
      vid_perevozki: '',
      vid_perevozki_name: '',
      nomenklatura: '',
      gotovyi_raschet: '',
      files: [],
      checked1: '',
      alert_message: [],
      data_vneseniya: '',
      rasschitat_do: '',
      logist: '',
      nomer_zayavki: '',
      new_nomer_zayavki: '',
      kompaniya_zakazchik_name: '',
      kompaniya_zakazchik_id: '',
      managerZakazchik_name: '',
      managerZakazchik_id: '',
      menedzer_zakazchik: '',
      ISD: '',
      cena_kontrakta: '',
      data_kontrakta: '',
      dob_oplatu: false,
      oplata_arr: [],
      adres_pogruzke: '',
      adres_pogruzke_show: '',
      adres_vygruski: '',
      adres_vygruski_show: '',
      data_pogruzki: '',
      data_dostavki: '',
      gruzomesta_big: '',
      // gruzomesta_small:'',
      rasstojanie: '',
      ob_ves: '',
      ob_ob: '',
      komment_1: '',
      // ob_budjet:0,
      add_ts: '',
      add_new_ts: false,
      vid_TS: '',
      stavka_TS: '',
      stavka_TS_bez_NDS: '',
      // stavka_TS_za_km:'',
      stavka_kp_TS: '',
      // marja_TS:'',
      kol_gruz_TS: '',
      kol_TS_TS: '',
      rasstojanie_TS: '',
      adres_pogruzki_TS: '',
      ob_ves_TS: '',
      ob_ob_TS: '',
      adres_vygr_TS: '',
      kommentari_TS: '',
      checked2: '',
      terminal_TS: '',
      spisokTSarr: [],
      edit_flag: false,
      //НОМЕР В МАССИВЕ, ЭТО НЕ id редактируемого объекта
      edit_number: '',
      id_ts: '',
      ts_list_names: [],
      logist_list: true,
      logist_name: 'Логист не выбран',
      openDP: false,
      openDP1: false,
      openDP2: false,
      openDP3: false,
      openDP4: false,
      termList: [],
      terminal_act_list: [],
      ad_pogruzki_arr_temp: [],
      ad_vygruz_arr_temp: [],
      perevozka_arr: [],
      gruzootpravitel_arr: [],
      select_temp_pogr_or_vygr: '',
      select_temp_pogr_or_vygr_key: '',
      order_header_text: '',
      role: '',
      permissions: [],
      logist_list_full: [{value: 0, label: "Логист не выбран"}],
      utverzdenie_show_button: false,
      v_rabote_show_button: false,
      ocenka_show_button: false,
      flag_pogruz: '',
      status: 'Черновик',
      vidTsNazavanie: '',
      adresPogruzkiShowSpan: true,
      adresVygruzkiShowSpan: true,
      localFirstClick: false,
      headerTimeShow1: false,
      headerTimeShow2: false,
      headerTime1: '',
      headerTime2: '',
      nomerZaprosaShowInp: true,
      statusShowInp: true,
      vidPerevozkiShowInp: true,
      dataVneseniyaShowInp: true,
      rasschitatDoShowInp: true,
      dataPogruzkiShowInp: true,

      zakazchikShowInp: true,
      managerZakazchikShowInp: true,
      checkboxPogrVygr: false,
      pogruzkaShowInp: false,
      perevozchikId: '',
      perevozchikName: '',
      perevozchikiList: [],
      editNumberKeyPerevozchik: '',
      tooltipTarget: "tooltip-target",
      tooltipText: "Текст, который отображается при наведении курсора на элемент",
      tooltipContent: "Содержимое всплывающего окна",
      isVisiblePogrList: true
    }
  },
  mounted() {
    this.get_ts_list();

    console.log(this.ts_list_names)
  },
  created() {
    this.role = this.auth_user['role_perm']['role']

    this.permissions = this.auth_user['role_perm']['permissions']

    this.get_terminal_list(this.termList);
    this.get_perevozka_list(this.perevozka_arr)
    this.get_gruzootpravitel_list(this.gruzootpravitel_arr)
    this.get_logist_list(this.logist_list_full)
    for (let i = document.getElementsByClassName('mx-input-wrapper').length; i > 0; i--) {
      let m = i - 1
      document.getElementsByClassName('mx-input-wrapper')[m].remove();
    }
    //this.get_ts_list(this.ts_list_names);
    let adress = window.location.href;
    adress = (adress.split("/")[4])
    //если новый запрос
    if (adress == null) {
      this.data_vneseniya = new Date().toLocaleDateString('ru-RU');
      this.start_new_order();
      this.order_header_text = 'Создание запроса'
    }
    //если редактируем запрос
    else {
      this.order_id = adress;
      this.checkButtonsShow();
      this.start_get_old_order(adress, this.oplata_arr, this.spisokTSarr, this.perevozchikiList);
      this.update_unread_status()
      this.update_unread_status_v_rabote()
    }

  },

  computed: {
    stavka_TS_za_km: function () {
      if ((this.stavka_TS === '') || (this.rasstojanie_TS === '') || (this.stavka_TS == null) || (this.rasstojanie_TS == null)) {
        return 0
      } else {
        // return this.stavka_TS/this.rasstojanie_TS
        let st = this.stavka_TS / this.rasstojanie_TS
        return (parseInt(st * 100)) / 100
      }
    },

    marja_TS: function () {
      if (this.stavka_kp_TS === '' || this.stavka_TS === '') {
        return 0
      } else {
        return this.stavka_kp_TS - this.stavka_TS
      }
    },
    gruzomesta_small: function () {
      let gruzomesta = 0;
      for (var i = 0; i < this.spisokTSarr.length; i++) {
        gruzomesta = (Number(gruzomesta) + Number(this.spisokTSarr[i]['kol_gruz_TS']));
      }
      return this.gruzomesta_big - gruzomesta;
    },
    ob_budjet: function () {
      let budjet = 0;
      for (var i = 0; i < this.spisokTSarr.length; i++) {
        budjet = (Number(budjet) + (Number(this.spisokTSarr[i]['stavka_TS']) * Number(this.spisokTSarr[i]['kol_TS_TS'])));
      }
      return budjet;
    },
  },
  methods: {
    useFio,
    //проверка показывать ли этот блок текущему пользователю
    //role - id роли 1- admin 2- logist 3- manager и т.д
    //permission id прав, 1 - все права 2 - show и т.д.
    //на вход отдавать список id кто должен видеть блок

    checkRolePermission(users_permissions_list) {
      let permission = 2;

      //перебор юзеров
      let role = 0;
      let flag = false;
      for (var j = 0; j < users_permissions_list.length; j++) {
        role = users_permissions_list[j]
        if ((role === this.role) && ((this.permissions.includes(permission)) || (this.permissions.includes(1)))) {
          flag = true;
        }
      }
      return flag

    },
    test() {
      console.log(this.spisokTSarr)
    },
    pogruzkaShowInpChange(id) {
      this.perevozchikiList.forEach(obj => {
        if (obj.id === id) {
          obj.pogruzkaShowInp = !obj.pogruzkaShowInp; // Инвертируем значение
        }
      });
      console.log(this.perevozchikiList)
      // this.pogruzkaShowInp=!this.pogruzkaShowInp
    },
    updatePerevozchikField(orderPerevozchikId, fieldName, fieldValue) {
      axios
          .post('/updatePerevozchikField', {
            id: orderPerevozchikId,
            fieldName: fieldName,
            fieldValue: fieldValue,
          })

    },


    updateStavkaPerevozchikRasstojanie(field, data) {
      this.updateNumberToFormat(field, data)
      for (var i = 0; i < this.perevozchikiList.length; i++) {
        if ((this.perevozchikiList[i]['stavka_NDS'] == '') || (this.rasstojanie_TS == '') || (this.perevozchikiList[i]['stavka_NDS'] == null) || (this.rasstojanie_TS == null)) {
          this.perevozchikiList[i]['stavka_za_km'] = 0
        } else {
          let tempSt = this.perevozchikiList[i]['stavka_NDS'] / this.rasstojanie_TS
          this.perevozchikiList[i]['stavka_za_km'] = (parseInt(tempSt * 100)) / 100
        }
      }
    },
    updateStavkaPerevozchik(type, key) {
      if (type === 'stavka_NDS') {
        this.perevozchikiList[key]['stavka_NDS'] = this.setToNumberFormatData(this.perevozchikiList[key]['stavka_NDS'])

        if ((this.perevozchikiList[key]['stavka_NDS'] == '') || (this.perevozchikiList[key]['stavka_NDS'] == 0)) {
          this.perevozchikiList[key]['stavka_bez_NDS'] = 0
          this.perevozchikiList[key]['stavka_za_km'] = 0
        } else {
          this.perevozchikiList[key]['stavka_bez_NDS'] = (Number(this.perevozchikiList[key]['stavka_NDS']) / 1.2).toFixed(2);
          if ((this.perevozchikiList[key]['stavka_NDS'] == '') || (this.rasstojanie_TS == '') || (this.perevozchikiList[key]['stavka_NDS'] == null) || (this.rasstojanie_TS == null)) {
            this.perevozchikiList[key]['stavka_za_km'] = 0
          } else {
            let tempSt = this.perevozchikiList[key]['stavka_NDS'] / this.rasstojanie_TS
            this.perevozchikiList[key]['stavka_za_km'] = (parseInt(tempSt * 100)) / 100
          }
        }

      }
      if (type === 'stavka_bez_NDS') {
        this.perevozchikiList[key]['stavka_bez_NDS'] = this.setToNumberFormatData(this.perevozchikiList[key]['stavka_bez_NDS'])
        if ((this.perevozchikiList[key]['stavka_bez_NDS'] == '') || (this.perevozchikiList[key]['stavka_bez_NDS'] == 0)) {
          this.perevozchikiList[key]['stavka_NDS'] = 0
          this.perevozchikiList[key]['stavka_za_km'] = 0
        } else {
          this.perevozchikiList[key]['stavka_NDS'] = (Number(this.perevozchikiList[key]['stavka_bez_NDS']) * 1.2).toFixed(2);

          if ((this.perevozchikiList[key]['stavka_NDS'] == '') || (this.rasstojanie_TS == '') || (this.perevozchikiList[key]['stavka_NDS'] == null) || (this.rasstojanie_TS == null)) {
            this.perevozchikiList[key]['stavka_za_km'] = 0
          } else {
            let tempSt = this.perevozchikiList[key]['stavka_NDS'] / this.rasstojanie_TS
            this.perevozchikiList[key]['stavka_za_km'] = (parseInt(tempSt * 100)) / 100
          }
        }
      }
      //  this.updatePerevozchikField(this.perevozchikiList[key]['id'],'stavka_NDS',this.perevozchikiList[key]['stavka_NDS'])
      //  this.updatePerevozchikField(this.perevozchikiList[key]['id'],'stavka_bez_NDS',this.perevozchikiList[key]['stavka_bez_NDS'])

    },
    updateStavka() {
      this.stavka_TS = this.setToNumberFormat('stavka_TS_bez_NDS')
      if ((this.stavka_TS_bez_NDS === '') || (this.stavka_TS_bez_NDS === 0)) {
        this.stavka_TS = 0
      } else {
        this.stavka_TS = (Number(this.stavka_TS_bez_NDS) * 1.2).toFixed(2);
      }
    },
    updateStavkaBezNDS() {
      this.stavka_TS_bez_NDS = this.setToNumberFormat('stavka_TS')
      if ((this.stavka_TS === '') || (this.stavka_TS === 0)) {
        this.stavka_TS_bez_NDS = 0
      } else {
        this.stavka_TS_bez_NDS = (Number(this.stavka_TS) / 1.2).toFixed(2);
      }

    },
    nomerZaprosaShowInpChange() {
      this.nomerZaprosaShowInp = !this.nomerZaprosaShowInp
    },
    statusShowInpChange() {
      this.statusShowInp = !this.statusShowInp
    },
    vidPerevozkiShowInpChange() {
      this.vidPerevozkiShowInp = !this.vidPerevozkiShowInp
    },
    dataVneseniyaShowInpChange() {
      this.dataVneseniyaShowInp = !this.dataVneseniyaShowInp
    },
    rasschitatDoShowInpChange() {
      this.rasschitatDoShowInp = !this.rasschitatDoShowInp
    },
    dataPogruzkiShowInpChange() {
      this.dataPogruzkiShowInp = !this.dataPogruzkiShowInp
    },
    adresPogruzkiShowSpanChange() {
      this.adresPogruzkiShowSpan = !this.adresPogruzkiShowSpan
    },
    adresVygruzkiShowSpanChange() {
      this.adresVygruzkiShowSpan = !this.adresVygruzkiShowSpan
    },

    zakazchikShowInpChange() {
      this.zakazchikShowInp = !this.zakazchikShowInp
    },
    managerShowInpChange() {
      this.managerZakazchikShowInp = !this.managerZakazchikShowInp
    },
    showHeaderTime(numb) {
      if (numb === 1) {
        this.headerTimeShow1 = true
      }
      if (numb === 2) {
        this.headerTimeShow2 = true
      }
    },
    updateOrderNomerZaprosa() {
      axios
          .post('/updateOrderNomerZaprosa', {
            id: this.order_id,
            nomer_zayavki: this.new_nomer_zayavki
          })
          .then(response => {
            //если такая заявка уже была
            if (response.data.updated === 'false') {
              alert('Номер запроса уже существует')
              this.new_nomer_zayavki = this.nomer_zayavki
            } else {
              this.nomer_zayavki = this.new_nomer_zayavki
              this.order_header_text = 'Запрос номер: ' + this.nomer_zayavki + ' Дата внесения: '
            }

          })
    },
    parentMethodFromAutoinputPerevozka(data) {
      if (data.id !== null) {
        this.perevozchikiList[data.elem1].perevozchik_id = data.id
        this.perevozchikiList[data.elem1].perevozka.nazvanie = data.forma_id + ' ' + data.ts_name
        this.getPerevozchikData(data.id, data.elem1)
      }

    },
    closeParentAutoInputPogruzka(data) {
      this.perevozchikiList[data.elem1].pogruzkaShowInp = !this.perevozchikiList[data.elem1].pogruzkaShowInp
    },
    updateOrdersPerevozchik(perevozchik_id, key) {
      axios
          .post('/updateOrdersPerevozchik', {
            order_id: this.order_id,
            key: key,
            perevozchik_id: perevozchik_id,
          })
          .then(response => {
            if ((response.data.perevozchik[0].INN !== '') && (response.data.perevozchik[0].INN != null)) {
              //тут добавить строчку с новым параметром
              this.perevozchikiList[key].INN = response.data.perevozchik[0].INN
            }
            if ((response.data.perevozchik[0].kod_ATI !== '') && (response.data.perevozchik[0].kod_ATI != null)) {
              this.perevozchikiList[key].kod_ATI = response.data.perevozchik[0].kod_ATI
            } else {
              this.perevozchikiList[key].kod_ATI = ''
            }
          })
    },
    addEmptyPerevozchik() {
      //     axios
      //        .post('/addEmptyPerevozchik',{
      //            order_id:this.order_id,
      //        })
      //.then(response => {
      let objToPush = {};
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
        id: '',
        kod_ATI: '',
        kommentariy: '',
        nazvanie: '',
        perevozka_name: '',
        pochtovyi_adres: '',
        telefon: '',
        telefon_gen_dir: ''
      };
      objToPush['stavka_NDS'] = '';
      objToPush['stavka_bez_NDS'] = '';
      objToPush['pogruzkaShowInp'] = false;
      this.perevozchikiList.push(objToPush);
      // })
    },
    //сохранение перевозчика из модального окна
    gradeAddPerevozchik(id, nazvanie) {
      this.perevozchikiList[this.editNumberKeyPerevozchik].perevozchik_id = id
      this.getPerevozchikDataEditOrNew(id, this.editNumberKeyPerevozchik)
    },
    getPerevozchikDataEditOrNew(id, key) {
      axios
          .post('/getPerevozchikData', {
            id: id,
          })
          .then(response => {
            var elements = document.getElementsByClassName('perClassScript');

            for (let i = 0; i < this.perevozchikiList.length; i++) {
              if (this.perevozchikiList[i].perevozchik_id == id) {
                this.perevozchikiList[i].contacts = response.data.perevozchik[0].contacts
                this.perevozchikiList[i].perevozka.INN = response.data.perevozchik[0].INN
                this.perevozchikiList[i].perevozka.kod_ATI = response.data.perevozchik[0].kod_ATI
                this.perevozchikiList[i].perevozka.nazvanie = response.data.perevozchik[0].nazvanie
                elements[i].value = response.data.perevozchik[0].nazvanie
              }
            }
            for (let i = 0; i < this.spisokTSarr.length; i++) {
              for (let j = 0; j < this.spisokTSarr[i].perevozchikiList.length; j++) {
                if (this.spisokTSarr[i].perevozchikiList[j].perevozchik_id == id) {
                  this.spisokTSarr[i].perevozchikiList[j].contacts = response.data.perevozchik[0].contacts
                  this.spisokTSarr[i].perevozchikiList[j].perevozka.INN = response.data.perevozchik[0].INN
                  this.spisokTSarr[i].perevozchikiList[j].perevozka.kod_ATI = response.data.perevozchik[0].kod_ATI
                  this.spisokTSarr[i].perevozchikiList[j].perevozka.nazvanie = response.data.perevozchik[0].nazvanie
                }
              }
            }
          })
    },
    getPerevozchikData(id, key) {
      axios
          .post('/getPerevozchikData', {
            id: id,
          })
          .then(response => {
            for (let i = 0; i < this.perevozchikiList.length; i++) {
              if (this.perevozchikiList[i].perevozchik_id == id) {
                this.perevozchikiList[i].contacts = response.data.perevozchik[0].contacts
                this.perevozchikiList[i].perevozka.INN = response.data.perevozchik[0].INN
                this.perevozchikiList[i].perevozka.kod_ATI = response.data.perevozchik[0].kod_ATI
                this.perevozchikiList[i].perevozka.nazvanie = response.data.perevozchik[0].nazvanie
                this.perevozchikiList[i].perevozka.forma_id = response.data.perevozchik[0].forma_id
              }
            }

            //  this.perevozchikiList[key].contacts=response.data.perevozchik[0].contacts
          })
    },
    //метод изменяющий название погрузок или выгрузок на всей странице или удаляющий их и в левой колонке и в списке ТС
    chahgeFrontNames() {
      //получим все id нужных нам адресов ( адрес погрузки, выгрузки, и в списке ТС тоже самое ) и сформируем массив
      let findAdresa = [];
      findAdresa.push(this.adres_pogruzke);
      findAdresa.push(this.adres_vygruski);
      //делаем запрос
      //обновляем

      for (var i = 0; i < this.spisokTSarr.length; i++) {
        for (var j = 0; j < this.spisokTSarr[i]['adres_pogruzki_TS'].length; j++) {
          findAdresa.push(this.spisokTSarr[i]['adres_pogruzki_TS'][j]['adres_pogruzki']);
        }
        for (var j = 0; j < this.spisokTSarr[i]['adres_vygr_TS'].length; j++) {
          findAdresa.push(this.spisokTSarr[i]['adres_vygr_TS'][j]['adres_pogruzki']);
        }
      }

      axios
          .post('/chahgeFrontNames', {
            findAdresa: findAdresa
          })
          .then(response => {
            this.changeFrontNames(response.data.gruzColect)
          })
    },
    changeFrontNames(gruzColect) {

      //установим мордашку у адреса погрузки
      let nazv = this.issetFrontNames(gruzColect, this.adres_pogruzke)
      if (nazv) {
        this.adres_pogruzke_show = nazv
      } else {
        this.adres_pogruzke_show = ''
        this.adres_pogruzki = '' // Если название пустое, очищаем id адреса
      }
      //установим мордашку у адреса выгрузки
      nazv = this.issetFrontNames(gruzColect, this.adres_vygruski)
      if (nazv) {
        this.adres_vygruski_show = nazv
      } else {
        this.adres_vygruski_show = ''
        this.adres_pogruzki = '' // Если название пустое, очищаем id адреса
      }

      for (var i = 0; i < this.spisokTSarr.length; i++) {
        for (var j = 0; j < this.spisokTSarr[i]['adres_pogruzki_TS'].length; j++) {
          nazv = this.issetFrontNames(gruzColect, this.spisokTSarr[i]['adres_pogruzki_TS'][j]['adres_pogruzki'])
          if (nazv) {
            this.spisokTSarr[i]['adres_pogruzki_TS'][j]['adres_pogruzke_show'] = nazv
          } else {
            this.spisokTSarr[i]['adres_pogruzki_TS'][j]['adres_pogruzke_show'] = ''
            this.spisokTSarr[i]['adres_pogruzki_TS'][j]['adres_pogruzki'] = '' // Если название пустое, очищаем id адреса
          }
        }
        for (var j = 0; j < this.spisokTSarr[i]['adres_vygr_TS'].length; j++) {
          nazv = this.issetFrontNames(gruzColect, this.spisokTSarr[i]['adres_vygr_TS'][j]['adres_pogruzki'])
          if (nazv) {
            this.spisokTSarr[i]['adres_vygr_TS'][j]['adres_vygruzki_show'] = nazv
          } else {
            this.spisokTSarr[i]['adres_vygr_TS'][j]['adres_vygruzki_show'] = ''
            this.spisokTSarr[i]['adres_vygr_TS'][j]['adres_pogruzki'] = '' // Если название пустое, очищаем id адреса
          }
        }
      }
    },
    issetFrontNames(gruzColect, targetId) {
      const foundObject = gruzColect.find(obj => obj.id == targetId);

      if (foundObject) {
        return foundObject.nazvanie;
      } else {
        return false;
      }
    },
    changePogrVygrAllShow(id) {
      this.$refs.modalComponentforAction.setNewModalStatusFalse()
      this.$refs.modalComponentforAction.get_modal_edit_data(id)
    },
    //добавление пустой погрузки или выгрузки
    add_new_pogruzka_in_ts(data) {
      //если погрузка
      if (data.pogr_or_vygr === 'pogruzka') {
        this.ad_pogruzki_arr_temp[data.key]['adres_pogruzki'] = data.inp_pog_id;
        this.ad_pogruzki_arr_temp[data.key]['adres_pogruzke_show'] = data.nazvanie;
      }
      //если выгрузка
      if (data.pogr_or_vygr === 'vygruzka') {
        this.ad_vygruz_arr_temp[data.key]['adres_pogruzki'] = data.inp_pog_id;
        this.ad_vygruz_arr_temp[data.key]['adres_vygruzki_show'] = data.nazvanie;
      }

    },
    //если редактируешь тс
    parentMethodFromAutoinput(data) {
      this.vid_TS = data.vid_TS
    },
    //если добавляешь новое тс
    parentMethodFromAutoinputNewVidts(data) {

      this.vid_TS = data.vid_TS
    },
    //проверка какие кнопки показывать пользователям
    checkButtonsShow(list) {
      axios
          .post('/check_buttons_show', {
            id: this.order_id,
            user_id: this.auth_user['id'],
            role: this.role
          })
          .then(({data}) => (
                  this.ocenka_show_button = data.ocenkaShowButton,
                      this.utverzdenie_show_button = data.utverzdenieShowButton,
                      this.v_rabote_show_button = data.vRaboteShowButton
              )
          );
    },
    hideButton(buttonName) {
      if (buttonName === 'ocenka') {
        this.ocenka_show_button = false
        if (this.spisokTSarr.length !== 0) {
          this.utverzdenie_show_button = true
          this.v_rabote_show_button = true
        }

      }
      if (buttonName === 'naznachenie_stavki') {
        this.utverzdenie_show_button = false
        this.ocenka_show_button = true
        if (this.spisokTSarr.length !== 0) {
          this.v_rabote_show_button = true
        }
      }
      if (buttonName === 'v_rabote') {
        this.v_rabote_show_button = false
        if (this.spisokTSarr.length !== 0) {
          this.utverzdenie_show_button = true
        }
        this.ocenka_show_button = true
      }
    },
    update_order_oplata(summ_opl, id, data) {
      axios
          .post('/update_order_oplata', {
            id: id,
            summ_opl: summ_opl,
            data: data,
          })

    },
    async deletePerevozchikFromOrder(id, key) {
      const result = await this.confirmMethodMixin();
      if (result) {
        if (id !== null) {
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
                for (let i = 0; i < elements.length; i++) {
                  if (i !== key) {
                    if (key >= i) {
                      elementsNew[i].value = elements[i].value

                    } else {
                      elementsNew[i - 1].value = elements[i].value

                    }
                  }
                }
              })
        } else {
          this.perevozchikiList.splice(key, 1)
        }

      }
    },

    async delete_oplata_summa(id, key) {
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
    get_logist_list(inp) {
      axios
          .post('/get_logist_list', {})
          .then(({data}) => (
                  data.list_users.forEach(function (entry) {
                    inp.push({
                      id: entry.id,
                      full_logist_name: entry.last_name + ' ' + entry.first_name + ' ' + entry.patronymic,
                      value: entry.id,
                      label: useFio(entry.last_name, entry.first_name, entry.patronymic),
                      label_full: entry.last_name + ' ' + entry.first_name + ' ' + entry.patronymic
                    });
                  })
              )
          );
    },
    get_perevozka_list(inp) {
      axios
          .post('/getVidPerevozki', {})
          .then(({data}) => (
                  data.perevozka_list.forEach(function (entry) {
                    inp.push({
                      id: entry.id,
                      perevozka_name: entry.perevozka_name,
                      value: entry.id,
                      label: entry.perevozka_name
                    });
                  })
              )
          );
    },
    get_gruzootpravitel_list() {
      let inp_temp = [];
      axios
          .post('/getGruzWithName', {})
          .then(({data}) => (
                  data.gruzootpravitel.forEach(function (entry) {
                    inp_temp.push({
                      id: entry.id,
                      nazvanie: entry.nazvanie
                    });
                  })
              ),
              this.gruzootpravitel_arr = inp_temp,
          );
    },
    show_mod_edit(id) {
      //вызов метода дочернего компонента( модального окна )
      this.$refs.modalComponentforAction.setNewModalStatusFalse()
      this.$refs.modalComponentforAction.get_modal_edit_data(id)
    },
    //работа из модального окна добавления грузоотправителя
    //метод выборки, адрес погрузки или адрес выгрузки поменяется динамически
    select_temp_var(pogr_or_vygr, key) {
      if (pogr_or_vygr === 'manager') {
        this.show_mod_edit(this.kompaniya_zakazchik_id)
      } else {
        this.newModal()
      }

      //погрузка 1 - pogruzka
      //выгрузка 1 - vygruzka
      //список ТС редактирование тс, погрузка - TS_pogruzka
      //список ТС редактирование тс, выгрузка - TS_vygruzka
      //список ТС новое тс, погрузка - TS_pogruzka_new
      //список ТС новое тс, выгрузка - TS_vygruzka_new
      this.select_temp_pogr_or_vygr = pogr_or_vygr
      this.select_temp_pogr_or_vygr_key = key
    },
    select_Manager(kontakty) {
      if (this.select_temp_pogr_or_vygr === 'manager') {
        console.log(kontakty)
      }

    },
    newPerevozchik(key) {
      //в этой переменной храним ключ редактируемого перевозчика
      this.editNumberKeyPerevozchik = key
      //вызов метода дочернего компонента( модального окна )
      this.$refs.modalComponentforActionPerevozchik.newPerevozchik()
    },
    select_gruzootpravitel() {
      if (this.select_temp_pogr_or_vygr === 'pogruzka') {
        axios
            .post('/select_gruzootpravitel', {})
            .then(({data}) => (
                    this.adres_pogruzke = data.gruzootpravitel.id,
                        this.adres_pogruzke_show = data.gruzootpravitel.nazvanie,
                        this.$refs.AutoSelectComponent.up_gruzoot_from_select(this.adres_pogruzke, 'adres_pogruzke')
                )
            );
      }
      if (this.select_temp_pogr_or_vygr === 'vygruzka') {
        axios
            .post('/select_gruzootpravitel', {})
            .then(({data}) => (
                    this.adres_vygruski = data.gruzootpravitel.id,
                        this.adres_vygruski_show = data.gruzootpravitel.nazvanie,
                        this.$refs.AutoSelectComponent.up_gruzoot_from_select(this.adres_vygruski, 'adres_vygruski')
                )
            );
      }

      //если редактируем погрузку
      if (this.select_temp_pogr_or_vygr === 'TS_pogruzka') {
        axios
            .post('/select_gruzootpravitel', {})
            .then(({data}) => (
                    //сохраняем во временный массив
                    this.ad_pogruzki_arr_temp[this.select_temp_pogr_or_vygr_key]['adres_pogruzki'] = data.gruzootpravitel.id,
                        //сохраняем название во временный массив
                        this.ad_pogruzki_arr_temp[this.select_temp_pogr_or_vygr_key]['adres_pogruzke_show'] = data.gruzootpravitel.nazvanie,
                        //передаём в селект название и отображаем там
                        this.$refs.AutoSelectComponent_pogruzka_edit[this.select_temp_pogr_or_vygr_key].up_gruz_from_modal(data.gruzootpravitel.nazvanie)
                )
            );
      }
      //если редактируем выгрузку
      if (this.select_temp_pogr_or_vygr === 'TS_vygruzka') {
        axios
            .post('/select_gruzootpravitel', {})
            .then(({data}) => (
                    //сохраняем во временный массив
                    this.ad_vygruz_arr_temp[this.select_temp_pogr_or_vygr_key]['adres_pogruzki'] = data.gruzootpravitel.id,
                        //сохраняем название во временный массив
                        this.ad_vygruz_arr_temp[this.select_temp_pogr_or_vygr_key]['adres_vygruzki_show'] = data.gruzootpravitel.nazvanie,
                        //передаём в селект название и отображаем там
                        this.$refs.AutoSelectComponent_vygruzka_edit[this.select_temp_pogr_or_vygr_key].up_gruz_from_modal(data.gruzootpravitel.nazvanie)
                )
            );
      }
      //если добавляем новую погрузку
      if (this.select_temp_pogr_or_vygr === 'TS_pogruzka_new') {
        axios
            .post('/select_gruzootpravitel', {})
            .then(({data}) => (
                    //сохраняем во временный массив
                    this.ad_pogruzki_arr_temp[this.select_temp_pogr_or_vygr_key]['adres_pogruzki'] = data.gruzootpravitel.id,
                        //сохраняем название во временный массив
                        this.ad_pogruzki_arr_temp[this.select_temp_pogr_or_vygr_key]['adres_pogruzke_show'] = data.gruzootpravitel.nazvanie,
                        //передаём в селект название и отображаем там
                        this.$refs.AutoSelectComponent_pogruzka_empty[this.select_temp_pogr_or_vygr_key].up_gruz_from_modal(data.gruzootpravitel.nazvanie)
                )
            );
      }
      //если добавляем новую выгрузку
      if (this.select_temp_pogr_or_vygr === 'TS_vygruzka_new') {
        axios
            .post('/select_gruzootpravitel', {})
            .then(({data}) => (
                    //сохраняем во временный массив
                    this.ad_vygruz_arr_temp[this.select_temp_pogr_or_vygr_key]['adres_pogruzki'] = data.gruzootpravitel.id,
                        //сохраняем название во временный массив
                        this.ad_vygruz_arr_temp[this.select_temp_pogr_or_vygr_key]['adres_vygruzki_show'] = data.gruzootpravitel.nazvanie,
                        //передаём в селект название и отображаем там
                        this.$refs.AutoSelectComponent_vygruzka_empty[this.select_temp_pogr_or_vygr_key].up_gruz_from_modal(data.gruzootpravitel.nazvanie)
                )
            );
      }

    },
    get_terminal_list(inp) {
      axios
          .post('/get_terminal_list', {})
          .then(({data}) => (
                  data.terminal.forEach(function (entry) {
                    inp.push({
                      id: entry.id,
                      name: entry.ts_name
                    });
                  })
              )
          );
    },

    handleChange0() {
      this.updateOrderLoc('data_pogruzki', this.data_pogruzki);
    },
    handleChange1() {
      this.update_order()
    },
    openDB0() {
      this.openDP = true
    },
    openDB1() {
      this.openDP1 = true;
    },
    openDB2() {
      this.openDP2 = true
    },
    openDB3() {
      this.openDP3 = true
    },
    openDB4() {
      this.openDP4 = true
    },
    handleChange(label) {
      this.logist_name = label;
      this.logist_show();
      this.update_order_logist()
      if ((this.status === 'Черновик') && (label !== 'Логист не выбран')) {
        this.status = 'На оценке'
      }
    },
    logist_update() {
      this.logist_list = !this.logist_list
    },
    setStatusName(columnName) {
      if (columnName === 'ocenka') {
        this.status = 'На оценке'
      }
      if (columnName === 'naznachenie_stavki') {
        this.status = 'На утверждении'
      }
      if (columnName === 'v_rabote') {
        this.status = 'В работе'
      }
    },
    logist_show() {
      this.logist_list = !this.logist_list
    },
    get_ts_list() {
      axios
          .post('/get_ts_list', {})
          .then(response => (
                  this.ts_list_names.push(...(response.data.ts || []))
              )
          );
    },
    update_unread_status() {
      if (this.role === 2) {
        axios
            .post('/update_unread_status', {
              logist_id: this.auth_user['id'],
              id: this.order_id,
              column_name: 'ocenka',
            })
      }
      if (this.role === 1) {
        axios
            .post('/update_unread_status', {
              logist_id: this.auth_user['id'],
              id: this.order_id,
              column_name: 'naznachenie_stavki',
            })
      }
    },
    update_unread_status_v_rabote() {
      if (this.role === 2) {
        axios
            .post('/update_unread_status_v_rabote', {
              logist_id: this.auth_user['id'],
              id: this.order_id,
              column_name: 'v_rabote',
            })
      }
    },
    update_order_logist() {
      axios
          .post('/update_order_logist', {
            logist: this.logist,
            id: this.order_id
          })
      // .then(({ data }) => (
      //     this.update_order()
      // ))
    },
    setColumn(columnName) {
      axios
          .post('/orderChangeColumn', {
            columnName: columnName,
            id: this.order_id
          })
          .then(response => {
            if ((this.role == 2) && (columnName == 'naznachenie_stavki')) {
              //редиректим логиста на главную перед этим говорим  отобразить оценку
              localStorage.setItem('logist_ut_flag', '1')
              window.location.href = ('/')
            }
            //если утвердил админ
            if (this.role == 1) {
              this.hideButton(columnName)
              let message = this.setMessage(columnName)
              alert(message)
            }

          })
      this.setStatusName(columnName)
    },
    setMessage(columnName) {
      let message = ''
      if (columnName === 'ocenka') {
        message = 'Запрос отправлен на оценку'
      }
      if (columnName === 'naznachenie_stavki') {
        message = 'Запрос отправлен на назначение ставки'
      }
      if (columnName === 'v_rabote') {
        message = 'Запрос отправлен в работу'
      }
      return message;
    },
    newModal() {
      this.$refs.modalComponentforAction.newModal()
    },
    //метод применяемый для числовых значений с кореектировокой ввода
    updateOrderLocNumber(field, data) {
      data = this.setToNumberFormat(field)
      if ((field === 'adres_pogruzke') || (field === 'adres_vygruski')) {
        this.adresPogruzkiShowSpan = true
        this.adresVygruzkiShowSpan = true
      }
      axios
          .post('/updateOrderLoc', {
            id: this.order_id,
            field: field,
            data: data
          })
          .then(response => {
            this.toast("Данные обновлены", "success");
          })
    },
    //метод для всех остальных значений кроме числовых
    updateOrderLoc(field, data, label = '') {
      if (label !== '') {
        this[`${field}_name`] = label;
      }

      if ((field === 'adres_pogruzke') || (field === 'adres_vygruski')) {
        this.adresPogruzkiShowSpan = true
        this.adresVygruzkiShowSpan = true
      }
      axios
          .post('/updateOrderLoc', {
            id: this.order_id,
            field: field,
            data: data
          })
          .then(response => {
            this.toast("Данные обновлены", "success");
          })
    },
    update_order() {
      axios
          .post('/update_order', {
            id: this.order_id,
            data_vneseniya: this.data_vneseniya,
            rasschitat_do: this.rasschitat_do,
            nomenklatura: this.nomenklatura,
            kompaniya_zakazchik_name: this.kompaniya_zakazchik_name,
            kompaniya_zakazchik_id: this.kompaniya_zakazchik,
            managerZakazchik_id: this.menedzer_zakazchik,
            ISD: this.ISD,
            cena_kontrakta: this.cena_kontrakta,
            data_kontrakta: this.data_kontrakta,
            // adres_pogruzke:this.adres_pogruzke,
            data_pogruzki: this.data_pogruzki,
            data_dostavki: this.data_dostavki,
            adres_vygruski: this.adres_vygruski,
            komment_1: this.komment_1,
            logist: this.logist,
            gruzomesta_big: this.gruzomesta_big,
            gruzomesta_small: this.gruzomesta_small,
            rasstojanie: this.rasstojanie,
            ob_ves: this.ob_ves,
            ob_ob: this.ob_ob,
            vid_perevozki: this.vid_perevozki,
            oplata_arr: this.oplata_arr,
            start_flag: false,
            timeVneseniya: this.headerTime1,
            timeRasscheta: this.headerTime2
          })
    },
    start_get_old_order(adress, inp, TSinp, perevozchikiList) {
      axios
          .post('/start_get_old_order', {
            id: adress
          })
          .then(({data}) => (
                  this.data_vneseniya = data.data[0]['data_vneseniya'],
                      this.headerTime1 = data.data[0]['timeVneseniya'],
                      this.headerTime2 = data.data[0]['timeRasscheta'],
                      this.rasschitat_do = data.data[0]['rasschitat_do'],
                      this.nomenklatura = data.data[0]['nomenklatura'],
                      this.gotovyi_raschet = data.data[0]['gotovyi_raschet'],
                      this.nomer_zayavki = data.data[0]['nomer_zayavki'],
                      this.new_nomer_zayavki = data.data[0]['nomer_zayavki'],
                      this.kompaniya_zakazchik_id = data.data[0]['kompaniya_zakazchik'],
                      this.kompaniya_zakazchik_name = data.data[0]['kompaniya_zakazchik_name'],
                      this.managerZakazchik_name = data.data[0]['menedzer_zakazchik_name'],
                      this.managerZakazchik_id = data.data[0]['menedzer_zakazchik'],
                      this.ISD = data.data[0]['ISD'],
                      this.cena_kontrakta = data.data[0]['cena_kontrakta'],
                      this.data_kontrakta = data.data[0]['data_kontrakta'],
                      this.adres_pogruzke = data.data[0]['adres_pogruzke'],
                      this.adres_vygruski = data.data[0]['adres_vygruski'],
                      this.data_pogruzki = data.data[0]['data_pogruzki'],
                      this.data_dostavki = data.data[0]['data_dostavki'],
                      this.komment_1 = data.data[0]['komment_1'],
                      this.logist = data.data[0]['logist'],
                      this.logist_name = useFio(data.data[0]['logist_name']['last_name'], data.data[0]['logist_name']['first_name'], data.data[0]['logist_name']['patronymic']),
                      this.gruzomesta_big = data.data[0]['gruzomesta_big'],
                      this.rasstojanie = data.data[0]['rasstojanie'],
                      this.ob_ves = data.data[0]['ob_ves'],
                      this.ob_ob = data.data[0]['ob_ob'],
                      this.vid_perevozki = data.data[0]['vid_perevozki'],
                      this.vid_perevozki_name = data.data[0]['vid_perevozki_name'],
                      this.status = data.data[0]['status'],
                      this.perevozchikId = data.data[0]['perevozchik'],
                      this.perevozchikName = data.data[0]['nazvanie'],
                      data.data[0].oplata.forEach(function (entry) {
                        inp.push(entry);
                      }),
                      data.TS_list.forEach(function (entry) {
                        TSinp.push({
                          id_ts: entry.id_ts,
                          order_id: entry.order_id,
                          vid_TS: entry.vid_TS,
                          stavka_TS: entry.stavka_TS,
                          stavka_TS_bez_NDS: entry.stavka_TS_bez_NDS,
                          stavka_TS_za_km: entry.stavka_TS_za_km,
                          stavka_kp_TS: entry.stavka_kp_TS,
                          marja_TS: entry.marja_TS,
                          kol_gruz_TS: entry.kol_gruz_TS,
                          kol_TS_TS: entry.kol_TS_TS,
                          rasstojanie_TS: entry.rasstojanie_TS,
                          ob_ves_TS: entry.ob_ves_TS,
                          ob_ob_TS: entry.ob_ob_TS,
                          adres_pogruzki_TS: entry.adres_pogruzki_TS,
                          adres_vygr_TS: entry.adres_vygr_TS,
                          kommentari_TS: entry.kommentari_TS,
                          checked2: entry.checked2,
                          terminal_TS: entry.terminal_TS,
                          perevozchikiList: entry.perevozchiki,
                        });
                      }),
                      this.order_header_text = 'Запрос номер: ' + this.nomer_zayavki + ' Дата внесения: '
              )
          )

    },
    parentMethodFromAutoinputZakazchik(data) {
      if (this.kompaniya_zakazchik_id !== data.id) {
        //обнуляем менеджера
        this.managerZakazchik_name = ''
        this.managerZakazchik_id = ''
        this.updateOrderLoc('menedzer_zakazchik', '')
      }

      this.kompaniya_zakazchik_name = data.ts_name
      this.kompaniya_zakazchik_id = data.id
      this.zakazchikShowInp = true
      this.updateOrderLoc('kompaniya_zakazchik', data.id)

    },
    parentMethodFromAutoinputManager(data) {
      this.managerZakazchik_name = data.ts_name
      this.managerZakazchik_id = data.id
      this.managerZakazchikShowInp = true
      this.updateOrderLoc('menedzer_zakazchik', data.id)

    },
    closeParentAutoInput(data) {
      this.zakazchikShowInp = false
    },
    closeParentAutoInputManager(data) {
      this.managerZakazchikShowInp = false
    },
    //если новая запрос
    start_new_order() {
      axios
          .post('/start_new_order', {
            data_vneseniya: new Date().toLocaleDateString('ru-RU')
          })
          .then(({data}) => (
                  this.order_id = data.data['id'],
                      this.checkButtonsShow()
              )
          )
    },
    add_to_work() {
      this.v_rabote_show_button = false;
      axios
          .post('/add_to_work', {
            order_id: this.order_id
          })
          .then(response => {
            alert('запрос добавлена в работу')
          })
    },
    go_to_grade() {
      // window.location.href =('/grade/'+this.order_id)
      //отправляем запрос на вкладку назначение ставки
      this.utverzdenie_show_button = false;
      axios
          .post('/add_to_naznachenie_stavki', {
            order_id: this.order_id
          })
          .then(response => {
            if (this.role == 2) {
              //редиректим логиста на главную перед этим говорим  отобразить оценку
              localStorage.setItem('logist_ut_flag', '1')
              window.location.href = ('/')
            }
            //если утвердил админ
            if (this.role == 1) {
              alert('запрос добавлен в назначение ставки')
            }

          })
    },
    showButtons() {
      if (this.status !== 'Назначение ставки') {
        this.utverzdenie_show_button = true
      }
      if (this.status !== 'На утверждении') {
        this.utverzdenie_show_button = true
      }

      if (this.role == 1) {
        if (this.status !== 'На оценке') {
          this.ocenka_show_button = true
        }
        if (this.status !== 'В работе') {
          this.v_rabote_show_button = true
        }

      }

    },
    show_mod_edit_perevozchik(id, key) {
      //в этой переменной храним ключ редактируемого перевозчика
      this.editNumberKeyPerevozchik = key
      //вызов метода дочернего компонента( модального окна )
      this.$refs.modalComponentforActionPerevozchik.get_modal_edit_data(id)
    },
    hideButtonsUsers() {
      this.v_rabote_show_button = false
      this.utverzdenie_show_button = false
    },
    save_TS() {
      this.checkboxPogrVygr = false
      //показываем кнопки
      this.showButtons()
      if (this.edit_flag === false) {
        //проверка, были ли вообще хоть одно ТС
        if (this.spisokTSarr.length === 0) {
          this.id_ts = 0;
        } else {
          let last_id_ts = this.spisokTSarr.slice(-1);
          this.id_ts = Number(last_id_ts[0]['id_ts']) - Number(1);
        }
        let objToPush = {};
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

        if (this.spisokTSarr.length === 0) {
          this.spisokTSarr.push(objToPush);
        } else {
          Object.assign(this.spisokTSarr[this.id_ts], objToPush);
        }

//сохраняем новое тс
        axios
            .post('/save_ts', {
              id_ts: this.id_ts,
              order_id: this.order_id,
              vid_TS: this.vid_TS,
              stavka_TS: this.stavka_TS,
              stavka_TS_bez_NDS: this.stavka_TS_bez_NDS,
              stavka_TS_za_km: this.stavka_TS_za_km,
              stavka_kp_TS: this.stavka_kp_TS,
              marja_TS: this.marja_TS,
              kol_gruz_TS: this.kol_gruz_TS,
              kol_TS_TS: this.kol_TS_TS,
              rasstojanie_TS: this.rasstojanie_TS,
              adres_pogruzki_TS: this.ad_pogruzki_arr_temp,
              ob_ves_TS: this.ob_ves_TS,
              ob_ob_TS: this.ob_ob_TS,
              adres_vygr_TS: this.ad_vygruz_arr_temp,
              kommentari_TS: this.kommentari_TS,
              checked2: this.checked2,
              terminal_TS: this.terminal_TS,
              perevozchikiList: this.perevozchikiList
            })
            .then(response => {
              this.toast("ТС добавлен", "success");
            })

        this.vid_TS = '';
        this.id_ts = '';
        this.stavka_TS = '';
        this.stavka_TS_bez_NDS = '';
        this.stavka_kp_TS = '';
        this.kol_gruz_TS = '';
        this.kol_TS_TS = '';
        this.rasstojanie_TS = '';
        this.ad_pogruzki_arr_temp = [];
        this.ad_vygruz_arr_temp = [];
        this.ob_ves_TS = '';
        this.ob_ob_TS = '';
        this.adres_vygr_TS = '';
        this.kommentari_TS = '';
        this.checked2 = '';
        this.terminal_TS = '';
        this.add_ts = '';
        this.add_new_ts = false;
        this.perevozchikiList = ''
      }
      //редактируем существующее ТС
      else {
        this.spisokTSarr[this.edit_number]['vid_TS'] = this.vid_TS;
        this.spisokTSarr[this.edit_number]['stavka_TS'] = this.stavka_TS;
        this.spisokTSarr[this.edit_number]['stavka_TS_bez_NDS'] = this.stavka_TS_bez_NDS;
        this.spisokTSarr[this.edit_number]['stavka_kp_TS'] = this.stavka_kp_TS;
        this.spisokTSarr[this.edit_number]['kol_gruz_TS'] = this.kol_gruz_TS;
        this.spisokTSarr[this.edit_number]['kol_TS_TS'] = this.kol_TS_TS;
        this.spisokTSarr[this.edit_number]['rasstojanie_TS'] = this.rasstojanie_TS;
        this.spisokTSarr[this.edit_number]['adres_pogruzki_TS'] = this.ad_pogruzki_arr_temp;
        this.spisokTSarr[this.edit_number]['ob_ves_TS'] = this.ob_ves_TS;
        this.spisokTSarr[this.edit_number]['ob_ob_TS'] = this.ob_ob_TS;
        this.spisokTSarr[this.edit_number]['adres_vygr_TS'] = this.ad_vygruz_arr_temp;
        this.spisokTSarr[this.edit_number]['kommentari_TS'] = this.kommentari_TS;
        this.spisokTSarr[this.edit_number]['checked2'] = this.checked2;
        this.spisokTSarr[this.edit_number]['terminal_TS'] = this.terminal_TS;
        this.spisokTSarr[this.edit_number]['perevozchikiList'] = this.perevozchikiList;
        this.spisokTSarr[this.edit_number]['marja_TS'] = this.marja_TS;
        this.spisokTSarr[this.edit_number]['stavka_TS_za_km'] = this.stavka_TS_za_km;

        axios
            .post('/save_ts', {
              id_ts: this.id_ts,
              order_id: this.order_id,
              vid_TS: this.vid_TS,
              stavka_TS: this.stavka_TS,
              stavka_TS_bez_NDS: this.stavka_TS_bez_NDS,
              stavka_TS_za_km: this.stavka_TS_za_km,
              stavka_kp_TS: this.stavka_kp_TS,
              marja_TS: this.marja_TS,
              kol_gruz_TS: this.kol_gruz_TS,
              kol_TS_TS: this.kol_TS_TS,
              rasstojanie_TS: this.rasstojanie_TS,
              adres_pogruzki_TS: this.ad_pogruzki_arr_temp,
              ob_ves_TS: this.ob_ves_TS,
              ob_ob_TS: this.ob_ob_TS,
              adres_vygr_TS: this.ad_vygruz_arr_temp,
              kommentari_TS: this.kommentari_TS,
              checked2: this.checked2,
              terminal_TS: this.terminal_TS,
              perevozchikiList: this.perevozchikiList
            })
            .then(response => {
              this.toast("ТС изменен", "success");
            })
        this.id_ts = '';
        this.vid_TS = '';
        this.stavka_TS = '';
        this.stavka_TS_bez_NDS = '';
        this.stavka_kp_TS = '';
        this.kol_gruz_TS = '';
        this.kol_TS_TS = '';
        this.rasstojanie_TS = '';
        this.adres_pogruzki_TS = '';
        this.ob_ves_TS = '';
        this.ob_ob_TS = '';
        this.adres_vygr_TS = '';
        this.kommentari_TS = '';
        this.checked2 = '';
        this.terminal_TS = '';
        this.edit_number = '';
        this.edit_flag = false;
        this.add_ts = '';
        this.ad_pogruzki_arr_temp = [];
        this.ad_vygruz_arr_temp = [];
        this.perevozchikiList = ''
      }
    },
    // временный метод закрытия Редактирования
    cancel_TS() {
      this.checkboxPogrVygr = false,
          this.id_ts = '';
      this.vid_TS = '';
      this.stavka_TS = '';
      this.stavka_TS_bez_NDS = '';
      this.stavka_kp_TS = '';
      this.kol_gruz_TS = '';
      this.kol_TS_TS = '';
      this.rasstojanie_TS = '';
      this.adres_pogruzki_TS = '';
      this.ob_ves_TS = '';
      this.ob_ob_TS = '';
      this.adres_vygr_TS = '';
      this.kommentari_TS = '';
      this.checked2 = '';
      this.terminal_TS = '';
      this.edit_number = '';
      this.edit_flag = false;
      this.add_ts = '';
      this.ad_pogruzki_arr_temp = [];
      this.ad_vygruz_arr_temp = [];
      this.perevozchikiList = ''
    },
    //метод для получения названия видаТС по его id
    getTSNazvanie() {
      for (var i = 0; i < this.ts_list_names.length; i++) {
        if (this.ts_list_names[i]['id'] === this.vid_TS) {
          this.vidTsNazavanie = this.ts_list_names[i]['ts_name']
        }
      }
    },
    editTs(key) {
      this.add_new_ts = false;
      this.edit_number = key;
      this.edit_flag = true;
      this.add_ts = key;
      this.id_ts = this.spisokTSarr[key]['id_ts'];
      this.vid_TS = this.spisokTSarr[key]['vid_TS'];
      this.stavka_TS = this.spisokTSarr[key]['stavka_TS'];
      this.stavka_TS_bez_NDS = this.spisokTSarr[key]['stavka_TS_bez_NDS'];
      this.stavka_kp_TS = this.spisokTSarr[key]['stavka_kp_TS'];
      this.kol_gruz_TS = this.spisokTSarr[key]['kol_gruz_TS'];
      this.kol_TS_TS = this.spisokTSarr[key]['kol_TS_TS'];
      this.rasstojanie_TS = this.spisokTSarr[key]['rasstojanie_TS'];
      this.perevozchikiList = JSON.parse(JSON.stringify(this.spisokTSarr[key]['perevozchikiList']));
      //получим название вида ТС при редактирвовании и отдадим его в окошко поиска
      this.getTSNazvanie();

      this.ad_pogruzki_arr_temp = this.spisokTSarr[key]['adres_pogruzki_TS'];
      /*if (this.spisokTSarr[key]['adres_pogruzki_TS'].length === 0) {
        let objToPush = {};
        objToPush['adres_pogruzki'] = '';
        this.ad_pogruzki_arr_temp.push(objToPush);
      }*/
      this.ob_ves_TS = this.spisokTSarr[key]['ob_ves_TS'];
      this.ob_ob_TS = this.spisokTSarr[key]['ob_ob_TS'];

      this.ad_vygruz_arr_temp = this.spisokTSarr[key]['adres_vygr_TS'];
      /* if (this.spisokTSarr[key]['adres_vygr_TS'].length === 0) {
         let objToPush1 = {};
         objToPush1['adres_pogruzki'] = '';
         this.ad_vygruz_arr_temp.push(objToPush1);
       }*/

      this.kommentari_TS = this.spisokTSarr[key]['kommentari_TS'];
      this.checked2 = this.spisokTSarr[key]['checked2'];
      this.terminal_TS = this.spisokTSarr[key]['terminal_TS'];
    },
    async deleteTs() {
      console.log('deleteTs');
      const result = await this.confirmMethodMixin();
      this.checkboxPogrVygr = false
      console.log(result)
      if (result) {
        if (this.edit_flag === false) {
          this.id_ts = '';
          this.vid_TS = '';
          this.stavka_TS = '';
          this.stavka_TS_bez_NDS = '';
          this.stavka_kp_TS = '';
          this.kol_gruz_TS = '';
          this.kol_TS_TS = '';
          this.rasstojanie_TS = '';
          this.adres_pogruzki_TS = '';
          this.ob_ves_TS = '';
          this.ob_ob_TS = '';
          this.adres_vygr_TS = '';
          this.kommentari_TS = '';
          this.checked2 = '';
          this.terminal_TS = '';
          this.edit_number = '';
          this.edit_flag = false;
          this.add_ts = '';
          this.add_new_ts = false;
          this.ad_vygruz_arr_temp = [];
          this.ad_pogruzki_arr_temp = [];
        } else {
          axios
              .post('/delete_TS', {
                id_ts: this.id_ts,
                order_id: this.order_id,
              })
          this.spisokTSarr.splice(this.edit_number, 1);
          this.ad_vygruz_arr_temp = [];
          this.ad_pogruzki_arr_temp = [];
          this.id_ts = '';
          this.vid_TS = '';
          this.stavka_TS = '';
          this.stavka_TS_bez_NDS = '';
          this.stavka_kp_TS = '';
          this.kol_gruz_TS = '';
          this.kol_TS_TS = '';
          this.rasstojanie_TS = '';
          this.adres_pogruzki_TS = '';
          this.ob_ves_TS = '';
          this.ob_ob_TS = '';
          this.adres_vygr_TS = '';
          this.kommentari_TS = '';
          this.checked2 = '';
          this.terminal_TS = '';
          this.edit_number = '';
          this.edit_flag = false;
          this.add_ts = '';
          this.add_new_ts = false;
        }

        if (this.spisokTSarr.length === 0) {

          this.hideButtonsUsers()
        }
      }
    },
    addTS() {
      if (!this.add_new_ts) {
        this.add_new_ts = !this.add_new_ts;
      }
      if (this.add_new_ts) {
        let objTSToPush = {
          'id_ts': this.spisokTSarr.length + 1,
          // 'vid_TS': '',
          // 'stavka_TS': '',
          // 'stavka_TS_bez_NDS': '',
          // 'stavka_kp_TS': '',
          // 'kol_gruz_TS': '',
          // 'kol_TS_TS': '',
          // 'rasstojanie_TS': this.rasstojanie,
          'adres_pogruzki_TS': '',
          // 'ob_ves_TS': '',
          // 'ob_ob_TS': '',
          'adres_vygr_TS': '',
          // 'kommentari_TS': '',
          // 'checked2': '',
          // 'terminal_TS': '',
          'edit_number': '',
          'edit_flag': false,
          // 'ad_pogruzki_arr_temp': [],
          // 'ad_vygruz_arr_temp': [],
          'perevozchikiList': [],
        };
        this.spisokTSarr.push(objTSToPush);
        this.add_ts = this.spisokTSarr.length - 1;

        this.id_ts = this.spisokTSarr.length - 1;
        this.vid_TS = '';
        this.stavka_TS = '';
        this.stavka_TS_bez_NDS = '';
        this.stavka_kp_TS = '';
        this.kol_gruz_TS = '';
        this.kol_TS_TS = '';
        this.rasstojanie_TS = this.rasstojanie;
        this.adres_pogruzki_TS = '';
        this.ob_ves_TS = '';
        this.ob_ob_TS = '';
        this.adres_vygr_TS = '';
        this.kommentari_TS = '';
        this.checked2 = '';
        this.terminal_TS = '';
        this.edit_number = '';
        this.edit_flag = false;
        this.ad_pogruzki_arr_temp = [];
        this.ad_vygruz_arr_temp = [];
        this.perevozchikiList = [];

        if (this.edit_flag === false) {
          //проверка, были ли вообще хоть одно ТС
          if (this.spisokTSarr.length === 0) {
            this.id_ts = 0;
          } else {
            let last_id_ts = this.spisokTSarr.slice(-1);
            this.id_ts = Number(last_id_ts[0]['id_ts']) + Number(1);
          }
        }
      }
      // setTimeout(() => {
      //   this.scroll_to_end();
      // }, 200);

    },
    scroll_to_end() {
      let block = document.getElementsByClassName("right_white_block");
      let offsetPosition = block[0].scrollHeight;
      window.scrollBy({
        top: offsetPosition,
        behavior: 'smooth'
      });
    },
    //метод логики удаления из массива
    async delete_adres(arr, key, ref, pogr_vygr) {
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
    delete_from_arr(arr, key) {
      arr.splice(key, 1)
    },
    //arr массив откуда
    //ref ссылка на элементы вида AutoSelectComponent_pogruzka_empty
    //pogr_vygr ссылка во временном массиве названия например adres_vygruzki_show
    render_new_arr(arr, ref, pogr_vygr) {
      for (let i = 0; i < arr.length; i++) {
        this.$refs[ref][i].up_gruz_from_modal(arr[i][pogr_vygr])
      }
    },

    delete_adres_vygruz(key) {
      this.ad_vygruz_arr_temp.splice(key, 1)
    },
    add_empty_adres_pogr_to_old(elem1, key) {
      let objToPush = {};
      objToPush['adres_pogruzki'] = '';
      this.spisokTSarr[key].adres_pogruzki_TS.push(objToPush);
    },
    add_empty_adres_pogr() {
      let objToPush = {};
      objToPush['adres_pogruzki'] = '';
      this.ad_pogruzki_arr_temp.push(objToPush);
    },
    add_empty_adres_vygruz() {
      let objToPush = {};
      objToPush['adres_pogruzki'] = '';
      this.ad_vygruz_arr_temp.push(objToPush);
    },
    add_empty_terminal() {

    },
    dobavit_oplatu() {
      axios
          .post('/add_oplata_orders', {
            id: this.order_id,
          })
          .then(response => {
            let objToPush = {};
            objToPush['id'] = response.data.data.id;
            objToPush['oplata'] = '';
            objToPush['summa'] = '';
            this.oplata_arr.push(objToPush);
          })
    },
    openEndDatePicker: function () {

      this.$refs.startDatePicker.showCalendar();
      if (document.getElementsByClassName('vdp-datepicker__calendar')[3].style.display !== 'none') {
        this.$refs.startDatePicker1.showCalendar();
      }
      document.getElementsByClassName('vdp-datepicker__calendar')[3].style.display = 'none';

    },
    openEndDatePicker1: function () {
      if (document.getElementsByClassName('vdp-datepicker__calendar')[0].style.display !== 'none') {
        this.$refs.startDatePicker.showCalendar();
      }
      document.getElementsByClassName('vdp-datepicker__calendar')[0].style.display = 'none';
      this.$refs.startDatePicker1.showCalendar();
    },
    customFormatter1(date) {
      //первичная загрузка
      if (!this.start_flag) {
        this.start_flag = true
      }
      //все последующие загрузки и изменения
      else {
        this.rasschitat_do = new Date(this.rasschitat_do).toLocaleDateString('ru-RU');
        //   this.update_order()
      }
      return moment(date).format('D MM YYYY');
    },
    customFormatter(date) {
      //первичная загрузка
      if (!this.start_flag) {
        this.start_flag = true
      }
      //все последующие загрузки и изменения
      else {
        this.data_vneseniya = new Date(this.data_vneseniya).toLocaleDateString('ru-RU');
        //  this.update_order()
      }
      return moment(date).format('D MM YYYY');
    },
    addFiles(type) {
      if (type == 'nom') {
        this.$refs.files.click();
      }
      if (type == 'ready') {
        this.$refs.files_ready.click();
      }
    },
    DownloadFiles(type) {
      axios
          .post('/download_xlsx_orders', {
            id: this.order_id,
            docType: type,
          })
          .then(response => {
            window.location.assign('/get_xlsx_file/tempStorage/' + response.data.file);
          })
    },
    handleFilesUpload(type) {
      let uploadedFiles = '';
      if (type == 'nom') {
        uploadedFiles = this.$refs.files.files;
      }
      if (type == 'ready') {
        uploadedFiles = this.$refs.files_ready.files;
      }
      if (/\.(xlsx?)$/i.test(uploadedFiles[0].name)) {
        let reg = '';
        reg = (uploadedFiles[0].name.match(/([A-Za-zа-яА-Я0-9Ёё\W]+)\.(xlsx?)/))
        if (type == 'nom') {
          this.nomenklatura = reg[1];
          this.nomenklatura = reg[0];
        }
        if (type == 'ready') {
          this.gotovyi_raschet = reg[1];
          this.gotovyi_raschet = reg[0];
        }

        let formData = new FormData();
        let file = uploadedFiles;
        formData.append('file_xlsx', file[0]);
        if (type == 'nom') {
          formData.append('file_name', this.nomenklatura);
          formData.append('doc_type', 'nom');
        }
        if (type == 'ready') {
          formData.append('file_name', this.gotovyi_raschet);
          formData.append('doc_type', 'ready');
        }
        formData.append('order_id', this.order_id);
        formData.append('full_name', reg[0]);
        axios.post('/store_xlsx',
            formData,
            {
              headers: {
                'Content-Type': 'multipart/form-data'
              }
            }
        )
      } else {
        alert('Не верный формат файла')
      }

    },

  }
}
</script>

<style>
.create_ord_right_lit_text + .v-popper {
  display: inline-block;
  margin-left: 5px;
}
</style>

