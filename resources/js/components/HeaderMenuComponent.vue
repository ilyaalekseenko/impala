<template>
  <header class="inline-flex space-x-2.5 items-center justify-between py-4">
    <a href="/">
      <img :src="'/assets/img/logo.svg'" class="impala-logo" alt="Impala CRM">
    </a>
    <nav class="flex space-x-1.5 items-start justify-start">
      <a href="#" v-on:click="go_to_path('/gruzootpravitel')">
        Грузоотправитель/грузополучатель
      </a>
      <a href="#" v-on:click="go_to_path('/perevozchiki')">
        Перевозчики
      </a>
      <a href="#" v-on:click="go_to_path('/stavki')">
        Ставки
      </a>
    </nav>
    <div class="impala-userbar gap-8">
      <a href="#" v-on:click="go_to_path('/docs_setting')" v-show="checkRolePermissionMixin([1])"><i
          class="iconsax-setting-1"></i></a>
      <div class="inline-flex items-center justify-center gap-4">
        <span class="text-base font-semibold" v-html="useFio(user.last_name, user.first_name, user.patronymic)"></span>
        <a href="#" v-on:click="logout()"><i class="iconsax-logout-1"></i></a>
      </div>
    </div>
  </header>
</template>

<script>
import {useFio} from "../composables/fio";

export default {
  props: ['user'],
  data() {
    return {
      role: '',
      permissions: ''
    }
  },
  mounted() {
    this.getRoleUser()
  },
  methods: {
    useFio,
    getRoleUser() {
      axios
          .post('/getRoleUser', {})
          .then(({data}) => (
                  this.role = data.user.roles[0]['id'],
                      this.permissions = data.user.role_perm.permissions
              )
          )
    },
    logout() {
      axios
          .post('/logout', {})
          .then(({data}) => (
                  window.location.href = ('/login')
              )
          )
    }
  }
}
</script>
