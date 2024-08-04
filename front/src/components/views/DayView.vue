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
            if (this.stops.length !== 0) {

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

                map.setZoom(-2)
                map.fitBounds(llb, {
                    padding: { top: 25, bottom: 25, left: 25, right: 25 }
                })
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
    <section>
        <div class="row">
            <div class="col">
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
                <RouterLink :to="{ name: 'add-stop', params: { id: day.id, date: day.date } }">Add a stop</RouterLink>
                <template v-if="stops">
                    <template v-for="stop in stops">
                        <h5>{{ stop.name }} <span v-if="stop.rating"> - {{ stop.rating }}/5</span></h5>
                        <p>{{ stop.notes }}</p>
                        <RouterLink :to="{ name: 'update-stop', params: { id: stop.id, date: day.date } }">U
                        </RouterLink>
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
    </section>
</template>

<style scoped>
#map {
    width: 100%;
    height: 75vh;
    margin: 1rem auto 0;
}
</style>