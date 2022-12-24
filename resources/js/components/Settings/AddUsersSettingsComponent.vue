<template>
    <div class="container">
        <div class="col-12 mail_header">Добавление нового пользователя</div>
        <div class="justify-content-center">
            <div v-if="alert" class="alert alert-danger" role="alert">
                <ul id="error_list">
                    <li v-for="item in alert_arr" >
                        {{ item }}
                    </li>
                </ul>
            </div>
            <div v-if="success" class="alert alert-success" role="alert">
                Пользователь успешно добавлен
            </div>
            <div class="col-6">Никнейм</div>
            <input class="col-6"  v-model="name"  />
            <div class="col-6">Почта</div>
            <input class="col-6" type="email" v-model="email"  />
            <div class="col-3">
            <button type="button" class=" btn btn-primary add_new_user_button" v-on:click="add_new_user()">Добавить нового пользователя</button>
            </div>
            </div>
    </div>
</template>

<script>
    export default {
        mounted() {
        },
        data() {
            return {
                name:'',
                email:'',
                alert_arr:[],
                alert:false,
                success:''
            }
        },
        methods: {
            add_new_user()
            {
                this.alert_arr=[];
                this.alert=false;
                this.success=false;
                if(this.name=="")
                {
                    this.alert=true;
                    this.alert_arr.push('Заполните поле "Никнейм"');
                }
                if(this.email=="")
                {
                    this.alert=true;
                    this.alert_arr.push('Заполните поле "Почта"');
                }
                if(this.alert==false)
                {
                    axios.post( '/add_user',
                        {
                            name:this.name,
                            email:this.email,
                        }
                    )
                        .then(response => {
                            if (response.data.status === 'success') {
                                    this.success=true,
                                    this.name='',
                                    this.email=''

                            }
                        })
                        .catch((error) => {
                            this.add_to_errors(error.response.data.errors);
                        })
                }
            },
            add_to_errors(inp_errors)
            {
                this.alert=true;
                let temp_arr=[];
                for (const [key, value] of Object.entries(inp_errors)) {
                    value.forEach(function(number) {
                        temp_arr.push(number)
                    });
                }
                this.alert_arr=temp_arr;
            },

        }

    }
</script>
