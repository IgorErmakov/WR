<template>
    <div class="weather-pagination">

        <a href="#"
           @click="loadDays($event, 'prev')"
           v-if="!isPrevBtnHidden">Prev days</a>

        &nbsp;
        &nbsp;

        <a href="#"
           @click="loadDays($event, 'next')"
           v-if="!isNextBtnHidden">Next days</a>
    </div>
</template>

<script>
export default {

    props: [
        'isPrevBtnHidden',
        'isNextBtnHidden'
    ],

    methods: {

        loadDays(event, direction) {
            this.$emit('load-days', event, direction)
        }
    },
}
</script>
