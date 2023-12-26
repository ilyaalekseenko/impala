export default {
    data() {
        return {

        }
    },

    methods: {
        confirmMethodMixin() {
            return new Promise((resolve) => {
                this.$confirm({
                    message: 'Вы уверены?',
                    button: {
                        no: 'Нет',
                        yes: 'Да',
                    },
                    callback: (confirm) => {
                        resolve(confirm);
                    },
                });
            });
        },
        confirmDocMethodMixin() {
            return new Promise((resolve) => {
                this.$confirm({
                    message: 'Выберите тип документа',
                    button: {
                        no: 'Excel',
                        yes: 'Doc',
                        cancel: 'Отмена',
                    },
                    callback: (confirm) => {
                        resolve(confirm);
                    },
                });
            });
        },

    },

}
