<script>
import axios from 'axios';
import tt from "@tomtom-international/web-sdk-maps"

export default {
    name: 'DayView',
    components: {
        Map
    },
    data() {
        return {
            day: {},
            stops: [],
            map: null
        }
    },
    methods: {
        async getSingleDay() {
            await axios.get('http://127.0.0.1:8000/api/day' + this.$route.params.id).then(response => {
                this.day = response.data.day[0]
                this.stops = response.data.stops
                // console.log(this.day, this.stops);
            })
        },
        addMap() {
            const map = tt.map({
                key: "koaCbZL6M2ThGOlvwAqsz9z3lopU60iG",
                container: "map",
            })

            return map
        },
        addMarkers(map) {
            this.stops.forEach(stop => {
                // console.log(stop.position_longitude, stop.position_latitude);
                let position = [stop.position_longitude, stop.position_latitude]
                let marker = new tt.Marker().setLngLat(position).addTo(map)
                let popup = new tt.Popup().setText(stop.name)
                marker.setPopup(popup)
            })
        }
    },
    async mounted() {
        this.addMap()
        await this.getSingleDay()
        this.addMarkers(this.addMap())
    }
}
</script>

<template>
    <section>
        <div class="row">
            <div class="col">
                <div>
                    <h2 v-if="day.title">
                        {{ day.title }}
                    </h2>
                    <h2 v-else>
                        {{ day.date }}
                    </h2>
                    <p>
                        {{ day.description }}
                    </p>
                </div>
                <template v-for="stop in stops">
                    <h5>{{ stop.name }} - <span>{{ stop.rating }}/5</span></h5>
                    <p>{{ stop.notes }}</p>
                </template>
            </div>
            <div class="col">
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