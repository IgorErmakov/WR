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

        // "Leuven" by default, cheers to Belgium :)
        this.loadWeather(
            "4.7",
            "50.883333",
            0,
            'next'
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

                if ('0' != day) {

                    if (response.data.items.length < 7) {

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

                let dayKey = 'prev' == direction ?
                    0 :
                    this.dataItems.length - 1;

                let day =  this.dataItems[dayKey].dateIso;

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