<template>
    <div class="day-weather card">
            <div class="card-body" v-bind:class="{'is-today': day.isToday}">

                <h5 class="card-title">
                    <strong v-if="day.isToday">Today:</strong>
                    {{ day.dayLabel }}
                </h5>

                <img v-bind:src="'/svg/weather/' + day.weatherImage + '.svg'"
                     width="64"
                     height="64"
                     class="card-img-top"
                     v-if="day.weatherImage"
                />

                <p class="card-text">{{ day.summary }}</p>

                <div class="card-text temp-cart">
                    <div class="day-temp">
                        Day: <p>{{ day.dayTemp }}</p>
                    </div>
                    <div class="night-temp">
                        Night: <p>{{ day.nightTemp }}</p>
                    </div>
                </div>
            </div>
        </div>
</template>

<script>
export default {
    props: ['day']
}
</script>