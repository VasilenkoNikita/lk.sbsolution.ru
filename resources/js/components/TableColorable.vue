<template>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive table-bordered table-striped">
                <table class="table">
                    <thead>
                    <tr>
                        <th>
                            Название
                        </th>
                        <th>
                            Цвет
                        </th>
                        <th style="width:60px"></th>
                    </tr>
                    </thead>

                    <draggable v-model="colorsNew" handle=".handle" tag="tbody" @change="update" class="handle">
                        <tr v-for="(color, index) in colorsNew" :key="color.id">
                            <td><input class="form-control"  :value="color.name" v-on:input="color.name = $event.target.value" @change="update"></td>
                            <td><input class="form-control" type="color" :value="color.color" v-on:input="color.color = $event.target.value" @change="update"></td>
                            <td style="width:60px">
                                <button @click="colorsNew.splice(index, 1); update();" type="submit" class="btn btn-link" style="padding: .25rem .25rem;">
                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="1em" height="1em" viewBox="0 0 32 32" role="img" fill="currentColor" componentname="orchid-icon">
                                        <path d="M28.025 4.97l-7.040 0v-2.727c0-1.266-1.032-2.265-2.298-2.265h-5.375c-1.267 0-2.297 0.999-2.297 2.265v2.727h-7.040c-0.552 0-1 0.448-1 1s0.448 1 1 1h1.375l2.32 23.122c0.097 1.082 1.019 1.931 2.098 1.931h12.462c1.079 0 2-0.849 2.096-1.921l2.322-23.133h1.375c0.552 0 1-0.448 1-1s-0.448-1-1-1zM13.015 2.243c0-0.163 0.133-0.297 0.297-0.297h5.374c0.164 0 0.298 0.133 0.298 0.297v2.727h-5.97zM22.337 29.913c-0.005 0.055-0.070 0.11-0.105 0.11h-12.463c-0.035 0-0.101-0.055-0.107-0.12l-2.301-22.933h17.279z"></path>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </draggable>
                </table>
            </div>
            <button type="button"
                    class="btn  btn-default btn-block mt-3 mb-3"
                    data-controller="screen--modal-toggle"
                    data-action="click->screen--modal-toggle#targetModal"
                    data-screen--modal-toggle-title="Создание цвета"
                    data-screen--modal-toggle-key="ModalColorsCreate"
                    data-screen--modal-toggle-async=""
                    data-screen--modal-toggle-params="[]"
                    data-screen--modal-toggle-action="clients/methodForModalCreateColors"
                    data-screen--modal-toggle-open=""
                    id="btnnewcolor">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="1em" height="1em" viewBox="0 0 32 32" class="mr-2" role="img" fill="currentColor" componentname="orchid-icon">
                    <path d="M5.002 12.121v-11.121c0-0.552-0.448-1-1-1s-1 0.448-1 1v11.12c-1.729 0.446-3.013 2.014-3.013 3.88s1.284 3.434 3.013 3.881v11.119c0 0.552 0.448 1 1 1s1-0.448 1-1v-11.12c1.728-0.447 3.010-2.014 3.010-3.88s-1.282-3.433-3.010-3.879zM4.013 18.001c-0.003 0-0.008-0.001-0.011-0.001s-0.008 0.001-0.013 0.002c-1.099-0.006-1.99-0.901-1.99-2.002s0.893-1.995 1.991-2.001c0.004 0 0.008 0.001 0.012 0.001s0.008-0.001 0.011-0.001c1.098 0.007 1.989 0.902 1.989 2.001s-0.891 1.994-1.989 2.001zM17.002 18.121l-0-17.121c0-0.552-0.448-1-1-1s-1 0.448-1 1v17.12c-1.729 0.446-3.012 2.014-3.012 3.88s1.284 3.434 3.012 3.881v5.12c0 0.552 0.448 1 1 1s1-0.448 1-1v-5.12c1.727-0.447 3.009-2.014 3.009-3.88-0-1.864-1.282-3.432-3.009-3.879zM16.013 24.001c-0.004 0-0.008-0.001-0.012-0.001s-0.008 0.001-0.013 0.002c-1.098-0.006-1.99-0.901-1.99-2.002s0.894-1.996 1.994-2.001c0.004 0 0.007 0.001 0.011 0.001s0.006-0.001 0.009-0.001c1.099 0.006 1.992 0.901 1.992 2.001s-0.892 1.994-1.99 2.001zM29.002 6.121l-0-5.121c0-0.552-0.448-1-1-1s-1 0.448-1 1v5.12c-1.729 0.446-3.012 2.014-3.012 3.88s1.284 3.435 3.012 3.88v17.119c0 0.552 0.448 1 1 1s1-0.448 1-1v-17.12c1.727-0.447 3.009-2.014 3.009-3.88-0.001-1.864-1.282-3.432-3.009-3.879zM28.013 12.001c-0.004 0-0.007-0.001-0.011-0.001s-0.009 0.001-0.013 0.001c-1.099-0.006-1.991-0.901-1.991-2.001s0.892-1.995 1.99-2.001c0.005 0 0.009 0.001 0.013 0.001s0.008-0.001 0.011-0.001c1.098 0.007 1.989 0.902 1.989 2.001 0.001 1.099-0.89 1.994-1.989 2.001z"></path>
                </svg>
                Создать новый цвет
            </button>
        </div>
    </div>
</template>

<script>
import draggable from 'vuedraggable'
import axios from 'axios';
export default {
    components: {
        draggable
    },
    props: ['colors'],
    data() {
        return {
            colorsNew: this.colors,
            csrf: this.token
        }
    },
    methods: {
        update() {
            this.colorsNew.map((color, index) => {
                color.position = index + 1;
            })

            axios.put('clients/updateColors', {
                colors: this.colorsNew
            })
                .then(function (response) {

            })
        }
    },
    mounted() {
        console.log('Component mounted.')
    }
}
</script>
