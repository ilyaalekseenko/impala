<script setup>
import { ref, reactive, onMounted } from 'vue';
import {log} from "three/tsl";

// Список статусов для фильтрации
const statuses = reactive([
  { key: 'all', label: 'Журнал запросов', count: 0, unread: null },
  { key: 'ocenka', label: 'Оценка', count: 0, unread: null },
  { key: 'naznachenie_stavki', label: 'Назначение ставки', count: 0, unread: null },
  { key: 'v_rabote', label: 'В работе', count: 0, unread: null },
  { key: 'kontrol', label: 'Контроль', count: 0, unread: null },
  { key: 'zavershen', label: 'Завершён', count: 0, unread: null },
  { key: 'archive', label: 'Архив', count: 0, unread: null },
]);

// Для хранения выбранного статуса
const selectedStatus = ref(null);
const unreadRows = ref([]);

// Описание колонок таблицы
const columns = [
  { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
  { data: 'nomer_zayavki', title: '#' },
  { data: 'data_vneseniya', title: 'Дата внесения' },
  { data: 'status_label', title: 'Статус', render: function(data, type, row, meta) {
      if (type === 'display') {
        return '<span class="impala-badge impala-badge-' + row.status.slug + '">' + row.status.short + '</span>';
      }
      return data;
    } },
  { data: 'transportation', title: 'Тип перевозки' },
  { data: 'adres_pogruzke', title: 'Откуда' },
  { data: 'adres_vygruski', title: 'Куда' },
  { data: 'customer', title: 'Заказчик' },
  { data: 'rasschitat_do', title: 'Рассчитать до' },
  { data: 'logistician', title: 'Логист' },
  { data: 'cena_kontrakta', title: 'Общий бюджет' }
];

// Данные таблицы (загружаются с сервера)
const data = ref([]);

// Ссылка на экземпляр DataTable
const table = ref(null);

// **Настройки DataTables**
const options = {
  serverSide: true, // Включение серверной обработки
  processing: true, // Показ обработки данных
  ajax: {
    url: '/orders', // URL для получения данных
    type: 'GET',
    data: (d) => {
      if (selectedStatus.value) {
        d[selectedStatus.value] = 1;
      }
    },
    dataSrc: 'data', // Путь к данным в ответе сервера
    error: function (xhr, error, thrown) {
      console.error('Ошибка загрузки данных:', error);
    }
  },
  order: [[2, 'desc']], // Сортировка по столбцу ID по убыванию
  select: {
    style:    'multi',
    selector: 'td:first-child',
    items: 'row',
    info: false
  },
  drawCallback: function (settings) {
    unreadRowsAddClass();
  }
};

// Фильтрация по статусу
const filterByStatus = (key) => {
  if (selectedStatus.value === key) {
    selectedStatus.value = null; // Сбрасываем фильтр, если статус уже выбран
  } else {
    selectedStatus.value = key; // Устанавливаем новый статус
  }
  table.value.dt.ajax.reload(); // Перезагрузка таблицы
};

// Удаление выбранной строки
const deleteSelected = async () => {
  let selectedRows = [];
  table.value.dt.rows({ selected: true }).every(function () {
    let data = this.data();
    selectedRows.push(data.id); // Собираем ID выбранных строк
  });

  if (selectedRows.length > 0) {
    const id = selectedRows[0].id;
    if (confirm('Вы уверены, что хотите удалить выбранные записи?')) {
      axios.post('/delete_orders', { orders_id: selectedRows })
          .then(function (response) {
            table.value.dt.ajax.reload(); // Перезагрузка таблицы
            alert(response.data.message);
          })
          .catch(function (error) {
            console.error('Ошибка удаления:', error);
            alert('Произошла ошибка при удалении');
          });
    }
  } else {
    alert('Пожалуйста, выберите хотя бы одного пользователя для удаления.');
  }
};

// Обработка двойного клика по строке
const handleDoubleClick = (e) => {
  const row = e.target.closest('tr');
  if (row) {
    const rowData = table.value.dt.row(row).data();
    if (rowData) {
      axios
          .post('/check_if_order_isset', {
            id: rowData.id,
          })
          .then(response => {
            if (response.data.data === 'isset') {
              //если закладка в работе или контроль то редирект на вид grade
              if (rowData.v_rabote === 1) {
                window.location.href = ('/grade/' + rowData.id)
              } else {
                window.location.href = ('/create_orders/' + rowData.id)
              }
            } else {
              alert('Запрос удален')
            }
          })
    }
  }
};

const unreadRowsAddClass = () => {
  if (unreadRows.value.length > 0 ) {
    unreadRows.value.forEach(unread => {
      let rowUnread = table.value.dt.row((idx, data) => data.id === unread).node();

      if (rowUnread) {
        rowUnread.classList.add('unread');
      }
    });
  }
};

const updateStatusCounts = () => {
  axios
      .post('/header_counter_orders', {})
      .then(({data}) => {
        console.log(data)
            statuses.forEach(status => {
              if (data.mainHeaderArr[status.key] !== undefined) {
                status.count = data.mainHeaderArr[status.key];
              }
              if (data.unreadHeaderArr[status.key] !== undefined) {
                status.unread = data.unreadHeaderArr[status.key];
              }
            });
            if (data.unreadHeaderArr['ids'] !== undefined) {
              unreadRows.value.push(...data.unreadHeaderArr['ids']);
              unreadRowsAddClass();
            }
          }
      );
};

// Подключение к Echo при монтировании компонента
onMounted(() => {
  updateStatusCounts()
  window.Echo.private('logist')
      .listen('UpdateMainLogistEvent', (e) => {
        console.log(e)
        //если номер id пришедшего обновления совпадает с номером пользователя
        /*if (e.logistId === this.auth_user.id) {
          //общая белая шапка
          this.zurnal_zaiavok = e.mainHeaderArr.all
          this.ocenka = e.mainHeaderArr.ocenka
          this.naznachenie_stavki = e.mainHeaderArr.naznachenieStavki
          this.v_rabote = e.mainHeaderArr.vRabote
          this.kontrol = e.mainHeaderArr.kontrol
          this.zavershen = e.mainHeaderArr.vRabote
          //зелёная оповещения
          this.ocenka_counter = e.unreadHeaderArr.ocenka
          this.naznachenie_stavki_unread_count = e.unreadHeaderArr.naznachenieStavki
          this.v_rabote_unread_count = e.unreadHeaderArr.vRabote
          this.kontrol_unread_count = e.unreadHeaderArr.kontrol
          this.zavershen_unread_count = e.unreadHeaderArr.zavershen
        }*/
        updateStatusCounts();
        table.value.dt.ajax.reload();
      })

  window.Echo.private('delete-order-channel')
      .listen('DeleteOrderEvent', (e) => {
        console.log(e)
        updateStatusCounts();
        table.value.dt.ajax.reload();
      })
});
</script>

<template>
  <div class="p-5 justify-between items-start gap-2 flex flex-col">
    <div class="flex align-center justify-between w-full">
      <div class="flex gap-4 align-center">
        <button
            v-for="status in statuses"
            :key="status.key"
            class="impala-btn impala-btn-white"
            :class="(status.key === selectedStatus || status.key === 'all' && selectedStatus == null) ? 'impala-badge-'+status.key + ' active' : 'impala-badge-'+status.key"
            @click="filterByStatus(status.key)"
            :data-filter-key="status.key"
        >
          {{ status.label }}
          <span class="impala-badge" :class="'impala-badge-'+status.key" v-html="status.count"></span>
          <span v-if="status.unread" class="impala-badge-red" v-html="'+' + status.unread"></span>
        </button>
      </div>
      <div class="mb-3 flex gap-4 align-center">
        <button class="impala-btn impala-btn-red" @click="deleteSelected">Удалить</button>
        <button class="impala-btn impala-btn-primary" v-on:click="go_to_path('/create_orders')"><i class="iconsax-add-circle"></i> Создать запрос</button>
      </div>
    </div>
    <DataTable
        :columns="columns"
        :options="options"
        :data="data"
        ref="table"
        class="impala-datatable"
        @dblclick="handleDoubleClick"
    >
    </DataTable>
  </div>
</template>

<style scoped>

</style>