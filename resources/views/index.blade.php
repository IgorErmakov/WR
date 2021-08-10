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
                    <Autocomplete v-on:load-weather="loadWeather"></Autocomplete>
                </div>
            </div>

            <div id="weather-data">

                <Pagination v-if="dataItems.length"
                            v-bind:isPrevBtnHidden=isPrevBtnHidden
                            v-bind:isNextBtnHidden=isNextBtnHidden
                            v-on:load-days="loadDays"
                >
                </Pagination>

                <div id="days-list">
                    <Singleday v-for="day in dataItems" v-bind:day="day"></Singleday>
                </div>

                <Pagination v-if="dataItems.length"
                            v-bind:isPrevBtnHidden=isPrevBtnHidden
                            v-bind:isNextBtnHidden=isNextBtnHidden
                            v-on:load-days="loadDays"
                >
                </Pagination>

            </div>

        </div>

        <footer>
            <a href="https://darksky.net/poweredby/" target="_blank">Powered by Dark Sky</a>
        </footer>

    </div>


    <script src="/js/app.js"></script>

@endsection