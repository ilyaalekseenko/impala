export const myClickOutside = {
    bind(el, binding, vnode) {
        el.clickOutsideEvent = function(event) {
            // Проверяем, был ли клик снаружи элемента
            if (!(el === event.target || el.contains(event.target))) {
                // Выполняем обработчик, переданный в директиву
                vnode.context[binding.expression](event);
            }
        };

        // Добавляем обработчик события клика при привязке директивы
        document.body.addEventListener('click', el.clickOutsideEvent);
    },
    unbind(el) {
        // Удаляем обработчик события клика при отвязке директивы
        document.body.removeEventListener('click', el.clickOutsideEvent);
    },
};
