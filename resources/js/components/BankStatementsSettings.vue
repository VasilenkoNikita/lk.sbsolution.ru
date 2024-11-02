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
                            Рассчетный счет
                        </th>
                        <th style="width:30px;">
                            Позиция
                        </th>
                    </tr>
                    </thead>

                    <draggable v-model="bankStatementsSettings" handle=".handle" tag="tbody" @change="update" class="handle">
                        <tr v-for="(bankStatement, index) in bankStatementsSettings" :key="bankStatement.id">
                            <td style="cursor: grab;"><input class="form-control"  :value="bankStatement.bank_name" v-on:input="bankStatement.bank_name = $event.target.value" @change="update"></td>
                            <td style="cursor: grab;"><input class="form-control"  :value="bankStatement.checking_account" v-on:input="bankStatement.checking_account = $event.target.value" @change="update"></td>
                            <td style="cursor: grab; width:30px;"><input class="form-control"  :value="bankStatement.position" v-on:input="bankStatement.position = $event.target.value" @change="update"></td>
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
    props: ['bankstatements', 'client'],
    data() {

        return {
            bankStatementsSettings: this.bankstatements,
            csrf: this.token
        }
    },
    methods: {
        update() {
            this.bankStatementsSettings.map((bankStatement, index) => {
                bankStatement.position = index + 1;
            })

            axios.post('edit/updateBankStatementsSettings', {
                bankStatements: this.bankStatementsSettings
            })
                .then(function (response) {
                    console.log(this.bankStatementsSettings)
            })
        }
    },
    mounted() {
        console.log(this.bankStatementsSettings)

    }
}
</script>
