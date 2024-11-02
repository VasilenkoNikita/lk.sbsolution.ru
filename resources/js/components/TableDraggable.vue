<template>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive table-bordered table-striped">
                <table class="table">
                    <thead>
                    <tr>
                        <th>
                            Колонка
                        </th>
                        <th>
                            Позиция
                        </th>
                    </tr>
                    </thead>

                    <draggable v-model="settingsNew" handle=".handle" tag="tbody" @change="update" class="handle">
                        <tr v-for="(setting, index) in settingsNew" :key="setting.id">
                            <td>{{ setting.name }}</td>
                            <td>{{ setting.position }}</td>
                        </tr>
                    </draggable>
                </table>
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
    props: ['settings'],
    data() {
        return {
            settingsNew: this.settings,
            csrf: this.token
        }
    },
    methods: {
        update() {
            this.settingsNew.map((setting, index) => {
                setting.position = index + 1;
            })
            axios.put('clients/updateAll', {
                settings: this.settingsNew
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
