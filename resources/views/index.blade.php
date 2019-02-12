@extends('layouts/minimal')
@section('title', 'Weather lite')
@section('content')

    <div class="flex-center position-ref full-height" id="app">

        <div class="content">

            <div class="title m-b-md" id="app-title">
                Weather lite
            </div>

            <div class="panel panel-default" id="city-block">
                <div class="panel-heading">Please enter city name</div>
                <div class="panel-body">
                    <autocomplete></autocomplete>
                </div>
            </div>

            <div id="weather-data">

                <div id="days-list">
                    <singleday v-for="day in dayItems" v-bind:day="day"></singleday>
                </div>

                <div id="pagination">
                    <a href="#" click="loadDays($event, 'prev')">Prev 3 days</a>
                    |
                    <a href="#" click="loadDays($event, 'next')">Next 3 days</a>
                </div>

            </div>

        </div>

        <footer>
            <a href="https://darksky.net/poweredby/" target="_blank">Powered by Dark Sky</a>
        </footer>

    </div>

    <div id="autocomplete-tpl" style="display: none">

        <div>
            <input type="text"
                   placeholder="Enter city"
                   class="form-control"
                   v-model="searchQuery"
                   v-on:keyup="autoComplete">

            <div class="panel-footer1">

                <ul class="list-group" v-if="dataResults.length">
                    <li class="list-group-item"
                        v-for="result in dataResults"
                        @click="loadWeather(result)">
                        @{{ result.name }}, @{{ result.country }}
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div id="day-weather-tpl" style="display: none">

        <div class="day-weather card" style="width: 18rem;">
            <div class="card-body">

                <h5 class="card-title">@{{ day.dayLabel }}</h5>

                <img v-bind:src="day.weatherImage" class="card-img-top" alt="...">

                <p class="card-text">Day: @{{ day.dayTemp }}</p>

                <p class="card-text">Night: @{{ day.nightTemp }}</p>
            </div>
        </div>
    </div>


    <script type="text/javascript">

        function lg(name) {
            console.log(name)
        }

        Vue.component('autocomplete', {
            template: document.getElementById('autocomplete-tpl').innerHTML,

            data: function ()
            {
                return {
                    searchQuery: '',
                    dataResults: []
                }
            },

            methods: {
                autoComplete()
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


        Vue.component('singleday', {

            template: document.getElementById('day-weather-tpl').innerHTML,

            props: ['day'],

            // data: function ()
            // {
            //     return {
            //         day: this.day
            //     }
            // },

            mounted() {
                lg('m');
                lg(this.day)
            },
            methods: {
                // autoComplete()
                // {
                // },
                //
                // loadWeather(result)
                // {
                //     console.log(result.longitude)
                //     console.log(result.latitude)
                // }
            },
        })



        const app = new Vue({
            el: '#app',
            data() {
                return {
                    lastUsedLongitude: 0,
                    lastUsedLatitud: 0,
                    dayItems: [
                        {
                            dayLabel: 'label',
                            dayTemp: '31c',
                            nightTemp: '17C',
                            weatherImage: '',
                        },
                        {
                            dayLabel: 'label',
                            dayTemp: '31c',
                            nightTemp: '17C',
                            weatherImage: '',
                        },
                        {
                            dayLabel: 'label',
                            dayTemp: '31c',
                            nightTemp: '17C',
                            weatherImage: '',
                        }
                    ]
                }
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

                        response.data.days.forEach(itm => {
                            this.dataItems.push(itm)
                        })
                    });
                },

                loadDays(e, direction)
                {
                    if (this.dataItems.length) {

                        let day = 'prev' == direction ?
                            this.dataItems[0].day : // prior to "first day"
                            this.dataItems[2].day; // after "last day"

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

    </script>
@endsection