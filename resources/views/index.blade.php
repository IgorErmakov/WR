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


                <div class="weather-pagination" v-id="dataItems.length">

                    <a href="#"
                       @click="loadDays($event, 'prev')"
                       v-if="!isPrevBtnHidden">Prev days</a>
                    &nbsp;
                    &nbsp;
                    <a href="#"
                       @click="loadDays($event, 'next')"
                       v-if="!isNextBtnHidden">Next days</a>
                </div>


                <div id="days-list">
                    <singleday v-for="day in dataItems" v-bind:day="day"></singleday>
                </div>

                <div class="weather-pagination" v-id="dataItems.length">

                    <a href="#"
                       @click="loadDays($event, 'prev')"
                       v-if="!isPrevBtnHidden">Prev days</a>
                    &nbsp;
                    &nbsp;
                    <a href="#"
                       @click="loadDays($event, 'next')"
                       v-if="!isNextBtnHidden">Next days</a>
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
                   v-on:keyup="autocomplete">

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

        <div class="day-weather card">
            <div class="card-body" v-bind:class="{'is-today': day.isToday}">

                <h5 class="card-title">
                    <strong v-if="day.isToday">Today:</strong>
                    @{{ day.dayLabel }}
                </h5>

                <img v-bind:src="'/svg/weather/' + day.weatherImage + '.svg'"
                     width="64"
                     height="64"
                     class="card-img-top"
                />

                <p class="card-text">@{{ day.summary }}</p>
                <div class="card-text temp-cart">
                    <div class="day-temp">
                        Day: <p>@{{ day.dayTemp }}</p>
                    </div>
                    <div class="night-temp">
                        Night: <p>@{{ day.nightTemp }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="/js/skycons.js"></script>
    <script src="/js/components/autocomplete.js"></script>
    <script src="/js/components/singleday.js"></script>
    <script src="/js/main.js"></script>

@endsection