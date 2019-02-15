Vue.component('autocomplete', {
    template: document.getElementById('autocomplete-tpl').innerHTML,

    data: function ()
    {
        return {
            searchQuery: 'Gomel',
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

                    console.log(response.data.items);
                    this.dataResults = response.data.items;
                });
            }
        },

        loadWeather(result)
        {
            this.searchQuery = result.name + ',' + result.country;
            this.dataResults = []

            app.loadWeather(result.longitude, result.latitude);
        }
    },
})