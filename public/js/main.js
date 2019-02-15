function lg(name) {
    console.log(name)
}


// Main app
const app = new Vue({
    el: '#app',
    data() {
        return {
            lastUsedLongitude: 0,
            lastUsedLatitud: 0,
            isPrevBtnHidden: false,
            isNextBtnHidden: false,
            dataItems: []
        }
    },

    mounted() {
        this.loadWeather(
            "30.983333299999998",
            "52.4416667",
            0,
            '0'
        )
    },

    methods:  {

        loadWeather(longitude, latitude, day = '0', direction = '0')
        {
            this.dataItems = [];

            this.lastUsedLongitude = longitude;
            this.lastUsedLatitude  = latitude;

            let url = '/get-city-weather'
                + '/' + longitude
                + '/' + latitude
                + '/' + day
                + '/' + direction;

            axios.get(url).then(response => {

                let hidePrevBtn = false;
                let hideNextBtn = false;

                let maxEntriesCnt = 8;

                if ('0' != day) {

                    if (response.data.items.length < 8) {

                        if ('prev' == direction) {
                            hidePrevBtn = true;
                        } else {
                            hideNextBtn = true;
                        }
                    }
                }

                this.dataItems = response.data.items;

                this.isPrevBtnHidden = hidePrevBtn;
                this.isNextBtnHidden = hideNextBtn;
            });
        },

        loadDays(e, direction)
        {
            e.preventDefault();

            if (this.dataItems.length) {

                let lastDayKey = this.dataItems.length - 1;

                let day = 'prev' == direction ?
                    this.dataItems[0].dateIso : // prior to "first day"
                    this.dataItems[lastDayKey].dateIso; // after "last day"

                this.loadWeather(
                    this.lastUsedLongitude,
                    this.lastUsedLatitude,
                    day,
                    direction
                );
            }
        },
    }
});