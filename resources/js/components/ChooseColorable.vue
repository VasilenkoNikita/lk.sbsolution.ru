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

                    <tbody v-model="colorsNew" handle=".handle" tag="tbody" @change="update" class="handle">
                        <tr v-for="(color, index) in colorsNew" :key="color.id">
                            <td>{{ color.name }}</td>
                            <td><input class="form-control" type="color" :value="color.color" readonly="readonly"></td>
                            <td style="width:60px">
                                <button @click="update(color.id);" class="btn btn-link" style="padding: .25rem .25rem;">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" class="mr-2" width="1em" height="1em" role="img" fill="currentColor" componentname="orchid-icon">
                                        <path d="M1,32a1,1,0,0,1-.45-.11A1,1,0,0,1,0,31c0-3.74,1.79-7.91,4.69-8.14a3.83,3.83,0,0,1,3.55,2.21,3.7,3.7,0,0,1-.56,4.2,5.26,5.26,0,0,1-3.83,1.82,4.24,4.24,0,0,0-2.26.72A1,1,0,0,1,1,32Zm3.92-7.15H4.85C3.65,25,2.51,27,2.13,29.36a9.28,9.28,0,0,1,1.5-.26A3.26,3.26,0,0,0,6.17,28a1.69,1.69,0,0,0,.29-2A2,2,0,0,0,4.92,24.85Z"></path>
                                        <path d="M10.49,25.33a1,1,0,0,1-.7-.29L7,22.21a1,1,0,0,1-.29-.7A1,1,0,0,1,7,20.8L27.47.29a1,1,0,0,1,1.41,0l2.83,2.83a1,1,0,0,1,0,1.42L11.2,25A1,1,0,0,1,10.49,25.33ZM9.08,21.51l1.41,1.41L29.59,3.83,28.17,2.41Z"></path>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button @click="detach('1');" class="btn  btn-default btn-block mt-3 mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="1em" height="1em" viewBox="0 0 32 32" class="mr-2" role="img" fill="currentColor" componentname="orchid-icon">
                        <path d="M5.002 12.121v-11.121c0-0.552-0.448-1-1-1s-1 0.448-1 1v11.12c-1.729 0.446-3.013 2.014-3.013 3.88s1.284 3.434 3.013 3.881v11.119c0 0.552 0.448 1 1 1s1-0.448 1-1v-11.12c1.728-0.447 3.010-2.014 3.010-3.88s-1.282-3.433-3.010-3.879zM4.013 18.001c-0.003 0-0.008-0.001-0.011-0.001s-0.008 0.001-0.013 0.002c-1.099-0.006-1.99-0.901-1.99-2.002s0.893-1.995 1.991-2.001c0.004 0 0.008 0.001 0.012 0.001s0.008-0.001 0.011-0.001c1.098 0.007 1.989 0.902 1.989 2.001s-0.891 1.994-1.989 2.001zM17.002 18.121l-0-17.121c0-0.552-0.448-1-1-1s-1 0.448-1 1v17.12c-1.729 0.446-3.012 2.014-3.012 3.88s1.284 3.434 3.012 3.881v5.12c0 0.552 0.448 1 1 1s1-0.448 1-1v-5.12c1.727-0.447 3.009-2.014 3.009-3.88-0-1.864-1.282-3.432-3.009-3.879zM16.013 24.001c-0.004 0-0.008-0.001-0.012-0.001s-0.008 0.001-0.013 0.002c-1.098-0.006-1.99-0.901-1.99-2.002s0.894-1.996 1.994-2.001c0.004 0 0.007 0.001 0.011 0.001s0.006-0.001 0.009-0.001c1.099 0.006 1.992 0.901 1.992 2.001s-0.892 1.994-1.99 2.001zM29.002 6.121l-0-5.121c0-0.552-0.448-1-1-1s-1 0.448-1 1v5.12c-1.729 0.446-3.012 2.014-3.012 3.88s1.284 3.435 3.012 3.88v17.119c0 0.552 0.448 1 1 1s1-0.448 1-1v-17.12c1.727-0.447 3.009-2.014 3.009-3.88-0.001-1.864-1.282-3.432-3.009-3.879zM28.013 12.001c-0.004 0-0.007-0.001-0.011-0.001s-0.009 0.001-0.013 0.001c-1.099-0.006-1.991-0.901-1.991-2.001s0.892-1.995 1.99-2.001c0.005 0 0.009 0.001 0.013 0.001s0.008-0.001 0.011-0.001c1.098 0.007 1.989 0.902 1.989 2.001 0.001 1.099-0.89 1.994-1.989 2.001z"></path>
                    </svg>
                    Отменить заливку
                </button>
                <fieldset class="mb-3" data-async>
                    <input class="form-control" id="chosenColor" name="color_id" type="hidden">
                    <input class="form-control" id="detachColor" name="detach_color" type="hidden">
                </fieldset>
            </div>
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
    props: ['colors', 'client'],
    data() {
        return {
            colorsNew: this.colors,
            csrf: this.token
        }
    },
    methods: {
        update(value) {
            document.getElementById("chosenColor").value = value;
        },
        detach(value) {
            document.getElementById("detachColor").value = value;
        }
    },
    mounted() {
        console.log('Component mounted.')
    }
}
</script>
