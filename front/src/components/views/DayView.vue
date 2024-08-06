<script>
import axios from 'axios';
import tt from "@tomtom-international/web-sdk-maps"
import { RouterLink } from 'vue-router';
import { store } from '../../store';
import UpdateDay from '../partials/UpdateDay.vue';
import DeleteDay from '../partials/DeleteDay.vue';
import DeleteStop from '../partials/DeleteStop.vue';

export default {
    name: 'DayView',
    components: {
        UpdateDay, DeleteDay, DeleteStop
    },
    data() {
        return {
            store,
            day: {},
            stops: [],
        }
    },
    methods: {
        async getSingleDay() {
            await axios.get(store.baseApiUrl + 'day' + this.$route.params.id).then(response => {
                this.day = response.data.day[0]
                this.stops = response.data.stops
                // console.log(this.day, this.stops);
            })
        },
        // map functions
        addMap() {
            const map = tt.map({
                key: store.mapKey,
                container: "map",
            })

            return map
        },
        addMarkers(map) {
            if (this.stops.length > 0) {

                let longitudes = []
                let latitudes = []

                this.stops.forEach(stop => {
                    // console.log(stop.position_longitude, stop.position_latitude);
                    longitudes.push(stop.position_longitude)
                    latitudes.push(stop.position_latitude)
                    let position = [stop.position_longitude, stop.position_latitude]
                    let marker = new tt.Marker().setLngLat(position).addTo(map)
                    let popup = new tt.Popup().setText(stop.name)
                    marker.setPopup(popup)
                })

                let ne = [Math.max(...longitudes), Math.max(...latitudes)]
                let sw = [Math.min(...longitudes), Math.min(...latitudes)]

                var llb = new tt.LngLatBounds(sw, ne)

                if (this.stops.length === 1) {
                    map.setZoom(8)
                    map.setCenter(ne)
                } else {
                    map.setZoom(0)
                    map.fitBounds(llb, {
                        padding: { top: 40, bottom: 10, left: 25, right: 25 }
                    })
                }
            }
        }
    },
    async mounted() {
        await this.getSingleDay()
        this.addMarkers(this.addMap())
    }
}
</script>

<template>
    <section id="day">
        <div class="container pt-4">
            <div class="row pt-4">
                <div class="col pt-4">
                    <!-- left col -->
                    <div class="d-flex justify-content-between">
                        <h2 class="w-50">
                            {{ day.date }}
                        </h2>
                        <h2 v-if="day.title">
                            {{ day.title }}
                        </h2>
                    </div>
                    <div class="d-flex justify-content-end">
                        <!-- update day offcanvas -->
                        <UpdateDay :day="day" @update-day="getSingleDay()" />

                        <!-- delete day modal -->
                        <DeleteDay :day="day" />
                    </div>
                    <p>
                        {{ day.description }}
                    </p>
                    <RouterLink :to="{ name: 'add-stop', params: { id: day.id, date: day.date } }">
                        <button class="btn btn-outline-light text-primary-emphasis" type="button">
                            <h2 class="m-0">
                                <div v-html="store.addIcon"></div>
                            </h2>
                        </button>
                    </RouterLink>

                    <template v-if="stops">
                        <template v-for="stop in stops">
                            <h5><i class="fa-solid fa-location-dot"></i> {{ stop.name }} </h5>
                            <div v-if="stop.rating"> - {{ stop.rating }}/5</div>
                            <p>{{ stop.notes }}</p>
                            <img :src="store.imageBaseUrl + stop.image" alt="">
                            <button class="btn">
                                <RouterLink :to="{ name: 'update-stop', params: { id: stop.id, date: day.date } }">
                                    <button class="btn btn-outline-light" type="button">
                                        <div v-html="store.editIcon"></div>
                                    </button>
                                </RouterLink>
                            </button>
                            <!-- delete stop modal -->
                            <DeleteStop :stop="stop" />

                        </template>
                    </template>
                </div>
                <div class="col">
                    <!-- right col -->
                    <div id='map'></div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped></style>