// dashboard.js

require('./bootstrap');

//import HelloController from "./controllers/hello"

//application.register("hello", HelloController);

import TableHideController from "./controllers/tableHide_controller"
application.register("tableHide", TableHideController);

window.Vue = require("vue").default;

var csrf_token = $('meta[name="csrf-token"]').attr('content');

import VueColumnsResizable from 'vue-columns-resizable';
import VueDragscroll from 'vue-dragscroll'
Vue.use(VueDragscroll)
Vue.use(VueColumnsResizable);

Vue.component('table-draggable', require('./components/TableDraggable.vue').default);
Vue.component('table-colorable', require('./components/TableColorable.vue').default);
Vue.component('choose-colorable', require('./components/ChooseColorable.vue').default);
Vue.component('table-bank-statements', require('./components/BankStatementsSettings.vue').default);


document.addEventListener("turbolinks:load", function() {
    const clientsSettings = new Vue({
        el: '#clientsSettings',
        data    :   {
            token   : csrf_token,
        }
    });

    const colorsChose = new Vue({
        el: '#colorsChose',
        data    :   {
            token   : csrf_token,
        }
    });

    const colors = new Vue({
        el: '#colors',
        data    :   {
            token   : csrf_token,
        }
    });

    const bankStatements = new Vue({
        el: '#bankStatements',
        data    :   {
            token   : csrf_token,
        }
    })
    const dragscrolltable = new Vue({
        el: '#dragscrolltable',
        data    :   {
            token   : csrf_token,
        }
    });
})
