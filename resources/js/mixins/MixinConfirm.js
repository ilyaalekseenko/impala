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

    },

}
