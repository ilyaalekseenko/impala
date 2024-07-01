import { markRaw } from "vue";
import { defineStore } from "pinia";

export const useModal = defineStore("modal", {
    state: () => {
        return {
            isOpen: false,
                view: {},
            actions: [],
        }
    },
    actions: {
        open(view, actions){
            this.isOpen = true;
            this.actions = actions;
            // using markRaw to avoid over performance as reactive is not required
            this.view = markRaw(view)
        },
        close() {
            this.isOpen = false;
            this.view = {};
            this.actions = [];
        },
    },
});

export default useModal;