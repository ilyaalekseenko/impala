export default {
    data() {
        return {

        }
    },

    methods: {
        //миксин для преобразования чисел в вид понятный компьютеру
        //в этом методе указывать глобальную переменную а не локальную
        setToNumberFormat(field)
        {
            //проверяем если запятых больше чем 1 тогда выкидываем предупреждение
            let countCom=this.countCommas(this[field])
            if(countCom>1)
            {
                alert('Вы ввели число в не правильном формате. Правильный формат числа с точкой, без запятых')
                this[field]=0;
            }
            else
            {
                //убираем все буквы и пробелы, заменяем запятые на точку
                this[field] = this.removeLettersSpaces(this.replaceCommaWithDot(this[field]));
            }
            return this[field];
        },
        countCommas(str) {
            return str.match(/,/g) ? str.match(/,/g).length : 0;
        },
        removeLettersSpaces(str) {
            return str.replace(/[^0-9.]/g, ""); // заменяем все символы, не являющиеся цифрами или точкой, на пустую строку
        },
        replaceCommaWithDot(str) {
            return str.replace(/,/g, "."); // заменяем все запятые на точки
        },

    },
}
