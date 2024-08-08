<script>
import axios from 'axios'
import { RouterLink } from 'vue-router';
import { store } from '../../store';
import NewTrip from '../partials/NewTrip.vue';
import UpdateTrip from '../partials/UpdateTrip.vue';
import DeleteTrip from '../partials/DeleteTrip.vue';
import NewDay from '../partials/NewDay.vue';

export default {
    name: 'Homeview',
    components: {
        NewTrip, UpdateTrip, DeleteTrip, NewDay,
    },
    data() {
        return {
            store,
            trips: [],
            singleTrip: null,
            singleTripDays: {},
            errors: {},
            loading: false,
        }
    },
    methods: {
        getTrips() {
            axios.get(store.baseApiUrl + 'trips').then(response => {
                this.trips = response.data.trips
                // console.log(this.trips);
            })
        },
        getSingleTrip(id) {
            axios.get(store.baseApiUrl + 'trips/' + id).then(response => {
                this.singleTrip = {}
                this.singleTrip = response.data.trip[0]
                this.singleTripDays = response.data.days
                // console.log(this.singleTrip, this.singleTripDays);
                let allCards = document.querySelectorAll('.trip')
                allCards.forEach(card => {
                    card.classList.remove('bg-info-subtle')
                    card.classList.remove('text-info')
                    card.classList.add('text-primary-emphasis')
                });

                let tripCard = document.getElementById(`trip-${id}`)
                tripCard.classList.add('bg-info-subtle')
                tripCard.classList.remove('text-primary-emphasis')
                tripCard.classList.add('text-info')
            })
        },
    },
    mounted() {
        this.getTrips()
    }
}
</script>

<template>
    <section id="home">
        <div class="container">
            <div class="row row-cols-1 row-cols-md-2">
                <!-- left col -->
                <div class="col mt-4">

                    <!-- left col -->
                    <template v-for="trip in trips">
                        <div class="trip card bg-light mb-3 text-primary-emphasis" :id="`trip-${trip.id}`"
                            @click="getSingleTrip(trip.id)">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <p class="card-text text-primary-emphasis">{{ trip.start_date }}</p>
                                    <div>
                                        <!-- update trip offcanvas -->
                                        <UpdateTrip :trip="trip" @update-trip="getTrips()" />

                                        <!-- delete trip modal -->
                                        <DeleteTrip :trip="trip" @delete-trip="getTrips()" />
                                    </div>
                                </div>
                                <h4 class="card-title">{{ trip.title }}</h4>
                            </div>
                        </div>

                        <!-- single trip col small -->
                        <div class="col my-4" id="single-trip-column-screen-sm">
                            <template v-if="singleTrip !== null">
                                <template v-if="trip.id === singleTrip.id">
                                    <div class="card text-info-emphasis bg-info-subtle"
                                        :id="`single-trip-${singleTrip.id}`">
                                        <div class="card-body">
                                            <h3 class="card-title">{{ singleTrip.title }}</h3>
                                            <div class="card-text">
                                                <div class="table-responsive">
                                                    <table
                                                        class="table table-striped table-hover table-borderless table-info align-middle">
                                                        <tbody>
                                                            <template v-for="day in singleTripDays">
                                                                <tr>
                                                                    <RouterLink
                                                                        :to="{ name: 'day', params: { id: day.id, date: day.date } }">
                                                                        <td class="text-primary-emphasis w-25 p-2">{{
                                                                            day.date }}
                                                                        </td>
                                                                        <td class="text-success" v-if="day.title">
                                                                            <strong>{{
                                                                                day.title
                                                                                }}</strong>
                                                                        </td>
                                                                    </RouterLink>
                                                                </tr>
                                                            </template>
                                                        </tbody>
                                                    </table>
                                                    <div class="d-flex justify-content-center">
                                                        <!-- new day offcanvas -->
                                                        <NewDay :singleTrip="singleTrip" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </template>
                        </div>
                    </template>

                    <!-- new trip offcanvas -->
                    <div class="d-flex justify-content-center mb-4">
                        <NewTrip @new-trip="getTrips()" />
                    </div>
                </div>
                <!-- right col -->
                <div class="col mt-4" id="single-trip-column-screen-lg">
                    <div v-if="singleTrip !== null">
                        <div class="card text-info-emphasis bg-info-subtle" :id="`single-trip-${singleTrip.id}`">
                            <div class="card-body">
                                <h3 class="card-title">{{ singleTrip.title }}</h3>
                                <div class="card-text">
                                    <div class="table-responsive">
                                        <table
                                            class="table table-striped table-hover table-borderless table-info align-middle">
                                            <tbody>
                                                <template v-for="day in singleTripDays">
                                                    <tr>
                                                        <RouterLink
                                                            :to="{ name: 'day', params: { id: day.id, date: day.date } }">
                                                            <td class="text-primary-emphasis w-25 p-2">{{ day.date }}
                                                            </td>
                                                            <td class="text-success" v-if="day.title"><strong>{{
                                                                day.title
                                                                    }}</strong>
                                                            </td>
                                                        </RouterLink>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                        <div class="d-flex justify-content-center">
                                            <!-- new day offcanvas -->
                                            <NewDay :singleTrip="singleTrip" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.card {
    box-shadow: 0px 0px 40px 10px rgba(0, 0, 0, 0.3);
}

@media screen and (min-width: 768px) {
    #single-trip-column-screen-sm {
        display: none
    }
}

@media screen and (max-width: 768px) {
    #single-trip-column-screen-lg {
        display: none
    }
}
</style>