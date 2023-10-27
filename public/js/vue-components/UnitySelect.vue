<template>
    <select class="form-control" v-bind:value="value" v-on:input="$emit('input', $event.target.value)">
        <option v-for="unit in resultUnits" :value="unit.id">{{unit.NomeFantasia}}</option>
    </select>
</template>

<script>
    module.exports = {
        props: ['value'],
        data() {
            return {
                resultUnits: []
            }
        },
        methods: {
            async getUnits() {
                const response = await fetch(`http://localhost:8000/splits/get-all-units?tk=${localStorage.getItem("tk")}`);
                const data = await response.json();

                this.resultUnits = data;
            }
        },
        mounted() {
            this.getUnits();
        }
    }
</script>