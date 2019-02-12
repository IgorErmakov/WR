@extends('layouts/minimal')
@section('title', 'Weather lite')
@section('content')

    <div class="flex-center position-ref full-height" id="app">

        <div class="content">

            <div class="title m-b-md">
                Weather lite
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">Please enter city name</div>
                <div class="panel-body">
                    <autocomplete></autocomplete>
                </div>
            </div>

            <div id="weather-data">

                <singleday v-for="day in dayItems" v-bind:day="day"></singleday>

                <a href="#" click="loadPrevDays($event)">Prev 3 days</a>
                <a href="#" click="loadNextDays($event)">Next 3 days</a>

            </div>




        </div>
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

        <div>

            <div class="day-weather card" style="width: 18rem;">
                <img v-bind:href="day.weatherImage" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">@{{ day.dayLabel }}</h5>
                    <p class="card-text">@{{ day.dayTemp }}</p>
                    <p class="card-text">@{{ day.nightTemp }}</p>
                </div>
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
                    console.log(result.longitude)
                    console.log(result.latitude)

                    this.searchQuery = result.name + ',' + result.country;
                    this.dataResults = []

                    axios.get('/get-city-weather/' + result.longitude + '/' + result.latitude).then(response => {

                        response.data.days.forEach(itm => {
                            this.dataResults.push(itm)
                        })
                    });
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
                    dayItems: [
                        {
                            dayLabel: 'label',
                            dayTemp: '31c',
                            nightTemp: '17C',
                            weatherImage: '',
                        }
                    ]
                }
            },

            mounted() {

            },

            methods:  {

                loadPrevDays(e)
                {

                },
                loadNextDays(e)
                {

                },
            }
        });

    </script>
@endsection