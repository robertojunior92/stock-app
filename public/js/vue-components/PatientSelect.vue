<template>
    <v-select :options="resultPatients" @search="findPatient"></v-select>
</template>

<script>
    module.exports = {
        props: ['value'],
        components {
            'v-select': VueSelect.VueSelect,
        }
        data() {
            return {
                resultPatients: []
            }
        },
        methods: {
            async findPatient(search, loading) {
                const response = await fetch(`http://localhost:8000/splits/get-patient?q=${search}`);
                const data = await response.json();

                this.resultPatients = data;
            }
        },
    }
</script>