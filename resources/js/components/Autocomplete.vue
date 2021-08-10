<template>
    <div>
        <input type="text"
               placeholder="Enter city"
               class="form-control"
               v-model="searchQuery"
               v-on:keyup="autocomplete">

        <div class="panel-footer1">

            <ul class="list-group" v-if="dataResults.length">
                <li class="list-group-item"
                    v-for="result in dataResults"
                    @click="loadWeather(result)">
                    {{ result.name }}, {{ result.country }}
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {

    data: function ()
    {
        return {
            searchQuery: 'Leuven, BE',
            dataResults: []
        }
    },

    created() {

        this.debounceAutocomplete = _.debounce(this.findCity, 800);
    },

    methods: {

        autocomplete()
        {
            this.debounceAutocomplete();
        },

        findCity()
        {
            this.dataResults = [];

            if (this.searchQuery.length > 2) {

                axios.get('/find-city/' + this.searchQuery).then(response => {
                    this.dataResults = response.data.items;
                });
            }
        },

        loadWeather(result)
        {
            this.searchQuery = result.name + ',' + result.country;
            this.dataResults = []

            // @todo igor: add vuex
            this.$emit('load-weather', result.longitude, result.latitude, 0, 'next')
        }
    },
}
</script>
